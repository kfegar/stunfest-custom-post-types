<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class GenreTaxonomy {

    public function register(): void {
        register_taxonomy( 'genre', 'indiegame', [
            'labels'       => [
                'name'          => __( 'Genres', 'stunfest-cpt' ),
                'singular_name' => __( 'Genre', 'stunfest-cpt' ),
                'menu_name'     => __( 'Genres', 'stunfest-cpt' ),
                'all_items'     => __( 'Tous les genres', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier le genre', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter un genre', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des genres', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucun genre trouvé', 'stunfest-cpt' ),
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'genre' ],
            'show_in_rest' => true,
        ] );
    }
}
