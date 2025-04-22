<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Taxes as TaxesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Taxes extends Controller
{

    public $taxesTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->taxesTable = new TaxesModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'tax';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Tax | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Tax | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_tax()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The title is required!',
                    ]
                ],
                'percentage' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The percentage is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/taxes');
            }

            $data = [
                'title' =>  $this->request->getPost('title'),
                'percentage' =>  $this->request->getPost('percentage'),
            ];

            $added = $this->taxesTable->insert($data);

            if ($added) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Tax Added Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/taxes');

        }else{
            return redirect()->to('/admin/taxes');
        }
    }
    
    public function manage_taxes()
    {
        $data['main_page'] = TABLES . 'manage-taxes';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Taxes | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Taxes | ' . $settings['app_name'];

        $data['taxes_result'] = $this->taxesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function delete_tax()
    {
        $tax_id = $this->request->getGet('id');
        $deleted = $this->taxesTable->delete($tax_id);

        if ($deleted) {
           $response['error'] = false;
           $response['message'] = 'Tax Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Tax not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function edit_tax()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-tax';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Taxes | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Taxes | ' . $settings['app_name'];

            $data['fetched_data'] = $this->taxesTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/brand');
        }
    }

    public function update_tax()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
               'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The title is required!',
                    ]
                ],
                'percentage' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The percentage is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/taxes/edit_tax?edit_id='.$this->request->getPost('edit_tax_id'));
            }

            $edit_id = $this->request->getPost('edit_tax_id');

            $data = [
                'title' =>  $this->request->getPost('title'),
                'percentage' =>  $this->request->getPost('percentage'),
            ];

            // dd($data);
            $updated = $this->taxesTable->update($edit_id, $data);

            if ($updated) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Tax updated Successful!');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/taxes/edit_tax?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/taxes');
        }
    }
    
}