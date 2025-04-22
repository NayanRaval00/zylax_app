<?php

namespace App\Models;

use CodeIgniter\Model;

class AttributeSetCategory extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'attribute_set_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['attribute_set_id', 'category_id'];

    function getProductAttributesSets($category_id)
    {
        $builder = $this->db->table('attribute_set_category');
        $builder->select('attribute_set.*');
        $builder->join('attribute_set', 'attribute_set_category.attribute_set_id = attribute_set.id');
        $builder->where('attribute_set_category.category_id', $category_id);
        // $builder->orderBy('pcv.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductAttributesSetValues($attribute_set_id)
    {
        $builder = $this->db->table('attributes');
        $builder->select('attributes.id as attribute_id, attributes.name as attribute_name, attributes.slug as attribute_slug');
        // $builder->join('attribute_values', 'attributes.id = attribute_values.attribute_id', 'left');
        // $builder->join('attribute_set_category', 'attributes.attribute_set_id = attribute_set_category.attribute_set_id', 'left');
        // $builder->join('products', 'products.category_id = attribute_set_category.category_id', 'left');
        $builder->where('attributes.attribute_set_id', $attribute_set_id);
        $builder->orderBy('attributes.name', 'ASC');
        // $builder->orderBy('pcv.id', 'ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery(); exit;
        return $query->getResultArray();

    }

    function getAttributeNameProductCounts($attribute_category_id, $attribute_set_id, $attribute_slug, $brands = [])
    {
        $sql = "SELECT COUNT(DISTINCT p_a.product_id) AS countRes FROM product_attributes AS p_a JOIN products AS pc ON p_a.product_id = pc.id WHERE (pc.category_id = '$attribute_category_id' OR pc.category_id IN (SELECT id FROM categories WHERE parent_id = '$attribute_category_id')) AND pc.status = '1' AND p_a.attribute_id = '$attribute_set_id' AND p_a.attribute_value_id IN (SELECT id FROM attributes WHERE slug = '$attribute_slug')";

        if(!empty($brands)){
            // print_r($brands); exit;
            $brands_list = implode(",", $brands);
           $sql .= "AND pc.brand IN ($brands_list)";
        }
        // echo $sql; exit;
        // if($attribute_set_id == '42') echo $sql."<br>";
        return $this->db->query($sql)->getRow();
    }

    // function getAttributeNameProductCountsParentAttributes($attribute_category_id, $attribute_set_id, $attribute_slug, $brands = [], $attributes = [])
    // {
    //     // print_r($attributes); exit;

    //     $filter_attribute_set_ids = array_column($attributes, 'attribute_set_id');

    //      if (in_array($attribute_set_id, $filter_attribute_set_ids)) {

    //         $sql_get2 = "SELECT COUNT(*) AS countRes FROM ( 
    //             SELECT pc.id 
    //             FROM product_attributes AS p_a 
    //             JOIN products AS pc ON p_a.product_id = pc.id 
    //             WHERE (pc.category_id = '$attribute_category_id' 
    //             OR pc.category_id IN ( SELECT id FROM categories WHERE parent_id = '$attribute_category_id' )) 
    //             AND pc.status = '1' 
    //             AND ( (p_a.attribute_id = '$attribute_set_id' 
    //             AND p_a.attribute_value_id IN ( SELECT id FROM attributes WHERE slug = '$attribute_slug' )) ";
            
    //         $innerCond = '';
            
    //         foreach ($attributes as $attribute) {
    //             $selectedAttrNames = isset($attribute['filter_value']) ? explode(' ', $attribute['filter_value']) : [];
    //             $attribute_set_id = $attribute['attribute_set_id'];
    //             $attr_names = implode("','", $selectedAttrNames);
            
    //             $sql_get = "SELECT attributes.id FROM attributes WHERE slug IN('$attr_names') AND attribute_set_id='$attribute_set_id'";
    //             $results = $this->db->query($sql_get)->getResultArray();
            
    //             $names = array_column($results, 'id');
    //             $attr_names_ids = implode("','", $names);
                
    //             if (!empty($names)) {
    //                 if ($innerCond != "") {
    //                     $innerCond .= " OR ";
    //                 } else {
    //                     $innerCond .= " OR ";
    //                 }
    //                 $innerCond .= " (p_a.attribute_id = '$attribute_set_id' AND p_a.attribute_value_id IN ('$attr_names_ids') ";
    //             }
    //         }
            
    //         if ($innerCond != "") {
    //             $innerCond .= " ) "; // Close the bracket for OR conditions
    //             $sql_get2 .= " ) GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = 1 ) AS filtered_products ";
    //         } else {
    //             $sql_get2 .= " ) AS filtered_products ";
    //         }

    //         // echo $sql_get2; exit;


    //      }else{
            


    //         $sql_get2 = "SELECT COUNT(*) AS countRes FROM ( 
    //             SELECT pc.id 
    //             FROM product_attributes AS p_a 
    //             JOIN products AS pc ON p_a.product_id = pc.id 
    //             WHERE (pc.category_id = '$attribute_category_id' 
    //             OR pc.category_id IN ( SELECT id FROM categories WHERE parent_id = '$attribute_category_id' )) 
    //             AND pc.status = '1' 
    //             AND ( (p_a.attribute_id = '$attribute_set_id' 
    //             AND p_a.attribute_value_id IN ( SELECT id FROM attributes WHERE slug = '$attribute_slug' )) ";
            
    //         $innerCond = '';
            
    //         foreach ($attributes as $attribute) {
    //             $selectedAttrNames = isset($attribute['filter_value']) ? explode(' ', $attribute['filter_value']) : [];
    //             $attribute_set_id = $attribute['attribute_set_id'];
    //             $attr_names = implode("','", $selectedAttrNames);
            
    //             $sql_get = "SELECT attributes.id FROM attributes WHERE slug IN('$attr_names') AND attribute_set_id='$attribute_set_id'";
    //             $results = $this->db->query($sql_get)->getResultArray();
            
    //             $names = array_column($results, 'id');
    //             $attr_names_ids = implode("','", $names);
                
    //             if (!empty($names)) {
    //                 if ($innerCond != "") {
    //                     $innerCond .= " OR ";
    //                 } else {
    //                     $innerCond .= " OR ";
    //                 }
    //                 $innerCond .= " (p_a.attribute_id = '$attribute_set_id' AND p_a.attribute_value_id IN ('$attr_names_ids') ";
    //             }
    //         }
            
    //         if ($innerCond != "") {
    //             $innerCond .= " ) "; // Close the bracket for OR conditions
    //             $sql_get2 .= " $innerCond ) GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = 2 ) AS filtered_products ";
    //         } else {
    //             $sql_get2 .= " ) AS filtered_products ";
    //         }

    //     }
        
    //     // echo $sql_get2."<br>";
    //     // exit;
        

    //     // $sql = "SELECT COUNT(*) AS countRes FROM ( SELECT pc.id FROM product_attributes AS p_a JOIN products AS pc ON p_a.product_id = pc.id WHERE (pc.category_id = '$attribute_category_id' OR pc.category_id IN ( SELECT id FROM categories WHERE parent_id = '$attribute_category_id' )) AND pc.status = '1' AND ( (p_a.attribute_id = '$attribute_set_id' AND p_a.attribute_value_id IN ( SELECT id FROM attributes WHERE slug = '$attribute_slug' )) OR (p_a.attribute_id = '94' AND p_a.attribute_value_id IN ( SELECT id FROM attributes WHERE slug = 'rgb' )) ) GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = 2 ) AS filtered_products";

    //     // if(!empty($brands)){
    //     //     // print_r($brands); exit;
    //     //     $brands_list = implode(",", $brands);
    //     //    $sql .= "AND pc.brand IN ($brands_list)";
    //     // }
    //     // echo $sql; exit;
    //     // if($attribute_set_id == '42') echo $sql."<br>";
    //     // echo $this->db->getLastQuery(). "<br>";
    //     return $this->db->query($sql_get2)->getRow();
    // }

    function getAttributeNameProductCountsParentAttributes($attribute_category_id, $attribute_set_id, $attribute_slug, $brands = [], $attributes = [])
{
    $filter_attribute_set_ids = array_column($attributes, 'attribute_set_id');

    // Start SQL
    $sql = "SELECT COUNT(*) AS countRes FROM ( 
        SELECT pc.id 
        FROM product_attributes AS p_a 
        JOIN products AS pc ON p_a.product_id = pc.id 
        WHERE (pc.category_id = '$attribute_category_id' 
        OR pc.category_id IN (SELECT id FROM categories WHERE parent_id = '$attribute_category_id')) 
        AND pc.status = '1' ";

    // Brand filter (if provided)
    if (!empty($brands)) {
        $brands_list = implode(",", array_map('intval', $brands)); // ensure safe integers
        $sql .= " AND pc.brand IN ($brands_list) ";
    }

    // Start attributes filter
    $sql .= " AND ( (p_a.attribute_id = '$attribute_set_id' 
              AND p_a.attribute_value_id IN (SELECT id FROM attributes WHERE slug = '$attribute_slug'))";

    $innerCond = '';
    $attributeCount = 1; // Start from 1 for the main attribute

    foreach ($attributes as $attribute) {
        $attr_set_id = $attribute['attribute_set_id'];

        // Don't count main attribute again if already selected
        if ($attr_set_id == $attribute_set_id) {
            continue;
        }

        $selectedAttrNames = isset($attribute['filter_value']) ? explode(' ', trim($attribute['filter_value'])) : [];

        if (empty($selectedAttrNames)) continue;

        $attr_names = implode("','", array_map('addslashes', $selectedAttrNames));

        $sql_get = "SELECT id FROM attributes WHERE slug IN ('$attr_names') AND attribute_set_id = '$attr_set_id'";
        $results = $this->db->query($sql_get)->getResultArray();
        $attr_ids = array_column($results, 'id');

        if (!empty($attr_ids)) {
            $attributeCount++;
            $attr_ids_str = implode("','", $attr_ids);
            $innerCond .= " OR (p_a.attribute_id = '$attr_set_id' AND p_a.attribute_value_id IN ('$attr_ids_str'))";
        }
    }

    // Append inner conditions and close the WHERE clause
    if (!empty($innerCond)) {
        $sql .= $innerCond;
    }

    $sql .= ") GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = $attributeCount ) AS filtered_products";

    // Uncomment to debug SQL
    // echo $sql; exit;

    return $this->db->query($sql)->getRow();
}


    // function getProductAttributesSetValues($attribute_set_id)
    // {
    //     $builder = $this->db->table('attributes');
    //     $builder->select('attributes.id as attribute_id, attributes.name as attribute_name, COUNT(products.id) AS product_count');
    //     // $builder->join('attribute_values', 'attributes.id = attribute_values.attribute_id', 'left');
    //     $builder->join('attribute_set_category', 'attributes.attribute_set_id = attribute_set_category.attribute_set_id', 'left');
    //     $builder->join('products', 'products.category_id = attribute_set_category.category_id', 'left');
    //     $builder->where('attributes.attribute_set_id', $attribute_set_id);
    //     // $builder->orderBy('pcv.id', 'ASC');
    //     $query = $builder->get();
    //     // echo $this->db->getLastQuery(); exit;
    //     return $query->getResultArray();

    // }

    function getProductAttributeSetWithCategory()
    {
        $builder = $this->db->table('attribute_set_category attr_set_cat');
        $builder->select('attr_set.id, attr_set.name, attr_set.slug, COUNT(p.id) AS product_count');
        $builder->join('products as p', 'attr_set_cat.category_id = p.category_id');
        $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');
        $builder->groupBy(['attr_set_cat.id', 'attr_set_cat.category_id']);
        $builder->orderBy('attr_set_cat.id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getProductAttributeSetWithCategoryMultiple($category = [], $brands = [])
    {
        $builder = $this->db->table('attribute_set_category attr_set_cat');
        $builder->select('attr_set.id, attr_set.name, attr_set.slug, p.category_id');
        $builder->join('products as p', 'attr_set_cat.category_id = p.category_id');
        $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');

        if (!empty($category) && $category != "") {
            $builder->whereIn('p.category_id', $category);
        }

        if (!empty($brands) && $brands != "") {
            $builder->whereIn('p.brand', $brands);
        }

        $builder->where('p.status', 1);

        $builder->groupBy(['attr_set_cat.id', 'attr_set_cat.category_id']);
        // $builder->orderBy('attr_set_cat.id', 'ASC');
        $builder->orderBy('attr_set.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    function getCategoryAttributeSet($category = null)
    {
        $builder = $this->db->table('attribute_set_category asc');
        $builder->select('attr_set.id, attr_set.name');
        $builder->join('attribute_set as attr_set', 'asc.attribute_set_id = attr_set.id');

        if (!empty($category) && $category != "") {
            $builder->where('asc.category_id', $category);
        }

        // $builder->where('p.status', 1);
        // $builder->orderBy('attr_set.name', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

}