<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomNotifications extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'custom_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'message', 'type'];
}