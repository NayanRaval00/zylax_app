<?php

namespace App\Controllers;

use App\Services\PayPalService;
use CodeIgniter\Controller;

class PayPalController extends Controller
{
    private $paypalService;

    public function __construct()
    {
        $this->paypalService = new PayPalService();
    }

    // Create PayPal Order
    public function createOrder()
    {
        $items = [
            [
                "name" => "Product 1",
                "description" => "Awesome product",
                "quantity" => "1",
                "unit_amount" => ["value" => "1.00", "currency_code" => "USD"]
            ],
            [
                "name" => "Product 2",
                "description" => "Another product",
                "quantity" => "1",
                "unit_amount" => ["value" => "1.00", "currency_code" => "USD"]
            ]
        ];

        $totalAmount = "2.00";

        $order = $this->paypalService->createOrder($items, $totalAmount);

        if (isset($order['links'][1]['href'])) {
            return redirect()->to($order['links'][1]['href']);
        }

        return "Error creating order!";
    }

    // Handle PayPal Success
    // public function success()
    // {
    //     $orderID = $this->request->getGet('token');
    //     $payment = $this->paypalService->capturePayment($orderID);

    //     if (isset($payment['status']) && $payment['status'] === "COMPLETED") {
    //         return view('frontend/cart/success', ['data' => $payment]);
    //     }

    //     return view('frontend/cart/failure');
    // }

    // Handle PayPal Cancel
    // public function cancel()
    // {
    //     return view('frontend/cart/failure');
    // }
}
