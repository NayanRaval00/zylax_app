<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Menu</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu') ?>">Menu</a></li>
                        <li class="breadcrumb-item active">Add Menu</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/menu/add_menu'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="title">Menu Title <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title" value="<?= old('title') ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="icon">Icon HTML Code</label>
                                        <input type="text" class="form-control" id="icon" placeholder="Menu Icon" name="icon" value="<?= old('icon') ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link">Slug <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="link" placeholder="Menu Slug" name="link" value="<?= old('link') ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="image_top">Image Top <span class='text-danger text-sm'>*</span></label>
                                        <input type="file" id="image_top" name="image_top" class="form-control" required="true" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="image_right">Image Right</label>
                                        <input type="file" id="image_right" name="image_right" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control type_event_trigger" required="">
                                            <option value="type_1" <?php if("type_1" == old('type')){ echo "selected"; } ?>>Type 1</option>
                                            <option value="type_2" <?php if("type_2" == old('type')){ echo "selected"; } ?>>Type 2</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="sort">Menu Position</label>
                                        <input type="number" class="form-control" id="sort" placeholder="Menu Position" name="sort" value="10">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description">Description </label>
                                    <div class="mb-3">
                                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here">
                                        <?php
                                            $description = old('description');
                                            echo isset($description) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $description)) : "";
                                        ?>
                                        </textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="active">Active</label>
                                        <div class="mb-3">
                                            <label class="radioopt"><input type="radio" name="active" value="1" checked> Yes</label>
                                            <label class="radioopt"><input type="radio" name="active" value="0"> No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-3">
                                    <button type="reset" class="btn btn-warning ">Reset</button>
                                    <button type="submit" class="btn btn-success">Add Menu</button>
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