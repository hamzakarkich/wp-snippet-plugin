<?php
class WP_Snippet_Manager_Meta_Box {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
    }

    public function add_meta_box() {
        add_meta_box(
            'snippet_location',
            __('Snippet Location', 'wp-snippet-manager'),
            array($this, 'render_meta_box'),
            'code_snippet',
            'side',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('wp_snippet_meta_box', 'wp_snippet_meta_box_nonce');

        $location = get_post_meta($post->ID, '_snippet_location', true);
        ?>
        <p>
            <label for="snippet_location"><?php _e('Insert Snippet in:', 'wp-snippet-manager'); ?></label>
            <select name="snippet_location" id="snippet_location" class="widefat">
                <option value="header" <?php selected($location, 'header'); ?>><?php _e('Header', 'wp-snippet-manager'); ?></option>
                <option value="footer" <?php selected($location, 'footer'); ?>><?php _e('Footer', 'wp-snippet-manager'); ?></option>
                <option value="none" <?php selected($location, 'none'); ?>><?php _e('Do not auto-insert', 'wp-snippet-manager'); ?></option>
            </select>
        </p>
        <p class="description">
            <?php _e('Choose where to automatically insert this snippet, or select "Do not auto-insert" to use it only with shortcodes.', 'wp-snippet-manager'); ?>
        </p>
        <?php
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['wp_snippet_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['wp_snippet_meta_box_nonce'], 'wp_snippet_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['snippet_location'])) {
            update_post_meta($post_id, '_snippet_location', sanitize_text_field($_POST['snippet_location']));
        }
    }
}