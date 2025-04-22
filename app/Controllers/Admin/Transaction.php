<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Transaction extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES.'transaction';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Transaction | ' . $settings['app_name'];
        $data['meta_description'] = 'View Transaction | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function view_transaction()
    {
        $data['main_page'] = TABLES.'transaction';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Transaction | ' . $settings['app_name'];
        $data['meta_description'] = 'View Transaction | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
    public function customer_wallet()
    {
        $data['main_page'] = TABLES.'customer-wallet';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Transaction | ' . $settings['app_name'];
        $data['meta_description'] = 'View Transaction | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
}