<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadImages extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'upload_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['image', 'created_at'];


}