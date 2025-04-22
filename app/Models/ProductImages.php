<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImages extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'image'];
}