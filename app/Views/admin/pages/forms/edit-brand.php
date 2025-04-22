<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Brand</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Brand</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/brand/update_brand'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_brand" value="<?= @$fetched_data['id'] ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="brand_input_name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="brand_input_name" placeholder="Brand Name" name="brand_input_name" value="<?= isset($fetched_data['name']) ? output_escaping($fetched_data['name']) : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-sm-2 col-form-label">Slug <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="slug" placeholder="Brand Slug" name="slug" value="<?= isset($fetched_data['slug']) ? output_escaping($fetched_data['slug']) : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image">Main Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : 131 x 131 pixels)</small></label>
                                    <div class="col-sm-6">
                                        <input type="file" id="image" name="image" class="form-control"/>
                                    </div>
                                    <?php if($fetched_data['image']): ?>
                                        <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                        <div class="container-fluid row image-upload-section">
                                            <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                <div class="image-upload-div">
                                                    <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['image'] ?>" alt="Image Not Found">
                                                </div>
                                                <input type="hidden" name="category_input_image" value="<?= base_url().$fetched_data['image'] ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="icon">Icon</label>
                                        <input type="file" id="icon" name="icon" class="form-control"/>
                                        <?php if($fetched_data['icon']): ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class="image-upload-div">
                                                        <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['icon'] ?>" alt="Icon Not Found">
                                                    </div>
                                                    <input type="hidden" name="brand_icon_image" value="<?= $fetched_data['icon'] ?>">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-6 mt-4 text-center">
                                        <label for="is_show" class="col-form-label">Is Show Homepage ?</label>
                                        <input type="checkbox" name="is_show" id="is_show" <?= (isset($fetched_data['is_show']) && $fetched_data['is_show'] == 1) ? 'Checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description">Description </label>
                                    <div class="mb-3">
                                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here"><?= (isset($fetched_data['id'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data['description'])) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success">Update Brand</button>
                                </div>
                            </div>

                    </div>
                    <!-- /.card-footer -->
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