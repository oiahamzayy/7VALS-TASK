jQuery(document).ready(function($) {
    // Function to initialize newsletter forms
    function initNewsletterForms() {
        $(".sam-newsletter-form").off("submit.samNewsletter").on("submit.samNewsletter", function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $button = $form.find(".sam-newsletter-button");
            var $buttonText = $button.find(".button-text");
            var $buttonLoading = $button.find(".button-loading");
            var $message = $form.find(".sam-newsletter-message");
            
            // Clear previous errors
            $form.find(".sam-newsletter-error").text("").removeClass("show");
            $form.find(".sam-newsletter-input").removeClass("error");
            $message.hide().removeClass("success error");
            
            // Get form data
            var formData = {
                action: "sam_newsletter_subscribe",
                name: $form.find("input[name=name]").val(),
                email: $form.find("input[name=email]").val(),
                sam_newsletter_nonce: $form.find("input[name=sam_newsletter_nonce]").val()
            };
            
            // Basic validation
            var errors = {};
            if (!formData.name || formData.name.trim().length < 2) {
                errors.name = "Name is required (min 2 characters)";
            }
            if (!formData.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                errors.email = "Valid email is required";
            }
            
            if (Object.keys(errors).length > 0) {
                Object.keys(errors).forEach(function(field) {
                    $form.find("input[name=" + field + "]").addClass("error");
                    $form.find(".sam-newsletter-error[data-field=" + field + "]").text(errors[field]).addClass("show");
                });
                return;
            }
            
            // Show loading
            $button.prop("disabled", true);
            if ($buttonText.length) $buttonText.hide();
            if ($buttonLoading.length) $buttonLoading.show();
            
            // Submit
            $.ajax({
                url: samNewsletter.ajaxurl,
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $message.removeClass("error").addClass("success").html(response.data.message).show();
                        $form[0].reset();
                    } else {
                        $message.removeClass("success").addClass("error").html(response.data.message || "An error occurred").show();
                    }
                },
                error: function() {
                    $message.removeClass("success").addClass("error").html("Connection error. Please try again.").show();
                },
                complete: function() {
                    $button.prop("disabled", false);
                    if ($buttonText.length) $buttonText.show();
                    if ($buttonLoading.length) $buttonLoading.hide();
                }
            });
        });
    }
    
    // Initialize on page load
    initNewsletterForms();
    
    // Re-initialize when new content is loaded (for dynamic content)
    $(document).on("DOMNodeInserted", function() {
        setTimeout(initNewsletterForms, 100);
    });
});