<?php

namespace App\Models;

use CodeIgniter\Model;

class ShippingCategoryPrice extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'shipping_category_prices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['shipping_id', 'category_id', 'price', 'ordermaxprice', 'orderminprice', 'priority'];

    function getShippingPricingListing($category_id)
    {
        $builder = $this->db->table('shipping_category_prices');
        $builder->select('shipping_category_prices.*, shipping.name as shipping_name, shipping.id as shipping_id, shipping.description as shipping_description');
        $builder->join('shipping', 'shipping_category_prices.shipping_id = shipping.id', 'left');
        $builder->join('shipping_category', 'shipping_category_prices.shipping_id = shipping_category.id', 'left');
        $builder->where('shipping_category.status', '1');
        $builder->where('shipping_category_prices.category_id', $category_id);
        $builder->orderBy('shipping_category_prices.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    // this function call cart and checkout page only to fetch all cat and non cat data
    function fetchShippingCharges($category_id)
    {
        $builder = $this->db->table('shipping_category_prices');
        $builder->select('shipping_category_prices.*, shipping.name as shipping_name, shipping.id as shipping_id, shipping.description as shipping_description');
        $builder->join('shipping', 'shipping_category_prices.shipping_id = shipping.id', 'left');
        $builder->join('shipping_category', 'shipping_category_prices.shipping_id = shipping_category.id', 'left');
        $builder->where('shipping_category.status', '1');
        
        // First, check if an exact match exists
        $exactMatch = clone $builder;
        $exactMatch->where('shipping_category_prices.category_id', $category_id);
        $exactMatchQuery = $exactMatch->get();
        
        if ($exactMatchQuery->getNumRows() > 0) {
            return $exactMatchQuery->getResultArray(); // Return exact match only
        }
    
        // If no exact match, fetch records with category_id = 0
        $builder->where('shipping_category_prices.category_id', 0);
        $query = $builder->get();
        
        return $query->getResultArray();
    }


    public function fetchPriorityShipping($priority)
    {
        $builder = $this->db->table('shipping_category_prices'); // plural table name
        $builder->select('shipping_category_prices.*, shipping.name as shipping_name');
        $builder->join('shipping', 'shipping.id = shipping_category_prices.shipping_id');
        $builder->where('shipping_category_prices.priority', $priority);
        
        return $builder->get()->getFirstRow('array'); // return as associative array
    }
    
    

}