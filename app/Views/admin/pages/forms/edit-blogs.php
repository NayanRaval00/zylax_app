<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><?= isset($fetched_data['id']) ? 'Update' : 'Add' ?> Blog</h4>
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
                        <form class="form-horizontal" action="<?= base_url('admin/blogs/update_blog'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_blog" value="<?= @$fetched_data['id'] ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="blog_title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="blog_title" placeholder="Title" name="blog_title" value="<?= isset($fetched_data['title']) ? output_escaping($fetched_data['title']) : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="category_parent" class="col-sm-2 col-form-label">Select Categories <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-6">
                                        <select id="category_parent" name="blog_category">
                                            <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Category' ?></option>
                                            
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?= $category['id'] ?>" <?php if($category['id'] == $fetched_data['category_id']){ echo "selected"; } ?>><?= $category['name'] ?></option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>
                                </div>

                                <br>
                                <div class="form-group">
                                    <label name='image' for="image">Main Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : larger than 400 x 260 & smaller than 600 x 300 pixels.)</small></label>
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
                                    <textarea name="blog_description" class="textarea addr_editor" placeholder="Place some text here"><?= (isset($fetched_data['description'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data['description'])) : ''; ?></textarea>
                                    <div class="form-group mt-3">
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-success"><?= (isset($fetched_data['id'])) ? 'Update Blog' : 'Add Blog' ?></button>
                                    </div>
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