<?php
/**
 * Style functions.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Register admin styles.
 */
function register_admin_styles() {
	wp_register_style( PLUGIN_HYPHEN . '-admin-styles',      esc_url_raw( plugins_url( '../assets/css/admin.css',              __FILE__ ) ), array(), '2.0.0' );
	wp_register_style( 'azrcrv-admin-standard-styles',       esc_url_raw( plugins_url( '../assets/css/admin-standard.css',     __FILE__ ) ), array(), '2.0.0' );
	wp_register_style( 'azrcrv-pluginmenu-admin-styles',     esc_url_raw( plugins_url( '../assets/css/admin-pluginmenu.css',   __FILE__ ) ), array(), '2.0.0' );
}

/**
 * Enqueue admin styles.
 */
function enqueue_admin_styles() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ( isset( $_GET['page'] ) && ( $_GET['page'] == PLUGIN_HYPHEN || $_GET['page'] == 'azrcrv-plugin-menu' ) ) ) {
		wp_enqueue_style( PLUGIN_HYPHEN . '-admin-styles' );
		wp_enqueue_style( 'azrcrv-admin-standard-styles' );
		wp_enqueue_style( 'azrcrv-pluginmenu-admin-styles' );
	}
}

/**
 * Register front end styles.
 */
function register_frontend_styles() {
	wp_register_style( PLUGIN_HYPHEN . '-styles', esc_url_raw( plugins_url( '../assets/css/style.css', __FILE__ ) ), array(), '2.0.0' );
}

/**
 * Enqueue front end styles.
 */
function enqueue_frontend_styles() {
	wp_enqueue_style( PLUGIN_HYPHEN . '-styles' );
}
