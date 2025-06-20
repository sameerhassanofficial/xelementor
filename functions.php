<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Load configuration
require_once get_stylesheet_directory() . '/includes/config.php';

// Load enqueue functions
require_once get_stylesheet_directory() . '/includes/enqueue.php';

// Load Elementor helpers
require_once get_stylesheet_directory() . '/includes/elementor-helpers.php';

// Load WordPress helpers
require_once get_stylesheet_directory() . '/includes/wordpress-helpers.php';

// Load shortcodes
require_once get_stylesheet_directory() . '/includes/shortcodes.php';

// Load template parts
require_once get_stylesheet_directory() . '/includes/template-parts.php';

?>
