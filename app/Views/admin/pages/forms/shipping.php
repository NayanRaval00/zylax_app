<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Shipping</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/shipping') ?>">Shipping</a></li>
                        <li class="breadcrumb-item active">Add Shipping</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/shipping/add_shipping'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <div class="card-body">
                                
                                <div class="form-group row">
                                    <label for="shipping_name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="shipping_name" placeholder="Shipping Name" name="shipping_name" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description" class="col-sm-2 col-form-label">Description <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" placeholder="Description" name="description" value="">
                                    </div>
                                </div>

                                <div class="form-group my-3">
                                    <button type="reset" class="btn btn-warning ">Reset</button>
                                    <button type="submit" class="btn btn-success">Add Shipping</button>
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