<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Shortcodes for dynamic content
function get_site_title_shortcode($atts) {
    return esc_html(get_bloginfo('name'));
}
add_shortcode('site_title', 'get_site_title_shortcode');

function get_site_description_shortcode($atts) {
    return esc_html(get_bloginfo('description'));
}
add_shortcode('site_description', 'get_site_description_shortcode');

function get_site_url_shortcode($atts) {
    return esc_url(get_site_url());
}
add_shortcode('site_url', 'get_site_url_shortcode');

function get_current_date_shortcode($atts) {
    $atts = shortcode_atts([
        'format' => 'F j, Y'
    ], $atts);

    return esc_html(date($atts['format']));
}
add_shortcode('date', 'get_current_date_shortcode');

function get_current_time_shortcode($atts) {
    $atts = shortcode_atts([
        'format' => 'g:i a'
    ], $atts);

    return esc_html(date($atts['format']));
}
add_shortcode('time', 'get_current_time_shortcode');
?>
