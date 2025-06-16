<?php
if (!defined('ABSPATH')) exit;

// Define current theme version
define('HELLO_ELEMENTOR_CHILD_VERSION', '2.1.0');

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
}
add_action('elementor/widgets/register', 'register_custom_elementor_widgets');

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

    if ($load_scripts) {
        wp_enqueue_style('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css', [], '8.4.5');
        wp_enqueue_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js', ['jquery'], '8.4.5', true);
    }
}
add_action('wp_enqueue_scripts', 'xcarousel_enqueue_scripts');

// Enqueue scripts for Elementor editor
function xcarousel_editor_scripts() {
    wp_enqueue_style('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css', [], '8.4.5');
    wp_enqueue_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js', ['jquery'], '8.4.5', true);
}
add_action('elementor/editor/after_enqueue_styles', 'xcarousel_editor_scripts');

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

// Add meta box for Template Type
add_action('add_meta_boxes', function () {
    add_meta_box(
        'custom_template_type', 
        'Template Type', 
        function ($post) {
            $value = get_post_meta($post->ID, '_custom_template_type', true);
            wp_nonce_field('custom_template_type_nonce', 'custom_template_type_nonce_field');
            ?>
            <label for="custom_template_type">Select Template Type:</label>
            <select name="custom_template_type" id="custom_template_type" style="width:100%;">
                <option value="">— Default —</option>
                <?php foreach (['header', 'footer'] as $type) : ?>
                    <option value="<?php echo esc_attr($type); ?>" <?php selected($value, $type, true); ?>>
                        <?php echo esc_html(ucfirst($type)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
        }, 
        'elementor_library', 
        'side', 
        'high'
    );
});

// Save post meta
add_action('save_post_elementor_library', function ($post_id) {
    // Security checks
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (!isset($_POST['custom_template_type_nonce_field']) || 
        !wp_verify_nonce($_POST['custom_template_type_nonce_field'], 'custom_template_type_nonce')) return;
    
    // Save the meta
    $template_type = isset($_POST['custom_template_type']) ? sanitize_text_field($_POST['custom_template_type']) : '';
    update_post_meta($post_id, '_custom_template_type', $template_type);
});

// Add custom column to Elementor Library
add_filter('manage_elementor_library_posts_columns', function($columns) {
    $columns['template_type'] = 'Template Type';
    return $columns;
});

// Render custom column content
add_action('manage_elementor_library_posts_custom_column', function ($column, $post_id) {
    if ($column === 'template_type') {
        $type = get_post_meta($post_id, '_custom_template_type', true);
        echo esc_html(ucfirst($type) ?: '—');
    }
}, 10, 2);

// Add quick edit dropdown
add_action('quick_edit_custom_box', function ($column_name, $post_type) {
    if ($column_name !== 'template_type' || $post_type !== 'elementor_library') return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-group">
                <span class="title">Template Type</span>
                <select name="custom_template_type">
                    <option value="">— Default —</option>
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                </select>
            </label>
        </div>
    </fieldset>
    <?php
}, 10, 2);

// Handle quick edit saving
add_action('save_post', function ($post_id) {
    // Security checks
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'elementor_library') return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Check if this is a quick edit
    if (isset($_POST['_inline_edit']) && wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
        if (isset($_POST['custom_template_type'])) {
            update_post_meta($post_id, '_custom_template_type', sanitize_text_field($_POST['custom_template_type']));
        }
    }
});

// Pre-fill quick edit field with JavaScript
add_action('admin_footer-edit.php', function () {
    global $typenow;
    if ($typenow !== 'elementor_library') return;
    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            // Save the original edit function
            const wpInlineEdit = inlineEditPost.edit;
            
            // Override the edit function
            inlineEditPost.edit = function (id) {
                // Call the original function
                wpInlineEdit.apply(this, arguments);
                
                // Get the post ID
                const postId = typeof id === 'object' ? parseInt(id.substr(id.lastIndexOf('-') + 1)) : id;
                
                // Find the template type value from the row
                const $row = $('#post-' + postId);
                let templateType = $row.find('td.template_type').text().trim().toLowerCase();
                
                // Clean up the value
                if (templateType === '—' || templateType === '') {
                    templateType = '';
                }
                
                // Set the value in the quick edit form
                $('select[name="custom_template_type"]').val(templateType);
            };
        });
    </script>
    <?php
});

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

// Add admin notice if widgets folder doesn't exist
add_action('admin_notices', function() {
    if (!is_dir(get_stylesheet_directory() . '/widgets/')) {
        echo '<div class="notice notice-warning is-dismissible">
            <p><strong>Custom Elementor Widgets:</strong> Please create a "widgets" folder in your child theme directory to use custom widgets.</p>
        </div>';
    }
});
?>