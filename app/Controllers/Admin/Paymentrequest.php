<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Paymentrequest extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES . 'payment-request';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Payment Request | ' . $settings['app_name'];
        $data['meta_description'] = 'Payment Request | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

}