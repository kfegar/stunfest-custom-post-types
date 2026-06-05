<?php

namespace Stunfest\CustomPostTypes\Taxonomies;

class JourTaxonomy {

    public function register(): void {
        register_taxonomy( 'jour', 'evenement', [
            'labels'       => [
                'name'          => __( 'Jours', 'stunfest-cpt' ),
                'singular_name' => __( 'Jour', 'stunfest-cpt' ),
                'menu_name'     => __( 'Jours', 'stunfest-cpt' ),
                'all_items'     => __( 'Tous les jours', 'stunfest-cpt' ),
                'edit_item'     => __( 'Modifier le jour', 'stunfest-cpt' ),
                'add_new_item'  => __( 'Ajouter un jour', 'stunfest-cpt' ),
                'search_items'  => __( 'Rechercher des jours', 'stunfest-cpt' ),
                'not_found'     => __( 'Aucun jour trouvé', 'stunfest-cpt' ),
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'jour' ],
            'show_in_rest' => true,
        ] );
    }
}
