<?php
/**
 * Plugin Name: WP Snippet Manager
 * Plugin URI: https://github.com/hamzakarkich/wp-snippet-plugin
 * Description: Easily manage and insert code snippets in your WordPress site.
 * Version: 2.0
 * Author: Hamza
 * Author URI: https://github.com/hamzakarkich/
 * License: GPLv2 or later
 * Text Domain: wp-snippet-manager
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('WP_SNIPPET_MANAGER_VERSION', '2.0');
define('WP_SNIPPET_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_SNIPPET_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-meta-box.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-insert.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-shortcode.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-settings.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-taxonomy.php';
require_once WP_SNIPPET_MANAGER_PLUGIN_DIR . 'includes/class-wp-snippet-manager-dashboard.php';

// Initialize plugin
function wp_snippet_manager_init() {
    load_plugin_textdomain('wp-snippet-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    new WP_Snippet_Manager_Meta_Box();
    new WP_Snippet_Manager_Insert();
    new WP_Snippet_Manager_Shortcode();
    new WP_Snippet_Manager_Settings();
    new WP_Snippet_Manager_Taxonomy();
    new WP_Snippet_Manager_Dashboard();
}
add_action('plugins_loaded', 'wp_snippet_manager_init');

// Register custom post type
function wp_snippet_manager_register_post_type() {
    $args = array(
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'code-snippet'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor'),
        'labels' => array(
            'name' => __('Code Snippets', 'wp-snippet-manager'),
            'singular_name' => __('Code Snippet', 'wp-snippet-manager'),
        ),
        'menu_icon' => 'dashicons-editor-code',
    );

    register_post_type('code_snippet', $args);
}
add_action('init', 'wp_snippet_manager_register_post_type');

// Enqueue scripts and styles
function wp_snippet_manager_enqueue_scripts() {
    wp_enqueue_script('wp-snippet-manager', WP_SNIPPET_MANAGER_PLUGIN_URL . 'js/wp-snippet-manager.js', array('jquery'), WP_SNIPPET_MANAGER_VERSION, true);
    wp_localize_script('wp-snippet-manager', 'wp_snippet_manager', array(
        'nonce' => wp_create_nonce('wp_snippet_manager'),
    ));

    $options = get_option('wp_snippet_manager_options');
    if (isset($options['enable_syntax_highlighting']) && $options['enable_syntax_highlighting'] === 'on') {
        wp_enqueue_style('prism', WP_SNIPPET_MANAGER_PLUGIN_URL . 'css/prism.css', array(), WP_SNIPPET_MANAGER_VERSION);
        wp_enqueue_script('prism', WP_SNIPPET_MANAGER_PLUGIN_URL . 'js/prism.js', array(), WP_SNIPPET_MANAGER_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'wp_snippet_manager_enqueue_scripts');
add_action('wp_enqueue_scripts', 'wp_snippet_manager_enqueue_scripts');

// Activation hook
register_activation_hook(__FILE__, 'wp_snippet_manager_activate');

function wp_snippet_manager_activate() {
    wp_snippet_manager_register_post_type();
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wp_snippet_manager_deactivate');

function wp_snippet_manager_deactivate() {
    flush_rewrite_rules();
}