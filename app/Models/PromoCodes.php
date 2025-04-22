<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCodes extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'promo_codes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['promo_code', 'message', 'start_date', 'end_date', 'no_of_users', 'minimum_order_amount', 'discount', 'discount_type', 'max_discount_amount', 'repeat_usage', 'no_of_repeat_usage', 'image', 'status', 'is_cashback', 'list_promocode', 'date_created'];


    public function validatePromoCode($code)
    {
        return $this->where('promo_code', $code)
                    ->where('status', 1)
                    ->where('start_date <=', date('Y-m-d'))
                    ->where('end_date >=', date('Y-m-d'))
                    ->first();
    }

}