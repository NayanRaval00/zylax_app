<?php

namespace App\Models;

use CodeIgniter\Model;

class Tracking_logs extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tracking_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'tracking_id', 'status', 'created_at'];
}