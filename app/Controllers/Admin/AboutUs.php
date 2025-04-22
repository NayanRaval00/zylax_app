<?php

namespace App\Controllers\Admin;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use CodeIgniter\Controller;

class AboutUs extends Controller
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
        $data['main_page'] = FORMS . 'about-us';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'About Us | ' . $settings['app_name'];
        $data['meta_description'] = 'About Us | ' . $settings['app_name'];

        $data['about_us'] = get_settings('about_us');

        return view('admin/template', $data);
    }

    public function update_about_us_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            $data = [
                'value' =>  $this->request->getPost('about_us_input_description'),
            ];

            $updated = $this->settingTable->where('variable', 'about_us')->set($data)->update();

            if ($updated) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Content updated Successful!');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            return redirect()->to('/admin/aboutus');

        }else{
            return redirect()->to('/admin/aboutus');
        }
    }

    
}