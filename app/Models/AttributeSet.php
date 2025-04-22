<?php

namespace App\Models;

use CodeIgniter\Model;

class AttributeSet extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'attribute_set';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'status'];

    function getAttributeSetWithCategory()
    {
        $builder = $this->db->table('attribute_set');
        $builder->select('attribute_set.id, attribute_set.name, c.name category_name');
        $builder->join('attribute_set_category as asc', 'attribute_set.id = asc.attribute_set_id', 'left');
        $builder->join('categories as c', 'asc.category_id = c.id', 'left');
        // $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');
        // $builder->groupBy(['attr_set_cat.id', 'attr_set_cat.category_id']);
        $builder->groupBy(['asc.attribute_set_id']);
        $builder->orderBy('attribute_set.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }
}