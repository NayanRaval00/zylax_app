<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'address_id';
    protected $allowedFields = [
        'user_id', 'email', 'phoneno', 'country_code', 'state_code', 'city_code', 'pincode', 'address', 'status_addr'
    ];
}