<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;

use App\Models\Products;
use App\Models\AttributeSetCategory;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\AttributeSet;
use App\Models\ProductMasterTags;
use App\Models\ProductTags;
use App\Models\Menus;
use App\Models\MenuCategory;
use App\Models\Custommenus;
use App\Models\CustommenuCategory;
use App\Models\CustommenusSub;
use App\Models\CustommenuSubCategory;

class MegaMenuController extends BaseController
{

    public function mega_menu($mega_menus_slug, $mega_menus_id)
    {

        // echo $mega_menus_id; exit;

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $menusModel = new Menus();
        $mega_menus = $menusModel
                                ->where('id', $mega_menus_id)
                                ->first();

        // dd($mega_menus);

        $menuCategoryModel = new MenuCategory();

        $get_categories = $menuCategoryModel
                    ->where('menu_id', $mega_menus_id)
                    ->findAll();

        // dd($get_categories);

        $categoriesModel = new Categories();

        $filter_category_ids = array_column($get_categories, 'category_id');
        // dd($filter_category_ids);

        $categories = [];
        foreach ($filter_category_ids as $category_id) {

            $filterChildIds = array_unique($categoriesModel->getChildCategoryIds($category_id));
            // $filterCategoryIds = array_merge($filterCategory, $filterChildIds);
            // dd($filterChildIds);

            $categoryGets = $categoriesModel->getProductCountWithMultpleCategory($filterChildIds);

            $categories = array_merge($categories, $categoryGets);
            
        }

        // dd($filterCategoryListing);

        $filterCategory = [];


        foreach ($get_categories as $key => $category) {
            // array_push($filterCategory,$category['category_id']);

            $childIds = $categoriesModel->getChildCategoryIds($category['category_id']);
            // echo "<pre>";
            // print_r($childIds); exit;
            
            // $filterCategory[] = $childIds;
            $filterCategory = array_merge($filterCategory, $childIds);
            
        }

        // dd($filterCategory);

        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);

        // $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
        $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
        $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
        $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // $filterCategory = [$sub_category['id']];

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

