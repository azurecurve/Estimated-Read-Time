<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Estimated Read Time
 * Description: Displays estimated read-times for articles on the homepage, in search results, on category and tag pages, on date and author archive pages, and individual posts and pages.
 * Version: 0.1.0
 * Author: Code Potent
 * Author URI: https://codepotent.com
 * Plugin URI: https://github.com/johnalarcon/estimated-read-time
 * Text Domain: estimated-read-time
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright © 2019 - CodePotent
 * -----------------------------------------------------------------------------
 *           ____          _      ____       _             _
 *          / ___|___   __| | ___|  _ \ ___ | |_ ___ _ __ | |_
 *         | |   / _ \ / _` |/ _ \ |_) / _ \| __/ _ \ '_ \| __|
 *         | |__| (_) | (_| |  __/  __/ (_) | ||  __/ | | | |_
 *          \____\___/ \__,_|\___|_|   \___/ \__\___|_| |_|\__|.com
 *
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\EstimatedReadTime;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

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