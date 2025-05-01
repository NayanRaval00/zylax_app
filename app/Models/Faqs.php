<?php

namespace App\Models;

use CodeIgniter\Model;

class Faqs extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'faqs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['question', 'answer', 'active'];

}