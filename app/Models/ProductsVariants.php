<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsVariants extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_variants';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['product_id', 'price', 'rrp','weight', 'height', 'breadth', 'length', 'sku', 'stock', 'status'];
}