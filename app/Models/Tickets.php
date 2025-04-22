<?php

namespace App\Models;

use CodeIgniter\Model;

class Tickets extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ticket_type_id', 'user_id', 'subject', 'email', 'description', 'status', 'last_updated'];
}