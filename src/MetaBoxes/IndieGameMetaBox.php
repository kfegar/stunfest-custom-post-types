<?php

namespace Stunfest\CustomPostTypes\MetaBoxes;

class IndieGameMetaBox extends AbstractMetaBox {

    private const NONCE_ACTION = 'indiegame_details_save';

    private array $fields = [
        '_indiegame_platform' => [ 'label' => 'Plateforme', 'type' => 'text' ],
        '_indiegame_url'      => [ 'label' => 'Site web',   'type' => 'url' ],
        '_indiegame_desc'     => [ 'label' => 'Description','type' => 'textarea' ],
    ];

    protected function id(): string {
        return 'indiegame_details';
    }

    protected function title(): string {
        return __( 'Détails du jeu', 'stunfest-cpt' );
    }

    protected function post_type(): string {
        return 'indiegame';
    }

    public function render( \WP_Post $post ): void {
        $this->nonce_field( self::NONCE_ACTION );

        foreach ( $this->fields as $key => $field ) {
            $value = get_post_meta( $post->ID, $key, true );
            $label = esc_html( $field['label'] );
            $id    = esc_attr( $key );
            $val   = esc_attr( $value );

            echo "<p><label for=\"{$id}\"><strong>{$label}</strong></label></p>";

            if ( $field['type'] === 'textarea' ) {
                echo "<textarea id=\"{$id}\" name=\"{$id}\" rows=\"4\" style=\"width:100%\">" . esc_textarea( $value ) . '</textarea>';
            } else {
                $type = esc_attr( $field['type'] );
                echo "<input type=\"{$type}\" id=\"{$id}\" name=\"{$id}\" value=\"{$val}\" style=\"width:100%\">";
            }
        }
    }

    public function save( int $post_id ): void {
        if ( ! $this->verify_nonce( self::NONCE_ACTION ) ) return;

        foreach ( $this->fields as $key => $field ) {
            if ( ! isset( $_POST[ $key ] ) ) continue;

            update_post_meta(
                $post_id,
                $key,
                $this->sanitize( $field['type'], $_POST[ $key ] )
            );
        }
    }
}
