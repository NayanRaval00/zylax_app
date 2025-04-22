<?php 

namespace App\Controllers;
use App\Libraries\Paypal_lib;
use App\Models\Products;
use App\Models\shipping_address;
use App\Models\billing_address;
use App\Models\orders;
use App\Models\order_items;
use App\Models\ShippingCategoryPrice;
use App\Models\Transaction;
use App\Models\Tbl_nab_returndata;
use App\Models\Settings;
use App\Models\Tracking_logs;
use App\Models\PromoCodes;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Services\PayPalService;
use CodeIgniter\Controller;
use App\Libraries\EmailHelper;



class CheckoutController extends Controller
{
    public function addToCart(){
        return view('frontend/cart/add-cart');
    }

    public function checkOut(){
        $Settings = new Settings();
        $UserModel = new UserModel();
        $shipping_gst = $Settings->where('variable', 'web_settings')->first();

        if ($shipping_gst) {
            $jsonData = json_decode($shipping_gst['value'], true);
            $checkoutenabled = $jsonData['checkout_disbaled'] ?? null;
        }

        $userData=[];

        $session = session();
        if ($session->has('user_id')) {
            $userId = $session->get('user_id');
            $UserModel = $UserModel->getUserByUserid($userId);
            if ($UserModel) {
                $userData = (array) $UserModel; // Convert object to array
            }
        }
        return view('frontend/cart/checkout', ['chkenabled' => $checkoutenabled, 'user' => $userData]);
    }

