<?php

namespace App\Models;

use CodeIgniter\Model;

class Product_Cart extends Model
{
    protected $table = 'product_cart';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'guest_userid',
        'product_id',
        'product_name',
        'product_price',
        'cat_id',
        'quantity',
        'configuration',
        'created_at',
        'updated_at',
        'configuration_hash',
        'product_image',
        'cart_json',
        'product_unit_price'
    ];

    public function checkExistingCartItem($userId, $sessionId, $productId, $configurationHash)
    {
        $builder = $this->builder();

        if ($userId) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('guest_userid', $sessionId);
        }

        $builder->where('product_id', $productId);
        $builder->where('configuration_hash', $configurationHash);
        //echo $builder->getCompiledSelect(); exit; // Outputs the raw SQL

        return $builder->get()->getRowArray();
    }

    public function getCartItemsByGuestId($guestId)
    {
        return $this->where('guest_userid', $guestId)->findAll();
    }

    public function deleteCartItem($cartItemId)
    {
        return $this->db->table('product_cart')
                        ->where('id', $cartItemId)
                        ->delete();
    }

}
