<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Page</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Page</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/staticpages/add_page'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <div class="card-body">

                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="name" class="col-form-label">Title <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="name" placeholder="Enter Page Name" name="name" value="<?= old('name') ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="menu_name" class="col-form-label">Name On Menu <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="menu_name" placeholder="Enter Name On Menu" name="menu_name" value="<?= old('menu_name') ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="slug" class="col-form-label">Slug <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="slug" placeholder="Enter Slug" name="slug" value="<?= old('slug') ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description">Description </label>
                                    <div class="mb-3">
                                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here"><?= old('description') ?></textarea>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="image">Main Image</label>
                                    <div class="col-sm-6">
                                        <input type="file" id="image" name="image" class="form-control"/>
                                    </div>
                                </div>

                                
                                <hr class="mt-4">
                                <h4 class="bg-light m-0 px-2 py-3">SEO Configuration</h4>

                                <div class="d-flex bg-light">
                                    <div class="form-group col-sm-6">
                                        <label for="meta_title" class="form-label form-label-sm d-flex">
                                            SEO Page Title
                                        </label>
                                        <input type="text" class="form-control" id="meta_title"
                                            placeholder="SEO Page Title" name="meta_title"
                                            value="<?= old('meta_title') ?>">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label for="meta_description" class="form-label form-label-sm d-flex">
                                            SEO Meta Keywords
                                        </label>
                                        <input type="text" name='meta_description' class='form-control' id='meta_description' placeholder="SEO Meta Keywords" value="<?= old('meta_description') ?>" />
                                    </div>
                                </div>
                                <div class="d-flex bg-light">

                                    <div class="form-group col-sm-6">
                                        <label for="meta_keyword" class="form-label form-label-sm d-flex">
                                            SEO Meta Description
                                        </label>
                                        <textarea class="form-control" id="meta_keyword"
                                            placeholder="SEO Meta Keywords" name="meta_keyword"><?= old('meta_keyword') ?></textarea>
                                    </div>

                                </div>


                                <label for="pro_input_specification">Status </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="active" value="1" checked> Yes</label>
                                    <label class="radioopt"><input type="radio" name="active" value="0"> No</label>
                                </div>

                                <label for="pro_input_specification">Where To Place In Footer? </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="place_to" value="important_links"> Important Links</label>
                                    <label class="radioopt"><input type="radio" name="place_to" value="cooperation"> Co-operations</label>
                                    <label class="radioopt"><input type="radio" name="place_to" value="none" checked> None</label>
                                </div>

                                <label for="pro_input_specification">Page Script Name [Optional]</label>
                                <div class="mb-3">
                                    <input type="text" name='page_script' class='form-control' id='page_script' placeholder="Page Script Name [Optional]" value="" />
                                </div>

                                <label for="pro_input_specification">Page Type </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="page_type" value="Static" checked> Static</label>
                                    <label class="radioopt"><input type="radio" name="page_type" value="Repair"> Repair</label>
                                </div>                                
                                
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success">Add Page</button>
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