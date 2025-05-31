<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name:		Filtered Categories
 * Description:		Creates a new Categories sidebar widget which allows categories to be included/excluded. A link to a categories page listing all categories can be configured to be displayed; a shortcode [fc] can be used on this page to display categories list.
 * Version:			1.2.7
 * Requires CP:		1.0
 * Requires PHP:	7.4
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/filtered-categories/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		filtered-categories
 * Domain Path:		/languages
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_fc');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_fc_create_admin_menu');
add_action('admin_post_azrcrv_fc_save_options', 'azrcrv_fc_save_options');
add_action('widgets_init', 'azrcrv_fc_create_widget');
add_action('plugins_loaded', 'azrcrv_fc_load_languages');

// add filters
add_filter('plugin_action_links', 'azrcrv_fc_add_plugin_action_link', 10, 2);
add_filter('dynamic_sidebar_params', 'azrcrv_fc_custom_widget_css');
add_filter('codepotent_update_manager_image_path', 'azrcrv_fc_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_fc_custom_image_url');

// add shortcodes
add_shortcode('fc', 'azrcrv_fc_shortcode');
add_shortcode('FC', 'azrcrv_fc_shortcode');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('filtered-categories', false, $plugin_rel_path);
}

/**
 * Custom plugin image path.
 *
 * @since 1.2.0
 *
 */
