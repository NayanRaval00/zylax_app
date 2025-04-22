<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Report extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES . 'sales-report';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Sales Report | ' . $settings['app_name'];
        $data['meta_description'] = 'Sales Report | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function sales_report()
    {
        $data['main_page'] = TABLES . 'sales-report';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Sales Report | ' . $settings['app_name'];
        $data['meta_description'] = 'Sales Report | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
    public function sales_inventory()
    {
        $data['main_page'] = TABLES . 'sales-inventory';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Sales Report | ' . $settings['app_name'];
        $data['meta_description'] = 'Sales Report | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
}