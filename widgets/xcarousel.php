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
            'slide_link',
            [
                'label' => esc_html__('Link', 'textdomain'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'textdomain'),
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
            'show_dots',
            [
                'label' => esc_html__('Show Dots', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                            if (!empty($slide['slide_link']['url'])) {
                                $this->add_link_attributes('slide-link-' . $index, $slide['slide_link']);
                                echo '<a ' . $this->get_render_attribute_string('slide-link-' . $index) . '>';
                            }

                            switch ($slide['slide_type']) {
                                case 'image':
                                    if (!empty($slide['slide_image']['url'])) {
                                        echo '<img src="' . esc_url($slide['slide_image']['url']) . '" alt="Slide Image">';
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

                            if (!empty($slide['slide_link']['url'])) {
                                echo '</a>';
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
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            <?php endif; ?>
        </div>

        <script>
        jQuery(document).ready(function($) {
            var sliderType = '<?php echo $settings["slider_type"]; ?>';
            var swiperConfig = {
                effect: sliderType,
                slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : $settings['slides_to_show']; ?>,
                slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : $settings['slides_to_scroll']; ?>,
                spaceBetween: <?php echo $settings['space_between']; ?>,
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
                    640: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_show_mobile'] ?: 1); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_scroll_mobile'] ?: 1); ?>,
                        spaceBetween: <?php echo $settings['space_between_mobile'] ?: 5; ?>,
                    },
                    768: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_show_tablet'] ?: 2); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_scroll_tablet'] ?: 1); ?>,
                        spaceBetween: <?php echo $settings['space_between_tablet'] ?: 10; ?>,
                    },
                    1024: {
                        slidesPerView: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_show'] ?: 3); ?>,
                        slidesPerGroup: <?php echo in_array($settings['slider_type'], ['fade', 'cube', 'flip', 'creative']) ? 1 : ($settings['slides_to_scroll'] ?: 1); ?>,
                        spaceBetween: <?php echo $settings['space_between'] ?: 15; ?>,
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

            var swiper = new Swiper('.elementor-element-<?php echo $this->get_id(); ?> .<?php echo $swiper_class; ?>', swiperConfig);
        });
        </script>

        <style>
        .xcarousel-wrapper {
            position: relative;
            overflow:hidden;
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
        .xcarousel-slide a {
            display: block;
            width: 100%;
            height: 100%;
        }
        .slide-custom-content {
            padding: 20px;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .swiper-button-next,
        .swiper-button-prev {
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        .swiper-pagination-bullet {
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        </style>
        <?php
    }
}
