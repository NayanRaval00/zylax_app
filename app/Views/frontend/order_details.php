<?= $this->include('frontend/layouts/header');

// print_r($orders_details);
// die;
?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg') ?>)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url();?>"><i class="bi bi-house-door"
                            style="color: #EB4227;"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">My Profile</li>
            </ol>
        </nav>
    </div>
</section>
<!-- Content Section -->
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-md-12 myaccount-area">
                <div class="row">
                    <?= view('frontend/partials/profile-nav'); ?>
                    <div class="col-md-9 order-history">
                        <div class="row">
                            <div class="col-12">
                                <h3>Order #<?php echo $orders_details[0]['tracking_order_id']; ?> details</h3>
                                <p>Payment via <?php echo $orders_details[0]['payment_source']; ?>. Customer IP:
                                    <?php echo $orders_details[0]['ip']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <!-- General Section -->
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <h4>General</h4>
                                        <label for="datetime-created-1">Order Date:</label>
                                        <input type="datetime-local" id="datetime-created-1" class="form-control"
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($orders_details[0]['created_at'])); ?>" readonly>

                                        <label for="status-1">Status:</label>
                                        <h4><?php echo $orders_details[0]['order_status'] ?></h4>
                                        <div><?php echo isset($orders_details[0]['company']) ? 'Company Name: ' . $orders_details[0]['company'] : ''; ?></div>  
                                    </div>

                                    <!-- Billing Section -->
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <h4>Billing</h4>
                                        <p>
                                            <?php echo $orders_details[0]['billing_name'] . ' ' . $orders_details[0]['billing_last_name']; ?><br>
                                            <?php echo $orders_details[0]['billing_addr_1']; ?><br>
                                            <?php echo $orders_details[0]['billing_addr_2']; ?><br>
                                            <?php echo $orders_details[0]['billing_city'] . ' ' . $orders_details[0]['billing_pincode']; ?><br>
                                            <?php echo $orders_details[0]['billing_state']; ?>
                                        </p>
                                        <p>Email: <a href="mailto:<?php echo $orders_details[0]['billing_email']; ?>">
                                                <?php echo $orders_details[0]['billing_email']; ?></a></p>
                                        <p>Phone: <a
                                                href="tel:<?php echo $orders_details[0]['billing_phone_number']; ?>">
                                                <?php echo $orders_details[0]['billing_phone_number']; ?></a></p>
                                    </div>

                                    <!-- Shipping Section -->
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <h4>Shipping</h4>
                                        <p>
                                            <?php echo !empty($orders_details[0]['shipping_name']) ? $orders_details[0]['shipping_name'] . ' ' . $orders_details[0]['shipping_last_name'] : $orders_details[0]['billing_name'] . ' ' . $orders_details[0]['billing_last_name']; ?><br>
                                            <?php echo !empty($orders_details[0]['shipping_addr_1']) ? $orders_details[0]['shipping_addr_1'] : $orders_details[0]['billing_addr_1']; ?><br>
                                            <?php echo !empty($orders_details[0]['shipping_addr_2']) ? $orders_details[0]['shipping_addr_2'] : $orders_details[0]['billing_addr_2']; ?><br>
                                            <?php echo !empty($orders_details[0]['shipping_city']) ? $orders_details[0]['shipping_city'] : $orders_details[0]['billing_city']; ?>
                                            <?php echo !empty($orders_details[0]['shipping_pincode']) ? $orders_details[0]['shipping_pincode'] : $orders_details[0]['billing_pincode']; ?><br>
                                            <?php echo !empty($orders_details[0]['shipping_state']) ? $orders_details[0]['shipping_state'] : $orders_details[0]['billing_state']; ?>
                                        </p>
                                        <p>Email: <a
                                                href="mailto:<?php echo !empty($orders_details[0]['shipping_email']) ? $orders_details[0]['shipping_email'] : $orders_details[0]['billing_email']; ?>">
                                                <?php echo !empty($orders_details[0]['shipping_email']) ? $orders_details[0]['shipping_email'] : $orders_details[0]['billing_email']; ?></a>
                                        </p>
                                        <p>Phone: <a
                                                href="tel:<?php echo !empty($orders_details[0]['shipping_phone_number']) ? $orders_details[0]['shipping_phone_number'] : $orders_details[0]['billing_phone_number']; ?>">
                                                <?php echo !empty($orders_details[0]['shipping_phone_number']) ? $orders_details[0]['shipping_phone_number'] : $orders_details[0]['billing_phone_number']; ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Section in Separate Row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>Products</h4>
                            </div>
                        </div>

                        <?php
if (!empty($orders_details) && isset($orders_details[0]['products'])) {
    $products = json_decode($orders_details[0]['products'], true); // ✅ NO square brackets here

    if (is_array($products) && count($products) > 0) {
        foreach ($products as $product) {
?>
            <div class="row mb-3 border p-2">
            <div class="col-md-3 col-sm-6 col-12">
                                <p>
                                    <a href="<?php echo isset($product['product_image']) ? htmlspecialchars($product['product_image']) : '#'; ?>"
                                        target="_blank">
                                        <img src="<?php echo isset($product['product_image']) ? htmlspecialchars($product['product_image']) : ''; ?>"
                                            alt="Product Image" width="50" height="50">
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <p>SKU: <?php echo htmlspecialchars($product['sku_id']); ?></p>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <p><?php echo htmlspecialchars($product['product_name']); ?>
                                    (x<?php echo htmlspecialchars($product['quantity']); ?>)</p>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <p>GST: $<?php echo number_format($product['product_gst'], 2); ?></p>
                            </div>
                            <div class="col-md-2 col-sm-6 col-12">
                                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                            </div>

                <?php
                // ✅ Display Addons (already an array)
                if (!empty($product['addon_products']) && is_array($product['addon_products'])) {
                    echo '<div class="col-12 mt-2">';
                    echo '<strong>Addons:</strong><ul class="mb-0">';
                    foreach ($product['addon_products'] as $addon) {
                        echo '<li>' . htmlspecialchars($addon['addon_set']) . ' - ' . htmlspecialchars($addon['addon_name']) . ' ($' . number_format($addon['addon_price'], 2) . ')</li>';
                    }
                    echo '</ul></div>';
                }
                ?>
            </div>
<?php
        }
    } else {
        echo "<div class='row'><div class='col-12'><p>No products found.</p></div></div>";
    }
} else {
    echo "<div class='row'><div class='col-12'><p>No order details found.</p></div></div>";
}
?>


                        <!-- Total Breakdown in Separate Row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>Total Breakdown</h4>
                                <p>Product Total Excl GST: $<?php echo number_format($orders_details[0]['product_amount'], 2); ?></p>
                                <p>GST: $<?php echo number_format($orders_details[0]['total_gst'], 2); ?></p>
                                <p>Shipping Charges inc GST: $<?php echo number_format($orders_details[0]['shipping_price'], 2); ?></p>
                                <p>Coupon Discount <?php echo $orders_details[0]['discount_type']  ?>: $<?php echo number_format($orders_details[0]['discount_price'], 2); ?></p>
                                <p><strong>Grand Total: $<?php echo number_format($orders_details[0]['tran_total_amt'], 2); ?></strong>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->include('frontend/layouts/footer') ?>