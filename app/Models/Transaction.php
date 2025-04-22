<?php

namespace App\Models;

use CodeIgniter\Model;

class Transaction extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['id', 'user_id', 'guest_id', 'tracking_order_id', 'billing_id', 'shipping_id', 'product_amount', 'shipping_charge', 'total_amount',  'shipping_method', 'email', 'payment_source', 'status', 'order_status', 'ip', 'created_on', 'payment_response'];
}