<?= $this->include('frontend/layouts/header') ?>

<?php 

// foreach($products  as $rr){
//     echo "<pre>";
//     print_r($rr);
// }

// print_R($shipping_prices);


// die;
?>
<!-- Content Section -->
<section class="home-page-slider">
    <div class="container py-3">
        <div class="row">
            <!-- Left Side: Main Slider + Bottom Features -->
            <div class="col-md-8 d-flex flex-column">
                <!-- Main Slider -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="swiper home-slider">
                            <div class="swiper-wrapper">
                                <?php foreach($slider as $s){
                                    if($s['slider_name'] == "Cover") { ?>
                                <div class="swiper-slide">
                                <?php if($s['link'] != 'NULL'){ ?>
                                <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid" alt="Slide 1"></a>
                                <?php }else{ ?>
                                    <img src="<?= base_url($s['image']) ?>" class="img-fluid" alt="Slide 1">
                                <?php } ?>
                                </div>
                                <?php } } ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
                <!-- Bottom Two Feature Images in Two Columns -->
                <div class="row flex-grow-1 mt-3">
                    <div class="col-6  bottom-features">
                        <?php foreach($slider as $s){
                        if($s['slider_name'] == "Banner Bottom Left"){
                            if($s['link'] != 'NULL'){
                        ?>
                        <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid"
                                alt="Feature Below 1"></a>
                        <?php }else{ ?>
                            <img src="<?= base_url($s['image']) ?>" class="img-fluid"
                            alt="Feature Below 1">
                    
                    <?php } } } ?>
                    </div>
                    <div class="col-6 bottom-features">
                        <?php foreach($slider as $s){
                        if($s['slider_name'] == "Banner Botttom Center"){
                            if($s['link'] != 'NULL'){
                        ?>
                        <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid"
                                alt="Feature Below 1"></a>
                        <?php }else{ ?>
                            <img src="<?= base_url($s['image']) ?>" class="img-fluid"
                            alt="Feature Below 1">
                        <?php } } } ?>
                    </div>
                </div>
            </div>
            <!-- Right Side: Feature Images (Aligned Properly) -->
            <div class="col-md-4 d-flex flex-column justify-content-between">
                <div class="row flex-grow-1">
                    <div class="col-md-12 col-6 featured-images">
                    <?php foreach($slider as $s){
                        if($s['slider_name'] == "Banner Bottom Right"){
                            if($s['link'] != 'NULL'){
                        ?>
                        <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid mb-3 flex-fill"
                                alt="Feature Below 1"></a>
                        <?php }else{ ?>
                            <img src="<?= base_url($s['image']) ?>" class="img-fluid"
                            alt="Feature Below 1">
                        <?php } } } ?>
                    </div>
                    <div class="col-md-12 col-6 featured-images">
                    <?php foreach($slider as $s){
                        if($s['slider_name'] == "Banner Top left 1"){
                            if($s['link'] != 'NULL'){
                        ?>
                        <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid flex-fill"
                                alt="Feature Below 1"></a>
                        <?php }else{ ?>
                            <img src="<?= base_url($s['image']) ?>" class="img-fluid"
                            alt="Feature Below 1">
                        <?php } } } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="features-service-section py-4">
    <div class="container">
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-6 col-lg-3 mb-3">
                <div class="feature-service-box">
                    <div class="feature-service-icon">
                        <img src="assets/frontend/images/feature-icon-1.png" class="img-fluid" alt="Feature 1">
                    </div>
                    <div class="feature-service-content">
                        <h5>Free Delivery</h5>
                        <p>Orders from all item</p>
                    </div>
                </div>
            </div>
            <!-- feature-service 2 -->
            <div class="col-6 col-lg-3 mb-3">
                <div class="feature-service-box">
                    <div class="feature-service-icon">
                        <img src="assets/frontend/images/feature-icon-2.png" class="img-fluid" alt="Feature 1">
                    </div>
                    <div class="feature-service-content">
                        <h5>Return & Refund</h5>
                        <p>Maney back guarantee</p>
                    </div>
                </div>
            </div>
            <!-- feature-service 3 -->
            <div class="col-6 col-lg-3 mb-3">
                <div class="feature-service-box">
                    <div class="feature-service-icon">
                        <img src="assets/frontend/images/feature-icon-3.png" class="img-fluid" alt="Feature 1">
                    </div>
                    <div class="feature-service-content">
                        <h5>Member Discount</h5>
                        <p>One very order over $140.00</p>
                    </div>
                </div>
            </div>
            <!-- feature-service 4 -->
            <div class="col-6 col-lg-3 mb-3">
                <div class="feature-service-box">
                    <div class="feature-service-icon">
                        <img src="assets/frontend/images/feature-icon-4.png" class="img-fluid" alt="Feature 1">
                    </div>
                    <div class="feature-service-content">
                        <h5>Support 24/7</h5>
                        <p>Contact us 24 hours a day</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Swiper Carousel Section -->
