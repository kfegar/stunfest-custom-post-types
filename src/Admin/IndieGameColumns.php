<?php

namespace Stunfest\CustomPostTypes\Admin;

class IndieGameColumns {

    private const META_COLUMNS = [ 'platform', 'site_web', 'description' ];
    private const TAX_COLUMNS  = [ 'studio', 'genre', 'createur' ];

    public function register(): void {
        add_filter( 'manage_indiegame_posts_columns', $this->defineColumns( ... ) );
        add_action( 'manage_indiegame_posts_custom_column', $this->renderColumn( ... ), 10, 2 );
        add_filter( 'manage_edit-indiegame_sortable_columns', $this->sortableColumns( ... ) );
        add_action( 'pre_get_posts', $this->handleMetaSort( ... ) );
        add_filter( 'posts_clauses', $this->handleTaxSort( ... ), 10, 2 );
    }

    private function defineColumns( array $columns ): array {
        $date = $columns['date'] ?? null;
        unset( $columns['date'] );

        $columns['col_studio']      = __( 'Studio', 'stunfest-cpt' );
        $columns['col_genre']       = __( 'Genre', 'stunfest-cpt' );
        $columns['col_createur']    = __( 'Créateur', 'stunfest-cpt' );
        $columns['col_platform']    = __( 'Plateforme', 'stunfest-cpt' );
        $columns['col_site_web']    = __( 'Site web', 'stunfest-cpt' );
        $columns['col_description'] = __( 'Description', 'stunfest-cpt' );

        if ( $date !== null ) {
            $columns['date'] = $date;
        }

        return $columns;
    }

    private function renderColumn( string $column, int $post_id ): void {
        match ( $column ) {
            'col_studio'      => $this->renderTaxonomyColumn( $post_id, 'studio' ),
            'col_genre'       => $this->renderTaxonomyColumn( $post_id, 'genre' ),
            'col_createur'    => $this->renderTaxonomyColumn( $post_id, 'createur' ),
            'col_platform'    => $this->renderMetaColumn( $post_id, 'platform' ),
            'col_site_web'    => $this->renderMetaColumn( $post_id, 'site_web' ),
            'col_description' => $this->renderDescriptionColumn( $post_id ),
            default           => null,
        };
    }

    private function sortableColumns( array $columns ): array {
        $columns['col_studio']      = 'studio';
        $columns['col_genre']       = 'genre';
        $columns['col_createur']    = 'createur';
        $columns['col_platform']    = 'platform';
        $columns['col_site_web']    = 'site_web';
        $columns['col_description'] = 'description';

        return $columns;
    }

    private function handleMetaSort( \WP_Query $query ): void {
        if ( ! is_admin() || ! $query->is_main_query() ) return;
        if ( $query->get( 'post_type' ) !== 'indiegame' ) return;

        $orderby = $query->get( 'orderby' );

        if ( in_array( $orderby, self::META_COLUMNS, true ) ) {
            $query->set( 'meta_key', $orderby );
            $query->set( 'orderby', 'meta_value' );
        }
    }

    private function handleTaxSort( array $pieces, \WP_Query $query ): array {
        global $wpdb;

        if ( ! is_admin() || ! $query->is_main_query() ) return $pieces;
        if ( $query->get( 'post_type' ) !== 'indiegame' ) return $pieces;

        $orderby = $query->get( 'orderby' );
        $order   = strtoupper( $query->get( 'order', 'ASC' ) );

        if ( ! in_array( $orderby, self::TAX_COLUMNS, true ) ) return $pieces;

        $pieces['join'] .= "
            LEFT JOIN {$wpdb->term_relationships} tr_sort
                ON {$wpdb->posts}.ID = tr_sort.object_id
            LEFT JOIN {$wpdb->term_taxonomy} tt_sort
                ON tr_sort.term_taxonomy_id = tt_sort.term_taxonomy_id
                AND tt_sort.taxonomy = '{$orderby}'
            LEFT JOIN {$wpdb->terms} t_sort
                ON tt_sort.term_id = t_sort.term_id
        ";
        $pieces['orderby'] = "t_sort.name {$order}";
        $pieces['groupby'] = "{$wpdb->posts}.ID";

        return $pieces;
    }

    private function renderTaxonomyColumn( int $post_id, string $taxonomy ): void {
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            echo '—';
            return;
        }

        $links = array_map( function ( \WP_Term $term ) use ( $taxonomy ): string {
            $url = add_query_arg( [
                'post_type' => 'indiegame',
                $taxonomy   => $term->slug,
            ], admin_url( 'edit.php' ) );

            return '<a href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a>';
        }, $terms );

        echo implode( ', ', $links );
    }

    private function renderDescriptionColumn( int $post_id ): void {
        $value = function_exists( 'get_field' ) ? get_field( 'description', $post_id ) : get_post_meta( $post_id, 'description', true );

        echo $value ? esc_html( wp_trim_words( $value, 10, '…' ) ) : '—';
    }

    private function renderMetaColumn( int $post_id, string $field_name ): void {
        $value = function_exists( 'get_field' ) ? get_field( $field_name, $post_id ) : get_post_meta( $post_id, $field_name, true );

        echo $value ? esc_html( $value ) : '—';
    }
}
