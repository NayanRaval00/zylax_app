<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= isset($product_details[0]['id']) ? 'Update' : 'Add' ?> Product</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">

                        <!-- Display Success Message -->
                        <?php if(session()->getFlashdata('status') == "success" ): ?>
                            <!-- <div class="alert alert-success" role="alert">
                                <?= session()->getFlashdata('message'); ?>
                            </div> -->
                        <?php endif; ?>
                        <?php if(session()->getFlashdata('status') == "error" ): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashdata('message'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('validation')): ?>
                            <p style="color: red;"><?= session()->getFlashdata('validation')->listErrors(); ?></p>
                        <?php endif; ?>
                        
                        <!-- form start -->
                        <form class="form-horizontal" action="<?= base_url('admin/product/add_product'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group col-md-12">
                                    <label for="pro_input_name" class="col-form-label">Name <span class='text-danger text-sm'>*</span> </label>
                                    <input type="text" class="form-control" id="pro_input_name" placeholder="Product Name" name="pro_input_name" value="<?= old('pro_input_name') ?>">
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="pro_input_slug" class="col-form-label">Slug <span class='text-danger text-sm'>*</span> </label>
                                    <input type="text" class="form-control" id="pro_input_slug" placeholder="Product Slug" name="pro_input_slug" value="<?= old('pro_input_slug') ?>">
                                </div>

                                <!-- <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="tags">Tags <small>( These tags help you in search result )</small></label>
                                        <input name='tags' class='tags' id='tags' placeholder="AC, Cooler,Smartphones,etc" value="<?= (isset($product_details[0]['tags']) && !empty($product_details[0]['tags'])) ? $product_details[0]['tags'] : "" ?>" />
                                    </div>
                                </div> -->

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="category_parent">Select Tags</label>
                                        <select id="category_parent" name="tags[]" multiple>                            
                                            <?php getProductTags(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="category_id" class="col-form-label">Select Category <span class='text-danger text-sm'>*</span></label>
                                        <select class='form-control comman_select2' name='category_id'>
                                            <option value=''>Select Category</option>
                                            <?php 
                                            $selectedCate = old('category_id');
                                            getCategories(0, '-', $selectedCate); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brand" class="col-form-label">Brand</label>
                                        <select class=" col-md-12  form-control comman_select2" id="admin_brand_list" name="brand">
                                            <option value="">Select Brand</option>
                                            <?php
                                                foreach ($brands as $row) {
                                                $selectedBrand = old('brand');
                                            ?>
                                                <option value="<?= $row['id'] ?>" <?= (isset($selectedBrand) && $selectedBrand == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">

                                    <div class="form-group row text-center">
                                        <div class="col-md-12">
                                            <label for="is_feature" class="col-form-label">Is Feature ?</label>
                                            <input type="checkbox" name="is_feature" id="is_feature" data-bootstrap-switch data-off-color="danger" data-on-color="success" 
                                            <?php $is_feature = old('is_feature');
                                            echo (isset($is_feature) && $is_feature == true) ? 'checked' : '' ?>>
                                        </div>  
                                        <div class="col-md-12">
                                            <label for="is_discount" class="col-form-label">Is discount ?</label>
                                            <input type="checkbox" name="is_discount" id="is_discount" data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                            <?php $is_discount = old('is_discount');
                                            echo (isset($is_discount) && $is_discount == true) ? 'checked' : '' ?>>
                                        </div>  
                                        <div class="col-md-12">
                                            <label for="is_hot_deal" class="col-form-label">Is Hot Deal ?</label>
                                            <input type="checkbox" name="is_hot_deal" id="is_hot_deal" data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                            <?php $is_hot_deal = old('is_hot_deal');
                                            echo (isset($is_hot_deal) && $is_hot_deal == true) ? 'checked' : '' ?>>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="is_hot_deal" class="col-form-label">Is best seller ?</label>
                                            <input type="checkbox" name="is_best_seller" id="is_best_seller" data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                            <?php $is_best_seller = old('is_best_seller');
                                            echo (isset($is_best_seller) && $is_best_seller == true) ? 'checked' : '' ?>>
                                        </div>    
                                    </div>

                                       


                                    </div>
                                </div>

                                <div class="form-group row">
                                    <!-- Model -->
                                    <div class="col-md-4">
                                        <label for="model" class="col-form-label">Model</label>
                                        <input type="text" class="col-md-12 form-control" name="model" id="model" value="<?= old('model') ?>" placeholder='Model'>
                                    </div>
                                    <!-- VPN -->
                                    <div class="col-md-4">
                                        <label for="vpn" class="col-form-label">VPN</label>
                                        <input type="text" class="col-md-12 form-control" name="vpn" id="vpn" value="<?= old('vpn') ?>" placeholder='VPN'>
                                    </div>
                                    <!-- GTIN -->
                                    <div class="col-md-4">
                                        <label for="gtin" class="col-form-label">GTIN</label>
                                        <input type="text" class="col-md-12 form-control" name="gtin" id="gtin" value="<?= old('gtin') ?>" placeholder='GTIN'>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="row col mt-3">
                                            <!-- <div class="col-md-4">
                                                <label for="tax" class="col-form-label">Tax</label>
                                                <select name="tax" class="form-control w-100">
                                                    <option value="">Select Tax</option>
                                                    <?php
                                                        foreach ($tax_details as $row) {
                                                    ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['title'] . ' ' . '(' . $row['percentage'] . '%)' ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 made-in-select">
                                                <label for="made_in" class="col-form-label">Made In</label>
                                                <select class="col-md-12 form-control" id="country_list" name="made_in">
                                                <?php
                                                    foreach ($countries_list as $countries) {
                                                ?>
                                                    <option value='<?= $countries['id'] ?>'><?= $countries['name'] ?></option>
                                                <?php
                                                    }
                                                ?>
                                                </select>
                                            </div> -->
                                            
                                            <!-- <div class="col-md-4 warranty_period <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                <label for="warranty_period" class="col-form-label">Warranty Period</label>
                                                <input type="text" class="col-md-12 form-control" id="warranty_period" name="warranty_period" value="<?= old('warranty_period') ?>" placeholder='Warranty Period if any'>
                                            </div>
                                            <div class="col-md-4 guarantee_period <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                <label for="guarantee_period" class="col-form-label">Guarantee Period</label>
                                                <input type="text" class="col-md-12 form-control" id="guarantee_period" name="guarantee_period" value="<?= old('guarantee_period') ?>" placeholder='Guarantee Period if any'>
                                            </div> -->
                                        </div>

                                        <!-- <div class="row col mt-3 pickup_locations <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                            <div class="col-md-8 standdard_shipping">
                                                <label for="shipping_type" class="col-form-label">For standdard shipping</label>
                                                <select class='form-control shiprocket_type' name="pickup_location" id="pickup_location">
                                                    <option value="">Select Pickup Location</option>
                                                    <option value="no">No Pickup</option>                                                   
                                                </select>
                                            </div>
                                        </div> -->

                                        <!-- <div class="row col mt-3">
                                            <div class="col-md-3 col-xs-6">
                                                <label for="is_prices_inclusive_tax" class="col-form-label">Tax included in prices?</label>
                                                <input type="checkbox" name="is_prices_inclusive_tax" id="is_prices_inclusive_tax" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes" data-off-text="No"
                                                <?php $is_prices_inclusive_tax = old('is_prices_inclusive_tax');
                                                echo (isset($is_prices_inclusive_tax) && $is_prices_inclusive_tax == true) ? 'checked' : '' ?>>
                                            </div>
                                            <?php if (isset($payment_method['cod_method']) && $payment_method['cod_method'] == 1) { ?>
                                                <div class="col-md-2 col-xs-6 cod_allowed <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                    <label for="cod_allowed" class="col-form-label">Is COD allowed?</label>
                                                    <input type="checkbox" name="cod_allowed" id="cod_allowed" <?= (isset($product_details[0]['cod_allowed']) && $product_details[0]['cod_allowed'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                                    <?php $cod_allowed = old('cod_allowed');
                                                    echo (isset($cod_allowed) && $cod_allowed == true) ? 'checked' : '' ?>>
                                                </div>
                                            <?php }
                                            ?>
                                            <div class="col-md-2 col-xs-6 is_returnable <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                <label for="is_returnable" class="col-form-label">IS Returnable ?</label>
                                                <input type="checkbox" name="is_returnable" id="is_returnable" <?= (isset($product_details[0]['is_returnable']) && $product_details[0]['is_returnable'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                                <?php $is_returnable = old('is_returnable');
                                                echo (isset($is_returnable) && $is_returnable == true) ? 'checked' : '' ?>>
                                            </div>
                                            <div class="col-md-2 col-xs-6 is_cancelable <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                <label for="is_cancelable" class="col-form-label">Is cancelable ? </label>
                                                <input type="checkbox" name="is_cancelable" id="is_cancelable" class="switch" <?= (isset($product_details[0]['is_cancelable']) && $product_details[0]['is_cancelable'] == '1') ? 'Checked' : ''; ?> data-bootstrap-switch data-off-color="danger" data-on-color="success"
                                                <?php $is_cancelable = old('is_cancelable');
                                                echo (isset($is_cancelable) && $is_cancelable == true) ? 'checked' : '' ?>>
                                            </div>
                                            <div class="col-md-3 col-xs-6 <?= (isset($product_details[0]['is_cancelable']) && $product_details[0]['is_cancelable'] == 1) ? '' : 'collapse' ?>" id='cancelable_till'>
                                                <label for="cancelable_till" class="col-form-label">Till which status ? <span class='text-danger text-sm'>*</span></label>
                                                <input type="text" class="form-control" name="cancelable_till" placeholder="cancelable_till" id="cancelable_till" value="received" disabled>
                                                <input type="hidden" class="form-control" name="cancelable_till" placeholder="cancelable_till" id="cancelable_till" value="received">
                                            </div>
                                        </div>

                                        <div class="row col mt-3 is_attachment_required <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                            <div class="col-md-4  is_attachment_required d-flex justify-content-between<?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                <label for="is_attachment_required" class="col-form-label is_attachment_required">Is Attachment Required ?</label>
                                                <a class=" form-switch  mr-1 mb-1" title="Deactivate" href="javascript:void(0)">
                                                    <input type="checkbox" class="form-check-input " role="switch" id="is_attachment_required" name="is_attachment_required" <?= (isset($product_details[0]['is_attachment_required']) && $product_details[0]['is_attachment_required'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" />
                                                </a>
                                            </div>
                                        </div> -->

                                        <div class="row col mt-3">

                                            <div class="col pt-4 pb-4">
                                                <div class="form-group col-sm-12">
                                                    <label for="image">Main Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : 180 x 180 pixels)</small></label>
                                                    <div class="col-sm-6">
                                                        <input type="file" id="image" name="image" class="form-control"/>
                                                    </div>                                                   
                                                </div>
                                                <div class="form-group">
                                                    <label for="other_images">Other Images <small>(Recommended Size : 180 x 180 pixels)</small></label>
                                                    <div class="col-sm-6">
                                                        <input type="file" id="other_images" name="other_images[]" class="form-control" multiple/>
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group d-flex">
                                                    <div class="form-group col-md-6">
                                                        <label for="video_type" class="col-form-label">Video Type</label>
                                                        <select class='form-control comman_select2' name='video_type' id='video_type'>
                                                            <option value='' <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == '' || $product_details[0]['video_type'] == NULL)) ? 'selected' : ''; ?>>None</option> -->
                                                            <!-- <option value='self_hosted' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'self_hosted') ? 'selected' : ''; ?>>Self Hosted</option> -->
                                                            <!-- <option value='youtube' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'youtube') ? 'selected' : ''; ?>>Youtube</option>
                                                            <option value='vimeo' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'vimeo') ? 'selected' : ''; ?>>Vimeo</option>
                                                        </select>
                                                    </div> -->
                                                    <!-- <div class="col-md-6 <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'youtube' ||  $product_details[0]['video_type'] == 'vimeo')) ? '' : 'd-none'; ?>" id="video_link_container">
                                                        <label for="video" class="col-form-label">Video Link <span class='text-danger text-sm'>*</span></label>
                                                        <input type="text" class='form-control' name='video' id='video' value="<?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'youtube' || $product_details[0]['video_type'] == 'vimeo')) ? $product_details[0]['video'] : ''; ?>" placeholder="Paste Youtube / Vimeo Video link or URL here">
                                                    </div> -->
                                                    <!-- <div class="col-md-6 mt-2 <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'self_hosted')) ? '' : 'd-none'; ?>" id="video_media_container">
                                                        <label for="image" class="ml-2">Video <span class='text-danger text-sm'>*</span></label>
                                                        <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='pro_input_video' data-isremovable='1' data-media_type='video' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                                        <?php if (isset($product_details[0]['id']) && !empty($product_details[0]['id']) && isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'self_hosted') { ?>
                                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                            <div class="container-fluid row image-upload-section ">
                                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                                    <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= base_url('assets/admin/images/video-file.png') ?>" alt="Product Video" title="Product Video"></div>
                                                                    <input type="hidden" name="pro_input_video" value='<?= $product_details[0]['video'] ?>'>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="container-fluid row image-upload-section">
                                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                    </div> -->
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="form-group  col-md-12 mb-3">
                                        <h3 class="card-title">Additional Info</h3>
                                        <div class="col-12 row additional-info existing-additional-settings vvv">
                                                        <div class="row mt-4 col-md-12 ">
                                                            <nav class="w-100">
                                                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                                                    <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a>
                                                                    <a class="nav-item nav-link" id="tab-for-general-seo-section" data-toggle="tab" href="#seo-section-settings" role="tab" aria-controls="general-seo-section" aria-selected="true">SEO Configuration</a>
                                                                    <!-- <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a>
                                                                    <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Related Products</a>
                                                                    <a class="nav-item nav-link disabled product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Product Options</a> -->
                                                                </div>
                                                            </nav>
                                                            <div class="tab-content p-3 col-md-12" id="nav-tabContent">
                                                                <div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">

                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="simple_price">Price:</label>
                                                                            <div class="">
                                                                                <input type="number" name="simple_price" id="simple_price" class="form-control stock-simple-mustfill-field price" min='0' step="0.01" value="<?= old('simple_price') ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label for="simple_rrp">RRP:</label>
                                                                            <div class="">
                                                                                <input type="number" name="simple_rrp" id="simple_rrp" class="form-control" min='1' step="0.01" value="<?= old('simple_rrp') ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="form-group row mt-3" id="product-dimensions">
                                                                        <div class="col-md-6">
                                                                            <label for="weight" class="control-label col-md-12"><small>(These are the product parcel's dimentions.)</small></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group d-flex" id="product-dimensions">
                                                                        <div class="col-3">
                                                                            <label for="weight" class="control-label col-md-12">Weight <small>(kg)</small> <span class='text-danger text-xs'>*</span></label>
                                                                            <input type="number" class="form-control" name="weight" placeholder="Weight" id="weight" value="<?= old('weight') ?>" step="0.01" min='0.01'>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label for="height" class="control-label col-md-12">Height <small>(cms)</small></label>
                                                                            <input type="number" class="form-control" name="height" placeholder="Height" id="height" value="<?= old('height') ?>" step="0.01" min='0.01'>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small></label>
                                                                            <input type="number" class="form-control" name="breadth" placeholder="Breadth" id="breadth" value="<?= old('breadth') ?>" step="0.01" min='0.01'>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label for="length" class="control-label col-md-12">Length <small>(cms)</small></label>
                                                                            <input type="number" class="form-control" name="length" placeholder="Length" id="length" value="<?= old('length') ?>" step="0.01" min='0.01'>
                                                                        </div>
                                                                    </div> -->

                                                                    <!-- <div class="form-group  simple_stock_management mx-2">
                                                                        <div class="d-flex">
                                                                            <input type="checkbox" name="simple_stock_management_status" id="simple_stock_management_status" class="align-middle simple_stock_management_status">
                                                                            <label class="align-middle m-0 mx-2" for="simple_stock_management_status">Enable Stock Management</label>
                                                                        </div>
                                                                    </div> -->

                                                                    <div class="d-flex">
                                                                        <!-- <div class="col col-xs-12">
                                                                            <label class="control-label" for="product_sku">SKU :</label>
                                                                            <input type="text" name="product_sku" id="product_sku" class="col form-control simple-pro-sku">
                                                                        </div> -->
                                                                        <div class="col col-xs-12">
                                                                            <label for="product_total_stock" class="control-label">Total Stock :</label>
                                                                            <input type="number" min="1" name="product_total_stock" id="product_total_stock" class="col form-control stock-simple-mustfill-field" value="<?= old('product_total_stock') ?>">
                                                                        </div>
                                                                        <div class="col col-xs-12">
                                                                            <?php 
                                                                                $selectedsimple_product_stock_status = old('simple_product_stock_status');
                                                                            ?>
                                                                            <label class="control-label">Stock Status :</label>
                                                                            <select type="text" class="col form-control stock-simple-mustfill-field comman_select2" id="simple_product_stock_status" name="simple_product_stock_status">
                                                                                <option value="1" <?= (isset($selectedsimple_product_stock_status) && $selectedsimple_product_stock_status == "1") ? 'selected' : '' ?>>In Stock</option>
                                                                                <option value="0" <?= (isset($selectedsimple_product_stock_status) && $selectedsimple_product_stock_status == "0") ? 'selected' : '' ?>>Out Of Stock</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                        
                                                                </div>
                                                                <div class="tab-pane fade" id="seo-section-settings" role="tabpanel" aria-labelledby="seo-section-settings-tab">
                                                                    <h4 class="bg-light m-0 px-2 py-3">SEO Configuration</h4>

                                                                    <div class="d-flex bg-light">
                                                                        <div class="form-group col-sm-6">
                                                                            <label for="seo_page_title" class="form-label form-label-sm d-flex">
                                                                                SEO Page Title
                                                                            </label>
                                                                            <input type="text" class="form-control" id="seo_page_title"
                                                                                placeholder="SEO Page Title" name="seo_page_title"
                                                                                value="<?= old('seo_page_title') ?>">
                                                                        </div>

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="seo_meta_keywords" class="form-label form-label-sm d-flex">
                                                                                SEO Meta Keywords
                                                                            </label>
                                                                            <input class='tags bg-white' id='seo_meta_keywords' placeholder="SEO Meta Keywords" name="seo_meta_keywords" value="<?= old('seo_meta_keywords') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex bg-light">

                                                                        <div class="form-group col-sm-6">
                                                                            <label for="seo_meta_description" class="form-label form-label-sm d-flex">
                                                                                SEO Meta Description
                                                                            </label>
                                                                            <textarea class="form-control" id="seo_meta_description"
                                                                                placeholder="SEO Meta Keywords" name="seo_meta_description"><?= output_escaping(old('seo_meta_description')) ?></textarea>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="seo_og_image">SEO Open Graph Image <small>(Recommended Size : 131 x 131 pixels)</small></label>
                                                                                <div class="col-sm-12">
                                                                                    <input type="file" id="seo_og_image" name="seo_og_image" class="form-control"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab">
                                                                    <div class="info col-12 p-3 d-none" id="note">
                                                                        <div class=" col-12 d-flex align-center"> <strong>Note : </strong>
                                                                            <input type="checkbox" checked="checked" class="ml-3 my-auto custom-checkbox" disabled> <span class="ml-3">check if the attribute is to be used for variation </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <a href="javascript:void(0);" id="add_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm">Add Attributes</a>
                                                                        <a href="javascript:void(0);" id="save_attributes" class="btn btn-block btn-outline-primary col-md-2 float-right m-2 btn-sm d-none">Save Attributes</a>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <div id="attributes_process">
                                                                        <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-attributes-added">
                                                                            <div class="col-md-12 text-center">No Product Attribures Are Added !</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="product-variants-tab">
                                                                    <div class="clearfix"></div>
                                                                    <div class="form-group text-center row my-auto p-2 border rounded bg-gray-light col-md-12 no-variants-added">
                                                                        <div class="col-md-12 text-center">No Product Variations Are Added !</div>
                                                                    </div>
                                                                    <div id="variants_process" class="ui-sortable"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                              
                                    </div>
                                    
                                    <div class="card-body pad">
                                        <div class="form-group col-md-12">
                                            <label for="short_description">Short Description <span class='text-danger text-sm'>*</span></label>
                                            <div class="mb-3">
                                                <textarea type="text" class="form-control addr_editor" id="short_description" placeholder="Product Short Description" name="short_description">
                                                    <?php
                                                        $short_description = old('short_description');
                                                        echo isset($short_description) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $short_description)) : "";
                                                    ?>
                                                </textarea>
                                            </div>
                                            <label for="pro_input_description">Description </label>
                                            <div class="mb-3">
                                                <textarea name="pro_input_description" class="textarea addr_editor" id="pro_input_description" placeholder="Place some text here">
                                                    <?php
                                                        $pro_input_description = old('pro_input_description');
                                                        echo isset($pro_input_description) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $pro_input_description)) : "";
                                                    ?>
                                                </textarea>
                                            </div>
                                            <label for="extra_input_description">Extra Description </label>
                                            <div class="mb-3">
                                                <textarea name="extra_input_description" id="extra_input_description" class="textarea addr_editor" placeholder="Place some text here">
                                                    <?php
                                                        $extra_input_description = old('extra_input_description');
                                                        echo isset($extra_input_description) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $extra_input_description)) : "";
                                                    ?>
                                                </textarea>
                                            </div>
                                            <label for="pro_input_specification">Specification </label>
                                            <div class="mb-3">
                                                <textarea name="pro_input_specification" class="textarea addr_editor" id="pro_input_specification" placeholder="Place some text here">
                                                    <?php
                                                        $pro_input_specification = old('pro_input_specification');
                                                        echo isset($pro_input_specification) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $pro_input_specification)) : "";
                                                    ?>
                                                </textarea>
                                            </div>
                                            <!-- <label for="pro_input_specification">Configure Me </label>
                                            <div class="mb-3">
                                                <label class="radioopt"><input type="radio" name="configure_me" value="1"> Yes</label>
                                                <label class="radioopt"><input type="radio" name="configure_me" value="0" checked> No</label>
                                            </div>
                                            <label for="pro_input_specification">Submit To Google </label>
                                            <div class="mb-3">
                                                <label class="radioopt"><input type="radio" name="submit_to_google" value="1"> Yes</label>
                                                <label class="radioopt"><input type="radio" name="submit_to_google" value="0" checked> No</label>
                                            </div> -->

                                            <div class="form-group">
                                                <button type="reset" class="btn btn-warning">Reset</button>
                                                <button type="submit" class="btn btn-success"><?= (isset($product_details[0]['id'])) ? 'Update Product' : 'Add Product' ?></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>