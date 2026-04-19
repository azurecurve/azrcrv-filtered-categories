<?php
/**
 * Instructions tab on settings page.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Instructions tab.
 */
$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-fc' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>
		<th scope="row" colspan="2" class="azrcrv-settings-section-heading">
			' . esc_html__( 'Widget', 'azrcrv-fc' ) . '
		</th>
	</tr>

	<tr>
		<td scope="row" colspan="2">
			<p>' . esc_html__( 'Go to Appearance &rarr; Widgets and drag the Filtered Categories by azurecurve widget into any sidebar. Enter a title for the widget and save. The widget will display a list of categories filtered according to the settings below.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row" colspan="2" class="azrcrv-settings-section-heading">
			' . esc_html__( 'Shortcode', 'azrcrv-fc' ) . '
		</th>
	</tr>

	<tr>
		<td scope="row" colspan="2">
			<p>' . esc_html__( 'Place the shortcode [fc] on any page or post to display the full category list. This is intended for use on a dedicated categories page whose slug is entered in the Category Page setting below, which causes a link to that page to appear at the bottom of the widget.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row" colspan="2" class="azrcrv-settings-section-heading">
			' . esc_html__( 'Include / Exclude Setting', 'azrcrv-fc' ) . '
		</th>
	</tr>

	<tr>
		<td scope="row" colspan="2">
			<p>' . esc_html__( 'Set the mode to Include and tick the categories you want to appear in the widget. Set the mode to Exclude and tick the categories you want to hide from the widget. The [fc] shortcode always shows all categories regardless of this setting.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

	<tr>
		<th scope="row" colspan="2" class="azrcrv-settings-section-heading">
			' . esc_html__( 'Category Page &amp; Link Text', 'azrcrv-fc' ) . '
		</th>
	</tr>

	<tr>
		<td scope="row" colspan="2">
			<p>' . esc_html__( 'Enter the slug of the page containing the [fc] shortcode in the Category Page field. When a matching page is found, a link to that page is shown below the category list in the widget. The Category Link Text field controls the wording of that link.', 'azrcrv-fc' ) . '</p>
		</td>
	</tr>

</table>';
