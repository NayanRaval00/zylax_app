<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Categories as CategoriesModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Category extends Controller
{
    public $categoryTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->categoryTable = new CategoriesModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-category';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Category Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Category Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function create_category()
    {

        $data['main_page'] = FORMS . 'category';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Category | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Category , Create Category | ' . $settings['app_name'];

        $data['categories'] = $this->categoryTable
        // ->where('parent_id', 0)
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_category()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'category_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category Name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category Slug is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/category/create_category');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');
            $banner = $this->request->getFile('banner');
            $icon = $this->request->getFile('icon');
            $seo_og_image = $this->request->getFile('seo_og_image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $slug_category = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

                if($slug_category){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Slug Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/category', $mainImage);

                    // Move the file to the public/uploads directory
                    if ($banner->isValid() && !$banner->hasMoved()) {

                        $bannerImage = $banner->getRandomName();
                        $banner->move('uploads/category/banner', $bannerImage);
                    }

                    // Move the file to the public/uploads directory
                    if ($icon->isValid() && !$icon->hasMoved()) {

                        $iconImage = $icon->getRandomName();
                        $icon->move('uploads/category/icon', $iconImage);
                    }

                    // Move the file to the public/uploads directory
                    if ($seo_og_image->isValid() && !$seo_og_image->hasMoved()) {

                        $seoOgImage = $seo_og_image->getRandomName();
                        $seo_og_image->move('uploads/category/seo', $seoOgImage);
                    }

                    $data = [
                        'name' =>  $this->request->getPost('category_input_name'),
                        'parent_id' =>  $this->request->getPost('category_parent'),
                        // 'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                        'slug' =>  $this->request->getPost('slug'),
                        'image' =>  "uploads/category/".$mainImage,
                        'icon' => isset($iconImage) ? "uploads/category/icon/".$iconImage : "",
                        'banner' => isset($bannerImage) ? "uploads/category/banner/".$bannerImage : "",
                        'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                        'description' =>  $this->request->getPost('description'),
                        'status' =>  1,
                        'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                        'seo_meta_keywords' =>  $this->request->getPost('seo_meta_keyword'),
                        'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                        'seo_og_image' => isset($seoOgImage) ? "uploads/category/seo/".$seoOgImage : "",
                    ];

                    // dd($data);
                    $category_added = $this->categoryTable->insert($data);

                    if ($category_added) {

                        $slug_data = [
                            'type' =>  'category',
                            'ref_id' =>  $category_added,
                            'slug' =>  $this->request->getPost('slug'),
                        ];

                        $slug_added = $this->slugTable->insert($slug_data);

                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Category added Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    

                }
                
            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Category Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/category/create_category');

        }else{
            return redirect()->to('/admin/category/create_category');
        }
    }

    public function edit_category()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-category';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Category | ' . $settings['app_name'] : 'Add Category | ' . $settings['app_name'];
            $data['meta_description'] = 'Add Category , Create Category | ' . $settings['app_name'];

            $data['fetched_data'] = $this->categoryTable->where('id', $edit_id)->first();

            // dd($data);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/category');
        }

    }

    public function update_category()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'category_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category Name is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category Slug is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/category/create_category');
            }

            $edit_category_id = $this->request->getPost('edit_category');

            // echo $this->request->getPost('is_show'); exit;

            // Get uploaded file
            $main_image = $this->request->getFile('image');
            $banner = $this->request->getFile('banner');
            $icon = $this->request->getFile('icon');
            $seo_og_image = $this->request->getFile('seo_og_image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_category = $this->categoryTable
                    ->where('type', 'category')
                    ->where('slug', $this->request->getPost('slug'))
                    ->where('ref_id <>', $edit_category_id)
                    ->first();

                    if($exist_category){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Slug Already exist you should use a different name');
                    }else{

                         // Move the file to the public/uploads directory
                        if ($banner != "" && $banner->isValid() && !$banner->hasMoved()) {

                            $bannermage = $banner->getRandomName();
                            $banner->move('uploads/category/banner', $bannermage);
                        }

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/category', $mainImage);

                        // Move the file to the public/uploads directory
                        if ($icon != "" && $icon->isValid() && !$icon->hasMoved()) {

                            $iconImage = $icon->getRandomName();
                            $icon->move('uploads/category/icon', $iconImage);
                        }

                        // Move the file to the public/uploads directory
                        if ($seo_og_image != "" && $seo_og_image->isValid() && !$seo_og_image->hasMoved()) {

                            $seoOgImage = $seo_og_image->getRandomName();
                            $seo_og_image->move('uploads/category/seo', $seoOgImage);
                        }

                        $data = [
                            'name' =>  $this->request->getPost('category_input_name'),
                            'parent_id' =>  $this->request->getPost('category_parent'),
                            // 'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                            'slug' =>  $this->request->getPost('slug'),
                            'image' =>  "uploads/category/".$mainImage,
                            'icon' => isset($iconImage) ? "uploads/category/icon/".$iconImage : $this->request->getPost('category_icon_image'),
                            'banner' => isset($bannermage) ? "uploads/category/banner/".$bannermage : $this->request->getPost('category_input_banner'),
                            'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                            'description' =>  $this->request->getPost('description'),
                            'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                            'seo_meta_keywords' =>  $this->request->getPost('seo_meta_keyword'),
                            'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                            // 'seo_og_image' => isset($seoOgImage) ? "uploads/category/seo/".$seoOgImage : $exist_category['seo_og_image'],
                        ];

                        // dd($data);
                        $category_updated = $this->categoryTable->update($edit_category_id, $data);

                        if ($category_updated) {
                            $session->setFlashdata('status', 'success');
                            $session->setFlashdata('message', 'Category updated Successful!');
                        } else {
                            $session->setFlashdata('status', 'error');
                            $session->setFlashdata('message', 'Something went wrong');
                        }
                        

                    }
                    
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Category Main Image is required!');
                }

            }else{

                $exist_category = $this->slugTable
                                        ->where('slug', $this->request->getPost('slug'))
                                        ->where('ref_id <>', $edit_category_id)
                                        ->first();

                if($exist_category){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Slug Already exist you should use a different name');
                }else{

                    // Move the file to the public/uploads directory
                    if ($banner != "" && $banner->isValid() && !$banner->hasMoved()) {

                        $bannermage = $banner->getRandomName();
                        $banner->move('uploads/category/banner', $bannermage);
                    }

                    // Move the file to the public/uploads directory
                    if ($icon != "" && $icon->isValid() && !$icon->hasMoved()) {

                        $iconImage = $icon->getRandomName();
                        $icon->move('uploads/category/icon', $iconImage);
                    }

                    // Move the file to the public/uploads directory
                    if ($seo_og_image != "" && $seo_og_image->isValid() && !$seo_og_image->hasMoved()) {
                        $seoOgImage = $seo_og_image->getRandomName();
                        $seo_og_image->move('uploads/category/seo', $seoOgImage);
                    }else{
                        $seoOgImage = "";
                    }

                    $data = [
                        'name' =>  $this->request->getPost('category_input_name'),
                        'parent_id' =>  $this->request->getPost('category_parent'),
                        // 'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                        'slug' =>  $this->request->getPost('slug'),
                        'icon' => isset($iconImage) ? "uploads/category/icon/".$iconImage : $this->request->getPost('category_icon_image'),
                        'banner' => isset($bannermage) ? "uploads/category/banner/".$bannermage : $this->request->getPost('category_input_banner'),
                        'is_show' =>  $this->request->getPost('is_show') ? 1 : 0,
                        'description' =>  $this->request->getPost('description'),
                        'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                        'seo_meta_keywords' =>  $this->request->getPost('seo_meta_keyword'),
                        'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                        'seo_og_image' => isset($seoOgImage) ? "uploads/category/seo/".$seoOgImage : $this->request->getPost('category_seo_image'),
                    ];

                    // dd($data);
                    $category_updated = $this->categoryTable->update($edit_category_id, $data);

                    if ($category_updated) {

                       // update if exist or new add
                       $slug_data = [
                            'type' =>  'category',
                            'ref_id' =>  $edit_category_id,
                            'slug' =>  $this->request->getPost('slug'),
                        ];
                        $findOrCreate = $this->slugTable->findOrCreate($slug_data);

                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Category updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    
                }             

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/category/edit_category?edit_id='.$edit_category_id);

        }else{
            return redirect()->to('/admin/category');
        }
    }
    
    public function update_category_status($category_id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $category_updated = $this->categoryTable->update($category_id, $data);

        if ($category_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Category status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/category');
    }

    public function delete_category()
    {
        $category_id = $this->request->getGet('id');
        $category_deleted = $this->categoryTable->delete($category_id);

        if ($category_deleted) {
           $response['error'] = false;
           $response['message'] = 'Category Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Category not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function fetchCategories()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->categoryTable;

        if (!empty($searchValue)) {
            $query->like('name', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $categories = $query->limit($length, $start)->find();

        $data = [];
        foreach ($categories as $category) {

            if($category['status'] == 1){
                $status = '<a class="badge badge-success text-white">Active</a>';
                $change_status = '<a class="btn btn-warning action-btn btn-xs update_active_status ml-1 mr-1 mb-1" data-table="categories" title="Deactivate" href="'.base_url('admin/category/update_category_status/'.$category['id'].'/0').'">
                                    <i class="fa fa-eye-slash"></i>
                                </a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Inactive</a>';
                $change_status = '<a class="btn btn-primary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="categories" href="'.base_url('admin/category/update_category_status/'.$category['id'].'/1').'" title="Active">
                                    <i class="fa fa-eye"></i>
                                </a> ';
            }

            $data[] = [
                $category['name'],
                '<div class="image-box-100">
                    <a href="'.base_url().$category['image'].'"
                        data-toggle="lightbox">
                        <img class="rounded" src="'.base_url().$category['image'].'">
                    </a>
                </div>',
                $status,
                '<a href="'.base_url('admin/category/edit_category?edit_id='.$category['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/category/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-category btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$category['id'].'"> <i class="fa fa-trash"></i> </a>
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