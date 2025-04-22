<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Brands as BrandsModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Brand extends Controller
{

    public $brandsTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->brandsTable = new BrandsModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-brands';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Brand Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Brand Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function create_brand()
    {
        $data['main_page'] = FORMS . 'brand';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Brand | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Brand , Create Brand | ' . $settings['app_name'];

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
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Brand Slug is required!',
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
            $icon = $this->request->getFile('icon');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $slug_category = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

                if($slug_category){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Slug Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/brands', $mainImage);

                    // Move the file to the public/uploads directory
                    if ($icon->isValid() && !$icon->hasMoved()) {

                        $iconImage = $icon->getRandomName();
                        $icon->move('uploads/brand/icon', $iconImage);
                    }

                    $data = [
                        'name' =>  $this->request->getPost('brand_input_name'),
                        // 'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                        'slug' =>  $this->request->getPost('slug'),
                        'image' =>  "uploads/brands/".$mainImage,
                        'icon' => isset($iconImage) ? "uploads/brand/icon/".$iconImage : "",
                        'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                        'description' =>  $this->request->getPost('description'),
                        'status' =>  1,
                    ];

                    // dd($data);
                    $brand_added = $this->brandsTable->insert($data);

                    if ($brand_added) {

                        $slug_data = [
                            'type' =>  'brand',
                            'ref_id' =>  $brand_added,
                            'slug' =>  $this->request->getPost('slug'),
                        ];

                        $slug_added = $this->slugTable->insert($slug_data);

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
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Brand Slug is required!',
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
            $icon = $this->request->getFile('icon');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_brand = $this->brandsTable
                    ->where('slug', $this->request->getPost('slug'))
                    ->where('id <>', $edit_brand_id)
                    ->first();

                    if($exist_brand){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Brand Already exist you should use a different name');
                    }else{

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/brands', $mainImage);

                         // Move the file to the public/uploads directory
                        if ($icon != "" && $icon->isValid() && !$icon->hasMoved()) {

                            $iconImage = $icon->getRandomName();
                            $icon->move('uploads/brand/icon', $iconImage);
                        }

                        $data = [
                            'name' =>  $this->request->getPost('brand_input_name'),
                            // 'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                            'slug' =>  $this->request->getPost('slug'),
                            'image' =>  "uploads/brands/".$mainImage,
                            'icon' => isset($iconImage) ? "uploads/brand/icon/".$iconImage : $this->request->getPost('brand_icon_image'),
                            'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                            'description' =>  $this->request->getPost('description'),
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

                $exist_brand = $this->slugTable
                                    ->where('slug', $this->request->getPost('slug'))
                                    ->where('ref_id <>', $edit_brand_id)
                                    ->first();

                if($exist_brand){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Slug Already exist you should use a different name');
                }else{

                    // Move the file to the public/uploads directory
                    if ($icon != "" && $icon->isValid() && !$icon->hasMoved()) {

                        $iconImage = $icon->getRandomName();
                        $icon->move('uploads/brand/icon', $iconImage);
                    }

                    $data = [
                        'name' =>  $this->request->getPost('brand_input_name'),
                        // 'slug' =>  url_title($this->request->getPost('brand_input_name'), '-', true),
                        'slug' =>  $this->request->getPost('slug'),
                        'icon' => isset($iconImage) ? "uploads/brand/icon/".$iconImage : $this->request->getPost('brand_icon_image'),
                        'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                        'description' =>  $this->request->getPost('description'),
                    ];

                    // dd($data);
                    $updated = $this->brandsTable->update($edit_brand_id, $data);

                    if ($updated) {

                        // update if exist or new add
                        $slug_data = [
                            'type' =>  'brand',
                            'ref_id' =>  $edit_brand_id,
                            'slug' =>  $this->request->getPost('slug'),
                        ];
                        $findOrCreate = $this->slugTable->findOrCreate($slug_data);                                        

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

    public function fetchBrands()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->brandsTable;

        if (!empty($searchValue)) {
            $query->like('name', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $brands = $query->limit($length, $start)->find();

        $data = [];
        foreach ($brands as $brand) {

            if($brand['status'] == 1){
                $status = '<a class="badge badge-success text-white">Active</a>';
                $change_status = '<a class="btn btn-warning action-btn btn-xs update_active_status ml-1 mr-1 mb-1" data-table="categories" title="Deactivate" href="'.base_url('admin/brand/update_brand_status/'.$brand['id'].'/0').'">
                                    <i class="fa fa-eye-slash"></i>
                                </a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Inactive</a>';
                $change_status = '<a class="btn btn-primary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="categories" href="'.base_url('admin/brand/update_brand_status/'.$brand['id'].'/1').'" title="Active">
                                    <i class="fa fa-eye"></i>
                                </a> ';
            }

            $data[] = [
                $brand['name'],
                '<div class="image-box-100">
                    <a href="'.base_url().$brand['image'].'"
                        data-toggle="lightbox">
                        <img class="rounded" src="'.base_url().$brand['image'].'">
                    </a>
                </div>',
                $status,
                '<a href="'.base_url('admin/brand/edit_brand?edit_id='.$brand['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/category/create_category"><i class="fa fa-pen"></i></a>

                <a class="delete-brand btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$brand['id'].'">
                    <i class="fa fa-trash"></i>
                </a>

                '. $change_status
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