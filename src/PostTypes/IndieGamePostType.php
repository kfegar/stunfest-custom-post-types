<?php

namespace Stunfest\CustomPostTypes\PostTypes;

class IndieGamePostType {

    public function register(): void {
        register_post_type( 'indiegame', [
            'labels'       => [
                'name'               => __( 'Jeux indépendants', 'stunfest-cpt' ),
                'singular_name'      => __( 'Jeu indépendant', 'stunfest-cpt' ),
                'menu_name'          => __( 'Jeux indés', 'stunfest-cpt' ),
                'add_new'            => __( 'Ajouter', 'stunfest-cpt' ),
                'add_new_item'       => __( 'Ajouter un jeu', 'stunfest-cpt' ),
                'edit_item'          => __( 'Modifier le jeu', 'stunfest-cpt' ),
                'new_item'           => __( 'Nouveau jeu', 'stunfest-cpt' ),
                'view_item'          => __( 'Voir le jeu', 'stunfest-cpt' ),
                'search_items'       => __( 'Rechercher des jeux', 'stunfest-cpt' ),
                'not_found'          => __( 'Aucun jeu trouvé', 'stunfest-cpt' ),
                'not_found_in_trash' => __( 'Aucun jeu dans la corbeille', 'stunfest-cpt' ),
            ],
            'public'       => true,
            'has_archive'  => true,
            'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'menu_icon'    => 'dashicons-games',
            'rewrite'      => [ 'slug' => 'indiegame' ],
            'show_in_rest' => true,
        ] );
    }
}
