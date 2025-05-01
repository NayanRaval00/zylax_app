<?php
// print_r($orders_details[0]['products']);
// $products = json_decode($orders_details[0]['products'], true);

// print_r($products);

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Orders</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card content-area p-4">
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
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($orders_details[0]['created_at'])); ?>"
                                            readonly>
                                        <label for="status-1">Status:</label>
                                        <select id="status-1" name="order_status" class="form-control"
                                            data-tracking-id="<?php echo $orders_details[0]['tracking_order_id']; ?>">
                                            <option value="progress"
                                                <?php echo ($orders_details[0]['order_status'] == 'progress' ? 'selected' : ''); ?>>
                                                In Progress</option>
                                            <option value="shipped"
                                                <?php echo ($orders_details[0]['order_status'] == 'shipped' ? 'selected' : ''); ?>>
                                                Shipped</option>
                                            <option value="completed"
                                                <?php echo ($orders_details[0]['order_status'] == 'completed' ? 'selected' : ''); ?>>
                                                Completed</option>
                                            <option value="cancel"
                                                <?php echo ($orders_details[0]['order_status'] == 'cancel' ? 'selected' : ''); ?>>
                                                Canceled</option>
                                        </select>
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
                                <p>Product Total Excl GST:
                                    $<?php echo number_format($orders_details[0]['product_amount'], 2); ?></p>
                                <p>GST: $<?php echo number_format($orders_details[0]['total_gst'], 2); ?></p>
                                <p>Shipping Charges inc GST:
                                    $<?php echo number_format($orders_details[0]['shipping_price'], 2); ?></p>
                                <p>Coupon Discount <?php echo $orders_details[0]['discount_type']  ?>:
                                    $<?php echo number_format($orders_details[0]['discount_price'], 2); ?></p>
                                <p><strong>Grand Total:
                                        $<?php echo number_format($orders_details[0]['tran_total_amt'], 2); ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- /.content -->
</div>

<script>
$(document).ready(function() {
    $("#status-1").change(function() {
        var trackingId = $(this).data("tracking-id");
        var newStatus = $(this).val();

        $.ajax({
            url: "<?= base_url('admin/adminorders/status_update') ?>",
            type: "POST",
            data: {
                tracking_order_id: trackingId,
                order_status: newStatus
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: xhr.responseJSON ? xhr.responseJSON.message :
                        "Something went wrong!",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Try Again"
                });
            }
        });
    });
});
</script>