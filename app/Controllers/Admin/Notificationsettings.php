<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Notifications as NotificationsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Notificationsettings extends Controller
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
        $data['main_page'] = FORMS . 'notification-settings';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Update Notification Settings | ' . $settings['app_name'];
        $data['meta_description'] = 'Update Notification Settings | ' . $settings['app_name'];

        $data['vap_id_Key'] = get_settings('vap_id_Key');
        $data['sender_id'] = get_settings('sender_id');
        $data['firebase_project_id'] = get_settings('firebase_project_id');
        $data['service_account_file'] = get_settings('service_account_file');

        return view('admin/template', $data);
    }

    public function manage_notifications()
    {
        $data['main_page'] = TABLES . 'manage-notifications';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Send Notification | ' . $settings['app_name'];
        $data['meta_description'] = 'Send Notification | ' . $settings['app_name'];

        $data['notifications_result'] = $this->notificationsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function send_notifications()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'send_to' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The send_to is required!',
                    ]
                ],
                'type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The type is required!',
                    ]
                ],
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The title is required!',
                    ]
                ],
                'message' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The message is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/notificationsettings/manage_notifications');
            }

            $data = [
                'send_to' =>  $this->request->getPost('send_to'),
                'type' =>  $this->request->getPost('type'),
                'title' =>  $this->request->getPost('title'),
                'message' =>  $this->request->getPost('message'),
            ];

            $added = $this->notificationsTable->insert($data);

            if ($added) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Notification Added Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/notificationsettings/manage_notifications');

        }else{
            return redirect()->to('/admin/notificationsettings/manage_notifications');
        }
    }
    
}