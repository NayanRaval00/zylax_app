<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Sliders as SlidersModel;
use App\Models\Categories as CategoriesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\SliderCategory as SliderCategory;



class Slider extends Controller
{

    public $slidersTable, $categoryTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->slidersTable = new SlidersModel();
        $this->categoryTable = new CategoriesModel();
        $this->SliderCategory = new SliderCategory();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-slider';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Slider Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Slider Management | ' . $settings['app_name'];

        $data['slider_result'] = $this->slidersTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_slider()
    {
        $data['main_page'] = FORMS . 'slider';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Slider | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Slider | ' . $settings['app_name'];

        $data['categories'] = $this->SliderCategory
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_slider()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'slider_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Slider Type is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/slider/create_slider');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                    // Move the file to the public/uploads directory
                $mainImage = $main_image->getRandomName();
                $main_image->move('uploads/sliders', $mainImage);

                $data = [
                    'type' =>  $this->request->getPost('slider_type'),
                    'type_id' =>  $this->request->getPost('category_id') ? $this->request->getPost('category_id') : 0,
                    'image' =>  "uploads/sliders/".$mainImage,
                    'link'  => $this->request->getPost('url'),
                ];

                // dd($data);
                $slider_added = $this->slidersTable->insert($data);

                if ($slider_added) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Slider Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Slider Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/slider/create_slider');

        }else{
            return redirect()->to('/admin/slider/create_slider');
        }
    }

    public function delete_slider()
    {
        $slider_id = $this->request->getGet('id');
        $deleted = $this->slidersTable->delete($slider_id);

        if ($deleted) {
           $response['error'] = false;
           $response['message'] = 'Slider Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Slider not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    

    public function edit_slider()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-slider';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Slider | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Slider | ' . $settings['app_name'];

            $data['fetched_data'] = $this->slidersTable->where('id', $edit_id)->first();

            $data['categories'] = $this->SliderCategory
            ->orderBy('id', 'DESC')
            ->findAll();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/slider');
        }
    }

    public function update_slider()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'slider_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Slider Type is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/slider/edit_slider?edit_id='.$this->request->getPost('edit_slider'));
            }

            $edit_slider_id = $this->request->getPost('edit_slider');

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/sliders', $mainImage);

                    $data = [
                        'type' =>  $this->request->getPost('slider_type'),
                        'type_id' =>  ($this->request->getPost('slider_type') == 'categories' ) ? $this->request->getPost('category_id') : 0,
                        'image' =>  "uploads/sliders/".$mainImage,
                        'link'  => $this->request->getPost('url'),
                    ];
   
                    $updated = $this->slidersTable->update($edit_slider_id, $data);

                    if ($updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Slider updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }

                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Slider Main Image is required!');
                }

            }else{

                $data = [
                    'type' =>  $this->request->getPost('slider_type'),
                    'type_id' =>  ($this->request->getPost('slider_type') == 'categories' ) ? $this->request->getPost('category_id') : 0,
                    'link'  => $this->request->getPost('url'),
                ];

                // dd($data);
                $updated = $this->slidersTable->update($edit_slider_id, $data);

                if ($updated) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Slider updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }         

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/slider/edit_slider?edit_id='.$edit_slider_id);

        }else{
            return redirect()->to('/admin/slider');
        }
    }
    
}