<?php
namespace WPSnippetManager;

class Shortcodes {
    public function __construct() {
        add_shortcode('code_snippet', [$this, 'shortcode']);
        add_action('admin_head', [$this, 'add_mce_button']);
        add_action('wp_ajax_wp_snippet_get_snippets', [$this, 'get_snippets_for_mce']);
    }

    public function shortcode($atts) {
        $atts = shortcode_atts(['id' => null], $atts, 'code_snippet');

        if (!$atts['id']) {
            return '';
        }

        $snippet = get_post($atts['id']);
        if (!$snippet || $snippet->post_type !== 'code_snippet') {
            return '';
        }

        return wp_kses_post($snippet->post_content);
    }

    public function add_mce_button() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', [$this, 'add_tinymce_plugin']);
            add_filter('mce_buttons', [$this, 'register_mce_button']);
        }
    }

    public function add_tinymce_plugin($plugin_array) {
        $plugin_array['wp_snippet_mce_button'] = WP_SNIPPET_MANAGER_PLUGIN_URL . 'js/mce-button.js';
        return $plugin_array;
    }

    public function register_mce_button($buttons) {
        $buttons[] = 'wp_snippet_mce_button';
        return $buttons;
    }

    public function get_snippets_for_mce() {
        check_ajax_referer('wp_snippet_manager', 'security');

        $snippets = get_posts([
            'post_type' => 'code_snippet',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $snippet_list = array_map(function($snippet) {
            return [
                'text' => $snippet->post_title,
                'value' => $snippet->ID,
            ];
        }, $snippets);

        wp_send_json_success($snippet_list);
    }
}

class AdminColumns {
    public function __construct() {
        add_filter('manage_code_snippet_posts_columns', [$this, 'custom_columns']);
        add_action('manage_code_snippet_posts_custom_column', [$this, 'custom_column_content'], 10, 2);
    }

    public function custom_columns($columns) {
        $new_columns = [
            'cb' => $columns['cb'],
            'title' => __('Title', 'wp-snippet-manager'),
            'location' => __('Location', 'wp-snippet-manager'),
            'shortcode' => __('Shortcode', 'wp-snippet-manager'),
            'date' => __('Date', 'wp-snippet-manager'),
        ];
        return $new_columns;
    }

    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'location':
                $location = get_post_meta($post_id, '_snippet_location', true);
                echo ucfirst($location);
                break;
            case 'shortcode':
                echo '<code>[code_snippet id="' . $post_id . '"]</code>';
                break;
        }
    }
}