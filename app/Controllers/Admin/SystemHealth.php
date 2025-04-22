<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class SystemHealth extends Controller
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
        $data['main_page'] = VIEW . 'system_health';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Email Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Email Settings | ' . $settings['app_name'];

        $data['email_settings'] = get_settings('email_settings', true);

        return view('admin/template', $data);
    }

}