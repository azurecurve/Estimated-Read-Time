<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Estimated Read Time
 * Description: Displays estimated read-times for articles on the homepage, in search results, on category and tag pages, on date and author archive pages, and individual posts and pages.
 * Version: 0.2.0
 * Author: azurecurve
 * Author URI: https://dev.azrcrv.co.uk/classicpress-plugins
 * Plugin URI: https://dev.azrcrv.co.uk/classicpress-plugins
 * Text Domain: codepotent-estimated-read-time
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2021, John Alarcon (Code Potent)
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

// Include update client.
require_once(plugin_dir_path(__FILE__).'classes/UpdateClient.class.php');

// Add action to process shortcodes.
add_shortcode('estimated-read-time', __NAMESPACE__.'\process_shortcode');

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

	// Reading speed; 200 wpm is average; use lower for more technical content.
	$reading_speed = apply_filters('codepotent_estimated_read_time_speed', 100);

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

// POST-ADOPTION: Remove these actions before pushing your next update.
add_action('upgrader_process_complete', 'codepotent_enable_adoption_notice', 10, 2);
add_action('admin_notices', 'codepotent_display_adoption_notice');

// POST-ADOPTION: Remove this function before pushing your next update.
function codepotent_enable_adoption_notice($upgrader_object, $options) {
	if ($options['action'] === 'update') {
		if ($options['type'] === 'plugin') {
			if (!empty($options['plugins'])) {
				if (in_array(plugin_basename(__FILE__), $options['plugins'])) {
					set_transient(PLUGIN_PREFIX.'_adoption_complete', 1);
				}
			}
		}
	}
}

// POST-ADOPTION: Remove this function before pushing your next update.
function codepotent_display_adoption_notice() {
	if (get_transient(PLUGIN_PREFIX.'_adoption_complete')) {
		delete_transient(PLUGIN_PREFIX.'_adoption_complete');
		echo '<div class="notice notice-success is-dismissible">';
		echo '<h3 style="margin:25px 0 15px;padding:0;color:#e53935;">IMPORTANT <span style="color:#aaa;">information about the <strong style="color:#333;">'.PLUGIN_NAME.'</strong> plugin</h3>';
		echo '<p style="margin:0 0 15px;padding:0;font-size:14px;">The <strong>'.PLUGIN_NAME.'</strong> plugin has been officially adopted and is now managed by <a href="'.PLUGIN_AUTHOR_URL.'" rel="noopener" target="_blank" style="text-decoration:none;">'.PLUGIN_AUTHOR.'<span class="dashicons dashicons-external" style="display:inline;font-size:98%;"></span></a>, a longstanding and trusted ClassicPress developer and community member. While it has been wonderful to serve the ClassicPress community with free plugins, tutorials, and resources for nearly 3 years, it\'s time that I move on to other endeavors. This notice is to inform you of the change, and to assure you that the plugin remains in good hands. I\'d like to extend my heartfelt thanks to you for making my plugins a staple within the community, and wish you great success with ClassicPress!</p>';
		echo '<p style="margin:0 0 15px;padding:0;font-size:14px;font-weight:600;">All the best!</p>';
		echo '<p style="margin:0 0 15px;padding:0;font-size:14px;">~ John Alarcon <span style="color:#aaa;">(Code Potent)</span></p>';
		echo '</div>';
	}
}