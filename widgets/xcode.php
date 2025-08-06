<?php
/**
 * XCode Widget for Elementor
 * 
 * A widget that allows adding custom code (HTML, CSS, JavaScript) or shortcodes
 * with a tabbed interface for better organization.
 */

class XCode_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'xcode';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('XCode', 'textdomain');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-code';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['code', 'html', 'css', 'javascript', 'shortcode', 'custom code'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'code_type',
            [
                'label' => esc_html__('Code Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'custom_code',
                'options' => [
                    'custom_code' => esc_html__('Custom Code', 'textdomain'),
                    'shortcode' => esc_html__('Shortcode', 'textdomain'),
                    'snippet' => esc_html__('Code Snippet', 'textdomain'),
                ],
            ]
        );

        // Custom Code Fields
        $this->add_control(
            'html_code',
            [
                'label' => esc_html__('HTML', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 20,
                'default' => '',
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        $this->add_control(
            'css_code',
            [
                'label' => esc_html__('CSS', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 20,
                'default' => '',
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        $this->add_control(
            'js_code',
            [
                'label' => esc_html__('JavaScript', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'javascript',
                'rows' => 20,
                'default' => '',
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        // Shortcode Field
        $this->add_control(
            'shortcode',
            [
                'label' => esc_html__('Shortcode', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('[shortcode]', 'textdomain'),
                'default' => '',
                'condition' => [
                    'code_type' => 'shortcode',
                ],
            ]
        );

        $this->add_control(
            'default_shortcodes',
            [
                'label' => esc_html__('Default Shortcodes', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'date' => esc_html__('Date', 'textdomain'),
                    'time' => esc_html__('Time', 'textdomain'),
                    'site_title' => esc_html__('Site Title', 'textdomain'),
                    'site_description' => esc_html__('Site Description', 'textdomain'),
                    'site_url' => esc_html__('Site URL', 'textdomain'),
                ],
                'default' => [],
                'condition' => [
                    'code_type' => 'shortcode',
                ],
            ]
        );
        
        // Code Snippet Fields
        $this->add_control(
            'snippet_title',
            [
                'label' => esc_html__('Snippet Title', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Code Snippet', 'textdomain'),
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );
        
        $this->add_control(
            'snippet_code',
            [
                'label' => esc_html__('Code', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'php',
                'rows' => 20,
                'default' => '',
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );
        
        $this->add_control(
            'snippet_language',
            [
                'label' => esc_html__('Language', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'php',
                'options' => [
                    'php' => 'PHP',
                    'javascript' => 'JavaScript',
                    'css' => 'CSS',
                    'html' => 'HTML',
                    'json' => 'JSON',
                    'xml' => 'XML',
                    'sql' => 'SQL',
                    'bash' => 'Bash',
                    'python' => 'Python',
                    'java' => 'Java',
                    'csharp' => 'C#',
                    'cpp' => 'C++',
                    'ruby' => 'Ruby',
                    'go' => 'Go',
                    'rust' => 'Rust',
                    'swift' => 'Swift',
                    'kotlin' => 'Kotlin',
                    'typescript' => 'TypeScript',
                    'scss' => 'SCSS',
                    'sass' => 'Sass',
                    'less' => 'Less',
                    'yaml' => 'YAML',
                    'markdown' => 'Markdown',
                ],
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );

        $this->add_control(
            'show_line_numbers',
            [
                'label' => esc_html__('Show Line Numbers', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );

        $this->add_control(
            'copy_button',
            [
                'label' => esc_html__('Show Copy Button', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );

        $this->add_control(
            'syntax_highlighting',
            [
                'label' => esc_html__('Syntax Highlighting', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section for Code Snippet
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'code_type' => 'snippet',
                ],
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .xcode-snippet-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .xcode-snippet-title',
            ]
        );
        
        $this->add_control(
            'snippet_bg_color',
            [
                'label' => esc_html__('Background Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .xcode-snippet-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'snippet_text_color',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .xcode-snippet-code' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'snippet_typography',
                'selector' => '{{WRAPPER}} .xcode-snippet-code',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'snippet_border',
                'selector' => '{{WRAPPER}} .xcode-snippet-container',
            ]
        );
        
        $this->add_control(
            'snippet_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xcode-snippet-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'snippet_box_shadow',
                'selector' => '{{WRAPPER}} .xcode-snippet-container',
            ]
        );
        
        $this->add_responsive_control(
            'snippet_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xcode-snippet-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Advanced Section
        $this->start_controls_section(
            'advanced_section',
            [
                'label' => esc_html__('Advanced', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        $this->add_control(
            'auto_run_js',
            [
                'label' => esc_html__('Auto Run JavaScript', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'textdomain'),
                'label_off' => esc_html__('No', 'textdomain'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        $this->add_control(
            'load_position',
            [
                'label' => esc_html__('JavaScript Load Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'footer',
                'options' => [
                    'footer' => esc_html__('Footer', 'textdomain'),
                    'header' => esc_html__('Header', 'textdomain'),
                    'inline' => esc_html__('Inline', 'textdomain'),
                ],
                'condition' => [
                    'code_type' => 'custom_code',
                    'auto_run_js' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'scoped_css',
            [
                'label' => esc_html__('Scoped CSS', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'textdomain'),
                'label_off' => esc_html__('No', 'textdomain'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__('Restrict CSS styles to this widget only', 'textdomain'),
                'condition' => [
                    'code_type' => 'custom_code',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        
        // Handle different code types
        switch ($settings['code_type']) {
            case 'custom_code':
                $this->render_custom_code($settings, $widget_id);
                break;
            
            case 'shortcode':
                $this->render_shortcode($settings);
                break;
                
            case 'snippet':
                $this->render_code_snippet($settings);
                break;
        }
    }
    
    /**
     * Render custom code output
     */
    protected function render_custom_code($settings, $widget_id) {
        $html_code = $settings['html_code'];
        $css_code = $settings['css_code'];
        $js_code = $settings['js_code'];
        
        echo '<div class="xcode-custom-code-container xcode-widget-' . esc_attr($widget_id) . '" id="xcode-' . esc_attr($widget_id) . '">';
        
        // Output HTML
        echo $html_code;
        
        // Output CSS
        if (!empty($css_code)) {
            echo '<style type="text/css">';
            // Add ID-based scoping for CSS
            if ($settings['scoped_css'] === 'yes') {
                $scoped_css = preg_replace('/([^{}]+\s*{)/', '#xcode-' . $widget_id . ' $1', $css_code);
            } else {
                $scoped_css = preg_replace('/([^{}]+\s*{)/', '#xcode-' . $widget_id . ' $1', $css_code);
            }
            echo $scoped_css;
            echo '</style>';
        }
        
        // Output JavaScript
        if (!empty($js_code)) {
            if ($settings['auto_run_js'] === 'yes') {
                switch ($settings['load_position']) {
                    case 'inline':
                        echo '<script type="text/javascript">' . $js_code . '</script>';
                        break;
                        
                    case 'header':
                        add_action('wp_head', function() use ($js_code) {
                            echo '<script type="text/javascript">' . $js_code . '</script>';
                        });
                        break;
                        
                    default: // Footer
                        add_action('wp_footer', function() use ($js_code) {
                            echo '<script type="text/javascript">' . $js_code . '</script>';
                        });
                        break;
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'document.addEventListener("DOMContentLoaded", function() {';
                echo $js_code;
                echo '});';
                echo '</script>';
            }
        }
        
        echo '</div>';
    }
    
    /**
     * Render shortcode output
     */
    protected function render_shortcode($settings) {
        if (!empty($settings['shortcode'])) {
            echo '<div class="xcode-shortcode-container">';
            echo shortcode_unautop(do_shortcode($settings['shortcode']));
        } else {
            if (!empty($settings['default_shortcodes'])) {
                echo '<div class="xcode-shortcode-container">';
                foreach ($settings['default_shortcodes'] as $shortcode) {
                    echo shortcode_unautop(do_shortcode('[' . $shortcode . ']'));
                }
                echo '</div>';
            } else {
                echo '<p>No shortcode provided.</p>';
            }
        }
    }

    /**
     * Render code snippet output
     */
    protected function render_code_snippet($settings) {
        if (!empty($settings['snippet_code'])) {
            $language_class = 'language-' . esc_attr($settings['snippet_language']);
            
            echo '<div class="xcode-snippet-container">';
            
            if (!empty($settings['snippet_title'])) {
                echo '<h4 class="xcode-snippet-title">' . esc_html($settings['snippet_title']) . '</h4>';
            }
            
            echo '<pre class="' . $language_class . '"><code class="xcode-snippet-code">';
            echo esc_html($settings['snippet_code']);
            echo '</code></pre>';
            
            // Add copy button functionality
            ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var copyButtons = document.querySelectorAll('.xcode-copy-btn');
                copyButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var codeElement = this.parentNode.querySelector('code');
                        var tempTextArea = document.createElement('textarea');
                        tempTextArea.value = codeElement.textContent;
                        document.body.appendChild(tempTextArea);
                        tempTextArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempTextArea);
                        
                        // Change button text temporarily
                        var originalText = this.textContent;
                        this.textContent = 'Copied!';
                        var that = this;
                        setTimeout(function() {
                            that.textContent = originalText;
                        }, 2000);
                    });
                });
            });
            </script>
            <?php
            
            echo '</div>';
        }
    }
    
    /**
     * Render plain content for search engines.
     */
    protected function content_template() {
        ?>
        <# if (settings.code_type === 'custom_code') { #>
            <div class="xcode-custom-code-container">
                {{{ settings.html_code }}}
            </div>
        <# } else if (settings.code_type === 'shortcode') { #>
            <div class="xcode-shortcode-container">
                {{{ settings.shortcode }}}
            </div>
        <# } else if (settings.code_type === 'snippet') { #>
            <div class="xcode-snippet-container">
                <# if (settings.snippet_title) { #>
                    <h4 class="xcode-snippet-title">{{{ settings.snippet_title }}}</h4>
                <# } #>
                <pre class="language-{{{ settings.snippet_language }}}">
                    <code class="xcode-snippet-code">{{{ settings.snippet_code }}}</code>
                </pre>
            </div>
        <# } #>
        <?php
    }
}
