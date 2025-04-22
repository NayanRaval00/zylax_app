<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Shipping as ShippingModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Shipping extends Controller
{
    public $shippingTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->shippingTable = new ShippingModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-shipping';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Shipping Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Shipping Management | ' . $settings['app_name'];

        $data['shipping_result'] = $this->shippingTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_shipping()
    {

        $data['main_page'] = FORMS . 'shipping';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Shipping | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Shipping , Create Shipping | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_shipping()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'shipping_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Shipping Name is required!',
                    ]
                ],
                'description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Description is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/shipping/create_shipping');
            }


            $exist_category = $this->shippingTable->where('name', $this->request->getPost('shipping_name'))->first();

            if($exist_category){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Shipping Name Already exist you should use a different name');
            }else{

                $data = [
                    'name' =>  $this->request->getPost('shipping_name'),
                    'description' =>  $this->request->getPost('description'),
                ];

                $shipping_added = $this->shippingTable->insert($data);

                if ($shipping_added) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Shipping added Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }                

            }
                

            // Validation passed, process the form data
            return redirect()->to('/admin/shipping/create_shipping');

        }else{
            return redirect()->to('/admin/shipping/create_shipping');
        }
    }

    public function edit_shipping()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-shipping';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Shipping | ' . $settings['app_name'] : 'Add Shipping | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Shipping , Create Shipping | ' . $settings['app_name'];

            $data['fetched_data'] = $this->shippingTable->where('id', $edit_id)->first();

            // dd($data);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/shipping');
        }

    }

    public function update_shipping()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'shipping_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Shipping Name is required!',
                    ]
                ],
                'description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Description is required!',
                    ]
                ],
            ];

            $edit_shipping_id = $this->request->getPost('edit_shipping');

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/shipping/edit_shipping?edit_id='.$edit_shipping_id);
            }

            $exist_shipping = $this->shippingTable
                            ->where('name', $this->request->getPost('shipping_name'))
                            ->where('id <>', $edit_shipping_id)
                            ->first();

            if($exist_shipping){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Shipping Name Already exist you should use a different name');
            }else{

                $data = [
                    'name' =>  $this->request->getPost('shipping_name'),
                    'description' =>  $this->request->getPost('description'),
                ];

                $shipping_updated = $this->shippingTable->update($edit_shipping_id, $data);

                if ($shipping_updated) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Shipping updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
                
            }    

            // Validation passed, process the form data
            return redirect()->to('/admin/shipping/edit_shipping?edit_id='.$edit_shipping_id);

        }else{
            return redirect()->to('/admin/shipping');
        }
    }
    
    public function update_shipping_status($shipping_id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $shipping_updated = $this->shippingTable->update($shipping_id, $data);

        if ($shipping_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Shipping status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/shipping');
    }

    public function delete_shipping()
    {
        $shipping_id = $this->request->getGet('id');
        $shipping_deleted = $this->shippingTable->delete($shipping_id);

        if ($shipping_deleted) {
           $response['error'] = false;
           $response['message'] = 'Shipping Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Shipping not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    
}