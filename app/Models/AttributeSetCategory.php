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


    function getAttributeNameProductCountsWithMultipleCategory($attribute_category_ids, $attribute_set_id, $attribute_slug, $brands = [])
    {
        if (empty($attribute_category_ids)) {
            return 0; // or handle gracefully
        }

        // Convert category IDs array to comma-separated string
        $category_ids_str = implode(',', array_map('intval', $attribute_category_ids));

        // SQL query start
        $sql = "SELECT COUNT(DISTINCT p_a.product_id) AS countRes 
                FROM product_attributes AS p_a 
                JOIN products AS pc ON p_a.product_id = pc.id 
                WHERE (pc.category_id IN ($category_ids_str) 
                    OR pc.category_id IN (
                        SELECT id FROM categories WHERE parent_id IN ($category_ids_str)
                    )) 
                AND pc.status = '1' 
                AND p_a.attribute_id = '$attribute_set_id' 
                AND p_a.attribute_value_id IN (
                    SELECT id FROM attributes WHERE slug = '$attribute_slug'
                )";

        // Apply brand filter if given
        if (!empty($brands)) {
            $brands_list = implode(',', array_map('intval', $brands));
            $sql .= " AND pc.brand IN ($brands_list)";
        }

        // echo $sql. '<br>';

        return $this->db->query($sql)->getRow();
    }

    function getAttributeNameProductCountsWithMultipleCategoryGlobalSearch(
        array $attribute_category_ids,
        $attribute_set_id,
        $attribute_slug,
        array $brands = [],
        string $keyword = ''
    ) {
        if (empty($attribute_category_ids)) {
            return 0;
        }
    
        $category_ids_str = implode(',', array_map('intval', $attribute_category_ids));
    
        // Prepare full-text keyword match clause
        $matchScoreClause = '';
        $havingClause = '';
        if (!empty($keyword)) {
            $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
            $words = explode(' ', $keyword);
            $searchStringFull = '+' . implode(' +', $words);
    
            $matchScoreClause = ",
                (
                    (MATCH(pc.name) AGAINST('$searchStringFull' IN BOOLEAN MODE) * 50) +
                    (MATCH(pc.short_description, pc.model, pc.vpn) AGAINST('$searchStringFull' IN BOOLEAN MODE) * 20) +
                    " . implode(' + ', array_map(function ($word) {
                        $plainWord = str_replace('+', '', $word);
                        return "(CASE WHEN LOCATE('$plainWord', pc.name) > 0 THEN (1000 - LOCATE('$plainWord', pc.name)) ELSE 0 END)";
                    }, $words)) . "
                ) AS match_score";
    
            $havingClause = " HAVING match_score > 0";
        }
    
        // Build SQL
        $sql = "SELECT COUNT(*) AS countRes FROM (
            SELECT DISTINCT p_a.product_id $matchScoreClause
            FROM product_attributes AS p_a
            JOIN products AS pc ON p_a.product_id = pc.id
            WHERE (pc.category_id IN ($category_ids_str)
                OR pc.category_id IN (
                    SELECT id FROM categories WHERE parent_id IN ($category_ids_str)
                ))
            AND pc.status = '1'
            AND p_a.attribute_id = '$attribute_set_id'
            AND p_a.attribute_value_id IN (
                SELECT id FROM attributes WHERE slug = '$attribute_slug'
            )";
    
        // Add brand filter
        if (!empty($brands)) {
            $brands_list = implode(',', array_map('intval', $brands));
            $sql .= " AND pc.brand IN ($brands_list)";
        }
    
        $sql .= " GROUP BY p_a.product_id";
    
        if (!empty($havingClause)) {
            $sql .= $havingClause;
        }
    
        $sql .= ") AS filtered_products";
    
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

    function getAttributeNameProductCountsParentAttributesMultipleCategory($attribute_category_ids = [], $attribute_set_id, $attribute_slug, $brands = [], $attributes = [])
    {
        if (empty($attribute_category_ids)) {
            return 0; // Or handle as needed
        }
    
        // Convert category array to comma-separated list
        $category_ids_str = implode(',', array_map('intval', $attribute_category_ids));
    
        // Start SQL
        $sql = "SELECT COUNT(*) AS countRes FROM ( 
            SELECT pc.id 
            FROM product_attributes AS p_a 
            JOIN products AS pc ON p_a.product_id = pc.id 
            WHERE (pc.category_id IN ($category_ids_str) 
            OR pc.category_id IN (SELECT id FROM categories WHERE parent_id IN ($category_ids_str))) 
            AND pc.status = '1' ";
    
        // Brand filter
        if (!empty($brands)) {
            $brands_list = implode(',', array_map('intval', $brands));
            $sql .= " AND pc.brand IN ($brands_list) ";
        }
    
        // Start attribute filtering
        $sql .= " AND ( (p_a.attribute_id = '$attribute_set_id' 
                AND p_a.attribute_value_id IN (SELECT id FROM attributes WHERE slug = '$attribute_slug'))";
    
        $innerCond = '';
        $attributeCount = 1;
    
        foreach ($attributes as $attribute) {
            $attr_set_id = $attribute['attribute_set_id'] ?? null;
    
            if (!$attr_set_id || $attr_set_id == $attribute_set_id) {
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
    
        if (!empty($innerCond)) {
            $sql .= $innerCond;
        }
    
        $sql .= ") GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = $attributeCount ) AS filtered_products";
    
        return $this->db->query($sql)->getRow();
    }

    function getAttributeNameProductCountsParentAttributesMultipleCategoryGlobalSearch(
        array $attribute_category_ids = [],
        $attribute_set_id,
        $attribute_slug,
        array $brands = [],
        array $attributes = [],
        string $keyword = ''
    ) {
        if (empty($attribute_category_ids)) {
            return 0; // Or handle as needed
        }
    
        $category_ids_str = implode(',', array_map('intval', $attribute_category_ids));
    
        // Prepare keyword logic
        $matchScoreClause = '';
        $havingClause = '';
        if (!empty($keyword)) {
            $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
            $words = explode(' ', $keyword);
            $searchStringFull = '+' . implode(' +', $words);
    
            $matchScoreClause = ",
                (
                    (MATCH(pc.name) AGAINST('$searchStringFull' IN BOOLEAN MODE) * 50) +
                    (MATCH(pc.short_description, pc.model, pc.vpn) AGAINST('$searchStringFull' IN BOOLEAN MODE) * 20) +
                    " . implode(' + ', array_map(function ($word) {
                        $plainWord = str_replace('+', '', $word);
                        return "(CASE WHEN LOCATE('$plainWord', pc.name) > 0 THEN (1000 - LOCATE('$plainWord', pc.name)) ELSE 0 END)";
                    }, $words)) . "
                ) AS match_score";
    
            $havingClause = " HAVING match_score > 0";
        }
    
        // Start SQL
        $sql = "SELECT COUNT(*) AS countRes FROM (
            SELECT pc.id $matchScoreClause
            FROM product_attributes AS p_a
            JOIN products AS pc ON p_a.product_id = pc.id
            WHERE (pc.category_id IN ($category_ids_str)
            OR pc.category_id IN (SELECT id FROM categories WHERE parent_id IN ($category_ids_str)))
            AND pc.status = '1'";
    
        // Brand filter
        if (!empty($brands)) {
            $brands_list = implode(',', array_map('intval', $brands));
            $sql .= " AND pc.brand IN ($brands_list)";
        }
    
        // Attribute filtering
        $sql .= " AND ( (p_a.attribute_id = '$attribute_set_id'
                    AND p_a.attribute_value_id IN (SELECT id FROM attributes WHERE slug = '$attribute_slug'))";
    
        $innerCond = '';
        $attributeCount = 1;
    
        foreach ($attributes as $attribute) {
            $attr_set_id = $attribute['attribute_set_id'] ?? null;
    
            if (!$attr_set_id || $attr_set_id == $attribute_set_id) {
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
    
        if (!empty($innerCond)) {
            $sql .= $innerCond;
        }
    
        $sql .= ") GROUP BY pc.id HAVING COUNT(DISTINCT p_a.attribute_id) = $attributeCount";
    
        // Add match score filtering if keyword exists
        if (!empty($havingClause)) {
            $sql .= " AND match_score > 0";
        }
    
        $sql .= ") AS filtered_products";
    
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

    
    // function getProductAttributeSetWithCategoryMultipleGroup($category = [], $brands = [])
    // {
    //     $builder = $this->db->table('attribute_set_category attr_set_cat');
    //     $builder->select('attr_set.id, attr_set.name, attr_set.slug, p.category_id');
    //     $builder->join('products as p', 'attr_set_cat.category_id = p.category_id');
    //     $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');

    //     // if(2 dimension){
    //     //     foreach{
    //     //         if (!empty($category)) {
    //     //             $builder->whereIn('p.category_id', $category);
    //     //         }
    //     //         foreach{
    //     //             if (!empty($category)) {
    //     //                 $builder->whereIn('p.category_id', $category);
    //     //             }
    //     //         }
    //     //     }
    //     // }
    //     if (!empty($category)) {
    //         $builder->whereIn('p.category_id', $category);
    //     }

    //     if (!empty($brands) && $brands != "") {
    //         $builder->whereIn('p.brand', $brands);
    //     }

    //     $builder->where('p.status', 1);

    //     $builder->groupBy(['attr_set.id']);
    //     // $builder->groupBy(['attr_set.id', 'attr_set_cat.id', 'attr_set_cat.category_id']);

    //     $builder->orderBy('attr_set.name', 'ASC');
    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

    function getProductAttributeSetWithCategoryMultipleGroup($category = [], $brands = [])
    {
        $builder = $this->db->table('attribute_set_category attr_set_cat');
        $builder->select('attr_set.id as attribute_set_id, attr_set.name, attr_set.slug, p.category_id');
        $builder->join('products as p', 'attr_set_cat.category_id = p.category_id');
        $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');

        if (!empty($category)) {
            $builder->whereIn('p.category_id', $category);
        }

        if (!empty($brands)) {
            $builder->whereIn('p.brand', $brands);
        }

        $builder->where('p.status', 1);
        $builder->orderBy('attr_set.name', 'ASC');

        $query = $builder->get();
        $results = $query->getResultArray();

        // Group by attribute set and merge category_ids
        $grouped = [];

        foreach ($results as $row) {
            $id = $row['attribute_set_id'];

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'id' => $id,
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'category_ids' => [],
                ];
            }

            if (!in_array($row['category_id'], $grouped[$id]['category_ids'])) {
                $grouped[$id]['category_ids'][] = $row['category_id'];
            }
        }

        // Reindex for array_values() if you want a clean 0-indexed array
        return array_values($grouped);
    }

    function getProductAttributeSetWithCategoryMultipleGroupGlobalSearch(array $category = [], array $brands = [], string $keyword = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('attribute_set_category attr_set_cat');
        $builder->select('attr_set.id as attribute_set_id, attr_set.name, attr_set.slug, p.category_id');
        $builder->join('products as p', 'attr_set_cat.category_id = p.category_id');
        $builder->join('attribute_set as attr_set', 'attr_set.id = attr_set_cat.attribute_set_id');

        // Filters
        if (!empty($category)) {
            $builder->whereIn('p.category_id', $category);
        }

        if (!empty($brands)) {
            $builder->whereIn('p.brand', $brands);
        }

        $builder->where('p.status', 1);

        // Full-text keyword filtering
        $keyword = trim(preg_replace('/\s+/', ' ', $keyword));
        if (!empty($keyword)) {
            $words = explode(' ', $keyword);
            $searchStringFull = '+' . implode(' +', $words); // +hp +probook

            // Match score calculation for filtering
            $builder->select('
                (
                    (MATCH(p.name) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 50) +
                    (MATCH(p.short_description, p.model, p.vpn) AGAINST("' . $searchStringFull . '" IN BOOLEAN MODE) * 20) +
                    ' . implode(' + ', array_map(function ($word) {
                        $plainWord = str_replace('+', '', $word);
                        return '(CASE WHEN LOCATE("' . $plainWord . '", p.name) > 0 THEN (1000 - LOCATE("' . $plainWord . '", p.name)) ELSE 0 END)';
                    }, $words)) . '
                ) AS match_score
            ');

            $builder->having('match_score >', 0);
        }

        $builder->orderBy('attr_set.name', 'ASC');

        $query = $builder->get();
        $results = $query->getResultArray();

        // Group by attribute set and merge category_ids
        $grouped = [];
        foreach ($results as $row) {
            $id = $row['attribute_set_id'];

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'id' => $id,
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'category_ids' => [],
                ];
            }

            if (!in_array($row['category_id'], $grouped[$id]['category_ids'])) {
                $grouped[$id]['category_ids'][] = $row['category_id'];
            }
        }

        return array_values($grouped);
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