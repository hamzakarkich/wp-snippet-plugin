<?php
class WP_Snippet_Manager_Dashboard {
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'wp_snippet_manager_dashboard_widget',
            'Recent Code Snippets',
            array($this, 'dashboard_widget_content')
        );
    }

    public function dashboard_widget_content() {
        $recent_snippets = get_posts(array(
            'post_type' => 'code_snippet',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        if ($recent_snippets) {
            echo '<ul>';
            foreach ($recent_snippets as $snippet) {
                printf(
                    '<li><a href="%s">%s</a></li>',
                    esc_url(get_edit_post_link($snippet->ID)),
                    esc_html($snippet->post_title)
                );
            }
            echo '</ul>';
        } else {
            echo '<p>No recent snippets found.</p>';
        }

        printf(
            '<p><a href="%s">Manage all snippets</a></p>',
            esc_url(admin_url('edit.php?post_type=code_snippet'))
        );
    }
}