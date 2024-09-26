<?php
/*
Plugin Name: WP Snippet Manager
Plugin URI: https://github.com/hamzakarkich/wp-snippet-plugin
Description: Easily manage and insert code snippets like HTML, CSS, JavaScript, and PHP in various locations of your WordPress site.
Version: 1.1
Author: Hamza
Author URI: https://github.com/hamzakarkich/
License: GPLv2 or later
Text Domain: wp-snippet-manager
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('WP_SNIPPET_MANAGER_VERSION', '1.1');
define('WP_SNIPPET_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_SNIPPET_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/custom-post-type.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/insert-snippets.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/shortcodes.php';

// Activation hook
register_activation_hook(__FILE__, 'wp_snippet_manager_activate');

function wp_snippet_manager_activate() {
    // Flush rewrite rules to ensure our custom post type is registered
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wp_snippet_manager_deactivate');

function wp_snippet_manager_deactivate() {
    // Clean up if needed
}

// Load text domain for internationalization
function wp_snippet_manager_load_textdomain() {
    load_plugin_textdomain('wp-snippet-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wp_snippet_manager_load_textdomain');