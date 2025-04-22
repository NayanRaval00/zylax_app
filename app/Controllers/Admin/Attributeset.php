<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\AttributeSet as AttributeSetModel;
use App\Models\AttributeSetCategory as AttributeSetCategoryModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Attributeset extends Controller
{

    public $attributeSetTable, $attributeSetCategoryTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->attributeSetTable = new AttributeSetModel();
        $this->attributeSetCategoryTable = new AttributeSetCategoryModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'attribute-set';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Attribute Set | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Attribute Set | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_attribute_set()
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
                        'required'    => 'The name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The slug is required!',
                    ]
                ],
                'category' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The category is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                $session->setFlashdata('old_input', $this->request->getPost()); // Store old input
                return redirect()->to('/admin/attributeset')->withInput(); // Redirect with input
            }

            $slug_attribute = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

            if($slug_attribute){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'slug' =>  $this->request->getPost('slug'),
                ];
    
                $inserted_id = $this->attributeSetTable->insert($data);
    
                if ($inserted_id) {

                    $slug_data = [
                        'type' =>  'attribute_set',
                        'ref_id' =>  $inserted_id,
                        'slug' =>  $this->request->getPost('slug'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);
    
                    $categories = $this->request->getPost('category');
                    if (isset($categories)) {
                        foreach ($categories as $category) {
                            $assign_attribute_set = [
                                'attribute_set_id' =>  $inserted_id,
                                'category_id' =>  $category,
                            ];
                            $assign_inserted_id = $this->attributeSetCategoryTable->insert($assign_attribute_set);
                        }
                    }
    
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Attribute set Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/attributeset');

        }else{
            return redirect()->to('/admin/attributeset');
        }
    }
    
    public function manage_attribute_set()
    {
        $data['main_page'] = TABLES . 'manage-attribute-set';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Attribute Set | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Attribute Set | ' . $settings['app_name'];

        $data['attribute_set_result'] = $this->attributeSetTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function edit_attribute_set()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-attribute-set';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Attribute Set | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Attribute Set | ' . $settings['app_name'];

            $data['fetched_data'] = $this->attributeSetTable->where('id', $edit_id)->first();
            $selected_categories = $this->attributeSetCategoryTable->where('attribute_set_id', $edit_id)->findAll();

            $category_list = "";
            foreach ($selected_categories as $category) {
                $category_list .= $category['category_id'] . ",";
            }

            $data['selected_categories'] = $category_list;

            // dd($data);
            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/brand');
        }
    }

    public function update_attribute_set()
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
                        'required'    => 'The name is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/attributeset/edit_attribute_set?edit_id='.$this->request->getPost('edit_ticket_type'));
            }

            $edit_id = $this->request->getPost('edit_attribute_set');

            $exist_attribute_set = $this->slugTable
                                    ->where('slug', $this->request->getPost('slug'))
                                    ->where('ref_id <>', $edit_id)
                                    ->first();

            if($exist_attribute_set){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $edit_attribute_set_category = rtrim($this->request->getPost('edit_attribute_set_category'), ",");;
                $existing_attribute_set_categories_array = explode(',', $edit_attribute_set_category);
                $new_categories = $this->request->getPost('category');
                $difference_categories = array_diff($existing_attribute_set_categories_array, $new_categories);
    
                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'slug' =>  $this->request->getPost('slug'),
                ];
    
                // dd($data);
                $updated = $this->attributeSetTable->update($edit_id, $data);
    
                if ($updated) {

                    // update if exist or new add
                       $slug_data = [
                        'type' =>  'attribute_set',
                        'ref_id' =>  $edit_id,
                        'slug' =>  $this->request->getPost('slug'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);
    
                    // delete removed category first
                    foreach ($difference_categories as $category) {
                        $this->attributeSetCategoryTable
                                ->where('attribute_set_id', $edit_id)
                                ->where('category_id', $category)
                                ->delete();
                    }
    
                    // add/update new category
                    foreach ($new_categories as $category) {
    
                        $exist_category_update = $this->attributeSetCategoryTable
                                                        ->where('attribute_set_id', $this->request->getPost('edit_attribute_set'))
                                                        ->where('category_id', $category)
                                                        ->first();
                                                
                        if(isset($exist_category_update['id']) && !empty($exist_category_update['id'])){
    
                            $attribute_set_category_id = $exist_category_update['id'];
    
                            $existing_assign_attribute_set_category_price = [
                                'attribute_set_id' =>  $this->request->getPost('edit_attribute_set'),
                                'category_id' =>  $category,
                            ];
                            $updated = $this->attributeSetCategoryTable->update($attribute_set_category_id, $existing_assign_attribute_set_category_price);
    
                        }else{
                            $assign_shipping_category_price = [
                                'attribute_set_id' =>  $this->request->getPost('edit_attribute_set'),
                                'category_id' =>  $category,
                            ];
                            $assign_inserted_id = $this->attributeSetCategoryTable->insert($assign_shipping_category_price);
                        }
    
                    }
    
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Attribute set updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/attributeset/edit_attribute_set?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/attributeset');
        }
    }
    
    public function update_attribute_set_status($id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $brand_updated = $this->attributeSetTable->update($id, $data);

        if ($brand_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Attribute Set status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/attributeset/manage_attribute_set');
    }
    
}