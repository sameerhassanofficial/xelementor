<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class XCarousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'xcarousel';
    }

    public function get_title() {
        return esc_html__('XCarousel', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['carousel', 'slider', 'image', 'gallery'];
    }

    public function get_script_depends() {
        return ['swiper'];
    }

    public function get_style_depends() {
        return ['swiper'];
    }

    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_type',
            [
                'label' => esc_html__('Content Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => esc_html__('Image', 'textdomain'),
                    'template' => esc_html__('Template', 'textdomain'),
                    'custom' => esc_html__('Custom Content', 'textdomain'),
                ],
            ]
        );

        $repeater->add_control(
            'slide_image',
            [
                'label' => esc_html__('Image', 'textdomain'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'slide_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'slide_template',
            [
                'label' => esc_html__('Choose Template', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_page_templates(),
                'condition' => [
                    'slide_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'slide_content',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__('Slide content goes here', 'textdomain'),
                'condition' => [
                    'slide_type' => 'custom',
                ],
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => esc_html__('Title', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Slide Title', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label' => esc_html__('Description', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Slide description goes here', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'slide_button_text',
            [
                'label' => esc_html__('Button Text', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Learn More', 'textdomain'),
            ]
        );

        $repeater->add_control(
            'slide_button_link',
            [
                'label' => esc_html__('Button Link', 'textdomain'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'textdomain'),
            ]
        );



        $repeater->add_control(
            'show_overlay_content',
            [
                'label' => esc_html__('Show Overlay Content', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'slide_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'overlay_alignment',
            [
                'label' => esc_html__('Content Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [
                    'slide_type' => 'image',
                    'show_overlay_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'overlay_position',
            [
                'label' => esc_html__('Content Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'textdomain'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'textdomain'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'condition' => [
                    'slide_type' => 'image',
                    'show_overlay_content' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'overlay_background',
            [
                'label' => esc_html__('Overlay Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'slide_type' => 'image',
                    'show_overlay_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'textdomain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_type' => 'image',
                        'slide_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                    [
                        'slide_type' => 'image',
                        'slide_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                    [
                        'slide_type' => 'image',
                        'slide_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                    ],
                ],
                'title_field' => 'Slide {{{ slide_type }}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_settings',
            [
                'label' => esc_html__('Carousel Settings', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_type',
            [
                'label' => esc_html__('Slider Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'textdomain'),
                    'fade' => esc_html__('Fade', 'textdomain'),
                    'cube' => esc_html__('Cube', 'textdomain'),
                    'coverflow' => esc_html__('Coverflow', 'textdomain'),
                    'flip' => esc_html__('Flip', 'textdomain'),
                    'creative' => esc_html__('Creative', 'textdomain'),
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'condition' => [
                    'slider_type' => ['slide', 'coverflow'],
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
                'condition' => [
                    'slider_type' => ['slide', 'coverflow'],
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label' => esc_html__('Infinite Loop', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Animation Speed (ms)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 500,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__('Space Between (px)', 'textdomain'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 15,
                'tablet_default' => 10,
                'mobile_default' => 5,
            ]
        );

        $this->add_control(
            'effect_3d',
            [
                'label' => esc_html__('3D Effect', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => [
                    'slider_type' => ['slide', 'coverflow'],
                ],
            ]
        );

        $this->add_control(
            'lazy_loading',
            [
                'label' => esc_html__('Lazy Loading', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => esc_html__('Load images only when needed', 'textdomain'),
            ]
        );

        $this->add_control(
            'keyboard_control',
            [
                'label' => esc_html__('Keyboard Control', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => esc_html__('Allow keyboard navigation', 'textdomain'),
            ]
        );

        $this->add_control(
            'mousewheel_control',
            [
                'label' => esc_html__('Mousewheel Control', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => esc_html__('Allow mousewheel navigation', 'textdomain'),
            ]
        );

        $this->end_controls_section();

        // Navigation Settings
        $this->start_controls_section(
            'navigation_section',
            [
                'label' => esc_html__('Navigation', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__('Show Arrows', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrow_type',
            [
                'label' => esc_html__('Arrow Type', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'textdomain'),
                    'icon' => esc_html__('Icon', 'textdomain'),
                    'image' => esc_html__('Custom Image', 'textdomain'),
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label' => esc_html__('Previous Arrow Icon', 'textdomain'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                    'arrow_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label' => esc_html__('Next Arrow Icon', 'textdomain'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                    'arrow_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_image',
            [
                'label' => esc_html__('Previous Arrow Image', 'textdomain'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                    'arrow_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'next_arrow_image',
            [
                'label' => esc_html__('Next Arrow Image', 'textdomain'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                    'arrow_type' => 'image',
                ],
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => esc_html__('Show Dots', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Overlay Content
        $this->start_controls_section(
            'style_overlay',
            [
                'label' => esc_html__('Overlay Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'overlay_title_typography',
                'label' => esc_html__('Title Typography', 'textdomain'),
                'selector' => '{{WRAPPER}} .slide-overlay .slide-title',
            ]
        );

        $this->add_control(
            'overlay_title_color',
            [
                'label' => esc_html__('Title Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'overlay_description_typography',
                'label' => esc_html__('Description Typography', 'textdomain'),
                'selector' => '{{WRAPPER}} .slide-overlay .slide-description',
            ]
        );

        $this->add_control(
            'overlay_description_color',
            [
                'label' => esc_html__('Description Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_padding',
            [
                'label' => esc_html__('Content Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_spacing',
            [
                'label' => esc_html__('Content Spacing', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slide-overlay .slide-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Button
        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Button', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'textdomain'),
                'selector' => '{{WRAPPER}} .slide-overlay .slide-button',
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Background Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => esc_html__('Hover', 'textdomain'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#005a87',
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slide-overlay .slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Slides
        $this->start_controls_section(
            'style_slides',
            [
                'label' => esc_html__('Slides', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slide_height',
            [
                'label' => esc_html__('Height', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .xcarousel-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slide_border',
                'selector' => '{{WRAPPER}} .xcarousel-slide',
            ]
        );

        $this->add_responsive_control(
            'slide_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .xcarousel-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slide_box_shadow',
                'selector' => '{{WRAPPER}} .xcarousel-slide',
            ]
        );

        $this->end_controls_section();

        // Style Section - Arrows
        $this->start_controls_section(
            'style_arrows',
            [
                'label' => esc_html__('Arrows', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => esc_html__('Size', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'font-size: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .swiper-button-next.custom-arrow-icon i, {{WRAPPER}} .swiper-button-prev.custom-arrow-icon i, {{WRAPPER}} .swiper-button-next.custom-arrow-icon svg, {{WRAPPER}} .swiper-button-prev.custom-arrow-icon svg' => 'font-size: calc({{SIZE}}{{UNIT}} / 2); width: calc({{SIZE}}{{UNIT}} / 2); height: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->start_controls_tabs('arrow_style_tabs');

        $this->start_controls_tab(
            'arrow_normal_tab',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next:after, {{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next.custom-arrow-icon i, {{WRAPPER}} .swiper-button-prev.custom-arrow-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next.custom-arrow-icon svg, {{WRAPPER}} .swiper-button-prev.custom-arrow-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background',
            [
                'label' => esc_html__('Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'arrow_hover_tab',
            [
                'label' => esc_html__('Hover', 'textdomain'),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next:hover:after, {{WRAPPER}} .swiper-button-prev:hover:after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next:hover.custom-arrow-icon i, {{WRAPPER}} .swiper-button-prev:hover.custom-arrow-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .swiper-button-next:hover.custom-arrow-icon svg, {{WRAPPER}} .swiper-button-prev:hover.custom-arrow-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_background',
            [
                'label' => esc_html__('Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next:hover, {{WRAPPER}} .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Dots
        $this->start_controls_section(
            'style_dots',
            [
                'label' => esc_html__('Dots', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => esc_html__('Size', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => esc_html__('Spacing', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('dots_style_tabs');

        $this->start_controls_tab(
            'dots_normal_tab',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => esc_html__('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.3)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'dots_active_tab',
            [
                'label' => esc_html__('Active', 'textdomain'),
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => esc_html__('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function get_page_templates() {
        $templates = [];
        
        $library_templates = get_posts([
            'post_type' => 'elementor_library',
            'numberposts' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($library_templates as $template) {
            $templates[$template->ID] = $template->post_title;
        }

        return $templates;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['slides'])) {
            return;
        }

        $swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest') ? 'swiper' : 'swiper-container';

        $this->add_render_attribute('wrapper', 'class', [
            'xcarousel-wrapper',
            $swiper_class,
        ]);

        $show_dots = $settings['show_dots'] === 'yes';
        $show_arrows = $settings['show_arrows'] === 'yes';

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <div class="swiper-wrapper">
                <?php foreach ($settings['slides'] as $index => $slide) : ?>
                    <div class="swiper-slide">
                        <div class="xcarousel-slide">
                            <?php
                            switch ($slide['slide_type']) {
                                case 'image':
                                    if (!empty($slide['slide_image']['url'])) {
                                        echo '<img src="' . esc_url($slide['slide_image']['url']) . '" alt="Slide Image">';
                                        
                                        // Show overlay content only for image slides
                                        if ($slide['show_overlay_content'] === 'yes') {
                                            $overlay_class = 'slide-overlay overlay-' . ($slide['overlay_alignment'] ?? 'center') . ' overlay-' . ($slide['overlay_position'] ?? 'center');
                                            echo '<div class="' . esc_attr($overlay_class) . '">';
                                            
                                            if (!empty($slide['slide_title'])) {
                                                echo '<h3 class="slide-title">' . esc_html($slide['slide_title']) . '</h3>';
                                            }
                                            
                                            if (!empty($slide['slide_description'])) {
                                                echo '<p class="slide-description">' . esc_html($slide['slide_description']) . '</p>';
                                            }
                                            
                                            if (!empty($slide['slide_button_text']) && !empty($slide['slide_button_link']['url'])) {
                                                $this->add_link_attributes('slide-button-' . $index, $slide['slide_button_link']);
                                                echo '<a class="slide-button" ' . $this->get_render_attribute_string('slide-button-' . $index) . '>';
                                                echo esc_html($slide['slide_button_text']);
                                                echo '</a>';
                                            }
                                            
                                            echo '</div>';
                                        }
                                    }
                                    break;

                                case 'template':
                                    if (!empty($slide['slide_template'])) {
                                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($slide['slide_template']);
                                    }
                                    break;

                                case 'custom':
                                    if (!empty($slide['slide_content'])) {
                                        echo '<div class="slide-custom-content">' . wp_kses_post($slide['slide_content']) . '</div>';
                                    }
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($show_dots) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>

            <?php if ($show_arrows) : ?>
                <?php if ($settings['arrow_type'] === 'icon') : ?>
                    <div class="swiper-button-prev custom-arrow-icon">
                        <?php if (!empty($settings['prev_arrow_icon']['value'])) : ?>
                            <?php \Elementor\Icons_Manager::render_icon($settings['prev_arrow_icon'], ['aria-hidden' => 'true']); ?>
                        <?php else : ?>
                            <i class="fas fa-chevron-left"></i>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-button-next custom-arrow-icon">
                        <?php if (!empty($settings['next_arrow_icon']['value'])) : ?>
                            <?php \Elementor\Icons_Manager::render_icon($settings['next_arrow_icon'], ['aria-hidden' => 'true']); ?>
                        <?php else : ?>
                            <i class="fas fa-chevron-right"></i>
                        <?php endif; ?>
                    </div>
                <?php elseif ($settings['arrow_type'] === 'image') : ?>
                    <div class="swiper-button-prev custom-arrow-image">
                        <?php if (!empty($settings['prev_arrow_image']['url']) && $settings['prev_arrow_image']['url'] !== \Elementor\Utils::get_placeholder_image_src()) : ?>
                            <img src="<?php echo esc_url($settings['prev_arrow_image']['url']); ?>" alt="Previous">
                        <?php else : ?>
                            <i class="fas fa-chevron-left"></i>
                        <?php endif; ?>
                    </div>
                    <div class="swiper-button-next custom-arrow-image">
                        <?php if (!empty($settings['next_arrow_image']['url']) && $settings['next_arrow_image']['url'] !== \Elementor\Utils::get_placeholder_image_src()) : ?>
                            <img src="<?php echo esc_url($settings['next_arrow_image']['url']); ?>" alt="Next">
                        <?php else : ?>
                            <i class="fas fa-chevron-right"></i>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <script>
        jQuery(document).ready(function($) {
            var sliderType = '<?php echo $settings["slider_type"]; ?>';
            var swiperConfig = {
                effect: sliderType,
                slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_show']) ? $settings['slides_to_show'] : 3); ?>,
                slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : 1); ?>,
                spaceBetween: <?php echo isset($settings['space_between']) ? $settings['space_between'] : 15; ?>,
                speed: <?php echo $settings['speed']; ?>,
                loop: <?php echo $settings['infinite_loop'] === 'yes' ? 'true' : 'false'; ?>,
                <?php if ($settings['autoplay'] === 'yes') : ?>
                autoplay: {
                    delay: <?php echo $settings['autoplay_speed']; ?>,
                    pauseOnMouseEnter: <?php echo $settings['pause_on_hover'] === 'yes' ? 'true' : 'false'; ?>,
                },
                <?php endif; ?>
                <?php if ($show_dots) : ?>
                pagination: {
                    el: '.elementor-element-<?php echo $this->get_id(); ?> .swiper-pagination',
                    clickable: true,
                },
                <?php endif; ?>
                <?php if ($show_arrows) : ?>
                navigation: {
                    nextEl: '.elementor-element-<?php echo $this->get_id(); ?> .swiper-button-next',
                    prevEl: '.elementor-element-<?php echo $this->get_id(); ?> .swiper-button-prev',
                },
                <?php endif; ?>
                breakpoints: {
                    320: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : 1); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : 1); ?>,
                        spaceBetween: <?php echo isset($settings['space_between_mobile']) ? $settings['space_between_mobile'] : 5; ?>,
                    },
                    768: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : 2); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : 1); ?>,
                        spaceBetween: <?php echo isset($settings['space_between_tablet']) ? $settings['space_between_tablet'] : 10; ?>,
                    },
                    1024: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_show']) ? $settings['slides_to_show'] : 3); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : (isset($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : 1); ?>,
                        spaceBetween: <?php echo isset($settings['space_between']) ? $settings['space_between'] : 15; ?>,
                    },
                }
            };

            // Add effect-specific configurations
            switch(sliderType) {
                case 'fade':
                    swiperConfig.fadeEffect = { crossFade: true };
                    break;
                case 'cube':
                    swiperConfig.cubeEffect = {
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94
                    };
                    break;
                case 'coverflow':
                    swiperConfig.coverflowEffect = {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows: true
                    };
                    break;
                case 'flip':
                    swiperConfig.flipEffect = {
                        slideShadows: true,
                        limitRotation: true
                    };
                    break;
                case 'creative':
                    swiperConfig.creativeEffect = {
                        prev: {
                            translate: ["-120%", 0, -500],
                        },
                        next: {
                            translate: ["120%", 0, -500],
                        }
                    };
                    break;
            }

            var $carousel = jQuery('.elementor-element-<?php echo $this->get_id(); ?> .<?php echo $swiper_class; ?>');
            if (!$carousel.length || typeof Swiper === 'undefined') {
                return;
            }
            var swiper = new Swiper($carousel[0], swiperConfig);
        });
        </script>

        <style>
        .xcarousel-wrapper {
            position: relative;
            overflow: hidden;
        }
        .xcarousel-slide {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .xcarousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-custom-content {
            padding: 20px;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Slide Overlay Styles */
        .xcarousel-slide {
            position: relative;
        }
        
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: rgba(0,0,0,0.5);
            color: #ffffff;
            z-index: 2;
        }
        
        /* Overlay Alignment */
        .slide-overlay.overlay-left {
            align-items: flex-start;
            text-align: left;
        }
        
        .slide-overlay.overlay-center {
            align-items: center;
            text-align: center;
        }
        
        .slide-overlay.overlay-right {
            align-items: flex-end;
            text-align: right;
        }
        
        /* Overlay Position */
        .slide-overlay.overlay-top {
            justify-content: flex-start;
        }
        
        .slide-overlay.overlay-center {
            justify-content: center;
        }
        
        .slide-overlay.overlay-bottom {
            justify-content: flex-end;
        }
        
        /* Overlay Content Styles */
        .slide-overlay .slide-title {
            margin: 0 0 15px 0;
            font-size: 24px;
            font-weight: bold;
            line-height: 1.2;
        }
        
        .slide-overlay .slide-description {
            margin: 0 0 20px 0;
            font-size: 16px;
            line-height: 1.5;
            opacity: 0.9;
        }
        
        .slide-overlay .slide-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007cba;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .slide-overlay .slide-button:hover {
            background-color: #005a87;
            transform: translateY(-2px);
        }
        .swiper-button-next,
        .swiper-button-prev {
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        /* Custom Arrow Icons */
        .swiper-button-prev.custom-arrow-icon,
        .swiper-button-next.custom-arrow-icon {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        
        .swiper-button-prev.custom-arrow-icon i,
        .swiper-button-next.custom-arrow-icon i,
        .swiper-button-prev.custom-arrow-icon svg,
        .swiper-button-next.custom-arrow-icon svg {
            font-size: 20px;
            width: 20px;
            height: 20px;
            display: block !important;
        }
        
        /* Override default arrow styles when using custom icons */
        .swiper-button-prev.custom-arrow-icon:after,
        .swiper-button-next.custom-arrow-icon:after {
            display: none !important;
            content: none !important;
        }
        
        /* Override default arrow styles when using custom images */
        .swiper-button-prev.custom-arrow-image:after,
        .swiper-button-next.custom-arrow-image:after {
            display: none !important;
            content: none !important;
        }
        
        /* Custom Arrow Images */
        .swiper-button-prev.custom-arrow-image,
        .swiper-button-next.custom-arrow-image {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        
        .swiper-button-prev.custom-arrow-image img,
        .swiper-button-next.custom-arrow-image img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block !important;
        }
        .swiper-pagination-bullet {
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        /* Mobile responsive fixes */
        @media (max-width: 767px) {
            .swiper-button-next,
            .swiper-button-prev {
                width: 40px !important;
                height: 40px !important;
            }
            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 16px !important;
            }
        }
        </style>
        <?php
    }
}
