<?php

namespace App\Models;

use CodeIgniter\Model;

class Categories extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'parent_id', 'slug', 'image', 'icon', 'is_show', 'description', 'banner', 'row_order', 'status', 'clicks', 'seo_page_title', 'seo_meta_keywords', 'seo_meta_description', 'seo_og_image'];
    
    function getCategoryProductsFiltersListing($category_id)
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->where('p.category_id', $category_id);
        $builder->orderBy('p.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductCountWithCategory($brand = ''){
        $builder = $this->db->table('categories as c');
        $builder->select('c.id AS category_id, c.name AS category_name, c.slug AS category_slug, COUNT(p.id) AS product_count');
        $builder->join('products as p', 'c.id = p.category_id');
        
         if (!empty($brand) && $brand != "") {
            $builder->where('p.brand', $brand);
        }
        
        $builder->where('c.status', 1);
        $builder->where('p.status', 1);
        $builder->groupBy(['c.id', 'c.name']);
        // $builder->orderBy('c.id', 'ASC');
        $builder->orderBy('c.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductCountWithMultpleCategory($category = ''){
        $builder = $this->db->table('categories as c');
        $builder->select('c.id AS category_id, c.name AS category_name, c.slug AS category_slug, COUNT(p.id) AS product_count');
        $builder->join('products as p', 'c.id = p.category_id');
        
         if (!empty($category) && $category != "") {
            $builder->whereIn('c.id', $category);
        }
        
        $builder->where('c.status', 1);
        $builder->where('p.status', 1);
        $builder->groupBy(['c.id', 'c.name']);
        // $builder->orderBy('c.id', 'ASC');
        $builder->orderBy('c.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getCategoryBreadcrumb($categoryId)
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

    public function getChildCategoryIds($parentId)
    {
        $result = [];
        $this->findChildren($parentId, $result);
        return $result;
    }

    /**
     * Recursive function to find child categories
     */
    private function findChildren($parentId, &$result)
    {
        $categories = $this->where('parent_id', $parentId)->findAll();
        $result[] = $parentId;
        foreach ($categories as $category) {
            $result[] = $category['id'];
            $this->findChildren($category['id'], $result); // Recursively find children
        }
    }
    
}

