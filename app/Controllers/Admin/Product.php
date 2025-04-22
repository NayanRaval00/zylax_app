<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\Taxes as TaxesModel;
use App\Models\Categories as CategoriesModel;
use App\Models\Brands as BrandsModel;
use App\Models\Products as ProductsModel;
use App\Models\ProductsVariants as ProductsVariantsModel;
use App\Models\ProductMasterFeatures as ProductMasterFeaturesModel;
use App\Models\ProductFeatures as ProductFeaturesModel;
use App\Models\Countries as CountriesModel;
use App\Models\ProductImages as ProductImagesModel;
use App\Models\RelatedProducts as RelatedProductsModel;
use App\Models\RelatedProductOptions as RelatedProductOptionsModel;
use App\Models\ProductColorVariants as ProductColorVariantsModel;
use App\Models\Slugs as SlugsModel;
use App\Models\ProductMasterTags as ProductMasterTagsModel;
use App\Models\ProductTags as ProductTagsModel;
use App\Models\AttributeSetCategory as AttributeSetCategoryModel;
use App\Models\Attributes as AttributesModel;
use App\Models\ProductAttributes as ProductAttributesModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Product extends Controller
{

    public $taxesTable, $categoryTable, $brandsTable, $productsTable, $countriesTable, $productMasterFeaturesTable, $productFeaturesTable, $productImagesTable, $relatedProductsTable, $relatedProductOptionsTable, $slugTable, $productMasterTagsTable, $productTagsTable;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form']);
        $this->taxesTable = new TaxesModel();
        $this->categoryTable = new CategoriesModel();
        $this->brandsTable = new BrandsModel();
        $this->productsTable = new ProductsModel();
        $this->productsVariantsTable = new ProductsVariantsModel();
        $this->countriesTable = new CountriesModel();
        $this->productMasterFeaturesTable = new ProductMasterFeaturesModel();
        $this->productFeaturesTable = new ProductFeaturesModel();
        $this->productImagesTable = new ProductImagesModel();
        $this->relatedProductsTable = new RelatedProductsModel();
        $this->relatedProductOptionsTable = new RelatedProductOptionsModel();
        $this->productColorVariantsTable = new ProductColorVariantsModel();
        $this->slugTable = new SlugsModel();
        $this->productMasterTagsTable = new ProductMasterTagsModel();
        $this->productTagsTable = new ProductTagsModel();
        $this->attributeSetCategoryTable = new AttributeSetCategoryModel();
        $this->attributesTable = new AttributesModel();
        $this->productAttributesTable = new ProductAttributesModel();
    }

    public function index()
    {
        $data['main_page'] = TABLES . 'manage-product';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Product Management | ' . $settings['app_name'];
        $data['meta_description'] = 'Product Management | ' . $settings['app_name'];
        $data['brands'] = $this->brandsTable->findAll();

        return view('admin/template', $data);
    }

    public function create_product()
    {
        $data['main_page'] = FORMS . 'product';

        $settings = get_settings('system_settings', true);
        $data['title'] = 'Add Product | ' . $settings['app_name'];
        $data['meta_description'] = 'Add Product | ' . $settings['app_name'];

        $data['shipping_method'] = get_settings('shipping_method', true);
        $data['payment_method'] = get_settings('payment_method', true);
        $data['system_settings'] = get_settings('system_settings', true);

        $data['tax_details'] = $this->taxesTable->findAll();
        $data['category'] = $this->categoryTable->findAll();
        $data['brands'] = $this->brandsTable->findAll();
        $data['countries_list'] = $this->countriesTable->findAll();
        // $data['tag_list'] = $this->productMasterTagsTable->select('id, name')->where('status', '1')->findAll();

        return view('admin/template', $data);
    }

    public function add_product()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'pro_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Product Name is required!',
                    ]
                ],
                'pro_input_slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Product Slug is required!',
                    ]
                ],
                'short_description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Short Description is required!',
                    ]
                ],
                'category_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                $session->setFlashdata('old_input', $this->request->getPost()); // Store old input
                return redirect()->to('/admin/product/create_product')->withInput(); // Redirect with input
            }

             // Get uploaded file
            $main_image = $this->request->getFile('image');

            if ($main_image->isValid() && !$main_image->hasMoved()) {

                $exist_product = $this->slugTable->where('slug', $this->request->getPost('pro_input_slug'))->first();

                if($exist_product){
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Product Slug Already exist you should use a different slug');
                }else{

                     // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/products', $mainImage);

                    $data = [
                        'name' =>  $this->request->getPost('pro_input_name'),
                        // 'slug' =>  url_title($this->request->getPost('pro_input_slug'), '-', true),
                        'slug' =>  $this->request->getPost('pro_input_slug'),
                        // 'tags' =>  $this->request->getPost('tags'),
                        // 'tags' =>  input_tags_to_comma_string($this->request->getPost('tags')),
                        'product_type_menu' =>  'physical_product',
                        'category_id' =>  $this->request->getPost('category_id'),
                        'brand' =>  $this->request->getPost('brand'),
                        'is_feature' =>  $this->request->getPost('is_feature') ? 1 : 0,
                        'is_discount' =>  $this->request->getPost('is_discount') ? 1 : 0,
                        'is_hot_deal' =>  $this->request->getPost('is_hot_deal') ? 1 : 0,
                        'model' =>  $this->request->getPost('model'),
                        'vpn' =>  $this->request->getPost('vpn'),
                        'gtin' =>  $this->request->getPost('gtin'),
                        'image' =>  "uploads/products/".$mainImage,
                        // 'other_images' =>  json_encode($uploadedFiles),
                        // 'tax' =>  $this->request->getPost('tax'),
                        'indicator' =>  0,
                        // 'made_in' =>  $this->request->getPost('made_in'),
                        // 'total_allowed_quantity' =>  $this->request->getPost('total_allowed_quantity'),
                        // 'minimum_order_quantity' =>  $this->request->getPost('minimum_order_quantity'),
                        // 'quantity_step_size' =>  $this->request->getPost('quantity_step_size'),
                        'warranty_period' =>  $this->request->getPost('warranty_period'),
                        'guarantee_period' =>  $this->request->getPost('guarantee_period'),
                        // 'pickup_location' =>  $this->request->getPost('pickup_location'),
                        'is_prices_inclusive_tax' =>  $this->request->getPost('is_prices_inclusive_tax') ? 1 : 0,
                        'cod_allowed' =>  $this->request->getPost('cod_allowed') ? 1 : 0,
                        'is_returnable' =>  $this->request->getPost('is_returnable') ? 1 : 0,
                        'is_cancelable' =>  $this->request->getPost('is_cancelable') ? 1 : 0,
                        'is_attachment_required' =>  $this->request->getPost('is_attachment_required') ? 1 : 0,
                        'video_type' =>  $this->request->getPost('video_type'),
                        'video' =>  $this->request->getPost('video'),
                        // 'type' =>  $this->request->getPost('type'),
                        'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                        'seo_meta_keywords' =>  input_tags_to_comma_string($this->request->getPost('seo_meta_keywords')),
                        'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                        'seo_og_image' =>  $this->request->getPost('seo_og_image'),
                        'short_description' =>  $this->request->getPost('short_description'),
                        'description' =>  $this->request->getPost('pro_input_description'),
                        'extra_description' =>  $this->request->getPost('extra_input_description'),
                        'specification' =>  $this->request->getPost('pro_input_specification'),
                        // 'submit_to_google' =>  $this->request->getPost('submit_to_google'),
                        'is_best_seller' =>  $this->request->getPost('is_best_seller') ? 1 : 0,
                    ];

                    // dd($data);
                    $product_added_id = $this->productsTable->insert($data);

                    if ($product_added_id) {

                        // other images for products
                        $files = $this->request->getFiles();

                        if ($files) {
                            foreach ($files['other_images'] as $file) {
                                if ($file->isValid() && !$file->hasMoved()) {
                                    $newName = $file->getRandomName();
                                    $file->move('uploads/other_products', $newName);
                                    // $uploadedFiles[] = "uploads/other_products/".$newName;
                                    $uploads_image_path = "uploads/other_products/".$newName;

                                    $images_data = [
                                        'product_id' =>  $product_added_id,
                                        'image' =>  $uploads_image_path,
                                    ];

                                    $product_image_id = $this->productImagesTable->insert($images_data);
                                    
                                }
                            }
                        }

                        $product_data = [
                            'product_id' =>  $product_added_id,
                            'price' =>  $this->request->getPost('simple_price'),
                            'rrp' =>  $this->request->getPost('simple_rrp'),
                            'weight' =>  $this->request->getPost('weight'),
                            'height' =>  $this->request->getPost('height'),
                            'breadth' =>  $this->request->getPost('breadth'),
                            'length' =>  $this->request->getPost('length'),
                            // 'sku' =>  $this->request->getPost('product_sku'),
                            'stock' =>  $this->request->getPost('product_total_stock'),
                            'status' =>  $this->request->getPost('simple_product_stock_status'),
                        ];

                        $product_added = $this->productsVariantsTable->insert($product_data);

                        $tags = $this->request->getPost('tags');
                        foreach ($tags as $tag) {
                            $assign_product_tags = [
                                'product_id' =>  $product_added_id,
                                'tag_id' =>  $tag,
                            ];
                            $assign_inserted_id = $this->productTagsTable->insert($assign_product_tags);
                        }

                        $slug_data = [
                            'type' =>  'product',
                            'ref_id' =>  $product_added_id,
                            'slug' =>  $this->request->getPost('pro_input_slug'),
                        ];

                        $slug_added = $this->slugTable->insert($slug_data);

                        $session->setFlashdata('status', 'success');
                        $session->setFlashdata('message', 'Product Added Successfully');
                    } else {
                        $session->setFlashdata('status', 'error');
                        $session->setFlashdata('message', 'Something went wrong');
                    }
                }
            } else {
                 $session->setFlashdata('status', 'error');
                 $session->setFlashdata('message', 'Product Main Image is required!');
            }

            // Validation passed, process the form data
            return redirect()->to('/admin/product/create_product');

        }else{
            return redirect()->to('/admin/product/create_product');
        }
    }

    public function delete_product()
    {
        $product_id = $this->request->getGet('id');
        $product_deleted = $this->productsTable->delete($product_id);

        if ($product_deleted) {
           $response['error'] = false;
           $response['message'] = 'Product Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Product not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function delete_product_image()
    {
        $product_image_id = $this->request->getGet('id');
        $image_deleted = $this->productImagesTable->delete($product_image_id);

        if ($image_deleted) {
           $response['error'] = false;
           $response['message'] = 'Product Image Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Product Image not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function delete_related_product()
    {
        $related_id = $this->request->getGet('id');
        $related_deleted = $this->relatedProductsTable->delete($related_id);

        if ($related_deleted) {
           $response['error'] = false;
           $response['message'] = 'Related Product Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Related Product not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function delete_product_feature()
    {
        $feature_id = $this->request->getGet('id');
        $feature_deleted = $this->productFeaturesTable->delete($feature_id);

        if ($feature_deleted) {
           $response['error'] = false;
           $response['message'] = 'Product Feature Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Product Feature not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function delete_product_option()
    {
        $product_option_id = $this->request->getGet('id');
        $product_option_deleted = $this->relatedProductOptionsTable->delete($product_option_id);

        if ($product_option_deleted) {
           $response['error'] = false;
           $response['message'] = 'Product Option Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Product Option not Deleted Succesfully';
            echo(json_encode($response));
        }
    }
    
    public function delete_product_variant()
    {
        $product_variant_id = $this->request->getGet('id');
        $product_variant_deleted = $this->productColorVariantsTable->delete($product_variant_id);

        if ($product_variant_deleted) {
           $response['error'] = false;
           $response['message'] = 'Product Variant Deleted Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['message'] = 'Product Variant not Deleted Succesfully';
            echo(json_encode($response));
        }
    }

    public function edit_product()
    {

        $edit_id = $this->request->getGet('edit_id');

        if(isset($edit_id)){

            $data['main_page'] = FORMS . 'edit-product';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Edit Product | ' . $settings['app_name'];
            $data['meta_description'] = 'Edit Product | ' . $settings['app_name'];
    
            $data['shipping_method'] = get_settings('shipping_method', true);
            $data['payment_method'] = get_settings('payment_method', true);
            $data['system_settings'] = get_settings('system_settings', true);
    
            $data['tax_details'] = $this->taxesTable->findAll();
            $data['category'] = $this->categoryTable->findAll();
            $data['brands'] = $this->brandsTable->findAll();
            
            // $data['products_list'] = $this->productsTable->findAll();
            // $data['products_list'] = $this->productsTable->where('id !=', $edit_id)->where('status',1)->findAll();
            // $data['products_list'] = [];
            $data['related_products'] = $this->relatedProductsTable->getRelatedProductsListing($edit_id);
            
            $data['product_images'] = $this->productImagesTable->where('product_id', $edit_id)->findAll();
            
            $data['features_list'] = $this->productMasterFeaturesTable->findAll();
            $data['product_features'] = $this->productFeaturesTable->getProductFeaturesListing($edit_id);
            $data['product_options'] = $this->relatedProductOptionsTable->getProductOptionsListing($edit_id);
            $data['product_color_variants'] = $this->productColorVariantsTable->getProductColorVariantsListing($edit_id);

            $data['product_details'] = $this->productsTable->where('id', $edit_id)->findAll();
            $data['product_variants'] = $this->productsVariantsTable->where('product_id', $edit_id)->findAll();

            $selected_tags = $this->productTagsTable->where('product_id', $edit_id)->findAll();

            $tag_list = "";
            foreach ($selected_tags as $tag) {
                $tag_list .= $tag['tag_id'] . ",";
            }

            $data['selected_tags'] = $tag_list;

            $product_category_id = $data['product_details'][0]['category_id'];
            // echo $product_category_id; 

            $data['attributeSets'] = $this->attributeSetCategoryTable->getCategoryAttributeSet($product_category_id);
            $data['selectedAttributes'] = $this->productAttributesTable->getProductAllAttributes($edit_id);

            // dd($data['attributeSets']);
            // exit;

            // dd($data['selected_tags']);
            return view('admin/template', $data);
            
        }else{
            return redirect()->to('/admin/product');
        }        
    
    }

    public function update_product()
    {
        // Load the form helper and session for flash messages
        helper(['form', 'text']);
        $session = session();

        // Check if the form is submitted
        if ($this->request->getMethod() === 'POST') {
            
            // Define Validation Rules
            $rules = [
                'pro_input_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Product Name is required!',
                    ]
                ],
                'pro_input_slug' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Product Slug is required!',
                    ]
                ],
                'short_description' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Short Description is required!',
                    ]
                ],
                'category_id' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'    => 'The Category is required!',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                // Validation failed, return with errors
                $session->setFlashdata('validation', $this->validator);
                return redirect()->to('/admin/product/edit_product?edit_id='.$this->request->getPost('edit_product_id'));
            }

            $edit_product_id = $this->request->getPost('edit_product_id');
            $edit_product_variants_id = $this->request->getPost('edit_product_variants_id');

            $edit_product_tags = rtrim($this->request->getPost('edit_product_tags'), ",");;
            $existing_product_tag_array = explode(',', $edit_product_tags);
            $new_tags = $this->request->getPost('tags');
            if(!empty($new_tags)){
                $difference_tags = array_diff($existing_product_tag_array, $new_tags);
            }else{
                $difference_tags = [];
            }

            $exist_products = $this->slugTable
                                    ->where('slug', $this->request->getPost('pro_input_slug'))
                                    ->where('ref_id <>', $edit_product_id)
                                    ->first();

            if($exist_products){
                $session->setFlashdata('status', 'error');
                $session->setFlashdata('message', 'Product Slug Already exist you should use a different name');
            }else{

                // Get uploaded file
                $main_image = $this->request->getFile('image');

                if($main_image != ""){
                    // Move the file to the public/uploads directory
                    $mainImage = $main_image->getRandomName();
                    $main_image->move('uploads/products', $mainImage);
                    $main_image_path = "uploads/products/".$mainImage;
                }else{
                    $main_image_path = $this->request->getPost('edit_product_image');
                }
                
                $data = [
                    'name' =>  $this->request->getPost('pro_input_name'),
                    'slug' =>  $this->request->getPost('pro_input_slug'),
                    // 'tags' =>  input_tags_to_comma_string($this->request->getPost('tags')),
                    'category_id' =>  $this->request->getPost('category_id'),
                    'brand' =>  $this->request->getPost('brand'),
                    'is_feature' =>  $this->request->getPost('is_feature') ? 1 : 0,
                    'is_discount' =>  $this->request->getPost('is_discount') ? 1 : 0,
                    'is_hot_deal' =>  $this->request->getPost('is_hot_deal') ? 1 : 0,
                    'model' =>  $this->request->getPost('model'),
                    'vpn' =>  $this->request->getPost('vpn'),
                    'gtin' =>  $this->request->getPost('gtin'),
                    'image' =>  $main_image_path,
                    'warranty_period' =>  $this->request->getPost('warranty_period'),
                    'guarantee_period' =>  $this->request->getPost('guarantee_period'),
                    'is_prices_inclusive_tax' =>  $this->request->getPost('is_prices_inclusive_tax') ? 1 : 0,
                    'cod_allowed' =>  $this->request->getPost('cod_allowed') ? 1 : 0,
                    'is_returnable' =>  $this->request->getPost('is_returnable') ? 1 : 0,
                    'is_cancelable' =>  $this->request->getPost('is_cancelable') ? 1 : 0,
                    'is_attachment_required' =>  $this->request->getPost('is_attachment_required') ? 1 : 0,
                    'video_type' =>  $this->request->getPost('video_type'),
                    'video' =>  $this->request->getPost('video'),
                    'seo_page_title' =>  $this->request->getPost('seo_page_title'),
                    'seo_meta_keywords' =>  input_tags_to_comma_string($this->request->getPost('seo_meta_keywords')),
                    'seo_meta_description' =>  $this->request->getPost('seo_meta_description'),
                    'seo_og_image' =>  $this->request->getPost('seo_og_image'),
                    'short_description' =>  $this->request->getPost('short_description'),
                    'description' =>  $this->request->getPost('pro_input_description'),
                    'extra_description' =>  $this->request->getPost('extra_input_description'),
                    'specification' =>  $this->request->getPost('pro_input_specification'),
                    'configure_me' =>  $this->request->getPost('configure_me'),
                    'submit_to_google' =>  $this->request->getPost('submit_to_google'),
                    'is_best_seller' =>  $this->request->getPost('is_best_seller') ? 1 : 0,
                    'status' =>  $this->request->getPost('status') ? 1 : 0,
                ];

                // dd($data);
                $product_update = $this->productsTable->update($edit_product_id, $data);

                if ($product_update) {

                    // other images for products
                    $files = $this->request->getFiles();

                    if ($files) {
                        foreach ($files['other_images'] as $file) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move('uploads/other_products', $newName);
                                // $uploadedFiles[] = "uploads/other_products/".$newName;
                                $uploads_image_path = "uploads/other_products/".$newName;

                                $images_data = [
                                    'product_id' =>  $edit_product_id,
                                    'image' =>  $uploads_image_path,
                                ];

                                $product_image_id = $this->productImagesTable->insert($images_data);
                                
                            }
                        }
                    }

                    $product_data = [
                        'price' =>  $this->request->getPost('simple_price'),
                        'rrp' =>  $this->request->getPost('simple_rrp'),
                        'weight' =>  $this->request->getPost('weight'),
                        'height' =>  $this->request->getPost('height'),
                        'breadth' =>  $this->request->getPost('breadth'),
                        'length' =>  $this->request->getPost('length'),
                        // 'sku' =>  $this->request->getPost('product_sku'),
                        'stock' =>  $this->request->getPost('product_total_stock'),
                        'status' =>  $this->request->getPost('simple_product_stock_status'),
                    ];

                    $product_variants_updated = $this->productsVariantsTable->update($edit_product_variants_id, $product_data);

                    if(isset($difference_tags) && !empty($difference_tags) ){
                        // delete removed tags first
                        foreach ($difference_tags as $tag) {
                            $this->productTagsTable
                                    ->where('product_id', $edit_product_id)
                                    ->where('tag_id', $tag)
                                    ->delete();
                        }
                    }

                    // add/update new tags
                    if(isset($new_tags) && !empty($new_tags) ){
                        foreach ($new_tags as $tag) {

                            $edit_product_tag_id = $this->productTagsTable
                                                    ->where('product_id', $edit_product_id)
                                                    ->where('tag_id', $tag)
                                                    ->first();
    
                            if(empty($edit_product_tag_id['id'])){
    
                                $assign_product_tags = [
                                    'product_id' =>  $edit_product_id,
                                    'tag_id' =>  $tag,
                                ];
                                $assign_inserted_id = $this->productTagsTable->insert($assign_product_tags);
    
                            }                                               
    
                        }
                        
                    }

                    // update if exist or new add
                    $slug_data = [
                        'type' =>  'product',
                        'ref_id' =>  $edit_product_id,
                        'slug' =>  $this->request->getPost('pro_input_slug'),
                    ];
                    $findOrCreate = $this->slugTable->findOrCreate($slug_data);  

                    $session->setFlashdata('status', 'success');
                    $session->setFlashdata('message', 'Product Updated Successfully');
                } else {
                    $session->setFlashdata('status', 'error');
                    $session->setFlashdata('message', 'Something went wrong');
                }     

            }

            // Validation passed, process the form data
            return redirect()->to('/admin/product/edit_product?edit_id='.$this->request->getPost('edit_product_id'));

        }else{
            return redirect()->to('/admin/product/edit_product?edit_id='.$this->request->getPost('edit_product_id'));
        }
    }

    public function product_options_products()
    {
        $category_id = $this->request->getGet('id');
        $edit_product_id = $this->request->getGet('edit_product_id');
        $product_lists = $this->productsTable->select("id, name")->where('category_id', $category_id)->where('id !=', $edit_product_id)->findAll();

        if ($product_lists) {
           $response['error'] = false;
           $response['data'] = $product_lists;
           $response['message'] = 'Filter Product Fetched Succesfully';
            echo(json_encode($response));
        } else {
            $response['error'] = true;
            $response['data'] = [];
            $response['message'] = 'Products not found, try other category';
            echo(json_encode($response));
        }
    }

    public function add_product_options()
    {
        $option_product_id = $this->request->getGet('id');
        $edit_product_id = $this->request->getGet('product_id');
        $category_id = $this->request->getGet('category_id');

        if($option_product_id != ""){
            $exist_related_product_options = $this->relatedProductOptionsTable
                ->where('product_id', $edit_product_id)
                ->where('related_product_option_id', $option_product_id)
                ->first();

            if(empty($exist_related_product_options)){

                $related_product_option_data = [
                    'product_id' =>  $edit_product_id,
                    'related_product_option_id' =>  $option_product_id,
                    'category_id' =>  $category_id,
                ];
                $related_product_option_added_id = $this->relatedProductOptionsTable->insert($related_product_option_data);
                
                if ($related_product_option_added_id) {

                    $data_product_options = $this->productsTable
                        ->where('id', $option_product_id)
                        ->first();

                    $response['error'] = false;
                    $response['data'] = [
                        'option_id' => $related_product_option_added_id,
                        'product_name' => $data_product_options['name'],
                    ];
                    $response['message'] = 'Product Option Added Succesfully';
                    echo(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Product Option not added';
                    echo(json_encode($response));
                }

            }else{
                $response['error'] = true;
                $response['message'] = 'Already Product Option added';
                echo(json_encode($response));
            }
        }       

    }

    public function add_related_product()
    {
        $related_product_id = $this->request->getGet('id');
        $edit_product_id = $this->request->getGet('product_id');

        if($related_product_id != ""){
            $exist_related_product = $this->relatedProductsTable
                ->where('product_id', $edit_product_id)
                ->where('related_product_id', $related_product_id)
                ->first();

            if(empty($exist_related_product)){

                $related_product_data = [
                    'product_id' =>  $edit_product_id,
                    'related_product_id' =>  $related_product_id,
                ];
                $related_product_added_id = $this->relatedProductsTable->insert($related_product_data);
                
                if ($related_product_added_id) {

                    $data_product_options = $this->productsTable
                        ->where('id', $related_product_id)
                        ->first();

                    $response['error'] = false;
                    $response['data'] = [
                        'related_id' => $related_product_added_id,
                        'product_name' => $data_product_options['name'],
                    ];
                    $response['message'] = 'Related Product Added Succesfully';
                    echo(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Related Product not added';
                    echo(json_encode($response));
                }

            }else{
                $response['error'] = true;
                $response['message'] = 'Already Related Product added';
                echo(json_encode($response));
            }
        } else{
            $response['error'] = true;
            $response['message'] = 'Select related product';
            echo(json_encode($response));
        }     

    }

    public function add_product_feature()
    {
        $product_feature_id = $this->request->getGet('id');
        $edit_product_id = $this->request->getGet('product_id');

        if($product_feature_id != ""){
            $exist_product_feature = $this->productFeaturesTable
                ->where('product_id', $edit_product_id)
                ->where('feature_id', $product_feature_id)
                ->first();

            if(empty($exist_product_feature)){

                $product_feature_data = [
                    'product_id' =>  $edit_product_id,
                    'feature_id' =>  $product_feature_id,
                ];
                $product_feature_added_id = $this->productFeaturesTable->insert($product_feature_data);
                
                if ($product_feature_added_id) {

                    $data_product_master_feature = $this->productMasterFeaturesTable
                        ->where('id', $product_feature_id)
                        ->first();

                    $response['error'] = false;
                    $response['data'] = [
                        'feature_id' => $product_feature_added_id,
                        'feature_text' => $data_product_master_feature['text'],
                    ];
                    $response['message'] = 'Product feature Added Succesfully';
                    echo(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Product feature not added';
                    echo(json_encode($response));
                }

            }else{
                $response['error'] = true;
                $response['message'] = 'Already Product feature added';
                echo(json_encode($response));
            }
        } else{
            $response['error'] = true;
            $response['message'] = 'Select product feature';
            echo(json_encode($response));
        }     

    }

    public function add_product_variations()
    {
        $product_id = $this->request->getGet('product_id');
        $color = $this->request->getGet('color');
        $label = $this->request->getGet('label');
        $product_variant_id = $this->request->getGet('product_variant_id');

        if($product_id != "" && $color != "" && $label != "" && $product_variant_id != ""){
            $exist_product_variant = $this->productColorVariantsTable
                ->where('product_id', $product_id)
                ->where('product_variant_id', $product_variant_id)
                ->first();

            if(empty($exist_product_variant)){

                $product_variant_data = [
                    'product_id' =>  $product_id,
                    'color' =>  $color,
                    'label' =>  $label,
                    'product_variant_id' =>  $product_variant_id,
                ];
                $product_variant_added_id = $this->productColorVariantsTable->insert($product_variant_data);
                
                if ($product_variant_added_id) {

                    $data_product_options = $this->productsTable
                        ->where('id', $product_variant_id)
                        ->first();

                    $response['error'] = false;
                    $response['data'] = [
                        'variant_id' => $product_variant_added_id,
                        'color' => $color,
                        'label' => $label,
                        'product_name' => $data_product_options['name'],
                    ];
                    $response['message'] = 'Product Variant Added Succesfully';
                    echo(json_encode($response));
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Product Variant not added';
                    echo(json_encode($response));
                }

            }else{
                $response['error'] = true;
                $response['message'] = 'Already Product Variant added';
                echo(json_encode($response));
            }
        } else{
            $response['error'] = true;
            $response['message'] = 'All fields are Required';
            echo(json_encode($response));
        }   

    }

    public function fetchProducts()
    {

        $request = service('request');

        $draw = (int) $request->getPost('draw'); // Ensure integer
        $start = (int) $request->getPost('start'); // Ensure integer
        $length = (int) $request->getPost('length'); // Ensure integer
        // $searchValue = $request->getPost('search')['value'];


        $searchFilter = $this->request->getPost('search');
        $categoryFilter = $this->request->getPost('category'); // Dropdown filter
        $attributeSetFilter = $this->request->getPost('attribute_set'); // Dropdown filter
        $attributeNameFilter = $this->request->getPost('attribute_name'); // Dropdown filter
        $attributeNotAssignFilter = $this->request->getPost('attribute_not_assign'); // Dropdown filter
        $brandFilter = $this->request->getPost('brand'); // Dropdown filter
        $statusFilter = $this->request->getPost('status'); // Dropdown filter

        $query = $this->productsTable;

        $query->select('products.*, brands.name as brand_name, categories.name as category_name');

        $query->join('brands', 'brands.id = products.brand', 'left');
        $query->join('categories', 'categories.id = products.category_id', 'left');
        // $query->join('product_attributes', 'product_attributes.product_id = products.id', 'left');

          // if (!empty($searchValue)) {
        //     $query->like('products.name', $searchValue);
        // }

        if (!empty($attributeSetFilter) || !empty($attributeNameFilter)) {
            $query->join('product_attributes', 'product_attributes.product_id = products.id', 'left');
        }

         if (!empty($attributeSetFilter) && $attributeNotAssignFilter == 1) {
            $query = $query->where('product_attributes.attribute_id', $attributeSetFilter);
        }else if (!empty($attributeSetFilter) && $attributeNotAssignFilter == 0) {
            $query = $query->where('product_attributes.attribute_id !=', $attributeSetFilter);
        }

        if (!empty($attributeNameFilter) && $attributeNotAssignFilter == 1) {
            $query = $query->where('product_attributes.attribute_value_id', $attributeNameFilter);
        } else if (!empty($attributeNameFilter) && $attributeNotAssignFilter == 0) {
            $query = $query->where('product_attributes.attribute_value_id !=', $attributeNameFilter);
        }

        if (!empty($searchFilter)) {
            $query = $query->like('products.name', $searchFilter);
            $query = $query->orLike('products.model', $searchFilter);
            $query = $query->orLike('products.description', $searchFilter);
        }
        if (!empty($categoryFilter)) {
            $query = $query->where('products.category_id', $categoryFilter);
        }

        if (!empty($brandFilter)) {
            $query = $query->where('products.brand', $brandFilter);
        }
        if (!empty($statusFilter) || $statusFilter == 0) {
            $query = $query->where('products.status', $statusFilter);
        }
        
        if (!empty($attributeSetFilter) || !empty($attributeNameFilter)) {
            $query->groupBy('products.id');
        }

        $query->orderBy('products.id', 'DESC');

        $totalRecords = $query->countAllResults(false);
        $filteredRecords = $query->countAllResults(false);

        $products = $query->limit($length, $start)->find();
        
        // echo $query->db->getLastQuery(); exit;

        $data = [];
        foreach ($products as $product) {

            $data[] = [
                'select' => '<input type="checkbox" class="product-checkbox" value="' . $product['id'] . '">',
                'image' => '<div class="image-box-100">
                    <a href="'.base_url().$product['image'].'"
                        data-toggle="lightbox">
                        <img class="rounded" src="'.base_url().$product['image'].'">
                    </a>
                </div>',
                'name' => $product['name'],
                'brand' => $product['brand_name'],
                'category' => $product['category_name'],
                'action' => '<a href="'.base_url('admin/product/edit_product?edit_id='.$product['id']).'"
                  class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                     data-id="1" data-url="admin/category/create_category"><i class="fa fa-pen"></i></a>
                <a class="delete-product btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="'.$product['id'].'">
                    <i class="fa fa-trash"></i>
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

    public function assign_product_attributes()
    {
        $categoryId = $this->request->getGet('category_id');
        $productIds = $this->request->getGet('product_ids');
        // echo $productIds; exit;
        if(isset($categoryId, $productIds)){
            
            $data['main_page'] = FORMS . 'edit-assign-attributes';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Assign Attributes | ' . $settings['app_name'];
            $data['meta_description'] = 'Assign Attributes | ' . $settings['app_name'];
            
            $data['attributeSets'] = $this->attributeSetCategoryTable->getCategoryAttributeSet($categoryId);
            $data['product_ids'] = $productIds;

            $productArr = explode(',', $productIds);
            $products = $this->productsTable->select('category_id')->whereIn('id', $productArr)->findAll();

            $matchCategory = 0;

            if($matchCategory == 0){
                foreach ($products as $key => $product) {
                    if($product['category_id'] != $categoryId){
                        $matchCategory = 1;
                    }
                    // dd($product);
                }
            }

            $data['missmatch_category'] = $matchCategory;

            // echo $matchCategory; exit;
            // dd($products);
            // exit;

            // dd($data['selected_tags']);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/product');
        } 
     
    }

    public function assign_product_tags()
    {
        $productIds = $this->request->getGet('product_ids');
        // echo $productIds; exit;
        if(isset($productIds)){
            
            $data['main_page'] = FORMS . 'edit-assign-tags';

            $settings = get_settings('system_settings', true);
            $data['title'] = 'Assign Attributes | ' . $settings['app_name'];
            $data['meta_description'] = 'Assign Attributes | ' . $settings['app_name'];
            
            $data['product_ids'] = $productIds;

            // dd($data['attributeSets']);
            // exit;

            // dd($data['selected_tags']);
            return view('admin/template', $data);

        }else{
            return redirect()->to('/admin/product');
        } 
     
    }

    public function deleteProducts()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            $query = $this->productsTable;
            $query->whereIn('id', $ids)->delete();
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error']);
    }

    public function changeProductsCategory()
    {
        $ids = $this->request->getPost('ids');
        $category_id = $this->request->getPost('category');
        if (!empty($ids)) {

            $data = [
                'category_id' => $category_id
            ];

            $query = $this->productsTable;
            $query->whereIn('id', $ids)->set($data)->update();
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error']);
    }

    public function getAttributeSetByCategory()
    {
        $query = $this->attributeSetCategoryTable;
        $categoryId = $this->request->getPost('category_id');
        $attributeSets = $query->getCategoryAttributeSet($categoryId);

        return $this->response->setJSON($attributeSets);
    }

    public function getAttributeNameByAttributeSet()
    {
        $query = $this->attributesTable;
        $attributeSetId = $this->request->getPost('attribute_set_id');
        $attributeNames = $query->getAttributeSetAttributeName($attributeSetId);

        return $this->response->setJSON($attributeNames);
    }

    public function saveProductAttributes()
    {
        // $query = $this->attributesTable;
        // $attributeSetId = $this->request->getPost('attribute_set_id');
        // $attributeNames = $query->getAttributeSetAttributeName($attributeSetId);
        
        // return $this->response->setJSON($attributeNames);


        // echo '111'; exit;
        $deleteAttributes = $this->request->getPost('deleteAttributes');
        $attributes = $this->request->getPost('attributes');
        
        $deleteAttributesIds = explode(",", $deleteAttributes);
        $this->productAttributesTable->whereIn('id', $deleteAttributesIds)->delete();

        // print_r($deleteAttributes); exit;
        foreach ($attributes as $attribute) {

            $product_id = $attribute['product_id'];
            $name = $attribute['name'];
            $value = $attribute['value'];
            $possiblevalue = $attribute['possiblevalue'];

            $attrdata = [
                "product_id" => $product_id,
                "attribute_id" => $name,
                "attribute_value_id" => $value,
                "added_attribute_value" => $possiblevalue,
            ];

            $this->productAttributesTable->attributefindOrCreate($attrdata);
        }

        echo json_encode(["success" => true]);

    }

    public function saveMultipleProductAttributes()
    {

        // echo '111'; exit;
        $products = $this->request->getPost('products');
        $attributes = $this->request->getPost('attributes');
        $is_deleted = $this->request->getPost('is_deleted');
        
        $productsIds = explode(",", $products);

        foreach ($productsIds as $productId) {

            // echo $productId; exit;

            if($is_deleted == 1){
                $this->productAttributesTable->where('product_id', $productId)->delete();
            }

            foreach ($attributes as $attribute) {
                $attrdata = [
                    "product_id" => $productId,
                    "attribute_id" => $attribute['attribute_id'],
                    "attribute_value_id" => $attribute['attribute_value_id'],
                    "added_attribute_value" => $attribute['added_attribute_value'],
                ];
                // print_r($attrdata); exit;
                $this->productAttributesTable->multipleAttributefindOrCreate($attrdata);
            }

        }

        echo json_encode(["success" => true]);

    }

    public function saveMultipleProductTags()
    {

        // echo '111'; exit;
        $products = $this->request->getPost('products');
        $tags = $this->request->getPost('tags');
        
        $productsIds = explode(",", $products);

        foreach ($productsIds as $productId) {

            // echo $productId; exit;

            foreach ($tags as $tag) {
                $tagdata = [
                    "product_id" => $productId,
                    "tag_id" => $tag,
                ];
                // print_r($tagdata); exit;
                $this->productTagsTable->multipleProductTagsfindOrCreate($tagdata);
            }

        }

        echo json_encode(["success" => true]);

    }

    public function searchRelatedProducts()
    {
        $query = $this->request->getGet('query');
        $edit_id = $this->request->getGet('edit_id');
        
        // Fetch products where the name matches the search term
        $products = $this->productsTable->like('name', $query)->where('id !=', $edit_id)->where('status', 1)->findAll();

        return $this->response->setJSON($products);
    }

    public function searchCategory()
    {
        $query = $this->request->getGet('query');
        
        // Fetch categories where the name matches the search term
        $categories = $this->categoryTable->like('name', $query)->where('status', 1)->findAll();

        return $this->response->setJSON($categories);
    }

    public function searchCategoryFilterProducts()
    {
        $query = $this->request->getGet('query');
        $category_id = $this->request->getGet('category_id');
        $edit_id = $this->request->getGet('edit_id');
        
        // Fetch products where the name matches the search term
        if(isset($category_id) && $category_id != ""){
            $products = $this->productsTable->like('name', $query)->where('category_id', $category_id)->where('id !=', $edit_id)->where('status', 1)->findAll();
            return $this->response->setJSON($products);
        }
    }
    
}