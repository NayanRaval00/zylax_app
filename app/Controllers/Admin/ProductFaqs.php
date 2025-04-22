<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Brands as BrandsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class ProductFaqs extends Controller
{

    public $brandsTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->brandsTable = new BrandsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-product-faqs';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Product FAQS Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Product FAQS Management | ' . $settings['app_name'];

        $data['products_result'] = $this->brandsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_product()
    {
        $data['main_page'] = FORMS . 'product';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Product | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Product | ' . $settings['app_name'];

        $data['shipping_method'] = get_settings('shipping_method', true);
        $data['payment_method'] = get_settings('payment_method', true);
        $data['system_settings'] = get_settings('system_settings', true);

        return view('admin/template', $data);
    }

    public function add_brand()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'brand_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Brand Name is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/brand/create_brand');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $exist_brand = $this->brandsTable->where('name', $this->request->getPost('brand_input_name'))->first();

                if($exist_brand){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Brand Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/brands', $mainImage);

                    $data = [
                        'name' =>  $this->request->getPost('brand_input_name'),
                        'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                        'image' =>  "uploads/brands/".$mainImage,
                        'status' =>  1,
                    ];

                    // dd($data);
                    $brand_added = $this->brandsTable->insert($data);

                    if ($brand_added) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Brand Added Successfully');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                }
            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Brand Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/brand/create_brand');

        }else{
            return redirect()->to('/admin/brand/create_brand');
        }
    }

    public function update_brand_status($id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $brand_updated = $this->brandsTable->update($id, $data);

        if ($brand_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Brand status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/brand');
    }

    public function delete_brand()
    {
        $brand_id = $this->request->getGet('id');
        $brand_deleted = $this->brandsTable->delete($brand_id);

        if ($brand_deleted) {
           $response['error'] = false;
           $response['message'] = 'Brand Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Brand not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    

    public function edit_brand()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-brand';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Brand | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Brand , Create Brand | ' . $settings['app_name'];

            $data['fetched_data'] = $this->brandsTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/brand');
        }
    }

    public function update_brand()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'brand_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Brand Name is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/brand/edit_brand?edit_id='.$this->request->getPost('edit_brand'));
            }

            $edit_brand_id = $this->request->getPost('edit_brand');

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_brand = $this->brandsTable
                    ->where('name', $this->request->getPost('brand_input_name'))
                    ->where('id <>', $edit_brand_id)
                    ->first();

                    if($exist_brand){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Brand Already exist you should use a different name');
                    }else{

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/brands', $mainImage);

                        $data = [
                            'name' =>  $this->request->getPost('brand_input_name'),
                            'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                            'image' =>  "uploads/brands/".$mainImage,
                        ];

                        // dd($data);
                        $updated = $this->brandsTable->update($edit_brand_id, $data);

                        if ($updated) {
                            $session->setFlashdata('status', 'success');
                            $session->setFlashdata('message', 'Brand updated Successful!');
                        } else {
                            $session->setFlashdata('status', 'error');
                            $session->setFlashdata('message', 'Something went wrong');
                        }
                        

                    }
                    
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Brand Main Image is required!');
                }

            }else{

                $exist_brand = $this->brandsTable
                ->where('name', $this->request->getPost('brand_input_name'))
                ->where('id <>', $edit_brand_id)
                ->first();

                if($exist_brand){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Brand Already exist you should use a different name');
                }else{

                    $data = [
                        'name' =>  $this->request->getPost('brand_input_name'),
                        'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                    ];

                    // dd($data);
                    $updated = $this->brandsTable->update($edit_brand_id, $data);

                    if ($updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Brand updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    
                }             

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/brand/edit_brand?edit_id='.$edit_brand_id);

        }else{
            return redirect()->to('/admin/brand');
        }
    }
    
}