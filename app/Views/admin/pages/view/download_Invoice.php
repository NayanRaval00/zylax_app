<html lang="en">
<head>
    <title>Invoice</title>
    <style>
        ul[style] { list-style-type: none !important; }
        body { margin: 10px; padding: 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table, th, td { border: none !important; }
        th, td { border: 1px solid #ddd; text-align: left; word-wrap: break-word; }
        @page { margin: 0; padding: 0; size: A4; }
    </style>
</head>

<body>
    <?php $order = $orders_details[0]; ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
        <tr>
            <td valign="top" width="100%">
                <table width="870" style="max-width: 870px; width:100% !important;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="50%" style="padding: 5px 10px;">
                                        <img src="<?php echo base_url(); ?>/assets/frontend/images/zylax-logo.png" height="50" width="100">
                                        <address>
                                            <p>Zylax Computers<br>
                                                13/4A Foundry Rd, Seven Hills NSW 2147,<br>
                                                Phone: 1300 099529<br>
                                                ABN: 50 095 556 586<br>
                                                Contact: sales@zylax.com.au</p>
                                        </address>
                                    </td>
                                    <td width="50%" style="padding: 5px 10px; text-align: right;">
                                        <h1>INVOICE</h1>
                                        Invoice <strong>#<?php echo $order['tracking_order_id']; ?></strong><br>
                                        Date: <strong><?php echo date('M d, Y', strtotime($order['created_at'])); ?></strong><br>
                                        Amount: <strong>$<?php echo number_format($order['total_price'], 2); ?></strong><br>
                                        Payment Method: <strong><?php echo $order['payment_source']; ?></strong>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Billing Address</th>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Shipping Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 5px 10px; vertical-align: top;">
                                            <address>
                                                <p><?php echo $order['billing_name'] . ' ' . $order['billing_last_name']; ?><br>
                                                    <?php echo $order['billing_addr_1'] . ', ' . $order['billing_city'] . ', ' . $order['billing_state'] . ' ' . $order['billing_pincode']; ?><br>
                                                    <?php echo $order['billing_email']; ?><br>
                                                    <?php echo $order['billing_phone_number']; ?></p>
                                            </address>
                                        </td>
                                        <td style="padding: 5px 10px; vertical-align: top;">
                                            <address>
                                                <p><?php echo $order['shipping_name'] . ' ' . $order['shipping_last_name']; ?><br>
                                                    <?php echo $order['shipping_addr_1'] . ', ' . $order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_pincode']; ?><br>
                                                    <?php echo $order['shipping_email']; ?><br>
                                                    <?php echo $order['shipping_phone_number']; ?></p>
                                            </address>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Items</th>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Qty</th>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Price</th>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (json_decode("[" . $order['products'] . "]", true) as $product): ?>
                                        <tr>
                                            <td style="padding: 5px 10px;"> <?php echo $product['product_name']; ?> </td>
                                            <td style="padding: 5px 10px; text-align: center;"> <?php echo $product['quantity']; ?> </td>
                                            <td style="padding: 5px 10px; text-align: center;"> $<?php echo number_format($product['price'], 2); ?> </td>
                                            <td style="padding: 5px 10px; text-align: right;"> $<?php echo number_format($product['price'] * $product['quantity'], 2); ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Subtotal</strong></td>
                                        <td colspan="2" align="right">$<?php echo number_format($order['product_amount'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Shpping</strong></td>
                                        <td colspan="2" align="right">$<?php echo number_format($order['shipping_price'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><strong>GST</strong></td>
                                        <td colspan="2" align="right">$<?php echo number_format($order['total_gst'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Coupon Discount <?php echo $order['discount_type'] ?></strong></td>
                                        <td colspan="2" align="right">$<?php echo number_format($order['discount_price'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><strong>Grand Total</strong></td>
                                        <td colspan="2" align="right">$<?php echo number_format($order['tran_total_amt'], 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th bgcolor="#0093cf" style="color:#fff; padding:10px;">Notes:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 5px 10px;">
                                            <p><strong>Please always include your invoice number when making any payment!</strong></p>
                                            <p>For questions regarding this invoice, contact sales@zylax.com.au</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
