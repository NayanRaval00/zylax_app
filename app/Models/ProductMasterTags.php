<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductMasterTags extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_master_tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description', 'seo_page_title', 'seo_meta_keywords', 'seo_meta_description', 'seo_og_image', 'status'];
}