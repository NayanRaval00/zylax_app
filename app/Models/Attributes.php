<?php

namespace App\Models;

use CodeIgniter\Model;

class Attributes extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'attributes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['attribute_set_id', 'name', 'slug', 'type', 'date_created', 'status'];

    public function getAttributesListing()
    {
        return $this->select('attributes.*, attribute_set.name as attribute_set_name')
                    ->join('attribute_set', 'attribute_set.id = attributes.attribute_set_id')
                    ->orderBy('attributes.id', 'DESC')
                    ->findAll();
    }

    public function getAttributesIdFromCategory($category_id, $attribute_id)
    {
        $builder = $this->db->table('attribute_set_category');
        $builder->select('attribute_set_category.*');
        $builder->join('attribute_set', 'attribute_set.id=attribute_set_category.attribute_set_id');
        $builder->where('attribute_set_category.category_id', $category_id);
        $builder->where('attribute_set.slug', $attribute_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    function getAttributeSetAttributeName($attribute_set_id = null)
    {
        $builder = $this->db->table('attributes a');
        $builder->select('id, name');

        if (!empty($attribute_set_id) && $attribute_set_id != "") {
            $builder->where('a.attribute_set_id', $attribute_set_id);
        }

        // $builder->where('p.status', 1);
        // $builder->orderBy('attr_set.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}