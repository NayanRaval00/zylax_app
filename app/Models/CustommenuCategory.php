<?php

namespace App\Models;

use CodeIgniter\Model;

class CustommenuCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'custommenu_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['custommenu_id', 'category_id'];

    function getCustomMenuCategoryData($custommenu_id)
    {
        $builder = $this->db->table('custommenu_category');
        $builder->select('*');
        $builder->where('custommenu_id', $custommenu_id);
        $query = $builder->get();
        return $query->getResultArray();
    }
       
}