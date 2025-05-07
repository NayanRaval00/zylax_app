<?php

namespace App\Controllers;

use App\Models\Sliders;
use App\Models\Blogs;
use App\Models\BlogCategories;
use App\Models\Products;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\WishlistModel;

class Home extends BaseController
{
    public function index()
    {
        // Load Sliders with categories
        $sliderModel = new Sliders();
        $slider = $sliderModel->select("sliders.type, sliders.type_id, sliders.link, sliders.image, sliders.image_alt, slider_categories.slider_name")
            ->join("slider_categories", "slider_categories.id = sliders.type_id", "left")
            ->orderBy("sliders.id", "desc")
            ->findAll();
    
        // Check wishlist for logged-in user
        $wishlistItems = [];
        if (session()->has('user_id')) {
            $userId = session()->get('user_id');
            $wishlistModel = new \App\Models\WishlistModel();
    
            // Get all product_ids in wishlist for this user
            $wishlistData = $wishlistModel->where('user_id', $userId)->findAll();
            $wishlistItems = array_column($wishlistData, 'product_id');
        }
    
        // Load Blogs with categories
        $blogModel = new Blogs();
        $blogs = $blogModel->select("blogs.title, blogs.description, blogs.image, blogs.slug, blogs.status, blog_categories.id as blog_cat, blogs.id as blog_id, blogs.date_added")
            ->join("blog_categories", "blog_categories.id = blogs.category_id", "left")
            ->orderBy("blogs.id", "desc")
            ->findAll();
    
        $productModel = new Products();
    
        // Helper function to attach 'in_wishlist' flag
        $addWishlistFlag = function (&$products) use ($wishlistItems) {
            foreach ($products as &$p) {
                $p['in_wishlist'] = in_array($p['product_id'], $wishlistItems);
            }
            unset($p);
        };
    
        // Main product list
        $products = $productModel->getProductsFiltersListing();
        $addWishlistFlag($products);
    
        // Best arrivals
        $bestArrivals = $productModel->getProductsBestArrivals();
        $addWishlistFlag($bestArrivals);
    
        // Bestsellers
        $bestsellerMain = $productModel->getProductsBestSellers(1, 0);
        $addWishlistFlag($bestsellerMain);
    
        $bestseller = $productModel->getProductsBestSellers(15, 1);
        $addWishlistFlag($bestseller);
    
        // Hot deals
        $hotdeals = $productModel->getProductshotdeals();
        $addWishlistFlag($hotdeals);
    
        // Load Brands
        $brandModel = new Brands();
        $brands = $brandModel->where('is_show', 1)
            ->where('icon IS NOT NULL', null, false)
            ->orderBy('id', 'DESC')
            ->findAll();
    
        // Load Categories
        $categoryModel = new Categories();
        $categories = $categoryModel->where('is_show', 1)
            ->orderBy('id', 'asc')
            ->findAll();
    
        // Pass all data to the view
        return view('frontend/index', [
            'slider'         => $slider,
            'blogs'          => $blogs,
            'products'       => $products,
            'arrivals'       => $bestArrivals,
            'bestsellerMain' => $bestsellerMain,
            'bestseller'     => $bestseller,
            'hotdeals'       => $hotdeals,
            'brands'         => $brands,
            'categories'     => $categories
        ]);
    }
    

    public function product_list()
    {
        return view('frontend/product-list');
    }
}
