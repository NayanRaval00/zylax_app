<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Customer extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES . 'manage-customer';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Customer | ' . $settings['app_name'];
        $data['meta_description'] = 'View Customer | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function addresses()
    {
        $data['main_page'] = TABLES . 'manage-address';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'View Address | ' . $settings['app_name'];
        $data['meta_description'] = 'View Address | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
}