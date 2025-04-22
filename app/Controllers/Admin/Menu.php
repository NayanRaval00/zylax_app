<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Menus as MenusModel;
use App\Models\MenuCategory as MenuCategoryModel;
use App\Models\Custommenus as CustommenusModel;
use App\Models\CustommenuCategory as CustommenuCategoryModel;
use App\Models\CustommenusSub as CustommenusSubModel;
use App\Models\CustommenuSubCategory as CustommenuSubCategoryModel;
use App\Models\MenuFeaturedProducts as MenuFeaturedProductsModel;
use App\Models\Products as ProductsModel;
use App\Models\Slugs as SlugsModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Menu extends Controller
{
    public $menusTable, $menuCategoryTable, $custommenusTable, $custommenuCategoryTable, $custommenusSubTable, $custommenuSubCategoryTable, $menuFeaturedProductsTable, $slugTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->menusTable = new MenusModel();
        $this->menuCategoryTable = new MenuCategoryModel();
        $this->custommenusTable = new CustommenusModel();
        $this->custommenuCategoryTable = new CustommenuCategoryModel();
        $this->custommenusSubTable = new CustommenusSubModel();
        $this->custommenuSubCategoryTable = new CustommenuSubCategoryModel();
        $this->menuFeaturedProductsTable = new MenuFeaturedProductsModel();
        $this->productsTable = new ProductsModel();
        $this->slugTable = new SlugsModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-menus';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Menu Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Menu Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function create_menu()
    {

        $data['main_page'] = FORMS . 'menu';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Menu | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Menu , Create Menu | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function add_menu()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Title is required!',
                    ]
                ],
                'link' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Link is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/menu/create_menu');
            }

             // Get uploaded file
            $image_top = $this->request->getFile('image_top');
            $image_right = $this->request->getFile('image_right');

            $slug_tables = $this->slugTable
                                ->where('slug', $this->request->getPost('link'))
                                ->where('type', 'mega_menu')
                                ->first();

            if($slug_tables){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                // Move the file to the public/uploads directory
                if ($image_top->isValid() && !$image_top->hasMoved()) {

                    $imageTopImage = $image_top->getRandomName();
                    $image_top->move('uploads/menus/image-top', $imageTopImage);
                }

                // Move the file to the public/uploads directory
                if ($image_right->isValid() && !$image_right->hasMoved()) {

                    $imageRightImage = $image_right->getRandomName();
                    $image_right->move('uploads/menus/image-right', $imageRightImage);
                }

                $data = [
                    'title' =>  $this->request->getPost('title'),
                    'icon' =>  $this->request->getPost('icon'),
                    'link' =>  $this->request->getPost('link'),
                    'image_top' => isset($imageTopImage) ? "uploads/menus/image-top/".$imageTopImage : "",
                    'image_right' => isset($imageRightImage) ? "uploads/menus/image-right/".$imageRightImage : "",
                    'type' =>  $this->request->getPost('type'),
                    'sort' =>  $this->request->getPost('sort'),
                    'description' =>  $this->request->getPost('description'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];

                // dd($data);
                $added_id = $this->menusTable->insert($data);

                if ($added_id) {

                    $slug_data = [
                        'type' =>  'mega_menu',
                        'ref_id' =>  $added_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Menu added Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
                
            }              

            // Validation passed, process the form data
            return redirect()->to('/admin/menu/create_menu');

        }else{
            return redirect()->to('/admin/menu/create_menu');
        }
    }

    public function edit_menu()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-menu';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Menu | ' . $settings['app_name'] : 'Add Menu | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Menu | ' . $settings['app_name'];

            $data['fetched_data'] = $this->menusTable->where('id', $edit_id)->first();

            $selected_categories = $this->menuCategoryTable->where('menu_id', $edit_id)->findAll();

            $category_list = "";
            foreach ($selected_categories as $category) {
                $category_list .= $category['category_id'] . ",";
            }

            $data['selected_categories'] = $category_list;

            $data['featured_products'] = $this->menuFeaturedProductsTable->getMenuFeaturedProductListing($edit_id);

            // dd($data['featured_products']);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/menu');
        }

    }

    public function update_menu()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Title is required!',
                    ]
                ],
                'link' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Link is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/menu/edit_menu?edit_id='.$this->request->getPost('edit_menu'));
            }

            $edit_id = $this->request->getPost('edit_menu');

            // Get uploaded file
            $image_top = $this->request->getFile('image_top');
            $image_right = $this->request->getFile('image_right');

            $exist_menu = $this->slugTable
                                ->where('type', 'mega_menu')
                                ->where('slug', $this->request->getPost('link'))
                                ->where('ref_id <>', $edit_id)
                                ->first();

            if($exist_menu){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $edit_menu_category = rtrim($this->request->getPost('edit_menu_category'), ",");;
                $existing_menu_categories_array = explode(',', $edit_menu_category);
                $new_categories = $this->request->getPost('category') ? $this->request->getPost('category') : [];
                $difference_categories = array_diff($existing_menu_categories_array, $new_categories);

                // Move the file to the public/uploads directory
                if ($image_top != "" && $image_top->isValid() && !$image_top->hasMoved()) {

                    $imageTopImage = $image_top->getRandomName();
                    $image_top->move('uploads/menus/image-top', $imageTopImage);
                }

                // Move the file to the public/uploads directory
                if ($image_right != "" && $image_right->isValid() && !$image_right->hasMoved()) {

                    $imageRightImage = $image_right->getRandomName();
                    $image_right->move('uploads/menus/image-right', $imageRightImage);
                }

                $data = [
                    'title' =>  $this->request->getPost('title'),
                    'icon' =>  $this->request->getPost('icon'),
                    'link' =>  $this->request->getPost('link'),
                    'image_top' => isset($imageTopImage) ? "uploads/menus/image-top/".$imageTopImage : $this->request->getPost('menu_image_top'),
                    'image_right' => isset($imageRightImage) ? "uploads/menus/image-right/".$imageRightImage : $this->request->getPost('menu_image_right'),
                    'type' =>  $this->request->getPost('type'),
                    'sort' =>  $this->request->getPost('sort'),
                    'description' =>  $this->request->getPost('description'),
                    'featured_title' =>  $this->request->getPost('featured_title'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];

                // dd($data);
                $menu_updated = $this->menusTable->update($edit_id, $data);

                if ($menu_updated) {

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'mega_menu',
                        'ref_id' =>  $edit_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);


                    // delete removed category first
                    if(isset($difference_categories)){
                        foreach ($difference_categories as $category) {
                            $this->menuCategoryTable
                                    ->where('menu_id', $edit_id)
                                    ->where('category_id', $category)
                                    ->delete();
                        }
                    }
    
                    // add/update new category
                    if(isset($new_categories)){
                        foreach ($new_categories as $category) {
        
                            $exist_category_update = $this->menuCategoryTable
                                                            ->where('menu_id', $edit_id)
                                                            ->where('category_id', $category)
                                                            ->first();
                                                    
                            if(isset($exist_category_update['id']) && !empty($exist_category_update['id'])){
        
                                $menu_category_id = $exist_category_update['id'];
        
                                $existing_assign_menu_category_price = [
                                    'menu_id' =>  $edit_id,
                                    'category_id' =>  $category,
                                ];
                                $updated = $this->menuCategoryTable->update($menu_category_id, $existing_assign_menu_category_price);
        
                            }else{
                                $assign_menu_category = [
                                    'menu_id' =>  $edit_id,
                                    'category_id' =>  $category,
                                ];
                                $assign_inserted_id = $this->menuCategoryTable->insert($assign_menu_category);
                            }
        
                        }
                    }

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Menu updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }
                

            }
               
            // Validation passed, process the form data
            return redirect()->to('/admin/menu/edit_menu?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/menu');
        }
    }

    public function fetchMenus()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->menusTable;

        if (!empty($searchValue)) {
            $query->like('title', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $menus = $query->limit($length, $start)->find();

        $data = [];
        foreach ($menus as $menu) {

            if($menu['active'] == 1){
                $status = '<a class="badge badge-success text-white">Enable</a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Disable</a>';
            }

            $data[] = [
                $menu['id'],
                $menu['title'],
                $status,
                '<a href="'.base_url('admin/menu/edit_menu?edit_id='.$menu['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/menu/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-mega_menus btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$menu['id'].'"> <i class="fa fa-trash"></i> </a>'
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }

    public function add_featured_product()
    {
        $menu_id = $this->request->getGet('menu_id');
        $product_type = $this->request->getGet('product_type');
        $product_id = $this->request->getGet('product_id');

        if($menu_id != "" && $product_type != "" && $product_id != ""){
            $exist_featured_product = $this->menuFeaturedProductsTable
                ->where('menu_id', $menu_id)
                ->where('product_type', $product_type)
                // ->where('product_id', $product_id)
                ->first();

            if(empty($exist_featured_product)){

                $featured_product_data = [
                    'menu_id' =>  $menu_id,
                    'product_type' =>  $product_type,
                    'product_id' =>  $product_id,
                ];
                $featured_product_added_id = $this->menuFeaturedProductsTable->insert($featured_product_data);
                
                if ($featured_product_added_id) {

                    $data_product_options = $this->productsTable
                                                ->where('id', $product_id)
                                                ->first();

                    $response['error'] = false;
                    $response['data'] = [
                        'featured_id' => $featured_product_added_id,
                        'product_type' => $product_type,
                        'product_name' => $data_product_options['name'],
                    ];
                    $response['message'] = 'Featured Product Added Succesfully';
                    echo(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Featured Product not added';
                    echo(json_encode($response));
                }

            }else{
                $response['error'] = true;
                $response['message'] = 'Already Featured Product added';
                echo(json_encode($response));
            }
        } else{
            $response['error'] = true;
            $response['message'] = 'All fields are Required';
            echo(json_encode($response));
        }   

    }

    public function delete_menu_featured_product()
    {
        $menu_featured_product_id = $this->request->getGet('id');
        $menu_featured_product_deleted = $this->menuFeaturedProductsTable->delete($menu_featured_product_id);

        if ($menu_featured_product_deleted) {
           $response['error'] = false;
           $response['message'] = 'Menu Feature Product Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Menu Feature Product not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function delete_menus()
    {
        $delete_id = $this->request->getGet('id');
        $deleted = $this->menusTable->delete($delete_id);

        if ($deleted) {

            $slug_tables = $this->slugTable->deleteSlugByTypeAndRefId('mega_menu', $delete_id);

           $response['error'] = false;
           $response['message'] = 'Menu Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Menu not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    // custom menu

    public function custommenus()
    {
        $data['main_page'] = TABLES . 'manage-custommenus';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Custom Menu Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Custom Menu Management | ' . $settings['app_name'];

        return view('admin/template', $data);
    }

    public function fetchCustomMenus()
    {
        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        $searchValue = $request->getPost('search')['value'];

        $query = $this->custommenusTable;

        $query->select('custommenus.*, menus.title as parent_menu_title');
        $query->join('menus', 'menus.id = custommenus.menu_id', 'left');

        if (!empty($searchValue)) {
            $query->like('custommenus.title', $searchValue);
            $query->orLike('menus.title', $searchValue);
        }

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $menus = $query->limit($length, $start)->find();

        $data = [];
        foreach ($menus as $menu) {

            if($menu['active'] == 1){
                $status = '<a class="badge badge-success text-white">Enable</a>';
            }else{
                $status = '<a class="badge badge-danger text-white">Disable</a>';
            }

            $data[] = [
                $menu['id'],
                $menu['title'],
                $menu['parent_menu_title'],
                $menu['sort'],
                '<a href="'.base_url('admin/menu/subcustommenus/'.$menu['id']).'" class="badge badge-primary text-white">Sub Custom</a>',
                $status,
                '<a href="'.base_url('admin/menu/edit_custommenu?edit_id='.$menu['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/menu/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-custom_menus btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$menu['id'].'"> <i class="fa fa-trash"></i> </a>'
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }

    public function create_custommenu()
    {

        $data['main_page'] = FORMS . 'custommenu';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Custom Menu | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Custom Menu , Create Custom Menu | ' . $settings['app_name'];

        $data['menus'] = $this->menusTable
                                    ->where('active', 1)
                                    ->findAll();

        return view('admin/template', $data);
    }

    public function add_custommenu()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Title is required!',
                    ]
                ],
                'link' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Link is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/menu/create_custommenu');
            }

            $slug_tables = $this->slugTable
                                ->where('slug', $this->request->getPost('link'))
                                ->where('type', 'mega_custommenu')
                                ->first();

            if($slug_tables){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $new_categories = $this->request->getPost('category') ? $this->request->getPost('category') : [];

                $data = [
                    'title' =>  $this->request->getPost('title'),
                    'menu_id' =>  $this->request->getPost('menu_id'),
                    'link' =>  $this->request->getPost('link'),
                    'sort' =>  $this->request->getPost('sort'),
                    'link_url' =>  $this->request->getPost('link_url'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];

                // dd($data);
                $added_id = $this->custommenusTable->insert($data);

                if ($added_id) {

                    // insert new category to custom menu category table
                    if(isset($new_categories)){
                        foreach ($new_categories as $category) {
                            $assign_custommenu_category = [
                                'custommenu_id' =>  $added_id,
                                'category_id' =>  $category,
                            ];
                            $assign_inserted_id = $this->custommenuCategoryTable->insert($assign_custommenu_category);
                        }
                    }

                    $slug_data = [
                        'type' =>  'mega_custommenu',
                        'ref_id' =>  $added_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Custom Menu added Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }
            // Validation passed, process the form data
            return redirect()->to('/admin/menu/create_custommenu');

        }else{
            return redirect()->to('/admin/menu/create_custommenu');
        }
    }

    public function edit_custommenu()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-custommenu';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Custom Menu | ' . $settings['app_name'] : 'Add Custom Menu | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Custom Menu | ' . $settings['app_name'];

            $data['fetched_data'] = $this->custommenusTable->where('id', $edit_id)->first();

            $data['menus'] = $this->menusTable
                                    ->where('active', 1)
                                    ->findAll();

            $selected_categories = $this->custommenuCategoryTable->where('custommenu_id', $edit_id)->findAll();

            $category_list = "";
            foreach ($selected_categories as $category) {
                $category_list .= $category['category_id'] . ",";
            }

            $data['selected_categories'] = $category_list;

            // dd($data);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/menu/custommenus');
        }

    }

    public function update_custommenu()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Title is required!',
                    ]
                ],
                'link' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Link is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/menu/edit_custommenu?edit_id='.$this->request->getPost('edit_custommenu'));
            }

            $edit_id = $this->request->getPost('edit_custommenu');

            $edit_custommenu_category = rtrim($this->request->getPost('edit_custommenu_category'), ",");;
            $existing_custommenu_categories_array = explode(',', $edit_custommenu_category);
            $new_categories = $this->request->getPost('category') ? $this->request->getPost('category') : [];
            $difference_categories = array_diff($existing_custommenu_categories_array, $new_categories);

            $exist_menu = $this->slugTable
                                ->where('type', 'mega_custommenu')
                                ->where('slug', $this->request->getPost('link'))
                                ->where('ref_id <>', $edit_id)
                                ->first();

            if($exist_menu){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $data = [
                    'title' =>  $this->request->getPost('title'),
                    'menu_id' =>  $this->request->getPost('menu_id'),
                    'link' =>  $this->request->getPost('link'),
                    'sort' =>  $this->request->getPost('sort'),
                    'link_url' =>  $this->request->getPost('link_url'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];

                // dd($data);
                $custommenu_updated = $this->custommenusTable->update($edit_id, $data);

                if ($custommenu_updated) {

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'mega_custommenu',
                        'ref_id' =>  $edit_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);

                    // delete removed category first
                    if(isset($difference_categories)){
                        foreach ($difference_categories as $category) {
                            $this->custommenuCategoryTable
                                    ->where('custommenu_id', $edit_id)
                                    ->where('category_id', $category)
                                    ->delete();
                        }
                    }

                    // add/update new category
                    if(isset($new_categories)){
                        foreach ($new_categories as $category) {

                            $exist_category_update = $this->custommenuCategoryTable
                                                            ->where('custommenu_id', $edit_id)
                                                            ->where('category_id', $category)
                                                            ->first();
                                                    
                            if(isset($exist_category_update['id']) && !empty($exist_category_update['id'])){

                                $custommenu_category_id = $exist_category_update['id'];

                                $existing_assign_custommenu_category_price = [
                                    'custommenu_id' =>  $edit_id,
                                    'category_id' =>  $category,
                                ];
                                $updated = $this->custommenuCategoryTable->update($custommenu_category_id, $existing_assign_custommenu_category_price);

                            }else{
                                $assign_custommenu_category = [
                                    'custommenu_id' =>  $edit_id,
                                    'category_id' =>  $category,
                                ];
                                $assign_inserted_id = $this->custommenuCategoryTable->insert($assign_custommenu_category);
                            }

                        }
                    }

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Custom Menu updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }
                               
            // Validation passed, process the form data
            return redirect()->to('/admin/menu/edit_custommenu?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/custommenus');
        }
    }

    public function delete_custom_menus()
    {
        $delete_id = $this->request->getGet('id');
        $deleted = $this->custommenusTable->delete($delete_id);

        if ($deleted) {

            $slug_tables = $this->slugTable->deleteSlugByTypeAndRefId('mega_custommenu', $delete_id);

           $response['error'] = false;
           $response['message'] = 'Custom Menu Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Custom Menu not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

     // subcustom menu

     public function subcustommenus($parent_id)
     {
         $data['main_page'] = TABLES . 'manage-subcustommenus';
 
         $settings = get_settings('system_settings', true);
         $data['title'] = 'Sub Custom Menu Management | ' . $settings['app_name'];
         $data['meta_description'] = 'Sub Custom Menu Management | ' . $settings['app_name'];

         $data['parent_id'] = $parent_id;
 
         return view('admin/template', $data);
     }

     public function fetchSubCustomMenus($parent_id)
     {
         $request = service('request');
 
         $draw = (int) $request->getPost('draw'); // Ensure integer
         $start = (int) $request->getPost('start'); // Ensure integer
         $length = (int) $request->getPost('length'); // Ensure integer
         $searchValue = $request->getPost('search')['value'];
 
         $query = $this->custommenusSubTable;

        // $query->select('custommenus_sub.*, c2.title as parent_customsub_title');
        // $query->join('custommenus_sub c2', 'c2.id = custommenus_sub.custommenu_id', 'left');
 
        if (!empty($searchValue)) {
            // $query->like('custommenus_sub.title', $searchValue);
            $query->like('title', $searchValue);
        }
        $query->where('parent_id', $parent_id);
        $query->where('custommenu_id', 0);
            
         $totalRecords = $query->countAllResults(false);
         $filteredRecords = $query->countAllResults(false);
 
         $sub_custom_menus = $query->limit($length, $start)->find();
 
         $data = [];
         foreach ($sub_custom_menus as $menu) {
 
             if($menu['active'] == 1){
                 $status = '<a class="badge badge-success text-white">Enable</a>';
             }else{
                 $status = '<a class="badge badge-danger text-white">Disable</a>';
             }

             $data[] = [
                $menu['id'],
                $menu['title'],
               //  $menu['parent_customsub_title'] ? $menu['parent_customsub_title'] : " - ",
               '<a href="'.base_url('admin/menu/sub_subcustommenus/'.$parent_id.'/'.$menu['id']).'" class="badge badge-primary text-white">Sub Custom</a>',
                $status,
                '<a href="'.base_url('admin/menu/edit_subcustommenu?edit_id='.$menu['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/menu/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-sub_custom_menus btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$menu['id'].'"> <i class="fa fa-trash"></i> </a>'
            ];
 
         }
 
         return $this->response->setJSON([
             "draw" => intval($draw),
             "recordsTotal" => $totalRecords,
             "recordsFiltered" => $filteredRecords,
             "data" => $data
         ]);
     }

     public function create_subcustommenu($parent_id, $subcustom_id = 0)
     {
 
         $data['main_page'] = FORMS . 'subcustommenu';
 
         $settings = get_settings('system_settings', true);
         $data['title'] = 'Add Sub Custom Menu | ' . $settings['app_name'];
         $data['meta_description'] = 'Add Sub Custom Menu , Create Sub Custom Menu | ' . $settings['app_name'];
 
         $data['parent_id'] = $parent_id;
         $data['subcustom_id'] = $subcustom_id;

         $data['customsub_menus'] = $this->custommenusSubTable
                                    ->where('parent_id', $parent_id)
                                    ->where('custommenu_id', 0)
                                    ->where('active', 1)
                                    ->findAll();

        //  dd($data);

         return view('admin/template', $data);
     }

     public function add_subcustommenu($parent_id)
     {
         // Load the form helper and session for flash messages
         helper(['form', 'text']);
         $session = session();
 
         // Check if the form is submitted
         if ($this->request->getMethod() === 'POST') {
             
             // Define Validation Rules
             $rules = [
                 'title' => [
                     'rules' => 'required',
                     'errors' => [
                         'required'    => 'The Menu Title is required!',
                     ]
                 ],
                 'link' => [
                     'rules' => 'required',
                     'errors' => [
                         'required'    => 'The Menu Link is required!',
                     ]
                 ],
             ];
 
             if (!$this->validate($rules)) {
                 // Validation failed, return with errors
                 $session->setFlashdata('validation', $this->validator);
                 return redirect()->to('/admin/menu/create_subcustommenu/'.$parent_id);
             }

            $slug_tables = $this->slugTable
                                ->where('slug', $this->request->getPost('link'))
                                ->where('type', 'mega_custommenu_sub')
                                ->first();

            if($slug_tables){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $new_categories = $this->request->getPost('category') ? $this->request->getPost('category') : [];
    
                $data = [
                    'parent_id' =>  $parent_id,
                    // 'custommenu_id' =>  $this->request->getPost('custommenu_id'),
                    'custommenu_id' =>  $this->request->getPost('custommenu_id') ? $this->request->getPost('custommenu_id') : 0,
                    'title' =>  $this->request->getPost('title'),
                    'link' =>  $this->request->getPost('link'),
                    'link_url' =>  $this->request->getPost('link_url'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];
    
                // dd($data);
                $added_id = $this->custommenusSubTable->insert($data);
    
                if ($added_id) {

                    // insert new category to custom menu category table
                    if(isset($new_categories)){
                        foreach ($new_categories as $category) {
                            $assign_custommenu_sub_category = [
                                'custommenus_sub_id' =>  $added_id,
                                'category_id' =>  $category,
                            ];
                            $assign_inserted_id = $this->custommenuSubCategoryTable->insert($assign_custommenu_sub_category);
                        }
                    }

                    $slug_data = [
                        'type' =>  'mega_custommenu_sub',
                        'ref_id' =>  $added_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];

                    $slug_added = $this->slugTable->insert($slug_data);

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Sub Custom Menu added Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }
 
            if($this->request->getPost('custommenu_id')){
                // Validation passed, process the form data
                return redirect()->to('/admin/menu/create_subcustommenu/'.$parent_id.'/'.$this->request->getPost('custommenu_id'));
            }else{
                // Validation passed, process the form data
                return redirect()->to('/admin/menu/create_subcustommenu/'.$parent_id);
            }
 
         }else{
             return redirect()->to('/admin/menu/create_subcustommenu/'.$parent_id);
         }
     }

     public function edit_subcustommenu()
    {
        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-subcustommenu';

            $settings = get_settings('system_settings', true);
            $data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Sub Custom Menu | ' . $settings['app_name'] : 'Add Sub Custom Menu | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Sub Custom Menu | ' . $settings['app_name'];

            $data['fetched_data'] = $this->custommenusSubTable->where('id', $edit_id)->first();

            $selected_categories = $this->custommenuSubCategoryTable->where('custommenus_sub_id', $edit_id)->findAll();

            $category_list = "";
            foreach ($selected_categories as $category) {
                $category_list .= $category['category_id'] . ",";
            }

            $data['selected_categories'] = $category_list;

            $data['customsub_menus'] = $this->custommenusSubTable
                                            ->where('parent_id', $data['fetched_data']['parent_id'])
                                            ->where('custommenu_id', 0)
                                            ->where('active', 1)
                                            ->findAll();

            // dd($data);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/menu/custommenus');
        }

    }

    public function update_subcustommenu()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Title is required!',
                    ]
                ],
                'link' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Menu Link is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/menu/edit_subcustommenu?edit_id='.$this->request->getPost('edit_subcustommenu'));
            }

            $edit_id = $this->request->getPost('edit_subcustommenu');

            $edit_subcustommenu_category = rtrim($this->request->getPost('edit_subcustommenu_category'), ",");;
            $existing_custommenu_categories_array = explode(',', $edit_subcustommenu_category);
            $new_categories = $this->request->getPost('category') ? $this->request->getPost('category') : [];
            $difference_categories = array_diff($existing_custommenu_categories_array, $new_categories);

            $exist_menu = $this->slugTable
                                ->where('type', 'mega_custommenu_sub')
                                ->where('slug', $this->request->getPost('link'))
                                ->where('ref_id <>', $edit_id)
                                ->first();

            if($exist_menu){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Slug Already exist you should use a different name');
            }else{

                $data = [
                    'custommenu_id' =>  $this->request->getPost('custommenu_id'),
                    'title' =>  $this->request->getPost('title'),
                    'link' =>  $this->request->getPost('link'),
                    'link_url' =>  $this->request->getPost('link_url'),
                    'active' =>  $this->request->getPost('active') ? 1 : 0,
                ];

                // dd($data);
                $subcustommenu_updated = $this->custommenusSubTable->update($edit_id, $data);

                if ($subcustommenu_updated) {

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'mega_custommenu_sub',
                        'ref_id' =>  $edit_id,
                        'slug' =>  $this->request->getPost('link'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);

                    // delete removed category first
                    if(isset($difference_categories)){
                        foreach ($difference_categories as $category) {
                            $this->custommenuSubCategoryTable
                                    ->where('custommenus_sub_id', $edit_id)
                                    ->where('category_id', $category)
                                    ->delete();
                        }
                    }

                    // add/update new category
                    foreach ($new_categories as $category) {

                        $exist_category_update = $this->custommenuSubCategoryTable
                                                        ->where('custommenus_sub_id', $edit_id)
                                                        ->where('category_id', $category)
                                                        ->first();
                                                
                        if(isset($exist_category_update['id']) && !empty($exist_category_update['id'])){

                            $custommenu_sub_category_id = $exist_category_update['id'];

                            $existing_assign_custommenu_category_price = [
                                'custommenus_sub_id' =>  $edit_id,
                                'category_id' =>  $category,
                            ];
                            $updated = $this->custommenuSubCategoryTable->update($custommenu_sub_category_id, $existing_assign_custommenu_category_price);

                        }else{
                            $assign_custommenu_category = [
                                'custommenus_sub_id' =>  $edit_id,
                                'category_id' =>  $category,
                            ];
                            $assign_inserted_id = $this->custommenuSubCategoryTable->insert($assign_custommenu_category);
                        }

                    }

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Sub Custom Menu updated Successful!');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }

            }
                               
            // Validation passed, process the form data
            return redirect()->to('/admin/menu/edit_subcustommenu?edit_id='.$edit_id);

        }else{
            return redirect()->to('/admin/custommenus');
        }
    }

    public function delete_sub_custom_menus()
    {
        $delete_id = $this->request->getGet('id');
        $deleted = $this->custommenusSubTable->delete($delete_id);

        if ($deleted) {

            $slug_tables = $this->slugTable->deleteSlugByTypeAndRefId('mega_custommenu_sub', $delete_id);

           $response['error'] = false;
           $response['message'] = 'Sub Custom Menu Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Sub Custom Menu not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

     // sub subcustom menu

     public function sub_subcustommenus($parent_id, $subcustommenu_id)
     {
         $data['main_page'] = TABLES . 'manage-sub-subcustommenus';
 
         $settings = get_settings('system_settings', true);
         $data['title'] = 'Sub Sub Custom Menu Management | ' . $settings['app_name'];
         $data['meta_description'] = 'Sub Sub Custom Menu Management | ' . $settings['app_name'];

         $data['parent_id'] = $parent_id;
         $data['subcustommenu_id'] = $subcustommenu_id;
 
         return view('admin/template', $data);
     }

     public function fetchSubSubCustomMenus($parent_id, $subcustommenu_id)
     {
         $request = service('request');
 
         $draw = (int) $request->getPost('draw'); // Ensure integer
         $start = (int) $request->getPost('start'); // Ensure integer
         $length = (int) $request->getPost('length'); // Ensure integer
         $searchValue = $request->getPost('search')['value'];
 
         $query = $this->custommenusSubTable;

        // $query->select('custommenus_sub.*, c2.title as parent_customsub_title');
        // $query->join('custommenus_sub c2', 'c2.id = custommenus_sub.custommenu_id', 'left');
 
        if (!empty($searchValue)) {
            // $query->like('custommenus_sub.title', $searchValue);
            $query->like('title', $searchValue);
        }
        $query->where('parent_id', $parent_id);
        $query->where('custommenu_id', $subcustommenu_id);
            
         $totalRecords = $query->countAllResults(false);
         $filteredRecords = $query->countAllResults(false);
 
         $sub_custom_menus = $query->limit($length, $start)->find();
 
         $data = [];
         foreach ($sub_custom_menus as $menu) {
 
             if($menu['active'] == 1){
                 $status = '<a class="badge badge-success text-white">Enable</a>';
             }else{
                 $status = '<a class="badge badge-danger text-white">Disable</a>';
             }

             $data[] = [
                $menu['id'],
                $menu['title'],
               //  $menu['parent_customsub_title'] ? $menu['parent_customsub_title'] : " - ",
            //    '<a href="'.base_url('admin/menu/sub_subcustommenus/'.$menu['id']).'" class="badge badge-primary text-white">Sub Custom</a>',
                $status,
                '<a href="'.base_url('admin/menu/edit_subcustommenu?edit_id='.$menu['id']).'" class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="1" data-url="admin/menu/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-sub_custom_menus btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$menu['id'].'"> <i class="fa fa-trash"></i> </a>'
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