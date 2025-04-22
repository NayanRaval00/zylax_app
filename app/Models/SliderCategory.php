<?php

namespace App\Models;

use CodeIgniter\Model;

class SliderCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'slider_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'slider_name'];
}