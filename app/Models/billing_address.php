<?php

namespace App\Models;

use CodeIgniter\Model;

class billing_address extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'billing_address';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'name', 'last_name', 'company_name', 'address_1', 'address_2', 'state', 'city', 'pincode', 'email', 'phone_number', 'created_at'];
}