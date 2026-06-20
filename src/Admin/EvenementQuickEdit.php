<?php

namespace Stunfest\CustomPostTypes\Admin;

class EvenementQuickEdit {

    private const POST_TYPE = 'evenement';

    /** Colonne (déjà présente, cf. EvenementColumns) servant d'ancrage pour un rendu unique par ligne. */
    private const ANCHOR_COLUMN = 'col_createurs';

    private const NONCE_ACTION = 'stunfest_evenement_quick_edit';
    private const NONCE_FIELD  = 'stunfest_evenement_quick_edit_nonce';

    /** @var array<string, array{label: string, type: string, key: string}> */
    private array $fields;

    public function __construct() {
        $this->fields = [
            'createurs'     => [
                'label' => __( 'Créateur(s)', 'stunfest-cpt' ),
                'type'  => 'text',
                'key'   => 'field_stunfest_evenement_createurs',
            ],
            'description'   => [
                'label' => __( 'Description', 'stunfest-cpt' ),
                'type'  => 'textarea',
                'key'   => 'field_stunfest_evenement_description',
            ],
            'contact'       => [
                'label' => __( 'Contact', 'stunfest-cpt' ),
                'type'  => 'text',
                'key'   => 'field_stunfest_evenement_contact',
            ],
            'heure_debut'   => [
                'label' => __( 'Heure de début', 'stunfest-cpt' ),
                'type'  => 'time',
                'key'   => 'field_stunfest_evenement_heure_debut',
            ],
            'heure_fin'     => [
                'label' => __( 'Heure de fin', 'stunfest-cpt' ),
                'type'  => 'time',
                'key'   => 'field_stunfest_evenement_heure_fin',
            ],
            'mise_en_avant' => [
                'label' => __( 'Mise en avant', 'stunfest-cpt' ),
                'type'  => 'checkbox',
                'key'   => 'field_stunfest_evenement_mise_en_avant',
            ],
        ];
    }

    public function register(): void {
        add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', $this->renderRowData( ... ), 20, 2 );
        add_action( 'quick_edit_custom_box', $this->renderQuickEditFields( ... ), 10, 2 );
        add_action( 'save_post_' . self::POST_TYPE, $this->save( ... ) );
        add_action( 'admin_enqueue_scripts', $this->enqueue( ... ) );
    }

    /**
     * Émet, une seule fois par ligne, les valeurs brutes des champs (JSON caché)
     * que le JS lira pour pré-remplir le formulaire de modification rapide.
     */
    public function renderRowData( string $column, int $post_id ): void {
        if ( self::ANCHOR_COLUMN !== $column ) {
            return;
        }

        $data = [];

        foreach ( $this->fields as $name => $config ) {
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
        if ( self::POST_TYPE !== $post_type || self::ANCHOR_COLUMN !== $column ) {
            return;
        }

        wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <span class="title"><?php esc_html_e( "Détails de l'évènement", 'stunfest-cpt' ); ?></span>
                <?php foreach ( $this->fields as $name => $config ) : ?>
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
        if ( ! isset( $_POST[ self::NONCE_FIELD ] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ), self::NONCE_ACTION ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        foreach ( $this->fields as $name => $config ) {
            $input_name = 'stunfest_qe_' . $name;

            if ( 'checkbox' === $config['type'] ) {
                $value = isset( $_POST[ $input_name ] ) ? 1 : 0;
            } else {
                if ( ! isset( $_POST[ $input_name ] ) ) {
                    continue;
                }

                $raw = wp_unslash( $_POST[ $input_name ] );

                $value = 'textarea' === $config['type']
                    ? sanitize_textarea_field( $raw )
                    : sanitize_text_field( $raw );
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

        if ( ! $screen instanceof \WP_Screen || self::POST_TYPE !== $screen->post_type ) {
            return;
        }

        wp_enqueue_script(
            'stunfest-evenement-quick-edit',
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

        return is_string( $value ) ? $value : '';
    }
}
