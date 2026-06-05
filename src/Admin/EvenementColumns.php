<?php

namespace Stunfest\CustomPostTypes\Admin;

class EvenementColumns {

    public function register(): void {
        add_filter( 'manage_evenement_posts_columns', $this->defineColumns( ... ) );
        add_action( 'manage_evenement_posts_custom_column', $this->renderColumn( ... ), 10, 2 );
        add_filter( 'manage_edit-evenement_sortable_columns', $this->sortableColumns( ... ) );
    }

    private function defineColumns( array $columns ): array {
        $date = $columns['date'] ?? null;
        unset( $columns['date'] );

        $columns['col_jour']        = __( 'Jour', 'stunfest-cpt' );
        $columns['col_lieu']        = __( 'Lieu', 'stunfest-cpt' );
        $columns['col_thematique']  = __( 'Thématique', 'stunfest-cpt' );
        $columns['col_createurs']   = __( 'Créateur(s)', 'stunfest-cpt' );
        $columns['col_contact']     = __( 'Contact', 'stunfest-cpt' );
        $columns['col_description'] = __( 'Description', 'stunfest-cpt' );
        $columns['col_heure_debut'] = __( 'Début', 'stunfest-cpt' );
        $columns['col_heure_fin']   = __( 'Fin', 'stunfest-cpt' );

        if ( $date !== null ) {
            $columns['date'] = $date;
        }

        return $columns;
    }

    private function renderColumn( string $column, int $post_id ): void {
        match ( $column ) {
            'col_jour'        => $this->renderTaxonomyColumn( $post_id, 'jour' ),
            'col_lieu'        => $this->renderTaxonomyColumn( $post_id, 'lieu' ),
            'col_thematique'  => $this->renderTaxonomyColumn( $post_id, 'thematique' ),
            'col_createurs'   => $this->renderMetaColumn( $post_id, 'createurs' ),
            'col_contact'     => $this->renderMetaColumn( $post_id, 'contact' ),
            'col_description' => $this->renderDescriptionColumn( $post_id ),
            'col_heure_debut' => $this->renderMetaColumn( $post_id, 'heure_debut' ),
            'col_heure_fin'   => $this->renderMetaColumn( $post_id, 'heure_fin' ),
            default           => null,
        };
    }

    private function sortableColumns( array $columns ): array {
        $columns['col_jour'] = 'jour';
        $columns['col_lieu'] = 'lieu';
        $columns['col_thematique'] = 'thematique';
        $columns['col_createurs']   = 'createurs';
        $columns['col_contact'] = 'contact';
        $columns['col_heure_debut'] = 'heure_debut';
        $columns['col_heure_fin'] = 'heure_fin';


        return $columns;
    }

    private function renderTaxonomyColumn( int $post_id, string $taxonomy ): void {
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            echo '—';
            return;
        }

        $links = array_map( function ( \WP_Term $term ) use ( $taxonomy ): string {
            $url = add_query_arg( [
                'post_type' => 'evenement',
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
