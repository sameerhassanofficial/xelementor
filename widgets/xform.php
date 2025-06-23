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
     * Get script dependencies.
     *
     * Retrieve the list of script dependencies the widget requires.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        // First, register the script.
        wp_register_script(
            'xform-frontend-script',
            get_stylesheet_directory_uri() . '/assets/js/xform-frontend.js', // Assuming assets/js is in the theme root. Adjust if xform is a plugin.
            ['jquery'],
            '1.0.0', // Version
            true     // In footer
        );

        // Localize script with AJAX URL and other data if needed
        wp_localize_script(
            'xform-frontend-script',
            'xform_ajax_obj',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                // We can add other data like nonce here if needed for JS-side validation or specific actions
                // 'nonce' => wp_create_nonce('xform_ajax_nonce_example') // Example, actual nonce is on the form
            ]
        );

        return ['xform-frontend-script'];
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
        $settings = $this->get_settings_for_display();
        $form_id = 'xform-' . $this->get_id();
        $submit_button_text = !empty($settings['submit_text']) ? esc_html($settings['submit_text']) : esc_html__('Submit', 'xform-widget');

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
                display: none; /* Initially hidden, shown by JS */
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

            <div class="xform-messages"><!-- Populated by JavaScript --></div>

            <form id="<?php echo esc_attr($form_id); ?>" class="xform-widget-form xform-ajax-form" method="post" action="">
                <?php // The 'action' attribute is not strictly necessary for AJAX forms but can be a fallback or for non-JS users if we add that later. ?>
                <input type="hidden" name="action" value="xform_submit_action"> <?php // This is for WordPress AJAX handler ?>
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
        $label_text = esc_html($field_settings['field_label']);

        // Create a sanitized key from the label for the field name, or fallback to _id
        $field_name_key = !empty($field_settings['field_label']) ? sanitize_title($field_settings['field_label']) : $field_settings['_id'];
        $field_name = 'xform_fields[' . esc_attr($field_name_key) . ']';

        $required_attr = ($field_settings['field_required'] === 'yes') ? 'required' : '';
        $placeholder_attr = esc_attr($field_settings['field_placeholder']);

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
    error_log('[XForm Debug] Entered xform_handle_submission_action.');

    // Ensure this is a POST request and our specific action
    if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($_POST['action']) || 'xform_submit_action' !== $_POST['action']) {
        if ('POST' === $_SERVER['REQUEST_METHOD']) { // Log if it's POST but not our action
            error_log('[XForm Debug] POST request received, but not xform_submit_action. Action: ' . (isset($_POST['action']) ? esc_html($_POST['action']) : 'Not set'));
        }
        return;
    }
    error_log('[XForm Debug] Matched POST and action xform_submit_action.');

    $widget_id = isset($_POST['xform_widget_id']) ? sanitize_key($_POST['xform_widget_id']) : null;
    if (!$widget_id) {
        error_log('[XForm Debug] Error: xform_widget_id not found in POST.');
        // Optionally set a generic error message if you can't tie it to a form
        return;
    }
    error_log('[XForm Debug] Widget ID: ' . esc_html($widget_id));

    // Verify nonce
    if (!isset($_POST['xform_nonce_field']) || !wp_verify_nonce($_POST['xform_nonce_field'], 'xform_submit_action_nonce_' . $widget_id)) {
        error_log('[XForm Debug] Nonce verification failed for widget ID: ' . esc_html($widget_id));
        $GLOBALS['xform_messages']['xform-' . $widget_id] = [
            'type' => 'error',
            'message' => esc_html__('Security check failed. Please try again.', 'xform-widget')
        ];
        return;
    }
    error_log('[XForm Debug] Nonce verified successfully for widget ID: ' . esc_html($widget_id));

    // Get email settings from hidden fields
    $email_to = isset($_POST['xform_email_to']) ? sanitize_email($_POST['xform_email_to']) : '';
    $email_subject_template = isset($_POST['xform_email_subject']) ? sanitize_text_field($_POST['xform_email_subject']) : 'New Form Submission from {site_title}';
    $success_message_setting = isset($_POST['xform_success_message_setting']) ? sanitize_text_field($_POST['xform_success_message_setting']) : esc_html__('Your message has been sent successfully.', 'xform-widget');
    $error_message_setting = isset($_POST['xform_error_message_setting']) ? sanitize_text_field($_POST['xform_error_message_setting']) : esc_html__('The email could not be sent. Please check site email configuration.', 'xform-widget');

    if (empty($email_to)) {
        error_log('[XForm Debug] Error: Email To address is empty. Widget ID: ' . esc_html($widget_id));
        $GLOBALS['xform_messages']['xform-' . $widget_id] = [
            'type' => 'error',
            'message' => esc_html__('Admin email (Email To) is not configured for this form.', 'xform-widget')
        ];
        return;
    }
    error_log('[XForm Debug] Email To: ' . esc_html($email_to) . ', Subject Template: ' . esc_html($email_subject_template));

    $email_subject = str_replace('{site_title}', get_bloginfo('name'), $email_subject_template);

    $errors = [];
    $form_data_for_email = [];
    $submitted_fields = isset($_POST['xform_fields']) && is_array($_POST['xform_fields']) ? $_POST['xform_fields'] : [];

    if (empty($submitted_fields) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['action']) && $_POST['action'] === 'xform_submit_action') {
         error_log('[XForm Debug] No xform_fields data submitted despite form action match. Widget ID: ' . esc_html($widget_id));
         // $errors[] = esc_html__('No data submitted.', 'xform-widget'); // This might be too strict if form can be empty
    }

    $reply_to_email = null;

    foreach ($submitted_fields as $field_name_key => $value) {
        $label = ucwords(str_replace(['-', '_'], ' ', sanitize_key($field_name_key))); // Sanitize key before using in str_replace

        $sanitized_value = '';
        if (is_array($value)) {
            $sanitized_value = array_map('sanitize_text_field', $value);
            $form_data_for_email[$label] = implode(', ', $sanitized_value);
        } else {
            $raw_value = stripslashes((string)$value); // Cast to string before stripslashes
            if (!$reply_to_email && (stripos($label, 'email') !== false || stripos($label, 'e-mail') !== false) && is_email($raw_value)) {
                $reply_to_email = sanitize_email($raw_value);
            }
            $sanitized_value = sanitize_text_field($raw_value);
            $form_data_for_email[$label] = $sanitized_value;
        }
        error_log("[XForm Debug] Processed field - Label: " . esc_html($label) . ", Sanitized Value: " . esc_html(is_array($sanitized_value) ? implode(', ', $sanitized_value) : $sanitized_value));
    }

    // Server-side "required" validation is still a challenge here without easy access to original field settings.
    // This is a placeholder for where more robust validation would go.
    // For now, we rely on browser `required` attribute.

    if (!empty($errors)) {
        error_log('[XForm Debug] Validation errors: ' . esc_html(implode(', ', $errors)) . ' Widget ID: ' . esc_html($widget_id));
        $GLOBALS['xform_messages']['xform-' . $widget_id] = [
            'type' => 'error',
            'message' => $error_message_setting // Use the setting, or implode errors
        ];
    } else {
        error_log('[XForm Debug] No validation errors, proceeding to build email. Widget ID: ' . esc_html($widget_id));
        $email_body = esc_html__("You have received a new message from your website's contact form.", 'xform-widget') . "\n\n";
        foreach ($form_data_for_email as $label => $value) {
            $email_body .= esc_html($label) . ": " . esc_html($value) . "\n";
        }
        $email_body .= "\n--\n";
        $email_body .= sprintf(esc_html__('This email was sent from a contact form on %s (%s)', 'xform-widget'), get_bloginfo('name'), esc_url(home_url())) . "\n";

        $headers = [];
        $site_domain = preg_replace('/^www\./', '', sanitize_text_field(wp_parse_url(home_url(), PHP_URL_HOST))); // Sanitize host
        $from_email = 'wordpress@' . $site_domain;

        $headers[] = 'From: ' . get_bloginfo('name') . ' <' . $from_email . '>';
        if ($reply_to_email) {
            $headers[] = 'Reply-To: ' . $reply_to_email;
            error_log('[XForm Debug] Reply-To header set to: ' . esc_html($reply_to_email));
        } else {
            error_log('[XForm Debug] No reply-to email identified or set.');
        }
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';

        error_log('[XForm Debug] Attempting to send email. To: ' . esc_html($email_to) . ', Subject: ' . esc_html($email_subject));
        error_log('[XForm Debug] Email Body: ' . $email_body); // Be careful logging full body if sensitive data
        error_log('[XForm Debug] Email Headers: ' . esc_html(implode("\r\n", $headers)));

        add_action('wp_mail_failed', 'xform_log_wp_mail_failure_action', 10, 1);
        $mail_sent = wp_mail($email_to, $email_subject, $email_body, $headers);
        remove_action('wp_mail_failed', 'xform_log_wp_mail_failure_action', 10);

        if ($mail_sent) {
            error_log('[XForm Debug] wp_mail() returned true. Email supposedly sent. Widget ID: ' . esc_html($widget_id));
            $GLOBALS['xform_messages']['xform-' . $widget_id] = [
                'type' => 'success',
                'message' => $success_message_setting
            ];
        } else {
            error_log('[XForm Debug] wp_mail() returned false. Email sending FAILED. Widget ID: ' . esc_html($widget_id));
            $phpmailer_error = get_transient('xform_phpmailer_error_' . $widget_id);
            $error_message_to_display = $error_message_setting;
            if ($phpmailer_error) {
                error_log('[XForm Debug] PHPMailer Error for ' . esc_html($widget_id) . ': ' . esc_html($phpmailer_error));
                // Optionally append PHPMailer error to user message if WP_DEBUG is on, for admin users
                if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
                    $error_message_to_display .= ' (Debug: ' . esc_html($phpmailer_error) . ')';
                }
                delete_transient('xform_phpmailer_error_' . $widget_id);
            }
            $GLOBALS['xform_messages']['xform-' . $widget_id] = [
                'type' => 'error',
                'message' => $error_message_to_display
            ];
        }
    }
}
// Ensure the hook for submission handling has a unique name if this file could be included multiple times,
// or ensure it's only included once. For now, assuming it's included once.
// Running it a bit earlier (priority 9) in case other plugins on template_redirect interfere.
// add_action('template_redirect', 'xform_handle_submission_action', 9); // Commented out for AJAX

