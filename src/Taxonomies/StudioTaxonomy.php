<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class StudioTaxonomy {

    public function register(): void {
        register_taxonomy( 'studio', 'indiegame', [
            'labels'       => [
                'name'          => __( 'Studios', 'stunfest-cpt' ),
                'singular_name' => __( 'Studio', 'stunfest-cpt' ),
                'menu_name'     => __( 'Studios', 'stunfest-cpt' ),
                'all_items'     => __( 'Tous les studios', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier le studio', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter un studio', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des studios', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucun studio trouvé', 'stunfest-cpt' ),
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'studio' ],
            'show_in_rest' => true,
        ] );
    }
}
