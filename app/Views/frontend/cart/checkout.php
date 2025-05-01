<?= $this->include('frontend/layouts/header') ?>

<?php 

// echo "<pre>";
// print_r($addressModel);
// echo "</pre>";

// die;
?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url() ?>"><i class="bi bi-house-door"
                            style="color: #EB4227;"></i></a>
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
                            <input type="text" class="form-control" id="firstname"
                                value="<?= old('firstname', $user['fname'] ?? '') ?>" name="firstname"
                                placeholder="First Name">
                        </div>
                        <div class="col-lg-6">
                            <label for="lastname">Last Name *</label>
                            <input type="text" class="form-control" id="lastname"
                                value="<?= old('lastname', $user['lname'] ?? '') ?>" name="lastname"
                                placeholder="Last Name">
                        </div>
                        <div class="col-lg-12">
                            <label for="companyname">Company Name (Optional)</label>
                            <input type="text" class="form-control" id="companyname" name="companyname"
                                value="<?= old('companyname', $addressModel['company'] ?? '') ?>">
                        </div>
                        <div class="col-lg-6">
                            <label for="address">Address 1 *</label>
                            <input class="form-control" id="address_1" name="address_1" value="<?= old('address_1', $addressModel['address_1'] ?? '') ?>"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label for="address">Address 2 *</label>
                            <input class="form-control" id="address_2" name="address_2" value="<?= old('address_2',$addressModel['address_2'] ?? '') ?>">
                        </div>
                        <div class="col-lg-4">
                            <label for="state">Region/State *</label>
                            <input class="form-control" id="state" name="state" value="<?= old('state', $addressModel['state'] ?? '') ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="city">Suburb *</label>
                            <input class="form-control" id="city" name="city" value="<?= old('city', $addressModel['city'] ?? '') ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="pincode">Pincode *</label>
                            <input type="text" class="form-control" id="pincode" name="pincode"
                                value="<?= old('pincode', $addressModel['pincode'] ?? '') ?>" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>"
                                id="email" name="email" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="phoneno">Phone Number *</label>
                            <input type="text" class="form-control" value="<?= old('phoneno', $user['mobile'] ?? '') ?>"
                                id="phoneno" name="phoneno" required>
                        </div>
                        <div class="col-lg-12">
                            <input type="checkbox" id="shipDifferentAddress" name="shipDifferentAddress"
                                <?= old('shipDifferentAddress') ? 'checked' : '' ?>> Ship to a different address
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="row shipaddress-details" id="shipaddress-details" style="display: none;">
                        <div class="col-lg-6">
                            <label for="address">Shipping Address 1 *</label>
                            <input class="form-control" id="ship_address_1" name="ship_address_1"
                                value="<?= old('ship_address_1') ?>">
                        </div>
                        <div class="col-lg-6">
                            <label for="address">Shipping Address 2 *</label>
                            <input class="form-control" id="ship_address_2" name="ship_address_2"
                                value="<?= old('ship_address_2') ?>">
                        </div>
                        <div class="col-lg-4">
                            <label for="state">Region/State *</label>
                            <input class="form-control" id="ship_state" name="ship_state"
                                value="<?= old('ship_state') ?>">
                        </div>
                        <div class="col-lg-4">
                            <label for="city">Suburb *</label>
                            <input class="form-control" id="ship_city" name="ship_city" value="<?= old('ship_city') ?>">
                        </div>
                        <div class="col-lg-4">
                            <label for="pincode">Pincode *</label>
                            <input type="text" class="form-control" id="ship_pincode" name="ship_pincode"
                                value="<?= old('ship_pincode') ?>">
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="card">
                        <h5 class="card-header checkout-title">Payment Option *</h5>
                        <div class="payment-container">
                            <div class="payment-option">
                                <input type="radio" name="payment" id="bank_deposit" value="bank_deposit"
                                    <?= old('payment') == 'bank_deposit' ? 'checked' : '' ?>>
                                <label for="bank_deposit">
                                    <img src="assets/frontend/images/venmo.svg" alt="bank_deposit" /><br>Bank Deposit
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="payment" id="paypal" value="paypal"
                                    <?= old('payment') == 'paypal' ? 'checked' : '' ?>>
                                <label for="paypal">
                                    <img src="assets/frontend/images/paypal.svg" alt="PayPal" /><br>PayPal
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="payment" id="nab" value="nab"
                                    <?= old('payment') == 'nab' ? 'checked' : '' ?>>
                                <label for="nab">
                                    <img src="https://www.zylax.com.au/assets/front/images/icons/debit-card.png"
                                        alt="Debit Card" /><br>Debit/Credit Card (NAB Pay)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h2 class="checkout-title">Additional Information</h2>
                    <div class="col-lg-12">
                        <label for="ordernote">Order Notes (Optional)</label>
                        <textarea class="form-control ordernote" id="ordernote" name="ordernote"
                            placeholder="Notes about your order, e.g. special notes for delivery"><?= old('ordernote') ?></textarea>
                    </div>
                </div>

                <!-- Right Side: Cart Summary -->
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="border">
                                <div class="heading_s1 mb-3">
                                    <h2 class="page-title">Cart Totals</h2>
                                </div>
                                <?php if (!empty($checkout_data['products'])) : ?>
                                <div class="list-group" id="checkout-product">
                                    <?php foreach ($checkout_data['products'] as $index => $product) : ?>
                                    <a href="#" class="list-group-item d-flex align-items-center">
                                        <img src="<?= esc($product['prd_image']) ?>" alt="Product"
                                            class="me-3 height-64">

                                        <input type="hidden" value="<?= esc($product['prd_id']) ?>" name="item_id[]">
                                        <input type="hidden" value="<?= isset($product['prd_image']) ? esc($product['prd_image']) : '' ?>"
                                            name="item_image[]">
                                        <input type="hidden" name="item_unit_price[]" value="<?= esc($product['prd_unit_price']) ?>">
                                        <input type="hidden" value="<?= esc($product['prd_name']) ?>"
                                            name="item_name[]">
                                        <input type="hidden" value="<?= esc($product['prd_qty']) ?>" name="item_qty[]">
                                        <input type="hidden"
                                            value="<?= str_replace(',', '', esc($product['prd_total'])) ?>"
                                            name="item_price[]">

                                        <div class="flex-grow-1">
                                            <p class="mb-0"><?= esc($product['prd_name']) ?></p>
                                            <p class="mb-0"><?= esc($product['prd_qty']) ?> x <span
                                                    class="text-orange fw-bold">$<?= esc($product['prd_total']) ?></span>
                                            </p>

                                            <?php if (!empty($checkout_data['addonData'][$index])) : ?>
                                            <?php foreach ($checkout_data['addonData'][$index] as $addon) : ?>
                                            <input type="hidden" name="addonSet[<?= $index ?>][]"
                                                value="<?= esc($addon['addon_set']) ?>">
                                            <input type="hidden" name="addonName[<?= $index ?>][]"
                                                value="<?= esc($addon['addon_name']) ?>">
                                            <input type="hidden" name="addonprice[<?= $index ?>][]"
                                                value="<?= esc($addon['addon_price']) ?>">

                                            <p class="mb-0 small text-muted">
                                                <?= esc($addon['addon_set']) ?>: <?= esc($addon['addon_name']) ?>
                                                (+$<?= esc($addon['addon_price']) ?>)
                                            </p>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <br>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody id="shipping_checkout">

                                            <!-- Total Products Excluding GST -->
                                            <tr>
                                                <input type="hidden" id="total_prd_amt" name="total_prd_amt"
                                                    value="<?= esc($checkout_data['total_prd_amt']) ?>">
                                                <td class="cart_total_label">Cart Total Exc. GST</td>
                                                <td class="cart_total_amount chk-sub">
                                                    $<?= esc($checkout_data['total_prd_amt']) ?></td>
                                            </tr>

                                            <!-- Shipping Method -->
                                            <tr>
                                                <td class="cart_total_label">
                                                    <div class="radio-info"
                                                        style="display: flex; align-items: center; gap: 10px;">
                                                        <input type="hidden" name="item_shipid"
                                                            value="<?= esc($checkout_data['item_shipid']) ?>">
                                                        <input type="hidden" name="item_shipprice"
                                                            value="<?= esc($checkout_data['item_shipprice']) ?>">
                                                        <input type="hidden" name="ship_gst"
                                                            value="<?= esc($checkout_data['ship_gst']) ?>">
                                                        <input type="hidden" name="exclude_ship_amount"
                                                            value="<?= esc($checkout_data['exclude_ship_amount']) ?>">

                                                        <input type="radio"
                                                            id="flat-rate_<?= esc($checkout_data['item_shipid']) ?>"
                                                            class="shipping_method"
                                                            data-price="<?= esc($checkout_data['item_shipprice']) ?>"
                                                            name="radio-flat-rate" checked>

                                                        <label for="flat-rate_<?= esc($checkout_data['item_shipid']) ?>"
                                                            style="display: flex; align-items: center; gap: 8px;">
                                                            <img width="24px" height="24px"
                                                                src="https://www.zylax.com.au/assets/front/images/icons/shipping-icon.png"
                                                                alt="Shipping">
                                                            <span><?= htmlspecialchars($checkout_data['shipping_name']); ?></span>
                                                            <!-- Shipping Name not available in response -->
                                                        </label>
                                                    </div>

                                                    <div class="cart_total_label" style="margin-top: 5px;">
                                                        <span>Excl. GST:
                                                            $<?= esc($checkout_data['exclude_ship_amount']) ?></span><br>
                                                        <span>GST: $<?= esc($checkout_data['ship_gst']) ?></span><br>
                                                    </div>
                                                </td>

                                                <td class="cart_total_amount"
                                                    style="text-align: right; vertical-align: middle;">
                                                    <span>$<?= esc($checkout_data['exclude_ship_amount']) ?></span><br>
                                                    <span>$<?= esc($checkout_data['ship_gst']) ?></span><br>
                                                </td>
                                            </tr>

                                            <!-- GST and Total Price -->
                                            <tr class="gst_including">
                                                <input type="hidden" name="total_product_gst" id="total_product_gst"
                                                    value="<?= esc($checkout_data['total_product_gst']) ?>">
                                                <input type="hidden" name="exculde_product_amount"
                                                    value="<?= esc($checkout_data['exculde_product_amount']) ?>">
                                                <td class="cart_total_label">
                                                    GST Amount: <br> Price Inc. GST:
                                                </td>
                                                <td class="cart_total_amount">
                                                    $<?= esc($checkout_data['total_product_gst']) ?><br>
                                                    $<?= esc($checkout_data['exculde_product_amount']) ?>
                                                </td>
                                            </tr>

                                            <!-- Discount -->
                                            <tr>
                                                <input type="hidden" id="discount_price" name="discount_price"
                                                    value="<?= esc($checkout_data['discount_price']) ?>">
                                                <input type="hidden" id="discount_type" name="discount_type"
                                                    value="<?= esc($checkout_data['discount_type']) ?>">
                                                <td class="cart_total_label">Discount</td>
                                                <td class="cart_total_amount discount">
                                                    $<?= esc($checkout_data['discount_price']) ?>.00</td>
                                            </tr>

                                            <!-- Final Total -->
                                            <tr class="topborder">
                                                <input type="hidden" id="total_amt" name="total_amt"
                                                    value="<?= esc($checkout_data['total_amt']) ?>">
                                                <td><strong>Total</strong></td>
                                                <td class="cart_total_amount chk-ttl">
                                                    $<?= esc($checkout_data['total_amt']) ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <?php if (session()->has('user_id')) { ?>
                                <button type="submit" name="submit" class="w-100 btn btn-lg btn-orange">Place Order <i
                                        class="bi bi-arrow-right"></i></button>
                                <?php } elseif ($chkenabled) { ?>
                                <button type="submit" name="submit" class="w-100 btn btn-lg btn-orange">Guest Checkout
                                    <i class="bi bi-arrow-right"></i></button>
                                <?php } else { ?>
                                <button type="button" onclick="show_login()" class="w-100 btn btn-lg btn-orange">Login
                                    Order <i class="bi bi-arrow-right"></i></button>
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
document.addEventListener("DOMContentLoaded", function() {
    const cardOption = document.getElementById("card");
    const cardDetails = document.getElementById("card-details");
    const allPaymentOptions = document.querySelectorAll('input[name="payment"]');

    allPaymentOptions.forEach(option => {
        option.addEventListener("change", function() {
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
                    "gnaf": "1",
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

(function() {
    var widget, initAddressFinder = function() {
        console.log(document.getElementById('ship_address_1'))
        widget = new AddressFinder.Widget(
            document.getElementById('ship_address_1'),
            '3AUMPCNBFJX94HKR8DWT',
            'AU', {
                "address_params": {
                    "gnaf": "1",
                }
            }
        );
        widget.on('result:select', function(fullAddress, metaData) {
            // You will need to update these ids to match those in your form
            document.getElementById('ship_address_1').value = metaData.address_line_1;
            document.getElementById('ship_address_2').value = metaData.address_line_2;
            document.getElementById('ship_city').value = metaData.locality_name;
            document.getElementById('ship_state').value = metaData.state_territory;
            document.getElementById('ship_pincode').value = metaData.postcode;

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


function show_login() {
    <?php if(!session()->has('user_id')){ ?>
    $('#loginModal').modal('show');
    <?php } ?>
}
</script>