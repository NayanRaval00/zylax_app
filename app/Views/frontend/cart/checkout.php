<?= $this->include('frontend/layouts/header') ?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door" style="color: #EB4227;"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a href="/">Checkout</a></li>
            </ol>
        </nav>
    </div>
</section>
<!-- <form action="<?= base_url('paypal/create-order') ?>" method="POST">
    <button type="submit">Pay with PayPal</button>
</form> -->
<!-- Content Section -->
<section class="cart-area cart-checkout pt-100 pb-100">
    <div class="container">
    <form method="post" action="<?= base_url('guest') ?>" class="checkout-form">
        <div class="row">
            <?php if (session()->has('validation')): ?>
                <div class="alert alert-danger">
                    <?= session('validation')->listErrors(); ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-8">
                <h1 class="checkout-title">Billing Information</h1>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="firstname">First Name *</label>
                        <input type="text" class="form-control" id="firstname" value="<?php echo isset($user['fname']) ? $user['fname'] : ''; ?>" name="firstname" placeholder="First Name" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="lastname">Last Name *</label>
                        <input type="text" class="form-control" id="lastname" value="<?php echo isset($user['lname']) ? $user['lname'] : ''; ?>" name="lastname" placeholder="Last Name" required>
                    </div>
                    <div class="col-lg-12">
                        <label for="companyname">Company Name (Optional)</label>
                        <input type="text" class="form-control" id="companyname" name="companyname">
                    </div>
                    <div class="col-lg-6">
                        <label for="address">Address 1 *</label>
                        <input class="form-control" id="address_1" name="address_1" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="address">Address 2 *</label>
                        <input class="form-control" id="address_2" name="address_2">
                    </div>
                    <div class="col-lg-4">
                        <label for="state">Region/State *</label>
                        <input class="form-control" id="state" name="state" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="city">Suburb *</label>
                        <input class="form-control" id="city" name="city" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="pincode">Pincode *</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" id="email" name="email" required>
                    </div>
                    <div class="col-lg-6">
                        <label for="phoneno">Phone Number *</label>
                        <input type="text" class="form-control" value="<?php echo isset($user['mobile']) ? $user['mobile'] : ''; ?>" id="phoneno" name="phoneno" required>
                    </div>
                    <div class="col-lg-12">
                        <input type="checkbox" id="shipDifferentAddress"> Ship to a different address
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="row shipaddress-details" id="shipaddress-details" style="display: none;">
                <div class="col-lg-6">
                        <label for="address">Shipping Address 1 *</label>
                        <input class="form-control" id="address_1" name="ship_address_1">
                    </div>
                    <div class="col-lg-6">
                        <label for="address">Shipping Address 2 *</label>
                        <input class="form-control" id="address_2" name="ship_address_2">
                    </div>
                    <div class="col-lg-4">
                        <label for="state">Region/State *</label>
                        <input class="form-control" id="ship_state" name="ship_state">
                    </div>
                    <div class="col-lg-4">
                        <label for="city">Suburb *</label>
                        <input class="form-control" id="ship_city" name="ship_city">
                    </div>
                    <div class="col-lg-4">
                        <label for="pincode">Pincode *</label>
                        <input type="text" class="form-control" id="ship_pincode" name="ship_pincode">
                    </div>
                </div>

                    <!-- Payment Options -->
                <div class="card">
                    <h5 class="card-header checkout-title">Payment Option *</h5>
                    <div class="payment-container">
                        <div class="payment-option">
                            <input type="radio" name="payment" id="bank_deposit" value="bank_deposit">
                            <label for="bank_deposit">
                                <img src="assets/frontend/images/venmo.svg" alt="bank_deposit" />
                                <br>Bank Deposit
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" name="payment" id="paypal" value="paypal">
                            <label for="paypal">
                                <img src="assets/frontend/images/paypal.svg" alt="PayPal" />
                                <br>PayPal
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" name="payment" id="nab" value="nab">
                            <label for="nab">
                                <img src="https://www.zylax.com.au/assets/front/images/icons/debit-card.png" alt="Amazon Pay" />
                                <br>Debit CardCredit/Debit Card(NAB Pay)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <h2 class="checkout-title">Additional Information</h2>
                <div class="col-lg-12">
                    <label for="ordernote">Order Notes (Optional)</label>
                    <textarea class="form-control ordernote" id="ordernote" name="ordernote"
                        placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="border">
                            <div class="heading_s1 mb-3">
                                <h2 class="page-title">Cart Totals</h2>
                            </div>
                            <div class="list-group" id="checkout-product">

                            </div><br>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="shipping_checkout">
                                        <tr>
                                            <td class="cart_total_label">Cart Total Exc. GST</td>
                                            <td class="cart_total_amount chk-sub">$00.00</td>
                                        </tr>
                                        <!-- <tr class="shipping_checkout">
                                        </tr> -->
                                        <tr class="gst_including">
                                        </tr>
                                        <tr>
                                            <input type="hidden" id="discount_price" name="discount_price">
                                            <input type="hidden" id="discount_type" name="discount_type">
                                            <td class="cart_total_label">Discount</td>
                                            <td class="cart_total_amount discount">$00.00</td>
                                        </tr>
                                        <tr class="topborder">
                                            <input type="hidden" id="total_amt" name="total_amt">
                                            <td class=""><strong>Total</strong></td>
                                            <td class="cart_total_amount chk-ttl">$00.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (session()->has('user_id')) { ?>
                            <button type="submit" name="submit" class="w-100 btn btn-lg btn-orange">Place Order <i
                                    class="bi bi-arrow-right"></i></button>
                            <?php }elseif($chkenabled){ ?>
                            <button type="submit" name="submit" class="w-100 btn btn-lg btn-orange">Guest Checkout <i
                                class="bi bi-arrow-right"></i></button>
                            <?php }else{ ?>
                                <button type="button" onclick="show_login()" class="w-100 btn btn-lg btn-orange">Login Order <i
                                class="bi bi-arrow-right"></i></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</section>

<?= $this->include('frontend/layouts/footer') ?>

<script>
$(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
$(".qtybutton").on("click", function() {
    var $button = $(this);
    var oldValue = $button.parent().find("input").val();
    if ($button.text() == "+") {
        var newVal = parseFloat(oldValue) + 1;
    } else {
        // Don't allow decrementing below zero
        if (oldValue > 0) {
            var newVal = parseFloat(oldValue) - 1;
        } else {
            newVal = 0;
        }
    }
    $button.parent().find("input").val(newVal);
});
</script>

<script>
$(document).ready(function() {
    $('#shipDifferentAddress').change(function() {
        if ($(this).is(':checked')) {
            $('#shipaddress-details').slideDown();
        } else {
            $('#shipaddress-details').slideUp();
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const cardOption = document.getElementById("card");
    const cardDetails = document.getElementById("card-details");
    const allPaymentOptions = document.querySelectorAll('input[name="payment"]');

    allPaymentOptions.forEach(option => {
        option.addEventListener("change", function () {
            if (cardOption.checked) {
                cardDetails.style.display = "block";
            } else {
                cardDetails.style.display = "none";
            }
        });
    });
});

// auto address fetch
(function() {
    var widget, initAddressFinder = function() {
        console.log(document.getElementById('address_1'))
        widget = new AddressFinder.Widget(
            document.getElementById('address_1'),
            '3AUMPCNBFJX94HKR8DWT',
            'AU', {
                "address_params": {
                "gnaf" : "1",
                }
            }
        );
        widget.on('result:select', function(fullAddress, metaData) {
            // You will need to update these ids to match those in your form
            document.getElementById('address_1').value = metaData.address_line_1;
            document.getElementById('address_2').value = metaData.address_line_2;
            document.getElementById('city').value = metaData.locality_name;
            document.getElementById('state').value = metaData.state_territory;
            document.getElementById('pincode').value = metaData.postcode;

        });
    };

    function downloadAddressFinder() {
        var script = document.createElement('script');
        script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
        script.async = true;
        script.onload = initAddressFinder;
        document.body.appendChild(script);
    };

    document.addEventListener('DOMContentLoaded', downloadAddressFinder);
})();


function show_login(){
    <?php if(!session()->has('user_id')){ ?>
        $('#loginModal').modal('show');
    <?php } ?>
}
</script>