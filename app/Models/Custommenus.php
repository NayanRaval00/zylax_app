<?php

namespace App\Models;

use CodeIgniter\Model;

class Custommenus extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'custommenus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['menu_id', 'title', 'link', 'link_url', 'sort', 'active'];

    function getCustomMenuDetail($custom_menu_id)
    {
        $builder = $this->db->table('custommenus');
        $builder->select('*');
        $builder->where('id', $custom_menu_id);
        $query = $builder->get();
        return $query->getRowArray();
    }
       
}