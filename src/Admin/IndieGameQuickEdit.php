<?php

namespace Stunfest\CustomPostTypes\Admin;

class IndieGameQuickEdit extends AbstractQuickEdit {

    protected function postType(): string {
        return 'indiegame';
    }

    protected function anchorColumn(): string {
        return 'col_platform';
    }

    protected function fieldsetTitle(): string {
        return __( 'Détails du jeu', 'stunfest-cpt' );
    }

    protected function fields(): array {
        return [
            'platform'    => [
                'label' => __( 'Plateforme', 'stunfest-cpt' ),
                'type'  => 'text',
                'key'   => 'field_stunfest_indiegame_platform',
            ],
            'site_web'    => [
                'label' => __( 'Site web', 'stunfest-cpt' ),
                'type'  => 'url',
                'key'   => 'field_stunfest_indiegame_url',
            ],
            'description' => [
                'label' => __( 'Description', 'stunfest-cpt' ),
                'type'  => 'textarea',
                'key'   => 'field_stunfest_indiegame_description',
            ],
        ];
    }
}
