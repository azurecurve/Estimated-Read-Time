<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Estimated Read Time
 * Description: Displays estimated read-times for articles on the homepage, in search results, on category and tag pages, on date and author archive pages, and individual posts and pages.
 * Version: 1.0.0
 * Author: azurecurve
 * Author URI: https://dev.azrcrv.co.uk/classicpress-plugins
 * Plugin URI: https://dev.azrcrv.co.uk/classicpress-plugins
 * Text Domain: estimated-read-time
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2021, John Alarcon (Code Potent)
 * Copyright 2021, Ian Grieve (azurecurve)
 * -----------------------------------------------------------------------------
 * Adopted by azurecurve, 06/01/2021
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\EstimatedReadTime;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_ert');

// Include constants file.
require_once(plugin_dir_path(__FILE__).'includes/constants.php');

// Include update client.
require_once(plugin_dir_path(__FILE__).'classes/UpdateClient.class.php');

// Add action to load languages
add_action('plugins_loaded', __NAMESPACE__.'\\load_languages');

// add filter to add plugin action link
add_filter('plugin_action_links', __NAMESPACE__.'\\add_plugin_action_link', 10, 2);

// Add action to create admin menu
add_action('admin_menu', __NAMESPACE__.'\\create_admin_menu');

// Add action to save options
add_action('admin_post_azrcrv_ert_save_options', __NAMESPACE__.'\\save_options');

// Add action to process shortcodes.
add_shortcode('estimated-read-time', __NAMESPACE__.'\\process_shortcode');

/**
 * Load language files.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('estimated-read-time', false, $plugin_rel_path);
}

/**
 * Add action link on plugins page.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-ert').'"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'estimated-read-time').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function create_admin_menu(){
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Estimated Read Time", "estimated-read-time")
						,esc_html__("Estimated Read Time", "estimated-read-time")
						,'manage_options'
						,'azrcrv-ert'
						,__NAMESPACE__.'\\display_options');
}

/**
 * Get options with defaults.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function get_option_with_defaults($option_name){
 
	$defaults = array(
						'estimated-reading-speed' => 200,
					);

	$options = get_option($option_name, $defaults);

	$options = wp_parse_args($options, $defaults);

	return $options;

}

/**
 * Display Settings page.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'estimated-read-time'));
    }
	
	global $wpdb;
	
	// Retrieve plugin configuration options from database
	$options = get_option_with_defaults('azrcrv-ert');
	
	?>
	<div id="azrcrv-ert-general" class="wrap">
		<h1>
			<?php
				echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
				esc_html_e(get_admin_page_title());
			?>
		</h1>
		<?php
			if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Settings have been saved.', 'estimated-read-time'); ?></strong></p>
				</div>
		<?php } ?>
		
		<div>
		
			<form method="post" action="admin-post.php">
				
				<fieldset>
				
					<input type="hidden" name="action" value="azrcrv_ert_save_options" />
					<input name="page_options" type="hidden" value="estimated-reading-speed" />
					
					<!-- Adding security through hidden referrer field -->
					<?php wp_nonce_field('azrcrv-ert', 'azrcrv-ert-nonce'); ?>
					
					<table class="form-table">
					
						<tr>
							<th scope="row">
								<label for="estimated-reading-speed">
									<?php esc_html_e('Estimated Reading Speed', 'estimated-read-time'); ?>
								</label>
							</th>
							<td>
								<input name="estimated-reading-speed" type="number" step="5" min="10" id="estimated-reading-speed" value="<?php echo stripslashes($options['estimated-reading-speed']); ?>" class="small-text" />
								<p class="description"><?php esc_html_e('A standard estimated read speed is approximately 200 words per minute; for more technical content, 100 would be common.', 'estimated-read-time'); ?></p>
							</td>
						</tr>
						
					</table>
					
				</fieldset>
				
				<input type="submit" value="<?php esc_html_e('Save Changes', 'estimated-read-time'); ?>" class="button-primary"/>
				
			</form>
		</div>
	</div>
	
	<?php
}

/**
 * Save settings.
 *
 * @author Ian Grieve
 *
 * @since 1.0.0
 *
 */
function save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'estimated-read-time'));
	}
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-ert', 'azrcrv-ert-nonce')){
	
		// Retrieve original plugin options array
		$options = get_option('azrcrv-ert');
		
		$option_name = 'estimated-reading-speed';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field(intval($_POST[$option_name]));
		}
		
		// Store updated options array to database
		update_option('azrcrv-ert', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-ert&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Process shortcode.
 *
 * This function processes the estimated-read-time shortcode into HTML markup.
 *
 * @author John Alarcon
 *
 * @since 0.1.0
 *
 * @param array $atts Shortcode arguments.
 * @return string Markup of shortcode output.
 */
function process_shortcode($atts) {

	// Default values for when not passed in shortcode.
	$defaults = [
		'words' => 0,
	];

	// Replace any missing shortcode arguments with defaults.
	$atts = shortcode_atts(
				$defaults,
				$atts,
				'estimated-read-time');
				
	// get plugin options with defaults
	$options = get_option_with_defaults('azrcrv-ert');
	
	// Reading speed; 200 wpm is average; use lower for more technical content.
	$reading_speed = apply_filters('codepotent_estimated_read_time_speed', $options['estimated-reading-speed']);

	// Read time in minutes; rounded up to nearest minute.
	$reading_time = ceil( (int)$atts['words'] / $reading_speed );

	// Return a translated string.
	$markup = sprintf(
			_n(
				'%d minute read',		// Singular text
				'%d minute read',		// Plural text
				$reading_time,			// Trigger for singular or plural
				'estimated-read-time'	// Text domain
			),
			$reading_time // The replacement value for %d in the text strings.
		);

	// Return estimated time string.
	return $markup;

}