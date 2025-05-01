<?php

namespace App\Controllers;
USe App\Models\Sliders;
USe App\Models\Blogs;
USe App\Models\BlogCategories;
USe App\Models\Products;
Use App\Models\Brands;
Use App\Models\Categories;


class Home extends BaseController
{
    public function index()
    {
        $slider = new Sliders();
        $slider = $slider->select("sliders.type, sliders.type_id, sliders.link, sliders.image, sliders.image_alt, slider_categories.slider_name")
        ->join("slider_categories", "slider_categories.id = sliders.type_id", "left")
        ->orderBy("sliders.id", "desc")
        ->findAll();

        $blog_categories = new BlogCategories();
        $blog =  new Blogs();

        $blog = $blog->select("blogs.title, blogs.description, blogs.image, blogs.slug, blogs.status,blog_categories.id as blog_cat, blogs.id as blog_id, blogs.date_added")
        ->join("blog_categories", "blog_categories.id = blogs.category_id", "left")
        ->orderBy("blogs.id", "desc")
        ->findAll();
    
        $Products = new Products();
        $product = $Products->getProductsFiltersListing();
        $bestArrivals = $Products->getProductsBestArrivals();
        $bestsellerMain = $Products->getProductsBestSellers(1, 0);
        $bestseller = $Products->getProductsBestSellers(15, 1);
        $hotdeals = $Products->getProductshotdeals();
        
        $brands = new Brands();
        $brands = $brands->where('is_show', 1)
                 ->where('icon IS NOT NULL', null, false) // Ensuring 'icon' is not NULL
                 ->orderBy('id', 'DESC')
                 ->findAll();


        $categories = new Categories();
        $categories = $categories->where('is_show', 1)->orderBy('id','asc')->findAll();

        // dd($bestseller);
        return view('frontend/index', ['slider' => $slider, 'blogs' => $blog, 'products' => $product, 'brands' => $brands, 'arrivals' => $bestArrivals, 'categories' => $categories, 'bestsellerMain' => $bestsellerMain, 'bestseller' => $bestseller, 'hotdeals' => $hotdeals ]);
    }
    
    public function product_list()
    {
        return view('frontend/product-list');
    }
}
