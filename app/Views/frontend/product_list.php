<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door" style="color: #EB4227;"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
</section>
<!-- Content Section -->
<section class="container my-5 product-listing-container">

    <div class="row">
        <div class="col-xl-3 col-lg-4 filter-container-box">
            <div class="filter margin-15">
                <button class="filter-heading" id="toggleBtn">Filter <i class="bi bi-sliders"></i></button>
            </div>
        </div>
        <div class="col-xl-9 col-lg-8 product-listing-filter">
            <div class="row g-2">
                <div class="col-lg-2 col-md-6 d-flex justify-content-center">
                    <!-- List View Button -->
                    <button class="btn btn-outline-primary listview mx-2" id="listViewButton">
                        <i class="bi bi-list"></i>
                    </button>
                    <!-- Thumbnail View Button -->
                    <button class="btn btn-outline-primary gridview mx-2" id="thumbViewButton">
                        <i class="bi bi-grid"></i>
                    </button>
                </div>
                <div class="col-lg-3 col-md-6" id="thirdrow"><b><?= $total_products ?></b> Results Found</div>

                <div class="col-lg-3 col-md-6" id="firstrow">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control searchinput" placeholder="Search..." value="<?php if(isset($search) && $search != "") { echo $search; } ?>">
                        <button class="input-group-text" id="search_btn">
                            <i class="bi bi-search"></i> <!-- Bootstrap Icons -->
                        </button>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" id="secondrow">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3"><label>Sort by:</label></div>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <select id="filter_by" class="form-control sortby">
                                <option selected="" value="">Select Sort by</option>
                                <option value="name_asc" <?php if(isset($filterBy) && $filterBy == 'name_asc'){ echo "selected"; } ?>>Sort by a to z</option>
                                <option value="name_dsc" <?php if(isset($filterBy) && $filterBy == 'name_dsc'){ echo "selected"; } ?>>Sort by z to a</option>
                                <option value="price_high" <?php if(isset($filterBy) && $filterBy == 'price_high'){ echo "selected"; } ?>>Sort by price high to low</option>
                                <option value="price_low" <?php if(isset($filterBy) && $filterBy == 'price_low'){ echo "selected"; } ?>>Sort by price low to high</option>
                                <option value="latest" <?php if(isset($filterBy) && $filterBy == 'latest'){ echo "selected"; } ?>>Sort by Latest to Oldest</option>
                                <option value="oldest" <?php if(isset($filterBy) && $filterBy == 'oldest'){ echo "selected"; } ?>>Sort by Oldest to Latest</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-4 filter-container">
            <!-- <div class="filter margin-15">
					<button class="filter-heading" id="toggleBtn">Filter <i class="bi bi-sliders"></i></button>
				</div> -->
            <br>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Related Categories
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
                        <div class="accordion-body" style="font-size: 14px;max-height: 300px;overflow-y: scroll;">
                            <?php foreach($categories as $category) {?>
                                <label class="radioopt"><input type="checkbox" class="category-checkbox" name="cate" value="<?= $category['category_slug'] ?>" <?php echo in_array($category['category_slug'], $selectedCategories) ? 'checked' : ''; ?> > <?= $category['category_name'] ?> (<?= $category['product_count'] ?>)</label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingBrand">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseBrands" aria-expanded="true" aria-controls="collapseOne">
                            Brands
                        </button>
                    </h2>
                    <div id="collapseBrands" class="accordion-collapse collapse show" aria-labelledby="headingBrand">
                        <div class="accordion-body" style="font-size: 14px;max-height: 300px;overflow-y: scroll;">
                            <?php foreach($brands as $brand) {?>
                                <label class="radioopt"><input type="checkbox" class="brand-checkbox" name="brand" value="<?= $brand['brand_slug'] ?>" <?php echo in_array($brand['brand_slug'], $selectedBrands) ? 'checked' : ''; ?> > <?= $brand['brand_name'] ?> (<?= $brand['product_count'] ?>)</label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <?php foreach($attribute_set_values as $attribute_set) {
                    // print_r($attribute_set);
                    ?>
                    <hr>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBrand">
                            <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCustom<?= $attribute_set['set_id'] ?>" aria-expanded="true" aria-controls="collapseOne">
                                <?= $attribute_set['set_name'] ?>
                            </button>
                        </h2>
                        <div id="collapseCustom<?= $attribute_set['set_id'] ?>" class="accordion-collapse collapse show" aria-labelledby="headingBrand">
                            <div class="accordion-body" style="font-size: 14px;">
                                <?php foreach($attribute_set['dropdowns'] as $attribute) {
                                    // $query_string = str_replace(' ', '-', strtolower($attribute_set['set_name']));
                                    $query_string = $attribute_set['set_slug'];
                                    $selectedAttributes = isset($_GET[$query_string]) ? explode(' ', $_GET[$query_string]) : [];
                                    ?>
                                    <label class="radioopt"><input type="checkbox" class="attribute-checkbox" name="<?= $query_string ?>" value="<?= $attribute['attribute_name'] ?>" <?php echo in_array($attribute['attribute_name'], $selectedAttributes) ? 'checked' : ''; ?>> <?= $attribute['attribute_name'] ?> (<?= $attribute['product_count'] ?>) </label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <hr>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Price
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo">
                        <div class="accordion-body" style="font-size: 14px;max-height: 300px;overflow-y: scroll;">
                            <div class="range-slider">
                                <div class="slider-track"></div>
                                <div class="slider-active"></div>
                                <input type="range" min="<?= $product_min_max['min_price'] ?>" max="<?= $product_min_max['max_price'] ?>" value="<?php if(isset($minPrice) && $minPrice != "") { echo $minPrice; }else{ echo $product_min_max['min_price']; } ?>" id="minRange">
                                <input type="range" min="<?= $product_min_max['min_price'] ?>" max="<?= $product_min_max['max_price'] ?>" value="<?php if(isset($maxPrice) && $maxPrice != "") { echo $maxPrice; }else{ echo $product_min_max['max_price']; } ?>" id="maxRange">
                                <label class="form-label">
                                    <span id="minValue" style="float: left">$<?= $product_min_max['min_price'] ?></span> 
                                    <span id="maxValue" style="float: right">$<?= $product_min_max['max_price'] ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Rating
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree">
                        <div class="accordion-body" style="font-size: 14px;max-height: 300px;overflow-y: scroll;">

                            <label class="radioopt"><input type="checkbox" name="checkbox" value="1"> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star-fill"></i> 5.0</label>
                            <label class="radioopt"><input type="checkbox" name="checkbox" value="2"> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star"></i> 4.0 & up</label>
                            <label class="radioopt"><input type="checkbox" name="checkbox" value="3"> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i>
                                3.0 & up</label>
                            <label class="radioopt"><input type="checkbox" name="checkbox" value="3"> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                    class="bi bi-star"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i> 2.0 &
                                up</label>
                            <label class="radioopt"><input type="checkbox" name="checkbox" value="3"> <i
                                    class="bi bi-star-fill"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i> <i class="bi bi-star"></i> 1.0 & up</label>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Popular Tag
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour">
                        <div class="accordion-body" style="font-size: 14px;max-height: 300px;overflow-y: scroll;">

                            <div class=" tags-container" data-toggle="buttons">
                                <?php foreach($product_tags as $tag) {?>
                                    <label class="btn btn-default">
                                        <input type="checkbox" class="tag-checkbox" name="tag" value="<?= $tag['slug'] ?>" <?php echo in_array($tag['slug'], $selectedTags) ? 'checked' : ''; ?>>
                                        <div class="tag"><?= $tag['name'] ?> (<?= $tag['product_count'] ?>)</div>
                                    </label>
                                <?php } ?>
                            </div>          

                            <!-- <div class="popular-tag">Monitor</div>
                            <div class="popular-tag-active">Monitor</div>
                            <div class="popular-tag">Monitor</div>
                            <div class="popular-tag">Keyborad</div>
                            <div class="popular-tag">Computers</div>
                            <div class="popular-tag">Monitor</div>
                            <div class="popular-tag">Monitor</div>
                            <div class="popular-tag">Monitor</div> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 product-listing">

            <div class="row g-3">
                
                <?php foreach($products as $product) { ?>
                    <div class="col-lg-custom col-md-4 col-sm-6">
                        <div class="product-card text-center p-3">
                            <a href="<?= base_url($product['product_slug']); ?>" class="product-title"><?= $product['product_name'] ?></a>
                            <div class="rating py-2 mb-2">
                                <center><i class="bi bi-star-fill"></i> <i class="bi bi-star-fill"></i> <i
                                        class="bi bi-star-half"></i> <i class="bi bi-star"></i> <i class="bi bi-star"></i>
                                    (2)</center>
                            </div>
                            <a href="<?= base_url($product['product_slug']); ?>" class="product-image">
                                <img src="<?= base_url($product['product_image']); ?>" alt="Product">
                            </a>
                            <div class="product-brand-sku"> 
                                <span class="brand">Brand : <span><?= $product['brand_name'] ?></span> </span>
                                <span class="sku-id">SKU : <span><?= $product['p_vpn'] ?></span> </span>
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
                                <a href="javascript:void(0);" class="wishlist-btn "><i class="bi bi-heart"></i></a>
                            </div>
                            <div class="buy-now"><a href="javascript:void(0);" pid="<?= $product['product_id'] ?>" ppn="<?= $product['product_name'] ?>"  ppp="<?= $product['pv_price'] ?>"  ppimage="<?= isset($product['product_image']) ? $product['product_image'] : '' ?>"  pbaseurl = "<?= base_url() ?>"  cat_id="<?= $product['category_id'] ?>"
                                onclick="add_to_cart(this)" class="buynow-btn">Buy Now</a></div>
                            <?php if(isset($product['pv_status']) && $product['pv_status'] == 0){ ?>
                                <div class="out-of-stock">Out of Stock</div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <br>
            <!-- <div class="row">
                
                <ul class="pagination">
                    <li class="disabled"><a href="#"><i class="bi bi-chevron-left"></i></a></li>

                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#"><i class="bi bi-chevron-right"></i></a></li> 
                </ul>

            </div> -->

             <!-- Pagination Links -->
            <div>
                <?= $pager ?>
            </div>
            
        </div>

    </div>
</section>


<?= $this->include('frontend/layouts/footer') ?>

<script>
     document.querySelectorAll(".category-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let params = new URLSearchParams(window.location.search);
            let selectedValues = [];

            // Collect checked categories
            document.querySelectorAll(".category-checkbox:checked").forEach(checkedBox => {
                selectedValues.push(checkedBox.value);
            });

            if (selectedValues.length > 0) {
                params.set("categories", selectedValues.join("+"));
            } else {
                params.delete("categories");
            }

            // Reload the page with the new URL
            var newUrl = window.location.pathname + "?" + params.toString();
            newUrl = decodeURIComponent(newUrl);
            // console.log(newUrl); 
            window.location.href = newUrl;
        });
    });
    
     document.querySelectorAll(".brand-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let params = new URLSearchParams(window.location.search);
            let selectedValues = [];

            // Collect checked brands
            document.querySelectorAll(".brand-checkbox:checked").forEach(checkedBox => {
                selectedValues.push(checkedBox.value);
            });

            if (selectedValues.length > 0) {
                params.set("brands", selectedValues.join("+"));
            } else {
                params.delete("brands");
            }

            // Reload the page with the new URL
            var newUrl = window.location.pathname + "?" + params.toString();
            newUrl = decodeURIComponent(newUrl);
            // console.log(newUrl); 
            window.location.href = newUrl;
        });
    });

     document.querySelectorAll(".attribute-set-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let params = new URLSearchParams(window.location.search);
            let selectedValues = [];

            // Collect checked brands
            document.querySelectorAll(".attribute-set-checkbox:checked").forEach(checkedBox => {
                selectedValues.push(checkedBox.value);
            });

            if (selectedValues.length > 0) {
                params.set("attribute_set", selectedValues.join("+"));
            } else {
                params.delete("attribute_set");
            }

            // Reload the page with the new URL
            var newUrl = window.location.pathname + "?" + params.toString();
            newUrl = decodeURIComponent(newUrl);
            // console.log(newUrl); 
            window.location.href = newUrl;
        });
    });
    
     document.querySelectorAll(".tag-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let params = new URLSearchParams(window.location.search);
            let selectedValues = [];

            // Collect checked brands
            document.querySelectorAll(".tag-checkbox:checked").forEach(checkedBox => {
                selectedValues.push(checkedBox.value);
            });

            if (selectedValues.length > 0) {
                params.set("tags", selectedValues.join("+"));
            } else {
                params.delete("tags");
            }

            // Reload the page with the new URL
            var newUrl = window.location.pathname + "?" + params.toString();
            newUrl = decodeURIComponent(newUrl);
            // console.log(newUrl); 
            window.location.href = newUrl;
        });
    });

    $('#minRange').change(function() {
        let params = new URLSearchParams(window.location.search);
        
        var selectedValue = $(this).val();

        if (selectedValue > 0) {
            params.set("minPrice", selectedValue);
        } else {
            params.delete("minPrice");
        }

        // Reload the page with the new URL
        window.location.href = window.location.pathname + "?" + params.toString();
      
    });

    $('#maxRange').change(function() {
        let params = new URLSearchParams(window.location.search);
        
        var selectedValue = $(this).val();

        if (selectedValue > 0) {
            params.set("maxPrice", selectedValue);
        } else {
            params.delete("maxPrice");
        }

        // Reload the page with the new URL
        window.location.href = window.location.pathname + "?" + params.toString();
      
    });

    $('#filter_by').change(function() {
        let params = new URLSearchParams(window.location.search);
        
        var selectedValue = $(this).val();

        if (selectedValue) {
            params.set("filterBy", selectedValue);
        } else {
            params.delete("filterBy");
        }

        // Reload the page with the new URL
        window.location.href = window.location.pathname + "?" + params.toString();
      
    });
    $('#search_btn').click(function() {
        let params = new URLSearchParams(window.location.search);
        
        var selectedValue = $('.searchinput').val();

        if (selectedValue) {
            params.set("search", selectedValue);
        } else {
            params.delete("search");
        }

        // Reload the page with the new URL
        window.location.href = window.location.pathname + "?" + params.toString();
      
    });
    
        
</script>