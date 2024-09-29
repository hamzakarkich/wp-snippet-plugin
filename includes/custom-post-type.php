<?php
namespace WPSnippetManager;

class CustomPostType {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
    }

    public static function register_post_type() {
        $labels = [
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
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'code-snippet'],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor'],
            'menu_icon'          => 'dashicons-editor-code',
            'show_in_rest'       => true,
        ];

        register_post_type('code_snippet', $args);
    }
}