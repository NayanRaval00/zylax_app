<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductTags extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'tag_id'];

    function getProductCountWithTags(){
        $builder = $this->db->table('product_tags');
        $builder->select('product_master_tags.id as id, product_master_tags.name as name, product_master_tags.slug as slug, COUNT(products.id) AS product_count');
        $builder->join('products', 'product_tags.product_id = products.id');
        $builder->join('product_master_tags', 'product_tags.tag_id = product_master_tags.id');
        $builder->groupBy(['product_master_tags.id', 'product_master_tags.name']);
        $builder->orderBy('product_master_tags.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function multipleProductTagsfindOrCreate($data)
    {

        $exist_tag = $this->where('product_id', $data['product_id'])
                                ->where('tag_id', $data['product_id'])
                                ->first();

        // print_r($exist_tag); exit;
        if(empty($exist_tag) && $exist_tag == ""){
            $create_or_update = $this->insert($data);
        }
        return $create_or_update;
    }

}