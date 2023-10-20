<?php
// Register settings page as a submenu of a new top-level menu
add_action('admin_menu', 'robotxtender_register_settings_page');

function robotxtender_register_settings_page()
{
    // Create new top-level menu and set the dashboard to your settings page
    add_menu_page(
        'RobotXtender',
        'RobotXtender',
        'manage_options',
        'robotxtender_dashboard',
        'robotxtender_settings_page', // Set this to your settings page function
        'dashicons-admin-generic'
    );

    // Add submenu page for settings (optional, if you want to keep it separate)
    add_submenu_page(
        'robotxtender_dashboard', // Parent slug
        'RobotXtender Settings',  // Page title
        'Settings',               // Menu title
        'manage_options',         // Capability
        'robotxtender',           // Menu slug
        'robotxtender_settings_page' // Callback function
    );
}

// Register settings
add_action('admin_init', 'robotxtender_register_settings');

// Register settings
function robotxtender_register_settings()
{
    register_setting('robotxtender_settings_group', 'robotxtender_robots_txt');
    register_setting('robotxtender_settings_group', 'robotxtender_include_sitemap');
}
