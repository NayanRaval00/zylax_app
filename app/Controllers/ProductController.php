<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;
use CodeIgniter\Images\Image;

use App\Models\Products;
use App\Models\ProductImages;
use App\Models\ProductsVariants;
use App\Models\ProductFeatures;
use App\Models\RelatedProducts;
use App\Models\ShippingCategoryPrice;
use App\Models\ProductColorVariants;
use App\Models\AttributeSetCategory;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\AttributeSet;
use App\Models\ProductMasterTags;
use App\Models\ProductTags;
use App\Models\RelatedProductOptions;

class ProductController extends BaseController
{

    public function index()
    {
        
        $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        $pager = \Config\Services::pager();

        // Get current page number
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 12; // Items per page
        $offset = ($page - 1) * $perPage;
        
        $selectedCategories = isset($_GET['categories']) ? explode(' ', $_GET['categories']) : [];
        $selectedBrands = isset($_GET['brands']) ? explode(' ', $_GET['brands']) : [];
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

        // $totalProducts = $productsModel->countAll(); // Total product count
        $totalProducts = $productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags); // Total product count

        $product_min_max = $productsModel->getProductsMinMaxPrice();

        $categories = $categoriesModel->getProductCountWithCategory();
        // $categories = [];

        $brands = $brandModel->getProductCountWithBrandsMultipleCategory($filterCategory);
        // $brands = [];
        
        // $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategoryMultiple($filterCategory, $filterBrand);
        // $productAttributesSets = [];

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
                $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCounts($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_name']);
                // dd($attributeProductCounts->countRes);
                if($attributeProductCounts->countRes > 0){
                    $dropdownArray[] = [
                        'attribute_id' => $attributeSetValue['attribute_id'],
                        'attribute_name' => $attributeSetValue['attribute_name'],
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

        // dd($totalProducts);
        return view('frontend/product_list', ['products' => $products, 'total_products' => $totalProducts, 'attribute_set_values' => $productAttributesSetsValues, 'categories' => $categories, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedCategories' => $selectedCategories, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '')]);
    }

    public function show($slug)
    {
        $productsModel = new Products();
        $product = $productsModel->getProductBySlug($slug);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Product Not Found");
        }

        // Get breadcrumb trail
        // dd($product['category_id']);
        $breadcrumb = $productsModel->getBreadcrumb($product['category_id']);
        // dd($breadcrumb);        

        $productImagesModel = new ProductImages();
        $productImages = $productImagesModel->where('product_id',$product['id'])->orderBy('id','asc')->findAll();

        $productsVariantsModel = new ProductsVariants();
        $productsVariants = $productsVariantsModel->where('product_id',$product['id'])->orderBy('id','asc')->findAll();

        $productFeaturesModel = new ProductFeatures();
        $productFeatures = $productFeaturesModel->getProductFeaturesListing($product['id']);

        $relatedProductsModel = new RelatedProducts();
        $relatedProducts = $relatedProductsModel->getRelatedProductsByProductId($product['id']);
        
        $shippingCategoryPriceModel = new ShippingCategoryPrice();
        $shippingCategoryPrice = $shippingCategoryPriceModel->getShippingPricingListing($product['category_id']);
        

        $productColorVariantsModel = new ProductColorVariants();
        $productColorVariants = $productColorVariantsModel->getProductColorVariantsListing($product['id']);

        // $attributeSetCategoryModel = new AttributeSetCategory();
        // $productAttributesSets = $attributeSetCategoryModel->getProductAttributesSets($product['category_id']);
        $productAttributesSets = [];

        $productAttributesSetsValues = [];
        // $index = 0;

        // foreach($productAttributesSets as $attributeSet){
        //     $productAttributesSetsValues[$index] = [
        //         'set_id' => $attributeSet['id'],
        //         'set_name' => $attributeSet['name'],
        //     ];
        //     $productAttributesSetValues = $attributeSetCategoryModel->getProductAttributesSetValues($attributeSet['id']);
        //     $dropdownArray = [];
        //     foreach($productAttributesSetValues as $attributeSetValue){
        //         $dropdownArray[] = [
        //             'attribute_id' => $attributeSetValue['attribute_id'],
        //             'attribute_name' => $attributeSetValue['attribute_name'],
        //             'attribute_value' => $attributeSetValue['attribute_value'],
        //         ];
        //     }
        //     $productAttributesSetsValues[$index]['dropdowns'] = $dropdownArray;
        //     $index++;
        // }

        $seoTitle = $product['seo_page_title'];
        $seoMetaDescription = $product['seo_meta_description'];
        $seoMetaKeywords = $product['seo_meta_keywords'];

        $seoTags = [
            'title' => $product['seo_page_title'],
            'meta_description' => $product['seo_meta_description'],
            'meta_keywords' => $product['seo_meta_keywords'],
        ];

        $RelatedProductOptions = new RelatedProductOptions();
        $RelatedProductOptions = $RelatedProductOptions->getProductOptionsListing($product['id']);

        $attributeSetCategoryModel = new AttributeSetCategory();
        $attributeSets = $attributeSetCategoryModel->getCategoryAttributeSet($product['category_id']);

        // dd($attributeSets);
        return view('frontend/product_detail', ['seo' => $seoTags, 'product' => $product, 'breadcrumb' => $breadcrumb, 'images' => $productImages, 'products_variants' => $productsVariants, 'product_features' => $productFeatures, 'related_products' => $relatedProducts, 'product_color_variants' => $productColorVariants, 'product_attributes' => $productAttributesSetsValues, 'shipping_prices' => $shippingCategoryPrice, 'relatedProductOptions' => $RelatedProductOptions, 'attributeSets' => $attributeSets ]);
    }

}