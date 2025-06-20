<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

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

// Add admin notice if widgets folder doesn't exist
add_action('admin_notices', function() {
    if (!is_dir(get_stylesheet_directory() . '/widgets/')) {
        echo '<div class="notice notice-warning is-dismissible">
            <p><strong>Custom Elementor Widgets:</strong> Please create a "widgets" folder in your child theme directory to use custom widgets.</p>
        </div>';
    }
});
?>
