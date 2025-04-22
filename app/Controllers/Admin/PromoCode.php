<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\PromoCodes as PromoCodesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class PromoCode extends Controller
{

    public $promoCodesTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->promoCodesTable = new PromoCodesModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-promo-code';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Promo Code Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Promo Code Management | ' . $settings['app_name'];

        $data['promo_result'] = $this->promoCodesTable
        ->orderBy('id', 'DESC')
        ->findAll();

        return view('admin/template', $data);
    }

    public function create_promo_code()
    {
        $data['main_page'] = FORMS . 'promo-code';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Promo code | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Promo code | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_promo_code()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'promo_code' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The promo_code is required!',
                    ]
                ],
                'message' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The message is required!',
                    ]
                ],
                'start_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The start_date is required!',
                    ]
                ],
                'end_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The end_date is required!',
                    ]
                ],
                'no_of_users' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The no_of_users is required!',
                    ]
                ],
                'minimum_order_amount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The minimum_order_amount is required!',
                    ]
                ],
                'discount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The discount is required!',
                    ]
                ],
                'discount_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The discount_type is required!',
                    ]
                ],
                'max_discount_amount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The max_discount_amount is required!',
                    ]
                ],
                'repeat_usage' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The repeat_usage is required!',
                    ]
                ],
                'status' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The status is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/promocode/create_promo_code');
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $exist_promo = $this->promoCodesTable->where('promo_code', $this->request->getPost('promo_code'))->first();

                if($exist_promo){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Promo Already exist you should use a different name');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/promos', $mainImage);

                    $data = [
                        'promo_code' =>  $this->request->getPost('promo_code'),
                        'message' =>  $this->request->getPost('message'),
                        'start_date' =>  $this->request->getPost('start_date'),
                        'end_date' =>  $this->request->getPost('end_date'),
                        'no_of_users' =>  $this->request->getPost('no_of_users'),
                        'minimum_order_amount' =>  $this->request->getPost('minimum_order_amount'),
                        'discount' =>  $this->request->getPost('discount'),
                        'discount_type' =>  $this->request->getPost('discount_type'),
                        'max_discount_amount' =>  $this->request->getPost('max_discount_amount'),
                        'repeat_usage' =>  $this->request->getPost('repeat_usage'),
                        'image' =>  "uploads/promos/".$mainImage,
                        'status' =>  $this->request->getPost('status'),
                        'no_of_repeat_usage' =>  $this->request->getPost('no_of_repeat_usage'),
                        'is_cashback' =>  $this->request->getPost('is_cashback') ? true : false,
                        'list_promocode' =>  $this->request->getPost('list_promocode') ? true : false,
                    ];

                    // dd($data);
                    $added = $this->promoCodesTable->insert($data);

                    if ($added) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Promo Added Successfully');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                }
            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Promo Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/promocode/create_promo_code');

        }else{
            return redirect()->to('/admin/promocode/create_promo_code');
        }
    }

    public function delete_promo_code()
    {
        $delete_id = $this->request->getGet('id');
        $deleted = $this->promoCodesTable->delete($delete_id);

        if ($deleted) {
           $response['error'] = false;
           $response['message'] = 'Promo Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Promo not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    

    public function edit_promo_code()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-promo-code';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Promo | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Promo | ' . $settings['app_name'];

            $data['fetched_details'] = $this->promoCodesTable->where('id', $edit_id)->first();

            return view('admin/template', $data);

            
        }else{
            return redirect()->to('/admin/promocode');
        }
    }

    public function update_promo_code()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'promo_code' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The promo_code is required!',
                    ]
                ],
                'message' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The message is required!',
                    ]
                ],
                'start_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The start_date is required!',
                    ]
                ],
                'end_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The end_date is required!',
                    ]
                ],
                'no_of_users' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The no_of_users is required!',
                    ]
                ],
                'minimum_order_amount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The minimum_order_amount is required!',
                    ]
                ],
                'discount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The discount is required!',
                    ]
                ],
                'discount_type' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The discount_type is required!',
                    ]
                ],
                'max_discount_amount' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The max_discount_amount is required!',
                    ]
                ],
                'repeat_usage' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The repeat_usage is required!',
                    ]
                ],
                'status' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The status is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/promocode/edit_promo_code?edit_id='.$this->request->getPost('edit_promo_code'));
            }

            $edit_promo_id = $this->request->getPost('edit_promo_code');

            // Get uploaded file
            $main_image = $this->request->getFile('image');

            if($main_image != ""){

                if ($main_image->isValid() && !$main_image->hasMoved()) {

                    $exist_promo = $this->promoCodesTable
                    ->where('promo_code', $this->request->getPost('promo_code'))
                    ->where('id <>', $edit_promo_id)
                    ->first();

                    if($exist_promo){
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Promo Already exist you should use a different name');
                    }else{

                        // Move the file to the public/uploads directory
                        $mainImage = $main_image->getRandomName();
                        $main_image->move('uploads/promos', $mainImage);

                        $data = [
                            'promo_code' =>  $this->request->getPost('promo_code'),
                            'message' =>  $this->request->getPost('message'),
                            'start_date' =>  $this->request->getPost('start_date'),
                            'end_date' =>  $this->request->getPost('end_date'),
                            'no_of_users' =>  $this->request->getPost('no_of_users'),
                            'minimum_order_amount' =>  $this->request->getPost('minimum_order_amount'),
                            'discount' =>  $this->request->getPost('discount'),
                            'discount_type' =>  $this->request->getPost('discount_type'),
                            'max_discount_amount' =>  $this->request->getPost('max_discount_amount'),
                            'repeat_usage' =>  $this->request->getPost('repeat_usage'),
                            'status' =>  $this->request->getPost('status'),
                            'no_of_repeat_usage' =>  $this->request->getPost('no_of_repeat_usage'),
                            'is_cashback' =>  $this->request->getPost('is_cashback') ? true : false,
                            'list_promocode' =>  $this->request->getPost('list_promocode') ? true : false,
                            'image' =>  "uploads/promos/".$mainImage,
                        ];

                        // dd($data);
                        $updated = $this->promoCodesTable->update($edit_promo_id, $data);

                        if ($updated) {
                            $session->setFlashdata('status', 'success');
                            $session->setFlashdata('message', 'Promo updated Successful!');
                        } else {
                            $session->setFlashdata('status', 'error');
                            $session->setFlashdata('message', 'Something went wrong');
                        }
                        

                    }
                    
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Promo Main Image is required!');
                }

            }else{

                $exist_promo = $this->promoCodesTable
                ->where('promo_code', $this->request->getPost('promo_code'))
                ->where('id <>', $edit_promo_id)
                ->first();

                if($exist_promo){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Promo Already exist you should use a different name');
                }else{

                    $data = [
                        'promo_code' =>  $this->request->getPost('promo_code'),
                        'message' =>  $this->request->getPost('message'),
                        'start_date' =>  $this->request->getPost('start_date'),
                        'end_date' =>  $this->request->getPost('end_date'),
                        'no_of_users' =>  $this->request->getPost('no_of_users'),
                        'minimum_order_amount' =>  $this->request->getPost('minimum_order_amount'),
                        'discount' =>  $this->request->getPost('discount'),
                        'discount_type' =>  $this->request->getPost('discount_type'),
                        'max_discount_amount' =>  $this->request->getPost('max_discount_amount'),
                        'repeat_usage' =>  $this->request->getPost('repeat_usage'),
                        'status' =>  $this->request->getPost('status'),
                        'no_of_repeat_usage' =>  $this->request->getPost('no_of_repeat_usage'),
                        'is_cashback' =>  $this->request->getPost('is_cashback') ? true : false,
                        'list_promocode' =>  $this->request->getPost('list_promocode') ? true : false,
                    ];

                    // dd($data);
                    $updated = $this->promoCodesTable->update($edit_promo_id, $data);

                    if ($updated) {
                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Promo updated Successful!');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                    
                }             

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/promocode/edit_promo_code?edit_id='.$edit_promo_id);

        }else{
            return redirect()->to('/admin/promocode');
        }
    }
    
}