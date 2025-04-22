<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\BlogCategories as BlogCategoriesModel;
use App\Models\Blogs as BlogsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Blogs extends Controller
{
    public $blogCategoriesTable, $blogsTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->blogCategoriesTable = new BlogCategoriesModel();
        $this->blogsTable = new BlogsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-categories';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Category Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Category Management | ' . $settings['app_name'];

        $data['category_result'] = $this->blogCategoriesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_category()
    {

        $data['main_page'] = FORMS . 'blogs_category';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Category | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Category , Create Category | ' . $settings['app_name'];

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
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/blogs/create_category');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $exist_category = $this->blogCategoriesTable->where('name', $this->request->getPost('category_input_name'))->first();

                if($exist_category){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Category Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/blogs/category', $mainImage);

                    $data = [
                        'name' =>  $this->request->getPost('category_input_name'),
                        'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                        'image' =>  "uploads/blogs/category/".$mainImage,
                        'status' =>  1,
                    ];

                    // dd($data);
                    $category_added = $this->blogCategoriesTable->insert($data);

                    if ($category_added) {
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
            return redirect()->to('/admin/blogs/create_category');

        }else{
            return redirect()->to('/admin/blogs/create_category');
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

        $category_updated = $this->blogCategoriesTable->update($category_id, $data);

        if ($category_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Category status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/blogs');
    }

    public function delete_category()
    {
        $category_id = $this->request->getGet('id');
        $category_deleted = $this->blogCategoriesTable->delete($category_id);

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

    public function edit_category()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit_blogs_category';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Category | ' . $settings['app_name'] : 'Add Category | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Category , Create Category | ' . $settings['app_name'];

            $data['fetched_data'] = $this->blogCategoriesTable->where('id', $edit_id)->first();

            // dd($data);

            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/blogs');
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
            ];

            $edit_category_id = $this->request->getPost('edit_category');

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/blogs/edit_category?edit_id='.$edit_category_id);
            }

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_category = $this->blogCategoriesTable
                    ->where('name', $this->request->getPost('category_input_name'))
                    ->where('id <>', $edit_category_id)
                    ->first();

                    if($exist_category){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Category Already exist you should use a different name');
                    }else{

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/blogs/category', $mainImage);

                        $data = [
                            'name' =>  $this->request->getPost('category_input_name'),
                            'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                            'image' =>  "uploads/blogs/category/".$mainImage,
                        ];

                        // dd($data);
                        $category_updated = $this->blogCategoriesTable->update($edit_category_id, $data);

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

                $exist_category = $this->blogCategoriesTable
                ->where('name', $this->request->getPost('category_input_name'))
                ->where('id <>', $edit_category_id)
                ->first();

                if($exist_category){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Category Already exist you should use a different name');
                }else{

                    $data = [
                        'name' =>  $this->request->getPost('category_input_name'),
                        'slug' =>  url_title($this->request->getPost('category_input_name'), '-', true),
                    ];

                    // dd($data);
                    $category_updated = $this->blogCategoriesTable->update($edit_category_id, $data);

                    if ($category_updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Category updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    
                }             

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/blogs/edit_category?edit_id='.$edit_category_id);

        }else{
            return redirect()->to('/admin/blogs');
        }
    }

    /******* Blogs Manage  ******/

    public function manage_blogs()
    {

        $data['main_page'] = TABLES . 'manage-blogs';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Blogs | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Blogs | ' . $settings['app_name'];

        $data['blogs_result'] = $this->blogsTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_blog()
    {

        $data['main_page'] = FORMS . 'blogs';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Blog | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Blog | ' . $settings['app_name'];

        $data['categories'] = $this->blogCategoriesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_blog()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Blog Title is required!',
                    ]
                ],
                'blog_category' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Blog Category is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/blogs/create_blog');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $exist_blog = $this->blogsTable->where('title', $this->request->getPost('blog_title'))->first();

                if($exist_blog){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Blog Title Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/blogs', $mainImage);

                    $data = [
                        'category_id' =>  $this->request->getPost('blog_category'),
                        'title' =>  $this->request->getPost('blog_title'),
                        'description' =>  $this->request->getPost('blog_description'),
                        'image' =>  "uploads/blogs/".$mainImage,
                        'slug' =>  url_title($this->request->getPost('blog_title'), '-', true),
                        'status' =>  1,
                    ];

                    // dd($data);
                    $blog_added = $this->blogsTable->insert($data);

                    if ($blog_added) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Blog added Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }                    

                }
                
            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Blog Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/blogs/create_blog');

        }else{
            return redirect()->to('/admin/blogs/create_blog');
        }
    }

    public function update_blog_status($blog_id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $blog_updated = $this->blogsTable->update($blog_id, $data);

        if ($blog_updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Blog status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/blogs/manage_blogs');
    }

    public function delete_blog()
    {
        $blog_id = $this->request->getGet('id');
        $blog_deleted = $this->blogsTable->delete($blog_id);

        if ($blog_deleted) {
           $response['error'] = false;
           $response['message'] = 'Blog Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Blog not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function edit_blog()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-blogs';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Blog | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Blog | ' . $settings['app_name'];

            $data['categories'] = $this->blogCategoriesTable
            ->orderBy('id', 'DESC')
            ->findAll();

            $data['fetched_data'] = $this->blogsTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/blogs');
        }
    }

    public function update_blog()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Blog Title is required!',
                    ]
                ],
                'blog_category' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Blog Category is required!',
                    ]
                ],
            ];

            $edit_blog_id = $this->request->getPost('edit_blog');

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/blogs/edit_blog?edit_id='.$edit_blog_id);
            }

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_category = $this->blogsTable
                    ->where('title', $this->request->getPost('blog_title'))
                    ->where('id <>', $edit_blog_id)
                    ->first();

                    if($exist_category){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Blog Already exist you should use a different name');
                    }else{

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/blogs', $mainImage);

                        $data = [
                            'category_id' =>  $this->request->getPost('blog_category'),
                            'title' =>  $this->request->getPost('blog_title'),
                            'description' =>  $this->request->getPost('blog_description'),
                            'image' =>  "uploads/blogs/".$mainImage,
                            'slug' =>  url_title($this->request->getPost('blog_title'), '-', true),
                        ];

                        // dd($data);
                        $category_updated = $this->blogsTable->update($edit_blog_id, $data);

                        if ($category_updated) {
                            $session->setFlashdata('status', 'success');
                            $session->setFlashdata('message', 'Blog updated Successful!');
                        } else {
                            $session->setFlashdata('status', 'error');
                            $session->setFlashdata('message', 'Something went wrong');
                        }
                        

                    }
                    
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Blog Main Image is required!');
                }

            }else{

                $exist_blog = $this->blogsTable
                ->where('title', $this->request->getPost('blog_title'))
                ->where('id <>', $edit_blog_id)
                ->first();

                if($exist_blog){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Blog Already exist you should use a different name');
                }else{

                    $data = [
                        'category_id' =>  $this->request->getPost('blog_category'),
                        'title' =>  $this->request->getPost('blog_title'),
                        'description' =>  $this->request->getPost('blog_description'),
                        'slug' =>  url_title($this->request->getPost('blog_title'), '-', true),
                    ];

                    // dd($data);
                    $blog_updated = $this->blogsTable->update($edit_blog_id, $data);

                    if ($blog_updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Blog updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    
                }             

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/blogs/edit_blog?edit_id='.$edit_blog_id);

        }else{
            return redirect()->to('/admin/blogs');
        }
    }
    
    
}