<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;

use App\Models\Products;
use App\Models\ProductColorVariants;
use App\Models\AttributeSetCategory;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\AttributeSet;
use App\Models\Attributes;
use App\Models\ProductMasterTags;
use App\Models\ProductTags;

use App\Models\Menus;
use App\Models\Custommenus;
use App\Models\CustommenusSub;

class CategoryController extends BaseController
{

    public function index()
    {

        $categoriesModel = new Categories();
        $categories = $categoriesModel
                    ->where('parent_id', 0)
                    ->where('status', 1)
                    ->orderBy('name','asc')->findAll();
        // $parent_categories = $categoriesModel->getParentCategories();

        $categoryData = [
            'name' => "All Categories",
        ];

        // dd($parent_categories);
        return view('frontend/categories', ['category_data' => $categoryData, 'categories' => $categories]);
    }

    public function sub_category($parent_slug,$filterParam=null,$forwarded=null)
    {

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $categoriesModel = new Categories();
        $parent_category = $categoriesModel
                    ->where('slug', $parent_slug)
                    ->first();
        
        // dd($parent_category);
        // if(isset($parent_category['parent_id']) && $parent_category['parent_id'] != ""){
        //     $breadcrumb = $categoriesModel->getCategoryBreadcrumb($parent_category['parent_id']);
        // }

        $menu_breadcrumb = $breadcrumb = [];
        if(!empty($forwarded)){
            // print_r($forwarded); exit;
            $menu_breadcrumb = $this->megaMenuBreadcrumb($forwarded);
            // print_r($breadcrumb);
            // exit;
        }else{
            $breadcrumb = $categoriesModel->getCategoryBreadcrumb($parent_category['parent_id']);
        }

        $sub_categories = $categoriesModel
                    ->where('parent_id', $parent_category['id'])
                    ->where('status', 1)
                    ->orderBy('name','asc')->findAll();

        $subCategoryData = [
            'id' => $parent_category['id'],
            'name' => $parent_category['name'],
            'description' => $parent_category['description'],
        ];


        $attributeSetModel = new AttributeSet();
        $attributeModel = new Attributes();

        $excludedKeys = ['brands', 'search', 'filterBy', 'page']; // Keys to exclude
        $queryParams = array_diff_key($_GET, array_flip($excludedKeys));

        // echo "<pre>";
        $person = [];
        if(!empty($queryParams)){
            // print_r($queryParams); exit;

            $index = 0;
           
            // echo "<pre>";
            foreach ($queryParams as $filterName => $filterValue) {
                // echo $filterName.' - '.$filterValue.'<br>'; exit;

                // $selectedCustomAttributes = isset($_GET[$filterName]) ? explode(' ', $_GET[$filterName]) : [];
                // print_r($selectedCustomAttributes);
                // foreach ($selectedCustomAttributes as $attributeName) {
                //     // echo $index.' - '.$filterName.' - '.$attributeName.'<br>';
                    // $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$filterName)->first();
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('slug',$filterValue)->first();
                    $attributeData = $attributeModel->getAttributesIdFromCategory($parent_category['id'], $filterName);
                    // print_r($attributeData); exit;
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('attribute_set_id',$attributeSetData['id'])->where('name',$attributeName)->first();
                //     $person[$index]['filter_name'] = $filterName;
                //     $person[$index]['attribute_set_id'] = $attributeSetData['id'];
                //     $person[$index]['attribute_name'] = $attributeName;
                //     $person[$index]['attribute_id'] = $attributeData['id'];
                //     $index++;
                // }

                
                $person[$index]['filter_name'] = $filterName;
                $person[$index]['filter_value'] = $filterValue;
                if(!empty($attributeData['attribute_set_id'])){
                    $person[$index]['attribute_set_id'] = $attributeData['attribute_set_id'];
                }
                // $person[$index]['attribute_name'] = $attributeName;
                // $person[$index]['attribute_id'] = $attributeData['id'];

                $index++;
            }
            // dd($person); exit;
        }
        // exit;

       

        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);


        $categoriesModel = new Categories();
        // $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        $selectedBrands = $selectedTags = [];
        $minPrice = $maxPrice = $filterBy = $search = '';


