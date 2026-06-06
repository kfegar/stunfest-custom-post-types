<?php

namespace Stunfest\CustomPostTypes;

class Plugin {

    /** @var array<class-string> */
    private array $post_types = [
        PostTypes\EvenementPostType::class,
        PostTypes\IndieGamePostType::class,
    ];

    /** @var array<class-string> */
    private array $taxonomies = [
        Taxonomies\LieuTaxonomy::class,
        Taxonomies\ThematiqueTaxonomy::class,
        Taxonomies\JourTaxonomy::class,
        Taxonomies\CreateurTaxonomy::class,
        Taxonomies\GenreTaxonomy::class,
        Taxonomies\StudioTaxonomy::class,
    ];

    public function boot(): void {
        add_action( 'init', $this->register( ... ) );
        add_action( 'acf/init', $this->registerFields( ... ) );

        if ( is_admin() ) {
            ( new Admin\EvenementColumns() )->register();
        }
    }

    private function register(): void {
        foreach ( $this->post_types as $class ) {
            ( new $class() )->register();
        }

        foreach ( $this->taxonomies as $class ) {
            ( new $class() )->register();
        }
    }

    private function registerFields(): void {
        ( new Fields\EvenementFields() )->register();
        ( new Fields\IndieGameFields() )->register();
    }
}
