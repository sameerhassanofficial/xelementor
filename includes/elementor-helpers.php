<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Check if Elementor is active before registering widgets
function check_elementor_plugin() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning is-dismissible"><p>Custom Elementor widgets require Elementor to be installed and activated.</p></div>';
        });
        return false;
    }
    return true;
}

// Register custom Elementor widgets
function register_custom_elementor_widgets($widgets_manager) {
    // Check if Elementor is loaded
    if (!check_elementor_plugin()) {
        return;
    }

    // Register the existing Text widget
    if (file_exists(get_stylesheet_directory() . '/widgets/xtext.php')) {
        require_once get_stylesheet_directory() . '/widgets/xtext.php';
        if (class_exists('Text_Widget')) {
            $widgets_manager->register(new \Text_Widget());
        }
    }

    // Register the existing XCode widget
    if (file_exists(get_stylesheet_directory() . '/widgets/xcode.php')) {
        require_once get_stylesheet_directory() . '/widgets/xcode.php';
        if (class_exists('XCode_Widget')) {
            $widgets_manager->register(new \XCode_Widget());
        }
    }

    // Register the new XCarousel widget
    if (file_exists(get_stylesheet_directory() . '/widgets/xcarousel.php')) {
        require_once get_stylesheet_directory() . '/widgets/xcarousel.php';
        if (class_exists('XCarousel_Widget')) {
            $widgets_manager->register(new \XCarousel_Widget());
        }
    }

    // Register the XForm Widget
    if (file_exists(get_stylesheet_directory() . '/widgets/xform.php')) {
        require_once get_stylesheet_directory() . '/widgets/xform.php';
        if (class_exists('Advanced_Form_Widget')) {
            $widgets_manager->register(new \Advanced_Form_Widget());
        }
    }
}
add_action('elementor/widgets/register', 'register_custom_elementor_widgets');

// Hide Elementor Pro elements from panel
add_action('elementor/editor/after_enqueue_styles', function() {
    echo '<style>
        #elementor-panel-category-pro-elements,
        #elementor-panel-category-theme-elements,
        #elementor-panel-get-pro-elements-sticky,
        #elementor-panel-category-woocommerce-elements,
        #elementor-panel-get-pro-elements,
        #elementor-panel-category-theme-elements-single,
        #elementor-panel-category-link-in-bio {
            display: none !important;
        }
    </style>';
});
?>
