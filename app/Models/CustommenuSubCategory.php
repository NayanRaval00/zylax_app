<?php

namespace App\Models;

use CodeIgniter\Model;

class CustommenuSubCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'custommenus_sub_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['custommenus_sub_id', 'category_id'];
       
}