function azrcrv_fc_custom_image_path($path){
    if (strpos($path, 'azrcrv-filtered-categories') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.2.0
 *
 */
function azrcrv_fc_custom_image_url($url){
    if (strpos($url, 'azrcrv-filtered-categories') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Get options including defaults.
 *
 * @since 1.2.0
 *
 */
function azrcrv_fc_get_option($option_name){
 
	$defaults = array(
						'taxonomy' => 'category',
						'include_exclude' => 'include',
						'category_page' => 'categories',
						'category_link' => 'View all categories',
						'category_page_show_count' => 0,
						'category_page_feed_enabled' => 0,
						'category_page_feed_image' => '',
						'category' => array(''),
					);

	$options = get_option($option_name, $defaults);

	$options = wp_parse_args($options, $defaults);

	return $options;

}

/**
 * Add Filtered Categories action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-fc').'"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'filtered-categories').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Filtered Categories Settings", "filtered-categories")
						,esc_html__("Filtered Categories", "filtered-categories")
						,'manage_options'
						,'azrcrv-fc'
						,'azrcrv_fc_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'filtered-categories'));
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-fc');
	?>
	<div id="azrcrv-tc-general" class="wrap">
		<fieldset>
			<h1>
				<?php
					echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
					esc_html_e(get_admin_page_title());
				?>
			</h1>
			<?php if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Site settings have been saved.', 'filtered-categories') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_fc_save_options" />
				<input name="page_options" type="hidden" value="taxonomy" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-f', 'azrcrv-f-nonce'); ?>
				<table class="form-table">
				
				<tr><th scope="row"><label for="include_exclude"><?php esc_html_e('Include/Exclude Categories?', 'filtered-categories'); ?></label></th><td>
					<select name="include_exclude">
						<option value="include" <?php if($options['include_exclude'] == 'include'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Include', 'filtered-categories'); ?></option>
						<option value="exclude" <?php if($options['include_exclude'] == 'exclude'){ echo ' selected="selected"'; } ?>><?php esc_html_e('Exclude', 'filtered-categories'); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Flag whether marked categories should be included or excluded', 'filtered-categories'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Categories to Include/Exclude', 'filtered-categories'); ?></th><td>
					<div class='azrcrv_fc_scrollbox'>
						<?php
							global $wpdb;
							$query = "SELECT t.term_id AS `term_id`, t.name AS `name` FROM $wpdb->term_taxonomy tt INNER JOIN $wpdb->terms t On t.term_id = tt.term_id WHERE tt.taxonomy = 'category' ORDER BY t.name";
							$_query_result = $wpdb->get_results($query);
							foreach($_query_result as $data){
								?>
								<label for="<?php echo $data->term_id; ?>"><input name="category[<?php echo $data->term_id; ?>]" type="checkbox" id="category" value="1" <?php checked('1', $options['category'][$data->term_id]) ?> /><?php echo esc_html($data->name); ?></label><br />
								<?php
							}
							unset($_query_result);
						?>
					</div>
					<p class="description"><?php esc_html_e('Mark the tags you want to include/exclude from the categories', 'filtered-categories'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="category_page"><?php esc_html_e('Category Page', 'filtered-categories'); ?></label></th><td>
					<input type="text" name="category_page" value="<?php echo esc_html(stripslashes($options['category_page'])); ?>" class="medium-text" />
					<p class="description"><?php esc_html_e('Set default category page', 'filtered-categories'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="category_link"><?php esc_html_e('Category Link Text', 'filtered-categories'); ?></label></th><td>
					<input type="text" name="category_link" value="<?php echo esc_html(stripslashes($options['category_link'])); ?>" class="medium-text" />
					<p class="description"><?php esc_html_e('Set default category link text', 'filtered-categories'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Category page show count?', 'filtered-categories'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Category page show count?', 'filtered-categories'); ?></span></legend>
					<label for="category_page_show_count"><input name="category_page_show_count" type="checkbox" id="category_page_show_count" value="1" <?php checked('1', $options['category_page_show_count']); ?> /></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Enable category page feed image?', 'filtered-categories'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Enable category page feed image?', 'filtered-categories'); ?></span></legend>
					<label for="category_page_feed_enabled"><input name="category_page_feed_enabled" type="checkbox" id="category_page_feed_enabled" value="1" <?php checked('1', $options['category_page_feed_enabled']); ?> /></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><label for="category_page_feed_image"><?php esc_html_e('Category Page Feed Image', 'filtered-categories'); ?></label></th><td>
					<input type="text" name="category_page_feed_image" value="<?php echo esc_html(stripslashes($options['category_page_feed_image'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set category page feed image; leave blank for a default of "/wp-includes/images/rss.png"', 'filtered-categories'); ?></p>
				</td></tr>
				
				</table>
				
				<input type="submit" value="Submit" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'filtered-categories'));
	}
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-f', 'azrcrv-f-nonce')){
		// Retrieve original plugin options array
		$options = get_option('azrcrv-fc');
		
		$option_name = 'include_exclude';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'category';
		$newoptions = array();
		if (isset($_POST[$option_name])){
			//$options[$option_name] = sanitize_text_field($_POST[$option_name]);
			foreach ($_POST[$option_name] as $key => $val ) {
				$newoptions[$key] = sanitize_text_field($val);
			}
		}
		$options[$option_name] = $newoptions;
		
		$option_name = 'category_page';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'category_link';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'category_page_feed_image';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'category_page_feed_enabled';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'category_page_show_count';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		// Store updated options array to database
		update_option('azrcrv-fc', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-fc&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Register widget.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_create_widget(){
	register_widget('azrcrv_fc_register_widget');
}

/**
 * Widget class.
 *
 * @since 1.0.0
 *
 */
class azrcrv_fc_register_widget extends WP_Widget {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 */
	function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		
		// Widget creation function
		parent::__construct('azrcrv-fc',
							 'Filtered Categories by azurecurve',
							 array('description' =>
									esc_html__('A filtered list of categories.', 'azrcrv-fc')));
	}
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue(){
		// Enqueue Styles
		wp_enqueue_style('azrcrv-fc', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	}

	/**
	 * Display widget form in admin.
	 *
	 * @since 1.0.0
	 *
	 */
	function form($instance){
		// Retrieve previous values from instance
		// or set default values if not present
		$widget_title = (!empty($instance['azrcrv-fc-title']) ? 
							esc_attr($instance['azrcrv-fc-title']) :
							'Categories');
		?>

		<!-- Display field to specify title  -->
		<p>
			<label for="<?php echo 
						$this->get_field_id('azrcrv-fc-title'); ?>">
			<?php echo 'Widget Title:'; ?>			
			<input type="text" 
					id="<?php echo $this->get_field_id('azrcrv-fc-title'); ?>"
					name="<?php echo $this->get_field_name('azrcrv-fc-title'); ?>"
					value="<?php echo $widget_title; ?>" />			
			</label>
		</p> 

		<?php
	}

	/**
	 * Validate user input.
	 *
	 * @since 1.0.0
	 *
	 */
	function update($new_instance, $old_instance){
		$instance = $old_instance;

		$instance['azrcrv-fc-title'] = strip_tags($new_instance['azrcrv-fc-title']);

		return $instance;
	}
	
	/**
	 * Display widget on front end.
	 *
	 * @since 1.0.0
	 *
	 */
	function widget ($args, $instance){
		// Extract members of args array as individual variables
		extract($args);

		// Display widget title
		echo $before_widget;
		echo $before_title;
		$widget_title = (!empty($instance['azrcrv-fc-title']) ? 
					esc_attr($instance['azrcrv-fc-title']) :
					'Categories');
		echo apply_filters('widget_title', $widget_title);
		echo $after_title; 
		
		$options = get_option('azrcrv-fc');
		
		$first = true;
		$categories = '';
		$args = array('title_li' => '');
		foreach ($options['category'] as $key => $value){
			if ($first){ $first = false; }else{ $categories.= ','; }
			$categories .= $key;
		}
		
		if ($options['include_exclude'] == 'include'){
			$args['include'] = $categories;
		}else{
			$args['exclude'] = $categories;
		}
		
		echo '<ul>';
		wp_list_categories($args);
		echo '</ul>';
		
		if (strlen($options['category_page']) > 0){
			$page = get_page_by_path($options['category_page']);
			echo '<p class="azrcrv-fc"><a href="'.esc_url(get_permalink($page->ID)).'">'.$options['category_link'].'</p>';
		}
		
		echo $after_widget;
	}
}

/**
 * Custom widget class.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_custom_widget_css($params){
	global $widget_num;
	$this_id = $params[0]['id'];
	$arr_registered_widgets = wp_get_sidebars_widgets();
	
	if (!$widget_num){ $widget_num = array(); }
	
    if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])){
        return $params;
    }
	
    $class = 'widget_categories widget-azc-fc';
  
    $params[0]['before_widget'] = str_replace('widget-azc-fc', $class, $params[0]['before_widget']);
 
    return $params;
 
}

/**
 * Display filtered categories via shortcode.
 *
 * @since 1.0.0
 *
 */
function azrcrv_fc_shortcode($atts, $content = null){
	$options = get_option('azrcrv-fc');
	if ($options['category_page_feed_enabled'] == 1){
		if (strlen($options['category_page_feed_image']) > 0){
			$feed_image = '&feed_image='.$options['category_page_feed_image'];
		}else{
			$feed_image = '&feed_image=/wp-includes/images/rss.png';
		}
	}else{
		$feed_image = '';
	}
	
	$output = "<div class='azrcrv-fc'><ul>";
	$output .= wp_list_categories('echo=0&title_li=&style=list&show_count='.$options['category_page_show_count'].$feed_image);
	$output .= "</ul></div>";
	
	return $output;
}

?>