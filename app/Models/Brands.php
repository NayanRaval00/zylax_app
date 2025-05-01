<?php

namespace App\Models;

use CodeIgniter\Model;

class Brands extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'brands';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'image', 'icon', 'is_show', 'description', 'status'];

    function getBrandsProducts()
    {
        $builder = $this->db->table('brands b');
        $builder->select('b.id, b.name AS brand_name, b.slug AS brand_slug, b.image AS brand_image, COUNT(p.id) AS product_count');
        $builder->join('products p', 'b.id = p.brand', 'left');
        $builder->where('p.status', 1);
        $builder->groupBy(['b.id', 'b.name']);
        $builder->orderBy('b.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getBrandProductsFiltersListing($brand_id)
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id as product_id, p.name as product_name, p.slug as product_slug, p.image as product_image, pv.price as pv_price, pv.rrp as pv_rrp, pv.status as pv_status');
        $builder->join('product_variants as pv', 'pv.product_id = p.id', 'left');
        $builder->where('p.brand', $brand_id);
        $builder->orderBy('p.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductCountWithBrands($category = ''){
        $builder = $this->db->table('brands as b');
        $builder->select('b.id AS brand_id, b.name AS brand_name, b.slug AS brand_slug, COUNT(p.id) AS product_count');
        $builder->join('products as p', 'b.id = p.brand');

        if (!empty($category) && $category != "") {
            $builder->where('p.category_id', $category);
        }

        $builder->where('b.status', 1);
        $builder->where('p.status', 1);

        $builder->groupBy(['b.id', 'b.name']);
        // $builder->orderBy('b.id', 'ASC');
        $builder->orderBy('b.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductCountWithBrandWithAttributes($category = '', $attributes = []){
        // print_r($attributes); 
        
        $sql_get2 = "SELECT b.id as brand_id, b.name AS brand_name, b.slug AS brand_slug, count(p.id) as product_count from products p left join brands b on b.id = p.brand";

        $index = 0;

       
        $innerCond = '';
        foreach ($attributes as $attribute) {
            // print_r($attribute['filter_value']);
            $selectedAttrNames = isset($attribute['filter_value']) ? explode(' ', $attribute['filter_value']) : [];
            // print_r($selectedAttrNames);
            $attribute_set_id = $attribute['attribute_set_id'];
            $attr_names = implode("','", $selectedAttrNames);
            // print_r($attr_names);
            $sql_get = "SELECT attributes.id FROM attributes WHERE slug IN('$attr_names') AND attribute_set_id='$attribute_set_id'";
            // echo $sql_get;
            $results = $this->db->query($sql_get)->getResultArray();
            // print_r($results);

            $names = array_column($results, 'id'); // Extracts only the "name" column

            $attr_names_ids = implode("','", $names);
            
            if($innerCond != ""){
                $innerCond .= '  OR  ';
            }
            if($innerCond == ''){$innerCond .= '(';}
            $innerCond .= " (`attribute_value_id` in ('$attr_names_ids') and attribute_id = '$attribute_set_id' )" ;


            //print_r($attr_names_values);

        }

        if($innerCond != ""){
            $sql_get2 .= " where p.id in (SELECT pa.product_id  FROM `product_attributes` pa WHERE". $innerCond. " ) ";
        }

        // echo $innerCond;exit;
        
        $sql_get2 .= " and p.category_id = '$category' and p.status = '1' ) group by b.name order by b.name asc";
        
        // echo $sql_get2;
        // exit;

         // print_r($attr_names);

         
        //  $sql_get2 = "SELECT b.id as brand_id, b.name AS brand_name, b.slug AS brand_slug, count(p.id) as product_count from products p left join brands b on b.id = p.brand where p.id in (SELECT pa.product_id  FROM `product_attributes` pa WHERE `attribute_value_id` in (826) and attribute_id = 93) and p.category_id = 26 group by b.name order by b.name asc";
         // echo $sql_get;
         $results = $this->db->query($sql_get2)->getResultArray();
         return $results;

    }

    function getProductCountWithBrandWithAttributesMultiple($category_ids = [], $attributes = [])
    {
        $sql_get2 = "SELECT b.id as brand_id, b.name AS brand_name, b.slug AS brand_slug, count(p.id) as product_count 
                    FROM products p 
                    LEFT JOIN brands b ON b.id = p.brand";

        $innerCond = '';
        foreach ($attributes as $attribute) {
            $selectedAttrNames = isset($attribute['filter_value']) ? explode(' ', trim($attribute['filter_value'])) : [];
            $attribute_set_id = $attribute['attribute_set_id'];

            if (empty($selectedAttrNames)) continue;

            $attr_slugs = implode("','", array_map('addslashes', $selectedAttrNames));

            $sql_get = "SELECT id FROM attributes WHERE slug IN ('$attr_slugs') AND attribute_set_id = '$attribute_set_id'";
            $results = $this->db->query($sql_get)->getResultArray();
            $attr_ids = array_column($results, 'id');

            if (!empty($attr_ids)) {
                $attr_ids_str = implode("','", $attr_ids);
                if ($innerCond != "") {
                    $innerCond .= ' OR ';
                }
                if ($innerCond == '') {
                    $innerCond .= '(';
                }
                $innerCond .= "(`attribute_value_id` IN ('$attr_ids_str') AND attribute_id = '$attribute_set_id')";
            }
        }

        if (!empty($innerCond)) {
            $innerCond .= ')';
            $sql_get2 .= " WHERE p.id IN (SELECT pa.product_id FROM product_attributes pa WHERE $innerCond)";
        } else {
            $sql_get2 .= " WHERE 1=1"; // ensure WHERE clause exists
        }

        // Apply multiple category filter
        if (!empty($category_ids)) {
            $category_ids_str = implode(",", array_map('intval', $category_ids));
            $sql_get2 .= " AND p.category_id IN ($category_ids_str)";
        }

        $sql_get2 .= " AND p.status = '1' GROUP BY b.name ORDER BY b.name ASC";

        return $this->db->query($sql_get2)->getResultArray();
    }


    function getProductCountWithBrandsMultipleCategory($category = []){
        $builder = $this->db->table('brands as b');
        $builder->select('b.id AS brand_id, b.name AS brand_name, b.slug AS brand_slug, COUNT(p.id) AS product_count');
        $builder->join('products as p', 'b.id = p.brand');

        if (!empty($category) && $category != "") {
            $builder->whereIn('p.category_id', $category);
        }

        $builder->where('b.status', 1);
        $builder->where('p.status', 1);

        $builder->groupBy(['b.id', 'b.name']);
        // $builder->orderBy('b.id', 'ASC');
        $builder->orderBy('b.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}