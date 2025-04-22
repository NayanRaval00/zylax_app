<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title Tag -->
    <title>Zylax Computers | <?= isset($seo['title']) ? $seo['title'] : '' ?></title>
    <!-- Meta Description -->
    <meta name="description" content="<?= isset($seo['meta_description']) ? $seo['meta_description'] : '' ?>">
    <!-- Meta Keywords -->
    <meta name="keywords" content="<?= isset($seo['meta_keywords']) ? $seo['meta_keywords'] : '' ?>">

    <!-- Fonts -->
    <link href="<?= base_url('assets/frontend/fonts/fonts.css') ?>" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Bootstrap 5.3 CSS -->
    <link href="<?= base_url('assets/frontend/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/frontend/css/style.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="<?= base_url("assets/frontend/css/cart.css");?>" rel="stylesheet">
</head>

<body>
    <!-- Header Menu  -->
    <header class="header">
        <!-- <div class="offer-item text-center d-none d-sm-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6">FREE delivery & 40% Discount for next 3 orders! Place your 1st order in.</div>
                <div class="col-md-6">Until the end of the sale: <b class="saletime"><span>47</span> Days <span>06</span> Hours <span>47</span> Mins <span>06</span> Seconds</b></div>
            </div>
        </div>
        </div> -->
        <div class="top-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-6  d-none d-sm-block">Welcome to ZYLAX, One Stop Shop For All Your Gaming Needs!
                    </div>
                    <div class="col-md-6">
                        <div class="float-end tracking-order">
                            <a href="<?php echo base_url('track-order'); ?>" class="tracking">Track Order</a>
                            <?php if (!session()->has('user_id')) { ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"
                                class="sign-in">Signin/Register</a>
                            <?php } else { ?>
                            <a href="<?= base_url('auth/my-account') ?>">My Account</a>
                            <a href="<?= base_url('auth/logout') ?>" id="logout-btn">Logout</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-nav">
            <div class="container">
                <div class="row">
                    <div class="col-3 d-block d-sm-none">
                        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                    </button> -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <div class="col-6 col-lg-2 header-logo">
                        <a class="navbar-brand" href="<?= base_url();?>"><img
                                src="<?= base_url('assets/frontend/images/zylax-logo.png');?>" class="" alt="" /></a>
                    </div>
                    <div class="col-6 search-form">
                        <form class="form-search" accept-charset="utf-8" action="<?php echo base_url('autosearch') ?>" method="get">

                            <!-- Search Input -->
                            <div class="input-group box-search">

                                <!-- <input type="text" class="form-control" placeholder="Search anything..." aria-label="Search"> -->
                                <input autocomplete="off" id="search_anything"
                                    value="<?php echo !empty($_GET['q']) ? $_GET['q'] : "" ?>" type="text" name="q"
                                    placeholder="Search what you looking for ?">
                                <button class="btn btn-primary " type="submit"> </button>

                            </div>
                            <div class="dropdown-content" style="display:none">
                                <div class="category">
                                    <div class="category-header">
                                        <h5>Search Categories</h5>
                                    </div>
                                    <ul class="list-unstyled list-category text-left" id="category-container-auto">
                                    </ul>
                                </div>
                                <div class="column-product">
                                    <div class="product-header">
                                        <h5>List of "<span id="productSerachName">a</span>"</h5>
                                    </div>
                                    <div class="product-container" id="product-container-auto">

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-3 col-lg-4 col-md-4">
                        <div class="float-end price-support">
                            <div class="cart-container" id="cartContainer">
                                <a class="total-price"> $00.00<span>0</span></a>
                                <div class="cart-dropdown" id="cartDropdown">
                                    <div class="d-flex justify-content-between p-2">
                                        <button class="btn btn-success btn-sm view-cart">View Cart</button>
                                        <button class="btn btn-success btn-sm checkout">Checkout</button>
                                    </div>
                                </div>
                            </div>
                            <span class="support d-none d-sm-block">
                                <a class="tel">(+021) 345 678 910</a>
                                <a class="email">support@gmail.com</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar -->
        <?php
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM menus WHERE active='1'");
            $mega_menu = $query->getResultArray();
            // print_r($mega_menu); exit;
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <div class="shop-by-category">
                    <a class="mega-menu-btn" href="javascript:void(0);"><span class="megamenu-burger"></span><span>Shop
                            by Categories</span></a>
                    <div class="mega-menu-pro" id="megaMenupro" style="display: none;">
                        <div class="container">
                            <ul class="row main-categories" style="display: flex;">

                                <?php foreach ($mega_menu as $menu): 
                            $menu_id = $menu["id"];
                        ?>
                                <li class="col-md-4 category-item wsshoplink-active"
                                    data-category="<?= $menu['title'] ?>">
                                    <div class="icon-box">
                                        <?php if(isset($menu['image_top']) && $menu['image_top']){ ?>
                                        <img src="<?= base_url($menu['image_top']);?>" alt="<?= $menu['title'] ?>" />
                                        <?php }else{ ?>
                                        <!-- <i class="bi bi-keyboard"></i> -->
                                        <?= $menu['icon'] ?>
                                        <?php } ?>
                                    </div>
                                    <div class="sub-menu">
                                        <span class="category-title"><?= $menu['title'] ?></span>
                                        <p class="desc"><?= strip_tags($menu['description']) ?></p>
                                        <ul class="sub-category">

                                            <li class="sub-category-item" data-subcategory="><?= $menu['title'] ?>">
                                                <a class=" arrow-icon "
                                                    href="<?php if(isset($menu['link'])){ echo base_url($menu['link']); } ?>">
                                                    All <?= $menu['title'] ?></a>
                                            </li>

                                            <?php 
                                            $query = $db->query("SELECT categories.id, categories.name, categories.slug FROM menu_category INNER JOIN categories ON menu_category.category_id=categories.id WHERE menu_id='$menu_id'");
                                            $mega_menu_category = $query->getResultArray();
                                            // print_r($mega_menu_category); exit;
                                            foreach ($mega_menu_category as $category):
                                                $category_id = $category['id'];
                                        ?>
                                            <?php 
                                          $sub_query = $db->query("SELECT * FROM categories WHERE parent_id='$category_id'");
                                          $sub_category = $sub_query->getResultArray();
                                        //   print_r($sub_category); exit;
                                          ?>
                                            <li class="sub-category-item <?php if(isset($sub_category) && count($sub_category) > 0){ echo 'arrow-icon'; } ?> "
                                                data-subcategory="<?= $category['name'] ?>">
                                                <a class=" arrow-icon "
                                                    href="<?php if(isset($category['slug'])){ echo base_url($category['slug']); } ?>">
                                                    <?= $category['name'] ?></a>
                                                <?php 
                                                if($sub_category){
                                            ?>
                                                <ul>
                                                    <?php 
                                                    foreach ($sub_category as $sub_cate):
                                                ?>
                                                    <li class="sub-category-item"
                                                        data-subcategory="<?= $category['name'] ?>">
                                                        <a
                                                            href="<?php if(isset($sub_cate['slug'])){ echo base_url($category['slug'].'/'.$sub_cate['slug']); } ?>"><?= $sub_cate['name'] ?></a>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <?php } ?>
                                            </li>
                                            <?php endforeach; ?>

                                            <!-- custom menu and sub custom -->

                                            <?php 

                                        $custom_menu_query = $db->query("SELECT * FROM custommenus WHERE menu_id='$menu_id'");
                                        $custom_menu_result = $custom_menu_query->getResultArray();
                                        // print_r($custom_menu_result); exit;
                                        foreach ($custom_menu_result as $custom_menu):
                                            $custommenus_id = $custom_menu['id'];
                                        ?>

                                            <?php 

                                        $sub_custom_category_query = $db->query("SELECT categories.id, categories.name, categories.slug FROM custommenu_category INNER JOIN categories ON custommenu_category.category_id=categories.id WHERE custommenu_id='$custommenus_id'");
                                        $ub_custom_category_results = $sub_custom_category_query->getResultArray();

                                        $sub_custom_query = $db->query("SELECT * FROM custommenus_sub WHERE parent_id='$custommenus_id' AND custommenu_id='0'");
                                        $sub_custom_result = $sub_custom_query->getResultArray();
                                        //   print_r($sub_custom_result); exit;
                                        ?>
                                            <li class="sub-category-item <?php if(!empty($sub_custom_result) || !empty($ub_custom_category_results) && count($sub_custom_result) > 0 && count($ub_custom_category_results) > 0){ echo 'arrow-icon'; } ?>"
                                                data-subcategory="<?= $custom_menu['title'] ?>">
                                                <a class=" arrow-icon "
                                                    href="<?php if(isset($custom_menu['link'])){ echo base_url($custom_menu['link']); } ?>">
                                                    <?= $custom_menu['title'] ?></a>

                                                <?php 
                                            // if(count($ub_custom_category_results) > 0 || count($sub_custom_result) > 0){
                                            if(count($sub_custom_result) > 0){
                                                echo "<ul>";
                                            }
                                        ?>
                                                <?php  if($ub_custom_category_results){ ?>
                                                <?php foreach ($ub_custom_category_results as $sub_custom_category): ?>
                                                <!-- <li class="sub-category-item" data-subcategory="<?= $sub_custom_category['name'] ?>">
                                                            <a href="<?php if(isset($sub_custom_category['slug'])){ echo base_url($sub_custom_category['slug']); } ?>"><?= $sub_custom_category['name'] ?></a> 
                                                        </li> -->
                                                <?php endforeach; ?>
                                                <?php } ?>

                                                <?php  if($sub_custom_result){ ?>
                                                <?php foreach ($sub_custom_result as $sub_custom_menu):
                                                        $append_link = $custom_menu['link']. '/'.$sub_custom_menu['link'];
                                                        ?>
                                            <li class="sub-category-item"
                                                data-subcategory="<?= $sub_custom_menu['title'] ?>">
                                                <a
                                                    href="<?php if(isset($sub_custom_menu['link'])){ echo base_url($append_link); } ?>"><?= $sub_custom_menu['title'] ?></a>

                                                <?php 

                                                                $sub_custom_menu_id = $sub_custom_menu['id'];
                                                                $sub_sub_custom_query = $db->query("SELECT * FROM custommenus_sub WHERE custommenu_id='$sub_custom_menu_id'");
                                                                $sub_sub_custom_result = $sub_sub_custom_query->getResultArray();
                                                                //   print_r($sub_sub_custom_result); exit;

                                                            ?>

                                                <?php 
                                                                // if(count($ub_custom_category_results) > 0 || count($sub_custom_result) > 0){
                                                                if(count($sub_sub_custom_result) > 0){
                                                                    echo '<ul class="sub-category">';
                                                                }
                                                            ?>
                                                <?php
                                                                   if($sub_sub_custom_result){
                                                                    foreach ($sub_sub_custom_result as $sub_sub_custom_menu):
                                                                        $append_link_sub = $custom_menu['link']. '/'.$sub_sub_custom_menu['link'];
                                                                ?>
                                            <li class="sub-category-item"
                                                data-subcategory="<?= $sub_sub_custom_menu['title'] ?>">
                                                <a
                                                    href="<?php if(isset($sub_sub_custom_menu['link'])){ echo base_url($append_link_sub); } ?>"><?= $sub_sub_custom_menu['title'] ?></a>
                                            </li>
                                            <?php endforeach; 
                                                                    } ?>

                                            <?php 
                                                                if(count($sub_sub_custom_result) > 0){
                                                                    echo "</ul>";
                                                                }
                                                            ?>

                                </li>
                                <?php endforeach; ?>
                                <?php } ?>

                                <?php 
                                            if(count($sub_custom_result) > 0){
                                                echo "</ul>";
                                            }
                                        ?>

                                </li>

                                <?php endforeach; ?>

                            </ul>
                        </div>
                        <?php

                                $featured_product1 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 1");
                                $row_featured_product1 = $featured_product1->getRowArray();

                                $featured_product2 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 2");
                                $row_featured_product2 = $featured_product2->getRowArray();

                                $featured_product3 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 3");
                                $row_featured_product3 = $featured_product3->getRowArray();

                                $featured_product4 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 4");
                                $row_featured_product4 = $featured_product4->getRowArray();

                                $featured_product5 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 4");
                                $row_featured_product5 = $featured_product5->getRowArray();

                                $featured_product6 = $db->query("SELECT products.id, products.name, products.slug, products.image, product_variants.price FROM menus_featured_products LEFT JOIN products ON menus_featured_products.product_id=products.id LEFT JOIN product_variants ON menus_featured_products.product_id=product_variants.product_id WHERE menus_featured_products.menu_id = '$menu_id' and menus_featured_products.product_type = 4");
                                $row_featured_product6 = $featured_product6->getRowArray();

                                ?>
                        <div class="featured-block">
                            <div class="featured-contnet" data-category="Laptops and Tablets">
                                <h3><?= $menu['featured_title'] ?></h3>
                                <div class="card-container">
                                    <?php if(isset($row_featured_product1) && $row_featured_product1['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product1['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product1['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product1['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product1['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($row_featured_product2) && $row_featured_product2['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product2['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product2['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product2['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product2['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($row_featured_product3) && $row_featured_product3['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product3['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product3['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product3['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product3['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($row_featured_product4) && $row_featured_product4['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product4['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product4['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product4['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product4['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($row_featured_product5) && $row_featured_product5['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product5['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product5['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product5['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product5['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($row_featured_product6) && $row_featured_product6['id'] != ""){ ?>
                                    <div class="card-block">
                                        <div class="card">
                                            <img src="<?= base_url($row_featured_product6['image']);?>"
                                                class="card-img-top" alt="Image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $row_featured_product6['name'] ?></h5>
                                                <p class="card-text">$<?= $row_featured_product6['price'] ?></p>
                                                <a href="<?= base_url($row_featured_product6['slug']);?>"
                                                    class="">Explore</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        </li>
                        <?php endforeach; ?>

                        </ul>
                        <div class="sub-category" id="subCategory" style="display: none;">
                            <div class="row menu-inside-header">
                                <div class="col-md-12 parent-category">
                                    <div class="back-button" id="backButton" style="display: none;"><i class="bi bi-arrow-left"></i></div>
                                    <span id="parentCategoryTitle">
                                        <div class="icon-box"><i class="fa fa-laptop"></i></div>
                                    </span>
                                    <div id="parentCategoryDescription">
                                        <span>PC Parts, Upgrades and Essential Software</span>
                                        <p>Upgrade your PC with the best parts available—graphics cards, processors, and
                                            storage solutions. Enhance performance and gaming experience effortlessly.
                                            Get compatible parts at great prices.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row main-mega-content">
                                <div class="col-md-6 child-menu-mega">
                                    <ul class="sub-category-list"></ul>
                                </div>
                                <div class="col-md-6 child-feature-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto justify-content-between text-center">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <!-- Dropdown with Submenu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="/business-it-support">Business IT Support</a></li>
                            <li><a class="dropdown-item" href="/cloud-storage-and-backup">Cloud Storage And Backup</a>
                            </li>
                            <li><a class="dropdown-item" href="/email-support-and-services">Email Support And
                                    Services</a></li>
                            <li><a class="dropdown-item" href="/email-support-and-services">Email Support And
                                    Services</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('brands') ?>">Shop By Brands</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('about-zylax-computers') ?>">About
                            Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                </ul>
            </div>
            </div>
        </nav>
    </header>