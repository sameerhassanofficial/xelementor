<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Load configuration
require_once get_stylesheet_directory() . '/includes/config.php';

// Load enqueue functions
require_once get_stylesheet_directory() . '/includes/enqueue.php';

// Load Elementor helpers
require_once get_stylesheet_directory() . '/includes/elementor-helpers.php';

// Load WordPress helpers
require_once get_stylesheet_directory() . '/includes/wordpress-helpers.php';

// Load shortcodes
require_once get_stylesheet_directory() . '/includes/shortcodes.php';

// Load template parts
require_once get_stylesheet_directory() . '/includes/template-parts.php';


// Enqueue widget styles and scripts
function advanced_form_widget_assets() {
    wp_enqueue_style('advanced-form-widget', get_stylesheet_directory_uri() . '/assets/css/xform-widget.css', [], '1.0.0');
    wp_enqueue_script('advanced-form-widget', get_stylesheet_directory_uri() . '/assets/js/xform-widget.js', ['jquery'], '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('advanced-form-widget', 'advanced_form_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('advanced_form_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'advanced_form_widget_assets');


// Handle AJAX form submission
add_action('wp_ajax_submit_advanced_form', 'handle_advanced_form_submission');
add_action('wp_ajax_nopriv_submit_advanced_form', 'handle_advanced_form_submission');

function handle_advanced_form_submission() {
    // Enhanced security check
    if (!isset($_POST['advanced_form_nonce']) || !wp_verify_nonce($_POST['advanced_form_nonce'], 'advanced_form_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
        return;
    }
    
    // Check if required data exists
    if (!isset($_POST['form_data']) || !isset($_POST['form_id'])) {
        wp_send_json_error(['message' => 'Missing form data']);
        return;
    }
    
    $form_data = $_POST['form_data'];
    $form_id = sanitize_text_field($_POST['form_id']);
    
    // Process and sanitize form data
    $processed_data = [];
    if (is_array($form_data)) {
        foreach ($form_data as $field) {
            if (isset($field['name']) && isset($field['value'])) {
                $processed_data[sanitize_text_field($field['name'])] = sanitize_textarea_field($field['value']);
            }
        }
    }
    
    // Validate that we have some data
    if (empty($processed_data)) {
        wp_send_json_error(['message' => 'No valid form data received']);
        return;
    }
    
    // Try to save to database
    try {
        $result = save_form_submission($processed_data, $form_id);
        
        if ($result !== false) {
            $email_sent = false;
        if (isset($_POST['_send_email']) && $_POST['_send_email'] === 'yes') {
            $email_to = sanitize_email($_POST['_email_to']);
            $email_subject = sanitize_text_field($_POST['_email_subject']);
            $email_from_name = sanitize_text_field($_POST['_email_from_name']);
            $email_message_template = sanitize_textarea_field($_POST['_email_message']);
            
            // Prepare form data for email
            $form_data_str = '';
            foreach ($processed_data as $key => $value) {
                $form_data_str .= "{$key}: $value\n";
            }
            $email_message = str_replace('[all-fields]', $form_data_str, $email_message_template);
            
            // Set headers
            $headers = [
                'From: ' . $email_from_name . ' <' . $email_to . '>',
                'Content-Type: text/plain; charset=UTF-8',
            ];
            print_r($email_to);
            print_r($email_subject);
            print_r($email_from_name);
            print_r($email_message_template);
            print_r($email_message);exit;
            // Send email
            $email_sent = wp_mail($email_to, $email_subject, $email_message, $headers);
        }
        
        if ($email_sent && $result !== false) {
            wp_send_json_success(['message' => 'Form submitted successfully and email sent!']);
        } elseif ($result !== false) {
            wp_send_json_success(['message' => 'Form submitted successfully!']);
        } else {
            wp_send_json_error(['message' => 'Database error occurred']);
        }
        } else {
            wp_send_json_error(['message' => 'Database error occurred']);
        }
    } catch (Exception $e) {
        error_log('Form submission error: ' . $e->getMessage());
        wp_send_json_error(['message' => 'Form submission failed. Please try again.']);
    }
}


function save_form_submission($data, $form_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'advanced_form_submissions';
    
    // Get user IP safely
    $ip_address = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
    $result = $wpdb->insert(
        $table_name,
        [
            'form_id' => $form_id,
            'form_data' => wp_json_encode($data), // Use wp_json_encode instead of json_encode
            'submission_date' => current_time('mysql'),
            'ip_address' => sanitize_text_field($ip_address)
        ],
        [
            '%s', // form_id
            '%s', // form_data
            '%s', // submission_date
            '%s'  // ip_address
        ]
    );
    
    if ($result === false) {
        error_log('Database insert failed: ' . $wpdb->last_error);
    }
    
    return $result;
}


// Force create table on theme activation
function force_create_advanced_form_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'advanced_form_submissions';
    
    // Check if table exists
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id varchar(100) NOT NULL,
            form_data longtext NOT NULL,
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            ip_address varchar(45),
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('after_switch_theme', 'force_create_advanced_form_table');
add_action('init', 'force_create_advanced_form_table'); // Also run on init



function debug_form_submission() {
    if (isset($_POST['action']) && $_POST['action'] === 'submit_advanced_form') {
        error_log('Form submission debug: ' . print_r($_POST, true));
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'advanced_form_submissions';
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
        error_log('Table exists: ' . ($table_exists ? 'Yes' : 'No'));
    }
}
add_action('init', 'debug_form_submission');













?>
