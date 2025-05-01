<?php 

namespace App\Controllers;
use CodeIgniter\Controller;
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
use App\Models\Attributes;
use App\Models\ProductMasterTags;
use App\Models\ProductTags;
use App\Models\RelatedProductOptions;
// use App\Services\ElasticsearchIndexer;



class Findsearch extends Controller
{
    public function search()
    {
        $inputQuery = $_POST['input_query'] ?? '';
        $cleanInput = trim(preg_replace('/\s+/', ' ', $inputQuery));
    
        if (empty($cleanInput)) {
            return $this->response->setJSON([
                'products'   => [],
                'categories' => [],
                'message'    => 'Empty query'
            ]);
        }
    
        $ProductsObj = new Products();
    
        // Fetch results from model
        $products   = $ProductsObj->filterbyproduct($cleanInput);
        $categories = $ProductsObj->filterbycategory($cleanInput);
        $brands     = $ProductsObj->filterbybrand($cleanInput);
    
        // Assuming $products is your array of product data
        $pcategories = [];

        foreach ($products as $product) {
            $slug = $product['category_slug'];

            if (!isset($categories[$slug])) {
                // Initialize if category not added yet
                $pcategories[$slug] = [
                    'category_id'  => $product['category_id'],
                    'category_name'  => $product['category_name'],
                    'category_slug'  => $slug,
                    'match_score'    => 0
                ];
            }

            // Accumulate match count
           $pcategories[$slug]['match_score'] += $product['match_score'];
        }

        // Sort by match_score descending
        usort($pcategories, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });
    
        // Add non-duplicate brands to categories
        foreach ($brands as $brand) {
            $brandExists = false;
    
            foreach ($categories as $cat) {
                if (strtolower($cat['category_name']) === strtolower($brand['brand_name'])) {
                    $brandExists = true;
                    break;
                }
            }
    
            if (!$brandExists) {
                $categories[] = [
                    'category_id'   => $brand['brand_id'],
                    'category_name' => $brand['brand_name'],
                    'category_slug' => $brand['brand_slug'],
                    'type'          => 'brand'
                ];
            }
        }
    
        // Merge product categories and other categories
        $finalCategories = array_values(array_merge(array_values($pcategories), $categories));
      
      //print_r($products);
      //die;
    
        return $this->response->setJSON([
            'products'   => $products,
            'categories' => $finalCategories
        ]);
    }
    
    public function autosearch($forward_query_string = ''){

        // echo $forward_query_string; exit;

        $categoriesModel = new Categories();
        $attributeSetModel = new AttributeSet();
        $productMasterTagsModel = new ProductMasterTags();
        $brandModel = new Brands();
        $attributeSetCategoryModel = new AttributeSetCategory();
        $productTagsModel = new ProductTags();

        $pager = \Config\Services::pager();

        // Get current page number
        // $page = (int) ($this->request->getGet('page') ?? 1);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
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

        if($forward_query_string != ""){
            $cleanInput = trim(preg_replace('/\s+/', ' ', $forward_query_string));
            // echo $cleanInput; exit;
        }else{
            $getquery = isset($_GET['q']) ? $_GET['q'] : '';
            $cleanInput = trim(preg_replace('/\s+/', ' ', $getquery));

        }


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

        $productsModel = new Products();

        // left sidebar category filters only
        $categories   = $productsModel->filterByProductLeftFiltersCategory($cleanInput, [], []);
        $keywords_category_ids = array_column($categories, 'category_id');
        // dd($keywords_category_ids);

        $attributeSetModel = new AttributeSet();
        $attributeModel = new Attributes();

        $excludedKeys = ['q', 'categories', 'brands', 'search', 'filterBy', 'page']; // Keys to exclude
        $queryParams = array_diff_key($_GET, array_flip($excludedKeys));
        // dd($queryParams);

        $person = [];
        if(!empty($queryParams)){

            $index = 0;

            foreach ($queryParams as $filterName => $filterValue) {

                $attributeData = $attributeModel->getAttributesIdFromCategoryMultiple($keywords_category_ids, $filterName);

                $person[$index]['filter_name'] = $filterName;
                $person[$index]['filter_value'] = $filterValue;
                if(!empty($attributeData['attribute_set_id'])){
                    $person[$index]['attribute_set_id'] = $attributeData['attribute_set_id'];
                }
                $index++;
            }

        }
        // dd($person);

        $brands   = $productsModel->filterByProductLeftFiltersBrands($cleanInput, [], []);
        // dd($brands);

        // right product section
        // dd($filterCategory);
        if(!empty($person)){
            $products   = $productsModel->filterbyproductMultiple($cleanInput, $filterCategory, $filterBrand, $perPage, $offset, $person);
            $totalProducts   = $productsModel->filterbyproductCount($cleanInput, $filterCategory, $filterBrand, $person);   
        }else{
            $products   = $productsModel->filterbyproductMultiple($cleanInput, $filterCategory, $filterBrand, $perPage, $offset);
            $totalProducts   = $productsModel->filterbyproductCount($cleanInput, $filterCategory, $filterBrand);   
        }

        $product_min_max = $productsModel->getProductsMinMaxPrice();



       
        $productAttributesSets = $attributeSetCategoryModel->getProductAttributeSetWithCategoryMultipleGroupGlobalSearch($keywords_category_ids, [], $cleanInput);
        // dd($productAttributesSets);

        $productAttributesSetsValues = [];
        $index = 0;

        // dd($productAttributesSets);

        foreach($productAttributesSets as $attributeSet){
            $attrSetId = $attributeSet['id'];
            $attributeCategoryId = $attributeSet['category_ids'];
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
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCountsParentAttributesMultipleCategoryGlobalSearch($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_slug'], $filterBrand, $person, $cleanInput);
                }else{
                    $attributeProductCounts = $attributeSetCategoryModel->getAttributeNameProductCountsWithMultipleCategoryGlobalSearch($attributeCategoryId, $attrSetId, $attributeSetValue['attribute_slug'], $filterBrand, $cleanInput);
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

        // dd($page);
        return view('frontend/search', ['products' => $products, 'total_products' => $totalProducts, 'attribute_set_values' => $productAttributesSetsValues, 'categories' => $categories, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedCategories' => $selectedCategories, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pageNo' => $page, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '')]);
    }
     
    
    public function autosearchbycategory()
    {	
      	$inputQuery = $_POST['input_query'] ?? '';
      	$cat_id = $_POST['data_id'] ?? '';
      
        $cleanInput = trim(preg_replace('/\s+/', ' ', $inputQuery));
      
        $ProductsModel = new Products();
      
      	$products   = $ProductsModel->filterbyproduct($cleanInput, $cat_id);
      
        return $this->response->setJSON(['products' => $products]);
    } 
    
}