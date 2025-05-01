<?= $this->include('frontend/layouts/header') ?>
<section class="track-order py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Track Order</h1>
                <p>To track your order please enter your order ID in the input field below and press the “Track Order”
                    button. this was given to you on your receipt and in the confirmation email you should have
                    received.</p>
            </div>
            <div class="col-md-12">

                <form class="row" action="<?php echo base_url('track-order') ?>" method="GET">
                    <!-- Order ID Field -->
                    <div class="mb-3 col-md-6">
                        <label for="orderID" class="form-label">
                            Order ID
                            <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                                title="Order ID that we sent to your email address."></i>
                        </label>
                        <input type="text" class="form-control" name="orderID" id="orderID" placeholder="ID">
                    </div>
                    <div class="mb-3 col-md-12">
                        <!-- Submit Button -->
                        <p><i class="bi bi-info-circle"></i> Order ID that we sended to your in your email address.</p>
                        <button type="submit" class="btn btn-primary">Track Order <i
                                class="bi bi-arrow-right-short"></i></button>
                    </div>
                </form>
            </div>



            <!-- <a href="index.html" class="btn btn-primary ">Track Order <i class="bi bi-arrow-right-short"></i></a> -->

        </div>
    </div>
</section>
<?php if(!empty($order)){ ?>
<section class="order-status pb-5">
    <div class="container ">
        <div class="row">
            <div class="col-md-12 pb-5">
                <h2 class="text-bold">Order Details</h2>
                <ul class="timeline">
                    <?php 
                        // Extract just the tracking_status column
                        $statuses = array_column($order, 'tracking_status');

                        // Define status precedence
                        $status_priority = ['order_accepted', 'progress', 'shipped', 'completed'];

                        // Define which actual tracking_status maps to which stage
                        $status_map = [
                            'order_accepted' => ['paid', 'missing', 'pending', 'unpaid'],
                            'progress' => ['progress'],
                            'shipped' => ['shipped'],
                            'completed' => ['completed']
                        ];

                        // Determine the highest stage reached
                        $active_stage_index = -1;

                        foreach ($status_priority as $index => $stage) {
                            if (array_intersect($status_map[$stage], $statuses)) {
                                $active_stage_index = $index;
                            }
                        }
                    ?>

                    <!-- Order Accepted -->
                    <li class="timeline-item" data-active="<?= $active_stage_index >= 0 ? 'true' : 'false'; ?>">
                        <div class="timeline-content">Order Accepted</div>
                    </li>

                    <!-- In Progress -->
                    <li class="timeline-item" data-active="<?= $active_stage_index >= 1 ? 'true' : 'false'; ?>">
                        <div class="timeline-content">In Progress</div>
                    </li>

                    <!-- Shipped -->
                    <li class="timeline-item" data-active="<?= $active_stage_index >= 2 ? 'true' : 'false'; ?>">
                        <div class="timeline-content">Shipped</div>
                    </li>

                    <!-- Order Delivered -->
                    <li class="timeline-item" data-active="<?= $active_stage_index >= 3 ? 'true' : 'false'; ?>">
                        <div class="timeline-content">Order Delivered</div>
                    </li>
                </ul>




            </div>
            <div class="colo-md-12 pb-4">
                <table class="table table-borderless">
                    <thead class="table-primary">
                        <tr>
                            <th colspan="3" style="width: 69%;">Order ID: #<?php echo $order[0]['tracking_order_id']; ?>
                            </th>
                            <th colspan="3" class="text-end">
                                <?php echo date('Y-m-d H:i', strtotime($order[0]['order_date'])); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-secondary">
                            <th>Product</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                        <?php
                            if (!empty($products) && isset($products)) {
                                foreach ($products as $product) { ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="Product 1"
                                    width="50">
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                        </tr>
                        <?php }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-warning">
                            <th colspan="4" class="text-end">Incl. GST:</th>
                            <th>$<?php echo number_format($order[0]['product_amount'], 2); ?></th>
                        </tr>
                        <tr class="table-warning">
                            <th colspan="4" class="text-end">Shipping:</th>
                            <th>$<?php echo number_format($order[0]['shipping_price'], 2); ?></th>
                        </tr>
                        <tr class="table-warning">
                            <th colspan="4" class="text-end">Total Order Amount:</th>
                            <th>$<?php echo number_format($order[0]['tran_total_amt'], 2); ?></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</section>
<?php } ?>



<?= $this->include('frontend/layouts/footer') ?>