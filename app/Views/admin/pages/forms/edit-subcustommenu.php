<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <?php if(!empty($fetched_data['parent_id']) && !empty($fetched_data['custommenu_id'])){ ?>
                    <h4>Edit Sub Sub Custom Menu</h4>
                <?php }else{ ?>
                    <h4>Edit Sub Custom Menu</h4>
                <?php } ?>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <?php if(!empty($fetched_data['parent_id']) && !empty($fetched_data['custommenu_id'])){ ?>
                            <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu/sub_subcustommenus/'.$fetched_data['parent_id'].'/'.$fetched_data['custommenu_id']) ?>">Sub Sub Custom Menu</a></li>
                            <li class="breadcrumb-item active">Edit Sub Sub Custom Menu</li>
                        <?php }else{ ?>
                            <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu/subcustommenus/'.$fetched_data['parent_id']) ?>">Sub Custom Menu</a></li>
                            <li class="breadcrumb-item active">Edit Sub Custom Menu</li>
                        <?php } ?>
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
                        <form class="form-horizontal" action="<?= base_url('admin/menu/update_subcustommenu'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_subcustommenu" value="<?= @$fetched_data['id'] ?>">
                                <input type="hidden" name="custommenu_id" value="<?= @$fetched_data['custommenu_id'] ?>">
                                <input type="hidden" name="edit_subcustommenu_category" value="<?= $selected_categories ?>">
                            <?php } ?>
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="title">Menu Title <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title" value="<?= isset($fetched_data['title']) ? $fetched_data['title'] : "" ?>">
                                    </div>
                                    <!-- <div class="col-sm-6">
                                        <label for="custommenu_id">Parent Sub Custom</label>
                                        <select name="custommenu_id" id="custommenu_id" class="form-control" required="">
                                            <option value="0">Select Parent Sub Custom</option>
                                            <?php
                                                foreach ($customsub_menus as $customsub) {
                                            ?>
                                                <option value="<?= $customsub['id'] ?>" <?php if($customsub['id'] == $fetched_data['custommenu_id']){ echo "selected"; } ?>><?= $customsub['title'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div> -->
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link">Slug <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="link" placeholder="Menu Slug" name="link" value="<?= isset($fetched_data['link']) ? $fetched_data['link'] : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link_url">Link Url</label>
                                        <input type="text" class="form-control" id="link_url" placeholder="Menu Link Url" name="link_url" value="<?= isset($fetched_data['link_url']) ? $fetched_data['link_url'] : "" ?>">
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
                                    <?php if(!empty($fetched_data['parent_id']) && !empty($fetched_data['custommenu_id'])){ ?>
                                        <button type="submit" class="btn btn-success">Update Sub Sub Custom Menu</button>
                                    <?php }else{ ?>
                                        <button type="submit" class="btn btn-success">Update Sub Custom Menu</button>
                                    <?php } ?>
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