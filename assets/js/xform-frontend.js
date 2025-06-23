// XForm Frontend AJAX Handler
(function($) {
    'use strict';

    $(document).on('submit', '.xform-ajax-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $submitButton = $form.find('.xform-submit-button');
        var originalButtonText = $submitButton.text();
        var $messagesDiv = $form.find('.xform-messages');

        // Clear previous messages
        $messagesDiv.html('').removeClass('xform-message-success xform-message-error').hide();

        // Basic client-side validation (example: check required fields)
        var missingRequired = false;
        $form.find('[required]').each(function() {
            if ($(this).val() === '') {
                missingRequired = true;
                $(this).addClass('xform-field-error'); // Add error class for styling
            } else {
                $(this).removeClass('xform-field-error');
            }
        });

        if (missingRequired) {
            $messagesDiv.html('Please fill in all required fields.').addClass('xform-message-error').show();
            // You might want to add a class to the form or specific fields for error styling
            return;
        }

        var formData = $form.serialize();
        // The 'action' for WordPress AJAX is already in formData due to the hidden input field:
        // <input type="hidden" name="action" value="xform_submit_action">

        $.ajax({
            url: xform_ajax_obj.ajax_url, // Passed from wp_localize_script
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $submitButton.prop('disabled', true).text('Sending...');
                $messagesDiv.html('').removeClass('xform-message-success xform-message-error').hide();
            },
            success: function(response) {
                if (response && typeof response.data !== 'undefined' && typeof response.data.message !== 'undefined') {
                    if (response.success) {
                        $messagesDiv.html(response.data.message).addClass('xform-message-success').show();
                        $form[0].reset(); // Reset form on success
                        // Remove error classes if any
                        $form.find('.xform-field-error').removeClass('xform-field-error');
                    } else {
                        $messagesDiv.html(response.data.message).addClass('xform-message-error').show();
                        if (response.data.errors) {
                            console.error('XForm Server Errors:', response.data.errors);
                            // Optionally display field-specific errors if provided by server
                        }
                    }
                } else {
                     // Fallback for unexpected response format
                    $messagesDiv.html('An unexpected response was received from the server.').addClass('xform-message-error').show();
                    console.error('XForm AJAX Error: Unexpected response format', response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var errorMessage = 'An unexpected error occurred. Please try again.';
                if (jqXHR.responseText) {
                    try {
                        var response = JSON.parse(jqXHR.responseText);
                        if (response && response.data && response.data.message) {
                            errorMessage = response.data.message;
                        }
                    } catch (e) {
                        // Not a JSON response, or malformed
                        errorMessage = 'Error: ' + jqXHR.status + ' ' + jqXHR.statusText + '. Check console for more details.';
                    }
                }
                $messagesDiv.html(errorMessage).addClass('xform-message-error').show();
                console.error('XForm AJAX Error Details:', {
                    status: textStatus,
                    error: errorThrown,
                    response: jqXHR.responseText,
                    jqXHR: jqXHR
                });
            },
            complete: function() {
                $submitButton.prop('disabled', false).text(originalButtonText);
            }
        });
    });

})(jQuery);
