<?php

namespace App\Models;

use CodeIgniter\Model;

class Countries extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'countries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'iso3', 'numeric_code', 'iso2', 'phonecode'];
}