<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ContactUs extends Controller
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
        $data['main_page'] = FORMS . 'contact-us';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Contact Us | ' . $settings['app_name'];
        $data['meta_description'] = 'Contact Us | ' . $settings['app_name'];

        $data['contact_info'] = get_settings('contact_us');

        return view('admin/template', $data);
    }

    public function update_contact_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
                        
            $data = [
                'value' =>  $this->request->getPost('contact_input_description'),
            ];

            $updated = $this->settingTable->where('variable', 'contact_us')->set($data)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Contact Us updated Successful!');

            // Validation passed, process the form data
            return redirect()->to('/admin/contactus');

        }else{
            return redirect()->to('/admin/contactus');
        }
    }
    
}