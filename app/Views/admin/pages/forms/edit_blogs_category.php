<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= (isset($fetched_data['id'])) ? 'Update category for Blogs' : 'Add category for Blogs' ?></h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Category for blog</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/blogs/update_category'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_category" value="<?= @$fetched_data['id'] ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="category_input_name" class="col-sm-1 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="category_input_name" placeholder="Category Name" name="category_input_name" value="<?= isset($fetched_data['name']) ? output_escaping($fetched_data['name']) : "" ?>">
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
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data['id'])) ? 'Update Category' : 'Add Category' ?></button>
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