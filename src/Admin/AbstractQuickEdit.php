<?php

namespace Stunfest\CustomPostTypes\Admin;

/**
 * Expose des champs ACF dans la modification rapide (Quick Edit) d'un post type.
 *
 * Les valeurs brutes sont injectées par ligne (JSON caché) puis réinjectées
 * dans le formulaire par assets/js/quick-edit.js à l'ouverture.
 */
abstract class AbstractQuickEdit {

    /** Post type ciblé (ex. 'evenement'). */
    abstract protected function postType(): string;

    /** Colonne custom existante servant d'ancrage pour un rendu unique par ligne. */
    abstract protected function anchorColumn(): string;

    /**
     * Champs à exposer.
     *
     * @return array<string, array{label: string, type: string, key: string}>
     *         name => [ label, type (text|url|textarea|time|checkbox), clé ACF ]
     */
    abstract protected function fields(): array;

    protected function fieldsetTitle(): string {
        return __( 'Détails', 'stunfest-cpt' );
    }

    public function register(): void {
        add_action( 'manage_' . $this->postType() . '_posts_custom_column', $this->renderRowData( ... ), 20, 2 );
        add_action( 'quick_edit_custom_box', $this->renderQuickEditFields( ... ), 10, 2 );
        add_action( 'save_post_' . $this->postType(), $this->save( ... ) );
        add_action( 'admin_enqueue_scripts', $this->enqueue( ... ) );
    }

    private function nonceAction(): string {
        return 'stunfest_quick_edit_' . $this->postType();
    }

    private function nonceField(): string {
        return 'stunfest_quick_edit_' . $this->postType() . '_nonce';
    }

    /**
     * Émet, une seule fois par ligne, les valeurs brutes des champs (JSON caché).
     */
    public function renderRowData( string $column, int $post_id ): void {
        if ( $this->anchorColumn() !== $column ) {
            return;
        }

        $data = [];

        foreach ( $this->fields() as $name => $config ) {
            $data[ $name ] = $this->getValue( $post_id, $name, $config['type'] );
        }

        printf(
            '<div class="stunfest-qe-data" id="stunfest-qe-%d" style="display:none">%s</div>',
            $post_id,
            esc_html( (string) wp_json_encode( $data ) )
        );
    }

    /**
     * Rend les champs dans le formulaire de modification rapide (une seule fois).
     */
    public function renderQuickEditFields( string $column, string $post_type ): void {
        if ( $this->postType() !== $post_type || $this->anchorColumn() !== $column ) {
            return;
        }

        wp_nonce_field( $this->nonceAction(), $this->nonceField() );
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <span class="title"><?php echo esc_html( $this->fieldsetTitle() ); ?></span>
                <?php foreach ( $this->fields() as $name => $config ) : ?>
                    <?php $input_id = 'stunfest_qe_' . $name; ?>
                    <?php if ( 'checkbox' === $config['type'] ) : ?>
                        <label class="alignleft" for="<?php echo esc_attr( $input_id ); ?>">
                            <input type="checkbox" name="<?php echo esc_attr( $input_id ); ?>" id="<?php echo esc_attr( $input_id ); ?>" value="1">
                            <span class="checkbox-title"><?php echo esc_html( $config['label'] ); ?></span>
                        </label>
                    <?php else : ?>
                        <label class="inline-edit-group">
                            <span class="title"><?php echo esc_html( $config['label'] ); ?></span>
                            <?php if ( 'textarea' === $config['type'] ) : ?>
                                <textarea name="<?php echo esc_attr( $input_id ); ?>" rows="3" cols="22"></textarea>
                            <?php elseif ( 'time' === $config['type'] ) : ?>
                                <input type="time" name="<?php echo esc_attr( $input_id ); ?>" value="">
                            <?php elseif ( 'url' === $config['type'] ) : ?>
                                <input type="url" name="<?php echo esc_attr( $input_id ); ?>" value="">
                            <?php else : ?>
                                <input type="text" name="<?php echo esc_attr( $input_id ); ?>" value="">
                            <?php endif; ?>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </fieldset>
        <?php
    }

    public function save( int $post_id ): void {
        if ( ! isset( $_POST[ $this->nonceField() ] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ $this->nonceField() ] ) ), $this->nonceAction() ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        foreach ( $this->fields() as $name => $config ) {
            $input_name = 'stunfest_qe_' . $name;

            if ( 'checkbox' === $config['type'] ) {
                $value = isset( $_POST[ $input_name ] ) ? 1 : 0;
            } else {
                if ( ! isset( $_POST[ $input_name ] ) ) {
                    continue;
                }

                $raw = wp_unslash( $_POST[ $input_name ] );

                $value = match ( $config['type'] ) {
                    'textarea' => sanitize_textarea_field( $raw ),
                    'url'      => esc_url_raw( $raw ),
                    default    => sanitize_text_field( $raw ),
                };
            }

            if ( function_exists( 'update_field' ) ) {
                update_field( $config['key'], $value, $post_id );
            } else {
                update_post_meta( $post_id, $name, $value );
            }
        }
    }

    public function enqueue( string $hook ): void {
        if ( 'edit.php' !== $hook ) {
            return;
        }

        $screen = get_current_screen();

        if ( ! $screen instanceof \WP_Screen || $this->postType() !== $screen->post_type ) {
            return;
        }

        wp_enqueue_script(
            'stunfest-quick-edit',
            plugins_url( 'assets/js/quick-edit.js', STUNFEST_CPT_FILE ),
            [ 'jquery', 'inline-edit-post' ],
            '0.1.0',
            true
        );
    }

    private function getValue( int $post_id, string $name, string $type ): string {
        $value = function_exists( 'get_field' )
            ? get_field( $name, $post_id )
            : get_post_meta( $post_id, $name, true );

        if ( 'checkbox' === $type ) {
            return $value ? '1' : '0';
        }

        return is_scalar( $value ) ? (string) $value : '';
    }
}
