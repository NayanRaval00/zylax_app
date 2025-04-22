<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductAttributes extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_attributes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'attribute_id', 'attribute_value_id', 'added_attribute_value'];

    function getProductAllAttributes($product_id = null)
    {
        $builder = $this->db->table('product_attributes pa');
        $builder->select('*');

        if (!empty($product_id) && $product_id != "") {
            $builder->where('pa.product_id', $product_id);
        }

        // $builder->where('p.status', 1);
        // $builder->orderBy('attr_set.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function attributefindOrCreate($data)
    {

        $exist_attribute = $this->where('product_id', $data['product_id'])
                                ->where('attribute_id', $data['attribute_id'])
                                ->where('attribute_value_id', $data['attribute_value_id'])
                                ->first();

        // print_r($exist_attribute); exit;
                
        if(isset($exist_attribute) && $exist_attribute != ""){

            // if($exist_attribute['attribute_value_id'] == $data['attribute_value_id']){
                $create_or_update = $this->where('id', $exist_attribute['id'])
                                        ->set(['added_attribute_value' =>  $data['added_attribute_value']])->update();
            // }else{
            //     $create_or_update = $this->where('id', $exist_attribute['id'])
            //                             ->set($data)->update();
            // }
        }
        else{
            $create_or_update = $this->insert($data);
        }
        return $create_or_update;
    }

    public function multipleAttributefindOrCreate($data)
    {

        $exist_attribute = $this->where('product_id', $data['product_id'])
                                ->where('attribute_id', $data['attribute_id'])
                                ->where('attribute_value_id', $data['attribute_value_id'])
                                ->first();

        // print_r($exist_attribute); exit;
                
        if(isset($exist_attribute) && $exist_attribute != ""){

            $create_or_update = $this->where('id', $exist_attribute['id'])
                                        ->set($data)->update();
        }
        else{
            $create_or_update = $this->insert($data);
        }
        return $create_or_update;
    }


}