<?php

/**
 * Plugin Name: RobotXtender
 * Plugin URI: https://github.com/amarasa/robotxtender
 * Description: A WordPress plugin to update Robots.txt and override Yoast's settings.
 * Version: 1.0
 * Author: Angelo Marasa
 * Author URI: https://github.com/amarasa/robotxtender
 * License: GPL-2.0+
 * Text Domain: robotxtender
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('ROBOTXTENDER_VERSION', '1.0');
define('ROBOTXTENDER_PATH', plugin_dir_path(__FILE__));
define('ROBOTXTENDER_URL', plugin_dir_url(__FILE__));

// Include additional files
require_once(ROBOTXTENDER_PATH . 'includes/functions.php');
require_once(ROBOTXTENDER_PATH . 'includes/settings.php');
require_once(ROBOTXTENDER_PATH . 'includes/settings_content.php');

/**
 * Modify robots.txt content
 *
 * @param string $output Robots.txt output.
 * @param string $public_blog_public Public status.
 * @return string Modified robots.txt content.
 */


function robotxtender_modify_robots_txt($output, $public_blog_public)
{
    $custom_output = get_option('robotxtender_robots_txt', '');

    // Check if the "Include Sitemap" option is enabled
    if (get_option('robotxtender_include_sitemap') == 1) {
        // Dynamically get the sitemap URL for this site
        $sitemap_url = home_url('/sitemap_index.xml'); // Update this based on how your sitemap URL is generated

        // Insert the sitemap URL into the robots.txt content
        $custom_output = preg_replace('/(# ---------------------------)/', "$1\nSitemap: $sitemap_url\n", $custom_output);
    }

    if (!empty($custom_output)) {
        $output = $custom_output;
    }

    return $output;
}


// Override all other robots.txt filters
add_action('wp_loaded', 'robotxtender_override_robots');

function robotxtender_override_robots()
{
    // Remove all other hooks for 'robots_txt'
    remove_all_filters('robots_txt');

    // Add your custom robots.txt filter
    add_filter('robots_txt', 'robotxtender_modify_robots_txt', 10, 2);
}

// Activation hook
register_activation_hook(__FILE__, 'robotxtender_activate');

function robotxtender_activate()
{
    // Default robots.txt content
    $default_robots_txt = "# RobotXtender\n";
    $default_robots_txt .= "# ---------------------------\n";
    $default_robots_txt .= "User-agent: *\n";
    $default_robots_txt .= "Disallow:\n";
    $default_robots_txt .= "# -------------------\n";
    $default_robots_txt .= "# END RobotXtender BLOCK\n";

    // Set the default robots.txt content if it's not already set
    if (get_option('robotxtender_robots_txt') === false) {
        update_option('robotxtender_robots_txt', $default_robots_txt);
    }

    update_option('robotxtender_include_sitemap', 1);
}


// Deactivation hook
register_deactivation_hook(__FILE__, 'robotxtender_deactivate');

function robotxtender_deactivate()
{
    // Delete the stored robots.txt content
    delete_option('robotxtender_robots_txt');
}
