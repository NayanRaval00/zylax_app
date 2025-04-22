<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PaymentSettings extends Controller
{
    public $settingTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->settingTable = new SettingsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'payment-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Payment Methods Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Payment Methods Management | ' . $settings['app_name'];

        $data['settings'] = get_settings('payment_method', true);

        // dd($data);

        return view('admin/template', $data);
    }

    public function update_payment_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
                        
            $data = [
                'paypal_payment_method' =>  $this->request->getPost('paypal_payment_method') ? true : false,
                'paypal_mode' =>  $this->request->getPost('paypal_mode'),
                'paypal_business_email' =>  $this->request->getPost('paypal_business_email'),
                'paypal_client_id' =>  $this->request->getPost('paypal_client_id'),
                'paypal_secret_key' =>  $this->request->getPost('paypal_secret_key'),
                'currency_code' =>  $this->request->getPost('currency_code'),
                'razorpay_payment_method' =>  $this->request->getPost('razorpay_payment_method') ? true : false,
                'razorpay_key_id' =>  $this->request->getPost('razorpay_key_id'),
                'razorpay_secret_key' =>  $this->request->getPost('razorpay_secret_key'),
                'razorpay__webhook_url' =>  $this->request->getPost('razorpay__webhook_url'),
                'refund_webhook_secret_key' =>  $this->request->getPost('refund_webhook_secret_key'),
                'paystack_payment_method' =>  $this->request->getPost('paystack_payment_method') ? true : false,
                'paystack_key_id' =>  $this->request->getPost('paystack_key_id'),
                'paystack_secret_key' =>  $this->request->getPost('paystack_secret_key'),
                'paystack_webhook_url' =>  $this->request->getPost('paystack_webhook_url'),
                'stripe_payment_method' =>  $this->request->getPost('stripe_payment_method') ? true : false,
                'stripe_payment_mode' =>  $this->request->getPost('stripe_payment_mode'),
                'stripe_webhook_url' =>  $this->request->getPost('stripe_webhook_url'),
                'stripe_publishable_key' =>  $this->request->getPost('stripe_publishable_key'),
                'stripe_secret_key' =>  $this->request->getPost('stripe_secret_key'),
                'stripe_webhook_secret_key' =>  $this->request->getPost('stripe_webhook_secret_key'),
                'stripe_currency_code' =>  $this->request->getPost('stripe_currency_code'),
                'flutterwave_payment_method' =>  $this->request->getPost('flutterwave_payment_method') ? true : false,
                'flutterwave_public_key' =>  $this->request->getPost('flutterwave_public_key'),
                'flutterwave_secret_key' =>  $this->request->getPost('flutterwave_secret_key'),
                'flutterwave_encryption_key' =>  $this->request->getPost('flutterwave_encryption_key'),
                'flutterwave_currency_code' =>  $this->request->getPost('flutterwave_currency_code'),
                'flutterwave_webhook_secret_key' =>  $this->request->getPost('flutterwave_webhook_secret_key'),
                'flutterwave_webhook_url' =>  $this->request->getPost('flutterwave_webhook_url'),
                'paytm_payment_method' =>  $this->request->getPost('paytm_payment_method') ? true : false,
                'paytm_payment_mode' =>  $this->request->getPost('paytm_payment_mode'),
                'paytm_merchant_key' =>  $this->request->getPost('paytm_merchant_key'),
                'paytm_merchant_id' =>  $this->request->getPost('paytm_merchant_id'),
                'paytm_website' =>  $this->request->getPost('paytm_website'),
                'paytm_industry_type_id' =>  $this->request->getPost('paytm_industry_type_id'),
                'midtrans_payment_method' =>  $this->request->getPost('midtrans_payment_method') ? true : false,
                'midtrans_payment_mode' =>  $this->request->getPost('midtrans_payment_mode'),
                'midtrans_client_key' =>  $this->request->getPost('midtrans_client_key'),
                'midtrans_merchant_id' =>  $this->request->getPost('midtrans_merchant_id'),
                'midtrans_server_key' =>  $this->request->getPost('midtrans_server_key'),
                'myfaoorah_payment_method' =>  $this->request->getPost('myfaoorah_payment_method') ? true : false,
                'myfatoorah_token' =>  $this->request->getPost('myfatoorah_token'),
                'myfatoorah_payment_mode' =>  $this->request->getPost('myfatoorah_payment_mode'),
                'myfatoorah_language' =>  $this->request->getPost('myfatoorah_language'),
                'myfatoorah__webhook_url' =>  $this->request->getPost('myfatoorah__webhook_url'),
                'myfatoorah_country' =>  $this->request->getPost('myfatoorah_country'),
                'myfatoorah__successUrl' =>  $this->request->getPost('myfatoorah__successUrl'),
                'myfatoorah__errorUrl' =>  $this->request->getPost('myfatoorah__errorUrl'),
                'myfatoorah__secret_key' =>  $this->request->getPost('myfatoorah__secret_key'),
                'instamojo_payment_method' =>  $this->request->getPost('instamojo_payment_method') ? true : false,
                'instamojo_payment_mode' =>  $this->request->getPost('instamojo_payment_mode'),
                'instamojo_client_id' =>  $this->request->getPost('instamojo_client_id'),
                'instamojo_client_secret' =>  $this->request->getPost('instamojo_client_secret'),
                'instamojo_webhook_url' =>  $this->request->getPost('instamojo_webhook_url'),
                'phonepe_payment_method' =>  $this->request->getPost('phonepe_payment_method') ? true : false ,
                'phonepe_payment_mode' =>  $this->request->getPost('phonepe_payment_mode'),
                'phonepe_marchant_id' =>  $this->request->getPost('phonepe_marchant_id'),
                'phonepe_salt_index' =>  $this->request->getPost('phonepe_salt_index'),
                'phonepe_salt_key' =>  $this->request->getPost('phonepe_salt_key'),
                'phonepe_webhook_url' =>  $this->request->getPost('phonepe_webhook_url'),
                'direct_bank_transfer' =>  $this->request->getPost('direct_bank_transfer') ? true : false,
                'account_name' =>  $this->request->getPost('account_name'),
                'account_number' =>  $this->request->getPost('account_number'),
                'bank_name' =>  $this->request->getPost('bank_name'),
                'bank_code' =>  $this->request->getPost('bank_code'),
                'notes' =>  $this->request->getPost('notes'),
                'cod_method' =>  $this->request->getPost('cod_method') ? true : false,
                'min_cod_amount' =>  $this->request->getPost('min_cod_amount'),
                'max_cod_amount' =>  $this->request->getPost('max_cod_amount'),
            ];

            $json_data = json_encode($data);
            $json_data_value = ['value' =>  $json_data];
            $updated = $this->settingTable->where('variable', 'payment_method')->set($json_data_value)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Payment Method updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/paymentsettings');

        }else{
            return redirect()->to('/admin/paymentsettings');
        }
    }
    
}