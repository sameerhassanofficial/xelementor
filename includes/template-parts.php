<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Render custom header/footer templates
function render_custom_template($type) {
    if (!class_exists('\Elementor\Plugin')) {
        return;
    }

    $query = new WP_Query([
        'post_type' => 'elementor_library',
        'posts_per_page' => 1,
        'meta_key' => '_custom_template_type',
        'meta_value' => $type,
        'post_status' => 'publish',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if ($query->have_posts()) {
        $template_id = $query->posts[0]->ID;
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
    }

    wp_reset_postdata();
}

// Hook header and footer rendering
add_action('wp_body_open', function() {
    render_custom_template('header');
}, 5);

add_action('wp_footer', function() {
    render_custom_template('footer');
}, 5);
?>
