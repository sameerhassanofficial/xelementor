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
            'field_name',
            [
                'label' => __('Field Name', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'field_name',
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
            'field_required',
            [
                'label' => __('Required', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $repeater->add_control(
            'field_options',
            [
                'label' => __('Options (for select/radio/checkbox)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => __('Option 1|value1\nOption 2|value2', 'textdomain'),
                'condition' => [
                    'field_type' => ['select', 'radio', 'checkbox'],
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
            'success_message',
            [
                'label' => __('Success Message', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Thank you! Your message has been sent.', 'textdomain'),
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

                <div class="advanced-form-fields">
                    <?php foreach ($settings['form_fields'] as $field): ?>
                        <div class="advanced-form-field">
                            <label for="<?php echo esc_attr($field['field_name']); ?>">
                                <?php echo esc_html($field['field_label']); ?>
                                <?php if ($field['field_required'] === 'yes'): ?>
                                    <span class="required">*</span>
                                <?php endif; ?>
                            </label>
                            
                            <?php $this->render_field($field); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="advanced-form-actions">
                    <button type="submit" class="advanced-form-submit">
                        <span class="submit-text"><?php echo esc_html($settings['submit_button_text']); ?></span>
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

    private function render_field($field) {
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $placeholder = !empty($field['field_placeholder']) ? 'placeholder="' . esc_attr($field['field_placeholder']) . '"' : '';
        
        switch ($field['field_type']) {
            case 'textarea':
                echo '<textarea name="' . esc_attr($field['field_name']) . '" id="' . esc_attr($field['field_name']) . '" ' . $placeholder . ' ' . $required . '></textarea>';
                break;
                
            case 'select':
                echo '<select name="' . esc_attr($field['field_name']) . '" id="' . esc_attr($field['field_name']) . '" ' . $required . '>';
                if (!empty($field['field_options'])) {
                    $options = explode("\n", $field['field_options']);
                    foreach ($options as $option) {
                        $parts = explode('|', $option);
                        $label = trim($parts[0]);
                        $value = isset($parts[1]) ? trim($parts[1]) : $label;
                        echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
                    }
                }
                echo '</select>';
                break;
                
            case 'checkbox':
            case 'radio':
                if (!empty($field['field_options'])) {
                    $options = explode("\n", $field['field_options']);
                    foreach ($options as $option) {
                        $parts = explode('|', $option);
                        $label = trim($parts[0]);
                        $value = isset($parts[1]) ? trim($parts[1]) : $label;
                        echo '<label class="checkbox-radio-label">';
                        echo '<input type="' . esc_attr($field['field_type']) . '" name="' . esc_attr($field['field_name']) . '" value="' . esc_attr($value) . '" ' . $required . '>';
                        echo '<span>' . esc_html($label) . '</span>';
                        echo '</label>';
                    }
                }
                break;
                
            default:
                echo '<input type="' . esc_attr($field['field_type']) . '" name="' . esc_attr($field['field_name']) . '" id="' . esc_attr($field['field_name']) . '" ' . $placeholder . ' ' . $required . '>';
                break;
        }
    }
}
