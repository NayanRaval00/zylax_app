<?php

namespace App\Controllers\Admin;
use App\Models\Settings as SettingsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use CodeIgniter\Controller;

class PrivacyPolicy extends Controller
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
        $data['main_page'] = FORMS . 'privacy-policy';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Privacy Policy | ' . $settings['app_name'];
        $data['meta_description'] = 'Privacy Policy | ' . $settings['app_name'];

        $data['privacy_policy'] = get_settings('privacy_policy');
        $data['terms_n_condition'] = get_settings('terms_conditions');

        return view('admin/template', $data);
    }

    public function update_privacy_policy_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            $data1 = ['value' =>  $this->request->getPost('privacy_policy_input_description')];
            $updated1 = $this->settingTable->where('variable', 'privacy_policy')->set($data1)->update();

            $data2 = ['value' =>  $this->request->getPost('terms_n_conditions_input_description')];
            $updated2 = $this->settingTable->where('variable', 'terms_conditions')->set($data2)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Content updated Successful!');

            return redirect()->to('/admin/privacypolicy');

        }else{
            return redirect()->to('/admin/privacypolicy');
        }
    }

    public function shipping_policy()
    {
        $data['main_page'] = FORMS . 'shipping-policy';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Shipping Policy | ' . $settings['app_name'];
        $data['meta_description'] = 'Shipping Policy | ' . $settings['app_name'];

        $data['shipping_policy'] = get_settings('shipping_policy');

        return view('admin/template', $data);
    }

    public function update_shipping_policy_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            $data = ['value' =>  $this->request->getPost('shipping_policy_input_description')];
            $updated = $this->settingTable->where('variable', 'shipping_policy')->set($data)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Content updated Successful!');

            return redirect()->to('/admin/privacypolicy/shipping_policy');

        }else{
            return redirect()->to('/admin/privacypolicy/shipping_policy');
        }
    }

    public function return_policy()
    {
        $data['main_page'] = FORMS . 'return-policy';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'return Policy | ' . $settings['app_name'];
        $data['meta_description'] = 'return Policy | ' . $settings['app_name'];

        $data['return_policy'] = get_settings('return_policy');

        return view('admin/template', $data);
    }

    public function update_return_policy_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            $data = ['value' =>  $this->request->getPost('return_policy_input_description')];
            $updated = $this->settingTable->where('variable', 'return_policy')->set($data)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Content updated Successful!');

            return redirect()->to('/admin/privacypolicy/return_policy');

        }else{
            return redirect()->to('/admin/privacypolicy/return_policy');
        }
    }

    public function admin_policy()
    {
        $data['main_page'] = FORMS . 'admin-policy';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Admin Policy | ' . $settings['app_name'];
        $data['meta_description'] = 'Admin Policy | ' . $settings['app_name'];

        $data['admin_policy'] = get_settings('admin_policy');

        return view('admin/template', $data);
    }

    public function update_admin_policy_settings()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            $data = ['value' =>  $this->request->getPost('admin_policy_input_description')];
            $updated = $this->settingTable->where('variable', 'admin_policy')->set($data)->update();

            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Content updated Successful!');

            return redirect()->to('/admin/privacypolicy/admin_policy');

        }else{
            return redirect()->to('/admin/privacypolicy/admin_policy');
        }
    }

    
}