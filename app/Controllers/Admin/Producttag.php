<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\ProductMasterTags as ProductMasterTagsModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Producttag extends Controller
{

    public $productMasterTagsTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->productMasterTagsTable = new ProductMasterTagsModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'product-tags';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Product Tags | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Product Tags | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_product_tag()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Tag Name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Tag Slug is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/producttag');
            }

            $slug_tags = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

            if($slug_tags){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $seo_og_image = $this->request->getFile('seo_og_image');

                // Move the file to the public/uploads directory
                if ($seo_og_image->isValid() && !$seo_og_image->hasMoved()) {

                    $seoOgImage = $seo_og_image->getRandomName();
                    $seo_og_image->move('uploads/category/seo', $seoOgImage);
                }

                
                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'slug' =>  $this->request->getPost('slug'),
                    'description' =>  $this->request->getPost('description'),
                    'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                    'seo_meta_keywords' =>  $this->request->getPost('seo_meta_keyword'),
                    'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                    'seo_og_image' => isset($seoOgImage) ? "uploads/tags/seo/".$seoOgImage : "",
                ];
    
                $inserted_id = $this->productMasterTagsTable->insert($data);
    
                if ($inserted_id) {

                    $slug_data = [
                        'type' =>  'product_tag',
                        'ref_id' =>  $inserted_id,
                        'slug' =>  $this->request->getPost('slug'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);
    
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Product tag Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/producttag');

        }else{
            return redirect()->to('/admin/producttag');
        }
    }
    
    public function manage_product_tag()
    {
        $data['main_page'] = TABLES . 'manage-product-tag';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Product Tags | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Product Tags | ' . $settings['app_name'];

        $data['product_tags_result'] = $this->productMasterTagsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function edit_product_tag()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-product-tag';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Product Tags | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Product Tags | ' . $settings['app_name'];

            $data['fetched_data'] = $this->productMasterTagsTable->where('id', $edit_id)->first();

            // dd($data);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/brand');
        }
    }

    public function update_product_tag()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Tag Name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Slug is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/producttag/edit_product_tag?edit_id='.$this->request->getPost('edit_ticket_type'));
            }

            $edit_id = $this->request->getPost('edit_product_tag');

            $exist_product_tag = $this->slugTable
                                    ->where('slug', $this->request->getPost('slug'))
                                    ->where('ref_id <>', $edit_id)
                                    ->first();

            if($exist_product_tag){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $seo_og_image = $this->request->getFile('seo_og_image');

                // Move the file to the public/uploads directory
                if ($seo_og_image->isValid() && !$seo_og_image->hasMoved()) {

                    $seoOgImage = $seo_og_image->getRandomName();
                    $seo_og_image->move('uploads/category/seo', $seoOgImage);
                }

                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'slug' =>  $this->request->getPost('slug'),
                    'description' =>  $this->request->getPost('description'),
                    'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                    'seo_meta_keywords' =>  $this->request->getPost('seo_meta_keyword'),
                    'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                ];
    
                // dd($data);
                $updated = $this->productMasterTagsTable->update($edit_id, $data);
    
                if ($updated) {

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'product_tag',
                        'ref_id' =>  $edit_id,
                        'slug' =>  $this->request->getPost('slug'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);  
    
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Product tag updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/producttag/edit_product_tag?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/producttag');
        }
    }
    
    public function update_product_tag_status($id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $updated = $this->productMasterTagsTable->update($id, $data);

        if ($updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Product Tags status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/producttag/manage_product_tag');
    }
    
}