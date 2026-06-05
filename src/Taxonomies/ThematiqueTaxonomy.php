<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class ThematiqueTaxonomy {

    public function register(): void {
        register_taxonomy( 'thematique', 'evenement', [
            'labels'       => [
                'name'          => __( 'Thématiques', 'stunfest-cpt' ),
                'singular_name' => __( 'Thématique', 'stunfest-cpt' ),
                'menu_name'     => __( 'Thématiques', 'stunfest-cpt' ),
                'all_items'     => __( 'Toutes les thématiques', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier la thématique', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter une thématique', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des thématiques', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucune thématique trouvée', 'stunfest-cpt' ),
            ],
            'hierarchical' => false,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'thematique' ],
            'show_in_rest' => true,
        ] );
    }
}
