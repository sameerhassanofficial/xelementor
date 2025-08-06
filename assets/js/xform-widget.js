jQuery(document).ready(function($) {
    // Range slider value display
    $(document).on('input', 'input[type="range"]', function() {
        var value = $(this).val();
        $(this).siblings('.range-value-display').find('.range-value').text(value);
    });





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
        
        // Show processing message
        submitBtn.attr('title', 'Processing your request...');
        
        // Check if form has file inputs
        const hasFileInputs = form.find('input[type="file"]').length > 0;
        
        let ajaxData;
        let processData = true;
        let contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        
        if (hasFileInputs) {
            // Use FormData for file uploads
            const formData = new FormData(form[0]);
            formData.append('action', 'submit_advanced_form');
            formData.append('form_id', formId);
            formData.append('advanced_form_nonce', advanced_form_ajax.nonce);
            
            // Include email settings if they exist
            formData.append('_send_email', form.find('input[name="_send_email"]').val());
            formData.append('_email_to', form.find('input[name="_email_to"]').val());
            formData.append('_email_subject', form.find('input[name="_email_subject"]').val());
            formData.append('_email_from_name', form.find('input[name="_email_from_name"]').val());
            formData.append('_email_custom_message', form.find('input[name="_email_custom_message"]').val());
            formData.append('_email_message', form.find('input[name="_email_message"]').val());
            formData.append('_email_format', form.find('input[name="_email_format"]').val());
            
            ajaxData = formData;
            processData = false;
            contentType = false;
        } else {
            // Use serialized data for regular forms
            const formData = form.serializeArray();
            ajaxData = {
                action: 'submit_advanced_form',
                form_id: formId,
                form_data: formData,
                advanced_form_nonce: advanced_form_ajax.nonce,
                _send_email: form.find('input[name="_send_email"]').val(),
                _email_to: form.find('input[name="_email_to"]').val(),
                _email_subject: form.find('input[name="_email_subject"]').val(),
                _email_from_name: form.find('input[name="_email_from_name"]').val(),
                _email_custom_message: form.find('input[name="_email_custom_message"]').val(),
                _email_message: form.find('input[name="_email_message"]').val(),
                _email_format: form.find('input[name="_email_format"]').val()
            };
        }
        

        
        // AJAX submission
        $.ajax({
            url: advanced_form_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            processData: processData,
            contentType: contentType,
            timeout: 30000, // 30 second timeout
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
            error: function(xhr, status, error) {
                if (status === 'timeout') {
                    errorMessage.text('Request timed out. Please try again.').show();
                } else {
                    errorMessage.text('An error occurred. Please try again.').show();
                }
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false);
                submitText.show();
                loadingSpinner.hide();
                submitBtn.attr('title', '');
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

            // URL validation
            if (field.attr('type') === 'url' && field.val().trim()) {
                const urlRegex = /^https?:\/\/.+/;
                if (!urlRegex.test(field.val().trim())) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Please enter a valid URL</div>');
                }
            }

            // Number validation
            if (field.attr('type') === 'number' && field.val().trim()) {
                const numValue = parseFloat(field.val());
                const min = parseFloat(field.attr('min'));
                const max = parseFloat(field.attr('max'));
                
                if (isNaN(numValue)) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Please enter a valid number</div>');
                } else if (min && numValue < min) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Value must be at least ' + min + '</div>');
                } else if (max && numValue > max) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">Value must be at most ' + max + '</div>');
                }
            }

            // File validation
            if (field.attr('type') === 'file' && field[0].files.length > 0) {
                const file = field[0].files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB default
                
                if (file.size > maxSize) {
                    isValid = false;
                    fieldContainer.addClass('field-error');
                    fieldContainer.append('<div class="field-error-message">File size must be less than 5MB</div>');
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
