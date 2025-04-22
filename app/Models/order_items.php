<?php

namespace App\Models;

use CodeIgniter\Model;

class order_items extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'order_id', 'product_id', 'product_name', 'product_gst', 'price', 'quantity', 'image', 'created_at'];
}