<?php
namespace WPSnippetManager;

class InsertSnippets {
    public function __construct() {
        add_action('wp_head', [$this, 'insert_header'], 999);
        add_action('wp_footer', [$this, 'insert_footer'], 999);
    }

    public function insert_header() {
        $this->insert_snippets('header');
    }

    public function insert_footer() {
        $this->insert_snippets('footer');
    }

    private function insert_snippets($location) {
        $snippets = $this->get_snippets($location);

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

    private function get_snippets($location) {
        return new \WP_Query([
            'post_type' => 'code_snippet',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_snippet_location',
                    'value' => $location,
                ],
            ],
        ]);
    }
}