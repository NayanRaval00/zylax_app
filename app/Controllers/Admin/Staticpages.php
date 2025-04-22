<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Pages as PagesModel;
use App\Models\UploadImages as UploadImagesModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Staticpages extends Controller
{

    public $pagesTable, $uploadImagesTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->pagesTable = new PagesModel();
        $this->uploadImagesTable = new UploadImagesModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-static-pages';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Static Pages Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Static Pages Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function create_page()
    {
        $data['main_page'] = FORMS . 'static-page';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Static Pages | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Static Pages , Create Static Pages | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_page()
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
                        'required'    => 'The title is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The slug is required!',
                    ]
                ],
                'menu_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The menu name is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                $session->setFlashdata('old_input', $this->request->getPost()); // Store old input
                return redirect()->to('/admin/staticpages/create_page')->withInput(); // Redirect with input
            }

            $slug_static_page = $this->slugTable->where('slug', $this->request->getPost('slug'))->first();

            if($slug_static_page){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $main_image = $this->request->getFile('image');

                // Move the file to the public/uploads directory
                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/static-page/images', $mainImage);
                }

                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'menu_name' =>  $this->request->getPost('menu_name'),
                    'slug' =>  $this->request->getPost('slug'),
                    'description' =>  $this->request->getPost('description'),
                    'image' => isset($mainImage) ? "uploads/static-page/images/".$mainImage : "",
                    'meta_title' =>  $this->request->getPost('meta_title'),
                    'meta_description' =>  $this->request->getPost('meta_description'),
                    'meta_keyword' =>  $this->request->getPost('meta_keyword'),
                    'active' =>  $this->request->getPost('active') == "1" ? 1 : 0,
                    'place_to' =>  $this->request->getPost('place_to'),
                    'page_script' =>  $this->request->getPost('page_script'),
                    'page_type' =>  $this->request->getPost('page_type')
                ];

                // dd($data);
                $page_added = $this->pagesTable->insert($data);

                if ($page_added) {

                    $slug_data = [
                        'type' =>  'static_page',
                        'ref_id' =>  $page_added,
                        'slug' =>  $this->request->getPost('slug'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Static Pages Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/staticpages/create_page');

        }else{
            return redirect()->to('/admin/staticpages/create_page');
        }
    }    

    public function edit_page()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-static-page';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Static Pages | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Static Pages , Create Static Pages | ' . $settings['app_name'];

            $data['fetched_data'] = $this->pagesTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/staticpages');
        }
    }

    public function update_page()
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
                        'required'    => 'The title is required!',
                    ]
                ],
                'slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The slug is required!',
                    ]
                ],
                'menu_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The menu name is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/staticpages/edit_page?edit_id='.$this->request->getPost('edit_page'));
            }

            $edit_page_id = $this->request->getPost('edit_page');

            $exist_slugs = $this->slugTable
                                ->where('slug', $this->request->getPost('slug'))
                                ->where('ref_id <>', $edit_page_id)
                                ->first();

            if($exist_slugs){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $main_image = $this->request->getFile('image');

                // Move the file to the public/uploads directory
                if ($main_image != "" && $main_image->isValid() && !$main_image->hasMoved()) {

                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/static-page/images', $mainImage);
                }

                $data = [
                    'name' =>  $this->request->getPost('name'),
                    'menu_name' =>  $this->request->getPost('menu_name'),
                    'slug' =>  $this->request->getPost('slug'),
                    'description' =>  $this->request->getPost('description'),
                    'image' => isset($mainImage) ? "uploads/static-page/images/".$mainImage : $this->request->getPost('page_input_image'),
                    'meta_title' =>  $this->request->getPost('meta_title'),
                    'meta_description' =>  $this->request->getPost('meta_description'),
                    'meta_keyword' =>  $this->request->getPost('meta_keyword'),
                    'active' =>  $this->request->getPost('active') == "1" ? 1 : 0,
                    'place_to' =>  $this->request->getPost('place_to'),
                    'page_script' =>  $this->request->getPost('page_script'),
                    'page_type' =>  $this->request->getPost('page_type')
                ];

                // dd($data);
                $updated = $this->pagesTable->update($edit_page_id, $data);

                if ($updated) {

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'static_page',
                        'ref_id' =>  $edit_page_id,
                        'slug' =>  $this->request->getPost('slug'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);                                        

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Static Pages updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
                
            }             

            // Validation passed, process the form data
            return redirect()->to('/admin/staticpages/edit_page?edit_id='.$edit_page_id);

        }else{
            return redirect()->to('/admin/staticpages');
        }
    }

    public function fetchStaticPages()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->pagesTable;

        if (!empty($searchValue)) {
            $query->like('name', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $static_pages = $query->limit($length, $start)->find();

        $data = [];
        foreach ($static_pages as $page) {

            if($page['active'] == 1){
                $status = '<a class="badge badge-success text-white">Active</a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Inactive</a>';
            }

            $data[] = [
                $page['id'],
                $page['name'],
                $page['slug'],
                $page['page_type'],
                $status,
                '<a href="'.base_url('admin/staticpages/edit_page?edit_id='.$page['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/category/create_category"><i class="fa fa-pen"></i></a>
                 <a class="delete-page btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$page['id'].'">
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

    public function delete_page()
    {
        $page_id = $this->request->getGet('id');
        $page_deleted = $this->pagesTable->delete($page_id);

        if ($page_deleted) {
           $response['error'] = false;
           $response['message'] = 'Page Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Page not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    // uploads images
    public function upload_images()
    {
        $data['main_page'] = TABLES . 'manage-static-page-images';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Static Pages Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Static Pages Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function upload_new_image()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

     

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
        
            $main_image = $this->request->getFile('image');

            // Move the file to the public/uploads directory
            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $mainImage = $main_image->getRandomName();
                $main_image->move('uploads/editor-image/images', $mainImage);
            }

            $data = [
                'image' => isset($mainImage) ? "uploads/editor-image/images/".$mainImage : "",
            ];

            // dd($data);
            $image_added = $this->uploadImagesTable->insert($data);

            if ($image_added) {
                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Image Uploaded Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }


            // Validation passed, process the form data
            return redirect()->to('/admin/staticpages/upload_images');

        }else{
            return redirect()->to('/admin/staticpages/upload_images');
        }
    }    

    public function fetchUploadImages()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->uploadImagesTable;

        if (!empty($searchValue)) {
            $query->like('name', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $upload_images = $query->limit($length, $start)->find();

        $data = [];
        foreach ($upload_images as $image) {

            $data[] = [
                $image['id'],
                '<div class="image-box-100">
                    <a href="'.base_url().$image['image'].'"
                        data-toggle="lightbox">
                        <img class="rounded" src="'.base_url().$image['image'].'">
                    </a>
                </div>',
                '<a class="copy-link btn btn-primary mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-url="'.base_url().$image['image'].'">
                    Copy Link
                </a>
                <a class="delete-image btn btn-danger mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$image['id'].'">
                    Delete
                </a>'
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }

    public function delete_image()
    {
        $image_id = $this->request->getGet('id');
        $image_deleted = $this->uploadImagesTable->delete($image_id);

        if ($image_deleted) {
           $response['error'] = false;
           $response['message'] = 'Image Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Image not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    
}