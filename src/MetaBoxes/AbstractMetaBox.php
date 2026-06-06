<?php

namespace Stunfest\CustomPostTypes\MetaBoxes;

abstract class AbstractMetaBox {

    abstract protected function id(): string;
    abstract protected function title(): string;
    abstract protected function post_type(): string;

    public function register(): void {
        add_action( 'add_meta_boxes', [ $this, 'add' ] );
        add_action( 'save_post', [ $this, 'save' ] );
    }

    public function add(): void {
        add_meta_box(
            $this->id(),
            $this->title(),
            [ $this, 'render' ],
            $this->post_type(),
            'normal',
            'high'
        );
    }

    abstract public function render( \WP_Post $post ): void;

    abstract public function save( int $post_id ): void;

    protected function verify_nonce( string $nonce_action ): bool {
        $nonce_field = $this->id() . '_nonce';

        if ( ! isset( $_POST[ $nonce_field ] ) ) return false;
        if ( ! wp_verify_nonce( $_POST[ $nonce_field ], $nonce_action ) ) return false;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
        if ( ! current_user_can( 'edit_post', get_the_ID() ) ) return false;

        return true;
    }

    protected function nonce_field( string $nonce_action ): void {
        wp_nonce_field( $nonce_action, $this->id() . '_nonce' );
    }

    protected function sanitize( string $type, mixed $value ): string {
        return match ( $type ) {
            'url'      => esc_url_raw( $value ),
            'textarea' => sanitize_textarea_field( $value ),
            'number'   => (string) intval( $value ),
            default    => sanitize_text_field( $value ),
        };
    }
}
