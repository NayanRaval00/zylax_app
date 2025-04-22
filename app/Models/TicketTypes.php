<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketTypes extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ticket_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'date_created'];
}