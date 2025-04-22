<?php

namespace App\Services;

use Config\paypal;
use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

class PayPalService
{
    private $config;
    private $client;

    public function __construct()
    {
        $this->config = new paypal();
        $this->client = Services::curlrequest();
    }

    // Get PayPal Access Token
    public function getAccessToken()
    {
        $response = $this->client->request('POST', $this->config->baseUrl . "/v1/oauth2/token", [
            'auth' => [$this->config->clientId, $this->config->secret],
            'form_params' => ['grant_type' => 'client_credentials'],
            'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/x-www-form-urlencoded'],
            'verify' => false // 👈 SSL Verification disable
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'] ?? null;
    }

    // Create PayPal Order
    public function createOrder($items, $totalAmount, $prdAmt, $shipping, $gst, $discount, $orderID, $emailID)
    {
        $accessToken = $this->getAccessToken();

        $orderData = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" => $orderID,
                    "email_id" => $emailID,
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $totalAmount,
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => "USD",
                                "value" => $prdAmt
                            ],
                            "shipping" => [
                                "currency_code" => "USD",
                                "value" => round($shipping, 2)
                            ],
                            "tax_total" => [
                                "currency_code" => "USD",
                                "value" => round($gst, 2)
                            ],
                            "discount" => [
                                "currency_code" => "USD",
                                "value" => round(abs($discount), 2) // Must be POSITIVE value
                            ]
                        ]
                    ],
                    "items" => $items
                ]
            ],
            "application_context" => [
                "return_url" => $this->config->returnUrl,
                "cancel_url" => $this->config->cancelUrl
            ]
        ];

        // echo '<pre>';
        // print_r($orderData);
        // die;

        $response = $this->client->request('POST', $this->config->baseUrl . "/v2/checkout/orders", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ],
            'verify' => false, // 👈 SSL Verification disable
            'json' => $orderData
        ]);

        // echo '<pre>'; print_r($response); die;

        return json_decode($response->getBody(), true);
    }

    // Capture Payment
    public function capturePayment($orderID)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->request('POST', $this->config->baseUrl . "/v2/checkout/orders/{$orderID}/capture", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
                'order_id' => $orderID
            ],
            'verify' => false // 👈 SSL Verification disable
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getOrderDetails($paypalOrderID)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->request('GET', $this->config->baseUrl . "/v2/checkout/orders/{$paypalOrderID}", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ],
            'verify' => false
        ]);

        return json_decode($response->getBody(), true);
    }
}
