<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Notifications as NotificationsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class AuthenticationSettings extends Controller
{

    public $notificationsTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->notificationsTable = new NotificationsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'authentication-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Authentication Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Authentication Settings | ' . $settings['app_name'];

        $data['authentication_config'] = get_settings('authentication_settings', true);
        $data['time_slot_config'] = get_settings('time_slot_config', true);

        return view('admin/template', $data);
    }

    public function update_authentication_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
                        
            $data = [
                'value' =>  $this->request->getPost('authentication_method'),
            ];

            $updated = $this->settingTable->where('variable', 'authentication_settings')->set($data)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Authentication Setting updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/authenticationsettings');

        }else{
            return redirect()->to('/admin/authenticationsettings');
        }
    }
    

}