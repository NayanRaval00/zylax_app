<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Offers as OffersModel;
use App\Models\Categories as CategoriesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Offer extends Controller
{

    public $offersTable, $categoryTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->offersTable = new OffersModel();
        $this->categoryTable = new CategoriesModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-offers';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Offer Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Offer Management | ' . $settings['app_name'];

        $data['offer_result'] = $this->offersTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_offer()
    {
        $data['main_page'] = FORMS . 'offers';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Offer | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Offer | ' . $settings['app_name'];

        $data['categories'] = $this->categoryTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function add_offer()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'offer_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Offer Type is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/offer/create_offer');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                    // Move the file to the public/uploads directory
                $mainImage = $main_image->getRandomName();
                $main_image->move('uploads/offers', $mainImage);

                $data = [
                    'type' =>  $this->request->getPost('offer_type'),
                    'type_id' =>  $this->request->getPost('category_id') ? $this->request->getPost('category_id') : 0,
                    'image' =>  "uploads/offers/".$mainImage,
                ];

                // dd($data);
                $slider_added = $this->offersTable->insert($data);

                if ($slider_added) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Offer Added Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Offer Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/offer/create_offer');

        }else{
            return redirect()->to('/admin/offer/create_offer');
        }
    }

    public function delete_offer()
    {
        $offer_id = $this->request->getGet('id');
        $deleted = $this->offersTable->delete($offer_id);

        if ($deleted) {
           $response['error'] = false;
           $response['message'] = 'Offer Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Offer not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    

    public function edit_offer()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-offer';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Offer | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Offer | ' . $settings['app_name'];

            $data['fetched_data'] = $this->offersTable->where('id', $edit_id)->first();

            $data['categories'] = $this->categoryTable
            ->orderBy('id', 'DESC')
            ->findAll();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/offer');
        }
    }

    public function update_offer()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'offer_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Offer Type is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/offer/edit_offer?edit_id='.$this->request->getPost('edit_offer'));
            }

            $edit_slider_id = $this->request->getPost('edit_offer');

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/offers', $mainImage);

                    $data = [
                        'type' =>  $this->request->getPost('offer_type'),
                        'type_id' =>  ($this->request->getPost('offer_type') == 'categories' ) ? $this->request->getPost('category_id') : 0,
                        'image' =>  "uploads/offers/".$mainImage,
                    ];

                    // dd($data);
                    $updated = $this->offersTable->update($edit_slider_id, $data);

                    if ($updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Offer updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }

                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Offer Main Image is required!');
                }

            }else{

                $data = [
                    'type' =>  $this->request->getPost('offer_type'),
                    'type_id' =>  ($this->request->getPost('offer_type') == 'categories' ) ? $this->request->getPost('category_id') : 0,
                ];

                // dd($data);
                $updated = $this->offersTable->update($edit_slider_id, $data);

                if ($updated) {
                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Offer updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }         

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/offer/edit_offer?edit_id='.$edit_slider_id);

        }else{
            return redirect()->to('/admin/offer');
        }
    }
    
}