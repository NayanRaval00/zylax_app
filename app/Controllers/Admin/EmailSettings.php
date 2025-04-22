<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class EmailSettings extends Controller
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
        $data['main_page'] = FORMS . 'email-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Email Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Email Settings | ' . $settings['app_name'];

        $data['email_settings'] = get_settings('email_settings', true);

        return view('admin/template', $data);
    }

    public function set_email_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'email' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The email is required!',
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The password is required!',
                    ]
                ],
                'smtp_host' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The smtp_host is required!',
                    ]
                ],
                'smtp_port' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The smtp_port is required!',
                    ]
                ],
                'mail_content_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The mail_content_type is required!',
                    ]
                ],
                'smtp_encryption' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The smtp_encryption is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/emailsettings');
            }
            
            $data = [
                'email' =>  $this->request->getPost('email'),
                'password' =>  $this->request->getPost('password'),
                'smtp_host' =>  $this->request->getPost('smtp_host'),
                'smtp_port' =>  $this->request->getPost('smtp_port'),
                'mail_content_type' =>  $this->request->getPost('mail_content_type'),
                'smtp_encryption' =>  $this->request->getPost('smtp_encryption'),
            ];

            $json_data = json_encode($data);
            $json_data_value = ['value' =>  $json_data];
            $updated = $this->settingTable->where('variable', 'email_settings')->set($json_data_value)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Email Setting updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/emailsettings');

        }else{
            return redirect()->to('/admin/emailsettings');
        }
    }
    
}