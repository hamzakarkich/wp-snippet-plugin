<?php
class WP_Snippet_Manager_Insert {
    public function __construct() {
        add_action('wp_head', array($this, 'insert_header_snippets'), 999);
        add_action('wp_footer', array($this, 'insert_footer_snippets'), 999);
    }

    public function insert_header_snippets() {
        $this->insert_snippets('header');
    }

    public function insert_footer_snippets() {
        $this->insert_snippets('footer');
    }

    private function insert_snippets($location) {
        $snippets = new WP_Query(array(
            'post_type' => 'code_snippet',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_snippet_location',
                    'value' => $location,
                ),
            ),
        ));

        if ($snippets->have_posts()) {
            echo "<!-- Start WP Snippet Manager -->\n";
            while ($snippets->have_posts()) {
                $snippets->the_post();
                echo "<!-- Snippet: " . esc_html(get_the_title()) . " -->\n";
                echo wp_kses_post(get_the_content()) . "\n";
            }
            echo "<!-- End WP Snippet Manager -->\n";
            wp_reset_postdata();
        }
    }
}