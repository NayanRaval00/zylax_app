<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatedProductOptions extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'related_product_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'related_product_option_id', 'category_id'];

    function getProductOptionsListing($product_id)
    {
        $builder = $this->db->table('related_product_options as rpo');
        $builder->select('rpo.id as id, p.name as product_name, p.id as product_id, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status, p.is_hot_deal, p.is_best_seller, p.short_description, p.description, p.category_id');
        $builder->join('products as p', 'rpo.related_product_option_id = p.id', 'left');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->where('rpo.product_id', $product_id);
        $builder->where('p.status', 1);
        $builder->orderBy('rpo.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}