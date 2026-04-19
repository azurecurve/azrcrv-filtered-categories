<?php
/**
 * Settings tab on settings page.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Settings tab.
 */
$tab_settings_label = PLUGIN_NAME . ' ' . esc_html__( 'Settings', 'azrcrv-fc' );

$categories_html = '';
foreach ( get_all_categories() as $cat ) {
	$term_id          = intval( $cat->term_id );
	$checked_value    = isset( $options['category'][ $term_id ] ) ? $options['category'][ $term_id ] : 0;
	$categories_html .= '<label for="category-' . esc_attr( $term_id ) . '">'
		. '<input name="category[' . esc_attr( $term_id ) . ']" type="checkbox" id="category-' . esc_attr( $term_id ) . '" value="1" ' . checked( '1', $checked_value, false ) . ' />'
		. esc_html( $cat->name )
		. '</label><br />';
}

$tab_settings = '
<table class="form-table azrcrv-settings">

	<tr>
		<th scope="row" colspan="2">
			<label for="explanation">
				' . esc_html__( 'Filtered Categories displays a sidebar widget and [fc] shortcode showing a filtered list of categories, with configurable include/exclude control and an optional link to a categories page.', 'azrcrv-fc' ) . '
			</label>
		</th>
	</tr>

	<tr>
		<th scope="row">
			<label for="include_exclude">' . esc_html__( 'Include/Exclude Categories?', 'azrcrv-fc' ) . '</label>
		</th>
		<td>
			<select name="include_exclude" id="include_exclude">
				<option value="include" ' . selected( 'include', $options['include_exclude'], false ) . '>' . esc_html__( 'Include', 'azrcrv-fc' ) . '</option>
				<option value="exclude" ' . selected( 'exclude', $options['include_exclude'], false ) . '>' . esc_html__( 'Exclude', 'azrcrv-fc' ) . '</option>
			</select>
			<p class="description">' . esc_html__( 'Flag whether marked categories should be included or excluded.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row">' . esc_html__( 'Categories to Include/Exclude', 'azrcrv-fc' ) . '</th>
		<td>
			<div class="azrcrv-fc-scrollbox">
				' . $categories_html . '
			</div>
			<p class="description">' . esc_html__( 'Mark the categories you want to include or exclude.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="category_page">' . esc_html__( 'Category Page', 'azrcrv-fc' ) . '</label>
		</th>
		<td>
			<input type="text" name="category_page" id="category_page" value="' . esc_attr( $options['category_page'] ) . '" class="medium-text" />
			<p class="description">' . esc_html__( 'Slug of the page where the [fc] shortcode is used; a link will be shown at the bottom of the widget.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="category_link">' . esc_html__( 'Category Link Text', 'azrcrv-fc' ) . '</label>
		</th>
		<td>
			<input type="text" name="category_link" id="category_link" value="' . esc_attr( $options['category_link'] ) . '" class="medium-text" />
			<p class="description">' . esc_html__( 'Link text displayed at the bottom of the widget pointing to the category page.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row">' . esc_html__( 'Category page show count?', 'azrcrv-fc' ) . '</th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span>' . esc_html__( 'Category page show count?', 'azrcrv-fc' ) . '</span></legend>
				<label for="category_page_show_count">
					<input name="category_page_show_count" type="checkbox" id="category_page_show_count" value="1" ' . checked( '1', $options['category_page_show_count'], false ) . ' />
					<span class="description">' . esc_html__( 'Show the post count next to each category on the [fc] shortcode page.', 'azrcrv-fc' ) . '</span>
				</label>
			</fieldset>
		</td>
	</tr>

	<tr>
		<th scope="row">' . esc_html__( 'Enable category page feed image?', 'azrcrv-fc' ) . '</th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span>' . esc_html__( 'Enable category page feed image?', 'azrcrv-fc' ) . '</span></legend>
				<label for="category_page_feed_enabled">
					<input name="category_page_feed_enabled" type="checkbox" id="category_page_feed_enabled" value="1" ' . checked( '1', $options['category_page_feed_enabled'], false ) . ' />
					<span class="description">' . esc_html__( 'Show a feed image link next to each category on the [fc] shortcode page.', 'azrcrv-fc' ) . '</span>
				</label>
			</fieldset>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="category_page_feed_image">' . esc_html__( 'Category Page Feed Image', 'azrcrv-fc' ) . '</label>
		</th>
		<td>
			<input type="text" name="category_page_feed_image" id="category_page_feed_image" value="' . esc_attr( $options['category_page_feed_image'] ) . '" class="regular-text" />
			<p class="description">' . esc_html__( 'URL of the feed image; leave blank to use the default /wp-includes/images/rss.png.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

</table>';
