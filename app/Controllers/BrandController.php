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

class BrandController extends BaseController
{

    public function index()
    {

        $brandsModel = new Brands();
        $brands = $brandsModel->getBrandsProducts();
        
        return view('frontend/brands', ['brands' => $brands]);
    }

    public function brand_products($brand_slug)
    {
        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;

        $brandsModel = new Brands();
        $brand_data = $brandsModel
                    ->where('slug', $brand_slug)
                    ->first();

        $filterBrand = [$brand_data['id']];

        // $products = $brandsModel->getBrandProductsFiltersListing($brand_data['id']);

        $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedAttributeSets = isset($_GET['attribute_set']) ? explode(' ', $_GET['attribute_set']) : [];
        $selectedTags = isset($_GET['tags']) ? explode(' ', $_GET['tags']) : [];
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : '';
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : '';
        $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $filterCategory = [];
        if($selectedCategories && !empty($selectedCategories)){
            $cate_list = "";
            foreach ($selectedCategories as $cate_slug) {
                $categoriesData = $categoriesModel->select('id, name')->where('slug',$cate_slug)->first();
                $cate_list .= $categoriesData['id'] . ",";
            }
            $cate_comma_string = rtrim($cate_list,',');
            $filterCategory = explode(',', $cate_comma_string);
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
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $perPage, $offset);
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags)); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        $categories = $categoriesModel->getProductCountWithCategory($brand_data['id']);

        // $brands = $brandModel->getProductCountWithBrands();
        
        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategory();
        $productTags = $productTagsModel->getProductCountWithTags();

        // dd($productTags);
        return view('frontend/brand_product_list', ['brand_data' => $brand_data, 'total_products' => $totalProducts, 'products' => $products, 'attribute_sets' => $productAttributesSets, 'categories' => $categories, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedCategories' => $selectedCategories, 'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '')]);

        // return view('frontend/brand_product_list', ['brand_data' => $brand_data, 'products' => $products]);
    }

}
