<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Faqs as FaqsModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Faq extends Controller
{

    public $faqsTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->faqsTable = new FaqsModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-faqs';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Faq Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Faq Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function create_faq()
    {
        $data['main_page'] = FORMS . 'faq';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Faq | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Faq , Create Faq | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_faq()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'question' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Question is required!',
                    ]
                ],
                'answer' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Answer is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/faq/create_faq')->withInput(); // Redirect with old input
            }

            $data = [
                'question' =>  $this->request->getPost('question'),
                'answer' =>  $this->request->getPost('answer'),
                'active' =>  $this->request->getPost('active') ? 1 : 0,
            ];

            // dd($data);
            $faq_added = $this->faqsTable->insert($data);

            if ($faq_added) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Faq Added Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/faq/create_faq');

        }else{
            return redirect()->to('/admin/faq/create_faq');
        }
    }

    public function delete_faq()
    {
        $faq_id = $this->request->getGet('id');
        $faq_deleted = $this->faqsTable->delete($faq_id);

        if ($faq_deleted) {
           $response['error'] = false;
           $response['message'] = 'Faq Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Faq not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    

    public function edit_faq()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-faq';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Faq | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Faq , Create Faq | ' . $settings['app_name'];

            $data['fetched_data'] = $this->faqsTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/faq');
        }
    }

    public function update_faq()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'question' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Question is required!',
                    ]
                ],
                'answer' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Answer is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/faq/edit_faq?edit_id='.$this->request->getPost('edit_faq'));
            }

            $edit_faq_id = $this->request->getPost('edit_faq');

            $data = [
                'question' =>  $this->request->getPost('question'),
                'answer' =>  $this->request->getPost('answer'),
                'active' =>  $this->request->getPost('active') ? 1 : 0,
            ];

            // dd($data);
            $updated = $this->faqsTable->update($edit_faq_id, $data);

            if ($updated) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Faq updated Successful!');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/faq/edit_faq?edit_id='.$edit_faq_id);

        }else{
            return redirect()->to('/admin/faq');
        }
    }

    public function fetchFaqs()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->faqsTable;

        if (!empty($searchValue)) {
            $query->like('question', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $faqs = $query->limit($length, $start)->find();

        $data = [];
        foreach ($faqs as $faq) {

            if($faq['active'] == 1){
                $status = '<a class="badge badge-success text-white">Active</a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Inactive</a>';
            }

            $data[] = [
                $faq['id'],
                $faq['question'],
                $status,
                '<a href="'.base_url('admin/faq/edit_faq?edit_id='.$faq['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/category/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-faq btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$faq['id'].'">
                    <i class="fa fa-trash"></i>
                </a>
                '
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }
    
}