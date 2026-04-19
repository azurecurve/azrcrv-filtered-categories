<?php
/**
 * Plugin functionality — widget, shortcode and data helpers.
 */

/**
 * Declare the Namespace.
 */
namespace azurecurve\FilteredCategories;

/**
 * Get all categories from the database for the settings page.
 *
 * @return array Array of objects with term_id and name properties.
 */
function get_all_categories() {
	global $wpdb;

	return $wpdb->get_results(
		"SELECT t.term_id AS term_id, t.name AS name
		FROM {$wpdb->term_taxonomy} tt
		INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
		WHERE tt.taxonomy = 'category'
		ORDER BY t.name"
	);
}

/**
 * Register widget.
 */
function create_widget() {
	register_widget( __NAMESPACE__ . '\\Filtered_Categories_Widget' );
}

/**
 * Widget class.
 */
class Filtered_Categories_Widget extends \WP_Widget {

	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct(
			'azrcrv-fc',
			'Filtered Categories by azurecurve',
			array(
				'description' => esc_html__( 'A filtered list of categories.', 'azrcrv-fc' ),
			)
		);
	}

	/**
	 * Display widget form in admin.
	 */
	function form( $instance ) {
		$widget_title = ( ! empty( $instance['azrcrv-fc-title'] ) ? esc_attr( $instance['azrcrv-fc-title'] ) : 'Categories' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'azrcrv-fc-title' ) ); ?>">
				<?php esc_html_e( 'Widget Title:', 'azrcrv-fc' ); ?>
				<input type="text"
					id="<?php echo esc_attr( $this->get_field_id( 'azrcrv-fc-title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'azrcrv-fc-title' ) ); ?>"
					value="<?php echo esc_attr( $widget_title ); ?>" />
			</label>
		</p>
		<?php
	}

	/**
	 * Validate and save widget instance.
	 */
	function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$instance['azrcrv-fc-title'] = sanitize_text_field( $new_instance['azrcrv-fc-title'] );
		return $instance;
	}

	/**
	 * Display widget on front end.
	 */
	function widget( $args, $instance ) {

		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];

		$widget_title = ( ! empty( $instance['azrcrv-fc-title'] ) ? esc_attr( $instance['azrcrv-fc-title'] ) : 'Categories' );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied HTML.
		echo $before_widget;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied HTML.
		echo $before_title;
		echo esc_html( apply_filters( 'widget_title', $widget_title ) );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied HTML.
		echo $after_title;

		$options = get_option_with_defaults( PLUGIN_HYPHEN );

		$categories = implode( ',', array_keys( $options['category'] ) );

		$wp_list_args = array( 'title_li' => '' );

		if ( $options['include_exclude'] === 'include' ) {
			$wp_list_args['include'] = $categories;
		} else {
			$wp_list_args['exclude'] = $categories;
		}

		echo '<ul>';
		wp_list_categories( $wp_list_args );
		echo '</ul>';

		if ( strlen( $options['category_page'] ) > 0 ) {
			$page = get_page_by_path( $options['category_page'] );
			if ( $page ) {
				echo '<p class="azrcrv-fc-link"><a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( $options['category_link'] ) . '</a></p>';
			}
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-supplied HTML.
		echo $after_widget;
	}
}

/**
 * Custom widget CSS class injection.
 */
function custom_widget_css( $params ) {
	$this_id                  = $params[0]['id'];
	$arr_registered_widgets   = wp_get_sidebars_widgets();

	if ( ! isset( $arr_registered_widgets[ $this_id ] ) || ! is_array( $arr_registered_widgets[ $this_id ] ) ) {
		return $params;
	}

	$params[0]['before_widget'] = str_replace( 'class="', 'class="widget_categories widget-azc-fc ', $params[0]['before_widget'] );

	return $params;
}

/**
 * Display filtered categories via shortcode.
 */
function shortcode( $atts, $content = null ) {
	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	if ( $options['category_page_feed_enabled'] == 1 ) {
		if ( strlen( $options['category_page_feed_image'] ) > 0 ) {
			$feed_image = '&feed_image=' . esc_url( $options['category_page_feed_image'] );
		} else {
			$feed_image = '&feed_image=/wp-includes/images/rss.png';
		}
	} else {
		$feed_image = '';
	}

	$output  = "<div class='azrcrv-fc'><ul>";
	$output .= wp_list_categories( 'echo=0&title_li=&style=list&show_count=' . intval( $options['category_page_show_count'] ) . $feed_image );
	$output .= '</ul></div>';

	return $output;
}
