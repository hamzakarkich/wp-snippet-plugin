<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Insert snippets into the header
function wp_snippet_insert_header() {
    wp_snippet_insert_snippets('header');
}
add_action('wp_head', 'wp_snippet_insert_header', 999);

// Insert snippets into the footer
function wp_snippet_insert_footer() {
    wp_snippet_insert_snippets('footer');
}
add_action('wp_footer', 'wp_snippet_insert_footer', 999);

// Helper function to insert snippets
function wp_snippet_insert_snippets($location) {
    $args = array(
        'post_type' => 'code_snippet',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_snippet_location',
                'value' => $location,
            ),
        ),
    );

    $snippets = new WP_Query($args);

    if ($snippets->have_posts()) {
        echo "<!-- Start WP Snippet Manager -->\n";
        while ($snippets->have_posts()) {
            $snippets->the_post();
            $code = get_the_content();
            echo "<!-- Snippet: " . esc_html(get_the_title()) . " -->\n";
            echo wp_kses_post($code) . "\n";
        }
        echo "<!-- End WP Snippet Manager -->\n";
        wp_reset_postdata();
    }
}