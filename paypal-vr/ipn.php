<?php
include 'paypal_config.php';
// PayPal business email
// $your_paypal_email = "zylax-merchant@paypal.com";

<?php
include 'paypal_config.php';

// Read the JSON payload
$raw_post_data = file_get_contents("php://input");
$event = json_decode($raw_post_data, true);

// Log Webhook Event
file_put_contents("webhook_log.txt", print_r($event, true), FILE_APPEND);
echo "<pre>";print_r($event); exit;
// Check Event Type
if ($event['event_type'] === "PAYMENT.CAPTURE.COMPLETED") {
    $txn_id = $event['resource']['id'];
    $amount = $event['resource']['amount']['value'];
    $currency = $event['resource']['amount']['currency_code'];

    // Connect to Database
    $conn = new mysqli("localhost", "user", "password", "database");

    // Prevent Duplicate Transactions
    $stmt = $conn->prepare("SELECT COUNT(*) FROM payments WHERE txn_id = ?");
    $stmt->bind_param("s", $txn_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        // Store Payment in Database
        $stmt = $conn->prepare("INSERT INTO payments (txn_id, amount, currency, payment_status) VALUES (?, ?, ?, 'Completed')");
        $stmt->bind_param("sds", $txn_id, $amount, $currency);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}
?>
