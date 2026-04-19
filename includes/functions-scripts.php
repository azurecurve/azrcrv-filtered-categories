<?php
/**
 * Script functions.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Register admin scripts.
 */
function register_admin_scripts() {
	wp_register_script(
		PLUGIN_HYPHEN . '-admin-js',
		esc_url_raw( plugins_url( '../assets/js/admin-standard.js', __FILE__ ) ),
		array(),
		'2.0.0',
		true
	);
}

/**
 * Enqueue admin scripts.
 */
function enqueue_admin_scripts() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ( isset( $_GET['page'] ) && $_GET['page'] === PLUGIN_HYPHEN ) ||
		 ( isset( $_GET['page'] ) && $_GET['page'] === 'azrcrv-plugin-menu' ) ) {
		wp_enqueue_script( PLUGIN_HYPHEN . '-admin-js' );
	}
}
