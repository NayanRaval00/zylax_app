<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class TimeSlots extends Controller
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
        $data['main_page'] = FORMS . 'time-slots';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Time slots | ' . $settings['app_name'];
        $data['meta_description'] = 'Time slots | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    
}