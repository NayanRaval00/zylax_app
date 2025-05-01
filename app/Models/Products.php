<?php

namespace App\Models;

use CodeIgniter\Model;

class Products extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'tags', 'tax', 'indicator', 'made_in', 'category_id', 'brand', 'warranty_period', 'guarantee_period', 'gtin', 'model', 'vpn', 'pickup_location', 'is_prices_inclusive_tax', 'cod_allowed', 'is_returnable', 'is_cancelable', 'is_attachment_required', 'image', 'other_images', 'video_type', 'video', 'is_feature', 'is_discount', 'is_hot_deal', 'seo_page_title', 'seo_meta_keywords', 'seo_meta_description', 'seo_og_image', 'short_description', 'description', 'extra_description', 'specification', 'configure_me', 'submit_to_google', 'is_best_seller', 'status'];

    public function getProductBySlug($slug)
    {
        // return $this->where('slug', $slug)->first();
        return $this->select('products.*, brands.name as brand_name, categories.name as category_name, products.category_id')
        ->join('brands', 'brands.id = products.brand', 'left')
        ->join('categories', 'categories.id = products.category_id', 'left')
        ->where('products.slug', $slug)
        ->where('products.status', 1)
        ->first();
    }

    function getProductsBestArrivals()
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.date_added, p.category_id');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->groupBy('p.date_added');
        $builder->orderBy('p.id', 'DESC');
        $builder->where('p.is_feature', 1);
        $builder->where('p.status', 1);
        $builder->limit(12);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductsBestSellers($perPage = 15, $offset = 0)
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.date_added, p.category_id');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        // $builder->groupBy('p.date_added');
        $builder->orderBy('p.id', 'DESC');
        $builder->where('p.is_best_seller', 1);
        $builder->where('p.status', 1);
        // $builder->limit(12);
        $builder->limit($perPage, $offset);
        $query = $builder->get();
        // echo $this->db->getLastQuery(); exit;
        return $query->getResultArray();
    }

    function getProductshotdeals()
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.date_added, p.category_id');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->groupBy('p.date_added');
        $builder->orderBy('p.id', 'DESC');
        $builder->where('p.is_hot_deal', 1);
        $builder->where('p.status', 1);
        $builder->limit(7);
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductsMinMaxPrice()
    {
        // $builder = $this->db->table('products as p');
        // $builder->select('MIN(pv.price) AS min_price, MAX(pv.price) AS max_price');
        // $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        // // $builder->orderBy('p.id', 'ASC');
        // $query = $builder->get();
        // return $query->getResultArray();

        return $this->select('MIN(pv.price) AS min_price, MAX(pv.price) AS max_price')
        ->join('product_variants as pv', 'pv.product_id = products.id', 'left')
        // ->where('products.slug', $slug)
        ->where('products.status', 1)
        ->first();
    }

    // function getProductsFiltersListing($search = '', $filterBy = '', $category = [], $brand = [], $minPrice = '', $maxPrice = '', $attribute = [], $tags = [], $perPage = 12, $offset = 0, $cleanInput = '')
    // {
    //     $builder = $this->db->table('products as p');
    //     $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.category_id as category_id, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, brands.name as brand_name, p.vpn as p_vpn');
    //     $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
    //     $builder->join('brands', 'brands.id = p.brand', 'left');
    
    //     if (!empty($attribute)) {
    //         foreach ($attribute as $key => $values) {
    //             $valuesArray = explode(' ', $values['filter_value']);
    //             $attribute_value_ids = [];
    //             if (!empty($valuesArray)) {
    //                 $attr_ids = $this->db->table('attributes')
    //                     ->select('id')
    //                     ->where('attribute_set_id', $values['attribute_set_id'])
    //                     ->whereIn('slug', $valuesArray)
    //                     ->get()
    //                     ->getResultArray();
    
    //                 $attribute_value_ids = array_column($attr_ids, 'id');
    
    //                 if (!empty($attribute_value_ids) && is_array($attribute_value_ids)) {
    //                     $builder->join('product_attributes pa_' . $key, 'pa_' . $key . '.product_id = p.id');
    //                     $builder->where('pa_' . $key . '.attribute_id', $values['attribute_set_id']);
    //                     $builder->whereIn('pa_' . $key . '.attribute_value_id', $attribute_value_ids);
    //                 }
    //             }
    //         }
    //     }
    
    //     if (!empty($tags)) {
    //         $builder->join('product_tags as tags', 'tags.product_id = p.id', 'left');
    //         $builder->whereIn('tags.tag_id', $tags);
    //     }
    
    //     // Full-text search + order by LOCATE for title relevance
    //     if (!empty($search)) {
    //         $builder->where("MATCH(p.name, p.slug, p.vpn) AGAINST(" . $this->db->escape($search) . " IN NATURAL LANGUAGE MODE)", null, false);
    //         $builder->orderBy("LOCATE(" . $this->db->escape($search) . ", p.name)", "ASC", false);
    //     }
    
    //     if (!empty($cleanInput)) {
    //         $builder->where("MATCH(p.name, p.slug, p.vpn) AGAINST(" . $this->db->escape($cleanInput) . " IN NATURAL LANGUAGE MODE)", null, false);
    //         $builder->orderBy("LOCATE(" . $this->db->escape($cleanInput) . ", p.name)", "ASC", false);
    //     }
    
    //     if (!empty($category)) {
    //         $builder->whereIn('p.category_id', $category);
    //     }
    
    //     if (!empty($brand)) {
    //         $builder->whereIn('p.brand', $brand);
    //     }
    
    //     if (!empty($minPrice)) {
    //         $builder->where('pv.price >=', $minPrice);
    //     }
    
    //     if (!empty($maxPrice)) {
    //         $builder->where('pv.price <=', $maxPrice);
    //     }
    
    //     $builder->where('p.status', 1);
    
    //     // Sorting based on filterBy
    //     if (!empty($filterBy)) {
    //         switch ($filterBy) {
    //             case "name_asc":
    //                 $builder->orderBy('p.name', 'ASC');
    //                 break;
    //             case "name_dsc":
    //                 $builder->orderBy('p.name', 'DESC');
    //                 break;
    //             case "price_high":
    //                 $builder->orderBy('pv.price', 'DESC');
    //                 break;
    //             case "price_low":
    //                 $builder->orderBy('pv.price', 'ASC');
    //                 break;
    //             case "latest":
    //                 $builder->orderBy('p.id', 'ASC');
    //                 break;
    //             case "oldest":
    //                 $builder->orderBy('p.id', 'DESC');
    //                 break;
    //         }
    //     }
    
    //     $builder->limit($perPage, $offset);
    
    //     $query = $builder->get();
    //     echo $this->db->getLastQuery(); exit;
    //     return $query->getResultArray();
    // }

    function getProductsFiltersListing($search = '', $filterBy = '', $category = [], $brand = [], $minPrice = '', $maxPrice = '', $attribute = [], $tags = [], $perPage = 12, $offset = 0, $cleanInput = '')
    {
        if($cleanInput != ""){
            $results_products = $this->filterbyproduct($cleanInput);
        }
        else{

            $builder = $this->db->table('products as p');
            $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.category_id as category_id, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, brands.name as brand_name, p.vpn as p_vpn');
    
            // Relevance score
            // if (!empty($search)) {
            //     $escapedSearch = $this->db->escape($search);
    
            //     $builder->select("
            //         (
            //             (MATCH(p.name) AGAINST ($escapedSearch)) * 5 +
            //             (MATCH(p.short_description, p.model, p.vpn) AGAINST ($escapedSearch))
            //         ) AS relevance
            //     ", false);
            // }
    
            $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
            $builder->join('brands', 'brands.id = p.brand', 'left');
    
            if (!empty($attribute)) {
                foreach ($attribute as $key => $values) {
                    $valuesArray = explode(' ', $values['filter_value']);
                    if (!empty($valuesArray)) {
                        $attr_ids = $this->db->table('attributes')
                            ->select('id')
                            ->where('attribute_set_id', $values['attribute_set_id'])
                            ->whereIn('slug', $valuesArray)
                            ->get()
                            ->getResultArray();
    
                        $attribute_value_ids = array_column($attr_ids, 'id');
    
                        if (!empty($attribute_value_ids)) {
                            $builder->join("product_attributes pa_$key", "pa_$key.product_id = p.id");
                            $builder->where("pa_$key.attribute_id", $values['attribute_set_id']);
                            $builder->whereIn("pa_$key.attribute_value_id", $attribute_value_ids);
                        }
                    }
                }
            }
    
            if (!empty($tags)) {
                $builder->join('product_tags as tags', 'tags.product_id = p.id', 'left');
                $builder->whereIn('tags.tag_id', $tags);
            }
    
            if (!empty($search)) {
                $builder->where("
                    MATCH(p.name, p.short_description, p.model, p.vpn)
                    AGAINST(" . $this->db->escape($search) . " IN NATURAL LANGUAGE MODE)
                ", null, false);
            }
    
            if (!empty($cleanInput)) {
                $builder->where("
                    MATCH(p.name, p.short_description, p.model, p.vpn)
                    AGAINST(" . $this->db->escape($cleanInput) . " IN NATURAL LANGUAGE MODE)
                ", null, false);
            }
    
            if (!empty($category)) {
                $builder->whereIn('p.category_id', $category);
            }
    
            if (!empty($brand)) {
                $builder->whereIn('p.brand', $brand);
            }
    
            if (!empty($minPrice)) {
                $builder->where('pv.price >=', $minPrice);
            }
    
            if (!empty($maxPrice)) {
                $builder->where('pv.price <=', $maxPrice);
            }
    
            $builder->where('p.status', 1);
    
            // Sorting logic
            if (!empty($search)) {
                // $builder->orderBy('relevance', 'DESC', false);
            } elseif (!empty($filterBy)) {
                switch ($filterBy) {
                    case "name_asc":
                        $builder->orderBy('p.name', 'ASC');
                        break;
                    case "name_dsc":
                        $builder->orderBy('p.name', 'DESC');
                        break;
                    case "price_high":
                        $builder->orderBy('pv.price', 'DESC');
                        break;
                    case "price_low":
                        $builder->orderBy('pv.price', 'ASC');
                        break;
                    case "latest":
                        $builder->orderBy('p.id', 'ASC');
                        break;
                    case "oldest":
                        $builder->orderBy('p.id', 'DESC');
                        break;
                }
            }
    
            $builder->limit($perPage, $offset);
    
            $query = $builder->get();
            // echo $this->db->getLastQuery(); exit;
            $results_products = $query->getResultArray();

        }

        return $results_products;
      
    }

    function getProductsFiltersListingCount($search = '', $filterBy = '', $category = [], $brand = [], $minPrice = '', $maxPrice = '', $attribute = [], $tags = [], $cleanInput = '')
    {
        if($cleanInput != ""){
            $results_products = $this->filterbyproductTotalCount($cleanInput);
            $countTotal = $results_products;
        }
        else{
        $builder = $this->db->table('products as p');
        $builder->select('COUNT(DISTINCT p.id) as total');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->join('brands', 'brands.id = p.brand', 'left');

        if (!empty($attribute)) {
            foreach ($attribute as $key => $values) {
                $valuesArray = explode(' ', $values['filter_value']);
                if (!empty($valuesArray)) {
                    $attr_ids = $this->db->table('attributes')
                        ->select('id')
                        ->where('attribute_set_id', $values['attribute_set_id'])
                        ->whereIn('slug', $valuesArray)
                        ->get()
                        ->getResultArray();

                    $attribute_value_ids = array_column($attr_ids, 'id');

                    if (!empty($attribute_value_ids)) {
                        $builder->join("product_attributes pa_$key", "pa_$key.product_id = p.id");
                        $builder->where("pa_$key.attribute_id", $values['attribute_set_id']);
                        $builder->whereIn("pa_$key.attribute_value_id", $attribute_value_ids);
                    }
                }
            }
        }

        if (!empty($tags)) {
            $builder->join('product_tags as tags', 'tags.product_id = p.id', 'left');
            $builder->whereIn('tags.tag_id', $tags);
        }

        if (!empty($search)) {
            $escapedSearch = $this->db->escape($search);
            $builder->where("
                MATCH(p.name, p.short_description, p.model, p.vpn)
                AGAINST($escapedSearch IN NATURAL LANGUAGE MODE)
            ", null, false);
        }

        if (!empty($cleanInput)) {
            $escapedCleanInput = $this->db->escape($cleanInput);
            $builder->where("
                MATCH(p.name, p.short_description, p.model, p.vpn)
                AGAINST($escapedCleanInput IN NATURAL LANGUAGE MODE)
            ", null, false);
        }

        if (!empty($category)) {
            $builder->whereIn('p.category_id', $category);
        }

        if (!empty($brand)) {
            $builder->whereIn('p.brand', $brand);
        }

        if (!empty($minPrice)) {
            $builder->where('pv.price >=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $builder->where('pv.price <=', $maxPrice);
        }

        $builder->where('p.status', 1);

        $query = $builder->get()->getRowArray();
        $countTotal = $query['total'] ?? 0;
        }

        return $countTotal;
    }

    public function getBreadcrumb($categoryId)
    {
        $breadcrumbs = [];
        while ($categoryId) {
            $category = $this->db->table('categories')
                ->where('id', $categoryId)
                ->get()
                ->getRowArray();

            if ($category) {
                $breadcrumbs[] = $category;
                $categoryId = $category['parent_id'];
            } else {
                break;
            }
        }
        return array_reverse($breadcrumbs);
    }

    public function filterbycategory($keyword = '')
    {
        $db = \Config\Database::connect();
    
        // Format keyword for full-text BOOLEAN MODE
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword)); // remove extra spaces
        $words = explode(' ', $keyword);

        if (count($words) > 1) {
            $lastWord = array_pop($words);
            $searchString = '+' . implode(' ', $words) . ' ' . $lastWord . '*';
        } else {
            $searchString = '+' . $keyword . '*';
        }
    
        $sql = "SELECT 
                    categories.id AS category_id, 
                    categories.name AS category_name, 
                    categories.slug AS category_slug,
                    (
                        MATCH(categories.name) 
                        AGAINST(? IN BOOLEAN MODE) + 
                        CASE WHEN categories.name LIKE ? THEN 5 ELSE 0 
                    END
                ) AS relevance 
                FROM categories 
                WHERE 
                    MATCH(categories.name) 
                    AGAINST(? IN BOOLEAN MODE)
                    AND categories.status = 1 
                ORDER BY relevance DESC 
                LIMIT 20";
    
        $bindings = [$searchString, "%$keyword%", $searchString];
    
        $query = $db->query($sql, $bindings);
        //echo $this->db->getLastQuery(); exit;

        return $query->getResultArray();
    }

    // public function filterbycategoryWithProductCount($keyword = '')
    // {
    //     $db = \Config\Database::connect();

    //     // Clean keyword
    //     $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    //     $words = explode(' ', $keyword);

    //     if (count($words) > 1) {
    //         $lastWord = array_pop($words);
    //         $searchString = '+' . implode(' ', $words) . ' ' . $lastWord . '*';
    //     } else {
    //         $searchString = '+' . $keyword . '*';
    //     }

    //     // Get matching categories
    //     $sql = "SELECT 
    //                 categories.id AS category_id, 
    //                 categories.name AS category_name, 
    //                 categories.slug AS category_slug,
    //                 (
    //                     MATCH(categories.name, categories.slug, categories.description) 
    //                     AGAINST(? IN BOOLEAN MODE) + 
    //                     CASE WHEN categories.name LIKE ? THEN 5 ELSE 0 
    //                 END
    //             ) AS relevance 
    //             FROM categories 
    //             WHERE 
    //                 MATCH(categories.name, categories.slug, categories.description) 
    //                 AGAINST(? IN BOOLEAN MODE)
    //                 AND categories.status = 1 
    //             ORDER BY relevance DESC 
    //             LIMIT 20";

    //     $bindings = [$searchString, "%$keyword%", $searchString];
    //     $query = $db->query($sql, $bindings);
    //     $categories = $query->getResultArray();

    //     if (empty($categories)) return [];

    //     // Extract category IDs
    //     $categoryIds = array_column($categories, 'category_id');

    //     // Now count products grouped by these categories
    //     $builder = $db->table('products');
    //     $builder->select('category_id, COUNT(*) AS product_count');
    //     $builder->whereIn('category_id', $categoryIds);
    //     $builder->where('status', 1);
    //     $builder->groupBy('category_id');

    //     $counts = $builder->get()->getResultArray();

    //     // Map counts by category_id
    //     $countsMap = array_column($counts, 'product_count', 'category_id');

    //     // Merge count back into original category list
    //     foreach ($categories as &$cat) {
    //         $cat['product_count'] = $countsMap[$cat['category_id']] ?? 0;
    //     }

    //     return $categories;
    // }

    
    // public function filterbycategoryWithProductCount($keyword = '')
    // {
    //     $db = \Config\Database::connect();
    
    //     // Clean keyword
    //     $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    //     if (empty($keyword)) {
    //         return [];
    //     }
    
    //     $words = explode(' ', $keyword);
    
    //     // Prepare fulltext search string
    //     $searchString = '';
    //     foreach ($words as $word) {
    //         if (strlen($word) > 0) {
    //             $searchString .= '+' . $word . ' ';
    //         }
    //     }
    //     $searchString = trim($searchString);
    
    //     // Get matching categories first
    //     $sql = "SELECT 
    //                 categories.id AS category_id, 
    //                 categories.name AS category_name, 
    //                 categories.slug AS category_slug,
    //                 (
    //                     IFNULL(
    //                         MATCH(categories.name, categories.slug, categories.description) AGAINST(? IN BOOLEAN MODE),
    //                         0
    //                     )
    //                     + CASE WHEN categories.name LIKE ? THEN 5 ELSE 0 END
    //                 ) AS relevance 
    //             FROM categories 
    //             WHERE 
    //                 (MATCH(categories.name, categories.slug, categories.description) AGAINST(? IN BOOLEAN MODE)
    //                 OR categories.name LIKE ?
    //                 OR categories.slug LIKE ?
    //                 OR categories.description LIKE ?)
    //                 AND categories.status = 1 
    //             ORDER BY relevance DESC 
    //             LIMIT 20";
    
    //     $bindings = [
    //         $searchString, 
    //         "%$keyword%", 
    //         $searchString, 
    //         "%$keyword%", 
    //         "%$keyword%", 
    //         "%$keyword%"
    //     ];
    
    //     $query = $db->query($sql, $bindings);
    //     $categories = $query->getResultArray();
    
    //     if (empty($categories)) {
    //         return [];
    //     }
    
    //     // Extract category IDs
    //     $categoryIds = array_column($categories, 'category_id');
    
    //     // Now count products under these categories that also match the keyword
    //     $builder = $db->table('products');
    
    //     // Same fulltext + fallback logic for products
    //     $builder->select('products.category_id, COUNT(*) AS product_count')
    //         ->join('product_variants pv', 'pv.product_id = products.id', 'left')
    //         ->whereIn('products.category_id', $categoryIds)
    //         ->where('products.status', 1)
    //         ->groupStart()
    //             ->where("MATCH(products.name, products.short_description, products.model, products.vpn) AGAINST(:search: IN BOOLEAN MODE)", ['search' => $searchString], false)
    //             ->orLike('products.name', $keyword)
    //             ->orLike('products.short_description', $keyword)
    //             ->orLike('products.model', $keyword)
    //             ->orLike('products.vpn', $keyword)
    //         ->groupEnd()
    //         ->groupBy('products.category_id');

    //         echo $builder->getCompiledSelect(false); exit;
    
    //     $counts = $builder->get()->getResultArray();
    
    //     // Map counts by category_id
    //     $countsMap = array_column($counts, 'product_count', 'category_id');
    
    //     // Merge count back into original category list
    //     foreach ($categories as &$cat) {
    //         $cat['product_count'] = $countsMap[$cat['category_id']] ?? 0;
    //     }
    
    //     return $categories;
    // }

    // public function filterbycategoryWithProductCount($keyword = '')
    // {
    //     $db = \Config\Database::connect();

    //     // Clean the keyword
    //     $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    //     if (empty($keyword)) {
    //         return [];
    //     }

    //     $words = explode(' ', $keyword);

    //     // Build the boolean search string for MATCH
    //     $searchString = '';
    //     foreach ($words as $word) {
    //         if (!empty($word)) {
    //             $searchString .= '+' . $word . ' ';
    //         }
    //     }
    //     $searchString = trim($searchString);

    //     // Prepare dynamic CASE WHEN conditions for each word
    //     $caseConditions = [];
    //     $likeConditions = [];
    //     foreach ($words as $word) {
    //         $escapedWord = $db->escapeLikeString($word);
    //         $caseConditions[] = "CASE 
    //             WHEN categories.name LIKE '%$escapedWord%' 
    //             OR categories.slug LIKE '%$escapedWord%' 
    //             OR categories.description LIKE '%$escapedWord%' 
    //             THEN 2 ELSE 0 END";
            
    //         $likeConditions[] = "categories.name LIKE '%$escapedWord%'";
    //         $likeConditions[] = "categories.slug LIKE '%$escapedWord%'";
    //         $likeConditions[] = "categories.description LIKE '%$escapedWord%'";
    //     }

    //     // Build full SQL
    //     $sql = "SELECT 
    //                 categories.id AS category_id,
    //                 categories.name AS category_name,
    //                 categories.slug AS category_slug,
    //                 (
    //                     IFNULL(
    //                         (MATCH(categories.name, categories.slug, categories.description) 
    //                         AGAINST(? IN BOOLEAN MODE)), 
    //                     0)
    //                     + " . implode(' + ', $caseConditions) . "
    //                 ) AS relevance
    //             FROM categories
    //             WHERE (
    //                 MATCH(categories.name, categories.slug, categories.description) 
    //                 AGAINST(? IN BOOLEAN MODE)
    //                 OR " . implode(' OR ', $likeConditions) . "
    //             )
    //             AND categories.status = 1
    //             ORDER BY relevance DESC
    //             LIMIT 20";

    //     // Bindings
    //     $bindings = [$searchString, $searchString];

    //     $query = $db->query($sql, $bindings);
    //     return $query->getResultArray();
    // }

    public function filterByCategoryWithProductCount($keyword = '')
    {
        $db = \Config\Database::connect();

        // Clean the keyword
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return [];
        }

        $words = explode(' ', $keyword);

        // Build the boolean search string for MATCH
        $searchString = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $searchString .= '+' . $word . ' ';
            }
        }
        $searchString = trim($searchString);

        // Prepare dynamic CASE WHEN conditions for each word
        $caseConditions = [];
        $likeConditions = [];
        foreach ($words as $word) {
            $escapedWord = $db->escapeLikeString($word);
            $caseConditions[] = "CASE 
                WHEN categories.name LIKE '%$escapedWord%' 
                OR categories.slug LIKE '%$escapedWord%' 
                OR categories.description LIKE '%$escapedWord%' 
                THEN 2 ELSE 0 END";

            $likeConditions[] = "categories.name LIKE '%$escapedWord%'";
            $likeConditions[] = "categories.slug LIKE '%$escapedWord%'";
            $likeConditions[] = "categories.description LIKE '%$escapedWord%'";
        }

        // Build SQL to fetch categories
        $sql = "SELECT 
                    categories.id AS category_id,
                    categories.name AS category_name,
                    categories.slug AS category_slug,
                    (
                        IFNULL(
                            (MATCH(categories.name, categories.slug, categories.description) 
                            AGAINST(? IN BOOLEAN MODE)), 
                        0)
                        + " . implode(' + ', $caseConditions) . "
                    ) AS relevance
                FROM categories
                WHERE (
                    MATCH(categories.name, categories.slug, categories.description) 
                    AGAINST(? IN BOOLEAN MODE)
                    OR " . implode(' OR ', $likeConditions) . "
                )
                AND categories.status = 1
                ORDER BY relevance DESC
                LIMIT 20";

        $bindings = [$searchString, $searchString];
        $query = $db->query($sql, $bindings);
        $categories = $query->getResultArray();

        if (empty($categories)) {
            return [];
        }

        // Extract category IDs
        $categoryIds = array_column($categories, 'category_id');

        // Count products for these categories
        $builder = $db->table('products');
        $builder->select('category_id, COUNT(*) AS product_count');
        $builder->whereIn('category_id', $categoryIds);
        $builder->where('status', 1);
        $builder->groupBy('category_id');

        $counts = $builder->get()->getResultArray();

        // Map counts by category_id
        $countsMap = array_column($counts, 'product_count', 'category_id');

        // Merge product counts into categories
        foreach ($categories as &$cat) {
            $cat['product_count'] = $countsMap[$cat['category_id']] ?? 0;
        }

        return $categories;
    }

    public function filterByProductLeftFiltersCategory(string $keyword = '', array $category_ids = []): array
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return [];
        }
    
        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // Example: +hp +probook
    
        $db = \Config\Database::connect();
    
        // Step 1: Build the subquery manually
        $matchScoreSQL = '
            (MATCH(p.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
            (MATCH(p.short_description, p.model, p.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
            ' . implode(' + ', array_map(function ($word) {
                $plainWord = str_replace('+', '', $word);
                return '(CASE WHEN LOCATE("' . $plainWord . '", p.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", p.name)) ELSE 0 END)';
            }, $words));
    
        $whereCategory = '';
        if (!empty($category_ids)) {
            $whereCategory = ' AND p.category_id IN (' . implode(',', array_map('intval', $category_ids)) . ')';
        }
    
        // Full Raw Query
        $sql = "
            SELECT
                matched_products.category_id,
                COUNT(matched_products.id) AS product_count,
                categories.name AS category_name,
                categories.slug AS category_slug
            FROM (
                SELECT
                    p.id,
                    p.category_id,
                    ($matchScoreSQL) AS match_score
                FROM products p
                WHERE p.status = 1
                $whereCategory
                HAVING match_score > 0
            ) AS matched_products
            JOIN categories ON categories.id = matched_products.category_id
            GROUP BY matched_products.category_id
        ";
    
        $query = $db->query($sql);
        return $query->getResultArray();
    }

    public function filterByProductLeftFiltersBrands(string $keyword = '', array $brand_ids = []): array
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return [];
        }
    
        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // Example: +hp +probook
    
        $db = \Config\Database::connect();
    
        // Build the match score calculation
        $matchScoreSQL = '
            (MATCH(p.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
            (MATCH(p.short_description, p.model, p.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
            ' . implode(' + ', array_map(function ($word) {
                $plainWord = str_replace('+', '', $word);
                return '(CASE WHEN LOCATE("' . $plainWord . '", p.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", p.name)) ELSE 0 END)';
            }, $words));
    
        $whereBrand = '';
        if (!empty($brand_ids)) {
            $whereBrand = ' AND p.brand IN (' . implode(',', array_map('intval', $brand_ids)) . ')';
        }
    
        // Full raw query switching to brands
        $sql = "
            SELECT
                matched_products.brand_id,
                COUNT(matched_products.id) AS product_count,
                brands.name AS brand_name,
                brands.slug AS brand_slug
            FROM (
                SELECT
                    p.id,
                    p.brand AS brand_id,
                    ($matchScoreSQL) AS match_score
                FROM products p
                WHERE p.status = 1
                $whereBrand
                HAVING match_score > 0
            ) AS matched_products
            JOIN brands ON brands.id = matched_products.brand_id
            GROUP BY matched_products.brand_id
        ";
    
        $query = $db->query($sql);
        return $query->getResultArray();
    }
    
    

    public function filterbyproduct(string $keyword = '', int $category_id = 0, $perPage = 12, $offset = 0): array
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return [];
        }

        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // +hp +probook

        $db = \Config\Database::connect();
        $builder = $db->table('products');

        // Select fields
        $builder->select('
            products.id AS product_id,
            products.name AS product_name,
            products.image AS product_img,
            products.image AS product_image,
            brands.name AS brand_name,
            products.vpn AS p_vpn,
            products.slug AS product_slug,
            categories.id AS category_id,
            categories.name AS category_name,
            categories.slug AS category_slug,
            pv.price AS product_price,
            pv.price AS pv_price,
            pv.rrp AS pv_rrp,

            MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS title_relevance,
            MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS other_relevance,

            (
                (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                ' . implode(' + ', array_map(function($word) {
                    $plainWord = str_replace('+', '', $word);
                    return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
                }, $words)) . '
            ) AS match_score
        ');

        // Joins
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('brands', 'brands.id = products.brand');
        $builder->join('product_variants pv', 'pv.product_id = products.id');

        // Conditions
        $builder->where('products.status', 1);

        // No need MATCH() inside WHERE anymore!

        // HAVING match_score > 0
        $builder->having('match_score >', 0);

        // Ordering
        $builder->orderBy('match_score', 'DESC');
        $builder->orderBy('title_relevance', 'DESC');
        $builder->orderBy('other_relevance', 'DESC');

        // Pagination
        $builder->limit($perPage, $offset);

        $query = $builder->get();
        return $query->getResultArray();
    }

    // public function filterbyproductMultiple(string $keyword = '', array $category_ids = [], array $brand_ids = [], int $perPage = 12, int $offset = 0): array
    // {
    //     $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    //     if (empty($keyword)) {
    //         return [];
    //     }

    //     $words = explode(' ', $keyword);
    //     $searchStringFull = '+' . implode(' +', $words); // +hp +probook

    //     $db = \Config\Database::connect();
    //     $builder = $db->table('products');

    //     // Select fields
    //     $builder->select('
    //         products.id AS product_id,
    //         products.name AS product_name,
    //         products.image AS product_img,
    //         products.image AS product_image,
    //         brands.name AS brand_name,
    //         products.vpn AS p_vpn,
    //         products.slug AS product_slug,
    //         categories.id AS category_id,
    //         categories.name AS category_name,
    //         categories.slug AS category_slug,
    //         pv.price AS product_price,
    //         pv.price AS pv_price,
    //         pv.rrp AS pv_rrp,

    //         MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS title_relevance,
    //         MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS other_relevance,

    //         (
    //             (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
    //             (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
    //             ' . implode(' + ', array_map(function($word) {
    //                 $plainWord = str_replace('+', '', $word);
    //                 return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
    //             }, $words)) . '
    //         ) AS match_score
    //     ');

    //     // Joins
    //     $builder->join('categories', 'categories.id = products.category_id');
    //     $builder->join('brands', 'brands.id = products.brand');
    //     $builder->join('product_variants pv', 'pv.product_id = products.id');

    //     // Basic conditions
    //     $builder->where('products.status', 1);

    //     // Additional conditions
    //     if (!empty($category_ids)) {
    //         $builder->whereIn('products.category_id', $category_ids);
    //     }

    //     if (!empty($brand_ids)) {
    //         $builder->whereIn('products.brand', $brand_ids);
    //     }

    //     // Only products matching the search
    //     $builder->having('match_score >', 0);

    //     // Ordering
    //     $builder->orderBy('match_score', 'DESC');
    //     $builder->orderBy('title_relevance', 'DESC');
    //     $builder->orderBy('other_relevance', 'DESC');

    //     // Pagination
    //     $builder->limit($perPage, $offset);

    //     // echo $builder->getCompiledSelect(false); exit;

    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

    public function filterbyproductMultiple(string $keyword = '', array $category_ids = [], array $brand_ids = [], int $perPage = 12, int $offset = 0, array $attribute = []): array
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return [];
        }

        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // +hp +probook

        $db = \Config\Database::connect();
        $builder = $db->table('products');

        // Select fields
        $builder->select('
            products.id AS product_id,
            products.name AS product_name,
            products.image AS product_img,
            products.image AS product_image,
            brands.name AS brand_name,
            products.vpn AS p_vpn,
            products.slug AS product_slug,
            categories.id AS category_id,
            categories.name AS category_name,
            categories.slug AS category_slug,
            pv.price AS product_price,
            pv.price AS pv_price,
            pv.rrp AS pv_rrp,

            MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS title_relevance,
            MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) AS other_relevance,

            (
                (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                ' . implode(' + ', array_map(function($word) {
                    $plainWord = str_replace('+', '', $word);
                    return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
                }, $words)) . '
            ) AS match_score
        ');

        // Joins
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('brands', 'brands.id = products.brand');
        $builder->join('product_variants pv', 'pv.product_id = products.id');

        // Filter: only active products
        $builder->where('products.status', 1);

        // Category filter
        if (!empty($category_ids)) {
            $builder->whereIn('products.category_id', $category_ids);
        }

        // Brand filter
        if (!empty($brand_ids)) {
            $builder->whereIn('products.brand', $brand_ids);
        }

        // Attribute filter
        if (!empty($attribute)) {
            foreach ($attribute as $key => $values) {
                $valuesArray = explode(' ', $values['filter_value']);
                if (!empty($valuesArray)) {
                    $attr_ids = $db->table('attributes')
                        ->select('id')
                        ->where('attribute_set_id', $values['attribute_set_id'])
                        ->whereIn('slug', $valuesArray)
                        ->get()
                        ->getResultArray();

                    $attribute_value_ids = array_column($attr_ids, 'id');

                    if (!empty($attribute_value_ids)) {
                        $alias = "pa_$key";
                        $builder->join("product_attributes $alias", "$alias.product_id = products.id");
                        $builder->where("$alias.attribute_id", $values['attribute_set_id']);
                        $builder->whereIn("$alias.attribute_value_id", $attribute_value_ids);
                    }
                }
            }
        }

        // Search relevance filter
        $builder->having('match_score >', 0);

        // Order by relevance
        $builder->orderBy('match_score', 'DESC');
        $builder->orderBy('title_relevance', 'DESC');
        $builder->orderBy('other_relevance', 'DESC');

        // Pagination
        $builder->limit($perPage, $offset);
        
        // echo $builder->getCompiledSelect(false); exit;
        // Run query
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function countFilterByProductMultiple(string $keyword = '', array $category_ids = [], array $brand_ids = []): int
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return 0;
        }

        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // +hp +probook

        $db = \Config\Database::connect();
        $builder = $db->table('products');

        // Select COUNT(*) and match_score calculation
        $builder->select('
            COUNT(products.id) AS total_count,
            (
                (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                ' . implode(' + ', array_map(function($word) {
                    $plainWord = str_replace('+', '', $word);
                    return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
                }, $words)) . '
            ) AS match_score
        ');

        // Joins
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('brands', 'brands.id = products.brand');
        $builder->join('product_variants pv', 'pv.product_id = products.id');

        // Basic conditions
        $builder->where('products.status', 1);

        // Filters
        if (!empty($category_ids)) {
            $builder->whereIn('products.category_id', $category_ids);
        }

        if (!empty($brand_ids)) {
            $builder->whereIn('products.brand', $brand_ids);
        }

        // Only matching products
        $builder->having('match_score >', 0);

        $query = $builder->get();
        $result = $query->getRowArray();

        return $result['total_count'] ?? 0;
    }



    // public function filterbyproductCount(string $keyword = '', array $category_ids = [], array $brand_ids = []): int
    // {
    //     $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
    //     if (empty($keyword)) {
    //         return 0;
    //     }
    
    //     $words = explode(' ', $keyword);
    //     $searchStringFull = '+' . implode(' +', $words); // +hp +probook
    
    //     $db = \Config\Database::connect();
    
    //     // Build the original query first
    //     $builder = $db->table('products');
    
    //     $builder->select('products.id'); // Just select ID because we need to count rows
    
    //     // Joins
    //     $builder->join('categories', 'categories.id = products.category_id');
    //     $builder->join('brands', 'brands.id = products.brand');
    //     $builder->join('product_variants pv', 'pv.product_id = products.id');
    
    //     // Basic condition
    //     $builder->where('products.status', 1);
    
    //     // Extra filters
    //     if (!empty($category_ids)) {
    //         $builder->whereIn('products.category_id', $category_ids);
    //     }
    
    //     if (!empty($brand_ids)) {
    //         $builder->whereIn('products.brand', $brand_ids);
    //     }
    
    //     // Match score calculation
    //     $builder->select('
    //         (
    //             (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
    //             (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
    //             ' . implode(' + ', array_map(function($word) {
    //                 $plainWord = str_replace('+', '', $word);
    //                 return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
    //             }, $words)) . '
    //         ) AS match_score
    //     ');
    
    //     // Having match_score > 0
    //     $builder->having('match_score >', 0);
    
    //     // Compile the subquery
    //     $subQuery = $builder->getCompiledSelect();
    
    //     // Now count over the subquery
    //     $query = $db->query('SELECT COUNT(*) AS total_count FROM (' . $subQuery . ') AS temp');
    //     $row = $query->getRowArray();
    
    //     return (int) ($row['total_count'] ?? 0);
    // }
    
    public function filterbyproductCount(string $keyword = '', array $category_ids = [], array $brand_ids = [], array $attribute = []): int
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return 0;
        }
    
        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // +hp +probook
    
        $db = \Config\Database::connect();
        $builder = $db->table('products');
    
        $builder->select('products.id'); // We only need ID for counting
    
        // Joins
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('brands', 'brands.id = products.brand');
        $builder->join('product_variants pv', 'pv.product_id = products.id');
    
        // Basic condition
        $builder->where('products.status', 1);
    
        // Filters
        if (!empty($category_ids)) {
            $builder->whereIn('products.category_id', $category_ids);
        }
    
        if (!empty($brand_ids)) {
            $builder->whereIn('products.brand', $brand_ids);
        }
    
        // Attribute filters
        if (!empty($attribute)) {
            foreach ($attribute as $key => $values) {
                $valuesArray = explode(' ', $values['filter_value']);
                if (!empty($valuesArray)) {
                    $attr_ids = $db->table('attributes')
                        ->select('id')
                        ->where('attribute_set_id', $values['attribute_set_id'])
                        ->whereIn('slug', $valuesArray)
                        ->get()
                        ->getResultArray();
    
                    $attribute_value_ids = array_column($attr_ids, 'id');
    
                    if (!empty($attribute_value_ids)) {
                        $alias = "pa_$key";
                        $builder->join("product_attributes $alias", "$alias.product_id = products.id");
                        $builder->where("$alias.attribute_id", $values['attribute_set_id']);
                        $builder->whereIn("$alias.attribute_value_id", $attribute_value_ids);
                    }
                }
            }
        }
    
        // Match score
        $builder->select('
            (
                (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                ' . implode(' + ', array_map(function($word) {
                    $plainWord = str_replace('+', '', $word);
                    return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
                }, $words)) . '
            ) AS match_score
        ');
    
        $builder->having('match_score >', 0);
    
        // Compile subquery
        $subQuery = $builder->getCompiledSelect();
    
        // Count results
        $query = $db->query('SELECT COUNT(*) AS total_count FROM (' . $subQuery . ') AS temp');
        $row = $query->getRowArray();
    
        return (int) ($row['total_count'] ?? 0);
    }
    

   
    public function filterbyproductTotalCount(string $keywords = '', int $category_id = 0): int
    {
        $db = \Config\Database::connect();
    
        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return 0;
        }
    
        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);
    
        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }
    
        $builder = $db->table('products')
            ->select('COUNT(DISTINCT products.id) AS total_count')
            ->join('categories', 'categories.id = products.category_id')
            ->join('brands', 'brands.id = products.brand')
            ->join('product_variants pv', 'pv.product_id = products.id')
            ->where('products.status', 1);
    
        if ($category_id > 0) {
            $builder->where('products.category_id', $category_id);
        }
    
        $builder->groupStart();
        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.short_description,
                products.model,
                products.vpn
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }
    
        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc     = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";
    
                $builder->groupStart()
                    ->where("products.name              REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.model             REGEXP '{$pattern}'")
                    ->orWhere("products.vpn               REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }
        $builder->groupEnd();
    
        $row = $builder->get()->getRow();
        return (int) ($row->total_count ?? 0);
    }

    public function countProductsByCategory(string $keyword = '', int $category_id = 0): int
    {
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (empty($keyword)) {
            return 0;
        }
    
        $words = explode(' ', $keyword);
        $searchStringFull = '+' . implode(' +', $words); // +hp +probook
    
        $db = \Config\Database::connect();
        $builder = $db->table('products');
    
        // Select fields including match_score
        $builder->select('
            COUNT(products.id) AS product_count,
            (
                (MATCH(products.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                (MATCH(products.short_description, products.model, products.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                ' . implode(' + ', array_map(function($word) {
                    $plainWord = str_replace('+', '', $word);
                    return '(CASE WHEN LOCATE("' . $plainWord . '", products.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", products.name)) ELSE 0 END)';
                }, $words)) . '
            ) AS match_score
        ');
    
        // Joins
        $builder->join('categories', 'categories.id = products.category_id');
        $builder->join('product_variants pv', 'pv.product_id = products.id');
    
        // Basic conditions
        $builder->where('products.status', 1);
    
        // Additional conditions for category
        if (!empty($category_id)) {
            $builder->where('products.category_id', $category_id);
        }
    
        // Only products matching the search
        $builder->having('match_score >', 0);
    
        // Run the query
        $query = $builder->get();
        $result = $query->getRowArray();
    
        return $result['product_count'] ?? 0;
    }
    


    public function filterCategoriesByProductKeyword(string $keywords = ''): array
    {
        $db = \Config\Database::connect();

        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return [];
        }

        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);

        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }

        $builder = $db->table('products')
            ->select([
                'categories.id AS category_id',
                'categories.name AS category_name',
                'categories.slug AS category_slug',
                'COUNT(DISTINCT products.id) AS product_count'
            ])
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('brands', 'brands.id = products.brand', 'left')
            ->join('product_variants pv', 'pv.product_id = products.id', 'left')
            ->where('products.status', 1);

        $builder->groupStart();
        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.short_description,
                products.model,
                products.vpn
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }

        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc     = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";

                $builder->groupStart()
                    ->where("products.name              REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.model             REGEXP '{$pattern}'")
                    ->orWhere("products.vpn               REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }
        $builder->groupEnd();

        $builder->groupBy('categories.id');

        return $builder->get()->getResultArray();
    }

    public function filterMatchedCategoriesWithCount(string $keywords = ''): array
    {
        $db = \Config\Database::connect();

        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return [];
        }

        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);

        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }

        $builder = $db->table('products')
            ->select([
                'categories.id AS category_id',
                'categories.name AS category_name',
                'categories.slug AS category_slug',
                'COUNT(DISTINCT products.id) AS product_count'
            ])
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('brands', 'brands.id = products.brand', 'left')
            ->join('product_variants pv', 'pv.product_id = products.id', 'left')
            ->where('products.status', 1);

        // Apply the keyword filters like in your product search
        $builder->groupStart();
        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.short_description,
                products.model,
                products.vpn
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }

        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc     = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";

                $builder->groupStart()
                    ->where("products.name              REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.model             REGEXP '{$pattern}'")
                    ->orWhere("products.vpn               REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }
        $builder->groupEnd();

        $builder->groupBy('categories.id');
        $builder->limit(20);

        return $builder->get()->getResultArray();
    }

    public function searchProductGroupedByCategory(string $keywords = ''): array
    {
        $db = \Config\Database::connect();

        // Normalize and tokenize keywords
        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return [];
        }

        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);

        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }

        $builder = $db->table('products')
            ->select([
                'categories.id AS category_id',
                'categories.name AS category_name',
                'categories.slug AS category_slug',
                'COUNT(DISTINCT products.id) AS product_count'
            ])
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('brands', 'brands.id = products.brand', 'left')
            ->join('product_variants pv', 'pv.product_id = products.id', 'left')
            ->where('products.status', 1);

        // Apply full-text and regex keyword filtering
        $builder->groupStart();

        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.short_description,
                products.model,
                products.vpn
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }

        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc     = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";

                $builder->groupStart()
                    ->where("products.name              REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.model             REGEXP '{$pattern}'")
                    ->orWhere("products.vpn               REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }

        $builder->groupEnd();

        // Group results by category
        $builder->groupBy('categories.id');

        return $builder->get()->getResultArray();
    }


  
    public function filterbyproduct_old(string $keywords = '', int $category_id = 0): array
    {
        $db = \Config\Database::connect();
    
        // 🔔 Make sure to have a FULLTEXT index on: name, description, model, vpn, short_description, extra_description
    
        // Split and clean keywords
        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return [];
        }
    
        // Partition keywords by length
        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);
    
        // Build boolean full-text search string
        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }
    
        // LOCATE() for keyword position ordering
        $locateParts = [];
        foreach ($terms as $word) {
            $esc = addslashes($word);
            $locateParts[] = "IFNULL(NULLIF(LOCATE('{$esc}', products.name),0),9999)";
        }
        $keywordPosExpr = count($locateParts) > 1
            ? 'LEAST(' . implode(', ', $locateParts) . ')'
            : $locateParts[0];
    
        // Prepare SELECT fields including match_count
        $select = [
            'products.id          AS product_id',
            'products.name        AS product_name',
            'products.image       AS product_img',
            'products.slug        AS product_slug',
            'categories.id        AS category_id',
            'categories.name      AS category_name',
            'categories.slug      AS category_slug',
            'pv.price             AS product_price',
            "{$keywordPosExpr}    AS keyword_pos"
        ];
    
        $matchCountExpr = [];
    
        if (!empty($longTerms)) {
            $select[] = "MATCH(
                products.name,
                products.description,
                products.model,
                products.vpn,
                products.short_description,
                products.extra_description
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE) AS relevance";
    
            // Weighted fulltext relevance (10x)
            $matchCountExpr[] = "(MATCH(
                products.name,
                products.description,
                products.model,
                products.vpn,
                products.short_description,
                products.extra_description
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE) * 10)";
        }
    
        foreach ($shortTerms as $word) {
            $esc = addslashes($word);
            $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";
            foreach (['products.name', 'products.description', 'products.model', 'products.vpn', 'products.short_description', 'products.extra_description'] as $field) {
                // Each regex match counts as 2
                $matchCountExpr[] = "({$field} REGEXP '{$pattern}') * 2";
            }
        }
    
        // Final match_count field
        if ($matchCountExpr) {
            $select[] = '(' . implode(' + ', $matchCountExpr) . ') AS match_count';
        } else {
            $select[] = '0 AS match_count';
        }
    
        // Build query
        $builder = $db->table('products')
            ->select($select)
            ->join('categories', 'categories.id = products.category_id')
            ->join('product_variants pv', 'pv.product_id = products.id')
            ->where('products.status', 1);
    
        // Optional category filter
        if ($category_id > 0) {
            $builder->where('products.category_id', $category_id);
        }
    
        // WHERE conditions
        $builder->groupStart();
        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.description,
                products.model,
                products.vpn,
                products.short_description,
                products.extra_description
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }
    
        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";
                $builder->orGroupStart()
                    ->where("products.name REGEXP '{$pattern}'")
                    ->orWhere("products.description REGEXP '{$pattern}'")
                    ->orWhere("products.model REGEXP '{$pattern}'")
                    ->orWhere("products.vpn REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.extra_description REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }
        $builder->groupEnd();
    
        // ORDER BY match_count first, then relevance, then keyword position
        $builder->orderBy('match_count', 'DESC');
        if (!empty($longTerms)) {
            $builder->orderBy('relevance', 'DESC');
        }
        $builder->orderBy('keyword_pos', 'ASC', false);
    
        // Execute query
        $builder->limit(20);
    
        // Debug SQL
        //$sql = $builder->getCompiledSelect(false);
        //echo $sql; exit;
    
        // Run query
        $q = $builder->get();
        return $q->getResultArray();
    }
    
    public function filterbybrand($keyword = '')
    {
        $db = \Config\Database::connect();

        // Clean and format keyword
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword)); // remove extra spaces
        $words = explode(' ', $keyword);

        if (count($words) > 1) {
            $lastWord = array_pop($words);
            $searchString = '+' . implode(' ', $words) . ' ' . $lastWord . '*';
        } else {
            $searchString = '+' . $keyword . '*';
        }

        // SQL for full-text search with LIKE fallback and boosting
        $sql = "SELECT 
                    id AS brand_id,
                    name AS brand_name,
                    slug AS brand_slug,
                    (
                        MATCH(name, slug) 
                        AGAINST(? IN BOOLEAN MODE) +
                        CASE WHEN LOWER(name) LIKE ? THEN 5 ELSE 0 END
                    ) AS relevance
                FROM brands
                WHERE 
                    status = 1 AND (
                        MATCH(name, slug) AGAINST(? IN BOOLEAN MODE)
                        OR LOWER(name) LIKE ?
                    )
                ORDER BY relevance DESC
                LIMIT 20";

        $likeKeyword = '%' . strtolower($keyword) . '%';

        $bindings = [$searchString, $likeKeyword, $searchString, $likeKeyword];

        $query = $db->query($sql, $bindings);
        
        // Debugging query
        //echo $db->getLastQuery(); exit;

        return $query->getResultArray();
    }

    public function filterbyproductTotalCountByBrand(string $keywords = '', int $brand_id = 0): int
    {
        $db = \Config\Database::connect();
    
        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));
        $terms = array_filter(explode(' ', $keywords));
        if (empty($terms)) {
            return 0;
        }
    
        $shortTerms = array_filter($terms, fn($t) => mb_strlen($t) < 3);
        $longTerms  = array_filter($terms, fn($t) => mb_strlen($t) >= 3);
    
        $booleanSearch = '';
        if (!empty($longTerms)) {
            $escapedLongs = array_map('addslashes', $longTerms);
            $booleanSearch = '+' . implode(' +', $escapedLongs);
        }
    
        $builder = $db->table('products')
            ->selectCount('products.id', 'total_count')
            ->where('products.status', 1);
    
        if ($brand_id > 0) {
            $builder->where('products.brand', $brand_id);
        }
    
        $builder->groupStart();
    
        if (!empty($longTerms)) {
            $builder->where("MATCH(
                products.name,
                products.short_description,
                products.model,
                products.vpn
            ) AGAINST('{$booleanSearch}' IN BOOLEAN MODE)");
        }
    
        if (!empty($shortTerms)) {
            foreach ($shortTerms as $word) {
                $esc = addslashes($word);
                $pattern = "(^|[^[:alnum:]_]){$esc}($|[^[:alnum:]_])";
                $builder->groupStart()
                    ->where("products.name              REGEXP '{$pattern}'")
                    ->orWhere("products.short_description REGEXP '{$pattern}'")
                    ->orWhere("products.model             REGEXP '{$pattern}'")
                    ->orWhere("products.vpn               REGEXP '{$pattern}'")
                ->groupEnd();
            }
        }
    
        $builder->groupEnd();
    
        $result = $builder->get()->getRowArray();
        return (int) ($result['total_count'] ?? 0);
    }
    



   
}