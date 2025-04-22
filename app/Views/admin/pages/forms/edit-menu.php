<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Menu</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/menu') ?>">Menu</a></li>
                        <li class="breadcrumb-item active">Edit Menu</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/menu/update_menu'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php if (isset($fetched_data['id'])) { ?>
                                <input type="hidden" name="edit_menu" value="<?= @$fetched_data['id'] ?>">
                                <input type="hidden" name="edit_menu_category" value="<?= $selected_categories ?>">
                                <input type="hidden" id="menu_product_category_id" value="">
                            <?php } ?>
                            <div class="card-body">

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="title">Menu Title <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title" value="<?= isset($fetched_data['title']) ? $fetched_data['title'] : "" ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="icon">Icon HTML Code</label>
                                        <input type="text" class="form-control" id="icon" placeholder="Menu Icon" name="icon" value="<?= isset($fetched_data['icon']) ? htmlspecialchars($fetched_data['icon']) : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="link">Link <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="link" placeholder="Menu Link" name="link" value="<?= isset($fetched_data['link']) ? $fetched_data['link'] : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="image_top">Image Top</label>
                                        <input type="file" id="image_top" name="image_top" class="form-control"/>
                                        <?php if($fetched_data['image_top']): ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class="image-upload-div">
                                                        <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['image_top'] ?>" alt="Image Not Found">
                                                    </div>
                                                    <input type="hidden" name="menu_image_top" value="<?= $fetched_data['image_top'] ?>">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="image_right">Image Right</label>
                                        <input type="file" id="image_right" name="image_right" class="form-control"/>
                                        <?php if($fetched_data['image_right']): ?>
                                            <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                            <div class="container-fluid row image-upload-section">
                                                <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                    <div class="image-upload-div">
                                                        <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['image_right'] ?>" alt="Image Not Found">
                                                    </div>
                                                    <input type="hidden" name="menu_image_right" value="<?= $fetched_data['image_right'] ?>">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control type_event_trigger" required="">
                                            <option value="type_1" <?php if("type_1" == $fetched_data['type']){ echo "selected"; } ?>>Type 1</option>
                                            <option value="type_2" <?php if("type_2" == $fetched_data['type']){ echo "selected"; } ?>>Type 2</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="sort">Menu Position</label>
                                        <input type="number" class="form-control" id="sort" placeholder="Menu Position" name="sort" value="<?= isset($fetched_data['sort']) ? $fetched_data['sort'] : "" ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label for="category_parent">Categories</label>
                                        <select id="category_parent" name="category[]" multiple>                            
                                            <?php getCategoriesMultiple(0, '-', $selected_categories); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description">Description </label>
                                    <div class="mb-3">
                                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here"><?= (isset($fetched_data['id'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data['description'])) : ''; ?></textarea>
                                    </div>
                                </div>

                                <hr>
                                <h5>Featured Products Section</h5>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="featured_title">Featured Title</label>
                                        <input type="text" class="form-control" id="featured_title" placeholder="Featured Products Title" name="featured_title" value="<?= isset($fetched_data['featured_title']) ? $fetched_data['featured_title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="product_type">Products</label>
                                        <select name="product_type" id="product_type" class="form-control" required="">
                                            <option value="1">Product 1</option>
                                            <option value="2">Product 2</option>
                                            <option value="3">Product 3</option>
                                            <option value="4">Product 4</option>
                                            <option value="5">Product 5</option>
                                            <option value="6">Product 6</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="menu_product_category">Filter Category</label>
                                        <select id="menu_product_category" class="form-control w-100">
                                            <option value="">Search Category</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="menu_featured_product">Filter Products</label>
                                        <select id="menu_featured_product" class="form-control w-100">
                                            <option value="">Select Product</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <a href="javascript:void(0);" id="add_menu_featured_product" class="btn btn-block btn-outline-primary float-right btn-sm mt-2" data-menu-id="<?= (isset($fetched_data['id'])) ? $fetched_data['id'] : "" ?>">Add Product</a>
                                    </div>
                                </div>

                                <table class="table table-striped" id="append_featured_products">
                                    <?php foreach ($featured_products as $row) { ?>
                                        <tr id="featured_product_<?= $row['id'] ?>">
                                            <td><?= '( Product Type : ' . $row['product_type'] . ' ) - ( Product : '. $row['product_name'] .' )' ?></td>
                                            <td>
                                                <a class="btn btn-tool delete-menu-featured-product" data-id="<?= $row['id'] ?>"> 
                                                    <i class="text-danger far fa-times-circle fa-2x "></i> 
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>

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
                                    <button type="submit" class="btn btn-success">Update Menu</button>
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

$(document).ready(function() {

    $('#menu_product_category').select2({
        placeholder: "Search for a category",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategory'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    $('#menu_featured_product').select2({
        placeholder: "Search for a product",
        minimumInputLength: 3,  // Only trigger search after 3 characters
        ajax: {
            url: "<?= base_url('admin/product/searchCategoryFilterProducts'); ?>",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { query: params.term, category_id: $("#menu_product_category_id").val(), edit_id: 0 }; // Pass search term to backend
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

});

</script>