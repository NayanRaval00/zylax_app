<?php
include 'paypal_config.php';

$accessToken = getAccessToken();
$orderId = 'ZYLAX_'.time();
// Create the order payload with cart items
$orderData = [
    "intent" => "CAPTURE",
    "purchase_units" => [
        [
            "reference_id" => "zylax_computer_order_".$orderId,
            "description" => "Zylax Online Store order-".$orderId,
            "amount"=> [
                "currency_code"=> "USD",
                "value"=> "115.00",
                "breakdown"=> [
                    "item_total"=> [
                        "currency_code"=> "USD",
                        "value"=> "115.00"
                    ]
                ]
            ],
            "items" => [
                [
                    "name" => "Product 1",
                    "description" => "Description for product 1",
                    "quantity" => "1",
                    "unit_amount" => [
                        "value" => "15.00",
                        "currency_code" => "USD"
                    ]
                ],
                [
                    "name" => "Product 2",
                    "description" => "Description for product 2",
                    "quantity" => "2",
                    "unit_amount" => [
                        "value" => "50.00",
                        "currency_code" => "USD"
                    ]
                ]
            ],
            "shipping_address" => [
                "line1" => "2211 N First Street",
                "line2" => "Building 17",
                "city" => "San Jose",
                "country_code" => "US",
                "postal_code" => "95131",
                "state" => "CA",
                "phone" => "(123) 456-7890",
            ],
        ]
    ],
    "application_context" => [
        "return_url" => SITE_URL."response.php?doaction=success",
        "cancel_url" => SITE_URL."response.php?doaction=cancel",
    ]
];

$ch = curl_init(PAYPAL_BASE_URL . "/v2/checkout/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken
]);

$response = curl_exec($ch);
curl_close($ch);
echo "<pre>";print_r($response);
$order = json_decode($response, true);
$approvalUrl = $order['links'][1]['href']; // Get PayPal payment approval URL
// exit($approvalUrl);
header("Location: " . $approvalUrl); // Redirect user to PayPal
?>
