<?php
class Advanced_Form_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'advanced_form_widget';
    }

    public function get_title() {
        return __('XForm', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Form Settings', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_title',
            [
                'label' => __('Form Title', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Contact Form', 'textdomain'),
            ]
        );

        $this->add_control(
            'form_id',
            [
                'label' => __('Form ID', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'advanced_form_' . rand(1000, 9999),
            ]
        );

        $this->add_control(
            'grid_columns',
            [
                'label' => __('Columns', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => __('1 Column', 'textdomain'),
                    '2' => __('2 Columns', 'textdomain'),
                    '3' => __('3 Columns', 'textdomain'),
                    '4' => __('4 Columns', 'textdomain'),
                ],
            ]
        );

        $this->add_control(
            'column_gap',
            [
                'label' => __('Column Gap', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Form Fields Repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label' => __('Field Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __('Text', 'textdomain'),
                    'email' => __('Email', 'textdomain'),
                    'tel' => __('Phone', 'textdomain'),
                    'textarea' => __('Textarea', 'textdomain'),
                    'select' => __('Select', 'textdomain'),
                    'checkbox' => __('Checkbox', 'textdomain'),
                    'radio' => __('Radio', 'textdomain'),
                    'file' => __('File Upload', 'textdomain'),
                    'date' => __('Date', 'textdomain'),
                    'number' => __('Number', 'textdomain'),
                    'url' => __('URL', 'textdomain'),
                    'time' => __('Time', 'textdomain'),
                    'datetime-local' => __('Date & Time', 'textdomain'),
                    'color' => __('Color Picker', 'textdomain'),
                    'range' => __('Range Slider', 'textdomain'),
                    'hidden' => __('Hidden Field', 'textdomain'),
                    'html' => __('HTML Content', 'textdomain'),
                    'acceptance' => __('Acceptance', 'textdomain'),
                ],
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label' => __('Field Label', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Field Label', 'textdomain'),
            ]
        );



        $repeater->add_control(
            'field_placeholder',
            [
                'label' => __('Placeholder', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'field_width',
            [
                'label' => __('Field Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '100',
                'options' => [
                    '25' => __('25%', 'textdomain'),
                    '50' => __('50%', 'textdomain'),
                    '75' => __('75%', 'textdomain'),
                    '100' => __('100%', 'textdomain'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field[data-width="{{VALUE}}"]' => 'width: {{VALUE}}%;',
                ],
            ]
        );

        $repeater->add_control(
            'field_break_before',
            [
                'label' => __('Break Before Field', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Start this field on a new row', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'field_required',
            [
                'label' => __('Required', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $repeater->add_control(
            'field_validation',
            [
                'label' => __('Validation', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'textdomain'),
                    'email' => __('Email', 'textdomain'),
                    'url' => __('URL', 'textdomain'),
                    'phone' => __('Phone', 'textdomain'),
                    'number' => __('Number', 'textdomain'),
                    'min_length' => __('Minimum Length', 'textdomain'),
                    'max_length' => __('Maximum Length', 'textdomain'),
                    'regex' => __('Custom Regex', 'textdomain'),
                ],
            ]
        );

        $repeater->add_control(
            'field_validation_value',
            [
                'label' => __('Validation Value', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter validation value (e.g., min length)', 'textdomain'),
                'condition' => [
                    'field_validation' => ['min_length', 'max_length', 'regex'],
                ],
            ]
        );

        $repeater->add_control(
            'field_conditional_logic',
            [
                'label' => __('Conditional Logic', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Show/hide this field based on other field values', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'field_conditional_field',
            [
                'label' => __('Dependent Field', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter field name to depend on', 'textdomain'),
                'condition' => [
                    'field_conditional_logic' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'field_conditional_value',
            [
                'label' => __('Show When Value Is', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter value to show field', 'textdomain'),
                'condition' => [
                    'field_conditional_logic' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'field_options',
            [
                'label' => __('Options', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => __('Option 1\nOption 2\nOption 3', 'textdomain'),
                'description' => __('Enter each option on a new line. Values will be auto-generated from labels.', 'textdomain'),
                'condition' => [
                    'field_type' => ['select', 'radio', 'checkbox'],
                ],
            ]
        );

        $repeater->add_control(
            'field_default_value',
            [
                'label' => __('Default Value', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter default value', 'textdomain'),
                'condition' => [
                    'field_type' => ['text', 'email', 'tel', 'textarea', 'number', 'url'],
                ],
            ]
        );

        $repeater->add_control(
            'field_file_types',
            [
                'label' => __('Allowed File Types', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('jpg,jpeg,png,pdf,doc,docx', 'textdomain'),
                'default' => 'jpg,jpeg,png,pdf,doc,docx',
                'condition' => [
                    'field_type' => 'file',
                ],
                'description' => __('Comma-separated file extensions', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'field_file_size',
            [
                'label' => __('Max File Size (MB)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 50,
                'condition' => [
                    'field_type' => 'file',
                ],
            ]
        );

        $repeater->add_control(
            'field_multiple_files',
            [
                'label' => __('Allow Multiple Files', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'field_type' => 'file',
                ],
            ]
        );

        $repeater->add_control(
            'field_html_content',
            [
                'label' => __('HTML Content', 'textdomain'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('<p>Custom HTML content here</p>', 'textdomain'),
                'condition' => [
                    'field_type' => 'html',
                ],
            ]
        );

        $repeater->add_control(
            'field_acceptance_text',
            [
                'label' => __('Acceptance Text', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('I agree to the terms and conditions', 'textdomain'),
                'condition' => [
                    'field_type' => 'acceptance',
                ],
            ]
        );

        $repeater->add_control(
            'field_range_min',
            [
                'label' => __('Minimum Value', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'field_type' => 'range',
                ],
            ]
        );

        $repeater->add_control(
            'field_range_max',
            [
                'label' => __('Maximum Value', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 100,
                'condition' => [
                    'field_type' => 'range',
                ],
            ]
        );

        $repeater->add_control(
            'field_range_step',
            [
                'label' => __('Step Value', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'field_type' => 'range',
                ],
            ]
        );

        $this->add_control(
            'form_fields',
            [
                'label' => __('Form Fields', 'textdomain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'field_type' => 'text',
                        'field_label' => 'Name',
                        'field_name' => 'name',
                        'field_required' => 'yes',
                    ],
                    [
                        'field_type' => 'email',
                        'field_label' => 'Email',
                        'field_name' => 'email',
                        'field_required' => 'yes',
                    ],
                ],
                'title_field' => '{{{ field_label }}}',
            ]
        );

        // Form Actions
        $this->add_control(
            'submit_button_text',
            [
                'label' => __('Submit Button Text', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Submit', 'textdomain'),
            ]
        );

        $this->add_control(
            'enable_ajax',
            [
                'label' => __('Enable AJAX Submission', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );





        $this->add_control(
            'enable_file_upload',
            [
                'label' => __('Enable File Upload', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Allow file uploads in form', 'textdomain'),
            ]
        );

        $this->add_control(
            'max_file_size',
            [
                'label' => __('Max File Size (MB)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'condition' => [
                    'enable_file_upload' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => __('Success Message', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Thank you! Your message has been sent.', 'textdomain'),
            ]
        );

        $this->end_controls_section();

        // Form Actions Section
        $this->start_controls_section(
            'section_actions',
            [
                'label' => __('Form Actions', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_redirect',
            [
                'label' => __('Enable Redirect', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'redirect_url',
            [
                'label' => __('Redirect URL', 'textdomain'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/thank-you',
                'condition' => [
                    'enable_redirect' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'redirect_delay',
            [
                'label' => __('Redirect Delay (seconds)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 0,
                'max' => 10,
                'condition' => [
                    'enable_redirect' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_webhook',
            [
                'label' => __('Enable Webhook', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'webhook_url',
            [
                'label' => __('Webhook URL', 'textdomain'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://api.example.com/webhook',
                'condition' => [
                    'enable_webhook' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'webhook_method',
            [
                'label' => __('Webhook Method', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'POST',
                'options' => [
                    'POST' => 'POST',
                    'GET' => 'GET',
                    'PUT' => 'PUT',
                ],
                'condition' => [
                    'enable_webhook' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_slack',
            [
                'label' => __('Slack Notification', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'slack_webhook_url',
            [
                'label' => __('Slack Webhook URL', 'textdomain'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://hooks.slack.com/services/...',
                'condition' => [
                    'enable_slack' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Email Settings Section
        $this->start_controls_section(
            'section_email',
            [
                'label' => __('Email', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'send_email',
            [
                'label' => __('Send Email Notification', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'textdomain'),
                'label_off' => __('No', 'textdomain'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'email_to',
            [
                'label' => __('To', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => get_option('admin_email'),
                'placeholder' => get_option('admin_email'),
                'condition' => [
                    'send_email' => 'yes',
                ],
                'description' => __('Recipient email address. Separate multiple emails with a comma.', 'textdomain'),
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label' => __('Subject', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => sprintf(__('New Submission from %s', 'textdomain'), get_bloginfo('name')),
                'condition' => [
                    'send_email' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_from_name',
            [
                'label' => __('From Name', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => get_bloginfo('name'),
                'condition' => [
                    'send_email' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_custom_message',
            [
                'label' => __('Custom Message', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __("", 'textdomain'),
                'condition' => [
                    'send_email' => 'yes',
                ],
                'description' => __('Optional custom message to include before form data.', 'textdomain'),
            ]
        );

        $this->add_control(
            'email_format',
            [
                'label' => __('Email Format', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'html',
                'options' => [
                    'html' => __('HTML (Beautiful Design)', 'textdomain'),
                    'plain' => __('Plain Text', 'textdomain'),
                ],
                'condition' => [
                    'send_email' => 'yes',
                ],
                'description' => __('Choose HTML for modern design or Plain Text for simple format.', 'textdomain'),
            ]
        );

        $this->add_control(
            'email_message',
            [
                'label' => __('Form Data Placement', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __("[all-fields]", 'textdomain'),
                'condition' => [
                    'send_email' => 'yes',
                ],
                'description' => __('Use [all-fields] to include form data. For HTML format, the template will be automatically applied.', 'textdomain'),
            ]
        );

        $this->add_control(
            'email_format',
            [
                'label' => __('Email Format', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'plain',
                'options' => [
                    'plain' => __('Plain Text', 'textdomain'),
                    'html' => __('HTML', 'textdomain'),
                ],
                'condition' => [
                    'send_email' => 'yes',
                ],
                'description' => __('Choose email format. HTML provides better formatting.', 'textdomain'),
            ]
        );

        $this->end_controls_section();

        // Advanced Settings Section
        $this->start_controls_section(
            'section_advanced',
            [
                'label' => __('Advanced Settings', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_recaptcha',
            [
                'label' => __('Enable reCAPTCHA', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'recaptcha_site_key',
            [
                'label' => __('reCAPTCHA Site Key', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'enable_recaptcha' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'recaptcha_secret_key',
            [
                'label' => __('reCAPTCHA Secret Key', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'enable_recaptcha' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_honeypot',
            [
                'label' => __('Enable Honeypot', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Anti-spam protection', 'textdomain'),
            ]
        );

        $this->add_control(
            'enable_rate_limiting',
            [
                'label' => __('Enable Rate Limiting', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'rate_limit_attempts',
            [
                'label' => __('Max Attempts per Hour', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'condition' => [
                    'enable_rate_limiting' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'enable_auto_save',
            [
                'label' => __('Auto Save Progress', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Save form data as user types', 'textdomain'),
            ]
        );

        $this->add_control(
            'auto_save_interval',
            [
                'label' => __('Auto Save Interval (seconds)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 10,
                'max' => 300,
                'condition' => [
                    'enable_auto_save' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Form Style', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'form_background',
            [
                'label' => __('Form Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label' => __('Form Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'label' => __('Field Typography', 'textdomain'),
                'selector' => '{{WRAPPER}} .advanced-form-field input, {{WRAPPER}} .advanced-form-field textarea, {{WRAPPER}} .advanced-form-field select',
            ]
        );

        $this->add_control(
            'field_border_radius',
            [
                'label' => __('Field Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field input, {{WRAPPER}} .advanced-form-field textarea, {{WRAPPER}} .advanced-form-field select' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __('Button Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-submit' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Button Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-submit' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => __('Button Hover Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-submit:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => __('Button Hover Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-submit:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Field Styling Section
        $this->start_controls_section(
            'field_style_section',
            [
                'label' => __('Field Styling', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_background',
            [
                'label' => __('Field Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field input, {{WRAPPER}} .advanced-form-field textarea, {{WRAPPER}} .advanced-form-field select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_border_color',
            [
                'label' => __('Field Border Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field input, {{WRAPPER}} .advanced-form-field textarea, {{WRAPPER}} .advanced-form-field select' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_focus_border_color',
            [
                'label' => __('Field Focus Border Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field input:focus, {{WRAPPER}} .advanced-form-field textarea:focus, {{WRAPPER}} .advanced-form-field select:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => __('Field Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field input, {{WRAPPER}} .advanced-form-field textarea, {{WRAPPER}} .advanced-form-field select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'required_color',
            [
                'label' => __('Required Asterisk Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e74c3c',
                'selectors' => [
                    '{{WRAPPER}} .advanced-form-field .required' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Messages Styling Section
        $this->start_controls_section(
            'messages_style_section',
            [
                'label' => __('Messages Styling', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'success_message_color',
            [
                'label' => __('Success Message Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#27ae60',
                'selectors' => [
                    '{{WRAPPER}} .success-message' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'error_message_color',
            [
                'label' => __('Error Message Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e74c3c',
                'selectors' => [
                    '{{WRAPPER}} .error-message' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $form_id = $settings['form_id'];
        ?>
        <div class="advanced-form-container">
            <form class="advanced-form" id="<?php echo esc_attr($form_id); ?>" data-form-id="<?php echo esc_attr($form_id); ?>">
                <?php if (!empty($settings['form_title'])): ?>
                    <h3 class="advanced-form-title"><?php echo esc_html($settings['form_title']); ?></h3>
                <?php endif; ?>

                <div class="advanced-form-fields advanced-form-grid"
                     style="grid-template-columns: repeat(<?php echo esc_attr($settings['grid_columns']); ?>, 1fr);">
                    <?php foreach ($settings['form_fields'] as $field): 
                        $field_name = $this->generate_field_name($field['field_label']);
                    ?>
                        <div class="advanced-form-field" 
                             data-width="<?php echo esc_attr($field['field_width'] ?? '100'); ?>"
                             style="<?php 
                                $grid_style = 'grid-column: span ' . $this->get_grid_span($field['field_width'] ?? '100', $settings['grid_columns']) . ';';
                                if (isset($field['field_break_before']) && $field['field_break_before'] === 'yes') {
                                    $grid_style .= ' grid-column: 1 / -1;';
                                }
                                echo $grid_style;
                             ?>">
                            <label for="<?php echo esc_attr($field_name); ?>">
                                <?php echo esc_html($field['field_label']); ?>
                                <?php if ($field['field_required'] === 'yes'): ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>
                            
                            <?php $this->render_field($field, $field_name); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="advanced-form-actions">
                    <button type="submit" class="advanced-form-submit">
                        <span class="submit-text"><?php echo esc_html($settings['submit_button_text'] ?? 'Submit'); ?></span>
                        <span class="loading-spinner" style="display: none;">⟳</span>
                    </button>
                </div>

                <div class="advanced-form-messages">
                    <div class="success-message" style="display: none;">
                        <?php echo esc_html($settings['success_message']); ?>
                    </div>
                    <div class="error-message" style="display: none;"></div>
                </div>

                <?php wp_nonce_field('advanced_form_nonce', 'advanced_form_nonce'); ?>

                <?php // Add hidden fields for email settings if enabled
                if ('yes' === $settings['send_email']) : ?>
                    <input type="hidden" name="_send_email" value="yes">
                    <input type="hidden" name="_email_to" value="<?php echo esc_attr($settings['email_to']); ?>">
                    <input type="hidden" name="_email_subject" value="<?php echo esc_attr($settings['email_subject']); ?>">
                    <input type="hidden" name="_email_from_name" value="<?php echo esc_attr($settings['email_from_name']); ?>">
                    <input type="hidden" name="_email_custom_message" value="<?php echo esc_attr($settings['email_custom_message']); ?>">
                    <input type="hidden" name="_email_message" value="<?php echo esc_attr($settings['email_message']); ?>">
                    <input type="hidden" name="_email_format" value="<?php echo esc_attr($settings['email_format']); ?>">
                <?php endif; ?>
            </form>
        </div>
        <?php
    }

    private function get_grid_span($field_width, $grid_columns) {
        $width_percentage = intval($field_width);
        $columns = intval($grid_columns);
        
        // Calculate how many columns this field should span
        $span = ceil(($width_percentage / 100) * $columns);
        
        // Ensure span is at least 1 and doesn't exceed total columns
        return max(1, min($span, $columns));
    }

    private function generate_field_name($label) {
        // Convert label to lowercase and replace spaces/special chars with underscores
        $field_name = strtolower(trim($label));
        $field_name = preg_replace('/[^a-z0-9\s]/', '', $field_name);
        $field_name = preg_replace('/\s+/', '_', $field_name);
        $field_name = trim($field_name, '_');
        
        // Ensure it's not empty
        if (empty($field_name)) {
            $field_name = 'field_' . uniqid();
        }
        
        return $field_name;
    }

    private function render_field($field, $field_name) {
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $placeholder = !empty($field['field_placeholder']) ? 'placeholder="' . esc_attr($field['field_placeholder']) . '"' : '';
        $default_value = !empty($field['field_default_value']) ? 'value="' . esc_attr($field['field_default_value']) . '"' : '';
        
        switch ($field['field_type']) {
            case 'textarea':
                echo '<textarea name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $placeholder . ' ' . $required . '>' . esc_textarea($field['field_default_value'] ?? '') . '</textarea>';
                break;
                
            case 'select':
                echo '<select name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $required . '>';
                if (!empty($field['field_options'])) {
                    $options = explode("\n", $field['field_options']);
                    foreach ($options as $option) {
                        $label = trim($option);
                        if (!empty($label)) {
                            $value = $label; // Use the exact label as the value
                            $selected = ($value === ($field['field_default_value'] ?? '')) ? 'selected' : '';
                            echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                        }
                    }
                }
                echo '</select>';
                break;
                
            case 'checkbox':
            case 'radio':
                if (!empty($field['field_options'])) {
                    $options = explode("\n", $field['field_options']);
                    foreach ($options as $option) {
                        $label = trim($option);
                        if (!empty($label)) {
                            $value = $label; // Use the exact label as the value
                            $checked = ($value === ($field['field_default_value'] ?? '')) ? 'checked' : '';
                        echo '<label class="checkbox-radio-label">';
                            echo '<input type="' . esc_attr($field['field_type']) . '" name="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" ' . $required . ' ' . $checked . '>';
                        echo '<span>' . esc_html($label) . '</span>';
                        echo '</label>';
                        }
                    }
                }
                break;

            case 'file':
                $multiple = isset($field['field_multiple_files']) && $field['field_multiple_files'] === 'yes' ? 'multiple' : '';
                $accept = !empty($field['field_file_types']) ? 'accept="' . esc_attr($field['field_file_types']) . '"' : '';
                echo '<input type="file" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $multiple . ' ' . $accept . ' ' . $required . '>';
                if (!empty($field['field_file_types'])) {
                    echo '<small class="file-types-hint">Allowed types: ' . esc_html($field['field_file_types']) . '</small>';
                }
                break;

            case 'range':
                $min = isset($field['field_range_min']) ? 'min="' . esc_attr($field['field_range_min']) . '"' : 'min="0"';
                $max = isset($field['field_range_max']) ? 'max="' . esc_attr($field['field_range_max']) . '"' : 'max="100"';
                $step = isset($field['field_range_step']) ? 'step="' . esc_attr($field['field_range_step']) . '"' : 'step="1"';
                echo '<input type="range" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $min . ' ' . $max . ' ' . $step . ' ' . $default_value . ' ' . $required . '>';
                echo '<div class="range-value-display">Value: <span class="range-value">' . esc_html($field['field_default_value'] ?? '50') . '</span></div>';
                break;

            case 'color':
                echo '<input type="color" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $default_value . ' ' . $required . '>';
                break;

            case 'date':
                echo '<input type="date" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $default_value . ' ' . $required . '>';
                break;

            case 'time':
                echo '<input type="time" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $default_value . ' ' . $required . '>';
                break;

            case 'datetime-local':
                echo '<input type="datetime-local" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $default_value . ' ' . $required . '>';
                break;

            case 'number':
                $min = isset($field['field_validation_value']) && $field['field_validation'] === 'min_length' ? 'min="' . esc_attr($field['field_validation_value']) . '"' : '';
                $max = isset($field['field_validation_value']) && $field['field_validation'] === 'max_length' ? 'max="' . esc_attr($field['field_validation_value']) . '"' : '';
                echo '<input type="number" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $placeholder . ' ' . $default_value . ' ' . $min . ' ' . $max . ' ' . $required . '>';
                break;

            case 'url':
                echo '<input type="url" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $placeholder . ' ' . $default_value . ' ' . $required . '>';
                break;



            case 'hidden':
                echo '<input type="hidden" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $default_value . '>';
                break;

            case 'html':
                if (!empty($field['field_html_content'])) {
                    echo '<div class="html-content-field">' . wp_kses_post($field['field_html_content']) . '</div>';
                }
                break;

            case 'acceptance':
                $acceptance_text = !empty($field['field_acceptance_text']) ? $field['field_acceptance_text'] : 'I agree to the terms and conditions';
                echo '<label class="acceptance-label">';
                echo '<input type="checkbox" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $required . '>';
                echo '<span>' . esc_html($acceptance_text) . '</span>';
                echo '</label>';
                break;


                
            default:
                // Handle text, email, tel and other basic input types
                echo '<input type="' . esc_attr($field['field_type']) . '" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_name) . '" ' . $placeholder . ' ' . $default_value . ' ' . $required . '>';
                break;
        }
    }
}
