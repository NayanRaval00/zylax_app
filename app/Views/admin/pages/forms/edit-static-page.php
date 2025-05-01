<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Page</h4>
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
                        <form class="form-horizontal" action="<?= base_url('admin/staticpages/update_page'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_page" value="<?= @$fetched_data['id'] ?>">
                            <?php } ?>
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="name" class="col-form-label">Title <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="name" placeholder="Enter Page Name" name="name" value="<?= isset($fetched_data['name']) ? output_escaping($fetched_data['name']) : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="menu_name" class="col-form-label">Name On Menu <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="menu_name" placeholder="Enter Name On Menu" name="menu_name" value="<?= isset($fetched_data['menu_name']) ? output_escaping($fetched_data['menu_name']) : "" ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="slug" class="col-form-label">Slug <span class='text-danger text-sm'>*</span> </label>
                                        <input type="text" class="form-control" id="slug" placeholder="Enter Slug" name="slug" value="<?= isset($fetched_data['slug']) ? output_escaping($fetched_data['slug']) : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description">Description </label>
                                    <div class="mb-3">
                                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here"><?= (isset($fetched_data['id'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data['description'])) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image">Main Image</label>
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
                                                <input type="hidden" name="page_input_image" value="<?= $fetched_data['image'] ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
                                            value="<?= isset($fetched_data['meta_title']) ? output_escaping($fetched_data['meta_title']) : "" ?>">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label for="meta_description" class="form-label form-label-sm d-flex">
                                            SEO Meta Keywords
                                        </label>
                                        <input type="text" name='meta_description' class='form-control' id='meta_description' placeholder="SEO Meta Keywords" value="<?= isset($fetched_data['meta_description']) ? output_escaping($fetched_data['meta_description']) : "" ?>" />
                                    </div>
                                </div>
                                <div class="d-flex bg-light">

                                    <div class="form-group col-sm-6">
                                        <label for="meta_keyword" class="form-label form-label-sm d-flex">
                                            SEO Meta Description
                                        </label>
                                        <textarea class="form-control" id="meta_keyword"
                                            placeholder="SEO Meta Keywords" name="meta_keyword"><?= isset($fetched_data['meta_keyword']) ? output_escaping($fetched_data['meta_keyword']) : "" ?></textarea>
                                    </div>

                                </div>


                                <label for="pro_input_specification">Status </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="active" value="1" <?= (isset($fetched_data['active']) && $fetched_data['active'] == '1') ? 'checked' : ''; ?>> Yes</label>
                                    <label class="radioopt"><input type="radio" name="active" value="0" <?= (isset($fetched_data['active']) && $fetched_data['active'] == '0') ? 'checked' : ''; ?>> No</label>
                                </div>

                                <label for="pro_input_specification">Where To Place In Footer? </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="place_to" value="important_links" <?= (isset($fetched_data['place_to']) && $fetched_data['place_to'] == 'important_links') ? 'checked' : ''; ?>> Important Links</label>
                                    <label class="radioopt"><input type="radio" name="place_to" value="cooperation" <?= (isset($fetched_data['place_to']) && $fetched_data['place_to'] == 'cooperation') ? 'checked' : ''; ?>> Co-operations</label>
                                    <label class="radioopt"><input type="radio" name="place_to" value="none" <?= (isset($fetched_data['place_to']) && $fetched_data['place_to'] == 'none') ? 'checked' : ''; ?>> None</label>
                                </div>

                                <label for="pro_input_specification">Page Script Name [Optional]</label>
                                <div class="mb-3">
                                    <input type="text" name='page_script' class='form-control' id='page_script' placeholder="Page Script Name [Optional]" value="<?= isset($fetched_data['page_script']) ? output_escaping($fetched_data['page_script']) : "" ?>" />
                                </div>

                                <label for="pro_input_specification">Page Type </label>
                                <div class="mb-3">
                                    <label class="radioopt"><input type="radio" name="page_type" value="Static" <?= (isset($fetched_data['page_type']) && $fetched_data['page_type'] == 'Static') ? 'checked' : ''; ?>> Static</label>
                                    <label class="radioopt"><input type="radio" name="page_type" value="Repair" <?= (isset($fetched_data['page_type']) && $fetched_data['page_type'] == 'Repair') ? 'checked' : ''; ?>> Repair</label>
                                </div>   

                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="sort" class="col-form-label">Sort <span class='text-danger text-sm'>*</span> </label>
                                        <input type="number" class="form-control" id="sort" placeholder="Enter Sort" name="sort" value="<?= isset($fetched_data['sort']) ? output_escaping($fetched_data['sort']) : 10 ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success">Update Page</button>
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