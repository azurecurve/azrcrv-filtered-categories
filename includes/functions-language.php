<?php
/**
 * Language functions.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Load language files.
 */
function load_languages() {
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/../assets/languages';
	load_plugin_textdomain( 'azrcrv-fc', false, $plugin_rel_path );
}
