<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door" style="color: #EB4227;"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a href="/">Add to Cart</a></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content Section -->
<section class="cart-area pt-100 pb-100">
    <div class="container">
        <div class="row">
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
                        </tbody>
                    </table>
                </div>
                <div class="row m-0">
                    <div class="col-lg-12 cart-button">
                        <div class="coupon-all">
                            <div class="coupon">
                                <a href="<?= base_url('products') ?>" class="btn-orange-outline"
                                    name="Continue Shopping" type="submit"><i class="bi bi-arrow-left"></i> Continue
                                    Shopping</a>
                            </div>
                            <div class="coupon2">
                                <button class="btn-orange-outline" name="update_cart" type="submit">Update cart</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="border">
                            <div class="heading_s1 mb-3">
                                <h2 class="page-title">Cart Totals</h2>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="shipping_checkout">
                                        <tr>
                                            <input type="hidden" id="total_prd_amt" name="total_prd_amt">
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
                            <a href="<?= base_url('checkout') ?>" class="w-100 btn btn-lg btn-orange">Proceed To
                                CheckOut <i class="bi bi-arrow-right"></i></a>
                        </div>
                        <br>
                        <div class="card coupon_card">
                            <h5 class="card-header page-title">Coupon Code</h5>
                            <div class="card-body">
                                <input type="text" class="form-control" id="coupon_id" name="coupon" placeholder="Enter coupon code">
                                <button class="btn-orange-outline orange-fill" onclick="avail_couponcode()" type="button">Apply
                                    Coupon</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    document.addEventListener("DOMContentLoaded", function() {
        updateCartDisplay();
        renderCartPage();
    });

    function renderCartPage() {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        const cartTableBody = document.querySelector(".table tbody");
        cartTableBody.innerHTML = "";

        let subtotal = 0; // This is the subtotal before discounts
        let total = 0; // This will be the final total (including discounts, etc.)

        cart.forEach((item, index) => {
            let baseTotal = parseFloat(item.price) * item.quantity;
            let addonHtml = "";
            let addonsTotal = 0;

            // Loop through item configurations (add-ons)
            if (item.configuration && Array.isArray(item.configuration)) {
                addonHtml += `<ul style="padding-left: 15px; margin-top: 5px;">`;
                item.configuration.forEach(config => {
                    const addonPrice = parseFloat(config.added_price || 0);
                    addonsTotal += addonPrice * item.quantity; // Add add-on price for the quantity
                    addonHtml += `<li><strong>${config.set_name}:</strong> ${config.option} <span>(+$${addonPrice.toFixed(2)})</span></li>`;
                });
                addonHtml += `</ul>`;
            }

            // Item total includes both base and add-ons
            const itemTotal = baseTotal + addonsTotal;
            subtotal += itemTotal; // Add item total to subtotal

            // Update cart table
            cartTableBody.innerHTML += `
        <tr>
            <td class="product-remove">
                <center><a href="#" onclick="removeItem(${index})"><i class="bi bi-x"></i></a></center>
            </td>
            <td class="product-thumbnail"><img src="${item.image}" alt="" style="width: 50px;"></td>
            <td class="product-name">
                <strong>${item.name}</strong>
                ${addonHtml}
                <div style="font-size: 13px; margin-top: 5px; color: #555;">
                    <div>Base Total: $${baseTotal.toFixed(2)}</div>
                    <div>Add-ons Total: $${addonsTotal.toFixed(2)}</div>
                    <div><strong>Item Subtotal: $${itemTotal.toFixed(2)}</strong></div>
                </div>
            </td>
            <td class="product-price">$${parseFloat(item.price).toFixed(2)}</td>
            <td class="product-quantity">
                <div class="cart-plus-minus">
                    <input type="text" value="${item.quantity}" readonly>
                    <div class="dec qtybutton" onclick="updateQuantity(${index}, -1)">-</div>
                    <div class="inc qtybutton" onclick="updateQuantity(${index}, 1)">+</div>
                </div>
            </td>
            <td class="product-subtotal">$${itemTotal.toFixed(2)}</td>
        </tr>`;
        });

        // Calculate the total (including any possible discounts)
        let discount = parseFloat($("#discount_price").val()) || 0;
        total = subtotal - discount;

        // Update Cart Totals
        $(".chk-sub").html(`$${subtotal.toFixed(2)}`); // Cart Total Exc. GST
        $(".chk-ttl").html(`$${total.toFixed(2)}`); // Total (after discount)
    }





    // Function to update quantity
    function updateQuantity(index, change) {
        var cart = JSON.parse(localStorage.getItem("cart")) || [];
        if (cart[index]) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1); // Remove item if quantity is zero
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            renderCartPage();
            updateCartDisplay(); // Update cart dropdown as well
        }
    }
</script>