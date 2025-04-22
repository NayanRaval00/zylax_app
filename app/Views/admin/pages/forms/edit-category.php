<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Category</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/category') ?>">Category</a></li>
                        <li class="breadcrumb-item active">Edit Category</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/category/update_category'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_category" value="<?= @$fetched_data['id'] ?>">
                            <?php } ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="category_input_name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="category_input_name" placeholder="Category Name" name="category_input_name" value="<?= isset($fetched_data['name']) ? output_escaping($fetched_data['name']) : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slug" class="col-sm-2 col-form-label">Slug <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="slug" placeholder="Category Slug" name="slug" value="<?= isset($fetched_data['slug']) ? output_escaping($fetched_data['slug']) : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="category_parent" class="col-sm-2 col-form-label">Select Parent</label>
                                    <div class="col-sm-10">
                                        <select id="category_parent" name="category_parent">
                                            <option value="0"><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Parent' ?></option>
                                            
                                            <?php getCategories(0, '-', $fetched_data['parent_id']); ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="image">Main Image <span class='text-danger text-sm'>*</span><small>(Recommended Size : 131 x 131 pixels)</small></label>
                                        <input type="file" id="image" name="image" class="form-control"/>
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
                                    <div class="col-sm-6">
                                        <label for="banner">Banner Image <small>(Recommended Size : 131 x 131 pixels)</small></label>
                                        <input type="file" id="banner" name="banner" class="form-control"/>
                                        <?php if($fetched_data['banner']): ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class="image-upload-div">
                                                        <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['banner'] ?>" alt="Banner Not Found">
                                                    </div>
                                                    <input type="hidden" name="category_input_banner" value="<?= base_url().$fetched_data['banner'] ?>">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
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
                                                    <input type="hidden" name="category_icon_image" value="<?= $fetched_data['icon'] ?>">
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

                                <hr class="mt-4">
                                <h4 class="bg-light m-0 px-2 py-3">SEO Configuration</h4>

                                <div class="d-flex bg-light">
                                    <div class="form-group col-sm-6">
                                        <label for="seo_page_title" class="form-label form-label-sm d-flex">
                                            SEO Page Title
                                        </label>
                                        <input type="text" class="form-control" id="seo_page_title"
                                            placeholder="SEO Page Title" name="seo_page_title"
                                            value="<?= isset($fetched_data['seo_page_title']) ? output_escaping($fetched_data['seo_page_title']) : "" ?>">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label for="seo_meta_keyword" class="form-label form-label-sm d-flex">
                                            SEO Meta Keywords
                                        </label>
                                        <input type="text" name='seo_meta_keyword' class='form-control' id='seo_meta_keyword' placeholder="SEO Meta Keywords" value="<?= isset($fetched_data['seo_meta_keywords']) ? output_escaping($fetched_data['seo_meta_keywords']) : "" ?>" />
                                    </div>
                                </div>
                                <div class="d-flex bg-light">

                                    <div class="form-group col-sm-6">
                                        <label for="seo_meta_description" class="form-label form-label-sm d-flex">
                                            SEO Meta Description
                                        </label>
                                        <textarea class="form-control" id="seo_meta_description"
                                            placeholder="SEO Meta Keywords" name="seo_meta_description"><?= isset($fetched_data['seo_meta_description']) ? output_escaping($fetched_data['seo_meta_description']) : "" ?></textarea>
                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="seo_og_image">SEO Open Graph Image <small>(Recommended Size : 131 x 131 pixels)</small></label>
                                            <div class="col-sm-10">
                                                <div class='col-md-12'>
                                                <input type="file" id="seo_og_image" name="seo_og_image" class="form-control" />
                                                </div>                                                
                                            </div>
                                            <?php if($fetched_data['seo_og_image']): ?>
                                                <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                                <div class="container-fluid row image-upload-section">
                                                    <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                        <div class="image-upload-div">
                                                            <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['seo_og_image'] ?>" alt="Image Not Found">
                                                        </div>
                                                        <input type="hidden" name="category_seo_image" value="<?= $fetched_data['seo_og_image'] ?>">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-3">
                                    <button type="reset" class="btn btn-warning ">Reset</button>
                                    <button type="submit" class="btn btn-success">Update Category</button>
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