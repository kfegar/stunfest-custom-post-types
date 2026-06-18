<?php

namespace Stunfest\CustomPostTypes\Fields;

class ThematiqueFields {

    public function register(): void {
        acf_add_local_field_group( [
            'key'                   => 'group_stunfest_thematique',
            'title'                 => __( 'Détails de la thématique', 'stunfest-cpt' ),
            'fields'                => [
                [
                    'key'           => 'field_stunfest_thematique_icone',
                    'label'         => __( 'Icône', 'stunfest-cpt' ),
                    'name'          => 'icone',
                    'type'          => 'image',
                    'return_format' => 'url',
                    'preview_size'  => 'thumbnail',
                ],
            ],
            'location'              => [
                [
                    [
                        'param'    => 'taxonomy',
                        'operator' => '==',
                        'value'    => 'thematique',
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
