<?php

namespace App\Models;

use CodeIgniter\Model;

class Shipping extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'shipping';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'status'];
}