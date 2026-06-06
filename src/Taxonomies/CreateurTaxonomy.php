<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class CreateurTaxonomy {

    public function register(): void {
        register_taxonomy( 'createur', 'indiegame', [
            'labels'       => [
                'name'          => __( 'Créateurs', 'stunfest-cpt' ),
                'singular_name' => __( 'Créateur', 'stunfest-cpt' ),
                'menu_name'     => __( 'Créateurs', 'stunfest-cpt' ),
                'all_items'     => __( 'Tous les créateurs', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier le créateur', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter un créateur', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des créateurs', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucun créateur trouvé', 'stunfest-cpt' ),
            ],
            'hierarchical' => false,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'createur' ],
            'show_in_rest' => true,
        ] );
    }
}
