<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class ReturnRequest extends Controller
{
    public function index()
    {
        $data['main_page'] = TABLES . 'return-request';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Return Request | ' . $settings['app_name'];
        $data['meta_description'] = 'Return Request | ' . $settings['app_name'];

        return view('admin/template', $data);
    }
    
}