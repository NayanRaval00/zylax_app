<?php

namespace App\Models;

use CodeIgniter\Model;

class Menus extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'title', 'description', 'icon', 'link', 'image_top', 'image_right', 'sort', 'type', 'featured_title', 'active'];
       
}