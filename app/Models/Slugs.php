<?php

namespace App\Models;

use CodeIgniter\Model;

class Slugs extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'slugs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['type', 'ref_id', 'slug'];

    public function findOrCreate($data)
    {

        $exist_slug = $this->where('type', $data['type'])
                            ->where('ref_id', $data['ref_id'])
                            ->first();
                
        if(isset($exist_slug) && $exist_slug != ""){
            $create_or_update = $this->where('type', $data['type'])
                            ->where('ref_id', $data['ref_id'])
                            ->set(['slug' =>  $data['slug']])->update();
        }else{
            $create_or_update = $this->insert($data);
        }
        return $create_or_update;
    }

    public function deleteSlugByTypeAndRefId($type, $ref_id)
    {
        $this->where('type', $type)->where('ref_id', $ref_id)->delete();
    }

    
}