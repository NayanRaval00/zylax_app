<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductFeatures extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_features';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'feature_id'];

    function getProductFeaturesListing($product_id)
    {
        $builder = $this->db->table('product_features as pf');
        $builder->select('pf.id as id, pmf.icon as icon, pmf.text as text');
        $builder->join('product_master_features as pmf', 'pf.feature_id = pmf.id', 'left');
        $builder->where('pf.product_id', $product_id);
        $builder->orderBy('pf.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}