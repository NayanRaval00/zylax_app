<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogCategories extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'blog_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'image', 'status'];
}