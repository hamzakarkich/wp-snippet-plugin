<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Shortcode to insert a snippet by ID
function wp_snippet_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => null,
    ), $atts, 'code_snippet');

    if (!$atts['id']) {
        return '';
    }

    $snippet = get_post($atts['id']);
    if (!$snippet || $snippet->post_type !== 'code_snippet') {
        return '';
    }

    return wp_kses_post($snippet->post_content);
}
add_shortcode('code_snippet', 'wp_snippet_shortcode');

// TinyMCE button for inserting snippets
function wp_snippet_add_mce_button() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'wp_snippet_add_tinymce_plugin');
        add_filter('mce_buttons', 'wp_snippet_register_mce_button');
    }
}
add_action('admin_head', 'wp_snippet_add_mce_button');

function wp_snippet_add_tinymce_plugin($plugin_array) {
    $plugin_array['wp_snippet_mce_button'] = WP_SNIPPET_MANAGER_PLUGIN_URL . 'js/mce-button.js';
    return $plugin_array;
}

function wp_snippet_register_mce_button($buttons) {
    array_push($buttons, 'wp_snippet_mce_button');
    return $buttons;
}

// AJAX function to get snippets for TinyMCE button
function wp_snippet_get_snippets_for_mce() {
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

    wp_send_json($snippet_list);
}
add_action('wp_ajax_wp_snippet_get_snippets', 'wp_snippet_get_snippets_for_mce');