        if(!empty($filterCategory)){

            $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $perPage, $offset);
            $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags)); // Total product count

            $product_min_max = $productsModel->getProductsMinMaxPrice();

            // $categories = $categoriesModel->getProductCountWithCategory();

            $brands = $brandModel->getProductCountWithBrandsMultipleCategory($filterCategory);
            
            $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();
            $productTags = $productTagsModel->getProductCountWithTags();
            
        }else{
            $products = $brands = $productAttributesSets = $productTags = [];
            $totalProducts = 0;
            $product_min_max = [
                'min_price' => 0,
                'max_price' => 0
            ];
        }
        

        // dd($sub_category);
        return view('frontend/custom_menu_products', ['mega_menu_title' => $mega_menus, 'products' => $products, 'total_products' => $totalProducts, 'attribute_sets' => $productAttributesSets, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'categories' => $categories, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '') ]);

    }

    public function mega_custommenu($custommenus_sub_slug, $custommenus_sub_id)
    {

        // echo $custommenus_sub_slug; exit;

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $custommenusModel = new Custommenus();
        $custommenus_sub = $custommenusModel
                                ->where('id', $custommenus_sub_id)
                                ->first();

        // dd($custommenus_sub);

        $menusModel = new Menus();
        $megaMenu = $menusModel
                            ->where('id', $custommenus_sub['menu_id'])
                            ->first();

        // dd($megaMenu);

        $breadcrumb[0] = [
            'name' => $megaMenu['title'],
            'slug' => $megaMenu['link']
        ];

        // dd($breadcrumb);

        $custommenuCategoryModel = new CustommenuCategory();

        $get_categories = $custommenuCategoryModel
                    ->where('custommenu_id', $custommenus_sub_id)
                    ->findAll();

        // dd($get_categories);




        $categoriesModel = new Categories();

        $filter_category_ids = array_column($get_categories, 'category_id');
        // dd($filter_category_ids);

        $categories = [];
        foreach ($filter_category_ids as $category_id) {

            $filterChildIds = array_unique($categoriesModel->getChildCategoryIds($category_id));
            // $filterCategoryIds = array_merge($filterCategory, $filterChildIds);
            // dd($filterChildIds);

            $categoryGets = $categoriesModel->getProductCountWithMultpleCategory($filterChildIds);

            $categories = array_merge($categories, $categoryGets);
            
        }

        // dd($filterCategoryListing);
        


        $filterCategory = [];
        foreach ($get_categories as $key => $category) {
            // array_push($filterCategory,$category['category_id']);

            $childIds = $categoriesModel->getChildCategoryIds($category['category_id']);
            // echo "<pre>";
            // print_r($childIds); exit;
            
            // $filterCategory[] = $childIds;
            $filterCategory = array_merge($filterCategory, $childIds);
            
        }

        // dd($filterCategory);

        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);

        // $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
        $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
        $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
        $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // $filterCategory = [$sub_category['id']];

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

        if(!empty($filterCategory)){

            $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $perPage, $offset);
            $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags)); // Total product count

            $product_min_max = $productsModel->getProductsMinMaxPrice();

            // $categories = $categoriesModel->getProductCountWithCategory();

            $brands = $brandModel->getProductCountWithBrandsMultipleCategory($filterCategory);
            
            $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();
            $productTags = $productTagsModel->getProductCountWithTags();
            
        }else{
            $products = $brands = $productAttributesSets = $productTags = [];
            $totalProducts = 0;
            $product_min_max = [
                'min_price' => 0,
                'max_price' => 0
            ];
        }
        

        // dd($sub_category);
        return view('frontend/custom_menu_products', ['mega_menu_title' => $custommenus_sub, 'breadcrumb' => $breadcrumb, 'products' => $products, 'total_products' => $totalProducts, 'attribute_sets' => $productAttributesSets, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'categories' => $categories, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '') ]);

    }

    public function mega_custommenu_sub($parent_slug, $custommenus_sub_slug, $custommenus_sub_id)
    {

        // echo $custommenus_sub_id; exit;

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $custommenusSubModel = new CustommenusSub();
        $custommenus_sub = $custommenusSubModel
                                ->where('id', $custommenus_sub_id)
                                ->first();

        // dd($custommenus_sub);

        $custommenusModel = new Custommenus();
        $custommenus = $custommenusModel
                            ->where('id', $custommenus_sub['parent_id'])
                            ->first();
                
        // dd($custommenus);

        $menusModel = new Menus();
        $megaMenu = $menusModel
                            ->where('id', $custommenus['menu_id'])
                            ->first();

        // dd($megaMenu);

        $breadcrumb[0] = [
            'name' => $megaMenu['title'],
            'slug' => $megaMenu['link']
        ];
        $breadcrumb[1] = [
            'name' => $custommenus['title'],
            'slug' => $custommenus['link']
        ];
        
        // dd($breadcrumb);

        $custommenuSubCategoryModel = new CustommenuSubCategory();

        $get_categories = $custommenuSubCategoryModel
                    ->where('custommenus_sub_id', $custommenus_sub_id)
                    ->findAll();

        // dd($get_categories);

        // $filterCategory = [];

        // foreach ($get_categories as $key => $category) {
        //     array_push($filterCategory,$category['category_id']);
        // }

        $categoriesModel = new Categories();

        $filter_category_ids = array_column($get_categories, 'category_id');
        // dd($filter_category_ids);

        $categories = [];
        foreach ($filter_category_ids as $category_id) {

            $filterChildIds = array_unique($categoriesModel->getChildCategoryIds($category_id));
            // $filterCategoryIds = array_merge($filterCategory, $filterChildIds);
            // dd($filterChildIds);

            $categoryGets = $categoriesModel->getProductCountWithMultpleCategory($filterChildIds);

            $categories = array_merge($categories, $categoryGets);
            
        }

        // dd($filterCategoryListing);

        $filterCategory = [];


        // $categoriesModel = new Categories();

        foreach ($get_categories as $key => $category) {
            // array_push($filterCategory,$category['category_id']);

            $childIds = $categoriesModel->getChildCategoryIds($category['category_id']);
            // echo "<pre>";
            // print_r($childIds); exit;
            
            // $filterCategory[] = $childIds;
            $filterCategory = array_merge($filterCategory, $childIds);
            
        }

        // dd($filterCategory);

        $categoriesModel = new Categories();

        $parent_category = $categoriesModel
                    ->where('slug', $parent_slug)
                    ->first();

        // dd($parent_category);

        // if(isset($parent_category['id'])){
        //     $get_category_id = $parent_category['id'];

        //     // Get breadcrumb trail
        //     $breadcrumb = $categoriesModel->getCategoryBreadcrumb($get_category_id);
        //     // dd($breadcrumb);
        // }else{

        //     $custommenusModel = new Custommenus();
        //     $custommenus = $custommenusModel
        //                         ->where('link', $parent_slug)
        //                         ->first();

        //     $breadcrumb[0] = [
        //         'name' => $custommenus['title'],
        //         'slug' => $custommenus['link']
        //     ];
        // }


        // dd($breadcrumb);

        // $products = $categoriesModel->getCategoryProductsFiltersListing($parent_category['id']);

        $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        // $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
        $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
        $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
        $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // $filterCategory = [$sub_category['id']];

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
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $perPage, $offset);
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags)); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        // $categories = $categoriesModel->getProductCountWithCategory();

        $brands = $brandModel->getProductCountWithBrandsMultipleCategory($filterCategory);
        
        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();
        $productTags = $productTagsModel->getProductCountWithTags();

        // dd($sub_category);
        return view('frontend/custom_menu_products', ['mega_menu_title' => $custommenus_sub, 'breadcrumb' => $breadcrumb, 'parent_category' => $parent_category, 'products' => $products, 'total_products' => $totalProducts, 'attribute_sets' => $productAttributesSets, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'categories' => $categories, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '') ]);

    }

}
