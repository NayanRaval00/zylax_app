<?php

namespace App\Models;

use CodeIgniter\Model;

class Tbl_nab_returndata extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tbl_nab_returndata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['id', 'returned_data'];
}