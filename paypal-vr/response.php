<?php
include 'paypal_config.php';
$accessToken = getAccessToken();
$orderID = $_GET['token']; // PayPal returns order ID in "token"
$ch = curl_init(PAYPAL_BASE_URL . "/v2/checkout/orders/$orderID/capture");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken
]);
$response = curl_exec($ch);
curl_close($ch);
echo "<pre>";print_r($response);
$payment = json_decode($response, true);
echo "<pre>";print_r($payment);
if(isset($payment['details']) && isset($payment['name']) && $payment['name'] == 'UNPROCESSABLE_ENTITY')
{
    echo isset($payment['details'][0]['issue'])?$payment['details'][0]['issue']:'';
    echo "<br/>";
    echo isset($payment['details'][0]['description'])?$payment['details'][0]['description']:'';
}
else
{
    if ($payment['status'] == "COMPLETED") {
        echo "Payment successful!";
    } else {
        echo "Payment failed!";
    }
}
?>