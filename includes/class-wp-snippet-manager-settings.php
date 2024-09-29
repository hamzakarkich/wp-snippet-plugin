<?php
class WP_Snippet_Manager_Settings {
    private $options;

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page() {
        add_options_page(
            'WP Snippet Manager Settings',
            'WP Snippet Manager',
            'manage_options',
            'wp-snippet-manager',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        $this->options = get_option('wp_snippet_manager_options');
        ?>
        <div class="wrap">
            <h1>WP Snippet Manager Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('wp_snippet_manager_option_group');
                do_settings_sections('wp-snippet-manager-admin');
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        register_setting(
            'wp_snippet_manager_option_group',
            'wp_snippet_manager_options',
            array($this, 'sanitize')
        );

        add_settings_section(
            'wp_snippet_manager_setting_section',
            'General Settings',
            array($this, 'section_info'),
            'wp-snippet-manager-admin'
        );

        add_settings_field(
            'enable_syntax_highlighting',
            'Enable Syntax Highlighting',
            array($this, 'enable_syntax_highlighting_callback'),
            'wp-snippet-manager-admin',
            'wp_snippet_manager_setting_section'
        );
    }

    public function sanitize($input) {
        $new_input = array();
        if(isset($input['enable_syntax_highlighting']))
            $new_input['enable_syntax_highlighting'] = sanitize_text_field($input['enable_syntax_highlighting']);

        return $new_input;
    }

    public function section_info() {
        print 'Enter your settings below:';
    }

    public function enable_syntax_highlighting_callback() {
        $value = isset($this->options['enable_syntax_highlighting']) ? esc_attr($this->options['enable_syntax_highlighting']) : 'off';
        ?>
        <input type="checkbox" id="enable_syntax_highlighting" name="wp_snippet_manager_options[enable_syntax_highlighting]" value="on" <?php checked($value, 'on'); ?> />
        <label for="enable_syntax_highlighting">Enable syntax highlighting for snippets</label>
        <?php
    }
}