<?php

namespace App\Models;

use CodeIgniter\Model;

class ShippingCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'shipping_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['shipping_id', 'price', 'status'];

    function getShippingCategory()
    {
        $builder = $this->db->table('shipping_category');
        $builder->select('shipping_category.*, shipping.name as shipping_name');
        $builder->join('shipping', 'shipping_category.shipping_id = shipping.id');
        // $builder->where('shipping_category.id', $shipping_id);
        $builder->orderBy('shipping_category.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}