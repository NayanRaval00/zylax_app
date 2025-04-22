<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductMasterFeatures extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_master_features';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['icon', 'text'];

}