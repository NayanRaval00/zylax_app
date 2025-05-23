
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Slider Image For Add-on Offers and other benefits </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Slider</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" id='media-upload' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Media</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal" action="<?= base_url('admin/slider/add_slider'); ?>" method="POST" id="payment_setting_form" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group">
                                    <?php if (isset($fetched_data[0]['id'])) {
                                    ?>
                                        <input type="hidden" name="edit_slider" value="<?= $fetched_data[0]['id'] ?>">
                                    <?php } ?>

                                    <label for="slider_type">Type <span class='text-danger text-sm'>*</span> </label>
                                    <select name="slider_type" id="slider_type" class="form-control type_event_trigger" required="">
                                        <option value=" ">Select Type</option>
                                        <option value="categories" <?= (@$fetched_data[0]['type'] == "categories") ? 'selected' : ' ' ?>>Category</option>
                                        <!-- <option value="products" <?= (@$fetched_data[0]['type'] == "products") ? 'selected' : ' ' ?>>Product</option>
                                        <option value="slider_url" <?= (@$fetched_data[0]['type'] == "slider_url") ? 'selected' : ' ' ?>>Slider URL</option> -->
                                    </select>
                                </div>
                                <div id="type_add_html">
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'categories') ? '' : 'd-none' ?>
                                    <div class="form-group slider-categories <?= $hiddenStatus ?> ">

                                        <label for="category_id"> Categories <span class='text-danger text-sm'>*</span></label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select category </option>
                                            <?php
                                            if (!empty($categories)) {
                                                foreach ($categories as $row) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"> <?= $row['slider_name'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'slider_url') ? '' : 'd-none' ?>
                                    <div class="form-group slider-url <?= $hiddenStatus ?> ">

                                        <label for="slider_url"> Link <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" placeholder="https://example.com" name="link" value="<?= isset($fetched_data[0]['link']) ? output_escaping($fetched_data[0]['link']) : "" ?>">
                                    </div>
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'products') ? '' : 'd-none' ?>
                                    <div class="form-group row slider-products <?= $hiddenStatus ?>">
                                        <label for="product_id" class="control-label">Products <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12">
                                            <select name="product_id" class="search_admin_product w-100" data-placeholder=" Type to search and select products" onload="multiselect()">
                                                <?php
                                                if (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'products') {
                                                    $product_details = fetch_details('products', ['id' => $row['type_id']], 'id,name');
                                                    if (!empty($product_details)) {
                                                ?>
                                                        <option value="<?= $product_details[0]['id'] ?>" selected> <?= $product_details[0]['name'] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div><label for="image">Slider Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : 1648 x 610 pixels)</small></label></div>
                                    <div class="col-sm-6">
                                        <input type="file" id="image" name="image" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div><label for="image_alt">Alt Tag Text</label></div>
                                    <div class="col-sm-6">
                                        <input type="text" id="image_alt" name="image_alt" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div><label for="image">URL <span class='text-danger text-sm'>*</span></label></div>
                                    <div class="col-sm-6">
                                        <input type="text" id="url" name="url" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success"><?= (isset($fetched_data[0]['id'])) ? 'Update Slider' : 'Add Slider' ?></button>
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