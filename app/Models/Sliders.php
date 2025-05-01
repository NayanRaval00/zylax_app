<?php

namespace App\Models;

use CodeIgniter\Model;

class Sliders extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sliders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['type', 'type_id', 'link', 'image', 'image_alt'];
}