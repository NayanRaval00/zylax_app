<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Home Page',
            'main_page' => FORMS . 'home',
        );
        return view('admin/template', $data);
    }

}