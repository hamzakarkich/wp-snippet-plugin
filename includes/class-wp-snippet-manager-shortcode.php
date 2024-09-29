<?php
class WP_Snippet_Manager_Shortcode {
    public function __construct() {
        add_shortcode('code_snippet', array($this, 'shortcode'));
        add_action('admin_init', array($this, 'add_tinymce_button'));
        add_action('wp_ajax_wp_snippet_get_snippets', array($this, 'get_snippets_for_mce'));
    }

    public function shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, 'code_snippet');

        $id = intval($atts['id']);
        if ($id <= 0) {
            return '';
        }

        $snippet = get_post($id);
        if (!$snippet || $snippet->post_type !== 'code_snippet') {
            return '';
        }

        return wp_kses_post($snippet->post_content);
    }

    public function add_tinymce_button() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this, 'add_tinymce_plugin'));
            add_filter('mce_buttons', array($this, 'register_tinymce_button'));
        }
    }

    public function add_tinymce_plugin($plugin_array) {
        $plugin_array['wp_snippet_button'] = WP_SNIPPET_MANAGER_PLUGIN_URL . 'js/tinymce-button.js';
        return $plugin_array;
    }

    public function register_tinymce_button($buttons) {
        array_push($buttons, 'wp_snippet_button');
        return $buttons;
    }

    public function get_snippets_for_mce() {
        check_ajax_referer('wp_snippet_manager', 'security');

        $snippets = get_posts(array(
            'post_type' => 'code_snippet',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        $snippet_list = array();
        foreach ($snippets as $snippet) {
            $snippet_list[] = array(
                'text' => $snippet->post_title,
                'value' => $snippet->ID,
            );
        }

        wp_send_json_success($snippet_list);
    }
}