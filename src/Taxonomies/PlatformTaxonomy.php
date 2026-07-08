<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class PlatformTaxonomy {

    public function register(): void {
        register_taxonomy( 'platform', 'indiegame', [
            'labels'       => [
                'name'          => __( 'Plateformes', 'stunfest-cpt' ),
                'singular_name' => __( 'Plateforme', 'stunfest-cpt' ),
                'menu_name'     => __( 'Plateformes', 'stunfest-cpt' ),
                'all_items'     => __( 'Toutes les plateformes', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier la plateforme', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter une plateforme', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des plateformes', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucune plateforme trouvée', 'stunfest-cpt' ),
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'plateforme' ],
            'show_in_rest' => true,
        ] );
    }
}