    public function fetchShippingCharges(){
        $shippingCategoryPriceModel = new ShippingCategoryPrice();
        $Settings = new Settings();
        $shipping_gst = $Settings->where('variable', 'web_settings')->first();

        $is_gst_included = false;
        if ($shipping_gst) {
            $jsonData = json_decode($shipping_gst['value'], true); // Convert JSON to an associative array
            $shippingGst = $jsonData['shipping_gst'] ?? null; // Extract 'shipping_gst' key
            $productGst = $jsonData['product_gst'] ?? null; // Extract 'shipping_gst' key
            if(!empty($shippingGst)){
                $is_gst_included = true;
            }

        }
        $shippingCategoryPrice = $shippingCategoryPriceModel->fetchShippingCharges($this->request->getPost('category_id'));
        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'shipping_methods' => !empty($shippingCategoryPrice) ? $shippingCategoryPrice : [],
            'shipping_gst' => !empty($shippingGst) ? $shippingGst : [],
            'is_gst_included' => $is_gst_included,
            'product_gst' => $productGst
        ]);

    }

    public function guest_checkout()
    {
        $validation = \Config\Services::validation();
        $session = session();
        //create object of modal
        $orderModel = new orders();
        $orderItemModel = new order_items();
        $billingAddressModel = new billing_address();
        $shippingAddressModel = new shipping_address();
        $transaction = new Transaction();
        $Settings = new Settings();

        // create user and token
        $userId = 0;
        $user_id = "";
        if($session->get('user_id')){
            $userId = $session->get('user_id');
        }else{
            $user_id = 'zy-'.uniqid();
        }
        $cart_token = "zy_" . uniqid(mt_rand(), true);
        // Define validation rules
        $rules = [
            'firstname'     => 'required|min_length[3]|max_length[50]',
            'lastname'      => 'required|min_length[3]|max_length[50]',
            'email'         => 'permit_empty|valid_email',
            'phoneno'       => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'state'         => 'permit_empty|max_length[50]',
            'city'          => 'permit_empty|max_length[50]',
            'pincode'       => 'required|numeric',
            'shipaddress'   => 'permit_empty|min_length[5]|max_length[255]',
            'shipcountry'   => 'permit_empty|max_length[50]',
            'shipstate'     => 'permit_empty|max_length[50]',
            'shipcity'      => 'permit_empty|max_length[50]',
            'shippincode'   => 'permit_empty',
            'item_id'       => 'required',
            'item_image'    => 'required',
            'item_name'     => 'required',
            'item_qty'      => 'required',
            'item_price'    => 'required',
            'item_shipid'   => 'permit_empty',
            'item_shipprice'=> 'permit_empty'
        ];
            // Validate input data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $lastRow = $transaction->orderBy('id', 'DESC')->first();

        if (is_array($lastRow)) {
            $lastId = isset($lastRow['id']) ? $lastRow['id'] : 0;
        } elseif (is_object($lastRow)) {
            $lastId = $lastRow->id;
        } else {
            $lastId = 0;
        }
        
        $genrateOrderID = 'ZYLAX' . rand(10, 100) . $lastId;

        // Save Billing Address
        $billingId = null;
        $billingAddress = [
            'name' => $this->request->getPost('firstname'),
            'last_name'  => $this->request->getPost('lastname'),
            'email'     => $this->request->getPost('email'),
            'phone_number'   => $this->request->getPost('phoneno'),
            'company_name'   => $this->request->getPost('companyname'),
            'address_1'   => $this->request->getPost('address_1'),
            'address_2'   => $this->request->getPost('address_2'),
            'country'   => $this->request->getPost('country'),
            'state'     => $this->request->getPost('state'),
            'city'      => $this->request->getPost('city'),
            'pincode'   => $this->request->getPost('pincode')
        ];
        $billingId = $billingAddressModel->insert($billingAddress, true);

        // Save Shipping Address (if provided)
        $shippingId = null;
        if ($this->request->getPost('ship_address_1')) {
            $shippingAddress = [
                'name' => $this->request->getPost('firstname'),
                'last_name'  => $this->request->getPost('lastname'),
                'email'     => $this->request->getPost('email'),
                'phone_number'   => $this->request->getPost('phoneno'),
                'address_1'   => $this->request->getPost('ship_address_1'),
                'address_2'   => $this->request->getPost('ship_address_2'),
                'country'   => $this->request->getPost('ship_country'),
                'state'     => $this->request->getPost('ship_state'),
                'city'      => $this->request->getPost('ship_city'),
                'pincode'   => $this->request->getPost('ship_pincode')
            ];
            $shippingId = $shippingAddressModel->insert($shippingAddress, true);
        }

        $shipping_gst = $Settings->where('variable', 'web_settings')->first();
        if ($shipping_gst) {
            $jsonData = json_decode($shipping_gst['value'], true);
            $shippingGst = $jsonData['shipping_gst'] ?? null;
            $productGst = $jsonData['product_gst'] ?? null;
        }

        $item_qty = $this->request->getPost('item_qty');
        $item_price = $this->request->getPost('item_price');
        $total_price = 0;
        foreach ($item_qty as $index => $qty) {
            $total_price += (float) $qty * (float) $item_price[$index];
        }

        $ttl_amount = $total_price + $this->request->getPost('item_shipprice') + $this->request->getPost('total_product_gst');

        // PRINT_R($ttl_amount);
        // die;

        // Calculate discount
        // $discountAmount = 0;
        // if ($this->request->getPost('discount_type') === 'percentage') {
        //     // Calculate percentage discount
        //     $discountAmount = ($ttl_amount * floatval($this->request->getPost('discount_price'))) / 100;
        //     $storeDscount = $discountAmount;
        //     $newTotal = $discountAmount;
        // } else { 
        //     // Fixed amount discount
        //     $discountAmount = floatval($this->request->getPost('discount_price'));

        //     // Ensure discount does not exceed order total
        //     if ($discountAmount > $ttl_amount) {
        //         $discountAmount = $ttl_amount;
        //     }

        //     $newTotal = max(0, $ttl_amount - $discountAmount);
        // }

        if($this->request->getPost('payment') === 'paypal'){
            if(!empty($billingId) || !empty($shippingId)){
                $transactionData = array(
                    'user_id' => $userId,
                    'guest_id' => $user_id,
                    'tracking_order_id' => $genrateOrderID,
                    'billing_id' => $billingId,
                    'shipping_id' => $shippingId,
                    'product_amount' => $total_price,
                    'shipping_charge' => $this->request->getPost('item_shipprice'),
                    'shipping_method' => $this->request->getPost('item_shipid'),
                    'total_amount' => $this->request->getPost('total_amt'),
                    'email' => $this->request->getPost('email'),
                    'payment_source' => 'Paypal',
                    'status' => 'Completed',
                    'order_status' => 'progress',
                    'ip' => $this->request->getIPAddress()
                );
                $transaction->insert($transactionData);
                $tran_id = $transaction->insertID();

                // Create Order
                if($tran_id){
                    $orderData = [
                        'user_id' => $userId,
                        'guest_id' => $user_id, // Guest user
                        'billing_id' => $billingId,
                        'shipping_id' => $shippingId,
                        'tracking_id' => $genrateOrderID,
                        'transaction_id' => $tran_id,
                        'ship_cat_id' => $this->request->getPost('item_shipid'),
                        'shipping_price' => $this->request->getPost('item_shipprice'),
                        'shipping_gst' => $this->request->getPost('ship_gst'),
                        'total_gst' => $this->request->getPost('total_product_gst'),
                        'discount_price' => $this->request->getPost('discount_price'),
                        'discount_type' => $this->request->getPost('discount_type'),
                        'total_price' => $total_price,
                        'payment_status' => 'pending',
                        'unique_cart_token' => $cart_token,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $orderId = $orderModel->insert($orderData, true);
                }
                if ($orderId) {
                    $item_ids = $this->request->getPost('item_id');
                    $item_names = $this->request->getPost('item_name');
                    $item_prices = $this->request->getPost('item_price');
                    $item_quantities = $this->request->getPost('item_qty');
                    $item_images = $this->request->getPost('item_image');
                    // Ensure all arrays have the same number of elements
                    $item_count = count($item_ids);
                    for ($i = 0; $i < $item_count; $i++) {
                        $cal_gst = ($item_prices[$i] * $productGst) / 100;
                        $orderItemData = [
                            'order_id' => $orderId,
                            'product_id' => $item_ids[$i], 
                            'product_name' => $item_names[$i],
                            'product_gst' => $cal_gst,
                            'price' => $item_prices[$i],
                            'quantity' => $item_quantities[$i],
                            'image' => $item_images[$i]
                        ];
                
                        $orderItemModel->insert($orderItemData);
                    }
                }
                $data = [
                    'tracking_id' => $genrateOrderID,
                    'status' => 'progress'
                ];
        
                $transactionModel = new Tracking_logs();
        
                // Update the transaction where tracking_order_id matches
                $transactionModel->insert($data);

                $session->set('transection_id', $tran_id);
        
                // Retrieve items from POST request
                $item_names = $this->request->getPost('item_name') ?? [];
                $item_prices = $this->request->getPost('item_price') ?? [];
                $item_quantities = $this->request->getPost('item_qty') ?? [];

                // Validate input arrays
                if (!is_array($item_names) || !is_array($item_prices) || !is_array($item_quantities)) {
                    die('Invalid input data.');
                }

                $items = [];
                $totalAmount = 0;
                $item_count = count($item_names);

                for ($i = 0; $i < $item_count; $i++) {
                    $price = (float) $item_prices[$i];
                    $quantity = (int) $item_quantities[$i];
                    // $cal_gst = ($price * $productGst) / 100;
                    // $totalPrice = ($price + $cal_gst) * $quantity;

                    $items[] = [
                        "name" => $item_names[$i],
                        "description" => "Product purchase",
                        "quantity" => (string) $quantity,
                        "unit_amount" => [
                            "currency_code" => "USD",
                            "value" => round($price, 2) // Ensure it's a float
                        ]
                    ];

                    // $totalAmount += $totalPrice;
                }

                $discountpay = (float) ($this->request->getPost('discount_price') ?? 0);

                // Create PayPal order
                $paypalService = new PayPalService();
                $order = $paypalService->createOrder($items, round($this->request->getPost('total_amt'), 2), round($total_price, 2), $this->request->getPost('item_shipprice'), $this->request->getPost('total_product_gst'), $discountpay, $genrateOrderID, $this->request->getPost('email'));

                if (isset($order['links'][1]['href'])) {
                    return redirect()->to($order['links'][1]['href']);
                }

            }
        }else if($this->request->getPost('payment') === 'bank_deposit'){
            if(!empty($billingId) || !empty($shippingId)){
                $transactionData = array(
                    'user_id' => $userId,
                    'guest_id' => $user_id,
                    'tracking_order_id' => $genrateOrderID,
                    'billing_id' => $billingId,
                    'shipping_id' => $shippingId,
                    'product_amount' => $total_price,
                    'shipping_charge' => $this->request->getPost('item_shipprice'),
                    'shipping_method' => $this->request->getPost('item_shipid'),
                    'total_amount' => $this->request->getPost('total_amt'),
                    'email' => $this->request->getPost('email'),
                    'payment_source' => 'Bank Deposit',
                    'status' => 'Completed',
                    'order_status' => 'progress',
                    'ip' => $this->request->getIPAddress()
                );
                $transaction->insert($transactionData);
                $tran_id = $transaction->insertID();

                // Create Order
                if($tran_id){
                    $orderData = [
                        'user_id' => $userId,
                        'guest_id' => $user_id, // Guest user
                        'billing_id' => $billingId,
                        'shipping_id' => $shippingId,
                        'tracking_id' => $genrateOrderID,
                        'transaction_id' => $tran_id,
                        'ship_cat_id' => $this->request->getPost('item_shipid'),
                        'shipping_price' => $this->request->getPost('item_shipprice'),
                        'shipping_gst' => $this->request->getPost('ship_gst'),
                        'total_gst' => $this->request->getPost('total_product_gst'),
                        'discount_price' => $this->request->getPost('discount_price'),
                        'discount_type' => $this->request->getPost('discount_type'),
                        'total_price' => $total_price,
                        'payment_status' => 'pending',
                        'unique_cart_token' => $cart_token,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $orderId = $orderModel->insert($orderData, true);
                }
                if ($orderId) {
                    $item_ids = $this->request->getPost('item_id');
                    $item_names = $this->request->getPost('item_name');
                    $item_prices = $this->request->getPost('item_price');
                    $item_quantities = $this->request->getPost('item_qty');
                    $item_images = $this->request->getPost('item_image');
                    // Ensure all arrays have the same number of elements
                    $item_count = count($item_ids);
                    for ($i = 0; $i < $item_count; $i++) {
                        $cal_gst = ($item_prices[$i] * $productGst) / 100;
                        $orderItemData = [
                            'order_id' => $orderId,
                            'product_id' => $item_ids[$i], 
                            'product_name' => $item_names[$i],
                            'product_gst' => $cal_gst,
                            'price' => $item_prices[$i],
                            'quantity' => $item_quantities[$i],
                            'image' => $item_images[$i]
                        ];
                
                        $orderItemModel->insert($orderItemData);
                    }
                }
                $data = [
                    'tracking_id' => $genrateOrderID,
                    'status' => 'progress'
                ];
        
                $transactionModel = new Tracking_logs();
        
                // Update the transaction where tracking_order_id matches
                $transactionModel->insert($data);

                $data = [
                    'name' => $this->request->getPost('firstname'),
                    'last_name'  => $this->request->getPost('lastname'),
                    'email'     => $this->request->getPost('email'),
                    'phone_number'   => $this->request->getPost('phoneno'),
                    'address_1'   => $this->request->getPost('address_1'),
                    'address_2'   => $this->request->getPost('address_2'),
                    'country'   => $this->request->getPost('country'),
                    'state'     => $this->request->getPost('state'),
                    'city'      => $this->request->getPost('city'),
                    'pincode'   => $this->request->getPost('pincode'),
                    'item_ids' => $this->request->getPost('item_id'),
                    'item_names' => $this->request->getPost('item_name'),
                    'item_prices' => $this->request->getPost('item_price'),
                    'item_quantities' => $this->request->getPost('item_qty'),
                    'item_images' => $this->request->getPost('item_image'),
                    'shipping_charge' => $this->request->getPost('item_shipprice'),
                    'order_id' => $genrateOrderID,
                    'payment_method' => $this->request->getPost('payment')
                ];

                $emailHelper = new emailHelper();

                $email = $this->request->getPost('email');
                $emailHelper->sendOrderConfirmationEmail($email, $genrateOrderID);
                return redirect()->to('success')->with('data', $data);
            }
        }else if($this->request->getPost('payment') === 'nab'){
            $transactionData = array(
                'user_id' => $userId,
                'guest_id' => $user_id,
                'tracking_order_id' => $genrateOrderID,
                'billing_id' => $billingId,
                'shipping_id' => $shippingId,
                'product_amount' => $total_price,
                'shipping_charge' => $this->request->getPost('item_shipprice'),
                'shipping_method' => $this->request->getPost('item_shipid'),
                'total_amount' => $this->request->getPost('total_amt'),
                'email' => $this->request->getPost('email'),
                'payment_source' => 'nab',
                'status' => 'Completed',
                'order_status' => 'progress',
                'ip' => $this->request->getIPAddress()
            );
            $transaction->insert($transactionData);
            $tran_id = $transaction->insertID();
             // Create Order
            if($tran_id){
                $orderData = [
                    'user_id' => $userId,
                    'guest_id' => $user_id, // Guest user
                    'billing_id' => $billingId,
                    'shipping_id' => $shippingId,
                    'tracking_id' => $genrateOrderID,
                    'transaction_id' => $tran_id,
                    'ship_cat_id' => $this->request->getPost('item_shipid'),
                    'shipping_price' => $this->request->getPost('item_shipprice'),
                    'shipping_gst' => $this->request->getPost('ship_gst'),
                    'total_gst' => $this->request->getPost('total_product_gst'),
                    'discount_price' => $this->request->getPost('discount_price'),
                    'discount_type' => $this->request->getPost('discount_type'),
                    'total_price' => $total_price,
                    'payment_status' => 'pending',
                    'unique_cart_token' => $cart_token,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $orderId = $orderModel->insert($orderData, true);
            }
            if ($orderId) {
                $item_ids = $this->request->getPost('item_id');
                $item_names = $this->request->getPost('item_name');
                $item_prices = $this->request->getPost('item_price');
                $item_quantities = $this->request->getPost('item_qty');
                $item_images = $this->request->getPost('item_image');
                // Ensure all arrays have the same number of elements
                $item_count = count($item_ids);
                for ($i = 0; $i < $item_count; $i++) {
                    $cal_gst = ($item_prices[$i] * $productGst) / 100;
                    $orderItemData = [
                        'order_id' => $orderId,
                        'product_id' => $item_ids[$i], 
                        'product_name' => $item_names[$i],
                        'product_gst' => $cal_gst,
                        'price' => $item_prices[$i],
                        'quantity' => $item_quantities[$i],
                        'image' => $item_images[$i]
                    ];
            
                    $orderItemModel->insert($orderItemData);
                }
            }
            $data = [
                'tracking_id' => $genrateOrderID,
                'status' => 'progress'
            ];
    
            $transactionModel = new Tracking_logs();
    
            // Update the transaction where tracking_order_id matches
            $transactionModel->insert($data);

            $is_test = 0;
            $data = [
                'is_test' => $is_test,
                'merchant' => [
                    'live' => 'BGP0010',
                    'test' => 'XYZ0010',
                ],
                'name' => $this->request->getPost('firstname'),
                'last_name'  => $this->request->getPost('lastname'),
                'email'     => $this->request->getPost('email'),
                'phone_number'   => $this->request->getPost('phoneno'),
                'address_1'   => $this->request->getPost('address_1'),
                'address_2'   => $this->request->getPost('address_2'),
                'country'   => $this->request->getPost('country'),
                'state'     => $this->request->getPost('state'),
                'city'      => $this->request->getPost('city'),
                'pincode'   => $this->request->getPost('pincode'),
                'item_ids' => $this->request->getPost('item_id'),
                'item_names' => $this->request->getPost('item_name'),
                'item_prices' => $this->request->getPost('item_price'),
                'item_quantities' => $this->request->getPost('item_qty'),
                'item_images' => $this->request->getPost('item_image'),
                'shipping_charge' => $this->request->getPost('item_shipprice'),
                'gst_rate' => $this->request->getPost('total_product_gst'),
                'order_id' => $genrateOrderID,
            ];
            return view('frontend/cart/nabPayment', $data);
        }      
    }

    public function success() {
        $paypalService = new PayPalService();
        $Transaction = new Transaction();
        $emailHelper = new emailHelper();
    
        $paypalOrderID = $this->request->getGet('token');
    
        if($paypalOrderID){

            // Capture the payment
            $payment = $paypalService->capturePayment($paypalOrderID);
        
            if (!isset($payment['status']) || $payment['status'] !== "COMPLETED") {
                return view('frontend/cart/failure');
            }
        
            // Get order details
            $orderDetails = $paypalService->getOrderDetails($paypalOrderID);
        
            if (empty($orderDetails) || !isset($orderDetails['purchase_units'][0]['reference_id'])) {
                return view('frontend/cart/failure');
            }

        
            $referenceID = $orderDetails['purchase_units'][0]['reference_id'];
            $paymentResponseJson = json_encode($orderDetails);

            // Update only 'payment_response' in the database
            $emails = $Transaction->select('email')
                        ->where('tracking_order_id', $referenceID)
                        ->first();

            $email = $emails ? $emails['email'] : null;

            if ($emails > 0) {
                // echo "hii";
                $updated = $Transaction->where('tracking_order_id', $referenceID)
                        ->set('payment_response', $paymentResponseJson)
                        ->update();
                // Check if update was successful
                if ($updated) {
                    $emailHelper->sendOrderConfirmationEmail($email, $referenceID);
                    return view('frontend/cart/success', ['referenceID' => $referenceID]);
                }
            }
        }else{
            $data = session()->getFlashdata('data');
            return view('frontend/cart/success', ['data' => $data]);
        }
    
        return view('frontend/cart/failure');
    }
    

    public function cancel()
    {
        $data = array();
        return view('frontend/cart/failure', ['data' => $data]);
    }

    public function processNab()
    {
        $Tbl_nab_returndata = new Tbl_nab_returndata();
        $transaction = new Transaction();

        $request = service('request');
        $varData = json_encode(['get' => $request->getGet(), 'post' => $request->getPost()]);
        
        $Tbl_nab_returndata->insert(['returned_data' => $varData]);
        
        $orderId = $request->getGet('orderId');
        
        if (!empty($orderId)) {
            $objData = $transaction->where('order_id', $orderId)->get()->getRow();
            
            echo '<script>localStorage.clear();</script>';

            // if (!empty($objData)) {
            //     $db->table('transaction')->where('id', $objData->id)->update(['status' => 'Completed']);
                
            //     if ($objData->guest_id != 0) {
            //         $objBillingData = $db->table('billing_address')
            //             ->where('guest_id', $objData->guest_id)
            //             ->orderBy('id', 'DESC')
            //             ->limit(1)
            //             ->get()
            //             ->getRowArray();
            //     } else {
            //         $objBillingData = $db->table('billing_address')
            //             ->where('user_id', $objData->user_id)
            //             ->orderBy('id', 'DESC')
            //             ->limit(1)
            //             ->get()
            //             ->getRowArray();
            //     }

                
            //     // $emailService = \Config\Services::email();
            //     // $subject = "Zylax Order";
                
            //     // $dataMail = [
            //     //     'transection' => [
            //     //         'payment_source' => "NAB",
            //     //         'order_id' => $objData->order_id,
            //     //         'email' => $objData->email,
            //     //         'shipping_charge' => $objData->shipping_charge,
            //     //         'ex_taxtotal' => $objData->ex_taxtotal,
            //     //         'net_amount_debit' => $objData->net_amount_debit,
            //     //         'tax_amount' => $objData->tax_amount,
            //     //         'discount' => $objData->discount,
            //     //     ],
            //     //     'shippingAddress' => $objBillingData,
            //     //     'objProductData' => $db->table('orders')->where('transection_id', $objData->id)->get()->getResultArray()
            //     // ];
                
            //     // $mesg = view('mailTemplate/orders_as_service', $dataMail);
                
            //     // $emailService->setTo($objData->email)
            //     //     ->setSubject($subject)
            //     //     ->setMessage($mesg)
            //     //     ->send();
                
            //     // $emailService->setTo(ADMIN_MAIL)
            //     //     ->setSubject('New Order ' . date('Y-m-d'))
            //     //     ->setMessage($mesg)
            //     //     ->send();
                
            //     // $cartService = service('cart');
            //     // $cartService->destroy();
            // }
        }
    }

    public function trackorder()
    {
        if (!empty($_GET['orderID'])) {
            $orders = new Orders();
            $ordersdetails = $orders->getOrderBasicDetailsAndTracking($_GET['orderID']);

            if (!empty($ordersdetails) && isset($ordersdetails[0]['order_id'])) {
                $orderId = $ordersdetails[0]['order_id'];
                $ordersProd = $orders->getOrderProducts($orderId);
                return view('frontend/track-order', ['order' => $ordersdetails, 'products' => $ordersProd]);
            }
        }

        return view('frontend/track-order');
    }

    public function downloadInvoice($tracking_id)
    {
        // Fetch order details
        $orders = new orders();
        $data['orders_details'] = $orders->getOrderDetails($tracking_id);

        // Create a new instance of DOMPDF
        $dompdf = new Dompdf();

        // Set options for DOMPDF (optional)
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        // Load the view and get HTML content
        $htmlContent = view('admin/pages/view/download_Invoice', $data);

        // Load the HTML content into DOMPDF
        $dompdf->loadHtml($htmlContent);

        // (Optional) Set paper size
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('defaultFont', 'DejaVu Sans');

        $dompdf->set_option('margin_top', 0);
        $dompdf->set_option('margin_right', 0);
        $dompdf->set_option('margin_bottom', 0);
        $dompdf->set_option('margin_left', 0);

        // Render the PDF
        $dompdf->render();

        // Stream the PDF to the browser for download
        $dompdf->stream("invoice_{$tracking_id}.pdf", array("Attachment" => 1));
    }

    public function validate_coupon() {
        $promo_codes = new PromoCodes();
        $couponData = $promo_codes->validatePromoCode($this->request->getPost('coupon_id'));
    
        if (!$couponData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid or expired coupon code.'
            ]);
        }
    
        $orderTotal = floatval($this->request->getPost('total'));
    
        // Ensure orderTotal is valid
        if ($orderTotal <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid order total amount.'
            ]);
        }
    
        // Check if coupon is active
        if ($couponData['status'] != 1) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'This coupon is not active.'
            ]);
        }
    
        // Check start and end date
        $currentDate = date('Y-m-d');
        if ($currentDate < $couponData['start_date'] || $currentDate > $couponData['end_date']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'This coupon is not valid at this time.'
            ]);
        }
    
        // Check if the order total meets the minimum required amount
        if ($orderTotal < floatval($couponData['minimum_order_amount'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Minimum order amount required: ' . $couponData['minimum_order_amount']
            ]);
        }
    
        // Check repeat usage limit
        if ($couponData['repeat_usage'] == 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'This coupon can only be used once per user.'
            ]);
        }
    
        // Calculate discount
        $discountAmount = 0;

        if ($couponData['discount_type'] === 'percentage') {
            // Calculate percentage discount
            $discountAmount = ($orderTotal * floatval($couponData['discount'])) / 100;
        
            $newTotal = $discountAmount;
            // Ensure discount does not exceed the maximum allowed discount amount
            if ($couponData['max_discount_amount'] > 0 && $discountAmount > floatval($couponData['max_discount_amount'])) {
                $discountAmount = floatval($couponData['max_discount_amount']);
            }
        } else { 
            // Fixed amount discount
            $discountAmount = floatval($couponData['discount']);
        
            // Ensure discount does not exceed order total
            if ($discountAmount > $orderTotal) {
                $discountAmount = $orderTotal;
            }

            $newTotal = max(0, $orderTotal - $discountAmount);
        }

        // Ensure the final total is not negative
        
        
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Coupon applied successfully!',
            'coupon' => [
                'discount' => $discountAmount,
                'new_total' => $newTotal,
                'original_total' => $orderTotal,
                'discount_type' => $couponData['discount_type']
            ]
        ]);
    }
    
    
    

}
?>