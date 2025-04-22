<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatedProducts extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'related_products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'related_product_id'];

    function getRelatedProductsListing($product_id)
    {
        $builder = $this->db->table('related_products as rp');
        $builder->select('rp.id as id, p.name as product_name, p.category_id');
        $builder->join('products as p', 'rp.related_product_id = p.id', 'left');
        $builder->where('rp.product_id', $product_id);
        $builder->where('p.status', 1);
        $builder->orderBy('rp.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getRelatedProductsByProductId($product_id)
    {
        $builder = $this->db->table('related_products as rp');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, p.category_id');
        $builder->join('products as p', 'rp.related_product_id = p.id', 'left');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->where('rp.product_id', $product_id);
        $builder->where('p.status', 1);
        $builder->orderBy('rp.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}