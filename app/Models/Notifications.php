<?php

namespace App\Models;

use CodeIgniter\Model;

class Notifications extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'message', 'type', 'type_id', 'send_to', 'users_id', 'image', 'link'];
}