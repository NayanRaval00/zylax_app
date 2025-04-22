<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Custom Menu</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu/custommenus') ?>">Custom Menu</a></li>
                        <li class="breadcrumb-item active">Edit Custom Menu</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/menu/update_custommenu'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_custommenu" value="<?= @$fetched_data['id'] ?>">
                                <input type="hidden" name="edit_custommenu_category" value="<?= $selected_categories ?>">
                            <?php } ?>
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="title">Menu Title <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title" value="<?= isset($fetched_data['title']) ? $fetched_data['title'] : "" ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="menu_id">Parent Menu</label>
                                        <select name="menu_id" id="menu_id" class="form-control" required="">
                                            <option value="">Select Parent</option>
                                            <?php
                                                foreach ($menus as $menu) {
                                            ?>
                                                <option value="<?= $menu['id'] ?>" <?php if($menu['id'] == $fetched_data['menu_id']){ echo "selected"; } ?>><?= $menu['title'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link">Link <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="link" placeholder="Menu Link" name="link" value="<?= isset($fetched_data['link']) ? $fetched_data['link'] : "" ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link_url">Link Url</label>
                                        <input type="text" class="form-control" id="link_url" placeholder="Menu Link Url" name="link_url" value="<?= isset($fetched_data['link_url']) ? $fetched_data['link_url'] : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="sort">Sort</label>
                                        <select name="sort" id="sort" class="form-control type_event_trigger" required="">
                                            <option value="10" <?php if($fetched_data['sort'] == 10){ echo "selected"; } ?>>10</option>
                                            <option value="9" <?php if($fetched_data['sort'] == 9){ echo "selected"; } ?>>9</option>
                                            <option value="8" <?php if($fetched_data['sort'] == 8){ echo "selected"; } ?>>8</option>
                                            <option value="7" <?php if($fetched_data['sort'] == 7){ echo "selected"; } ?>>7</option>
                                            <option value="6" <?php if($fetched_data['sort'] == 6){ echo "selected"; } ?>>6</option>
                                            <option value="5" <?php if($fetched_data['sort'] == 5){ echo "selected"; } ?>>5</option>
                                            <option value="4" <?php if($fetched_data['sort'] == 4){ echo "selected"; } ?>>4</option>
                                            <option value="3" <?php if($fetched_data['sort'] == 3){ echo "selected"; } ?>>3</option>
                                            <option value="2" <?php if($fetched_data['sort'] == 2){ echo "selected"; } ?>>2</option>
                                            <option value="1" <?php if($fetched_data['sort'] == 1){ echo "selected"; } ?>>1</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="multi_category_filter">
                                    <div class="col-sm-12">
                                        <label for="category_parent">Categories</label>
                                        <select id="category_parent" name="category[]" multiple>                            
                                            <?php getCategoriesMultiple(0, '-', $selected_categories); ?>
                                        </select>
                                    </div>
                                </div>
                                

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="active">Active</label>
                                        <div class="mb-3">
                                            <label class="radioopt"><input type="radio" name="active" value="1" <?= (isset($fetched_data['active']) && $fetched_data['active'] == '1') ? 'checked' : ''; ?>> Yes</label>
                                            <label class="radioopt"><input type="radio" name="active" value="0" <?= (isset($fetched_data['active']) && $fetched_data['active'] == '0') ? 'checked' : ''; ?>> No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-3">
                                    <button type="reset" class="btn btn-warning ">Reset</button>
                                    <button type="submit" class="btn btn-success">Update Custom Menu</button>
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