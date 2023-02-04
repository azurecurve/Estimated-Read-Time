=== Estimated Read Time ===

Description:	Displays estimated read-times for articles on the homepage, search results, category and tag pages, date and author archive pages and posts and pages.
Version:		1.0.0
Tags:			read time,shortcode
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Contributors:	azurecurve,Code Potent
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/
Download link:	https://github.com/azurecurve/Estimated-Read-Time/releases/download/1.0.0/codepotent-estimated-read-time.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires CP:	1.0
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	estimated-read-time
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Displays estimated read-times for articles on the homepage, in search results, on category and tag pages, on date and author archive pages, and individual posts and pages.

== Description ==

# Description

The **Estimated Read Time** plugin for ClassicPress allows you to display expected reading times on your articles and summaries. The average person reads at 200 words per minute, so, that's the default setting. You can change it with a simple filter to suit your own audience and content.

This plugin is multisite compatible; each site will need settings to be configured in the admin dashboard.

== Installation ==

# Installation Instructions

 * Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/Estimated-Read-Time/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 
# Usage

To provide the most versatile placement options, the estimated read time display is implemented with a shortcode that is designed to be placed in your _theme template_ files. It may require some experimenting to find just the right templates for your particular theme, but you can try the `content.php`, `single.php`, `archive.php`, and `page.php` templates as a starting point.

Note that the shortcode is designed to work in templates that are **in the loop**, so you won't be able to place the shortcode in, say, your `header.php` or `footer.php` files. See the examples below and place _one or the other_ into your theme's template file(s) where you would like the estimated read time to be displayed.

### Example 1: Minimal template shortcode

`&lt;?php echo do_shortcode('[estimated-read-time words="'.str_word_count(strip_tags($post->post_content)).'"]'); ?>`

### Example 2: Template shortcode with dashicon

` &lt;?php echo do_shortcode('[estimated-read-time words="'.str_word_count(strip_tags($post->post_content)).'"]'); ?>`

# Settings

There is only a single setting, you can use this filter in a functionality plugin or functions.php file:
`
	&lt;?php

	// Define reading speed of your audience. 200 WPM is average; adjust to suit.
	add_filter('codepotent_estimated_read_time_speed', 'codepotent_estimated_read_time_speed');
	function codepotent_estimated_read_time_speed($reading_speed) {
		return 125;
	}
`

# Important Note on Updating your Theme

If you are using a theme that is a _parent theme_ (as opposed to a _child theme_) you will need to be cautious about updating your theme because the update will overwrite all the theme files. To avoid this, you have several options, as follows. Options are listed in the order of preference.

1. use a child theme and add your shortcodes to your child theme's templates, or
2. use a theme that you built yourself to avoid surprise updates, or
3. carefully copy your shortcodes out of your theme files before updating, then replacing them afterward, or
4. continue using the parent theme, but don't update it.

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.0.0](https://github.com/azurecurve/Estimated-Read-Time/releases/1.0.0/)
**2022-02-04**
 * Change plugin text domain.
 * Add azurecurve menu.
 * Add plugin action link.
 * Add options page.
 * Add option to set estimated read time (filter remains available for use ad will override the option).
 * Update readme file for compatibility with ClassicPress Directory.

### Version 0.2.1
**JUN-04-2021**
 1. Fix missing constants

### Version 0.2.0
**JUN-03-2021**
 1. Update plugin directory and slug name
 1. Update README.md
 1. Update footer credits
 1. Update plugin header details 
 1. Add `UpdateClient.class.php` v 2.1.0
 1. Add `constants.php` file
 1. Add adoption notice
 1. Remove public-facing Code Potent links
 1. Replace Code Potent graphics with new vendor graphics

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for ClassicPress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/classicpress-plugins/) and are integrated with the [Update Manager plugin](https://codepotent.com/classicpress/plugins/update-manager/) for fully integrated, no hassle, updates.

Some of the top plugins available from **azurecurve** are:
* [Add Open Graph Tags](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/)
* [Add Twitter Cards](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/)
* [Breadcrumbs](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/)
* [SMTP](https://development.azurecurve.co.uk/classicpress-plugins/smtp/)
* [To Twitter](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/)
* [Theme Switcher](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/)
* [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/)