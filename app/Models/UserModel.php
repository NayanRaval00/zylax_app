<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'ip_address', 'username', 'password', 'email','fname','lname', 'mobile', 'image', 'balance',
        'activation_selector', 'activation_code', 'forgotten_password_selector', 'forgotten_password_code',
        'forgotten_password_time', 'remember_selector', 'remember_code', 'created_on', 'last_login',
        'last_online', 'active', 'company', 'address', 'bonus_type', 'bonus', 'cash_received', 'dob',
        'country_code','state', 'city', 'area', 'street', 'pincode', 'serviceable_zipcodes', 'serviceable_cities',
        'apikey', 'referral_code', 'friends_code', 'fcm_id', 'platform_type', 'latitude', 'longitude',
        'type', 'driving_license', 'status', 'web_fcm', 'created_at', 'user_type'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    /**
     * Get user by email or username
     */
    public function getUserByEmailOrUsername($emailOrUsername)
    {
        return $this->where('email', $emailOrUsername)
                    ->orWhere('username', $emailOrUsername)
                    ->first();
    }

    /**
     * Insert a new user
     */
    public function createUser($data)
    {
        return $this->insert($data);
    }

    /**
     * Update user password
     */
    public function updatePassword($email, $newPassword)
    {
        return $this->where('email', $email)
                    ->set(['password' => password_hash($newPassword, PASSWORD_BCRYPT)])
                    ->update();
    }

    public function getUserByUserid($userID)
    {
        return $this->where('id', $userID)
                    ->first();
    }

    public function getUserDetails($filters = [])
    {
        $builder = $this->db->table('users u')
            ->select('
                u.id, u.username, u.email, u.fname, u.lname, u.mobile, u.image, u.balance,
                u.created_on, u.last_login, u.last_online, u.active, u.company, u.address,
                u.bonus_type, u.bonus, u.cash_received, u.dob, u.country_code, u.state,
                u.city, u.area, u.street, u.pincode, u.serviceable_zipcodes, u.serviceable_cities,
                u.apikey, u.referral_code, u.friends_code, u.fcm_id, u.platform_type,
                u.latitude, u.longitude, u.type, u.driving_license, u.status, u.web_fcm,
                u.created_at, u.user_type, u.mobile,
            ');
    
        // Apply filters

        if (!empty($filters['order_daterange'])) {
            $dateRange = explode(' - ', $filters['order_daterange']);
            if (count($dateRange) === 2) {
                $builder->where("DATE(u.created_at) BETWEEN '" . trim($dateRange[0]) . "' AND '" . trim($dateRange[1]) . "'");
            }
        }

        if (!empty($filters['usertype'])) {
            $builder->where('u.user_type', $filters['usertype']);
        }

        if (!empty($filters['orderID'])) {
            $builder->groupStart(); // ( ... )
                $builder->orLike('u.email', $filters['orderID']);
                $builder->orLike('u.username', $filters['orderID']);
                $builder->orLike('u.id', $filters['orderID']);
                $builder->orLike('u.fname', $filters['orderID']);
                $builder->orLike('u.lname', $filters['orderID']);
                $builder->orLike('u.company', $filters['orderID']);
                $builder->orLike('u.mobile', $filters['orderID']);
                $builder->orLike('u.user_type', $filters['orderID']);
            $builder->groupEnd(); // end of OR group
        
            if ($filters['userstatus'] !== '') {
                $builder->where('u.active', $filters['userstatus']); // AND u.active = ?
            }

            // if ($filters['usertype'] !== '') {
            //     $builder->where('u.user_type', $filters['usertype']);
            // }
        } else {
            if ($filters['userstatus'] !== '') {
                $builder->where('u.active', $filters['userstatus']);
            }

            // if ($filters['usertype'] !== '') {
            //     $builder->where('u.user_type', $filters['usertype']);
            // }
        }
    
        $builder->orderBy('u.created_at', 'DESC');

        // Limit and offset (optional)
        if (!empty($filters['limit'])) {
            $builder->limit((int) $filters['limit'], (int) ($filters['offset'] ?? 0));
        }
    
        // echo $builder->getCompiledSelect();
        // die;
        return $builder->get()->getResultArray();
    }
    
}
