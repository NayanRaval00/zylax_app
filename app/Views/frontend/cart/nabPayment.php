<?php 
$ordreId = $order_id;

if($is_test == 0){
    $marchant = $merchant['live'];
    $varUrl = "https://transact.nab.com.au/live/hpp/payment";
}elseif ($is_test == 1) {
    $marchant = $merchant['test'];
    $varUrl = "https://demo.transact.nab.com.au/live/hpp/payment";
}

?>
<h3>Please wait while we redirecting on NAB ......</h3>
<form id="fromId1" action="<?php echo $varUrl?>" method="post">
    <input type="hidden" name="vendor_name" value="<?php echo (!empty($marchant)) ? $marchant : ' '; ?>">
    <input type="hidden" name="payment_alert" value="sales@zylax.com.au">
    <input type="hidden" name="print_zero_qty" value="FALSE">

    <input type="hidden" name="Name" value="<?php echo $name . ' ' . $last_name; ?>">
    <input type="hidden" name="information_fields" value="Name">

    <input type="hidden" name="Address" value="<?php echo $address_1 . ' ' . $address_2; ?>">
    <input type="hidden" name="information_fields" value="Address">

    <input type="hidden" name="State" value="<?php echo $state; ?>">
    <input type="hidden" name="information_fields" value="State">

    <input type="hidden" name="City" value="<?php echo $city; ?>">
    <input type="hidden" name="information_fields" value="City">

    <input type="hidden" name="Post code" value="<?php echo $pincode; ?>">
    <input type="hidden" name="information_fields" value="Post code">

    <input type="hidden" name="E-mail" value="<?php echo $email; ?>">
    <input type="hidden" name="information_fields" value="E-mail">

    <input type="hidden" name="Telephone" value="<?php echo $phone_number; ?>">
    <input type="hidden" name="information_fields" value="Telephone">

    <input type="hidden" name="return_link_url" value="<?php echo base_url('success') ?>">
    <input type="hidden" name="reply_link_url" value="<?php echo base_url('processNab') . '?orderId=' . $ordreId; ?>">

    <?php

    // print_r($item_names);
    // print_r($item_quantities);
    // print_r($item_prices);

    // die;


    foreach ($item_names as $index => $item_name) {
        $item_price = $item_prices[$index];
        $item_qty = $item_quantities[$index];
        ?>
        <input type="hidden" name="<?php echo $item_name; ?>" value="<?php echo ($item_qty > 0) ? "$item_qty,$item_price" : $item_price; ?>">
    <?php } ?>

    <input type="hidden" name="Shipping Charge" value="<?php echo $shipping_charge ?>">
    <input type="hidden" name="gst_exempt_fields" value="">
    <input type="hidden" name="payment_reference" value="<?php echo $ordreId ?>">
    <input type="hidden" name="receipt_address" value="<?php echo $email; ?>">
    <input type="hidden" name="payment_alert" value="sales@zylax.com.au">

    <div class="" style="display: none">
        <button type="submit" id="fromId12" name="submit" class="btn-upper btn btn-primary checkout-page-button hidecls">Button</button>
    </div>
</form>

<script src="<?= base_url("assets/frontend/js/jquery.min.js");?>"></script>

<script>
    setTimeout(function () {
       $('#fromId12').click();
    }, 1000);
</script>
