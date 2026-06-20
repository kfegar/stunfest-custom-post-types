<?php
/**
 * Plugin Name: Stunfest Custom Post Types
 * Description: Custom post types pour le site Stunfest
 * Version: 0.1.0
 * Author: Kevin Fégar
 * Text Domain: stunfest-cpt
 * Requires Plugins: advanced-custom-fields
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'STUNFEST_CPT_FILE', __FILE__ );

register_activation_hook( __FILE__, function (): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            __( 'Ce plugin requiert <strong>Advanced Custom Fields</strong>. Veuillez l\'installer et l\'activer avant d\'activer ce plugin.', 'stunfest-cpt' ),
            __( 'Dépendance manquante', 'stunfest-cpt' ),
            [ 'back_link' => true ]
        );
    }
} );

add_action( 'admin_notices', function (): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Stunfest CPT : ce plugin requiert Advanced Custom Fields pour fonctionner.', 'stunfest-cpt' ) . '</p></div>';
    }
} );

spl_autoload_register( function ( string $class ): void {
    $prefix   = 'Stunfest\\CustomPostTypes\\';
    $base_dir = __DIR__ . '/src/';

    if ( ! str_starts_with( $class, $prefix ) ) return;

    $file = $base_dir . str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ) . '.php';

    if ( file_exists( $file ) ) require $file;
} );

( new \Stunfest\CustomPostTypes\Plugin() )->boot();
