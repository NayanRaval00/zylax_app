<?= $this->include('frontend/layouts/header') ?>

<?php

// print_r($cart_items);
// die;

?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door"
                            style="color: #EB4227;"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a href="/">Add to Cart</a></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content Section -->
<form action="<?= base_url('/CheckoutController/save_checkout') ?>" method="POST">
    <?= csrf_field() ?> <!-- CSRF protection -->
    <section class="cart-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <!-- Cart Items Section -->
                <div class="col-lg-8">
                    <div class="table-content table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <h1 class="page-title">Shopping Cart</h1>
                                    </th>
                                </tr>
                                <tr class="second-head">
                                    <th colspan="3">PRODUCT</th>
                                    <th>PRICE</th>
                                    <th>QUANTITY</th>
                                    <th>SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cart_items)) : ?>
                                    <?php foreach ($cart_items as $index => $cart_item) :
                                        $configuration = json_decode($cart_item['configuration'], true);

                                        $addons_total = 0;
                                        $addonHtml = '';

                                        if (!empty($configuration)) {
                                            foreach ($configuration as $key => $option) {
                                                $addons_total += floatval($option['added_price']) * $cart_item['quantity'];
                                                $addonHtml .= "<div>{$key}: {$option['option']}</div>";
                                            }
                                        }

                                        $base_price = floatval($cart_item['product_price']) - $addons_total;
                                        $grand_total = $cart_item['product_price'];
                                    ?>
                                        <tr data-product-id="<?= esc($cart_item['id']) ?>">
                                            <td class="product-remove">
                                                <center>
                                                    <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_id]" value="<?= esc($cart_item['id']) ?>">
                                                    <a href="#" class="remove-fromcart" data-product-id="<?= esc($cart_item['id']) ?>">
                                                        <i class="bi bi-x"></i>
                                                    </a>
                                                </center>
                                            </td>
                                            <td class="product-thumbnail">
                                                <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_image]" value="<?= base_url() . $cart_item['product_image'] ?>">
                                                <img src="<?= base_url() . $cart_item['product_image'] ?>" alt="Product" style="width: 50px;">
                                            </td>
                                            <td class="product-name">
                                                <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_name]" value="<?= esc($cart_item['product_name']) ?>">
                                                <?php if (!empty($configuration)) : ?>
                                                    <?php foreach ($configuration as $key => $option) : ?>
                                                        <input type="hidden" name="addonSet[<?= esc($cart_item['id']) ?>][]" value="<?= esc($key) ?>">
                                                        <input type="hidden" name="addonName[<?= esc($cart_item['id']) ?>][]" value="<?= esc($option['option']) ?>">
                                                        <input type="hidden" name="addonprice[<?= esc($cart_item['id']) ?>][]" value="<?= esc($option['added_price']) ?>">
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <strong><?= esc($cart_item['product_name']. $cart_item['product_id']) ?></strong>
                                                <?= $addonHtml ? "<div style='font-size: 13px; margin-top: 5px; color: #555;'>$addonHtml</div>" : "" ?>
                                                <div style="font-size: 13px; margin-top: 5px; color: #555;">
                                                    <div>Base Total: $<?= number_format($base_price, 2) ?></div>
                                                    <div>Add-ons Total: $<?= number_format($addons_total, 2) ?></div>
                                                </div>
                                            </td>
                                            <td class="product-price">
                                                <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_unit_price]" value="<?= esc($cart_item['product_unit_price']) ?>">
                                                $<?= isset($cart_item['product_unit_price']) ? number_format($cart_item['product_unit_price'], 2) : 0 ?>
                                            </td>
                                            <td class="product-quantity">
                                                <div class="cart-plus-minus">
                                                    <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_qty]" value="<?= esc($cart_item['quantity']) ?>">
                                                    <input type="text" value="<?= esc($cart_item['quantity']) ?>" readonly>
                                                    <div class="dec qtybutton" data-action="decrease" data-product-id="<?= esc($cart_item['id']) ?>">-</div>
                                                    <div class="inc qtybutton" data-action="increase" data-product-id="<?= esc($cart_item['id']) ?>">+</div>
                                                </div>
                                            </td>
                                            <td class="product-subtotal">
                                                <input type="hidden" name="products[<?= esc($cart_item['id']) ?>][prd_total]" value="<?= number_format($grand_total, 2) ?>">
                                                $<?= number_format($grand_total, 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No items in your cart.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row m-0">
                        <div class="col-lg-12 cart-button">
                            <div class="coupon-all">
                                <div class="coupon">
                                    <a href="<?= base_url('products') ?>" class="btn-orange-outline"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                                </div>
                                <div class="coupon2">
                                    <button class="btn-orange-outline" name="update_cart" id="update-cart-btn">Update cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Totals Section -->
                <div class="col-lg-4">
                    <div class="border">
                        <div class="heading_s1 mb-3">
                            <h2 class="page-title">Cart Totals</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="shipping_checkout">
                                    <tr>
                                        <input type="hidden" id="total_prd_amt" name="total_prd_amt" value="<?= $cart_total_excl_gst ?>">
                                        <td class="cart_total_label">Cart Total Exc. GST</td>
                                        <td class="cart_total_amount chk-sub">$<?= $cart_total_excl_gst ?></td>
                                    </tr>

                                    <?php
                                    $firstShipping = true;
                                    foreach ($shipping_methods as $shipping): ?>
                                        <tr>
                                            <td class="cart_total_label">
                                                <div class="radio-info" style="display: flex; align-items: center; gap: 10px;">
                                                    <input type="radio"
                                                        id="flat-rate_<?= $shipping['shipping_id']; ?>"
                                                        class="shipping_method"
                                                        name="radio-flat-rate"
                                                        data-shippingname="<?= htmlspecialchars($shipping['shipping_name']); ?>"
                                                        data-shipid="<?= $shipping['shipping_id']; ?>"
                                                        data-price="<?= number_format($shipping['price_incl_gst'], 2); ?>"
                                                        data-shipgst="<?= number_format($shipping['shipping_gst'], 2); ?>"
                                                        data-excludeship="<?= number_format($shipping['shipping_charge'], 2); ?>"
                                                        <?= $firstShipping ? 'checked' : ''; ?>>
                                                    <label for="flat-rate_<?= $shipping['shipping_id']; ?>">
                                                        <img width="24px" height="24px" src="https://www.zylax.com.au/assets/front/images/icons/shipping-icon.png" alt="Shipping">
                                                        <?= htmlspecialchars($shipping['shipping_name']); ?>
                                                    </label>
                                                </div>
                                                <div class="cart_total_label" style="margin-top: 5px;">
                                                    <span>Excl. GST:</span><br>
                                                    <span>GST:</span><br>
                                                </div>
                                            </td>
                                            <td class="cart_total_amount" style="text-align: right;">
                                                <span>$<?= number_format($shipping['price_excl_gst'], 2); ?></span><br>
                                                <span>$<?= number_format($shipping['shipping_gst'], 2); ?></span><br>
                                            </td>
                                        </tr>
                                    <?php
                                        $firstShipping = false;
                                    endforeach; ?>

                                    <tr class="gst_including">
                                        <input type="hidden" name="total_product_gst" id="total_product_gst" value="<?= $product_gst_amount ?>">
                                        <input type="hidden" name="exculde_product_amount" value="<?= $price_incl_gst ?>">
                                        <td class="cart_total_label">GST Amount <?php echo isset($discountpercentage) ? '(' . $discountpercentage . ')' : '' ?>:<br> Price Inc. GST:</td>
                                        <td class="cart_total_amount">$<?= $product_gst_amount ?><br> $<?= $price_incl_gst ?></td>
                                    </tr>

                                    <tr>
                                        <input type="hidden" id="discount_price" name="discount_price" value="0">
                                        <input type="hidden" id="discount_type" name="discount_type" value="">
                                        <td class="cart_total_label">Discount</td>
                                        <td class="cart_total_amount discount">$00.00</td>
                                    </tr>

                                    <tr class="topborder">
                                        <input type="hidden" id="total_amt" name="total_amt" value="<?= $final_total ?>">
                                        <td><strong>Total</strong></td>
                                        <td class="cart_total_amount chk-ttl">$<?= $final_total ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="w-100 btn btn-lg btn-orange">Proceed To CheckOut <i class="bi bi-arrow-right"></i></button>
                    </div>

                    <br>
                    <div class="card coupon_card">
                        <h5 class="card-header page-title">Coupon Code</h5>
                        <div class="card-body">
                            <input type="text" class="form-control" id="coupon_id" name="coupon" placeholder="Enter coupon code">
                            <button class="btn-orange-outline orange-fill" onclick="avail_couponcode()" type="button">Apply Coupon</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden Shipping Fields -->
    <input type="hidden" name="item_shipid" id="item_shipid">
    <input type="hidden" name="item_shipprice" id="item_shipprice">
    <input type="hidden" name="ship_gst" id="ship_gst">
    <input type="hidden" name="exclude_ship_amount" id="exclude_ship_amount">
    <input type="hidden" name="shipping_name" id="shipping_name">
</form>


<?= $this->include('frontend/layouts/footer') ?>

<script>
    // $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
    // $(".qtybutton").on("click", function() {
    //     var $button = $(this);
    //     var oldValue = $button.parent().find("input").val();
    //     if ($button.text() == "+") {
    //         var newVal = parseFloat(oldValue) + 1;
    //     } else {
    //         // Don't allow decrementing below zero
    //         if (oldValue > 0) {
    //             var newVal = parseFloat(oldValue) - 1;
    //         } else {
    //             newVal = 0;
    //         }
    //     }
    //     $button.parent().find("input").val(newVal);
    // });
</script>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     updateCartDisplay();
    //     renderCartPage();
    // });

    // function renderCartPage() {
    //     const cart = JSON.parse(localStorage.getItem("cart")) || [];
    //     const cartTableBody = document.querySelector(".table tbody");
    //     cartTableBody.innerHTML = "";

    //     let subtotal = 0; // This is the subtotal before discounts
    //     let total = 0; // This will be the final total (including discounts, etc.)

    //     cart.forEach((item, index) => {
    //         let baseTotal = parseFloat(item.price) * item.quantity;
    //         let addonHtml = "";
    //         let addonsTotal = 0;

    //         // Loop through item configurations (add-ons)
    //         if (item.configuration && Array.isArray(item.configuration)) {
    //             addonHtml += `<ul style="padding-left: 15px; margin-top: 5px;">`;
    //             item.configuration.forEach(config => {
    //                 const addonPrice = parseFloat(config.added_price || 0);
    //                 if (!addonPrice == 0) {
    //                     addonsTotal += addonPrice * item.quantity; // Add add-on price for the quantity
    //                     addonHtml +=
    //                         `<li><strong>${config.set_name}:</strong> ${config.option} <span>($${addonPrice.toFixed(2)})</span></li>`;
    //                 }
    //             });
    //             addonHtml += `</ul>`;
    //         }

    //         // Item total includes both base and add-ons
    //         const itemTotal = baseTotal //+ addonsTotal;
    //         subtotal += itemTotal; // Add item total to subtotal

    //         console.log(subtotal);
    //         console.log(itemTotal);

    //         // Update cart table
    //         cartTableBody.innerHTML += `
    //         <tr>
    //             <td class="product-remove">
    //                 <center><a href="#" onclick="removeItem(${index})"><i class="bi bi-x"></i></a></center>
    //             </td>
    //             <td class="product-thumbnail"><img src="${item.image}" alt="" style="width: 50px;"></td>
    //             <td class="product-name">
    //                 <strong>${item.name}</strong>
    //                 ${addonHtml}
    //                 <div style="font-size: 13px; margin-top: 5px; color: #555;">
    //                     <div>Base Total: $${baseTotal.toFixed(2) - addonsTotal.toFixed(2)}</div>
    //                     <div>Add-ons Total: $${addonsTotal.toFixed(2)}</div>
    //                 </div>
    //             </td>
    //             <td class="product-price">$${parseFloat(item.price).toFixed(2)}</td>
    //             <td class="product-quantity">
    //                 <div class="cart-plus-minus">
    //                     <input type="text" value="${item.quantity}" readonly>
    //                     <div class="dec qtybutton" onclick="updateQuantity(${index}, -1)">-</div>
    //                     <div class="inc qtybutton" onclick="updateQuantity(${index}, 1)">+</div>
    //                 </div>
    //             </td>
    //             <td class="product-subtotal">$${itemTotal.toFixed(2)}</td>
    //         </tr>`;
    //     });

    //     // Calculate the total (including any possible discounts)
    //     let discount = parseFloat($("#discount_price").val()) || 0;
    //     total = subtotal - discount;

    //     // Update Cart Totals
    //     $(".chk-sub").html(`$${subtotal.toFixed(2)}`); // Cart Total Exc. GST
    //     $(".chk-ttl").html(`$${total.toFixed(2)}`); // Total (after discount)
    // }

    // Function to update quantity
    // function updateQuantity(index, change) {
    //     var cart = JSON.parse(localStorage.getItem("cart")) || [];
    //     if (cart[index]) {
    //         cart[index].quantity += change;
    //         if (cart[index].quantity <= 0) {
    //             cart.splice(index, 1); // Remove item if quantity is zero
    //         }
    //         localStorage.setItem("cart", JSON.stringify(cart));
    //         setTimeout(() => {
    //             location.reload();
    //         }, 1000); // 1000 milliseconds = 1 second// Update cart dropdown as well
    //     }
    // }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attach shipping method change events
        document.querySelectorAll('input[name="radio-flat-rate"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                updateShippingDetails(this); // Update hidden fields
                updateFinalTotal(); // Update total amount
            });
        });

        // Run on page load
        let selectedShipping = document.querySelector('input[name="radio-flat-rate"]:checked');
        if (selectedShipping) {
            updateShippingDetails(selectedShipping); // Set hidden fields on page load
        }
        updateFinalTotal();
    });

    // Function to update hidden inputs based on selected shipping
    function updateShippingDetails(selectedShipping) {
        if (selectedShipping) {
            document.getElementById('item_shipid').value = selectedShipping.dataset.shipid || '';
            document.getElementById('item_shipprice').value = selectedShipping.dataset.price || '';
            document.getElementById('ship_gst').value = selectedShipping.dataset.shipgst || '';
            document.getElementById('exclude_ship_amount').value = selectedShipping.dataset.excludeship || '';
            document.getElementById('shipping_name').value = selectedShipping.dataset.shippingname || '';
        }
    }

    // Function to update final total
    function updateFinalTotal() {
        // Get base product amount (excluding GST)
        let baseTotal = parseFloat((document.getElementById('total_prd_amt').value || "0").replace(/,/g, ''));

        // Get GST on product
        let productGst = parseFloat((document.getElementById('total_product_gst').value || "0").replace(/,/g, ''));

        // Get discount (default 0)
        let discountPrice = parseFloat((document.getElementById('discount_price').value || "0").replace(/,/g, ''));

        // Get selected shipping method's price (including GST)
        let selectedShipping = document.querySelector('input[name="radio-flat-rate"]:checked');
        let shippingPrice = selectedShipping ? parseFloat(selectedShipping.dataset.price || "0") : 0;

        // Calculate final total
        let newFinalTotal = (baseTotal + productGst + shippingPrice - discountPrice).toFixed(2);

        // Update total display
        const totalElement = document.querySelector('.chk-ttl');
        if (totalElement) {
            totalElement.innerText = "$" + newFinalTotal;
        }
        const totalInput = document.getElementById('total_amt');
        if (totalInput) {
            totalInput.value = newFinalTotal;
        }

        // Update discount display
        const discountDisplay = document.querySelector('.discount');
        if (discountDisplay) {
            discountDisplay.innerText = discountPrice > 0 ? "$" + discountPrice.toFixed(2) : "$00.00";
        }
    }



    function avail_couponcode() {
        let coupon_id = $("#coupon_id").val().trim();
        let total = $(".chk-ttl").text().replace("$", "").trim();
        total = parseFloat(total);

        if (coupon_id == '') {
            Swal.fire({
                title: "Error",
                text: "Please enter a coupon code.",
                icon: "error",
                toast: true,
                position: "top-end",
                showConfirmButton: true,
                timer: 3000,
            });
            return;
        }

        $.ajax({
            url: '/CheckoutController/validate_coupon', // Your backend validation URL
            type: "POST",
            data: {
                coupon_id: coupon_id,
                total: total
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    // Update Discount and New Total
                    document.getElementById('discount_price').value = parseFloat(response.coupon.discount_amount).toFixed(2);
                    document.getElementById('discount_type').value = response.coupon.discount_type;

                    Swal.fire({
                        title: "Success",
                        text: "Coupon applied successfully!",
                        icon: "success",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    // Update Final Total
                    updateFinalTotal();

                    $(".coupon_card").hide();

                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: true,
                        timer: 3000,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", error);
                alert("Something went wrong. Please try again.");
            }
        });
    }

    $(".qtybutton").on("click", function() {
        var $button = $(this);
        var $row = $button.closest("tr");

        var $qtyInput = $row.find("input[type='text']");
        var $hiddenQty = $row.find("input[name^='products'][name$='[prd_qty]']");

        var oldQty = parseInt($qtyInput.val()) || 1;
        var newQty = $button.hasClass("inc") ? oldQty + 1 : Math.max(oldQty - 1, 1);

        $qtyInput.val(newQty);
        $hiddenQty.val(newQty);

        // Get base price and add-ons
        var baseTotal = 0;
        var addonTotal = 0;

        // Loop over all addon prices for this product
        $row.find("input[name^='addonprice']").each(function() {
            var addonPrice = parseFloat($(this).val()) || 0;
            addonTotal += addonPrice;
        });

        // Get total product price from hidden field or calculate manually
        var unitBasePrice = parseFloat($row.find(".product-price").text().replace('$', '').replace(',', '')) || 0;

        // Update subtotal: (base + addons) * qty
        
        var totalSubtotal = ((unitBasePrice + addonTotal) * newQty).toFixed(2);

        // Update the hidden prd_total and visible text
        var $subtotalCell = $row.find(".product-subtotal");
        var $subtotalInput = $subtotalCell.find("input[name^='products'][name$='[prd_total]']");
        var subtotalName = $subtotalInput.attr("name");

        $subtotalCell.html(`<input type="hidden" name="${subtotalName}" value="${totalSubtotal}">$${totalSubtotal}`);

        console.log('Product ID:', $row.data("product-id"));
        console.log('Unit Price (Base):', unitBasePrice);
        console.log('Add-ons Total:', addonTotal);
        console.log('Quantity:', newQty);
        console.log('New Subtotal:', totalSubtotal);
    });


    // ✅ Cart update button logic
    $("#update-cart-btn").on("click", function(e) {
        e.preventDefault();

        var cartData = [];

        $("tbody tr[data-product-id]").each(function() {
            var $row = $(this);
            var productId = $row.data("product-id");
            var quantity = parseInt($row.find("input[name^='products'][name$='[prd_qty]']").val()) || 1;
            var subtotal = parseFloat($row.find("input[name^='products'][name$='[prd_total]']").val()) || 0;

            if (productId) {
                cartData.push({
                    product_id: productId,
                    quantity: quantity,
                    subtotal: subtotal
                });
            }
        });

        // console.log("Submitting cart data:", cartData);

        // Uncomment and configure this section to send to server
        $.ajax({
            url: '/zylax/CheckoutController/update_cart_button',
            method: 'POST',
            data: {
                cart: cartData
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response,'response');
                
                location.reload();
            },
            error: function(xhr) {
                alert("Failed to update cart!");
                console.error(xhr.responseText);
            }
        });
    });
</script>