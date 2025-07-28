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
            $email_custom_message = sanitize_textarea_field($_POST['_email_custom_message']);
            $email_message_template = sanitize_textarea_field($_POST['_email_message']);
            $email_format = sanitize_text_field($_POST['_email_format']);
            
            // Debug: Log the email format
            error_log('XForm Email Format: ' . $email_format);
            
            // Prepare form data for email with clean formatting
            $form_data_str = '';
            $form_data_html = '';
            
            // Fields to exclude from email - only show actual user input
            $exclude_fields = [
                'advanced_form_nonce', 'action', 'form_id', 'form_data',
                'wp_http_referer', 'send_email', 'email_to', 'email_subject', 
                'email_from_name', 'email_message', 'email_format', 'email_custom_message',
                '_send_email', '_email_to', '_email_subject', '_email_from_name', 
                '_email_message', '_email_format', '_email_custom_message',
                'wp_http_referer', 'http_referer', 'referer'
            ];
            
            foreach ($processed_data as $key => $value) {
                // Skip empty values and system fields
                if (empty($value) || in_array($key, $exclude_fields)) {
                    continue;
                }
                
                // Skip any field that starts with underscore or contains system keywords
                if (strpos($key, '_') === 0 || 
                    in_array(strtolower($key), ['wp_http_referer', 'http_referer', 'referer', 'action', 'nonce', 'form_id', 'form_data'])) {
                    continue;
                }
                
                // Clean up field names for better readability
                $clean_key = ucwords(str_replace(['_', '-'], ' ', $key));
                
                // Plain text format
                $form_data_str .= $clean_key . ": " . $value . "\n";
                
                // HTML format with email-client compatible styling
                $form_data_html .= "<tr><td style='padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-weight: bold; color: #475569; width: 35%; font-size: 14px; font-family: Arial, sans-serif;'>" . $clean_key . "</td><td style='padding: 16px 20px; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 14px; line-height: 1.5; font-family: Arial, sans-serif;'>" . esc_html($value) . "</td></tr>";
            }
            
            if ($email_format === 'html') {
                // Modern, beautiful HTML email template with email-client compatible CSS
                $html_template = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>New Message</title>
                </head>
                <body style="margin: 0; padding: 20px; font-family: Arial, sans-serif; background-color: #667eea;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #667eea;">
                        <tr>
                            <td align="center" style="padding: 20px;">
                                <table width="500" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
                                    
                                    <!-- Header -->
                                    <tr>
                                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; border-radius: 20px 20px 0 0;">
                                            <div style="width: 60px; height: 60px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; margin: 0 auto 20px; display: inline-block; line-height: 60px; text-align: center;">
                                                <span style="font-size: 24px; color: white;">📧</span>
                                            </div>
                                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">New Message</h1>
                                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px;">You have received a new form submission</p>
                                        </td>
                                    </tr>
                                    
                                    <!-- Content -->
                                    <tr>
                                        <td style="padding: 40px 30px;">
                                            ' . (!empty($email_custom_message) ? '<div style="background-color: #f0f9ff; border-left: 4px solid #3b82f6; padding: 20px; margin-bottom: 30px; border-radius: 0 8px 8px 0;"><p style="margin: 0; color: #1e40af; font-size: 16px; line-height: 1.6;">' . nl2br(esc_html($email_custom_message)) . '</p></div>' : '') . '
                                            
                                            <!-- Form Data -->
                                            <div style="background-color: #f8fafc; border-radius: 16px; padding: 30px; border: 1px solid #e2e8f0;">
                                                <h2 style="margin: 0 0 25px 0; color: #1f2937; font-size: 20px; font-weight: bold; text-align: center;">Message Details</h2>
                                                
                                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                                    <tbody>
                                                        ' . $form_data_html . '
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';
                
                $email_message = $html_template;
            } else {
                // Clean plain text format
                if (!empty($email_custom_message)) {
                    $email_message = $email_custom_message . "\n\n";
                }
                $email_message .= str_replace('[all-fields]', $form_data_str, $email_message_template);
            }
            
            // Set headers - use admin email as from address for better deliverability
            $admin_email = get_option('admin_email');
            $content_type = ($email_format === 'html') ? 'text/html' : 'text/plain';
            $headers = [
                'From: ' . $email_from_name . ' <' . $admin_email . '>',
                'Reply-To: ' . $admin_email,
                'Content-Type: ' . $content_type . '; charset=UTF-8',
                'MIME-Version: 1.0',
                'X-Mailer: WordPress',
            ];
            
            // Send email with better error handling
            $email_sent = wp_mail($email_to, $email_subject, $email_message, $headers);
            
            // If HTML email fails, try plain text as fallback
            if (!$email_sent && $email_format === 'html') {
                error_log('XForm HTML email failed, trying plain text fallback');
                
                // Create plain text version
                $plain_text_message = '';
                if (!empty($email_custom_message)) {
                    $plain_text_message = $email_custom_message . "\n\n";
                }
                $plain_text_message .= $form_data_str;
                
                // Try with plain text headers
                $plain_headers = [
                    'From: ' . $email_from_name . ' <' . $admin_email . '>',
                    'Reply-To: ' . $admin_email,
                    'Content-Type: text/plain; charset=UTF-8',
                    'X-Mailer: WordPress',
                ];
                
                $email_sent = wp_mail($email_to, $email_subject, $plain_text_message, $plain_headers);
                
                if ($email_sent) {
                    error_log('XForm Plain text fallback email sent successfully');
                }
            }
            
            // Log email attempt for debugging
            if (!$email_sent) {
                error_log('XForm Email failed to send to: ' . $email_to . ' Subject: ' . $email_subject . ' Format: ' . $email_format);
                error_log('XForm Email Headers: ' . print_r($headers, true));
                error_log('XForm Email Content Length: ' . strlen($email_message));
            } else {
                error_log('XForm Email sent successfully to: ' . $email_to . ' Format: ' . $email_format);
            }
        }
        
        if ($result !== false) {
            if (isset($_POST['_send_email']) && $_POST['_send_email'] === 'yes') {
                if ($email_sent) {
                    wp_send_json_success(['message' => 'Form submitted successfully and email sent!']);
                } else {
                    wp_send_json_success(['message' => 'Form submitted successfully, but email could not be sent. Please check your email configuration.']);
                }
            } else {
                wp_send_json_success(['message' => 'Form submitted successfully!']);
            }
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

// Add email testing function
function test_xform_email() {
    if (isset($_GET['test_xform_email']) && current_user_can('manage_options')) {
        $admin_email = get_option('admin_email');
        $subject = 'Email Test - ' . get_bloginfo('name');
        $message = "This is a test email to verify your email configuration is working properly.\n\nIf you received this email, your SMTP settings are correctly configured.";
        $headers = [
            'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: WordPress',
        ];
        
        $sent = wp_mail($admin_email, $subject, $message, $headers);
        
        if ($sent) {
            echo '<div style="background: green; color: white; padding: 10px; margin: 10px;">✅ Email test successful! Check your inbox.</div>';
        } else {
            echo '<div style="background: red; color: white; padding: 10px; margin: 10px;">❌ Email test failed! Check your SMTP configuration.</div>';
        }
        
        // Log the test
        error_log('XForm Email Test - Sent: ' . ($sent ? 'Yes' : 'No'));
        
        // Show SMTP info if available
        if (function_exists('wp_mail_smtp_info')) {
            echo '<div style="background: blue; color: white; padding: 10px; margin: 10px;">SMTP Plugin detected</div>';
        }
    }
}
add_action('init', 'test_xform_email');













?>
