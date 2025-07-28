jQuery(document).ready(function($) {
    // Handle form submission
    $('.advanced-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formId = form.data('form-id');
        const submitBtn = form.find('.advanced-form-submit');
        const submitText = submitBtn.find('.submit-text');
        const loadingSpinner = submitBtn.find('.loading-spinner');
        const successMessage = form.find('.success-message');
        const errorMessage = form.find('.error-message');
        
        // Validate form
        if (!validateForm(form)) {
            return;
        }
        
        // Show loading state
        submitBtn.prop('disabled', true);
        submitText.hide();
        loadingSpinner.show();
        successMessage.hide();
        errorMessage.hide();
        
        // Serialize form data
        const formData = form.serializeArray();
        
        // AJAX submission
        $.ajax({
            url: advanced_form_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'submit_advanced_form',
                form_id: formId,
                form_data: formData,
                advanced_form_nonce: advanced_form_ajax.nonce,
                // Include email settings if they exist
                _send_email: form.find('input[name="_send_email"]').val(),
                _email_to: form.find('input[name="_email_to"]').val(),
                _email_subject: form.find('input[name="_email_subject"]').val(),
                _email_from_name: form.find('input[name="_email_from_name"]').val(),
                _email_custom_message: form.find('input[name="_email_custom_message"]').val(),
                _email_message: form.find('input[name="_email_message"]').val(),
                _email_format: form.find('input[name="_email_format"]').val()
            },
            success: function(response) {
                if (response.success) {
                    successMessage.text(response.data.message).show();
                    form[0].reset();
                    
                    // Auto-hide success message after 5 seconds
                    setTimeout(function() {
                        successMessage.fadeOut();
                    }, 5000);
                } else {
                    errorMessage.text(response.data.message).show();
                }
            },
            error: function() {
                errorMessage.text('An error occurred. Please try again.').show();
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false);
                submitText.show();
                loadingSpinner.hide();
            }
        });
    });
    
    // Form validation
    function validateForm(form) {
        let isValid = true;
        
        form.find('[required]').each(function() {
            const field = $(this);
            const fieldContainer = field.closest('.advanced-form-field');
            
            // Remove previous error styling
            fieldContainer.removeClass('field-error');
            fieldContainer.find('.field-error-message').remove();
            
            if (!field.val().trim()) {
                isValid = false;
                fieldContainer.addClass('field-error');
                fieldContainer.append('<div class="field-error-message">This field is required</div>');
            }
            
            // Email validation
            if (field.attr('type') === 'email' && field.val().trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.val().trim())) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Please enter a valid email address</div>');
                }
            }
            
            // Phone validation
            if (field.attr('type') === 'tel' && field.val().trim()) {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                if (!phoneRegex.test(field.val().trim().replace(/\s/g, ''))) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Please enter a valid phone number</div>');
                }
            }
        });
        
        return isValid;
    }
    
    // Real-time validation
    $('.advanced-form input, .advanced-form textarea, .advanced-form select').on('blur', function() {
        const field = $(this);
        const fieldContainer = field.closest('.advanced-form-field');
        
        fieldContainer.removeClass('field-error');
        fieldContainer.find('.field-error-message').remove();
        
        if (field.attr('required') && !field.val().trim()) {
            fieldContainer.addClass('field-error');
            fieldContainer.append('<div class="field-error-message">This field is required</div>');
        }
    });
    
    // Character counter for textarea
    $('.advanced-form textarea').each(function() {
        const textarea = $(this);
        const maxLength = textarea.attr('maxlength');
        
        if (maxLength) {
            const counter = $('<div class="character-counter">0/' + maxLength + '</div>');
            textarea.after(counter);
            
            textarea.on('input', function() {
                const currentLength = $(this).val().length;
                counter.text(currentLength + '/' + maxLength);
                
                if (currentLength > maxLength * 0.9) {
                    counter.addClass('warning');
                } else {
                    counter.removeClass('warning');
                }
            });
        }
    });
    
    // File upload preview
    $('.advanced-form input[type="file"]').on('change', function() {
        const input = $(this);
        const files = this.files;
        const preview = input.siblings('.file-preview');
        
        if (preview.length === 0) {
            input.after('<div class="file-preview"></div>');
        }
        
        const previewContainer = input.siblings('.file-preview');
        previewContainer.empty();
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileItem = $('<div class="file-item">' + file.name + ' (' + formatFileSize(file.size) + ')</div>');
            previewContainer.append(fileItem);
        }
    });
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Progressive enhancement for better UX
    $('.advanced-form').addClass('js-enabled');
});

// Add error styling to CSS
const errorStyles = `
<style>
.field-error input,
.field-error textarea,
.field-error select {
    border-color: #e74c3c !important;
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
}

.field-error-message {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 5px;
}

.character-counter {
    text-align: right;
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.character-counter.warning {
    color: #e74c3c;
}

.file-preview {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.file-item {
    padding: 5px 0;
    border-bottom: 1px solid #e1e5e9;
}

.file-item:last-child {
    border-bottom: none;
}
</style>`;

jQuery('head').append(errorStyles);
