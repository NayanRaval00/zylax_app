<?php

namespace App\Models;

use CodeIgniter\Model;

class Pages extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'menu_name', 'slug', 'image', 'description', 'meta_title', 'meta_description', 'meta_keyword', 'active', 'place_to', 'page_type', 'page_script', 'sort'];


}