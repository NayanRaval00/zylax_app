<?php

namespace App\Models;

use CodeIgniter\Model;

class CustommenusSub extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'custommenus_sub';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['parent_id', 'custommenu_id', 'title', 'link', 'link_url', 'active'];
      
    
    function getCustomSubMenuDetail($custom_menu_sub_id)
    {
        $builder = $this->db->table('custommenus_sub');
        $builder->select('*');
        $builder->where('id', $custom_menu_sub_id);
        $query = $builder->get();   
        return $query->getRowArray();
    }

}