<?php
class WP_Snippet_Manager_Taxonomy {
    public function __construct() {
        add_action('init', array($this, 'register_taxonomy'));
    }

    public function register_taxonomy() {
        $labels = array(
            'name'              => _x('Snippet Categories', 'taxonomy general name', 'wp-snippet-manager'),
            'singular_name'     => _x('Snippet Category', 'taxonomy singular name', 'wp-snippet-manager'),
            'search_items'      => __('Search Snippet Categories', 'wp-snippet-manager'),
            'all_items'         => __('All Snippet Categories', 'wp-snippet-manager'),
            'parent_item'       => __('Parent Snippet Category', 'wp-snippet-manager'),
            'parent_item_colon' => __('Parent Snippet Category:', 'wp-snippet-manager'),
            'edit_item'         => __('Edit Snippet Category', 'wp-snippet-manager'),
            'update_item'       => __('Update Snippet Category', 'wp-snippet-manager'),
            'add_new_item'      => __('Add New Snippet Category', 'wp-snippet-manager'),
            'new_item_name'     => __('New Snippet Category Name', 'wp-snippet-manager'),
            'menu_name'         => __('Categories', 'wp-snippet-manager'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'snippet-category'),
        );

        register_taxonomy('snippet_category', array('code_snippet'), $args);
    }
}