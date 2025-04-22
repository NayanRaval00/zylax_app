<?php

namespace App\Models;

use CodeIgniter\Model;

class AttributeValues extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'attribute_values';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['attribute_id', 'filterable', 'value', 'swatche_type', 'swatche_value', 'status'];

    public function getAttributeValuesListing()
    {
        return $this->select('attribute_values.*, attributes.name as attribute_name')
                    ->join('attributes', 'attributes.id = attribute_values.attribute_id')
                    ->orderBy('attribute_values.id', 'DESC')
                    ->findAll();
    }
}