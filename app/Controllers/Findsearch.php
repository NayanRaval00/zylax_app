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
                'products' => [],
                'categories' => [],
                'brands' => [],
                'message' => 'Empty query'
            ]);
        }
    
        $client = service('elasticsearch');
        helper('array');
    
        $Products = new Products();
    
        // 🔁 Reusable function for search
        function searchIndexOrDB($client, $index, $query, $fields, $dbCallback, $mapFn, $size = 20)
        {
            $resultData = [];
    
            try {
                $terms = explode(' ', $query);
                $shouldQueries = array_map(fn($term) => [
                    'multi_match' => [
                        'query'     => $term,
                        'fields'    => $fields,
                        'fuzziness' => 'AUTO'
                    ]
                ], $terms);
    
                $searchParams = [
                    'index' => $index,
                    'body' => [
                        'query' => [
                            'bool' => [
                                'should' => $shouldQueries
                            ]
                        ],
                        'size' => $size
                    ]
                ];
    
                $result = $client->search($searchParams);
                if (!empty($result['hits']['hits'])) {
                    foreach ($result['hits']['hits'] as $hit) {
                        $resultData[] = $hit['_source'];
                    }
                    return $resultData;
                }
    
            } catch (\Exception $e) {
                log_message('error', "Elastic search failed: " . $e->getMessage());
            }
    
            // Fallback: DB
            try {
                $dbResults = call_user_func($dbCallback, $query);
                foreach ($dbResults as $row) {
                    $formatted = $mapFn($row);
                    if (!empty($formatted)) {
                        $client->index([
                            'index' => $index,
                            'id'    => $formatted[array_key_first($formatted)],
                            'body'  => $formatted
                        ]);
                        $resultData[] = $formatted;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', "DB fallback failed: " . $e->getMessage());
            }
    
            return $resultData;
        }
    
        // 🔍 Products
        $products = searchIndexOrDB(
            $client, 'products', $cleanInput,
            ['product_name^3', 'short_description', 'description', 'sku'],
            [$Products, 'filterbyproduct'],
            fn($p) => [
                'product_id' => $p['product_id'],
                'product_name' => $p['product_name'],
                'product_slug' => $p['product_slug'],
                'product_img' => $p['product_img'],
                'product_price' => $p['product_price'],
                'short_description' => $p['short_description'] ?? '',
                'description' => $p['description'] ?? '',
                'sku' => $p['sku'] ?? ''
            ]
        );
    
        // 🔍 Categories
        $categories = searchIndexOrDB(
            $client, 'categories', $cleanInput,
            ['category_name'],
            [$Products, 'filterbycategory'],
            fn($c) => [
                'category_id' => $c['category_id'],
                'category_name' => $c['category_name'],
                'category_slug' => $c['category_slug'],
                'type' => 'category'
            ]
        );
    
        // 🔍 Brands (merged as category type)
        $brands = searchIndexOrDB(
            $client, 'brands', $cleanInput,
            ['brand_name'],
            [$Products, 'filterbybrand'],
            fn($b) => [
                'category_id' => $b['brand_id'],
                'category_name' => $b['brand_name'],
                'category_slug' => $b['brand_slug'],
                'type' => 'brand'
            ]
        );
    
        // ✅ Merge brands into categories
        if (!empty($brands)) {
            foreach ($brands as $brand) {
                if (!isset($brand['category_name']) || empty($brand['category_name'])) {
                    continue; // skip invalid entry
                }
    
                $exists = false;
                foreach ($categories as $cat) {
                    if (isset($cat['category_name']) &&
                        strtolower($cat['category_name']) === strtolower($brand['category_name'])) {
                        $exists = true;
                        break;
                    }
                }
    
                if (!$exists) {
                    $categories[] = $brand;
                }
            }
        }
    
        return $this->response->setJSON([
            'products'   => $products,
            'categories' => $categories
        ]);
    }
    
    
       

    public function autosearch(){

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

        $getquery = isset($_GET['q']) ? $_GET['q'] : '';
        $cleanInput = trim(preg_replace('/\s+/', ' ', $getquery));



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
        $products = $productsModel->getProductsFiltersListing($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $perPage, $offset, $cleanInput);

        // $totalProducts = $productsModel->countAll(); // Total product count
        $totalProducts = count($productsModel->getProductsFiltersListingCount($search, $filterBy, $filterCategory, $filterBrand, $minPrice, $maxPrice, $filterAttributes, $filterTags, $cleanInput)); // Total product count

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
        return view('frontend/search', ['products' => $products, 'total_products' => $totalProducts, 'attribute_set_values' => $productAttributesSetsValues, 'categories' => $categories, 'brands' => $brands, 'product_tags' => $productTags, 'product_min_max' => $product_min_max, 'selectedCategories' => $selectedCategories, 'selectedBrands' => $selectedBrands,'selectedAttributeSets' => $selectedAttributeSets, 'selectedTags' => $selectedTags, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'filterBy' => $filterBy, 'search' => $search, 'pager' => ( $totalProducts > $perPage ? $pager->makeLinks($page, $perPage, $totalProducts) : '')]);
    }
     
    
    public function autosearchbycategory()
    {
        $client = service('elasticsearch');
        $db = \Config\Database::connect();
    
        $inputQuery = $_POST['input_query'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';
        $cleanInput = trim(preg_replace('/\s+/', ' ', $inputQuery));
    
        // Build Solr-style query for Elasticsearch
        if (!empty($categoryId)) {
            $queryString = "category_id:{$categoryId} AND (" . $this->breakPhraseToOr('category_name', $cleanInput) . " OR " . $this->breakPhraseToOr('text', $cleanInput) . ")";
        } else {
            $queryString = $this->breakPhraseToOr('text', $cleanInput);
        }
    
        // STEP 1: Search from Elasticsearch
        try {
            $params = [
                'index' => ['products'],
                'body'  => [
                    'query' => [
                        'query_string' => [
                            'query' => $queryString
                        ]
                    ],
                    'size' => 20
                ]
            ];
    
            $results = $client->search($params);
            $products = array_map(fn($hit) => (array) $hit['_source'], $results['hits']['hits'] ?? []);
        } catch (\Exception $e) {
            $products = [];
        }
    
        // STEP 2: If not found in Elastic, fallback to MySQL
        if (empty($products)) {
            $builder = $db->table('products');
            $builder->select('
                products.id as product_id,
                products.name as product_name,
                products.slug as product_slug,
                products.image as product_img,
                pv.price as product_price,
                products.description as product_description,
                categories.id as category_id,
                categories.name as category_name
            ');
            $builder->join('categories', 'categories.id = products.category_id', 'left');
            $builder->join('product_variants as pv', 'pv.product_id = products.id', 'left');
    
            if (!empty($categoryId)) {
                $builder->where('products.category_id', $categoryId);
            }
    
            $builder->like('products.name', $cleanInput, 'both');
            $builder->limit(20);
    
            $products = $builder->get()->getResultArray();
    
            // Optional: index to Elastic
            foreach ($products as $item) {
                $client->index([
                    'index' => 'products',
                    'id'    => $item['product_id'],
                    'body'  => $item
                ]);
            }
        }
    
        return $this->response->setJSON(['products' => $products]);
    }

    private function breakPhraseToOr($field, $varQuery)
    {
        $arrQuery = explode(" ", $varQuery);
        $queryParts = [];

        foreach ($arrQuery as $word) {
            if (!empty($word)) {
                $queryParts[] = "{$field}:{$word}";
            }
        }

        return implode(" OR ", $queryParts);
    }

    
    
    
    
}
