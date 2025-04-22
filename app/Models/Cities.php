<?php

namespace App\Models;

use CodeIgniter\Model;

class Cities extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'cities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'minimum_free_delivery_order_amount', 'delivery_charges'];
}