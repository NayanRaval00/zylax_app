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

    // function getProductsFiltersListing()
    // {
    //     $builder = $this->db->table('products as p');
    //     $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.is_hot_deal, p.is_best_seller, p.category_id');
    //     $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
    //     $builder->orderBy('p.id', 'ASC');
    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

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

    function getProductsBestSellers()
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.date_added, p.category_id');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->groupBy('p.date_added');
        $builder->orderBy('p.id', 'DESC');
        $builder->where('p.is_best_seller', 1);
        $builder->where('p.status', 1);
        $builder->limit(12);
        $query = $builder->get();
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

    function getProductsFiltersListing($search = '', $filterBy = '', $category = [], $brand = [], $minPrice = '', $maxPrice = '', $attribute = [], $tags = [], $perPage = 12, $offset = 0, $cleanInput = '')
    {
        // echo $perPage; exit;
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.category_id as category_id, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, brands.name as brand_name, p.vpn as p_vpn');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->join('brands', 'brands.id = p.brand', 'left');

        // if (!empty($attribute) && $attribute != "") {
        //     $builder->join('attribute_set_category as asc', 'asc.category_id = p.category_id', 'left');
        //     $builder->whereIn('asc.attribute_set_id', $attribute);
        // }

        if (!empty($attribute)) {
            foreach ($attribute as $key => $values) {
                $valuesArray = explode(' ', $values['filter_value']); // Split multiple values
                // print_r($valuesArray); exit;
                
                $attribute_value_ids = [];
                if(!empty($valuesArray) ){
                    
                    $attr_ids = $this->db->table('attributes')
                                        ->select('id')
                                        ->where('attribute_set_id', $values['attribute_set_id'])
                                        // ->whereIn('name', $valuesArray)
                                        ->whereIn('slug', $valuesArray)
                                        ->get()
                                        ->getResultArray();

                    $attribute_value_ids = array_column($attr_ids, 'id');

                    // print_r($attr_ids); exit;
                    // print_r($attribute_value_ids); exit;
                    if(!empty($attribute_value_ids) && is_array($attribute_value_ids)){
                        $builder->join('product_attributes pa_' . $key, 'pa_' . $key . '.product_id = p.id')
                        ->where('pa_' . $key . '.attribute_id', $values['attribute_set_id'])
                        ->whereIn('pa_' . $key . '.attribute_value_id', $attribute_value_ids);
                    }
                }            
              
            }
        }

        // print_r($builder); exit;

        if (!empty($tags) && $tags != "") {
            $builder->join('product_tags as tags', 'tags.product_id = p.id', 'left');
            $builder->whereIn('tags.tag_id', $tags);
        }

        if (!empty($search)) {
            $builder->like('p.name', $search);
        }
        
        if (!empty($cleanInput)){
            $builder->like('p.name', $cleanInput);
        }

        if (!empty($category) && $category != "") {
            $builder->whereIn('p.category_id', $category);
        }

        if (!empty($brand) && $brand != "") {
            $builder->whereIn('p.brand', $brand);
        }

        if (!empty($minPrice) && $minPrice != "") {
            $builder->where('pv.price >=', $minPrice);
        }
         if (!empty($maxPrice) && $maxPrice != "") {
            $builder->where('pv.price <=', $maxPrice);
        }
        $builder->where('p.status', 1);;
        
        //  if (!empty($brandId) && $brandId != "") {
        //     $builder->where('p.brand', $brandId);
        // }

        //  Apply brand filter if provided
         if (!empty($filterBy) && $filterBy == "name_asc") {
            $builder->orderBy('p.name', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "name_dsc") {
            $builder->orderBy('p.name', 'DESC');
        }else if (!empty($filterBy) && $filterBy == "price_high") {
            $builder->orderBy('pv.price', 'DESC');
        }else if (!empty($filterBy) && $filterBy == "price_low") {
            $builder->orderBy('pv.price', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "latest") {
            $builder->orderBy('p.id', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "oldest") {
            $builder->orderBy('p.id', 'DESC');
        }


        // $builder->orderBy('pv.price', 'ASC');
        


        // $builder->orderBy('p.name', 'DESC');
        // $builder->orderBy('p.id', 'ASC');
        $builder->limit($perPage, $offset);
                
        // echo "<pre>";
        // print_r($builder); exit;

        $query = $builder->get();
        // echo $sql = $builder->getCompiledSelect();
        // echo $this->db->getLastQuery(); exit;

        return $query->getResultArray();
    }

    function getProductsFiltersListingCount($search = '', $filterBy = '', $category = [], $brand = [], $minPrice = '', $maxPrice = '', $attribute = [], $tags = [])
    {
        // echo $perPage; exit;
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.category_id as category_id, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, brands.name as brand_name, p.vpn as p_vpn');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->join('brands', 'brands.id = p.brand', 'left');

        // if (!empty($attribute) && $attribute != "") {
        //     $builder->join('attribute_set_category as asc', 'asc.category_id = p.category_id', 'left');
        //     $builder->whereIn('asc.attribute_set_id', $attribute);
        // }

        if (!empty($attribute)) {
            foreach ($attribute as $key => $values) {
                $valuesArray = explode(' ', $values['filter_value']); // Split multiple values
                // print_r($valuesArray); exit;
                
                $attribute_value_ids = [];
                if(!empty($valuesArray) ){
                    
                    $attr_ids = $this->db->table('attributes')
                                        ->select('id')
                                        ->where('attribute_set_id', $values['attribute_set_id'])
                                        // ->whereIn('name', $valuesArray)
                                        ->whereIn('slug', $valuesArray)
                                        ->get()
                                        ->getResultArray();
                    //     echo $this->db->getLastQuery(); exit;
                    // print_r($attr_ids);  exit;

                    $attribute_value_ids = array_column($attr_ids, 'id');

                    // print_r($attr_ids); exit;
                    // print_r($attribute_value_ids); exit;

                    if(!empty($attribute_value_ids) && is_array($attribute_value_ids)){
                        $builder->join('product_attributes pa_' . $key, 'pa_' . $key . '.product_id = p.id')
                        ->where('pa_' . $key . '.attribute_id', $values['attribute_set_id'])
                        ->whereIn('pa_' . $key . '.attribute_value_id', $attribute_value_ids);
                    }
                }            
              
            }
        }

        if (!empty($tags) && $tags != "") {
            $builder->join('product_tags as tags', 'tags.product_id = p.id', 'left');
            $builder->whereIn('tags.tag_id', $tags);
        }

        if (!empty($search)) {
            $builder->like('p.name', $search);
        }

        if (!empty($category) && $category != "") {
            $builder->whereIn('p.category_id', $category);
        }
        if (!empty($brand) && $brand != "") {
            $builder->whereIn('p.brand', $brand);
        }

        if (!empty($minPrice) && $minPrice != "") {
            $builder->where('pv.price >=', $minPrice);
        }
         if (!empty($maxPrice) && $maxPrice != "") {
            $builder->where('pv.price <=', $maxPrice);
        }
        $builder->where('p.status', 1);
        
        //  if (!empty($brandId) && $brandId != "") {
        //     $builder->where('p.brand', $brandId);
        // }

        //  Apply brand filter if provided
         if (!empty($filterBy) && $filterBy == "name_asc") {
            $builder->orderBy('p.name', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "name_dsc") {
            $builder->orderBy('p.name', 'DESC');
        }else if (!empty($filterBy) && $filterBy == "price_high") {
            $builder->orderBy('pv.price', 'DESC');
        }else if (!empty($filterBy) && $filterBy == "price_low") {
            $builder->orderBy('pv.price', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "latest") {
            $builder->orderBy('p.id', 'ASC');
        }else if (!empty($filterBy) && $filterBy == "oldest") {
            $builder->orderBy('p.id', 'DESC');
        }

        // $builder->orderBy('pv.price', 'ASC');
        
        
        // $builder->orderBy('p.name', 'DESC');
        // $builder->orderBy('p.id', 'ASC');
        // $builder->limit($perPage, $offset);
        // echo $sql = $builder->getCompiledSelect();
        $query = $builder->get();
        return $query->getResultArray();
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
        $builder = $this->db->table('categories');
        $builder->select('categories.id as category_id, categories.name as category_name, categories.slug as category_slug');
        $builder->where('categories.status', '1');
    
        if (!empty($keyword)) {
            $builder->groupStart();
            $keywords = explode(' ', $keyword);
            foreach ($keywords as $word) {
                $builder->orLike('categories.name', $word, 'both');
            }
            $builder->groupEnd();
        }
    
        $builder->orderBy('categories.name', 'ASC');
        $builder->limit(20);
        $query = $builder->get();
        return $query->getResultArray();
    }
  
    public function filterbyproduct($keyword = '')
    {
        $builder = $this->db->table('products');
        $builder->select('
            products.id as product_id, 
            products.name as product_name, 
            products.image as product_img, 
            products.slug as product_slug, 
            pv.price as product_price, 
            products.short_description, 
            products.description,
            products.sku
        ');
        $builder->join('product_variants as pv', 'pv.product_id = products.id', 'left');
        $builder->where('products.status', '1');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('products.name', $keyword, 'both')
                ->orLike('products.short_description', $keyword, 'both')
                ->orLike('products.description', $keyword, 'both')
                ->orLike('products.slug', $keyword, 'both')
            ->groupEnd();
        }

        $builder->orderBy('products.name', 'ASC');
        $builder->limit(20);

        // 🔥 Print raw SQL
        //echo $builder->getCompiledSelect(); // Just for debugging

        $query = $builder->get();
        return $query->getResultArray();
    }

    
     public function filterbybrand($keyword = '')
    {
        $builder = $this->db->table('brands');
        $builder->select('id as brand_id, name as brand_name, slug as brand_slug');
        $builder->where('status', '1');
    
        if (!empty($keyword)) {
            $builder->groupStart();
            $keywords = explode(' ', $keyword);
            foreach ($keywords as $word) {
                $builder->orLike('name', $word, 'both');
            }
            $builder->groupEnd();
        }
    
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    
}