/**
 * AJAX handler for XForm submission.
 *
 * @since 1.1.0 (AJAX update)
 */
function xform_ajax_submit_handler() {
    error_log('[XForm AJAX Debug] Entered xform_ajax_submit_handler.');

    $widget_id = isset($_POST['xform_widget_id']) ? sanitize_key($_POST['xform_widget_id']) : null;
    if (!$widget_id) {
        error_log('[XForm AJAX Debug] Error: xform_widget_id not found in POST.');
        wp_send_json_error(['message' => esc_html__('Form configuration error. Widget ID missing.', 'xform-widget')]);
        return;
    }
    error_log('[XForm AJAX Debug] Widget ID: ' . esc_html($widget_id));

    // Verify nonce. Note: check_ajax_referer dies on failure.
    // The second parameter 'xform_nonce_field' is the name of the nonce field in $_POST.
    if (false === check_ajax_referer('xform_submit_action_nonce_' . $widget_id, 'xform_nonce_field', false)) {
        error_log('[XForm AJAX Debug] Nonce verification failed for widget ID: ' . esc_html($widget_id));
        wp_send_json_error(['message' => esc_html__('Security check failed. Please refresh and try again.', 'xform-widget')]);
        return;
    }
    error_log('[XForm AJAX Debug] Nonce verified successfully for widget ID: ' . esc_html($widget_id));

    // Get email settings from hidden fields
    $email_to = isset($_POST['xform_email_to']) ? sanitize_email($_POST['xform_email_to']) : '';
    $email_subject_template = isset($_POST['xform_email_subject']) ? sanitize_text_field($_POST['xform_email_subject']) : 'New Form Submission from {site_title}';
    $success_message_setting = isset($_POST['xform_success_message_setting']) ? sanitize_text_field($_POST['xform_success_message_setting']) : esc_html__('Your message has been sent successfully.', 'xform-widget');
    $error_message_setting = isset($_POST['xform_error_message_setting']) ? sanitize_text_field($_POST['xform_error_message_setting']) : esc_html__('The email could not be sent. Please check site email configuration.', 'xform-widget');

    if (empty($email_to)) {
        error_log('[XForm AJAX Debug] Error: Email To address is empty. Widget ID: ' . esc_html($widget_id));
        wp_send_json_error(['message' => esc_html__('Admin email (Email To) is not configured for this form.', 'xform-widget')]);
        return;
    }
    error_log('[XForm AJAX Debug] Email To: ' . esc_html($email_to) . ', Subject Template: ' . esc_html($email_subject_template));

    $email_subject = str_replace('{site_title}', get_bloginfo('name'), $email_subject_template);

    $errors = []; // For collecting specific field validation errors if implemented later
    $form_data_for_email = [];
    $submitted_fields = isset($_POST['xform_fields']) && is_array($_POST['xform_fields']) ? $_POST['xform_fields'] : [];

    if (empty($submitted_fields)) {
         error_log('[XForm AJAX Debug] No xform_fields data submitted. Widget ID: ' . esc_html($widget_id));
         // Consider if this is an error or just an empty submission.
         // For now, we'll let it proceed, but server-side required field validation would catch this.
    }

    $reply_to_email = null;

    foreach ($submitted_fields as $field_name_key => $value) {
        $label = ucwords(str_replace(['-', '_'], ' ', sanitize_key($field_name_key)));

        $sanitized_value = '';
        if (is_array($value)) {
            $sanitized_value = array_map('sanitize_text_field', $value);
            $form_data_for_email[$label] = implode(', ', $sanitized_value);
        } else {
            $raw_value = stripslashes((string)$value);
            if (!$reply_to_email && (stripos($label, 'email') !== false || stripos($label, 'e-mail') !== false) && is_email($raw_value)) {
                $reply_to_email = sanitize_email($raw_value);
            }
            $sanitized_value = sanitize_text_field($raw_value);
            $form_data_for_email[$label] = $sanitized_value;
        }
        // Server-side required validation would go here by comparing against widget settings.
        // For now, relying on client-side `required`.
        // Example:
        // $field_setting = get_field_setting_from_widget_options($widget_id, $field_name_key);
        // if ($field_setting['is_required'] && empty($sanitized_value)) {
        //    $errors[$field_name_key] = $label . ' is required.';
        // }
        error_log("[XForm AJAX Debug] Processed field - Label: " . esc_html($label) . ", Sanitized Value: " . esc_html(is_array($sanitized_value) ? implode(', ', $sanitized_value) : $sanitized_value));
    }


    if (!empty($errors)) {
        error_log('[XForm AJAX Debug] Validation errors: ' . esc_html(implode(', ', $errors)) . ' Widget ID: ' . esc_html($widget_id));
        wp_send_json_error(['message' => $error_message_setting, 'errors' => $errors]);
        return;
    }

    error_log('[XForm AJAX Debug] No validation errors, proceeding to build email. Widget ID: ' . esc_html($widget_id));
    $email_body = esc_html__("You have received a new message from your website's contact form.", 'xform-widget') . "\n\n";
    foreach ($form_data_for_email as $label => $value) {
        $email_body .= esc_html($label) . ": " . esc_html($value) . "\n";
    }
    $email_body .= "\n--\n";
    $email_body .= sprintf(esc_html__('This email was sent from a contact form on %s (%s)', 'xform-widget'), get_bloginfo('name'), esc_url(home_url())) . "\n";

    $headers = [];
    $site_domain = preg_replace('/^www\./', '', sanitize_text_field(wp_parse_url(home_url(), PHP_URL_HOST)));
    $from_email = 'wordpress@' . $site_domain;

    $headers[] = 'From: ' . get_bloginfo('name') . ' <' . $from_email . '>';
    if ($reply_to_email) {
        $headers[] = 'Reply-To: ' . $reply_to_email;
        error_log('[XForm AJAX Debug] Reply-To header set to: ' . esc_html($reply_to_email));
    } else {
        error_log('[XForm AJAX Debug] No reply-to email identified or set.');
    }
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    error_log('[XForm AJAX Debug] Attempting to send email. To: ' . esc_html($email_to) . ', Subject: ' . esc_html($email_subject));
    // error_log('[XForm AJAX Debug] Email Body: ' . $email_body); // Potentially sensitive
    error_log('[XForm AJAX Debug] Email Headers: ' . esc_html(implode("\r\n", $headers)));

    add_action('wp_mail_failed', 'xform_log_wp_mail_failure_action', 10, 1);
    $mail_sent = wp_mail($email_to, $email_subject, $email_body, $headers);
    remove_action('wp_mail_failed', 'xform_log_wp_mail_failure_action', 10);

    if ($mail_sent) {
        error_log('[XForm AJAX Debug] wp_mail() returned true. Email supposedly sent. Widget ID: ' . esc_html($widget_id));
        wp_send_json_success(['message' => $success_message_setting]);
    } else {
        error_log('[XForm AJAX Debug] wp_mail() returned false. Email sending FAILED. Widget ID: ' . esc_html($widget_id));
        $phpmailer_error = get_transient('xform_phpmailer_error_' . $widget_id);
        $error_message_to_display = $error_message_setting;
        if ($phpmailer_error) {
            error_log('[XForm AJAX Debug] PHPMailer Error for ' . esc_html($widget_id) . ': ' . esc_html($phpmailer_error));
            if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
                $error_message_to_display .= ' (Debug: ' . esc_html($phpmailer_error) . ')';
            }
            delete_transient('xform_phpmailer_error_' . $widget_id);
        }
        wp_send_json_error(['message' => $error_message_to_display]);
    }
    // Note: wp_send_json_success and wp_send_json_error automatically die()
}
add_action('wp_ajax_xform_submit_action', 'xform_ajax_submit_handler');
add_action('wp_ajax_nopriv_xform_submit_action', 'xform_ajax_submit_handler');


/**
 * Logs PHPMailer errors via wp_mail_failed hook.
 * We use a transient to pass the error message back to the main submission handler,
 * as the $GLOBALS['phpmailer'] might not be reliably accessible right after wp_mail if it fails.
 * @param WP_Error $wp_error
 */
function xform_log_wp_mail_failure_action( $wp_error ){
    if (is_wp_error($wp_error)) {
        $error_message = $wp_error->get_error_message();
        error_log('[XForm Debug] wp_mail_failed hook. PHPMailer Error: ' . esc_html($error_message));
        // Try to get widget_id from POST if available to make transient unique
        $widget_id = isset($_POST['xform_widget_id']) ? sanitize_key($_POST['xform_widget_id']) : 'general';
        set_transient('xform_phpmailer_error_' . $widget_id, $error_message, 60);
    }
}

?>
