<?php $db = \Config\Database::connect(); ?>
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
                        <form class="form-horizontal" action="<?= base_url('admin/product/update_product'); ?>" method="POST" enctype="multipart/form-data">

                            <?php if (isset($product_details[0]['id'])) { ?>
                                <input type="hidden" name="edit_product_id" id="edit_product_id" value="<?= (isset($product_details[0]['id'])) ? $product_details[0]['id'] : "" ?>">
                                <input type="hidden" name="edit_product_variants_id" value="<?= (isset($product_variants[0]['id'])) ? $product_variants[0]['id'] : "" ?>">
                                <input type="hidden" name="edit_product_image" value="<?= (isset($product_details[0]['image'])) ? $product_details[0]['image'] : "" ?>">
                                <input type="hidden" name="edit_product_tags" value="<?= $selected_tags ?>">
                                <input type="hidden" id="product_options_category_id" value="">
                                <input type="hidden" id="product_variation_category_id" value="">
                                <input type="hidden" id="remove_attributes_ids" value="">
                            <?php } ?>

                            <div class="card-body">


                                    <div class="form-group  col-md-12 mb-3">
                                        <!-- <h3 class="card-title">Additional Info</h3> -->
                                        <div class="col-12 row additional-info existing-additional-settings vvv">
                                            <div class="row mt-4 col-md-12 ">
                                                <nav class="w-100">
                                                    <div class="nav nav-tabs" id="product-tab" role="tablist">
                                                        <a class="nav-item nav-link active" id="tab-for-general-price" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general-price" aria-selected="true">General</a>
                                                        <a class="nav-item nav-link" id="tab-for-general-seo-section" data-toggle="tab" href="#seo-section-settings" role="tab" aria-controls="general-seo-section" aria-selected="true">SEO Configuration</a>
                                                        <a class="nav-item nav-link product-attributes" id="tab-for-attributes" data-toggle="tab" href="#product-attributes" role="tab" aria-controls="product-attributes" aria-selected="false">Attributes</a>
                                                        <a class="nav-item nav-link related-products" id="tab-for-related-products" data-toggle="tab" href="#related-products" role="tab" aria-controls="related-products" aria-selected="false">Related Products</a>
                                                        <a class="nav-item nav-link feature-list" id="tab-for-feature-list" data-toggle="tab" href="#feature-list" role="tab" aria-controls="feature-list" aria-selected="false">Feature List</a>
                                                        <a class="nav-item nav-link product-options" id="tab-for-product-options" data-toggle="tab" href="#product-options" role="tab" aria-controls="product-options" aria-selected="false">Product Options</a>
                                                        <a class="nav-item nav-link product-variation" id="tab-for-product-variation" data-toggle="tab" href="#product-variation" role="tab" aria-controls="product-variation" aria-selected="false">Product Variation</a>
                                                    </div>
                                                </nav>
                                                <div class="tab-content p-3 col-md-12" id="nav-tabContent">
                                                    <div class="tab-pane fade active show" id="general-settings" role="tabpanel" aria-labelledby="general-settings-tab">

                                                                        
                                                    <div class="form-group col-md-12">
                                                        <label for="pro_input_name" class="col-form-label">Name <span class='text-danger text-sm'>*</span> </label>
                                                        <input type="text" class="form-control" id="pro_input_name" placeholder="Product Name" name="pro_input_name" value="<?= (isset($product_details[0]['name'])) ? htmlspecialchars($product_details[0]['name'], ENT_QUOTES, 'UTF-8') : "" ?>">
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <label for="pro_input_slug" class="col-form-label">Slug <span class='text-danger text-sm'>*</span> </label>
                                                        <input type="text" class="form-control" id="pro_input_slug" placeholder="Product Slug" name="pro_input_slug" value="<?= (isset($product_details[0]['slug'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product_details[0]['slug'])) : "" ?>">
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
                                                            <?php getProductTagsMultiple($selected_tags); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label for="category_id" class="col-form-label">Select Category <span class='text-danger text-sm'>*</span></label>
                                                            <select class='form-control comman_select2' name='category_id'>
                                                                <option value=''>Select Category</option>
                                                                <?php getCategories(0, '-', $product_details[0]['category_id']); ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="brand" class="col-form-label">Brand</label>
                                                            <select class=" col-md-12  form-control comman_select2" id="admin_brand_list" name="brand">
                                                                <option value="">Select Brand</option>
                                                                <?php
                                                                    foreach ($brands as $row) {
                                                                ?>
                                                                    <option value="<?= $row['id'] ?>" <?php if($row['id'] == $product_details[0]['brand']){ echo "selected"; } ?>><?= $row['name'] ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">

                                                            <div class="form-group row text-center">
                                                                <div class="col-md-12">
                                                                    <label for="is_feature" class="col-form-label">Is Feature ?</label>
                                                                    <input type="checkbox" name="is_feature" id="is_feature" <?= (isset($product_details[0]['is_feature']) && $product_details[0]['is_feature'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                </div>  
                                                                <div class="col-md-12">
                                                                    <label for="is_discount" class="col-form-label">Is discount ?</label>
                                                                    <input type="checkbox" name="is_discount" id="is_discount" <?= (isset($product_details[0]['is_discount']) && $product_details[0]['is_discount'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                </div>  
                                                                <div class="col-md-12">
                                                                    <label for="is_hot_deal" class="col-form-label">Is Hot Deal ?</label>
                                                                    <input type="checkbox" name="is_hot_deal" id="is_hot_deal" <?= (isset($product_details[0]['is_hot_deal']) && $product_details[0]['is_hot_deal'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                </div>  
                                                                <div class="col-md-12">
                                                                    <label for="is_hot_deal" class="col-form-label">Is best seller ?</label>
                                                                    <input type="checkbox" name="is_best_seller" id="is_best_seller" <?= (isset($product_details[0]['is_best_seller']) && $product_details[0]['is_best_seller'] == '1') ? 'Checked' : '' ?>   data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                </div>  
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <!-- Model -->
                                                        <div class="col-md-4">
                                                            <label for="model" class="col-form-label">Model</label>
                                                            <input type="text" class="col-md-12 form-control" name="model" id="model" value="<?= (isset($product_details[0]['model'])) ? $product_details[0]['model'] : '' ?>" placeholder='Model'>
                                                        </div>
                                                        <!-- VPN -->
                                                        <div class="col-md-4">
                                                            <label for="vpn" class="col-form-label">VPN</label>
                                                            <input type="text" class="col-md-12 form-control" name="vpn" id="vpn" value="<?= (isset($product_details[0]['vpn'])) ? $product_details[0]['vpn'] : '' ?>" placeholder='VPN'>
                                                        </div>
                                                        <!-- GTIN -->
                                                        <div class="col-md-4">
                                                            <label for="gtin" class="col-form-label">GTIN</label>
                                                            <input type="text" class="col-md-12 form-control" name="gtin" id="gtin" value="<?= (isset($product_details[0]['gtin'])) ? $product_details[0]['gtin'] : '' ?>" placeholder='GTIN'>
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
                                                                </div>-->
                                                                
                                                                <!-- <div class="col-md-4 warranty_period <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                                    <label for="warranty_period" class="col-form-label">Warranty Period</label>
                                                                    <input type="text" class="col-md-12 form-control" id="warranty_period" name="warranty_period" value="<?= (isset($product_details[0]['warranty_period'])) ? $product_details[0]['warranty_period'] : "" ?>" placeholder='Warranty Period if any'>
                                                                </div>
                                                                <div class="col-md-4 guarantee_period <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                                    <label for="guarantee_period" class="col-form-label">Guarantee Period</label>
                                                                    <input type="text" class="col-md-12 form-control" id="guarantee_period" name="guarantee_period" value="<?= (isset($product_details[0]['guarantee_period'])) ? $product_details[0]['guarantee_period'] : "" ?>" placeholder='Guarantee Period if any'>
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
                                                                    <input type="checkbox" name="is_prices_inclusive_tax" id="is_prices_inclusive_tax" <?= (isset($product_details[0]['is_prices_inclusive_tax']) && $product_details[0]['is_prices_inclusive_tax'] == '1') ? 'checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Yes" data-off-text="No">
                                                                </div>
                                                                <?php if (isset($payment_method['cod_method']) && $payment_method['cod_method'] == 1) { ?>
                                                                    <div class="col-md-2 col-xs-6 cod_allowed <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                                        <label for="cod_allowed" class="col-form-label">Is COD allowed?</label>
                                                                        <input type="checkbox" name="cod_allowed" id="cod_allowed" <?= (isset($product_details[0]['cod_allowed']) && $product_details[0]['cod_allowed'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                    </div>
                                                                <?php }
                                                                ?>
                                                                <div class="col-md-2 col-xs-6 is_returnable <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                                    <label for="is_returnable" class="col-form-label">IS Returnable ?</label>
                                                                    <input type="checkbox" name="is_returnable" id="is_returnable" <?= (isset($product_details[0]['is_returnable']) && $product_details[0]['is_returnable'] == '1') ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                </div>
                                                                <div class="col-md-2 col-xs-6 is_cancelable <?= (isset($product_details[0]['type']) && $product_details[0]['type'] == 'digital_product') ? 'd-none' : '' ?>">
                                                                    <label for="is_cancelable" class="col-form-label">Is cancelable ? </label>
                                                                    <input type="checkbox" name="is_cancelable" id="is_cancelable" class="switch" <?= (isset($product_details[0]['is_cancelable']) && $product_details[0]['is_cancelable'] == '1') ? 'Checked' : ''; ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
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
                                                                        <?php if($product_details[0]['image']): ?>
                                                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                                            <div class="container-fluid row image-upload-section">
                                                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                                                    <div class="image-upload-div">
                                                                                        <img class="img-fluid mb-2" src="<?= base_url().$product_details[0]['image'] ?>" alt="Image Not Found">
                                                                                    </div>
                                                                                    <input type="hidden" name="category_input_image" value="<?= base_url().$product_details[0]['image'] ?>">
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>                                                  
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="other_images">Other Images <small>(Recommended Size : 180 x 180 pixels)</small></label>
                                                                        <div class="col-sm-6">
                                                                            <input type="file" id="other_images" name="other_images[]" class="form-control" multiple/>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if (isset($product_details[0]['id']) && !empty($product_details[0]['id'])) {
                                                                    ?>
                                                                        <div class="container-fluid row image-upload-section mb-4">
                                                                            <?php
                                                                            if (!empty($product_images)) {
                                                                                foreach ($product_images as $row) {
                                                                            ?>
                                                                                    <div class="col-md-3 col-sm-12 shadow bg-white rounded m-3 p-3 text-center grow" id="product_image_<?= $row['id'] ?>">
                                                                                        <div class='image-upload-div'><img src="<?= base_url().$row['image']  ?>" alt="Image Not Found"></div>
                                                                                        <a href="javascript:void(0)" class="delete-product-image m-3" data-id="<?= $row['id'] ?>">
                                                                                            <span class="btn btn-block bg-gradient-danger btn-xs"><i class="far fa-trash-alt "></i> Delete</span>
                                                                                        </a>
                                                                                    </div>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <!-- <div class="form-group d-flex">
                                                                        <div class="form-group col-md-6">
                                                                            <label for="video_type" class="col-form-label">Video Type</label>
                                                                            <select class='form-control comman_select2' name='video_type' id='video_type'>
                                                                                <option value='' <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == '' || $product_details[0]['video_type'] == NULL)) ? 'selected' : ''; ?>>None</option> -->
                                                                                <!-- <option value='self_hosted' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'self_hosted') ? 'selected' : ''; ?>>Self Hosted</option> -->
                                                                                <!-- <option value='youtube' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'youtube') ? 'selected' : ''; ?>>Youtube</option>
                                                                                <option value='vimeo' <?= (isset($product_details[0]['video_type']) &&  $product_details[0]['video_type'] == 'vimeo') ? 'selected' : ''; ?>>Vimeo</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6 <?= (isset($product_details[0]['video_type']) && ($product_details[0]['video_type'] == 'youtube' ||  $product_details[0]['video_type'] == 'vimeo')) ? '' : 'd-none'; ?>" id="video_link_container">
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

                                                        
                                                    </div>

                                                        <div class="form-group row">
                                                            <div class="form-group col-md-6">
                                                                <label for="simple_price">Price:</label>
                                                                <div class="">
                                                                    <input type="number" name="simple_price" id="simple_price" class="form-control stock-simple-mustfill-field price" min='0' step="0.01" value="<?= (isset($product_variants[0]['price']) && !empty($product_variants[0]['price'])) ? $product_variants[0]['price'] : "" ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="simple_rrp">RRP:</label>
                                                                <div class="">
                                                                    <input type="number" name="simple_rrp" id="simple_rrp" class="form-control" min='1' step="0.01" value="<?= (isset($product_variants[0]['rrp']) && !empty($product_variants[0]['rrp'])) ? $product_variants[0]['rrp'] : "" ?>">
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
                                                                <input type="number" class="form-control" name="weight" placeholder="Weight" id="weight" value="<?= (isset($product_variants[0]['weight']) && !empty($product_variants[0]['weight'])) ? $product_variants[0]['weight'] : "" ?>" step="0.01" min='0.01'>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="height" class="control-label col-md-12">Height <small>(cms)</small></label>
                                                                <input type="number" class="form-control" name="height" placeholder="Height" id="height" value="<?= (isset($product_variants[0]['height']) && !empty($product_variants[0]['height'])) ? $product_variants[0]['height'] : "" ?>" step="0.01" min='0.01'>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="breadth" class="control-label col-md-12">Breadth <small>(cms)</small></label>
                                                                <input type="number" class="form-control" name="breadth" placeholder="Breadth" id="breadth" value="<?= (isset($product_variants[0]['breadth']) && !empty($product_variants[0]['breadth'])) ? $product_variants[0]['breadth'] : "" ?>" step="0.01" min='0.01'>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="length" class="control-label col-md-12">Length <small>(cms)</small></label>
                                                                <input type="number" class="form-control" name="length" placeholder="Length" id="length" value="<?= (isset($product_variants[0]['length']) && !empty($product_variants[0]['length'])) ? $product_variants[0]['length'] : "" ?>" step="0.01" min='0.01'>
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
                                                                <input type="text" name="product_sku" id="product_sku" class="col form-control simple-pro-sku" value="<?= (isset($product_variants[0]['sku']) && !empty($product_variants[0]['sku'])) ? $product_variants[0]['sku'] : "" ?>">
                                                            </div> -->
                                                            <div class="col col-xs-12">
                                                                <label for="product_total_stock" class="control-label">Total Stock :</label>
                                                                <input type="number" min="1" name="product_total_stock" id="product_total_stock" class="col form-control stock-simple-mustfill-field" value="<?= (isset($product_variants[0]['stock']) && !empty($product_variants[0]['stock'])) ? $product_variants[0]['stock'] : "" ?>">
                                                            </div>
                                                            <div class="col col-xs-12">
                                                                <label class="control-label">Stock Status :</label>
                                                                <select type="text" class="col form-control stock-simple-mustfill-field comman_select2" id="simple_product_stock_status" name="simple_product_stock_status">
                                                                    <option value="1" <?php if(isset($product_variants[0]['status']) && $product_variants[0]['status'] == 1){ echo "selected"; } ?>>In Stock</option>
                                                                    <option value="0" <?php if(isset($product_variants[0]['status']) && $product_variants[0]['status'] == 0){ echo "selected"; } ?>>Out Of Stock</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="card-body pad">
                                                            <div class="form-group col-md-12">
                                                                <label for="short_description">Short Description <span class='text-danger text-sm'>*</span></label>
                                                                <div class="mb-3">
                                                                    <textarea type="text" class="form-control addr_editor" id="short_description" placeholder="Product Short Description" name="short_description"><?= isset($product_details[0]['short_description']) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product_details[0]['short_description'])) : ""; ?></textarea>
                                                                </div>
                                                                <label for="pro_input_description">Description </label>
                                                                <div class="mb-3">
                                                                    <textarea name="pro_input_description" class="textarea addr_editor" id="pro_input_description" placeholder="Place some text here"><?= (isset($product_details[0]['id'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product_details[0]['description'])) : ''; ?></textarea>
                                                                </div>
                                                                <label for="extra_input_description">Extra Description </label>
                                                                <div class="mb-3">
                                                                    <textarea name="extra_input_description" id="extra_input_description" class="textarea addr_editor" placeholder="Place some text here"><?= (isset($product_details[0]['extra_description']) && !empty($product_details[0]['extra_description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product_details[0]['extra_description'])) : ''; ?></textarea>
                                                                </div>
                                                                <label for="pro_input_specification">Specification </label>
                                                                <div class="mb-3">
                                                                    <textarea name="pro_input_specification" class="textarea addr_editor" id="pro_input_specification" placeholder="Place some text here"><?= (isset($product_details[0]['extra_description']) && !empty($product_details[0]['specification'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $product_details[0]['specification'])) : ''; ?></textarea>
                                                                </div>
                                                                <label for="pro_input_specification">Configure Me </label>
                                                                <div class="mb-3">
                                                                    <label class="radioopt"><input type="radio" name="configure_me" value="1" <?= (isset($product_details[0]['configure_me']) && $product_details[0]['configure_me'] == '1') ? 'checked' : ''; ?>> Yes</label>
                                                                    <label class="radioopt"><input type="radio" name="configure_me" value="0" <?= (isset($product_details[0]['configure_me']) && $product_details[0]['configure_me'] == '0') ? 'checked' : ''; ?>> No</label>
                                                                </div>
                                                                <label for="pro_input_specification">Submit To Google </label>
                                                                <div class="mb-3">
                                                                    <label class="radioopt"><input type="radio" name="submit_to_google" value="1" <?= (isset($product_details[0]['submit_to_google']) && $product_details[0]['submit_to_google'] == '1') ? 'checked' : ''; ?>> Yes</label>
                                                                    <label class="radioopt"><input type="radio" name="submit_to_google" value="0" <?= (isset($product_details[0]['submit_to_google']) && $product_details[0]['submit_to_google'] == '0') ? 'checked' : ''; ?>> No</label>
                                                                </div>
                                                                <label for="pro_input_specification">Status </label>
                                                                <div class="mb-3">
                                                                    <label class="radioopt"><input type="radio" name="status" value="1" <?= (isset($product_details[0]['status']) && $product_details[0]['status'] == '1') ? 'checked' : ''; ?>> Yes</label>
                                                                    <label class="radioopt"><input type="radio" name="status" value="0" <?= (isset($product_details[0]['status']) && $product_details[0]['status'] == '0') ? 'checked' : ''; ?>> No</label>
                                                                </div>

                                                                <div class="form-group">
                                                                    <button type="reset" class="btn btn-warning">Reset</button>
                                                                    <button type="submit" class="btn btn-success"><?= (isset($product_details[0]['id'])) ? 'Update Product' : 'Add Product' ?></button>
                                                                </div>
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
                                                                    value="<?= isset($product_details[0]['seo_page_title']) ? output_escaping($product_details[0]['seo_page_title']) : "" ?>">
                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <label for="seo_meta_keywords" class="form-label form-label-sm d-flex">
                                                                    SEO Meta Keywords
                                                                </label>
                                                                <input class='tags bg-white' id='seo_meta_keywords' placeholder="SEO Meta Keywords" name="seo_meta_keywords" value="<?= isset($product_details[0]['seo_meta_keywords']) ? output_escaping($product_details[0]['seo_meta_keywords']) : "" ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="d-flex bg-light">

                                                            <div class="form-group col-sm-6">
                                                                <label for="seo_meta_description" class="form-label form-label-sm d-flex">
                                                                    SEO Meta Description
                                                                </label>
                                                                <textarea class="form-control" id="seo_meta_description"
                                                                    placeholder="SEO Meta Keywords" name="seo_meta_description"><?= isset($product_details[0]['seo_meta_description']) ? output_escaping($product_details[0]['seo_meta_description']) : "" ?></textarea>
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
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-success"><?= (isset($product_details[0]['id'])) ? 'Update' : 'Add' ?></button>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="related-products" role="tabpanel" aria-labelledby="related-products-tab">

                                                        <div class="d-flex bg-light">
                                                            <div class="form-group col-sm-12">
                                                                <label for="related_products_search" class="form-label form-label-sm d-flex">
                                                                Select Related Product
                                                                </label>
                                                                <select id="related_products_search" class="form-control w-100 comman_select2">
                                                                    <option value="">Search Product</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <table class="table table-striped" id="append_related_products">
                                                            <?php foreach ($related_products as $row) { ?>
                                                                <tr id="related_product_<?= $row['id'] ?>">
                                                                    <td><?= $row['product_name'] ?></td>
                                                                    <td>
                                                                        <a class="btn btn-tool delete-related-product" data-id="<?= $row['id'] ?>"> 
                                                                            <i class="text-danger far fa-times-circle fa-2x "></i> 
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>

                                                    </div>
                                                    <div class="tab-pane fade" id="feature-list" role="tabpanel" aria-labelledby="feature-list-tab">

                                                        <div class="d-flex bg-light">
                                                            <div class="form-group col-sm-12">
                                                                <label for="product_feature" class="form-label form-label-sm d-flex">
                                                                Select Product Feature
                                                                </label>
                                                                <select id="product_feature" class="form-control w-100 comman_select2">
                                                                    <option value="">Select Feature</option>
                                                                    <?php
                                                                    if (isset($product_details[0]['id'])) {
                                                                        foreach ($features_list as $row) {
                                                                    ?>
                                                                        <option value="<?= $row['id'] ?>" data-product="<?= $product_details[0]['id'] ?>"><?= $row['text'] ?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <table class="table table-striped" id="append_product_features">
                                                            <?php foreach ($product_features as $row) { ?>
                                                                <tr id="product_feature_<?= $row['id'] ?>">
                                                                    <td><?= $row['text'] ?></td>
                                                                    <td>
                                                                        <a class="btn btn-tool delete-product-feature" data-id="<?= $row['id'] ?>"> 
                                                                            <i class="text-danger far fa-times-circle fa-2x "></i> 
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>

                                                    </div>
                                                    <div class="tab-pane fade" id="product-options" role="tabpanel" aria-labelledby="product-options-tab">

                                                        <div class="d-flex bg-light">
                                                            <div class="form-group col-sm-12">
                                                                <label for="product-options-category" class="form-label form-label-sm d-flex">
                                                                    Option Category
                                                                </label>
                                                                <select id="product-options-category" class="form-control w-100">
                                                                    <option value="">Search Category</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex bg-light">
                                                            <div class="form-group col-sm-12">
                                                                <label for="product_options" class="form-label form-label-sm d-flex">
                                                                    Product Options
                                                                </label>
                                                                <select id="product_options" class="form-control w-100">
                                                                    <option value="">Search Product</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <table class="table table-striped" id="append_product_options">
                                                            <?php foreach ($product_options as $row) { ?>
                                                                <tr id="product_option_<?= $row['id'] ?>">
                                                                    <td><?= $row['product_name'] ?></td>
                                                                    <td>
                                                                        <a class="btn btn-tool delete-product-option" data-id="<?= $row['id'] ?>"> 
                                                                            <i class="text-danger far fa-times-circle fa-2x "></i> 
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>

                                                    </div>
                                                    <div class="tab-pane fade" id="product-variation" role="tabpanel" aria-labelledby="product-variation-tab">


                                                        <div class="form-group row">
                                                            <div class="col-md-4 mt-3">
                                                                <label for="product_variation_color" class="form-label form-label-sm d-flex">
                                                                    Color <span class="text-danger text-sm">*</span>
                                                                </label>
                                                                <input type="color" class="form-control" id="product_variation_color" placeholder="Color hex" >
                                                            </div>
                                                            <div class="col-md-4 mt-3">
                                                                <label for="product_variation_title" class="form-label form-label-sm d-flex">
                                                                    Title <span class="text-danger text-sm">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" id="product_variation_title" placeholder="Title" >
                                                            </div>
                                                            <div class="col-md-4 mt-3">
                                                                <label for="product_variation_category" class="form-label form-label-sm d-flex">
                                                                    Filter Category <span class="text-danger text-sm">*</span>
                                                                </label>
                                                                <select id="product_variation_category" class="form-control w-100">
                                                                    <option value="">Search Category</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label for="product_variation_product" class="form-label form-label-sm d-flex">
                                                                    Filter Products <span class="text-danger text-sm">*</span>
                                                                </label>
                                                                <select id="product_variation_product" class="form-control w-100">
                                                                    <option value="">Select Product</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mt-5">
                                                                <a href="javascript:void(0);" id="add_varient_product" class="btn btn-block btn-outline-primary float-right btn-sm mt-2" data-product-id="<?= (isset($product_details[0]['id'])) ? $product_details[0]['id'] : "" ?>">Add Varient Product</a>
                                                            </div>
                                                        </div>

                                                        <table class="table table-striped" id="append_product_color_variants">
                                                            <?php foreach ($product_color_variants as $row) { ?>
                                                                <tr id="product_variant_<?= $row['id'] ?>">
                                                                    <td><?= '( Color : ' . $row['color'] . ' ) - ( Label : '. $row['label'] . ' ) - ( Product : '. $row['product_name'] .' )' ?></td>
                                                                    <td>
                                                                        <a class="btn btn-tool delete-product-variant" data-id="<?= $row['id'] ?>"> 
                                                                            <i class="text-danger far fa-times-circle fa-2x "></i> 
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>

                                                    </div>
                                                    <div class="tab-pane fade" id="product-attributes" role="tabpanel" aria-labelledby="product-attributes-tab">

                                                        <!-- existing attributes show -->
                                                        <div id="attribute-container-parent">
                                                            <?php foreach ($selectedAttributes as $attribute) { ?>
                                                                <div class="row attribute-row-parent mt-3">
                                                                    <div class="col-md-3">
                                                                        <select class="attribute-name form-control">
                                                                            <option value="">--Select Attribute--</option>
                                                                            <?php foreach ($attributeSets as $attribute_set) { ?>
                                                                                <option value="<?= $attribute_set['id'] ?>" <?= ($attribute_set['id'] == $attribute['attribute_id']) ? 'selected' : ''; ?>><?= $attribute_set['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <select class="attribute-value form-control">
                                                                            <option value="">--Select Attribute Value--</option>
                                                                            <?php
                                                                                $attribute_set_id = $attribute['attribute_id'];
                                                                                $query = $db->query("SELECT * FROM attributes WHERE attribute_set_id = '$attribute_set_id'");
                                                                                $attribute_values = $query->getResultArray();
                                                                                // print_r($attribute_values); exit;
                                                                                foreach ($attribute_values as $value){
                                                                            ?>
                                                                                <option value="<?= $value['id'] ?>" <?= ($value['id'] == $attribute['attribute_value_id']) ? 'selected' : ''; ?>><?= $value['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group col-md-3">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" class="form-control possible-value" placeholder="Price" value="<?= $attribute['added_attribute_value'] ?>" />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <button type="button" class="btn btn-outline-danger remove-exiting-attribute" data-product-attribute-id="<?= $attribute['id'] ?>">Remove</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        
                                                        <!-- add attributes -->
                                                        <div id="attribute-container">       
                                                            <div class="row attribute-row mt-3">
                                                                <div class="col-md-3">
                                                                    <select class="attribute-name form-control">
                                                                        <option value="">Select Attribute</option>
                                                                        <?php foreach ($attributeSets as $attribute_set) { ?>
                                                                            <option value="<?= $attribute_set['id'] ?>"><?= $attribute_set['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="attribute-value form-control">
                                                                        <option value="">Select Value</option>
                                                                    </select>
                                                                </div>
                                                                <div class="input-group col-md-3 ">
                                                                    <span class="input-group-text">$</span>
                                                                    <input type="number" class="form-control possible-value" placeholder="Price" value="" />
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <!-- <button type="button" class="btn btn-outline-primary add-attribute">Add</button> -->
                                                                    <button type="button" class="btn btn-outline-danger remove-attribute">Remove</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <br>
                                                        <button type="button" class="btn btn-outline-primary add-attribute">Add New Attribute</button>
                                                        <button type="button" id="saveAttributes" class="btn btn-primary">Save Attributes</button>
                                                        
                                                    </div>
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

<script>
  function addRow() {
    const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
    const firstRow = table.rows[0];
    const newRow = table.insertRow();

    // Duplicate the first row
    newRow.innerHTML = firstRow.innerHTML;

    // Reset Select and Input Values
    newRow.querySelector('.product-select').value = "";

    // Set Event on New Button
    newRow.querySelector('button').onclick = addRow;
  }

  function deleteRow(btn) {
    let row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
 }
 
  function addFeatureRow() {
    const table = document.getElementById('productFeatureTable').getElementsByTagName('tbody')[0];
    const firstRow = table.rows[0];
    const newRow = table.insertRow();

    // Duplicate the first row
    newRow.innerHTML = firstRow.innerHTML;

    // Reset Select and Input Values
    newRow.querySelector('.product-select').value = "";

    // Set Event on New Button
    newRow.querySelector('button').onclick = addRow;
  }

  function deleteFeatureRow(btn) {
    let row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
 }
 
 $(document).ready(function() {
    var editProductId = <?php echo $product_details[0]['id']; ?>;

    $('#related_products_search').select2({
        placeholder: "Search for a product",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchRelatedProducts'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term, edit_id: editProductId }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    $('#product-options-category').select2({
        placeholder: "Search for a category",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategory'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    $('#product_options').select2({
        placeholder: "Search for a product",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategoryFilterProducts'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term, category_id: $("#product_options_category_id").val(), edit_id: editProductId }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    $('#product_variation_category').select2({
        placeholder: "Search for a category",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategory'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    $('#product_variation_product').select2({
        placeholder: "Search for a product",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategoryFilterProducts'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term, category_id: $("#product_variation_category_id").val(), edit_id: editProductId }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    // attributes level

    // Add new attribute row
    // $("#attribute-container").on("click", ".add-attribute", function () {
    $(".add-attribute").on("click", function () {
        let newRow = $(".attribute-row:first").clone();
        // newRow.find("select").val(""); // Reset dropdowns
        // newRow.find("input").val(""); // Reset dropdowns

        // Reset the cloned dropdowns
        newRow.find(".attribute-name").find("select").val("");
        newRow.find(".attribute-value").empty().append('<option value="">Select Value</option>');
        newRow.find(".possible-value").val("");

        $("#attribute-container").append(newRow);
    });

    // Remove attribute row
    $("#attribute-container").on("click", ".remove-attribute", function () {
        if ($(".attribute-row").length > 1) {
            $(this).closest(".attribute-row").remove();
        }
    });

    // Remove attribute row
    $("#attribute-container-parent").on("click", ".remove-exiting-attribute", function () {
        if ($(".attribute-row-parent").length > 0) {
            let attibute_id = $(this).data("product-attribute-id");
            console.log("remove id", attibute_id);

            let old_attribute = $("#remove_attributes_ids").val();
            if(old_attribute == ""){
                $("#remove_attributes_ids").val(attibute_id);
            }else{
                old_attribute += ","+attibute_id;
                $("#remove_attributes_ids").val(old_attribute);
            }

            $(this).closest(".attribute-row-parent").remove();
        }
    });

    // Save attributes via AJAX
    $("#saveAttributes").click(function (e) {
        e.preventDefault();

        // existing attributes
        let exisingAttributes = [];
        $(".attribute-row-parent").each(function () {
            let product_id = $("#edit_product_id").val();
            let name = $(this).find(".attribute-name").val();
            let value = $(this).find(".attribute-value").val();
            let possiblevalue = $(this).find(".possible-value").val();
            if (name && value) {
                exisingAttributes.push({ product_id, name, value, possiblevalue });
            }
        });

        // new attributes 
        let newAttributes = [];
        $(".attribute-row").each(function () {
            let product_id = $("#edit_product_id").val();
            let name = $(this).find(".attribute-name").val();
            let value = $(this).find(".attribute-value").val();
            let possiblevalue = $(this).find(".possible-value").val();
            if (name && value) {
                newAttributes.push({ product_id, name, value, possiblevalue });
            }
        });

        console.log("exisingAttributes", exisingAttributes);
        console.log("newAttributes", newAttributes);

        let finalAttributes = exisingAttributes.concat(newAttributes);
        console.log("finalAttributes", finalAttributes);

        // return;

        if (finalAttributes.length === 0) {
            alert("Please select at least one attribute!");
            return;
        }

        let remove_attributes_ids = $("#remove_attributes_ids").val();

        $.ajax({
            url: "<?= site_url('admin/product/saveProductAttributes') ?>", // Backend URL
            type: "POST",
            data: { attributes: finalAttributes, deleteAttributes: remove_attributes_ids },
            dataType: "json",
            success: function (response) {
                console.log("response", response);
                if (response.success) {
                    alert("Attributes saved successfully!");
                } else {
                    alert("Error saving attributes.");
                }
            },
            error: function () {
                alert("AJAX error.");
            }
        });
    });

    // $('.attribute-name').on('change', function() {
    $("#attribute-container").on("change", ".attribute-name", function () {
        var attributeSetId = $(this).val();
        console.log("attributeSetId", attributeSetId);
        let nearestAttributeNames = $(this).closest(".attribute-row").find(".attribute-value");
        if (attributeSetId) {
            $.ajax({
                url: "<?= site_url('admin/product/getAttributeNameByAttributeSet') ?>",
                type: "POST",
                data: { attribute_set_id: attributeSetId },
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
                    $.each(response, function(index, attribute_name) {
                        nearestAttributeNames.append('<option value="' + attribute_name.id + '">' + attribute_name.name + '</option>');
                    });
                }
            });
        } else {
            nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
        }    
    });

    $("#attribute-container-parent").on("change", ".attribute-name", function () {
        var attributeSetId = $(this).val();
        console.log("attributeSetId", attributeSetId);
        let nearestAttributeNames = $(this).closest(".attribute-row-parent").find(".attribute-value");
        if (attributeSetId) {
            $.ajax({
                url: "<?= site_url('admin/product/getAttributeNameByAttributeSet') ?>",
                type: "POST",
                data: { attribute_set_id: attributeSetId },
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
                    $.each(response, function(index, attribute_name) {
                        nearestAttributeNames.append('<option value="' + attribute_name.id + '">' + attribute_name.name + '</option>');
                    });
                }
            });
        } else {
            nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
        }    
    });

});

</script>