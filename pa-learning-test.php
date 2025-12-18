<?php
/**
 * Plugin Name: PA Learning Test Plugin
 * Plugin URI: https://example.com/
 * Description: A minimal plugin demonstrating WordPress core APIs, hooks, and coding standards.
 * Version: 1.0.0
 * Author: Felopater Adel
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PA_Learning_Test_Plugin {
    
    private $option_name = 'pa_learning_test_value';
    
    public function __construct() {
        // Initialize hooks
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_shortcode('pa_learning_message', array($this, 'shortcode_output'));
    }
    
    /**
     * Add settings page under Settings menu
     */
        public function add_settings_page() {
            add_options_page(
                'PA Learning Test Settings',          // Page title
                'PA Learning Test',                   // Menu title
                'manage_options',                     // Capability
                'pa-learning-test',                   // Menu slug
                array($this, 'render_settings_page') // Callback function
            );
        }
        
        /**
         * Register setting using WordPress Settings API
         */
        public function register_settings() {
            register_setting(
                'pa_learning_test_group',            // Option group
                $this->option_name,                  // Option name
                array(
                    'type' => 'string',
                    'sanitize_callback' => array($this, 'sanitize_input'), // Sanitization
                    'default' => ''
                )
            );
            
            add_settings_section(
                'pa_learning_test_main_section',     // Section ID
                'Main Settings',                     // Section title
                null,                                // Callback for description
                'pa-learning-test'                   // Page slug
            );
            
            add_settings_field(
                'pa_learning_test_field',            // Field ID
                'Enter your message',                // Field title
                array($this, 'render_text_field'),   // Callback to render field
                'pa-learning-test',                  // Page slug
                'pa_learning_test_main_section'      // Section ID
            );
        }
        
        /**
         * Sanitize input data
         */
        public function sanitize_input($input) {
            // Sanitize text field - strip tags, allow basic HTML if needed
            return sanitize_text_field($input);
        }
        
        /**
         * Render the text field
         */
        public function render_text_field() {
            $value = get_option($this->option_name, '');
            ?>
            <input type="text" 
                name="<?php echo esc_attr($this->option_name); ?>" 
                value="<?php echo esc_attr($value); ?>"
                class="regular-text">
            <?php
        }
        
        /**
         * Render the settings page
         */
        public function render_settings_page() {
            ?>
            <div class="wrap" style="max-width: 600px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h1 style="text-align: center; color: #333; margin-bottom: 30px;"><?php echo esc_html__('PA Learning Test Settings', 'pa-learning-test'); ?></h1>
                
                <form method="post" action="options.php">
                    <?php
                    settings_fields('pa_learning_test_group');
                    do_settings_sections('pa-learning-test');
                    submit_button(__('Save Settings'), 'primary', 'submit', true);
                    ?>
                </form>
                <?php $this->display_saved_value(); ?>

            </div>
            <?php
        }
        
        /**
         * Display saved value below the form
         */
        private function display_saved_value() {
            $value = get_option($this->option_name, '');
            if (!empty($value)) {
                ?>
                    <div class="updated inline" style="margin-top:1em; clear:both;">
                    <h3><?php echo esc_html__('Current Saved Value:', 'pa-learning-test'); ?></h3>
                    <p><?php echo esc_html($value); ?></p>
                    <p><strong><?php echo esc_html__('Shortcode:', 'pa-learning-test'); ?></strong> 
                    <code>[pa_learning_message]</code></p>
                </div>
                <?php
            }
        }
        
        /**
         * Shortcode callback function
         */
        public function shortcode_output($atts) {
            // Get attributes if any are passed
            $atts = shortcode_atts(array(), $atts, 'pa_learning_message');
            
            // Get the saved value
            $value = get_option($this->option_name, '');
            
            // Escape and return the output
            return esc_html($value);
        }
    }

    // Initialize the plugin
    new PA_Learning_Test_Plugin();