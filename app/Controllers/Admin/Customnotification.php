<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\CustomNotifications as CustomNotificationsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Customnotification extends Controller
{

    public $customNotificationsTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->customNotificationsTable = new CustomNotificationsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'custom_notification';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Custom Notification | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Custom Notification | ' . $settings['app_name'];

        $data['custom_notifications_result'] = $this->customNotificationsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_notification()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
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
                return redirect()->to('/admin/customnotification');
            }

            $exist_custom_message = $this->customNotificationsTable->where('type', $this->request->getPost('type'))->first();

            if($exist_custom_message){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Custom message Already exist you should use a different name');
            }else{

                $data = [
                    'type' =>  $this->request->getPost('type'),
                    'title' =>  $this->request->getPost('title'),
                    'message' =>  $this->request->getPost('message'),
                ];
    
                $added = $this->customNotificationsTable->insert($data);
    
                if ($added) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Custom Notification Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
    
            }
            // Validation passed, process the form data
            return redirect()->to('/admin/customnotification');
            

        }else{
            return redirect()->to('/admin/customnotification');
        }
    }
    
}