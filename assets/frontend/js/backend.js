$(document).ready(function() {
    function handleFormSubmit(formId, url, successRedirect) {
        $(formId + " form").submit(function(e) {
            e.preventDefault();
        
            let $form = $(this);
            let $submitButton = $form.find("button[type=submit]");
            let originalButtonText = $submitButton.html();
            let cart = null;

            let formData = $form.serializeArray();
            if(formId == '#loginForm'){
                cart = localStorage.getItem("cart");            
                // If cart exists and is not empty, add it to form data
                if (cart) {
                    try {
                        let cartData = JSON.parse(cart);
                        formData.push({ name: "cart", value: JSON.stringify(cartData) });
                    } catch (error) {
                        console.error("Error parsing cart data:", error);
                    }
                }
            }
        
            // Show loading state
            $submitButton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        
            $.ajax({
                url: url,
                type: "POST",
                data: $.param(formData),
                dataType: "json",
                success: function(response) {
                    let $messageDiv = $(formId + " .message");
                    let errorMessages = "";
                    if (response.status === 'success') {
                        if (successRedirect){ 
                            window.location = successRedirect;
                            // localStorage.clear();
                        }else {
                            $messageDiv.html(response.message)
                            .removeClass("alert-danger")
                            .addClass("alert alert-success alert-dismissible")
                            .show();
                        }
                    } else if(response.status === 'guest_user'){
                        if (confirm(response.message)) {
                            let $submitButton = $('#forgetp');
                            $submitButton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                            let newFormData = $form.serializeArray();
                            newFormData.push(
                                { name: "confirmed", value: true },
                                { name: "active", value: "1" },
                                { name: "user_type", value: "regular" }
                            );
                            $.ajax({
                                url: url, // Change this to your actual endpoint
                                type: 'POST',
                                data: $.param(newFormData),
                                dataType: "json",
                                beforeSend: function () {
                                    $submitButton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                                },
                                success: function(result) {
                                    if (result.status === 'success') {
                                        $messageDiv.html(result.message)
                                        .removeClass("alert-danger")
                                        .addClass("alert alert-success alert-dismissible")
                                        .show();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error:", error);
                                }
                            });
                        } else {
                            console.log("Staying on the page.");
                        }
                    } else {
                        if (typeof(response.message) === 'object') {
                            $.each(response.message, function(key, value) {
                                errorMessages += value + "<br>"; // Append each error message
                            });
                        } else {
                            errorMessages = response.message;
                        }
                        $messageDiv.html(errorMessages)
                            .removeClass("alert-success")
                            .addClass("alert alert-danger alert-dismissible")
                            .show();
        
                        hideMessage();
                    }
                },
                complete: function() {
                    // Restore button state after operation
                    $submitButton.prop("disabled", false).html(originalButtonText);
                }
            });
        });
    }

    handleFormSubmit("#loginForm", "/auth/login", '/auth/my-account');
    handleFormSubmit("#signUpForm", "/auth/register", '/auth/my-account');
    handleFormSubmit("#forgotPasswordForm", "/auth/forgot-password", null);

    function hideMessage() {
        setTimeout(function () {
            $(".message").fadeOut("slow");
        }, 5000); // 5000 milliseconds = 5 seconds
    }
});