       if(!empty($filterParam) && empty($_GET)){
        $filterParamFinal = ltrim($filterParam,"?");
        $arrParam = explode('&', $filterParamFinal);

        parse_str($filterParamFinal, $params);

        foreach ($params as $key => $filter) {
            // echo $key;
            // print_r($filter);


            if($key == 'brands'){
                $selectedBrands = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'tags'){
                $selectedTags = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'minPrice'){
                $minPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'maxPrice'){
                $maxPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'filterBy'){
                $filterBy = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'search'){
                $search = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }

            if($key != 'brands' && $key != 'tags' && $key != 'minPrice' && $key != 'maxPrice' && $key != 'filterBy' && $key != 'search'){
       
                $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$key)->first();
                $person[$index]['filter_name'] = $key;
                $person[$index]['filter_value'] = $filter;
                $person[$index]['attribute_set_id'] = $attributeSetData['id'];

            }

            $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];

        }        

        // print_r($arrParam); exit;
       }
       else{
            // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
            $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
            $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
            $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
            $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
            $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
            $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
            $search = isset($_GET['search']) ? $_GET['search'] : '';
       }
        $filterCategory = [$parent_category['id']];

        $filterBrand = [];
        if($selectedBrands && !empty($selectedBrands)){
            $brand_list = "";
            foreach ($selectedBrands as $brand_slug) {
                $brandData = $brandModel->select('id, name')->where('slug',$brand_slug)->first();
                $brand_list .= $brandData['id'] . ",";
            }
            $brand_comma_string = rtrim($brand_list,',');
            $filterBrand = explode(',', $brand_comma_string);
        }

        $filterAttributes = [];
        if($selectedAttributeSets && !empty($selectedAttributeSets)){
            $attr_list = "";
            foreach ($selectedAttributeSets as $attribute_slug) {
                $attributeData = $attributeSetModel->select('id, name')->where('slug',$attribute_slug)->first();
                $attr_list .= $attributeData['id'] . ",";
            }
            $attr_comma_string = rtrim($attr_list,',');
            $filterAttributes = explode(',', $attr_comma_string);
        }

        $filterTags = [];
        if($selectedTags && !empty($selectedTags)){
            $tags_list = "";
            foreach ($selectedTags as $tag_slug) {
                // echo $tag_slug. '<br>';
                $tagData = $productMasterTagsModel->select('id, name')->where('slug',$tag_slug)->first();
                $tags_list .= $tagData['id'] . ",";
            }
            $tags_comma_string = rtrim($tags_list,',');
            $filterTags = explode(',', $tags_comma_string);
        }

        // exit;
        // dd($filterTags);


        $productsModel = new Products();
        // $products = $productsModel->getProductsFiltersListing($search, $filterBy, [8, 9], [1]);
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags);
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags)); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        // $categories = $categoriesModel->getProductCountWithCategory();

        

        if(!empty($person)){
            $brands = $brandModel->getProductCountWithBrandWithAttributes($subCategoryData['id'], $person);
        }else{
            $brands = $brandModel->getProductCountWithBrands($subCategoryData['id']);
        }
        
        // $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();

        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategoryMultiple($filterCategory, $filterBrand);

        $productAttributesSetsValues = [];
        $index = 0;

        // dd($productAttributesSets);

        foreach($productAttributesSets as $attributeSet){
            $attrSetId = $attributeSet['id'];
            $attributeCategoryId = $attributeSet['category_id'];
            $productAttributesSetsValues[$index] = [
                'set_id' => $attributeSet['id'],
                'set_name' => $attributeSet['name'],
                'set_slug' => $attributeSet['slug'],
            ];
            $productAttributesSetValues = $attributeSetCategoryModel->getProductAttributesSetValues($attributeSet['id']);
            // dd($productAttributesSetValues);
            $dropdownArray = [];
            foreach($productAttributesSetValues as $attributeSetValue){
             
                if(!empty($person)){
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCountsParentAttributes($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_name'], $filterBrand, $person);
                }else{
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCounts($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_name'], $filterBrand);
                }

                // dd($attributeProductCounts->countRes);
                if($attributeProductCounts->countRes > 0){
                    $dropdownArray[] = [
                        'attribute_id' => $attributeSetValue['attribute_id'],
                        'attribute_name' => $attributeSetValue['attribute_name'],
                        'attribute_slug' => $attributeSetValue['attribute_slug'],
                        'product_count' => $attributeProductCounts->countRes,
                    ];
                }
                // dd($dropdownArray);
            }
            $productAttributesSetsValues[$index]['dropdowns'] = $dropdownArray;
            $index++;
        }

        // dd($productAttributesSetsValues);

        $productTags = $productTagsModel->getProductCountWithTags();

        // dd($productTags);
        return view('frontend/categories', ['category_data' => $subCategoryData, 'menu_breadcrumb' => $menu_breadcrumb, 'breadcrumb' => $breadcrumb, 'parent_category' => $parent_category, 'categories' => $sub_categories, 'products' => $products, 'total_products' => $totalProducts, 'attribute_set_values' => $productAttributesSetsValues, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '')]);

        // return view('frontend/categories', ['category_data' => $subCategoryData, 'parent_category' => $parent_category, 'categories' => $sub_categories, 'products' => $products]);
    }

    public function sub_sub_category($parent_slug, $category_slug,$filterParam=null,$forwarded=null)
    {

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $categoriesModel = new Categories();

        $parent_category = $categoriesModel
                    ->where('slug', $parent_slug)
                    ->first();

        $sub_category = $categoriesModel
                    ->where('slug', $category_slug)
                    ->first();

        $sub_sub_categories = $categoriesModel
                    ->where('parent_id', $sub_category['id'])
                    ->where('status', 1)
                    ->orderBy('id','asc')->findAll();

        $subSubCategoryData = [
            'id' => $sub_category['id'],
            'name' => $sub_category['name'],
            'description' => $sub_category['description'],
        ];

        $attributeSetModel = new AttributeSet();
        $attributeModel = new Attributes();

        $excludedKeys = ['brands', 'search', 'filterBy', 'page']; // Keys to exclude
        $queryParams = array_diff_key($_GET, array_flip($excludedKeys));

        // echo "<pre>";
        $person = [];
        if(!empty($queryParams)){
            // print_r($queryParams); exit;

            $index = 0;
           
            // echo "<pre>";
            foreach ($queryParams as $filterName => $filterValue) {
                // echo $filterName.' - '.$filterValue.'<br>'; exit;

                // $selectedCustomAttributes = isset($_GET[$filterName]) ? explode(' ', $_GET[$filterName]) : [];
                // print_r($selectedCustomAttributes);
                // foreach ($selectedCustomAttributes as $attributeName) {
                //     // echo $index.' - '.$filterName.' - '.$attributeName.'<br>';
                    // $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$filterName)->first();
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('slug',$filterValue)->first();
                    $attributeData = $attributeModel->getAttributesIdFromCategory($sub_category['id'], $filterName);
                    // print_r($attributeData); exit;
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('attribute_set_id',$attributeSetData['id'])->where('name',$attributeName)->first();
                //     $person[$index]['filter_name'] = $filterName;
                //     $person[$index]['attribute_set_id'] = $attributeSetData['id'];
                //     $person[$index]['attribute_name'] = $attributeName;
                //     $person[$index]['attribute_id'] = $attributeData['id'];
                //     $index++;
                // }

                
                $person[$index]['filter_name'] = $filterName;
                $person[$index]['filter_value'] = $filterValue;
                if(!empty($attributeData['attribute_set_id'])){
                    $person[$index]['attribute_set_id'] = $attributeData['attribute_set_id'];
                }
                // $person[$index]['attribute_name'] = $attributeName;
                // $person[$index]['attribute_id'] = $attributeData['id'];

                $index++;
            }
            // dd($person); exit;
        }
        
        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);

        $categoriesModel = new Categories();
        // $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        // dd($parent_category);
        // $breadcrumb = $categoriesModel->getCategoryBreadcrumb($parent_category['id']);


        $menu_breadcrumb = $breadcrumb = [];
        if(!empty($forwarded)){
            // print_r($forwarded); exit;
            $menu_breadcrumb = $this->megaMenuBreadcrumb($forwarded);
            // print_r($breadcrumb);
            // exit;
        }else{
            $breadcrumb = $categoriesModel->getCategoryBreadcrumb($parent_category['id']);
        }

        $selectedBrands = $selectedTags = [];
        $minPrice = $maxPrice = $filterBy = $search = '';


       if(!empty($filterParam) && empty($_GET)){
        $filterParamFinal = ltrim($filterParam,"?");
        $arrParam = explode('&', $filterParamFinal);

        parse_str($filterParamFinal, $params);

        $index = 0;
        foreach ($params as $key => $filter) {
            // echo $key;
            // print_r($filter);


            if($key == 'brands'){
                $selectedBrands = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'tags'){
                $selectedTags = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'minPrice'){
                $minPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'maxPrice'){
                $maxPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'filterBy'){
                $filterBy = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'search'){
                $search = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }

            if($key != 'brands' && $key != 'tags' && $key != 'minPrice' && $key != 'maxPrice' && $key != 'filterBy' && $key != 'search'){
       
                $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$key)->first();
                $person[$index]['filter_name'] = $key;
                $person[$index]['filter_value'] = $filter;
                $person[$index]['attribute_set_id'] = $attributeSetData['id'];

            }

            $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];

        }  
        // print_r($person); exit;      

        // print_r($arrParam); exit;
       }
       else{
       
        // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
        $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
        $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
        $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
       }
        $filterCategory = [$sub_category['id']];

        // print_r($selectedBrands); exit;


        $filterBrand = [];
        if($selectedBrands && !empty($selectedBrands)){
            $brand_list = "";
            foreach ($selectedBrands as $brand_slug) {
                $brandData = $brandModel->select('id, name')->where('slug',$brand_slug)->first();
                $brand_list .= $brandData['id'] . ",";
            }
            $brand_comma_string = rtrim($brand_list,',');
            $filterBrand = explode(',', $brand_comma_string);
        }

        $filterAttributes = [];
        if($selectedAttributeSets && !empty($selectedAttributeSets)){
            $attr_list = "";
            foreach ($selectedAttributeSets as $attribute_slug) {
                $attributeData = $attributeSetModel->select('id, name')->where('slug',$attribute_slug)->first();
                $attr_list .= $attributeData['id'] . ",";
            }
            $attr_comma_string = rtrim($attr_list,',');
            $filterAttributes = explode(',', $attr_comma_string);
        }

        $filterTags = [];
        if($selectedTags && !empty($selectedTags)){
            $tags_list = "";
            foreach ($selectedTags as $tag_slug) {
                // echo $tag_slug. '<br>';
                $tagData = $productMasterTagsModel->select('id, name')->where('slug',$tag_slug)->first();
                $tags_list .= $tagData['id'] . ",";
            }
            $tags_comma_string = rtrim($tags_list,',');
            $filterTags = explode(',', $tags_comma_string);
        }

        // exit;
        // dd($filterTags);


        // print_r($person); exit;
        $productsModel = new Products();
        // $products = $productsModel->getProductsFiltersListing($search, $filterBy, [8, 9], [1]);
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags, $perPage, $offset);
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags)); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        // $categories = $categoriesModel->getProductCountWithCategory();


        

        if(!empty($person)){
            $brands = $brandModel->getProductCountWithBrandWithAttributes($subSubCategoryData['id'], $person);
        }else{
            $brands = $brandModel->getProductCountWithBrands($subSubCategoryData['id']);
        }

        // print_r($brands); exit;
        
        // $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();

        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategoryMultiple($filterCategory, $filterBrand);

        $productAttributesSetsValues = [];
        $index = 0;

        // dd($productAttributesSets);

        foreach($productAttributesSets as $attributeSet){
            $attrSetId = $attributeSet['id'];
            $attributeCategoryId = $attributeSet['category_id'];
            $productAttributesSetsValues[$index] = [
                'set_id' => $attributeSet['id'],
                'set_name' => $attributeSet['name'],
                'set_slug' => $attributeSet['slug'],
            ];
            $productAttributesSetValues = $attributeSetCategoryModel->getProductAttributesSetValues($attributeSet['id']);
            // dd($productAttributesSetValues);
            $dropdownArray = [];
            foreach($productAttributesSetValues as $attributeSetValue){
                if(!empty($person)){
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCountsParentAttributes($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_slug'], $filterBrand, $person);
                }else{
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCounts($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_slug'], $filterBrand);
                }
                // dd($attributeProductCounts->countRes);
                if(isset($attributeProductCounts->countRes) && $attributeProductCounts->countRes > 0){
                    $dropdownArray[] = [
                        'attribute_id' => $attributeSetValue['attribute_id'],
                        'attribute_name' => $attributeSetValue['attribute_name'],
                        'attribute_slug' => $attributeSetValue['attribute_slug'],
                        'product_count' => $attributeProductCounts->countRes,
                    ];
                }
                // dd($dropdownArray);
            }
            $productAttributesSetsValues[$index]['dropdowns'] = $dropdownArray;
            $index++;
        }

        // dd($productAttributesSetsValues);

        $productTags = $productTagsModel->getProductCountWithTags();

        // dd($sub_category);
        return view('frontend/categories', ['category_data' => $subSubCategoryData, 'menu_breadcrumb' => $menu_breadcrumb, 'breadcrumb' => $breadcrumb, 'parent_category' => $parent_category, 'sub_category' => $sub_category, 'categories' => $sub_sub_categories, 'products' => $products, 'total_products' => $totalProducts,  'attribute_set_values' => $productAttributesSetsValues, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '') ]);

         // dd($products);
        // return view('frontend/categories', ['category_data' => $subSubCategoryData, 'parent_category' => $parent_category, 'sub_category' => $sub_category, 'categories' => $sub_sub_categories, 'products' => $products]);
    }

    public function sub_sub_sub_category($parent_slug, $sub_parent_category_slug, $sub_category_slug,$filterParam=null,$forwarded=null)
    {

        
        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $categoriesModel = new Categories();

        $parent_category = $categoriesModel
                    ->where('slug', $parent_slug)
                    ->first();

        $sub_parent_category = $categoriesModel
                    ->where('slug', $sub_parent_category_slug)
                    ->first();

        $sub_category = $categoriesModel
                    ->where('slug', $sub_category_slug)
                    ->first();

        $sub_sub_categories = $categoriesModel
                    ->where('parent_id', $sub_category['id'])
                    ->where('status', 1)
                    ->orderBy('id','asc')->findAll();

        $subSubCategoryData = [
            'id' => $sub_category['id'],
            'name' => $sub_category['name'],
            'description' => $sub_category['description'],
        ];


        $attributeSetModel = new AttributeSet();
        $attributeModel = new Attributes();

        $excludedKeys = ['brands', 'search', 'filterBy', 'page']; // Keys to exclude
        $queryParams = array_diff_key($_GET, array_flip($excludedKeys));

        // echo "<pre>";
        $person = [];
        if(!empty($queryParams)){
            // print_r($queryParams); exit;

            $index = 0;
           
            // echo "<pre>";
            foreach ($queryParams as $filterName => $filterValue) {
                // echo $filterName.' - '.$filterValue.'<br>'; exit;

                // $selectedCustomAttributes = isset($_GET[$filterName]) ? explode(' ', $_GET[$filterName]) : [];
                // print_r($selectedCustomAttributes);
                // foreach ($selectedCustomAttributes as $attributeName) {
                //     // echo $index.' - '.$filterName.' - '.$attributeName.'<br>';
                    // $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$filterName)->first();
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('slug',$filterValue)->first();
                    $attributeData = $attributeModel->getAttributesIdFromCategory($sub_category['id'], $filterName);
                    // print_r($attributeData); exit;
                    // $attributeData = $attributeModel->select('id, attribute_set_id, name')->where('attribute_set_id',$attributeSetData['id'])->where('name',$attributeName)->first();
                //     $person[$index]['filter_name'] = $filterName;
                //     $person[$index]['attribute_set_id'] = $attributeSetData['id'];
                //     $person[$index]['attribute_name'] = $attributeName;
                //     $person[$index]['attribute_id'] = $attributeData['id'];
                //     $index++;
                // }

                
                $person[$index]['filter_name'] = $filterName;
                $person[$index]['filter_value'] = $filterValue;
                if(!empty($attributeData['attribute_set_id'])){
                    $person[$index]['attribute_set_id'] = $attributeData['attribute_set_id'];
                }
                // $person[$index]['attribute_name'] = $attributeName;
                // $person[$index]['attribute_id'] = $attributeData['id'];

                $index++;
            }
            // dd($person); exit;
        }

        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);

        $categoriesModel = new Categories();
        // $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        // dd($sub_parent_category);
        // Get breadcrumb trail
        // $breadcrumb = $categoriesModel->getCategoryBreadcrumb($sub_parent_category['id']);

        $menu_breadcrumb = $breadcrumb = [];
        if(!empty($forwarded)){
            // print_r($forwarded); exit;
            $menu_breadcrumb = $this->megaMenuBreadcrumb($forwarded);
            // print_r($breadcrumb);
            // exit;
        }else{
            $breadcrumb = $categoriesModel->getCategoryBreadcrumb($sub_parent_category['id']);
        }
        

        $selectedBrands = $selectedTags = [];
        $minPrice = $maxPrice = $filterBy = $search = '';


       if(!empty($filterParam) && empty($_GET)){
        $filterParamFinal = ltrim($filterParam,"?");
        $arrParam = explode('&', $filterParamFinal);

        parse_str($filterParamFinal, $params);

        foreach ($params as $key => $filter) {
            // echo $key;
            // print_r($filter);


            if($key == 'brands'){
                $selectedBrands = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'tags'){
                $selectedTags = isset($filter) ? explode(' ', $filter) : [];
                // print_r($selectedBrands); exit;
            }
            if($key == 'minPrice'){
                $minPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'maxPrice'){
                $maxPrice = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'filterBy'){
                $filterBy = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }
            if($key == 'search'){
                $search = isset($filter) ? $filter : '';
                // print_r($selectedBrands); exit;
            }

            if($key != 'brands' && $key != 'tags' && $key != 'minPrice' && $key != 'maxPrice' && $key != 'filterBy' && $key != 'search'){
       
                $attributeSetData = $attributeSetModel->select('id, name')->where('slug',$key)->first();
                $person[$index]['filter_name'] = $key;
                $person[$index]['filter_value'] = $filter;
                $person[$index]['attribute_set_id'] = $attributeSetData['id'];

            }

            $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];

        }        

        // print_r($arrParam); exit;
       }
       else{
            // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
            $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
            $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
            $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
            $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
            $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
            $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
            $search = isset($_GET['search']) ? $_GET['search'] : '';
       }

        $filterCategory = [$sub_category['id']];

        $filterBrand = [];
        if($selectedBrands && !empty($selectedBrands)){
            $brand_list = "";
            foreach ($selectedBrands as $brand_slug) {
                $brandData = $brandModel->select('id, name')->where('slug',$brand_slug)->first();
                $brand_list .= $brandData['id'] . ",";
            }
            $brand_comma_string = rtrim($brand_list,',');
            $filterBrand = explode(',', $brand_comma_string);
        }

        $filterAttributes = [];
        if($selectedAttributeSets && !empty($selectedAttributeSets)){
            $attr_list = "";
            foreach ($selectedAttributeSets as $attribute_slug) {
                $attributeData = $attributeSetModel->select('id, name')->where('slug',$attribute_slug)->first();
                $attr_list .= $attributeData['id'] . ",";
            }
            $attr_comma_string = rtrim($attr_list,',');
            $filterAttributes = explode(',', $attr_comma_string);
        }

        $filterTags = [];
        if($selectedTags && !empty($selectedTags)){
            $tags_list = "";
            foreach ($selectedTags as $tag_slug) {
                // echo $tag_slug. '<br>';
                $tagData = $productMasterTagsModel->select('id, name')->where('slug',$tag_slug)->first();
                $tags_list .= $tagData['id'] . ",";
            }
            $tags_comma_string = rtrim($tags_list,',');
            $filterTags = explode(',', $tags_comma_string);
        }

        // exit;
        // dd($filterTags);


        $productsModel = new Products();
        // $products = $productsModel->getProductsFiltersListing($search, $filterBy, [8, 9], [1]);
        // echo "call"; exit;
        // print_r($person); exit;
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags, $perPage, $offset);
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $person, $filterTags)); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        // $categories = $categoriesModel->getProductCountWithCategory();

        

        if(!empty($person)){
            $brands = $brandModel->getProductCountWithBrandWithAttributes($subSubCategoryData['id'], $person);
        }else{
            $brands = $brandModel->getProductCountWithBrands($subSubCategoryData['id']);
        }
    

        // $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();

        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategoryMultiple($filterCategory, $filterBrand);

        $productAttributesSetsValues = [];
        $index = 0;

        // dd($productAttributesSets);

        foreach($productAttributesSets as $attributeSet){
            $attrSetId = $attributeSet['id'];
            $attributeCategoryId = $attributeSet['category_id'];
            $productAttributesSetsValues[$index] = [
                'set_id' => $attributeSet['id'],
                'set_name' => $attributeSet['name'],
                'set_slug' => $attributeSet['slug'],
            ];
            $productAttributesSetValues = $attributeSetCategoryModel->getProductAttributesSetValues($attributeSet['id']);
            // dd($productAttributesSetValues);
            $dropdownArray = [];
            foreach($productAttributesSetValues as $attributeSetValue){
                
                if(!empty($person)){
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCountsParentAttributes($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_name'], $filterBrand, $person);
                }else{
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCounts($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_name'], $filterBrand);
                }

                // dd($attributeProductCounts->countRes);
                if($attributeProductCounts->countRes > 0){
                    $dropdownArray[] = [
                        'attribute_id' => $attributeSetValue['attribute_id'],
                        'attribute_name' => $attributeSetValue['attribute_name'],
                        'attribute_slug' => $attributeSetValue['attribute_slug'],
                        'product_count' => $attributeProductCounts->countRes,
                    ];
                }
                // dd($dropdownArray);
            }
            $productAttributesSetsValues[$index]['dropdowns'] = $dropdownArray;
            $index++;
        }

        // dd($productAttributesSetsValues);

        $productTags = $productTagsModel->getProductCountWithTags();

        // dd($sub_category);
        return view('frontend/categories', ['category_data' => $subSubCategoryData, 'menu_breadcrumb' => $menu_breadcrumb, 'breadcrumb' => $breadcrumb, 'parent_category' => $parent_category, 'sub_parent_category' => $sub_parent_category, 'sub_category' => $sub_category, 'categories' => $sub_sub_categories, 'products' => $products, 'total_products' => $totalProducts, 'attribute_set_values' => $productAttributesSetsValues, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '') ]);

         // dd($products);
        // return view('frontend/categories', ['category_data' => $subSubCategoryData, 'parent_category' => $parent_category, 'sub_category' => $sub_category, 'categories' => $sub_sub_categories, 'products' => $products]);
    }

    private function megaMenuBreadcrumb($forwarded)
    {
        // print_r($forwarded); exit;
        // echo $forwarded->type; exit;

        $breadcrumb = [];
        if($forwarded->type == "mega_custommenu"){

            $custommenusModel = new Custommenus();
            $custommenus_sub = $custommenusModel
                                    ->where('id', $forwarded->ref_id)
                                    ->first();

            // dd($custommenus_sub);
            
            $menusModel = new Menus();
            $mega_menus = $menusModel
                                ->where('id', $custommenus_sub['menu_id'])
                                ->first();

            // dd($mega_menus);
            
            $breadcrumb[0] = [
                'name' => $mega_menus['title'],
                'slug' => $mega_menus['link']
            ];
            $breadcrumb[1] = [
                'name' => $custommenus_sub['title'],
                'slug' => ""
            ];
            
        }

        if($forwarded->type == "mega_custommenu_sub"){

            $custommenusSubModel = new CustommenusSub();
            $custommenus_sub = $custommenusSubModel
                                    ->where('id', $forwarded->ref_id)
                                    ->first();

            // dd($custommenus_sub);

            $custommenusModel = new Custommenus();
            $custommenus = $custommenusModel
                                    ->where('id', $custommenus_sub['parent_id'])
                                    ->first();

            // dd($custommenus);
            
            $menusModel = new Menus();
            $mega_menus = $menusModel
                                ->where('id', $custommenus['menu_id'])
                                ->first();

            // dd($mega_menus);
            
            $breadcrumb[0] = [
                'name' => $mega_menus['title'],
                'slug' => $mega_menus['link']
            ];
            $breadcrumb[1] = [
                'name' => $custommenus['title'],
                'slug' => $custommenus['link'],
            ];
            $breadcrumb[2] = [
                'name' => $custommenus_sub['title'],
                'slug' => ""
            ];
            
        }

        // print_r($breadcrumb);
        // exit;
        return $breadcrumb;
    }

}
