<?php

namespace Stunfest\CustomPostTypes\Fields;

class IndieGameFields {

    public function register(): void {
        acf_add_local_field_group( [
            'key'                   => 'group_stunfest_indiegame',
            'title'                 => __( 'Détails du jeu', 'stunfest-cpt' ),
            'fields'                => [
                [
                    'key'   => 'field_stunfest_indiegame_year',
                    'label' => __( 'Année', 'stunfest-cpt' ),
                    'name'  => 'annee',
                    'type'  => 'number',
                    'min'   => 1970,
                    'max'   => 2100,
                    'step'  => 1,
                ],
                [
                    'key'   => 'field_stunfest_indiegame_url',
                    'label' => __( 'Site web', 'stunfest-cpt' ),
                    'name'  => 'site_web',
                    'type'  => 'url',
                ],
                [
                    'key'   => 'field_stunfest_indiegame_description',
                    'label' => __( 'Description', 'stunfest-cpt' ),
                    'name'  => 'description',
                    'type'  => 'textarea',
                    'rows'  => 6,
                ],
            ],
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'indiegame',
                    ],
                ],
            ],
            'menu_order'            => 0,
            'position'              => 'normal',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'show_in_rest'          => 1,
        ] );
    }
}
