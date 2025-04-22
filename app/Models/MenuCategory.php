<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'menu_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'menu_id', 'category_id'];
       
}