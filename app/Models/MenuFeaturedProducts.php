<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuFeaturedProducts extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'menus_featured_products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['menu_id', 'product_type', 'product_id'];

    function getMenuFeaturedProductListing($menu_id)
    {
        $builder = $this->db->table('menus_featured_products as mfp');
        $builder->select('mfp.*, p.name as product_name');
        $builder->join('products as p', 'mfp.product_id = p.id', 'left');
        $builder->where('mfp.menu_id', $menu_id);
        // $builder->where('p.status', 1);
        $builder->orderBy('mfp.product_type', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}