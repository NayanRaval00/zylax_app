<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'address_id';
    protected $allowedFields = [
        'user_id', 'email', 'phoneno', 'company', 'address_1', 'city', 'pincode', 'address_2', 'status_addr', 'state'
    ];
}