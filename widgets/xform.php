<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor XForm Widget.
 *
 * Elementor widget that displays a customizable form.
 *
 * @since 1.0.0
 */
class XForm_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve xform widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name() {
        return 'xform';
    }

    /**
     * Get widget title.
     *
     * Retrieve xform widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('XForm', 'xform-widget');
    }

    /**
     * Get widget icon.
     *
     * Retrieve xform widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the xform widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['basic']; // Or a custom category e.g., ['x-widgets']
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the xform widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['form', 'contact', 'xform', 'custom form'];
    }

    /**
     * Register xform widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_form_settings',
            [
                'label' => esc_html__('Form Settings', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_title',
            [
                'label' => esc_html__('Form Title', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Contact Us', 'xform-widget'),
            ]
        );

        $this->add_control(
            'form_description',
            [
                'label' => esc_html__('Form Description', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_form_fields',
            [
                'label' => esc_html__('Form Fields', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label' => esc_html__('Field Type', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => esc_html__('Text', 'xform-widget'),
                    'email' => esc_html__('Email', 'xform-widget'),
                    'textarea' => esc_html__('Textarea', 'xform-widget'),
                ],
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label' => esc_html__('Label', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Field Label', 'xform-widget'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'field_placeholder',
            [
                'label' => esc_html__('Placeholder', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Enter value', 'xform-widget'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'field_required',
            [
                'label' => esc_html__('Required', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'xform-widget'),
                'label_off' => esc_html__('No', 'xform-widget'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $repeater->add_control(
            'field_width',
            [
                'label' => esc_html__('Width', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '100',
                'options' => [
                    '100' => esc_html__('100%', 'xform-widget'),
                    '50' => esc_html__('50%', 'xform-widget'),
                    // Add more options like 33%, 25% later if needed
                ],
            ]
        );

        $this->add_control(
            'form_fields',
            [
                'label' => esc_html__('Fields', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'field_type' => 'text',
                        'field_label' => esc_html__('Name', 'xform-widget'),
                        'field_placeholder' => esc_html__('Your Name', 'xform-widget'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                    [
                        'field_type' => 'email',
                        'field_label' => esc_html__('Email', 'xform-widget'),
                        'field_placeholder' => esc_html__('Your Email', 'xform-widget'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                    [
                        'field_type' => 'textarea',
                        'field_label' => esc_html__('Message', 'xform-widget'),
                        'field_placeholder' => esc_html__('Your Message', 'xform-widget'),
                        'field_required' => 'yes',
                        'field_width' => '100',
                    ],
                ],
                'title_field' => '{{{ field_label }}} ({{{ field_type }}})‎',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_submit_button',
            [
                'label' => esc_html__('Submit Button', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'submit_text',
            [
                'label' => esc_html__('Button Text', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Submit', 'xform-widget'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_email_settings',
            [
                'label' => esc_html__('Email Settings', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'email_to',
            [
                'label' => esc_html__('Email To', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => get_option('admin_email'),
                'label_block' => true,
                'description' => esc_html__('The email address where submissions will be sent.', 'xform-widget'),
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label' => esc_html__('Email Subject', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('New Form Submission from {site_title}', 'xform-widget'),
                'label_block' => true,
                'description' => esc_html__('You can use {site_title} shortcode.', 'xform-widget'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => esc_html__('Success Message', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Your message has been sent successfully.', 'xform-widget'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label' => esc_html__('Error Message', 'xform-widget'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('An error occurred. Please try again.', 'xform-widget'),
                'rows' => 3,
            ]
        );

        $this->end_controls_section();

        // Basic Style Tab (Placeholders for now, will be expanded)
        $this->start_controls_section(
            'section_style_form',
            [
                'label' => esc_html__('Form Container', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // Placeholder for form container styling - e.g. spacing, background
        $this->add_responsive_control(
            'form_padding',
            [
                'label' => esc_html__( 'Padding', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .xform-widget-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_fields',
            [
                'label' => esc_html__('Fields', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // Placeholder for field styling - e.g. typography, color, background, border
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .xform-field-wrap input, {{WRAPPER}} .xform-field-wrap textarea, {{WRAPPER}} .xform-field-wrap select',
            ]
        );
         $this->add_control(
            'field_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xform-field-wrap input, {{WRAPPER}} .xform-field-wrap textarea, {{WRAPPER}} .xform-field-wrap select' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'field_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xform-field-wrap input, {{WRAPPER}} .xform-field-wrap textarea, {{WRAPPER}} .xform-field-wrap select' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} .xform-field-wrap input, {{WRAPPER}} .xform-field-wrap textarea, {{WRAPPER}} .xform-field-wrap select',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'xform-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // Placeholder for button styling - e.g. typography, color, background, border, padding
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .xform-submit-button',
            ]
        );
        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xform-submit-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xform-submit-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
         $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .xform-submit-button',
            ]
        );
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'xform-widget' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .xform-submit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render xform widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $form_id = 'xform-' . $this->get_id();

        // Placeholder for displaying messages after form submission
        // This will be populated by the submission handler function
        global $xform_messages;
        $message_html = '';
        if (isset($xform_messages[$form_id]) && !empty($xform_messages[$form_id])) {
            $message_data = $xform_messages[$form_id];
            $message_class = $message_data['type'] === 'success' ? 'xform-message-success' : 'xform-message-error';
            $message_html = '<div class="xform-messages ' . esc_attr($message_class) . '">' . esc_html($message_data['message']) . '</div>';
            unset($xform_messages[$form_id]); // Clear message after displaying
        }

        ?>
        <style>
            .xform-fields-wrapper {
                display: flex;
                flex-wrap: wrap;
                gap: 20px; /* Adjust gap as needed */
            }
            .xform-field-wrap {
                box-sizing: border-box;
            }
            .xform-field-width-100 {
                width: 100%;
            }
            .xform-field-width-50 {
                width: calc(50% - 10px); /* Adjust based on gap / 2 */
            }
            /* Basic responsive for 50% width fields */
            @media (max-width: 767px) {
                .xform-field-width-50 {
                    width: 100%;
                }
            }
            .xform-field-label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .xform-input, .xform-textarea {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid #ccc;
                box-sizing: border-box;
            }
            .xform-required-indicator {
                color: red;
                margin-left: 3px;
            }
            .xform-submit-button-wrapper {
                margin-top: 20px;
            }
            .xform-messages {
                padding: 15px;
                margin-top: 20px;
                border-radius: 4px;
            }
            .xform-message-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .xform-message-error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
        <div class="xform-widget-wrapper">
            <?php if (!empty($settings['form_title'])) : ?>
                <h3 class="xform-title"><?php echo esc_html($settings['form_title']); ?></h3>
            <?php endif; ?>

            <?php if (!empty($settings['form_description'])) : ?>
                <p class="xform-description"><?php echo wp_kses_post($settings['form_description']); ?></p>
            <?php endif; ?>

            <?php echo $message_html; // Display success/error messages here ?>

            <form id="<?php echo esc_attr($form_id); ?>" class="xform-widget-form" method="post" action="<?php echo esc_url(htmlspecialchars($_SERVER['REQUEST_URI'])); ?>">
                <input type="hidden" name="action" value="xform_submit_action">
                <input type="hidden" name="xform_widget_id" value="<?php echo esc_attr($this->get_id()); ?>">
                <input type="hidden" name="xform_email_to" value="<?php echo esc_attr($settings['email_to']); ?>">
                <input type="hidden" name="xform_email_subject" value="<?php echo esc_attr($settings['email_subject']); ?>">
                <input type="hidden" name="xform_success_message_setting" value="<?php echo esc_attr($settings['success_message']); ?>">
                <input type="hidden" name="xform_error_message_setting" value="<?php echo esc_attr($settings['error_message']); ?>">
                <?php wp_nonce_field('xform_submit_action_nonce_' . $this->get_id(), 'xform_nonce_field'); ?>

                <div class="xform-fields-wrapper">
                    <?php
                    if (!empty($settings['form_fields'])) {
                        foreach ($settings['form_fields'] as $field_settings) {
                            $this->render_field($field_settings, $form_id);
                        }
                    }
                    ?>
                </div>

                <?php if (!empty($settings['submit_text'])) : ?>
                    <div class="xform-submit-button-wrapper">
                        <button type="submit" class="xform-submit-button">
                            <?php echo esc_html($settings['submit_text']); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render xform widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    // protected function content_template() {
        // Editor preview will be handled in a later step if needed
    // }

    /**
     * Render a single form field.
     *
     * @since 1.0.0
     * @access private
     * @param array $field_settings The settings for the individual field.
     * @param string $form_id The ID of the form, used for unique field IDs.
     */
    private function render_field($field_settings, $form_id) {
        $field_id_base = $form_id . '-' . $field_settings['_id'];
        // Use a more specific name structure for POST data to easily identify XForm fields
        $field_name = 'xform_fields[' . esc_attr($field_settings['_id']) . ']';
        $required_attr = ($field_settings['field_required'] === 'yes') ? 'required' : '';
        $placeholder_attr = esc_attr($field_settings['field_placeholder']);
        $label_text = esc_html($field_settings['field_label']);

        $field_classes = ['xform-field-wrap'];
        if (!empty($field_settings['field_type'])) {
            $field_classes[] = 'xform-field-type-' . esc_attr($field_settings['field_type']);
        }
        if (!empty($field_settings['field_width'])) {
            $field_classes[] = 'xform-field-width-' . esc_attr($field_settings['field_width']);
        }
         if ($field_settings['field_required'] === 'yes') {
            $field_classes[] = 'xform-field-required';
        }

        ?>
        <div class="<?php echo esc_attr(implode(' ', $field_classes)); ?>">
            <?php if (!empty($label_text)) : ?>
                <label for="<?php echo esc_attr($field_id_base); ?>" class="xform-field-label">
                    <?php echo $label_text; ?>
                    <?php if ($field_settings['field_required'] === 'yes') : ?>
                        <span class="xform-required-indicator">*</span>
                    <?php endif; ?>
                </label>
            <?php endif; ?>

            <?php
            switch ($field_settings['field_type']) {
                case 'text':
                    ?>
                    <input type="text"
                           id="<?php echo esc_attr($field_id_base); ?>"
                           name="<?php echo esc_attr($field_name); ?>"
                           placeholder="<?php echo $placeholder_attr; ?>"
                           <?php echo $required_attr; ?>
                           class="xform-input xform-input-text">
                    <?php
                    break;
                case 'email':
                    ?>
                    <input type="email"
                           id="<?php echo esc_attr($field_id_base); ?>"
                           name="<?php echo esc_attr($field_name); ?>"
                           placeholder="<?php echo $placeholder_attr; ?>"
                           <?php echo $required_attr; ?>
                           class="xform-input xform-input-email">
                    <?php
                    break;
                case 'textarea':
                    ?>
                    <textarea id="<?php echo esc_attr($field_id_base); ?>"
                              name="<?php echo esc_attr($field_name); ?>"
                              placeholder="<?php echo $placeholder_attr; ?>"
                              <?php echo $required_attr; ?>
                              class="xform-textarea"></textarea>
                    <?php
                    break;
                // Add more cases for other field types in later stages
            }
            ?>
        </div>
        <?php
    }
}