<section class="category-swiper-section">
    <div class="container">
        <div class="swiper category-swiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <?php foreach($categories as $cat){ ?>
                <div class="swiper-slide">
                    <?php if($cat['slug'] != 'NULL'){ ?>
                    <a class="category-box" href="<?= $cat['slug'] ?>">
                        <img src="<?= base_url($cat['icon']) ?>" class="feature-icon"
                            alt="Icon 1">
                        <h5><?= $cat['name'] ?></h5>
                    </a>
                    <?php }else{ ?>
                    <div class="category-box">
                    <img src="<?= base_url($cat['icon']) ?>" class="feature-icon"
                                                alt="Icon 1">
                        <h5><?= $cat['name'] ?></h5>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <!-- Slide 2 -->
            </div>
            <!-- Swiper Pagination -->
            <!-- <div class="swiper-category-pagination"></div> -->
            <!-- Swiper Navigation -->
        </div>
        <div class="swiper-cateory-nav">
            <div class="swiper-category-button-next swiper-button-next"></div>
            <div class="swiper-category-button-prev swiper-button-prev"></div>
        </div>
    </div>
</section>
<!-- Deals of the day -->
<?php if(!empty($hotdeals)){ ?>
<section class="deals-of-the-day">
    <div class="container">
        <div class="row">
            <div class="col-7">
                <h2>Best Deals of The Days</h2>
            </div>
            <div class="col-5 float-end"><a href="<?= base_url('deals') ?>" class="btn-white arrow-icon">View All</a></div>
        </div>
        <div class="row product-listing">
            <div class="col-md-4">
                <div class="product-card">
                    <?php foreach (array_slice($hotdeals, 0, 3) as $product): ?>
                    <div class="container deals-container ">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class=" text-center">
                                    <div class="purchase-row">
                                        <?php 
                                        $discount = product_off_percentage($product['pv_price'], $product['pv_rrp']); 
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                            <div class="discount-label"><?= $discount ?> OFF</div>
                                        <?php } ?>

                                        <span class="wishlist"><i class="bi bi-heart-fill"></i></span>
                                    </div>
                                    <a href="<?= base_url($product['product_slug']) ?>" class="product-image">
                                        <img src="<?= base_url($product['product_image']); ?>" alt="Product">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-title">
                                    <a href="<?= base_url($product['product_slug']) ?>" class="product-title"><?= $product['product_name'] ?></a>
                                </div>
                                <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i
                                            class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                            class="bi bi-star"></i> <i class="bi bi-star"></i> (2)</b></div>
                                <div class="price-row mb-2">
                                    <span class="price">$<?= $product['pv_price'] ?></span>
                                    <?php
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                        <span class="crossed-price">$<?= $product['pv_rrp'] ?></span>
                                    <?php } ?>
                                </div>
                                <button class="red-btn" onclick="add_to_cart(this)" id="option-1" name="option-1" pid="<?= $product['product_id'] ?>" ppn="<?= $product['product_name'] ?>", ppp="<?= $product['pv_price'] ?>", ppimage="<?= $product['product_image']?>" , pbaseurl = "<?= base_url() ?>"  cat_id="<?php echo $product['category_id'] ?>">Add to cart</button>
                            </div>
                            <div class="col-12">
                                <div class="timer-counter">
                                    <span class="box">84</span>
                                    <span class="box">84</span>
                                    <span class="box">84</span> :
                                    <span class="box">84</span>
                                    <span>Remains until the end of the offer</span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if(isset($hotdeals[3]) && count($hotdeals) >= 3){ ?>
            <div class="col-md-4">
                <?php if (isset($hotdeals[3])): ?>
                <div class="product-card big-card">
                    <div class="container deals-container ">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class=" text-center">
                                    <div class="purchase-row">
                                    <?php 
                                        $discount = product_off_percentage($hotdeals[3]['pv_price'], $hotdeals[3]['pv_rrp']); 
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                            <div class="discount-label"><?= $discount ?> OFF</div>
                                        <?php } ?>
                                        <span class="wishlist "><i class="bi bi-heart"></i></span>
                                    </div>
                                    <a href="<?= base_url($hotdeals[3]['product_slug'])?>" class="product-image">
                                        <img src="<?= base_url($hotdeals[3]['product_image']); ?>" alt="Product">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="product-title">
                                    <a href="<?= base_url($hotdeals[3]['product_slug']) ?>" class="product-title"><?= $hotdeals[3]['product_name'] ?></a>
                                </div>
                                <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i
                                            class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                            class="bi bi-star"></i> <i class="bi bi-star"></i> (2)</b></div>
                                <div class="price-row mb-2">
                                    <span class="price">$<?= $hotdeals[3]['pv_price'] ?></span>
                                    <?php
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                        <span class="crossed-price">$<?= $hotdeals[3]['pv_rrp'] ?></span>
                                    <?php } ?>
                                </div>
                                <p class="py-3 productDesc">The DeepCool CC360 ARGB Micro-ATX case offers outstanding
                                    value with spacious component compatibility, a...</p>
                                <p class="py-3">This product is about to run out</p>
                                <p><img src="assets/frontend/images/product-progress.png" class="img-fluid"
                                        alt="img-fluid"></p>
                                <p class="py-2">available only: <b>38</b></p>

                                <button class="red-btn shop-icon" onclick="add_to_cart(this)" id="option-1" name="option-1" pid="<?= $hotdeals[3]['product_id'] ?>" ppn="<?= $hotdeals[3]['product_name'] ?>", ppp="<?= $hotdeals[3]['pv_price'] ?>", ppimage="<?= $hotdeals[3]['product_image']?>" , pbaseurl = "<?= base_url() ?>" cat_id="<?php echo $hotdeals[3]['category_id'] ?>"><img src="assets/frontend/images/shop-icon.png" width="13"
                                        class="img-fluid" alt="img-fluid"> Add to cart</button>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php } ?>
            <?php if (!empty($hotdeals) && count($hotdeals) > 4){ ?>
            <div class="col-md-4">
                <div class="product-card">
                    <?php foreach (array_slice($hotdeals, 4, 3) as $product): ?>
                    <div class="container deals-container ">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class=" text-center">
                                    <div class="purchase-row">
                                        <?php 
                                        $discount = product_off_percentage($product['pv_price'], $product['pv_rrp']); 
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                            <div class="discount-label"><?= $discount ?> OFF</div>
                                        <?php } ?>
                                        <span class="wishlist active"><i class="bi bi-heart-fill"></i></span>
                                    </div>
                                    <a href="<?= base_url($product['product_slug']) ?>" class="product-image">
                                        <img src="<?= base_url($product['product_image']); ?>" alt="Product">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-title">
                                    <a href="<?= base_url($product['product_slug']) ?>" class="product-title"><?= $product['product_name'] ?></a>
                                </div>
                                <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i
                                            class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                            class="bi bi-star"></i> <i class="bi bi-star"></i> (2)</b></div>
                                <div class="price-row mb-2">
                                    <span class="price">$<?= $product['pv_price'] ?></span>
                                    <?php
                                        if ($discount !== 0 && $discount !== "0%") { ?>
                                        <span class="crossed-price">$<?= $product['pv_rrp'] ?></span>
                                    <?php } ?>
                                </div>
                                <button class="red-btn shop-icon" onclick="add_to_cart(this)" id="option-1" name="option-1" pid="<?= $product['product_id'] ?>" ppn="<?= $product['product_name'] ?>", ppp="<?= $product['pv_price'] ?>", ppimage="<?= $product['product_image']?>" , pbaseurl = "<?= base_url() ?>" cat_id="<?php echo $product['category_id'] ?>"> Add to cart</button>
                            </div>
                            <div class="col-12">
                                <div class="timer-counter">
                                    <span class="box">84</span>
                                    <span class="box">84</span>
                                    <span class="box">84</span> :
                                    <span class="box">84</span>
                                    <span>Remains until the end of the offer</span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>
