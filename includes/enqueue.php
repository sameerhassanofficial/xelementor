<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Enqueue child theme styles
function hello_elementor_child_scripts_styles() {
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['hello-elementor-theme-style'],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

// Enqueue Swiper scripts and styles for carousel
function xcarousel_enqueue_scripts() {
    // Only load if Elementor is active and we're on frontend or preview
    if (!class_exists('\Elementor\Plugin')) {
        return;
    }

    $load_scripts = false;

    // Load in preview mode
    if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
        $load_scripts = true;
    }

    // Load on frontend if page uses Elementor
    if (is_singular() && \Elementor\Plugin::$instance->documents->get(get_the_ID())->is_built_with_elementor()) {
        $load_scripts = true;
    }

    // Load in editor mode
    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
        $load_scripts = true;
    }

    // Swiper JS and CSS will be loaded by Elementor as a dependency of the XCarousel widget.
    // if ($load_scripts) {
        // wp_enqueue_style('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css', [], '8.4.5');
        // wp_enqueue_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js', ['jquery'], '8.4.5', true);
    // }
}
add_action('wp_enqueue_scripts', 'xcarousel_enqueue_scripts');

// Enqueue scripts for Elementor editor
function xcarousel_editor_scripts() {
    // Swiper JS and CSS will be loaded by Elementor as a dependency of the XCarousel widget.
    // wp_enqueue_style('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css', [], '8.4.5');
    // wp_enqueue_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js', ['jquery'], '8.4.5', true);
}
add_action('elementor/editor/after_enqueue_styles', 'xcarousel_editor_scripts');
?>
