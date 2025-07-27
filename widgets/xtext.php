<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Text_Widget extends Widget_Base {

    public function get_name() {
        return 'custom_advanced_widget';
    }

    public function get_title() {
        return __( 'XText', 'your-textdomain' );
    }

    public function get_icon() {
        return 'eicon-text';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        // --- CONTENT SECTION ---
        $this->start_controls_section('content_section', [
            'label' => __( 'Content', 'your-textdomain' ),
        ]);

        $this->add_control('content_type', [
            'label' => __( 'Content Type', 'your-textdomain' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'title' => ['title' => __( 'Single Title', 'your-textdomain' ), 'icon' => 'eicon-e-heading'],
                'editor' => ['title' => __( 'Editor', 'your-textdomain' ), 'icon' => 'eicon-edit'],
                'copyright' => ['title' => __( 'Copyright', 'your-textdomain' ), 'icon' => 'eicon-lock-user'],
                'before_after' => ['title' => __( 'Span Title', 'your-textdomain' ), 'icon' => 'eicon-heading'],
            ],
            'default' => 'title',
            'toggle' => true,
        ]);

        $this->add_control('title_text', [
            'label' => __( 'Title Text', 'your-textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Default Title', 'your-textdomain' ),
            'dynamic' => [ 'active' => true ],
            'condition' => ['content_type' => 'title'],
        ]);

        $this->add_control('heading_level', [
            'label' => __( 'Heading Level', 'your-textdomain' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
            ],
            'default' => 'h2',
            'condition' => ['content_type' => ['title', 'before_after']],
        ]);

        $this->add_control('title_link', [
            'label' => __( 'Link URL', 'your-textdomain' ),
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'show_external' => true,
            'dynamic' => [ 'active' => true ],
            'condition' => ['content_type' => ['title', 'before_after']],
        ]);

        $this->add_control('first_part_text', [
            'label' => __( 'First Part Text', 'your-textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'First Part', 'your-textdomain' ),
            'condition' => ['content_type' => 'before_after'],
        ]);

        $this->add_control('second_part_text', [
            'label' => __( 'Second Part Text', 'your-textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Second Part', 'your-textdomain' ),
            'condition' => ['content_type' => 'before_after'],
        ]);

        $this->add_control('editor_text', [
            'label' => __( 'Editor Content', 'your-textdomain' ),
            'type' => Controls_Manager::WYSIWYG,
            'default' => __( 'Editor content here...', 'your-textdomain' ),
            'condition' => ['content_type' => 'editor'],
        ]);

        $this->add_control('copyright_text', [
            'label' => __( 'Copyright Text', 'your-textdomain' ),
            'type' => Controls_Manager::TEXT,
            'default' => '© Your Company',
            'condition' => ['content_type' => 'copyright'],
        ]);

        $this->end_controls_section();

        // --- STYLE SECTION ---
        $this->start_controls_section('style_section', [
            'label' => __( 'Style', 'your-textdomain' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('text_alignment', [
            'label' => __( 'Text Alignment', 'your-textdomain' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                'justify' => ['title' => 'Justify', 'icon' => 'eicon-text-align-justify'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .custom-switch-content' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_color', [
            'label' => __( 'Title Color (Single/First)', 'your-textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .custom-title, {{WRAPPER}} .first-part' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('second_part_color', [
            'label' => __( 'Second Part Color', 'your-textdomain' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .second-part' => 'color: {{VALUE}};',
            ],
            'condition' => ['content_type' => 'before_after'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'typography',
            'selector' => '{{WRAPPER}} .custom-switch-content',
        ]);

        $this->add_group_control(Group_Control_Text_Shadow::get_type(), [
            'name' => 'text_shadow',
            'selector' => '{{WRAPPER}} .custom-switch-content',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $content_type = $settings['content_type'];
        echo '<div class="custom-switch-content">';

        if ( $content_type === 'title' ) {
            $tag = $settings['heading_level'];
            $text = esc_html( $settings['title_text'] );
            $link = $settings['title_link'];

            if ( ! empty( $link['url'] ) ) {
                $this->add_render_attribute( 'link', 'href', esc_url( $link['url'] ) );
                if ( $link['is_external'] ) {
                    $this->add_render_attribute( 'link', 'target', '_blank' );
                    $this->add_render_attribute( 'link', 'rel', 'noopener' );
                }
                echo "<{$tag} class='custom-title'><a " . $this->get_render_attribute_string( 'link' ) . ">{$text}</a></{$tag}>";
            } else {
                echo "<{$tag} class='custom-title'>{$text}</{$tag}>";
            }
        }

        if ( $content_type === 'before_after' ) {
            $tag = $settings['heading_level'];
            echo "<{$tag}><span class='first-part'>" . esc_html( $settings['first_part_text'] ) . "</span> ";
            echo "<span class='second-part'>" . esc_html( $settings['second_part_text'] ) . "</span></{$tag}>";
        }

        if ( $content_type === 'editor' ) {
            echo wp_kses_post( $settings['editor_text'] );
        }

        if ( $content_type === 'copyright' ) {
            echo '<small>' . esc_html( $settings['copyright_text'] ) . '</small>';
        }

        echo '</div>';
    }
}
