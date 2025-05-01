<?= $this->include('frontend/layouts/header') ?>
<?php $db = \Config\Database::connect(); ?>
<?php 

// print_r("zy_" . uniqid(mt_rand(), true));
// print_r($relatedProductOptions); 
// die;

    // foreach($product  as $rr){
    //     // echo "<pre>";
    //     print_r($rr['category_id']);
    // }

// print_R($relatedProductOptions);

// die;

?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a>
                </li>
                <?php 
                $link_append = "";
                foreach ($breadcrumb as $bc): 
                    $link_append .= esc($bc['slug']);
                ?>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url($link_append) ?>"><?= esc($bc['name']) ?></a></li>
                <?php 
                $link_append .= "/";
                endforeach; ?>
                <li class="breadcrumb-item active" aria-current="page"><?= $product['name'] ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content Section -->
<section class="product-detail-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Main Product Slider -->
                <div class="swiper-container main-product-slider">
                    <div class="swiper-wrapper">
                        <!-- Slides for Main Slider -->
                        <?php foreach($images as $image) {?>
                        <div class="swiper-slide">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                onclick="showImage('<?= base_url($image['image']); ?>')">
                                <img src="<?= base_url($image['image']); ?>" alt="Image 1">
                                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Optional Navigation -->
                    <div class="swiper-prodcut-detail-button-next swiper-button-next"></div>
                    <div class="swiper-prodcut-detail-button-prev swiper-button-prev"></div>
                </div>

                <!-- Thumbnail Slider -->
                <div class="swiper-container product-thumbnail-slider">
                    <div class="swiper-wrapper">
                        <!-- Slides for Thumbnails -->
                        <?php foreach($images as $image) {?>
                        <div class="swiper-slide">
                            <img src="<?= base_url($image['image']); ?>" alt="Thumbnail 1">
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Optional Navigation -->
                    <!-- <div class="swiper-prodcut-detail-thumb-button-next swiper-button-next"></div>
                             <div class="swiper-prodcut-detail-thumb-button-prev swiper-button-prev"></div> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-detail-content-block">
                   <!-- <div class="rating">
                        <b><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                class="bi bi-star-half"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i>
                            <span class="rating-percent">4.7 Star Rating</span>
                            <span class="rating-count">(21,671 User feedback)</span>
                        </b>
                    </div>-->
                    <h2 class="mt-1"><?= $product['name'] ?></h2>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="product-attributes">Sku:
                                <span><?= $product['vpn'] ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="product-attributes">Availability:
                                <?php if($products_variants[0]['status'] == 1){ ?>
                                <span class="color-green">In Stock</span>
                                <?php }else{ ?>
                                <span class="color-red">Out of Stock</span>
                                <?php } ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="product-attributes">Brand:
                                <span><?= $product['brand_name'] ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="product-attributes">Category:
                                <span><?= $product['category_name'] ?></span>
                            </p>
                        </div>
                        <div class="col-md-12 display-flex py-4">
                        <?php 
                        $discount = product_off_percentage($products_variants[0]['price'], $products_variants[0]['rrp']); 
                        ?>
                            <div class="price-column">
                                <span class="product-price">$<?= $products_variants[0]['price'] ?> </span>
                                <?php if($discount !== "0%"){ ?>
                                    <span class="product-strike-price">$<?= $products_variants[0]['rrp'] ?></span>
                                    <span class="label-yellow"><?= $discount ?> OFF</span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($product_color_variants) && count($product_color_variants) >= 1){ ?>
                        <div class="col-md-6">
                            <p class="product-variant">Color:</p>
                            <ul class="product-colors">
                                <?php foreach($product_color_variants as $product_color) { ?>
                                <li><a href="<?= base_url($product_color['product_slug']); ?>"
                                        style="background-color:<?= $product_color['color'] ?>;"><?= $product_color['label'] ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>

                        <?php if(isset($product['configure_me']) && $product['configure_me'] == 0){ ?>
                        <?php foreach($product_attributes as $attribute) { ?>
                        <!-- <div class="col-md-6">
                            <p class="product-variant"><?= $attribute['set_name'] ?>:</p>
                            <select class="form-control" name="Size">
                                <option value="">Select <?= $attribute['set_name'] ?></option>
                                <?php foreach($attribute['dropdowns'] as $dropdowns) { ?>
                                <option value="<?= $dropdowns['attribute_value'] ?>"><?= $dropdowns['attribute_name'] ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div> -->
                        <?php } ?>
                        <?php } ?>

                        <div class="col-md-12">
                            <p><?= $product['short_description'] ?></p>
                        </div>

                        <div class="col-md-12 py-3">
                            <div class="row cart-area">
                                <div class="col-md-3 product-quantity">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1" id="prd_cnt_<?= $product['id'] ?>">
                                        <div class="dec qtybutton">-</div>
                                        <div class="inc qtybutton">+</div>
                                    </div>
                                </div>
                                <div class="col-md-6 addCart">
                                    <button class="add-to-cart" pid="<?= $product['id'] ?>" ppn="<?= $product['name'] ?>"  ppp="<?= $products_variants[0]['price'] ?>"  ppimage="<?= isset($images[0]['image']) ? $images[0]['image'] : '' ?>"  pbaseurl = "<?= base_url() ?>"  cat_id="<?= $product['category_id'] ?>"
                                        onclick="add_to_cart(this)"
                                        name="add_cart">Add to cart </button>
                                </div>
                                <div class="col-md-3 buyNow">
                                    <button class="buy-now" name="buy_now" pid="<?= $product['id'] ?>" ppn="<?= $product['name'] ?>"  ppp="<?= $products_variants[0]['price'] ?>"  ppimage="<?= isset($images[0]['image']) ? $images[0]['image'] : '' ?>"  pbaseurl = "<?= base_url() ?>"  cat_id="<?= $product['category_id'] ?>"
                                    onclick="add_to_cart(this)">Buy Now</button>
                                </div>
                            </div>
                        </div>

                      <!--  <div class="col-md-12 py-3">
                            <div class="row wishlist-share-content">
                                <div class="col-md-7 wishlist">
                                    <a class="wishlist-btn" href="javascript:void(0);"><i class="bi bi-heart"></i> Add
                                        to Wishlist</a>
                                    <a class="compare-btn" href="javascript:void(0);"><i class="bi bi-arrow-repeat"></i>
                                        Add to Compare</a>
                                </div>

                                <div class="col-md-5 share-product">
                                    <span>Share product:</span>
                                    <a class="social-links" href="javascript:void(0);"><i class="bi bi-copy"></i></a>
                                    <a class="social-links" href="javascript:void(0);"><i
                                            class="bi bi-facebook"></i></a>
                                    <a class="social-links" href="javascript:void(0);"><i class="bi bi-twitter"></i></a>
                                    <a class="social-links" href="javascript:void(0);"><i
                                            class="bi bi-pinterest"></i></a>
                                </div>

                            </div>
                        </div>-->

                        
                        <?php if($product['configure_me'] == 1){ ?>
                            <hr />
                            <div class="customize-me">
                                <h6 class="title"><strong>Product Configuration (Customize Me)</strong></h6>

                                <div class="accordion" id="productConfigAccordion">

                                    <?php
                                    $index = 1;
                                    foreach ($attributeSets as $attribute) { 
                                        $product_id = $product['id'];
                                        $attribute_set_id = $attribute['id'];
                                        
                                        $query = $db->query("SELECT pa.id as id, s.name as attribute_set, a.name as attribute_name, pa.added_attribute_value as price  FROM product_attributes pa LEFT join attribute_set s on s.id=pa.attribute_id LEFT join attributes a on a.id=pa.attribute_value_id WHERE pa.product_id='$product_id' AND pa.attribute_id = '$attribute_set_id'");
                                        $attribute_values = $query->getResultArray();
                                        // print_r($attribute_values); exit;
                                        if(!empty($attribute_values) && count($attribute_values) > 0){
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne<?= $attribute['id'] ?>">
                                                <button class="accordion-button <?php if($index != 1){ echo 'collapsed'; } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $attribute['id'] ?>" aria-expanded="true" aria-controls="collapseOne<?= $attribute['id'] ?>">
                                                    <?= $attribute['name'] ?>
                                                </button>
                                            </h2>
                                            <div id="collapseOne<?= $attribute['id'] ?>" class="accordion-collapse collapse <?php if($index == 1){ echo 'show'; } ?> " aria-labelledby="headingOne<?= $attribute['id'] ?>" data-bs-parent="#productConfigAccordion<?= $attribute['id'] ?>" style="">
                                                <div class="accordion-body">

                                                    <div class="btn-radio style2">

                                                        <div class="radio-info" id="492" data-comp-id="8" data-price="0" data-option="">
                                                            <input checked="true" type="radio" id="radio-transfer_<?= $attribute['id'] ?>" value="0" class="radio" name="comp_option_<?= $attribute['id'] ?>">
                                                            <label for="radio-transfer_<?= $attribute['id'] ?>" class="clearfix">    
                                                                <span class="price-notice ml-2">&nbsp; +
                                                                    <span class="price">$0</span>
                                                                </span>
                                                            </label>
                                                        </div>

                                                        <?php
                                                            foreach ($attribute_values as $value){
                                                                if(!empty($value['price']) && $value['price'] > 0){
                                                        ?>
                                                            <div class="radio-info" id="633" data-comp-id="8" data-price="1100" data-option="16GB NVIDIA Quadro RTX A4000 ">
                                                                <input type="radio" id="radio-transfer_<?= $value['id'] ?>" value="<?= $value['price'] ?>" class="radio" name="comp_option_<?= $attribute['id'] ?>">
                                                                <label for="radio-transfer_<?= $value['id'] ?>" class="clearfix">&nbsp; <?= $value['attribute_name'] ?>
                                                                    <span class="price-notice ml-2">+
                                                                        <span class="price ">
                                                                            $<?= $value['price'] ?>
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        <?php } } ?>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    <?php  $index++; } }  ?>

                                </div>

                            </div>
                        <?php } ?>

                        <div class="col-md-12 py-3">
                            <div class="guarantee-box">
                                <p>100% Guarantee Safe Checkout</p>
                                <img src="<?= base_url('assets/frontend/images/payment-method.png'); ?>" width="312"
                                    alt="Thumbnail 3">
                            </div>
                        </div>
                        <?php if(!empty($relatedProductOptions)){ ?>
                            <div class="col-md-12 pt-3" id="related_product" style="display:none;">
                            <?php  foreach($relatedProductOptions as $rrpo){ ?>
                                <div class="product-options">
                                    <div class="option-title">
                                        <img src="<?= base_url($rrpo['product_image']) ?>" alt="<?= $rrpo['product_name'] ?>">
                                        <h5><?= $rrpo['product_name'] ?></h5>
                                    </div>
                                
                                    <p class="option-description"><?= $rrpo['short_description'] ?></p>
                                
                                    <label for="option-1" class="checkbox-label">
                                        <input type="checkbox" onclick="add_to_cart(this)" id="option-1" name="option-1" pid="<?= $rrpo['product_id'] ?>" ppn="<?= $rrpo['product_name'] ?>" ppp="<?= $rrpo['pv_price'] ?>" ppimage="<?= isset($rrpo['product_image']) ? $rrpo['product_image'] : '' ?>" pbaseurl = "<?= base_url() ?>" cat_id="<?php echo $rrpo['category_id'] ?>" value="extended_warranty">
                                        <span class="price">$<?= $rrpo['pv_price'] ?></span>
                                    </label>
                                
                                    <!-- Warranty Details -->
                                    <div class="option-details">
                                        <p><?= $rrpo['description'] ?></p>
                                    </div>

                                    <a href="<?= base_url('products'); ?>">More information</a>
                                    
                                </div>
                            <?php }?>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-info-container">

    <div class="container my-4">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs justify-content-center" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description"
                    type="button" role="tab" aria-controls="description" aria-selected="true">
                    Description
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="additional-info-tab" data-bs-toggle="tab" data-bs-target="#additional-info"
                    type="button" role="tab" aria-controls="additional-info" aria-selected="false">
                    Additional Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification"
                    type="button" role="tab" aria-controls="specification" aria-selected="false">
                    Specification
                </button>
            </li>
           <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button"
                    role="tab" aria-controls="review" aria-selected="false">
                    Review
                </button>
            </li>-->
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content product-tabs-content" id="productTabsContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <div class="row">
                    <div class="col-md-5">
                        <p class="title">Description</p>
                        <p class="description">
                            <?= $product['description'] ?>
                        </p>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <p class="title">Feature</p>
                                <p class="description features">

                                    <?php foreach($product_features as $feature) { ?>
                                    <span class="icon">
                                        <img src="<?= base_url($feature['icon']); ?>" class="" alt="<?= $feature['text']; ?>">
                                        <?= $feature['text']; ?>
                                    </span>
                                    <?php } ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="title">Shipping Information</p>
                                <p class="description shipping">
                                    <!-- <span class="shipping">Courier: <span> 2 - 4 days, free shipping</span></span> -->
                                    <?php foreach($shipping_prices as $shipping) { ?>
                                    <span class="shipping"><?= $shipping['shipping_name'] ?>: <span>
                                            <?= $shipping['shipping_description'] ?>,
                                            $<?= $shipping['price'] ?></span></span>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

            <!-- Additional Information Tab -->
            <div class="tab-pane fade" id="additional-info" role="tabpanel" aria-labelledby="additional-info-tab">
                <?= $product['extra_description'] ?>
            </div>

            <!-- Specification Tab -->
            <div class="tab-pane fade specification" id="specification" role="tabpanel"
                aria-labelledby="specification-tab">
                <?= $product['specification'] ?>
            </div>

            <!-- Review Tab -->
            <div class="tab-pane fade review-section" id="review" role="tabpanel" aria-labelledby="review-tab">

                <div class="row">
                    <!-- Left Side: Reviews Summary -->
                    <div class="col-md-4">
                        <div class="review-summary text-center">
                            <p class="title">Overall Rating</p>
                            <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i> <i class="bi bi-star"></i> <i
                                        class="bi bi-star"></i> (2)</b></div>
                            <p>4.5 out of 5</p>
                            <p>(150 reviews)</p>
                        </div>
                    </div>

                    <!-- Right Side: Individual Reviews -->
                    <div class="col-md-8">
                        <div class="reviews-list">
                            <div class="review-item border-bottom pb-3 mb-3">
                                <p class="title">John Doe</p>
                                <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i
                                            class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                            class="bi bi-star"></i> <i class="bi bi-star"></i></b></div>
                                <p>"Excellent product! Exceeded my expectations."</p>
                            </div>
                            <div class="review-item border-bottom pb-3 mb-3">
                                <p class="title">John Doe</p>
                                <div class="rating pt-3"><b><i class="bi bi-star-fill"></i> <i
                                            class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                            class="bi bi-star"></i> <i class="bi bi-star"></i></b></div>
                                <p>"Good quality, but delivery took longer than expected."</p>
                            </div>
                            <!-- Add more review items here -->
                        </div>

                        <!-- Add Review Form -->
                        <div class="add-review mt-4">
                            <p class="title">Add Your Review</p>
                            <form id="addReviewForm">
                                <div class="mb-3">
                                    <label for="userName" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="userName" placeholder="Enter your name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="userRating" class="form-label">Your Rating</label>
                                    <select class="form-select" id="userRating" required>
                                        <option value="" disabled selected>Choose rating</option>
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="userReview" class="form-label">Your Review</label>
                                    <textarea class="form-control" id="userReview" rows="3"
                                        placeholder="Write your review here..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>

<section class="related-products list-gallery product-listing py-4">
    <div class="container">
        <h4><strong>Related Products</strong></h4>
        <div class="row">
            <div class="col-md-12">
                <div class="swiper related-product-slider">
                    <div class="swiper-wrapper">

                        <?php foreach($related_products as $productsss) { ?>
                        <div class=" swiper-slide product-card text-center p-3">
                            <div class="product-title">
                                <a href="<?= base_url($productsss['product_slug']); ?>">
                                    <?= $productsss['product_name'] ?>
                                </a>
                            </div>
                          <!--  <div class="rating py-2 mb-2">
                                <center><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                        class="bi bi-star-fill"></i> <i class="bi bi-star-half"></i> <i
                                        class="bi bi-star"></i> (2)</center>
                            </div>-->
                            <a href="<?= base_url($productsss['product_slug']); ?>" class="product-image">
                                <img src="<?= base_url($productsss['product_image']); ?>" alt="<?= $productsss['product_name'] ?>">
                            </a>
                            <div class="price-row">
                                <div>
                                    <span class="price">$<?= $productsss['pv_price'] ?></span>
                                    <span class="crossed-price">$<?= $productsss['pv_rrp'] ?></span>
                                </div>
                                <div class="discount-label">
                                    <?= product_off_percentage($productsss['pv_price'], $productsss['pv_rrp']) ?> OFF</div>
                            </div>
                           <!-- <div class="purchase-row">
                                <span>1,286 <span>Purchases</span></span>
                                <span class="wishlist"><i class="bi bi-heart-fill"></i></span>
                            </div>-->
                            <button class="add-to-cart" name="add_cart" pid="<?= $productsss['product_id'] ?>" ppn="<?= $productsss['product_name'] ?>"  ppp="<?= $productsss['pv_price'] ?>"  ppimage="<?= $productsss['product_image']?>"  pbaseurl = "<?= base_url() ?>"  cat_id="<?= $productsss['category_id'] ?>" onclick="add_to_cart(this)">Add to cart </button>
                        </div>
                        <?php } ?>

                    </div>
                    <div class="swiper-related-nav">
                        <div class="swiper-related-product-button-next swiper-button-next"></div>
                        <div class="swiper-related-product-button-prev swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<?= $this->include('frontend/layouts/footer') ?>


<!-- Bootstrap Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Large Preview">
            </div>
        </div>
    </div>
</div>

<script>
function showImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
}
  
  $(".customize-me .title").on("click", function () {
            $(this).parent().toggleClass("active");
        });

// $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
$(".qtybutton").on("click", function() {
var $button = $(this);
var $input = $button.siblings("input"); // Select input next to button

var oldValue = parseFloat($input.val()) || 1; // Default to 1 if NaN
var newVal = oldValue;

if ($button.text().trim() === "+") {
    newVal = oldValue + 1;
} else {
    newVal = Math.max(1, oldValue - 1); // Prevent going below 1
}
$input.val(newVal).trigger("input").trigger("change"); // Ensure UI updates
});


var relatedSlider = new Swiper(".related-product-slider", {
    loop: true,
    slidesPerView: 2, // Default for mobile
    spaceBetween: 20,
    breakpoints: {
        768: {
            slidesPerView: 2
        },
        1024: {
            slidesPerView: 4
        }
    },
    navigation: {
        nextEl: ".swiper-related-product-button-next",
        prevEl: ".swiper-related-product-button-prev",
    }
});

$(document).ready(function () {
    console.log("loading");

    function getCheckedRadios() {
        let checkedRadios = {};

        let basePrice = "<?php echo $products_variants[0]['price'] ?>";

        let checkboxPrice = 0;
        $("input[type='radio']:checked").each(function () {
            let name = $(this).attr("name");
            let value = $(this).val();
            // checkedRadios[name] = value;
            checkboxPrice += parseFloat(value);
        });

        // console.log("Checked radio buttons:", checkedRadios);
        // console.log("checkboxPrice", checkboxPrice);

        let totalPrice = parseFloat(basePrice) + parseFloat(checkboxPrice);
        $(".product-price").html(`$${totalPrice}`);
        $('.add-to-cart').attr('ppp', `${totalPrice}`);

        if(checkboxPrice !== 0){
            Swal.fire({
                title: "Total Price : $" + totalPrice,
                text: "successfully addon added! please add to cart the product",
                icon: "success",
                toast: true,
                position: "top-end",
                showConfirmButton: true,
                timer: 3000,
            });
        }

    }

    // Run on page load
    getCheckedRadios();

    // Run when any radio button is changed
    $("input[type='radio']").change(getCheckedRadios);
});

</script>