// Initialize global message variable if not already set
if (!isset($GLOBALS['xform_messages'])) {
    $GLOBALS['xform_messages'] = [];
}

/**
 * Handles XForm submission.
 *
 * @since 1.0.0
 */
function xform_handle_submission_action() {
    // Ensure this is a POST request and our specific action
    if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($_POST['action']) || 'xform_submit_action' !== $_POST['action']) {
        return;
    }

    $widget_id = isset($_POST['xform_widget_id']) ? sanitize_key($_POST['xform_widget_id']) : null;
    if (!$widget_id) {
        // Cannot proceed without widget_id to verify nonce or get settings
        return;
    }

    // Verify nonce
    if (!isset($_POST['xform_nonce_field']) || !wp_verify_nonce($_POST['xform_nonce_field'], 'xform_submit_action_nonce_' . $widget_id)) {
        $GLOBALS['xform_messages']['xform-' . $widget_id] = [
            'type' => 'error',
            'message' => esc_html__('Security check failed. Please try again.', 'xform-widget')
        ];
        return;
    }

    // Get email settings from hidden fields (passed from render method)
    $email_to = isset($_POST['xform_email_to']) ? sanitize_email($_POST['xform_email_to']) : get_option('admin_email');
    $email_subject_template = isset($_POST['xform_email_subject']) ? sanitize_text_field($_POST['xform_email_subject']) : 'New Form Submission from {site_title}';

    // Replace placeholders in subject
    $email_subject = str_replace('{site_title}', get_bloginfo('name'), $email_subject_template);

    $errors = [];
    $form_data_for_email = [];
    $submitted_fields = isset($_POST['xform_fields']) && is_array($_POST['xform_fields']) ? $_POST['xform_fields'] : [];

    // Note: Validating required fields accurately server-side without AJAX
    // requires knowing the original field settings (label, required status).
    // This is complex because the settings are stored with the Elementor page data.
    // For Stage 1, we'll do basic "is empty" check if the key exists.
    // A more robust solution would involve retrieving widget settings using Elementor API on the server,
    // or passing more field metadata via hidden inputs (can be cumbersome and less secure).

    // For now, we'll assume all submitted fields from `xform_fields` should be processed.
    // We don't have easy access to the 'field_label' or 'field_required' setting from the `register_controls` here
    // without fetching the Elementor widget settings, which is complex in this non-AJAX context.
    // So, we will use the field key as a pseudo-label.

    if (empty($submitted_fields)) {
         $errors[] = esc_html__('No data submitted.', 'xform-widget');
    }

    foreach ($submitted_fields as $field_key => $value) {
        $sanitized_value = '';
        // Basic sanitization. For specific field types, more specific sanitization would be needed.
        if (is_array($value)) {
            $sanitized_value = array_map('sanitize_text_field', $value); // Or wp_kses_post for textarea if HTML is allowed
            $form_data_for_email[esc_html__('Field', 'xform-widget') . ' ' . sanitize_key($field_key)] = implode(', ', $sanitized_value);
        } else {
            $sanitized_value = sanitize_text_field(stripslashes($value)); // Or wp_kses_post for textarea
            $form_data_for_email[esc_html__('Field', 'xform-widget') . ' ' . sanitize_key($field_key)] = $sanitized_value;
        }

        // Simplistic required check: if a field key exists in POST but value is empty.
        // This doesn't know if it was *actually* marked required in Elementor settings.
        // A proper solution would need to compare against actual widget settings.
        // For now, we'll skip server-side "required" validation to avoid complexity
        // and rely on the browser's `required` attribute.
        // If we had labels easily:
        // $field_settings = ... // Logic to get specific field settings for $field_key
        // if ($field_settings['field_required'] === 'yes' && empty($sanitized_value)) {
        //    $errors[] = $field_settings['field_label'] . ' ' . esc_html__('is required.', 'xform-widget');
        // }

        // Basic Email Validation (if we could identify email fields)
        // if ($field_settings['field_type'] === 'email' && !is_email($sanitized_value)) {
        //    $errors[] = $field_settings['field_label'] . ': ' . esc_html__('Invalid email format.', 'xform-widget');
        // }
    }


    if (!empty($errors)) {
        $GLOBALS['xform_messages']['xform-' . $widget_id] = [
            'type' => 'error',
            // For Stage 1, we'll use the generic error message from settings,
            // as displaying individual field errors is more complex without AJAX/JS.
            'message' => sanitize_text_field($_POST['xform_error_message_setting'] ?? esc_html__('Please correct the errors and try again.', 'xform-widget'))
            // 'message' => implode('<br>', $errors) // Alternative: show all errors
        ];
    } else {
        $email_body = esc_html__("You have received a new message from your website's contact form.", 'xform-widget') . "\n\n";
        foreach ($form_data_for_email as $label => $value) {
            $email_body .= $label . ": " . $value . "\n";
        }
        $email_body .= "\n--\n";
        $email_body .= sprintf(esc_html__('This email was sent from a contact form on %s (%s)', 'xform-widget'), get_bloginfo('name'), home_url()) . "\n";

        $headers = [];
        // Attempt to get a "reply-to" from an email field if one was submitted.
        // This is a basic attempt; a more robust solution would identify the primary email field.
        foreach ($submitted_fields as $field_key => $value) {
            if (is_email(sanitize_email($value))) {
                $headers[] = 'Reply-To: ' . sanitize_email($value);
                break;
            }
        }
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';


        $mail_sent = wp_mail($email_to, $email_subject, $email_body, $headers);

        if ($mail_sent) {
            $GLOBALS['xform_messages']['xform-' . $widget_id] = [
                'type' => 'success',
                'message' => sanitize_text_field($_POST['xform_success_message_setting'] ?? esc_html__('Your message has been sent successfully.', 'xform-widget'))
            ];
        } else {
            $GLOBALS['xform_messages']['xform-' . $widget_id] = [
                'type' => 'error',
                'message' => sanitize_text_field($_POST['xform_error_message_setting'] ?? esc_html__('The email could not be sent.', 'xform-widget'))
            ];
        }
    }
}
add_action('template_redirect', 'xform_handle_submission_action');

/**
 * Register XForm Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_xform_widget( $widgets_manager ) {
    // Since the class is in the same file, we just need to register it.
    // If it were in a separate file, you would include it here first.
    // require_once( __DIR__ . '/widgets/xform.php' ); // Example if in separate file

    $widgets_manager->register( new XForm_Widget() );
}
add_action( 'elementor/widgets/register', 'register_xform_widget' );
?>
