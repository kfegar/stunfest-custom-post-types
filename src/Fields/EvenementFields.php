<?php

namespace Stunfest\CustomPostTypes\Fields;

class EvenementFields {

    public function register(): void {
        acf_add_local_field_group( [
            'key'                   => 'group_stunfest_evenement',
            'title'                 => __( "Détails de l'évènement", 'stunfest-cpt' ),
            'fields'                => [
                [
                    'key'   => 'field_stunfest_evenement_createurs',
                    'label' => __( 'Créateur(s)', 'stunfest-cpt' ),
                    'name'  => 'createurs',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_stunfest_evenement_description',
                    'label' => __( 'Description', 'stunfest-cpt' ),
                    'name'  => 'description',
                    'type'  => 'textarea',
                    'rows'  => 6,
                ],
                [
                    'key'   => 'field_stunfest_evenement_contact',
                    'label' => __( 'Contact', 'stunfest-cpt' ),
                    'name'  => 'contact',
                    'type'  => 'text',
                ],
                [
                    'key'            => 'field_stunfest_evenement_heure_debut',
                    'label'          => __( 'Heure de début', 'stunfest-cpt' ),
                    'name'           => 'heure_debut',
                    'type'           => 'time_picker',
                    'display_format' => 'H:i',
                    'return_format'  => 'H:i',
                ],
                [
                    'key'            => 'field_stunfest_evenement_heure_fin',
                    'label'          => __( 'Heure de fin', 'stunfest-cpt' ),
                    'name'           => 'heure_fin',
                    'type'           => 'time_picker',
                    'display_format' => 'H:i',
                    'return_format'  => 'H:i',
                ],
                [
                    'key'     => 'field_stunfest_evenement_mise_en_avant',
                    'label'   => __( 'Mise en avant', 'stunfest-cpt' ),
                    'name'    => 'mise_en_avant',
                    'type'    => 'true_false',
                    'message' => __( 'Afficher en premier dans la liste', 'stunfest-cpt' ),
                    'ui'      => 1,
                ],
            ],
            'location'              => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'evenement',
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
