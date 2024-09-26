<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register a custom post type for code snippets
function wp_snippet_create_post_type() {
    $labels = array(
        'name'               => _x('Code Snippets', 'post type general name', 'wp-snippet-manager'),
        'singular_name'      => _x('Code Snippet', 'post type singular name', 'wp-snippet-manager'),
        'menu_name'          => _x('Snippets', 'admin menu', 'wp-snippet-manager'),
        'name_admin_bar'     => _x('Snippet', 'add new on admin bar', 'wp-snippet-manager'),
        'add_new'            => _x('Add New', 'snippet', 'wp-snippet-manager'),
        'add_new_item'       => __('Add New Snippet', 'wp-snippet-manager'),
        'new_item'           => __('New Snippet', 'wp-snippet-manager'),
        'edit_item'          => __('Edit Snippet', 'wp-snippet-manager'),
        'view_item'          => __('View Snippet', 'wp-snippet-manager'),
        'all_items'          => __('All Snippets', 'wp-snippet-manager'),
        'search_items'       => __('Search Snippets', 'wp-snippet-manager'),
        'not_found'          => __('No snippets found.', 'wp-snippet-manager'),
        'not_found_in_trash' => __('No snippets found in Trash.', 'wp-snippet-manager')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'code-snippet'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor'),
        'menu_icon'          => 'dashicons-editor-code',
    );

    register_post_type('code_snippet', $args);
}

add_action('init', 'wp_snippet_create_post_type');

// Add custom columns to the admin list view
function wp_snippet_custom_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title', 'wp-snippet-manager'),
        'location' => __('Location', 'wp-snippet-manager'),
        'shortcode' => __('Shortcode', 'wp-snippet-manager'),
        'date' => __('Date', 'wp-snippet-manager'),
    );
    return $columns;
}
add_filter('manage_code_snippet_posts_columns', 'wp_snippet_custom_columns');

// Populate custom columns
function wp_snippet_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'location':
            $location = get_post_meta($post_id, '_snippet_location', true);
            echo ucfirst($location);
            break;
        case 'shortcode':
            echo '[code_snippet id="' . $post_id . '"]';
            break;
    }
}
add_action('manage_code_snippet_posts_custom_column', 'wp_snippet_custom_column_content', 10, 2);