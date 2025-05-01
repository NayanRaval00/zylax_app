<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['username', 'mobile', 'email', 'password'];

    public function getAdminDetails($filters = [])
    {
        $builder = $this->db->table('admins u')
            ->select('u.username, u.mobile, u.email, u.password, u.id');
    
        // Apply optional filters
        if (!empty($filters['order_daterange'])) {
            $dateRange = explode(' - ', $filters['order_daterange']);
            if (count($dateRange) === 2) {
                $builder->where("DATE(u.created_at) BETWEEN '" . trim($dateRange[0]) . "' AND '" . trim($dateRange[1]) . "'");
            }
        }
    
        if (!empty($filters['orderID'])) {
            $builder->groupStart();
                $builder->orWhere('u.email', $filters['orderID']);
                $builder->orWhere('u.username', $filters['orderID']);
                $builder->orWhere('u.id', $filters['orderID']);
                $builder->orWhere('u.mobile', $filters['orderID']);
            $builder->groupEnd();
        }
    
        $builder->orderBy('u.created_at', 'DESC');
    
        if (!empty($filters['limit'])) {
            $builder->limit((int) $filters['limit'], (int) ($filters['offset'] ?? 0));
        }
    
        return $builder->get()->getResultArray();
    }
}