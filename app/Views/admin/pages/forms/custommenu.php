<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add Custom Menu</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu/custommenus') ?>">Custom Menu</a></li>
                        <li class="breadcrumb-item active">Add Custom Menu</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/menu/add_custommenu'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="title">Menu Title <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title" value="">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="menu_id">Parent Menu</label>
                                        <select name="menu_id" id="menu_id" class="form-control" required="">
                                            <option value="">Select Parent</option>
                                            <?php
                                                foreach ($menus as $menu) {
                                            ?>
                                                <option value="<?= $menu['id'] ?>"><?= $menu['title'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link">Link <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="link" placeholder="Menu Link" name="link" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link_url">Link Url</label>
                                        <input type="text" class="form-control" id="link_url" placeholder="Menu Link Url" name="link_url" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="sort">Sort</label>
                                        <select name="sort" id="sort" class="form-control type_event_trigger" required="">
                                            <option value="10">10</option>
                                            <option value="9">9</option>
                                            <option value="8">8</option>
                                            <option value="7">7</option>
                                            <option value="6">6</option>
                                            <option value="5">5</option>
                                            <option value="4">4</option>
                                            <option value="3">3</option>
                                            <option value="2">2</option>
                                            <option value="1">1</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="multi_category_filter">
                                    <div class="col-sm-12">
                                        <label for="category_parent">Categories</label>
                                        <select id="category_parent" name="category[]" multiple>                            
                                            <?php getCategoriesMultiple(0, '-', ''); ?>
                                        </select>
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
                                    <button type="submit" class="btn btn-success">Add Custom Menu</button>
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
<script>

    let link_url = $("#link_url").val(); 
    console.log("link_url", link_url);   
    if(link_url !== ""){
        $("#multi_category_filter").hide();
    }

    $("#link_url").on("keypress keyup", function () {
        if ($(this).val().trim() === "") {
            $("#multi_category_filter").show();
        } else {
            $("#multi_category_filter").hide();
        }
    });
</script>