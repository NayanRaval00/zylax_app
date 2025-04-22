<?php

namespace App\Models;

use CodeIgniter\Model;

class Blogs extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'blogs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['category_id', 'title', 'description', 'image', 'slug', 'status'];
}