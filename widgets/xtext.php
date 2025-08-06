<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class XText_Widget extends Widget_Base {

    public function get_name() {
        return 'xtext_widget';
    }

    public function get_title() {
        return __( 'XText', 'textdomain' );
    }

    public function get_icon() {
        return 'eicon-text';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return ['text', 'title', 'heading', 'content', 'editor', 'copyright'];
    }

    protected function register_controls() {

        // --- CONTENT SECTION ---
        $this->start_controls_section('content_section', [
            'label' => __( 'Content', 'textdomain' ),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('content_type', [
            'label' => __( 'Content Type', 'textdomain' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'title' => ['title' => __( 'Single Title', 'textdomain' ), 'icon' => 'eicon-heading'],
                'editor' => ['title' => __( 'Editor', 'textdomain' ), 'icon' => 'eicon-edit'],
                'copyright' => ['title' => __( 'Copyright', 'textdomain' ), 'icon' => 'eicon-lock-user'],
                'before_after' => ['title' => __( 'Dual Title', 'textdomain' ), 'icon' => 'eicon-heading'],
                'highlighted' => ['title' => __( 'Highlighted Text', 'textdomain' ), 'icon' => 'eicon-text-highlight'],
            ],
            'default' => 'title',
            'toggle' => true,
        ]);

        $this->add_control('title_text', [
            'label' => __( 'Title Text', 'textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Default Title', 'textdomain' ),
            'dynamic' => [ 'active' => true ],
            'condition' => ['content_type' => 'title'],
        ]);

        $this->add_control('heading_level', [
            'label' => __( 'Heading Level', 'textdomain' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
            ],
            'default' => 'h2',
            'condition' => ['content_type' => ['title', 'before_after', 'highlighted']],
        ]);

        $this->add_control('title_link', [
            'label' => __( 'Link URL', 'textdomain' ),
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'show_external' => true,
            'dynamic' => [ 'active' => true ],
            'condition' => ['content_type' => ['title', 'before_after', 'highlighted']],
        ]);

        $this->add_control('first_part_text', [
            'label' => __( 'First Part Text', 'textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'First Part', 'textdomain' ),
            'condition' => ['content_type' => ['before_after', 'highlighted']],
        ]);

        $this->add_control('second_part_text', [
            'label' => __( 'Second Part Text', 'textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Second Part', 'textdomain' ),
            'condition' => ['content_type' => ['before_after', 'highlighted']],
        ]);

        $this->add_control('highlight_style', [
            'label' => __( 'Highlight Style', 'textdomain' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'background',
            'options' => [
                'background' => __( 'Background', 'textdomain' ),
                'underline' => __( 'Underline', 'textdomain' ),
                'border' => __( 'Border', 'textdomain' ),
                'gradient' => __( 'Gradient', 'textdomain' ),
            ],
            'condition' => ['content_type' => 'highlighted'],
        ]);

        $this->add_control('editor_text', [
            'label' => __( 'Editor Content', 'textdomain' ),
            'type' => Controls_Manager::WYSIWYG,
            'default' => __( 'Editor content here...', 'textdomain' ),
            'condition' => ['content_type' => 'editor'],
        ]);

        $this->add_control('copyright_text', [
            'label' => __( 'Copyright Text', 'textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => '© [year] ' . get_bloginfo('name') . '. All rights reserved.',
            'description' => __( 'Use [year] to automatically insert the current year', 'textdomain' ),
            'condition' => ['content_type' => 'copyright'],
        ]);

        $this->end_controls_section();

        // --- STYLE SECTION ---
        $this->start_controls_section('style_section', [
            'label' => __( 'Style', 'textdomain' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('text_alignment', [
            'label' => __( 'Text Alignment', 'textdomain' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                'justify' => ['title' => 'Justify', 'icon' => 'eicon-text-align-justify'],
            ],
            'default' => 'left',
            'selectors' => [
                '{{WRAPPER}} .xtext-content' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_color', [
            'label' => __( 'Title Color', 'textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .xtext-title, {{WRAPPER}} .xtext-first-part' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('second_part_color', [
            'label' => __( 'Second Part Color', 'textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .xtext-second-part' => 'color: {{VALUE}};',
            ],
            'condition' => ['content_type' => ['before_after', 'highlighted']],
        ]);

        $this->add_control('highlight_color', [
            'label' => __( 'Highlight Color', 'textdomain' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#ff6b6b',
            'selectors' => [
                '{{WRAPPER}} .xtext-highlighted .xtext-second-part' => 'color: {{VALUE}};',
                '{{WRAPPER}} .xtext-highlighted .xtext-second-part.highlight-bg' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .xtext-highlighted .xtext-second-part.highlight-underline' => 'border-bottom: 2px solid {{VALUE}};',
                '{{WRAPPER}} .xtext-highlighted .xtext-second-part.highlight-border' => 'border: 2px solid {{VALUE}};',
            ],
            'condition' => ['content_type' => 'highlighted'],
        ]);

        $this->add_control('link_color', [
            'label' => __( 'Link Color', 'textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .xtext-title a' => 'color: {{VALUE}};',
            ],
            'condition' => ['content_type' => ['title', 'before_after', 'highlighted']],
        ]);

        $this->add_control('link_hover_color', [
            'label' => __( 'Link Hover Color', 'textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .xtext-title a:hover' => 'color: {{VALUE}};',
            ],
            'condition' => ['content_type' => ['title', 'before_after', 'highlighted']],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .xtext-content',
        ]);

        $this->add_group_control(Group_Control_Text_Shadow::get_type(), [
            'name' => 'text_shadow',
            'selector' => '{{WRAPPER}} .xtext-content',
        ]);

        $this->end_controls_section();

        // --- BACKGROUND SECTION ---
        $this->start_controls_section('background_section', [
            'label' => __( 'Background', 'textdomain' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'background',
            'selector' => '{{WRAPPER}} .xtext-content',
        ]);

        $this->add_control('padding', [
            'label' => __( 'Padding', 'textdomain' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .xtext-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'border',
            'selector' => '{{WRAPPER}} .xtext-content',
        ]);

        $this->add_control('border_radius', [
            'label' => __( 'Border Radius', 'textdomain' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .xtext-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}} .xtext-content',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $content_type = $settings['content_type'];
        
        echo '<div class="xtext-content xtext-' . esc_attr($content_type) . '">';

        if ( $content_type === 'title' ) {
            $this->render_title($settings);
        } elseif ( $content_type === 'before_after' ) {
            $this->render_dual_title($settings);
        } elseif ( $content_type === 'highlighted' ) {
            $this->render_highlighted_title($settings);
        } elseif ( $content_type === 'editor' ) {
            echo wp_kses_post( $settings['editor_text'] );
        } elseif ( $content_type === 'copyright' ) {
            $this->render_copyright($settings);
        }

        echo '</div>';
    }

    private function render_title($settings) {
        $tag = $settings['heading_level'];
        $text = esc_html( $settings['title_text'] );
        $link = $settings['title_link'];

        if ( ! empty( $link['url'] ) ) {
            $this->add_render_attribute( 'link', 'href', esc_url( $link['url'] ) );
            if ( $link['is_external'] ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
                $this->add_render_attribute( 'link', 'rel', 'noopener' );
            }
            echo "<{$tag} class='xtext-title'><a " . $this->get_render_attribute_string( 'link' ) . ">{$text}</a></{$tag}>";
        } else {
            echo "<{$tag} class='xtext-title'>{$text}</{$tag}>";
        }
    }

    private function render_dual_title($settings) {
        $tag = $settings['heading_level'];
        echo "<{$tag} class='xtext-dual-title'>";
        echo "<span class='xtext-first-part'>" . esc_html( $settings['first_part_text'] ) . "</span> ";
        echo "<span class='xtext-second-part'>" . esc_html( $settings['second_part_text'] ) . "</span>";
        echo "</{$tag}>";
    }

    private function render_highlighted_title($settings) {
        $tag = $settings['heading_level'];
        $highlight_style = $settings['highlight_style'];
        $style_class = 'highlight-' . $highlight_style;
        
        echo "<{$tag} class='xtext-highlighted'>";
        echo "<span class='xtext-first-part'>" . esc_html( $settings['first_part_text'] ) . "</span> ";
        echo "<span class='xtext-second-part {$style_class}'>" . esc_html( $settings['second_part_text'] ) . "</span>";
        echo "</{$tag}>";
    }

    private function render_copyright($settings) {
        $text = $settings['copyright_text'];
        
        // Replace [year] with current year
        $text = str_replace('[year]', date('Y'), $text);
        
        echo '<small class="xtext-copyright">' . esc_html( $text ) . '</small>';
    }
}
