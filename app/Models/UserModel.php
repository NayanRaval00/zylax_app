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
        'type', 'driving_license', 'status', 'web_fcm', 'created_at'
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
}
