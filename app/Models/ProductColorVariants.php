<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductColorVariants extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_color_variants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'color', 'label', 'product_variant_id'];


    function getProductColorVariantsListing($product_id)
    {
        $builder = $this->db->table('product_color_variants as pcv');
        $builder->select('pcv.*, p.name as product_name, p.slug as product_slug');
        $builder->join('products as p', 'pcv.product_variant_id = p.id', 'left');
        $builder->where('pcv.product_id', $product_id);
        $builder->where('p.status', 1);
        $builder->orderBy('pcv.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}