<!-- Best Seller products -->
<section class="best-seller">
    <div class="container">
        <div class="row">
            <?php 
                  if(isset($bestseller[1])){ 
               ?>
            <div class="col-12 col-lg-5">
                <h5>Amazon Award-Winning Monitor</h5>
                <h3><?= $bestseller[1]['product_name']?> <span><?= $bestseller[1]['product_name']?> </span></h3>
                <span class="starting-price">Starting at Price</span><b class="price">$<?= $bestseller[1]['pv_price'] ?></b>
                <div class="product-box">
                <a href="<?= base_url($bestseller[1]['product_slug'])?>" class="product-image">
                    <img src="<?= base_url($bestseller[1]['product_image']); ?>" class="img-fluid" alt="Icon 7">
                  </a>
                </div>
            </div>
            <?php 
                     }
               ?>

            <div class="col-12 col-lg-7 float-end mt-5">
                <div class="row">
                    <div class="col-7">
                        <h4>Best Sellers</h4>
                    </div>
                    <div class="col-5">
                        <div class="swiper-best-seller-nav">
                            <div class="swiper-best-seller-button-next swiper-button-next"></div>
                            <div class="swiper-best-seller-button-prev swiper-button-prev"></div>
                        </div>
                    </div>
                </div>

                <div class="swiper best-seller-slider product-listing">
                    <div class="swiper-wrapper">
                        <?php foreach ($bestseller as $prds1) { ?>
                        <div class="swiper-slide">
                            <div class="product-card text-center">
                                <div class="purchase-row">
                                    <div class="toprated-label">Top Rated</div>
                                    <span class="wishlist"><i class="bi bi-heart"></i></span>
                                </div>
                                <a href="<?= base_url($prds1['product_slug']) ?>" class="product-image">
                                    <img src="<?= base_url($prds1['product_image']); ?>" alt="Product">
                                </a>
                                <div class="product-title">
                                    <p><a href="<?= base_url('product/'.$prds1['product_slug']); ?>"><?= $prds1['product_name']?></a></p>
                                </div>
                                <div class="rating py-2 mb-2">
                                    <b>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                        <i class="bi bi-star"></i>
                                        <i class="bi bi-star"></i> (2)
                                    </b>
                                </div>
                                <div class="price-row">
                                    <span class="price">$<?= $prds1['pv_price'] ?></span>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular brands  -->
<section class="popular-brands">
    <div class="container">
        <div class="row">
            <div class="popular-brand-content">
                <div class="row pb-2">
                    <div class="col-7">
                        <h3 class="heading-title">Popular Brands</h3>
                    </div>
                    <div class="col-5 float-end"><a href="<?= base_url('brands') ?>" class="btn-white arrow-icon">View All</a></div>
                </div>
                <div class="swiper brand-slider">
                    <div class="swiper-wrapper">
                        <?php foreach($brands as $brand){ ?>
                        <div class="swiper-slide">
                            <a href="<?= $brand['slug'] ?>">
                                <?php if(isset($brand['icon'])){ ?>
                                    <img src="<?= base_url($brand['icon']) ?>" alt="Brand 1">
                                <?php } ?>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-brand-nav">
                        <div class="swiper-brand-button-next swiper-button-next"></div>
                        <div class="swiper-brand-button-prev swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Listing Section -->
<section class="best-arrival-list py-5">
    <div class="container">
        <div class="row pb-2">
            <div class="col-7">
                <h3 class="heading-title">Best Arrivals</h3>
            </div>
            <div class="col-5 float-end"><a href="<?= base_url('best-arrivals') ?>" class="btn-white arrow-icon">View All</a></div>
        </div>
    </div>
    <div class="container py-4 product-listing">
        <div class="row g-3">
            <?php foreach($arrivals as $product){ ?>
            <div class="col-lg-custom col-md-3 col-sm-6">
                <div class="product-card text-center p-3">
                    <a href="<?= base_url($product['product_slug']) ?>" class="product-title"><?= $product['product_name'] ?></a>
                    <div class="rating py-2 mb-2">
                        <center><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                class="bi bi-star-half"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i> (2)
                        </center>
                    </div>
                    <div class="product-image">
                        <a href="<?= base_url($product['product_slug']) ?>" class="product-image">
                            <img src="<?= base_url($product['product_image']); ?>" alt="Product">
                        </a>
                    </div>
                    <div class="price-row">
                        <?php 
                        $discount = product_off_percentage($product['pv_price'], $product['pv_rrp']); 
                        ?>
                        <div>
                            <span class="price">$<?= $product['pv_price'] ?></span>
                            <?php if($discount !== "0%"){ ?>
                                <span class="crossed-price">$<?= $product['pv_rrp'] ?></span>
                            <?php } ?>
                        </div>
                        <?php 
                        if ($discount !== 0 && $discount !== "0%") { ?>
                            <div class="discount-label"><?= $discount ?> OFF</div>
                        <?php } ?>

                    </div>
                    <div class="purchase-row">
                        <span>1,286 <span>Purchases</span></span>
                        <span class="wishlist"><i class="bi bi-heart-fill"></i></span>
                    </div>
                    <?php if(isset($product['pv_status']) && $product['pv_status'] == 0){ ?>
                        <div class="out-of-stock">Out of Stock</div>
                    <?php }else{ ?>
                        <button class="add-to-cart" name="add_cart" pid="<?= $product['product_id'] ?>" ppn="<?= $product['product_name'] ?>"  ppp="<?= $product['pv_price'] ?>"  ppimage="<?= $product['product_image']?>"  pbaseurl = "<?= base_url() ?>" cat_id="<?= $product['category_id'] ?>"
                            onclick="add_to_cart(this)">Add to cart </button>
                    <?php } ?>

                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<section class="testimonial-section">
    <div class="container">
        <div class="row">
            <!-- Left Column: Heading & Description -->
            <div class="col-md-2 testimonial-content">
                <p class="eyebrow-text">Testimonials</p>
                <h2 class="section-title">What our Clients say</h2>
                <p class="section-description">
                    Lorem ipsum dolor sit amet adipiscing elit amet tellus adipiscing accumsan.
                </p>
            </div>
            <!-- Right Column: Swiper Slider -->
            <div class="col-md-10">
                <div class="swiper testimonial-slider">
                    <div class="swiper-wrapper">
                        <!-- Testimonial Card 1 -->
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-text">
                                    <h4 class="card-title">What others thought of</h4>
                                    <p class="card-description">
                                        Integer et accumsan nisl. Ut eget turpis eget magna fermentum facilisis.
                                    </p>
                                </div>
                                <div class="testimonial-footer d-flex align-items-center">
                                    <!-- Left: User Image -->
                                    <div class="testimonial-img">
                                        <img src="assets/frontend/images/user-icon.png" alt="User">
                                    </div>
                                    <!-- Right: Name & Designation -->
                                    <div class="testimonial-info">
                                        <h5 class="user-name">John William</h5>
                                        <p class="user-designation">Manager at Furniti</p>
                                    </div>
                                    <!-- Star Ratings -->
                                    <div class="testimonial-rating">
                                        <div class="star-rating" data-rating="3.5">
                                            <div class="stars ">
                                                <div class="rating ">
                                                    <center><i class="bi bi-star-fill"></i> <i
                                                            class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial Card 2 -->
                        <div class="swiper-slide">
                            <div class="testimonial-card red-theme">
                                <div class="testimonial-text">
                                    <h4 class="card-title">What others thought of</h4>
                                    <p class="card-description">
                                        Integer et accumsan nisl. Ut eget turpis eget magna fermentum facilisis.
                                    </p>
                                </div>
                                <div class="testimonial-footer d-flex align-items-center">
                                    <div class="testimonial-img">
                                        <img src="assets/frontend/images/user-icon.png" alt="User">
                                    </div>
                                    <div class="testimonial-info">
                                        <h5 class="user-name">Emily Smith</h5>
                                        <p class="user-designation">CEO at TechFirm</p>
                                    </div>
                                    <div class="testimonial-rating">
                                        <div class="star-rating" data-rating="2.5">
                                            <div class="stars ">
                                                <div class="rating ">
                                                    <center><i class="bi bi-star-fill"></i> <i
                                                            class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial Card 2 -->
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-text">
                                    <h4 class="card-title">What others thought of</h4>
                                    <p class="card-description">
                                        Integer et accumsan nisl. Ut eget turpis eget magna fermentum facilisis.
                                    </p>
                                </div>
                                <div class="testimonial-footer d-flex align-items-center">
                                    <div class="testimonial-img">
                                        <img src="assets/frontend/images/user-icon.png" alt="User">
                                    </div>
                                    <div class="testimonial-info">
                                        <h5 class="user-name">Emily Smith</h5>
                                        <p class="user-designation">CEO at TechFirm</p>
                                    </div>
                                    <div class="testimonial-rating">
                                        <div class="star-rating" data-rating="5">
                                            <div class="stars ">
                                                <div class="rating">
                                                    <center><i class="bi bi-star-fill"></i> <i
                                                            class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                                                        <i class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <!-- <div class="swiper-pagination"></div> -->
                    <div class="swiper-testimonial-nav">
                        <div class="swiper-testimonial-button-next swiper-button-next"></div>
                        <div class="swiper-testimonial-button-prev swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center about-container">
            <!-- Left Side: Image -->
            <div class="col-md-7">
                <img src="assets/frontend/images/about-image.png" class="img-fluid about-img" alt="About Us">
            </div>
            <!-- Right Side: Content -->
            <div class="col-md-5 about-content">
                <h5 class="about-title">About Us</h5>
                <h2 class="about-subtitle">About ZYLAX</h2>
                <p class="about-text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut in eget facilisi ut.
                    Turpis nisi tempus porttitor nunc interdum. Elit a auctor eget auctor. Massa facilisi
                    nunc ornare quis congue amet arcu. Tincidunt amet libero ultricies purus eu tortor habitan.
                </p>
                <a href="#" class="btn">Learn More</a>
            </div>
        </div>
    </div>
</section>
<!-- Feature banner  -->
<?php foreach($slider as $s){
    if($s['slider_name'] == "Home Ads Banner") { ?>
<section class="feature-banner pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
            <?php if($s['link'] != 'NULL'){ ?>
            <a href="<?= base_url($s['link']) ?>"><img src="<?= base_url($s['image']) ?>" class="img-fluid blog-img" alt="feature image"></a>
            <?php }else{ ?>
                <img src="<?= base_url($s['image']) ?>" class="img-fluid blog-img" alt="feature image">
            <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php } }?>
<!-- Blog Section -->
<section class="blog-list py-5">
    <div class="container">
        <div class="row pb-2">
            <div class="col-7">
                <h3 class="heading-title">Latest news and blogs</h3>
            </div>
            <div class="col-5 float-end"><a href="#" class="btn-white arrow-icon">View All</a></div>
        </div>
        <div class="row">
            <!-- Blog 1 -->
            <?php 
                  $bi = 0; 
                  foreach ($blogs as $blog) { 
                     if ($bi >= 3) break;
                  ?>
            <div class="col-md-4">
                <div class="blog-card">
                    <img src="<?= site_url().$blog['image'] ?>" class="img-fluid blog-img" alt="Blog Image">
                    <h4 class="blog-title mt-3"><?= $blog['title'] ?></h4>
                    <div class="d-flex justify-content-between align-items-center blog-meta">
                        <span class="blog-date"><?= date("F j, Y", strtotime($blog['date_added'])) ?></span>
                        <?php if($blog['slug'] != 'NULL'){ ?>
                        <a href="<?=  site_url().$blog['slug'] ?>" class="blog-link">Visit Blog →</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php 
                     $bi++; 
                  } 
               ?>
        </div>
    </div>
</section>
<!-- Footer -->

<?= $this->include('frontend/layouts/footer') ?>