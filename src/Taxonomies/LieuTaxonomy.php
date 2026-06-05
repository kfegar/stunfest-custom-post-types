<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class LieuTaxonomy {

    public function register(): void {
        register_taxonomy( 'lieu', 'evenement', [
            'labels'       => [
                'name'          => __( 'Lieux', 'stunfest-cpt' ),
                'singular_name' => __( 'Lieu', 'stunfest-cpt' ),
                'menu_name'     => __( 'Lieux', 'stunfest-cpt' ),
                'all_items'     => __( 'Tous les lieux', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier le lieu', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter un lieu', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des lieux', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucun lieu trouvé', 'stunfest-cpt' ),
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'lieu' ],
            'show_in_rest' => true,
        ] );
    }
}
