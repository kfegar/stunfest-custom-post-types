<?php

namespace Stunfest\CustomPostTypes\PostTypes;

class EvenementPostType {

    public function register(): void {
        register_post_type( 'evenement', [
            'labels'       => [
                'name'               => __( 'Évènements', 'stunfest-cpt' ),
                'singular_name'      => __( 'Évènement', 'stunfest-cpt' ),
                'menu_name'          => __( 'Évènements', 'stunfest-cpt' ),
                'add_new'            => __( 'Ajouter', 'stunfest-cpt' ),
                'add_new_item'       => __( 'Ajouter un évènement', 'stunfest-cpt' ),
                'edit_item'          => __( "Modifier l'évènement", 'stunfest-cpt' ),
                'new_item'           => __( 'Nouvel évènement', 'stunfest-cpt' ),
                'view_item'          => __( "Voir l'évènement", 'stunfest-cpt' ),
                'search_items'       => __( 'Rechercher des évènements', 'stunfest-cpt' ),
                'not_found'          => __( 'Aucun évènement trouvé', 'stunfest-cpt' ),
                'not_found_in_trash' => __( 'Aucun évènement dans la corbeille', 'stunfest-cpt' ),
            ],
            'public'       => true,
            'has_archive'  => true,
            'supports'     => [ 'title', 'editor', 'thumbnail' ],
            'menu_icon'    => 'dashicons-calendar-alt',
            'rewrite'      => [ 'slug' => 'evenement' ],
            'show_in_rest' => true,
        ] );
    }
}
