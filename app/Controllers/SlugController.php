<?php

namespace App\Controllers;
use App\Controllers\ProductController;
use App\Controllers\PageController;
use App\Controllers\BrandController;
use App\Controllers\CategoryController;
use CodeIgniter\Config\Services;
// use Config\Services;

use App\Models\Custommenus;
use App\Models\CustommenuCategory;
use App\Models\CustommenusSub;

class SlugController extends BaseController
{
    public function index($slug, $slug2 = "", $slug3 = "")
    {
        // echo $slug3; exit;

        $db = \Config\Database::connect();

        if(isset($slug3) && !empty($slug3)){
            $query = $db->table('slugs')->where('type', 'category')->where('slug', $slug3)->get();
        }else if(isset($slug2) && !empty($slug2)){
            $query = $db->table('slugs')->where('slug', $slug2)->get();
        }else{
            $query = $db->table('slugs')->where('slug', $slug)->get();
        }
        $page = $query->getRow();
        // print_r($page); exit;
        // $excludedRouteArr = [
        //     'admin',
        //     'products'
        // ];
        // if(!in_array($slug,$excludedRouteArr) && isset($page->type) && $page->type == "product"){
        if(isset($page->type) && $page->type == "product"){

            $productController = new ProductController();
            return $productController->show($page->slug);

        } else if(isset($page->type) && $page->type == "static_page"){

            $pageController = new PageController();
            return $pageController->show($page->slug);

        } else if(isset($page->type) && $page->type == "brand"){

            $brandController = new BrandController();
            return $brandController->brand_products($page->slug);

        } else if(isset($page->type) && $page->type == "category" && $slug2 == ""){

            $categoryController = new CategoryController();
            return $categoryController->sub_category($page->slug);

        } else if(isset($page->type) && $page->type == "category" && $slug2 != "" && $slug3 == ""){

            // echo "slug 1 ".$slug."<br>";
            // echo "slug 2 ".$page->slug;
            // exit;
            $categoryController = new CategoryController();
            // return $categoryController->sub_sub_category($slug, $page->slug);
            return $categoryController->sub_sub_category($slug, $page->slug);

        } else if(isset($page->type) && $page->type == "category" && $slug2 != "" && $slug3 != ""){

            // echo "set"; exit;

            $categoryController = new CategoryController();
            return $categoryController->sub_sub_sub_category($slug, $slug2, $page->slug);

        } else if(isset($page->type) && $page->type == "mega_menu" && $slug2 == ""){

            // echo $page->slug; exit;

            $megaMenuController = new MegaMenuController();
            return $megaMenuController->mega_menu($page->slug, $page->ref_id);

        } else if(isset($page->type) && $page->type == "mega_custommenu" && $slug2 == ""){

            // echo "set2"; exit;
            // print_r($page); exit;
            $categoryController = new CategoryController();


            $custommenusModel = new Custommenus();
            // $custommenuCategoryModel = new CustommenuCategory();


            $custom_menu = $custommenusModel->getCustomMenuDetail($page->ref_id);
            // print_r($custom_menu);
            // exit;

            // echo "test"; exit;
            // $category_link = $categoryModel->getCategoryMenuDetail($page->slug);
            // print_r($category_link);
            // exit;

            if( isset($custom_menu['link_url']) && $custom_menu['link_url'] != ""){

                // echo $custom_menu['link_url']; 
                
                $check_url_global_search = strpos($custom_menu['link_url'], 'autosearch');
                // echo $check_url_global_search;
                // exit;

                $get_split_query = explode('?', $custom_menu['link_url']);

                $get_first = ltrim($get_split_query[0], '/');
                $get_2nd = explode('/', $get_first);

                $check_count = count($get_2nd);
                // echo $get_2nd[0]; exit;
                // echo $check_count; exit;


                if($check_url_global_search === 1){

                    // echo "fff"; 
                    // print_r($get_split_query);

                    $queryString = $get_split_query[1];

                    // Parse the query string
                    parse_str($queryString, $queryParams);

                    if (isset($queryParams['q'])) {
                        $searchQuery = str_replace('+', ' ', $queryParams['q']);
                        // echo $searchQuery;

                        $FindsearchController = new Findsearch();
                        return $FindsearchController->autosearch($searchQuery);

                        
                    } else {
                        echo "No 'q' parameter found.";
                    }

                    exit;

                    // $FindsearchController = new Findsearch();
                    // return $FindsearchController->autosearch();

                    

                }else{
                    if($check_count == 1){
                        return $categoryController->sub_category($get_2nd[0], $get_split_query[1], $page);
                    }else if($check_count == 2){
                        return $categoryController->sub_sub_category($get_2nd[0], $get_2nd[1], $get_split_query[1], $page);
                    }else if($check_count == 3){
                        return $categoryController->sub_sub_sub_category($get_2nd[0], $get_2nd[1], $get_2nd[2], $get_split_query[1], $page);
                    }

                }


                // print_r($get_2nd);
                // echo "set"; 
                // exit;

            }
            else{
                $megaMenuController = new MegaMenuController();
                return $megaMenuController->mega_custommenu($page->slug, $page->ref_id);
            }

            // monitors/lcd-monitors?Aspect-Ratio=16:10+32:9&Curved=Yes&Panel-Type=IPS
            // return $categoryController->sub_sub_category("monitors", "lcd-monitors", "?brands=acer+hp");

        } else if(isset($page->type) && $page->type == "mega_custommenu_sub" && $slug2 != ""){

            // echo $page->slug; exit;
            // print_r($page); exit;
            $categoryController = new CategoryController();

            $custommenusSubModel = new CustommenusSub();
            $custom_menu_sub = $custommenusSubModel->getCustomSubMenuDetail($page->ref_id);
            // print_r($custom_menu_sub);
            // exit;

            if( isset($custom_menu_sub['link_url']) && $custom_menu_sub['link_url'] != ""){

                $check_url_global_search = strpos($custom_menu_sub['link_url'], 'autosearch');
                // echo $check_url_global_search;
                // exit;

                $get_split_query = explode('?', $custom_menu_sub['link_url']);

                $get_first = ltrim($get_split_query[0], '/');
                $get_2nd = explode('/', $get_first);

                $check_count = count($get_2nd);

                // if($check_count == 2){
                //     return $categoryController->sub_sub_category($get_2nd[0], $get_2nd[1], $get_split_query[1]);
                // }

                if($check_url_global_search === 1){

                    // echo "fff"; 
                    // print_r($get_split_query);

                    $queryString = $get_split_query[1];

                    // Parse the query string
                    parse_str($queryString, $queryParams);

                    if (isset($queryParams['q'])) {
                        $searchQuery = str_replace('+', ' ', $queryParams['q']);
                        // echo $searchQuery;

                        $FindsearchController = new Findsearch();
                        return $FindsearchController->autosearch($searchQuery);

                        
                    } else {
                        echo "No 'q' parameter found.";
                    }

                    exit;

                    // $FindsearchController = new Findsearch();
                    // return $FindsearchController->autosearch();

                    

                }else{
                    if($check_count == 1){
                        return $categoryController->sub_category($get_2nd[0], $get_split_query[1], $page);
                    }else if($check_count == 2){
                        return $categoryController->sub_sub_category($get_2nd[0], $get_2nd[1], $get_split_query[1], $page);
                    }else if($check_count == 3){
                        return $categoryController->sub_sub_sub_category($get_2nd[0], $get_2nd[1], $get_2nd[2], $get_split_query[1], $page);
                    }
                }

                // print_r($get_2nd);
                // echo "set"; 
                // exit;

            }
            else{
                $megaMenuController = new MegaMenuController();
                return $megaMenuController->mega_custommenu_sub($slug, $page->slug, $page->ref_id);
            }

        } 

        $routes = service('routes');
        $uri = $slug; // The URI you want to check

        $route = $routes->getRoutes();
        $routeMatched = false;

        foreach ($route as $key => $val) {
            // $key will be the route path like 'hello', 'home', etc.
            if (preg_match('#^' . str_replace('/', '\/', $key) . '$#', $uri)) {
                $routeMatched = true;
                break;
            }
        }

        // echo $routeMatched; exit;

        if (! $routeMatched) {
            return redirect()->route($slug);
        }
        return redirect()->route('404');
      
        // return redirect()->route($slug);

    }
    
}