<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ShippingSettings extends Controller
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
        $data['main_page'] = FORMS . 'shipping-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Shipping Methods Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Shipping Methods Management | ' . $settings['app_name'];

        $data['settings'] = get_settings('shipping_method', true);

        return view('admin/template', $data);
    }

    public function update_shipping_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
                        
            $data = [
                'local_shipping_method' =>  $this->request->getPost('local_shipping_method') ? true : false,
                'default_delivery_charge' =>  $this->request->getPost('default_delivery_charge'),
                'shiprocket_shipping_method' =>  $this->request->getPost('shiprocket_shipping_method') ? true : false,
                'email' =>  $this->request->getPost('email'),
                'password' =>  $this->request->getPost('password'),
                'webhook_url' =>  $this->request->getPost('webhook_url'),
                'webhook_token' =>  $this->request->getPost('webhook_token'),
                'standard_shipping_free_delivery' =>  $this->request->getPost('standard_shipping_free_delivery') ? true : false,
                'minimum_free_delivery_order_amount' =>  $this->request->getPost('minimum_free_delivery_order_amount'),
                'pincode_wise_deliverability' =>  $this->request->getPost('pincode_wise_deliverability') ? true : false,
                'city_wise_deliverability' =>  $this->request->getPost('city_wise_deliverability') ? true : false,
            ];

            $json_data = json_encode($data);
            $json_data_value = ['value' =>  $json_data];
            $updated = $this->settingTable->where('variable', 'shipping_method')->set($json_data_value)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Shipping Method updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/shippingsettings');

        }else{
            return redirect()->to('/admin/shippingsettings');
        }
    }
    
}