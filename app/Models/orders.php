<?php

namespace App\Models;

use CodeIgniter\Model;

class orders extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'user_id', 'tracking_id',  'guest_id','discount_price','discount_type', 'billing_id', 'transaction_id', 'shipping_gst', 'total_gst', 'shipping_id', 'ship_cat_id', 'shipping_price', 'total_price', 'payment_status', 'unique_cart_token', 'created_at'];

    // public function getOrderDetails($tracking_id = null, $filters = [])
    // {
    //     // Set group_concat_max_len to avoid truncation
    //     $this->db->query("SET SESSION group_concat_max_len = 1000000");
    
    //     $builder = $this->db->table('orders o')
    //         ->select(
    //             'o.user_id, o.guest_id, o.shipping_gst, o.shipping_price, o.total_gst, o.total_price, o.payment_status, o.created_at, o.discount_price, o.discount_type,
    //              sa.name as shipping_name, sa.last_name as shipping_last_name, sa.address_1 as shipping_addr_1, sa.address_2 as shipping_addr_2, sa.state as shipping_state, sa.city as shipping_city, sa.pincode as shipping_pincode, sa.email as shipping_email, sa.phone_number as shipping_phone_number,
    //              ba.name as billing_name, ba.last_name as billing_last_name, ba.address_1 as billing_addr_1, ba.address_2 as billing_addr_2, ba.state as billing_state, ba.city as billing_city, ba.pincode as billing_pincode, ba.email as billing_email, ba.phone_number as billing_phone_number,
    //              t.tracking_order_id, t.total_amount as tran_total_amt, t.product_amount, t.shipping_method, t.payment_source, t.status as tran_status, t.order_status, t.ip, t.id as tran_id,
    //              CONCAT("[", GROUP_CONCAT(
    //                 CONCAT(
    //                     "{",
    //                         "\"product_id\":\"", oi.product_id, "\",",
    //                         "\"quantity\":\"", oi.quantity, "\",",
    //                         "\"price\":\"", oi.price, "\",",
    //                         "\"product_name\":\"", oi.product_name, "\",",
    //                         "\"product_gst\":\"", oi.product_gst, "\",",
    //                         "\"product_image\":\"", oi.image, "\",",
    //                         "\"addon_products\":", IFNULL(oi.addon_products, "null"),
    //                     "}"
    //                 )
    //              ), "]") AS products'
    //         )
    //         ->join('shipping_address sa', 'sa.id = o.shipping_id', 'left')
    //         ->join('billing_address ba', 'ba.id = o.billing_id', 'left')
    //         ->join('order_items oi', 'oi.order_id = o.id', 'left')
    //         ->join('transaction t', 't.id = o.transaction_id', 'left');
    
    //     // Apply filters
    //     if (!empty($tracking_id)) {
    //         $builder->where('t.tracking_order_id', $tracking_id);
    //     } else {
    //         $builder->groupBy('o.id');
    //     }
    
    //     if (!empty($filters['order_daterange'])) {
    //         $dateRange = explode(' - ', $filters['order_daterange']);
    //         if (count($dateRange) === 2) {
    //             $builder->where("DATE(o.created_at) BETWEEN '".trim($dateRange[0])."' AND '".trim($dateRange[1])."'");
    //         }
    //     }
    
    //     if (!empty($filters['order_status']) && $filters['order_status'] !== 'all') {
    //         $builder->where('t.order_status', $filters['order_status']);
    //     }
    
    //     if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
    //         $builder->where('o.payment_status', $filters['payment_status']);
    //     }
    
    //     if (!empty($filters['billingEmail'])) {
    //         $builder->where('ba.email', $filters['billingEmail']);
    //     }
    
    //     if (!empty($filters['orderID'])) {
    //         $builder->where('t.tracking_order_id', $filters['orderID']);
    //     }
    
    //     return $builder->get()->getResultArray();
    // }
    
    public function getOrderDetails($tracking_id = null, $filters = [])
    {
        // Set group_concat_max_len to avoid truncation
        $this->db->query("SET SESSION group_concat_max_len = 1000000");
    
        $builder = $this->db->table('orders o')
            ->select(
                'o.user_id, o.guest_id, o.shipping_gst, o.shipping_price, o.total_gst, o.total_price, o.payment_status, o.created_at, o.discount_price, o.discount_type,
                 sa.name as shipping_name, sa.last_name as shipping_last_name, sa.address_1 as shipping_addr_1, sa.address_2 as shipping_addr_2, sa.state as shipping_state, sa.city as shipping_city, sa.pincode as shipping_pincode, sa.email as shipping_email, sa.phone_number as shipping_phone_number,
                 ba.name as billing_name, ba.last_name as billing_last_name, ba.address_1 as billing_addr_1, ba.address_2 as billing_addr_2, ba.state as billing_state, ba.city as billing_city, ba.pincode as billing_pincode, ba.email as billing_email, ba.phone_number as billing_phone_number,
                 t.tracking_order_id, t.total_amount as tran_total_amt, t.product_amount, t.shipping_method, t.payment_source, t.status as tran_status, t.order_status, t.ip, t.id as tran_id, u.user_type, u.id as user_id, u.username as user_name, p.model, IF(ba.company_name != "", ba.company_name, u.company) AS company,
                 CONCAT("[", GROUP_CONCAT(
                    CONCAT(
                        "{",
                            "\"product_id\":\"", oi.product_id, "\",",
                            "\"quantity\":\"", oi.quantity, "\",",
                            "\"price\":\"", oi.price, "\",",
                            "\"product_name\":\"", oi.product_name, "\",",
                            "\"product_gst\":\"", oi.product_gst, "\",",
                            "\"product_image\":\"", oi.image, "\",",
                            "\"sku_id\":\"", p.model, "\",",
                            "\"addon_products\":", IFNULL(oi.addon_products, "null"),
                        "}"
                    )
                 ), "]") AS products'
            )
            ->join('shipping_address sa', 'sa.id = o.shipping_id', 'left')
            ->join('billing_address ba', 'ba.id = o.billing_id', 'left')
            ->join('order_items oi', 'oi.order_id = o.id', 'left')
            ->join('products p', 'p.id = oi.product_id', 'left')
            ->join('transaction t', 't.id = o.transaction_id', 'left')
            ->join('users u', 'u.id = o.user_id', 'left');
    
        // Apply filters
        if (!empty($tracking_id)) {
            $builder->where('t.tracking_order_id', $tracking_id);
        }

        if (!empty($filters['usertype'])) {
            $builder->where('u.user_type', $filters['usertype']);
        }
    
        if (!empty($filters['order_daterange'])) {
            $dateRange = explode(' - ', $filters['order_daterange']);
            if (count($dateRange) === 2) {
                $builder->where("DATE(o.created_at) BETWEEN '" . trim($dateRange[0]) . "' AND '" . trim($dateRange[1]) . "'");
            }
        }
    
        if (!empty($filters['order_status']) && $filters['order_status'] !== 'all') {
            $builder->where('t.order_status', $filters['order_status']);
        }
    
        if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
            $builder->where('o.payment_status', $filters['payment_status']);
        }
    
        if (!empty($filters['billingEmail'])) {
            $builder->where('ba.email', $filters['billingEmail']);
        }
    
        // if (!empty($filters['orderID'])) {
        //     $builder->orWhere('t.tracking_order_id', $filters['orderID']);
        //     $builder->orWhere('ba.email', $filters['orderID']);
        //     $builder->orWhere('u.username', $filters['orderID']);
        //     $builder->orWhere('u.email', $filters['orderID']);
        //     $builder->orWhere('u.fname', $filters['orderID']);
        //     $builder->orWhere('u.lname', $filters['orderID']);
        // }
        if (!empty($filters['orderID'])) {
            $builder->groupStart(); // group OR conditions
                $builder->orLike('t.tracking_order_id', $filters['orderID']);
                $builder->orLike('ba.email', $filters['orderID']);
                $builder->orLike('u.username', $filters['orderID']);
                $builder->orLike('u.email', $filters['orderID']);
                $builder->orLike('u.fname', $filters['orderID']);
                $builder->orLike('u.lname', $filters['orderID']);
                $builder->orLike('ba.phone_number', $filters['orderID']);
                $builder->orLike('sa.phone_number', $filters['orderID']);
                $builder->orLike('p.model', $filters['orderID']);
                $builder->orLike('u.company', $filters['orderID']);
                $builder->orWhere("MATCH(oi.product_name) AGAINST(" . $this->db->escape($filters['orderID']) . " IN BOOLEAN MODE)", null, false);
                $builder->orWhere('p.vpn', $filters['orderID']);
            $builder->groupEnd();
        }
        
    
        // Always group by order to avoid duplicated product JSON
        $builder->groupBy('o.id');
        $builder->orderBy('o.id', 'Desc');
    
        // Apply limit and offset if available
        if (!empty($filters['limit'])) {
            $builder->limit((int) $filters['limit'], (int) ($filters['offset'] ?? 0));
        }

    //    echo $builder->getCompiledSelect(); exit; // Outputs the raw SQL
    
        return $builder->get()->getResultArray();
    }
        

    public function getOrderUserDetails($user_id, $perPage = 50)
    {
        return $this->select(
            'orders.*, orders.discount_price, orders.discount_type,
            sa.name as shipping_name, sa.last_name as shipping_last_name, sa.address_1 as shipping_addr_1, 
            sa.address_2 as shipping_addr_2, sa.state as shipping_state, sa.city as shipping_city, 
            sa.pincode as shipping_pincode, sa.email as shipping_email, sa.phone_number as shipping_phone_number,
            ba.name as billing_name, ba.last_name as billing_last_name, ba.address_1 as billing_addr_1, 
            ba.address_2 as billing_addr_2, ba.state as billing_state, ba.city as billing_city, 
            ba.pincode as billing_pincode, ba.email as billing_email, ba.phone_number as billing_phone_number,
            t.tracking_order_id, t.total_amount as tran_total_amt, t.product_amount, 
            t.shipping_method, t.payment_source, t.status as tran_status, t.order_status, t.ip, t.id as tran_id'
        )
        ->join('shipping_address sa', 'sa.id = orders.shipping_id', 'left')
        ->join('billing_address ba', 'ba.id = orders.billing_id', 'left')
        ->join('transaction t', 't.id = orders.transaction_id', 'left')
        ->where('orders.user_id', $user_id)
        ->orderBy('orders.created_at', 'DESC') // Order by latest orders
        ->paginate($perPage); // Enable pagination
    }

    public function getOrderBasicDetailsAndTracking($filters = [])
    {
        // print_r($filters);
        $builder = $this->db->table('orders o')
            ->select(
                'o.user_id, o.guest_id, o.shipping_gst, o.shipping_price, o.total_gst, o.total_price, o.payment_status, o.created_at as order_date, o.id as order_id, o.discount_price, o.discount_type,
                 sa.name as shipping_name, sa.last_name as shipping_last_name, sa.address_1 as shipping_addr_1, sa.address_2 as shipping_addr_2, sa.state as shipping_state, sa.city as shipping_city, sa.pincode as shipping_pincode, sa.email as shipping_email, sa.phone_number as shipping_phone_number,
                 ba.name as billing_name, ba.last_name as billing_last_name, ba.address_1 as billing_addr_1, ba.address_2 as billing_addr_2, ba.state as billing_state, ba.city as billing_city, ba.pincode as billing_pincode, ba.email as billing_email, ba.phone_number as billing_phone_number,
                 t.tracking_order_id, t.total_amount as tran_total_amt, t.product_amount, t.shipping_method, t.payment_source, t.order_status, t.ip, t.id as tran_id,
                 tl.status as tracking_status, tl.created_at as tracking_date'
            )
            ->join('shipping_address sa', 'sa.id = o.shipping_id', 'left')
            ->join('billing_address ba', 'ba.id = o.billing_id', 'left')
            ->join('transaction t', 't.id = o.transaction_id', 'left')
            ->join('tracking_logs tl', 'tl.tracking_id = t.tracking_order_id', 'left')
            ->where('tl.status IS NOT NULL');
    
        if (!empty($filters)) {
            $builder->where('t.tracking_order_id', $filters);
        }
    
        $builder->orderBy('tracking_date', 'ASC');
    
        return $builder->get()->getResultArray();
    }
    
    public function getOrderProducts($orderID)
    {
        $builder = $this->db->table('order_items oi')
            ->select('oi.order_id, oi.product_id, oi.quantity, oi.price, oi.product_name, oi.product_gst, oi.image AS product_image')
            ->where('oi.order_id', $orderID);
    
        return $builder->get()->getResultArray();
    }
    
    
    

    
    


           
    
}