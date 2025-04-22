<?php

use CodeIgniter\Database\Database;

if (!function_exists('get_settings')) {
    function get_settings($type = 'system_settings', $is_json = false)
    {
        $db = \Config\Database::connect(); // Load the database connection
        $query = $db->table('settings')->where('variable', $type)->get(); // Query the database
        // $data = $query->getRowArray(); // Return a single row as an array
        // return $data;
        $res = $query->getResultArray(); // Return a single row as an array
        if (!empty($res)) {
            if ($is_json) {
                $setting = json_decode($res[0]['value'], true);
                if ($type === "payment_method") {
                    if (!isset($setting['max_cod_amount'])) {
                        $setting['max_cod_amount'] = 0;
                    } else {
                        $setting['max_cod_amount'] =  (float)$setting['max_cod_amount'];
                    }
                    if (!isset($setting['min_cod_amount'])) {
                        $setting['min_cod_amount'] = 0;
                    } else {
                        $setting['min_cod_amount'] =  (float)$setting['min_cod_amount'];
                    }
                }
    
                return $setting;
            } else {
                return output_escaping($res[0]['value']);
            }
        }
    }
}

if (!function_exists('get_image_url')) {
    function get_image_url($path, $image_type = '', $image_size = '', $file_type = 'image')
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        $path = explode('/', (string)$path);
        $subdirectory = '';
        for ($i = 0; $i < count($path) - 1; $i++) {
            $subdirectory .= $path[$i] . '/';
        }
        $image_name = end($path);

        $file_main_dir = FCPATH . $subdirectory;
        $image_main_dir = base_url() . $subdirectory;
        if ($file_type == 'image') {
            $types = ['thumb', 'cropped'];
            $sizes = ['md', 'sm'];
            if (in_array(trim(strtolower($image_type)), $types) &&  in_array(trim(strtolower($image_size)), $sizes)) {
                $filepath = $file_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
                $imagepath = $image_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
                if (file_exists($filepath)) {
                    return  $imagepath;
                } else if (file_exists($file_main_dir . $image_name)) {
                    return  $image_main_dir . $image_name;
                } else {
                    return  base_url() . NO_IMAGE;
                }
            } else {
                if (file_exists($file_main_dir . $image_name)) {
                    return  $image_main_dir . $image_name;
                } else {
                    return  base_url() . NO_IMAGE;
                }
            }
        } else {
            $file = new SplFileInfo($file_main_dir . $image_name);
            $ext  = $file->getExtension();

            $media_data =  find_media_type($ext);
            $image_placeholder = $media_data[1];
            $filepath = FCPATH .  $image_placeholder;
            $extensionpath = base_url() .  $image_placeholder;
            if (file_exists($filepath)) {
                return  $extensionpath;
            } else {
                return  base_url() . NO_IMAGE;
            }
        }
    }
}

if (!function_exists('output_escaping')) {
    function output_escaping($array)
    {
        $exclude_fields = ["images", "other_images"];

        if (!empty($array)) {
            if (is_array($array)) {
                $data = array();
                foreach ($array as $key => $value) {
                    if (!in_array($key, $exclude_fields)) {
                        $data[$key] = stripcslashes((string)$value);
                    } else {
                        $data[$key] = $value;
                    }
                }
                return $data;
            } else if (is_object($array)) {
                $data = new stdClass();
                foreach ($array as $key => $value) {
                    if (!in_array($key, $exclude_fields)) {
                        $data->$key = stripcslashes($value);
                    } else {
                        $data[$key] = $value;
                    }
                }
                return $data;
            } else {
                return stripcslashes($array);
            }
        }
    }
}

if (!function_exists('input_tags_to_comma_string')) {
    function input_tags_to_comma_string($json)
    {
        if(isset($json) && !empty($json)){
            $implode = array();
            $multiple = json_decode($json, true);
            foreach($multiple as $single)
                $implode[] = implode(', ', $single);
            
            return implode(', ', $implode); 
        }
    }
}

if (!function_exists('product_off_percentage')) {
    function product_off_percentage($price, $rrp)
    {
        $percentage = 0;
        if($rrp != 0 && $price != 0){
            $percentage = (($rrp - $price) / $rrp) * 100;
        }
       return round($percentage) . "%";
    }
}

if (!function_exists('getCategories')) {
    function getCategories($parent_id = 0, $prefix = '-', $selected_id = 0 )
    {
        $db = \Config\Database::connect(); // Load the database connection
        $query = $db->table('categories')->where('parent_id', $parent_id)->orderBy('name', 'asc')->get(); // Query the database
        $result = $query->getResultArray(); // Return a single row as an array

        foreach ($result as $row){

            $selected = '';
            if($selected_id != 0 && $row['id'] == $selected_id){
                $selected = 'selected';
            }   

            // echo "<option value='" . $row['id'] . "' $selected>" . $prefix . $row['name'] . "</option>";
            if($row['parent_id'] == 0){
                echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
            }else{
                echo "<option value='" . $row['id'] . "' $selected>" . $prefix . $row['name'] . "</option>";
            }
        
            getCategories($row['id'], $prefix . '-', $selected_id);
        }        
    }
}

if (!function_exists('getCategoriesMultiple')) {
    function getCategoriesMultiple($parent_id = 0, $prefix = '-', $selected_id = '' )
    {

        $selected_ids = explode (",", $selected_id); 
        
        $db = \Config\Database::connect(); // Load the database connection
        $query = $db->table('categories')->where('parent_id', $parent_id)->orderBy('name', 'asc')->get(); // Query the database
        $result = $query->getResultArray(); // Return a single row as an array

        foreach ($result as $row){

            $selected = '';
            if(in_array($row['id'], $selected_ids ?? [])){
                $selected = 'selected';
            }   

            // echo "<option value='" . $row['id'] . "' $selected>" . $prefix . $row['name'] . "</option>";
            if($row['parent_id'] == 0){
                echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
            }else{
                echo "<option value='" . $row['id'] . "' $selected>" . $prefix . $row['name'] . "</option>";
            }
        
            getCategoriesMultiple($row['id'], $prefix . '-', $selected_id);
        }        
    }
}

if (!function_exists('getProductTags')) {
    function getProductTags()
    {
        $db = \Config\Database::connect(); // Load the database connection
        $query = $db->table('product_master_tags')->where('status', 1)->orderBy('name', 'asc')->get(); // Query the database
        $result = $query->getResultArray(); // Return a single row as an array

        foreach ($result as $row){
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }        
    }
}

if (!function_exists('getProductTagsMultiple')) {
    function getProductTagsMultiple($selected_id = '' )
    {

        $selected_ids = explode (",", $selected_id); 
        
        $db = \Config\Database::connect(); // Load the database connection
        $query = $db->table('product_master_tags')->where('status', 1)->orderBy('name', 'asc')->get(); // Query the database
        $result = $query->getResultArray(); // Return a single row as an array

        foreach ($result as $row){

            $selected = '';
            if(in_array($row['id'], $selected_ids ?? [])){
                $selected = 'selected';
            }   

            echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
        }        
    }
}