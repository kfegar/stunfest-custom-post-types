<?php

namespace Stunfest\CustomPostTypes\Admin;

class EvenementQuickEdit extends AbstractQuickEdit {

    protected function postType(): string {
        return 'evenement';
    }

    protected function anchorColumn(): string {
        return 'col_createurs';
    }

    protected function fieldsetTitle(): string {
        return __( "Détails de l'évènement", 'stunfest-cpt' );
    }

    protected function fields(): array {
        return [
            'createurs'     => [
                'label' => __( 'Créateur(s)', 'stunfest-cpt' ),
                'type'  => 'text',
                'key'   => 'field_stunfest_evenement_createurs',
            ],
            'description'   => [
                'label' => __( 'Description', 'stunfest-cpt' ),
                'type'  => 'textarea',
                'key'   => 'field_stunfest_evenement_description',
            ],
            'contact'       => [
                'label' => __( 'Contact', 'stunfest-cpt' ),
                'type'  => 'text',
                'key'   => 'field_stunfest_evenement_contact',
            ],
            'heure_debut'   => [
                'label' => __( 'Heure de début', 'stunfest-cpt' ),
                'type'  => 'time',
                'key'   => 'field_stunfest_evenement_heure_debut',
            ],
            'heure_fin'     => [
                'label' => __( 'Heure de fin', 'stunfest-cpt' ),
                'type'  => 'time',
                'key'   => 'field_stunfest_evenement_heure_fin',
            ],
            'mise_en_avant' => [
                'label' => __( 'Mise en avant', 'stunfest-cpt' ),
                'type'  => 'checkbox',
                'key'   => 'field_stunfest_evenement_mise_en_avant',
            ],
        ];
    }
}
