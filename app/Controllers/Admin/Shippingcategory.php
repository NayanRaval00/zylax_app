<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Shipping as ShippingModel;
use App\Models\ShippingCategory as ShippingCategoryModel;
use App\Models\ShippingCategoryPrice as ShippingCategoryPriceModel;
use App\Models\AttributeSet as AttributeSetModel;
use App\Models\AttributeSetCategory as AttributeSetCategoryModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Shippingcategory extends Controller
{

    public $attributeSetTable, $attributeSetCategoryTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->shippingTable = new ShippingModel();
        $this->shippingCategoryTable = new ShippingCategoryModel();
        $this->shippingCategoryPriceTable = new ShippingCategoryPriceModel();
        $this->attributeSetTable = new AttributeSetModel();
        $this->attributeSetCategoryTable = new AttributeSetCategoryModel();
    }

    public function index()
    {
        $data['main_page'] = FORMS . 'shipping-category';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Shipping Category | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Shipping Category | ' . $settings['app_name'];

        $data['shipping_result'] = $this->shippingTable
                                        ->orderBy('id', 'DESC')
                                        ->findAll();

        return view('admin/template', $data);
    }

    public function add_shipping_category()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'shipping_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Shipping Name is required!',
                    ]
                ],
                'price' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Price is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/shippingcategory');
            }

       
            $data = [
                'shipping_id' =>  $this->request->getPost('shipping_name'),
                'price' =>  $this->request->getPost('price'),
                'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                'orderminprice' =>  $this->request->getPost('orderminprice'),
            ];

            // print_r($_POST);

            // die;

            $inserted_id = $this->shippingCategoryTable->insert($data);

            if ($inserted_id) {

                $categories = $this->request->getPost('category') ?? '';
                if(!empty($categories)){
                    foreach ($categories as $category) {
                        $assign_shipping_category_price = [
                            'shipping_id' =>  $this->request->getPost('shipping_name'),
                            'category_id' =>  $category,
                            'price' =>  $this->request->getPost('price'),
                            'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                            'orderminprice' =>  $this->request->getPost('orderminprice'),
                            'priority' =>  $this->request->getPost('priority'),  
                        ];
                        $assign_inserted_id = $this->shippingCategoryPriceTable->insert($assign_shipping_category_price);
                    }
                }else{
                    $assign_shipping_category_price = [
                        'shipping_id' => $this->request->getPost('shipping_name') ?? '',
                        'category_id' => $this->request->getPost('category_id') ?? '',
                        'price' => $this->request->getPost('price') ?? 0,
                        'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                        'orderminprice' =>  $this->request->getPost('orderminprice'),
                        'priority' =>  $this->request->getPost('priority'),
                    ];
                    $assign_inserted_id = $this->shippingCategoryPriceTable->insert($assign_shipping_category_price);
                }

                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Shipping Category Added Successfully');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/shippingcategory');

        }else{
            return redirect()->to('/admin/shippingcategory');
        }
    }
    
    public function manage_shipping_category()
    {
        $data['main_page'] = TABLES . 'manage-shipping-category';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Manage Shipping Category | ' . $settings['app_name'];
        $data['meta_description'] = 'Manage Shipping Category | ' . $settings['app_name'];

        $data['shipping_category_result'] = $this->shippingCategoryTable->getShippingCategory();

        return view('admin/template', $data);
    }

    public function edit_shipping_category()
    {
        $edit_id = $this->request->getGet('edit_id');

            if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-shipping-category';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Shipping Category | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Shipping Category | ' . $settings['app_name'];

            $data['fetched_data'] = $this->shippingCategoryTable->where('id', $edit_id)->first();
            $selected_categories = $this->shippingCategoryPriceTable->where('shipping_id', $data['fetched_data']['shipping_id'])->findAll();

            $category_list = "";
            foreach ($selected_categories as $category) {
                $category_list .= $category['category_id'] . ",";
                $priority = $category['priority'];
                $shippingcatpricesid = $category['id'];

            }

            $data['selected_categories'] = $category_list;
            $data['priority'] = $priority;
            $data['shippingcatpricesid'] = $shippingcatpricesid;




            $data['shipping_result'] = $this->shippingTable
                                        ->orderBy('id', 'DESC')
                                        ->findAll();

            // dd($data);
            return view('admin/template', $data);
        }else{
            return redirect()->to('/admin/shippingcategory');
        }
    }

    public function update_shipping_category()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'shipping_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Shipping Name is required!',
                    ]
                ],
                'price' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Price is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/shippingcategory/edit_shipping_category?edit_id='.$this->request->getPost('edit_shipping_category_id'));
            }

            $edit_id = $this->request->getPost('edit_shipping_category_id');
            $shippingcatpricesid = $this->request->getPost('shippingcatpricesid');
            $edit_shipping_id = $this->request->getPost('edit_shipping_id');
            $new_categories = $this->request->getPost('category');
            if(!empty($new_categories)){
                $edit_shipping_category = rtrim($this->request->getPost('edit_shipping_category'), ",");;
                $existing_categories_array = explode(',', $edit_shipping_category);
                $difference_categories = array_diff($existing_categories_array, $new_categories);
            }
            $data = [
                'shipping_id' =>  $this->request->getPost('shipping_name'),
                'price' =>  $this->request->getPost('price'),
                'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                'orderminprice' =>  $this->request->getPost('orderminprice'),
            ];

            $updated = $this->shippingCategoryTable->update($edit_id, $data);

            if ($updated) {

                if(!empty($new_categories)){
                    // delete removed category first
                    foreach ($difference_categories as $category) {
                        $this->shippingCategoryPriceTable
                                ->where('shipping_id', $edit_shipping_id)
                                ->where('category_id', $category)
                                ->delete();
                    }

                    // add/update new category
                    foreach ($new_categories as $category) {

                        $exist_category_update = $this->shippingCategoryPriceTable
                                                        ->where('shipping_id', $this->request->getPost('shipping_name'))
                                                        ->where('category_id', $category)
                                                        ->first();
                                                
                        if(isset($exist_category_update['id']) && !empty($exist_category_update['id'])){

                            $shipping_category_price_id = $exist_category_update['id'];

                            $existing_assign_shipping_category_price = [
                                'shipping_id' =>  $this->request->getPost('shipping_name'),
                                'category_id' =>  $category,
                                'price' =>  $this->request->getPost('price'),
                                'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                                'orderminprice' =>  $this->request->getPost('orderminprice'),
                                'priority' =>  $this->request->getPost('priority'),  
                            ];
                            $updated = $this->shippingCategoryPriceTable->update($shipping_category_price_id, $existing_assign_shipping_category_price);

                        }else{
                            $assign_shipping_category_price = [
                                'shipping_id' =>  $this->request->getPost('shipping_name'),
                                'category_id' =>  $category,
                                'price' =>  $this->request->getPost('price'),
                                'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                                'orderminprice' =>  $this->request->getPost('orderminprice'),
                                'priority' =>  $this->request->getPost('priority'),  
                            ];
                            $assign_inserted_id = $this->shippingCategoryPriceTable->insert($assign_shipping_category_price);
                        }

                    }
                }else{
                    $existing_assign_shipping_category_price = [
                        'shipping_id' =>  $this->request->getPost('shipping_name'),
                        'price' =>  $this->request->getPost('price'),
                        'ordermaxprice' =>  $this->request->getPost('ordermaxprice'),
                        'orderminprice' =>  $this->request->getPost('orderminprice'),
                        'priority' =>  $this->request->getPost('priority'),  
                    ];
                    $updated = $this->shippingCategoryPriceTable->update($shippingcatpricesid, $existing_assign_shipping_category_price);
                }


                $session->setFlashdata('status', 'success');
                $session->setFlashdata('message', 'Shipping Category updated Successful!');
            } else {
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Something went wrong');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/shippingcategory/edit_shipping_category?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/shippingcategory');
        }
    }
    
    public function update_shipping_category_status($id, $status)
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        $data = [
            'status' =>  $status,
        ];

        $updated = $this->shippingCategoryTable->update($id, $data);

        if ($updated) {
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Shipping Category status updated Successful!');
        } else {
            $session->setFlashdata('status', 'error');
            $session->setFlashdata('message', 'Something went wrong');
        }

        return redirect()->to('/admin/shippingcategory/manage_shipping_category');
    }
    
}