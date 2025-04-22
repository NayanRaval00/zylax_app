<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Products</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12 main-content">
                <div class="card content-area p-4">
                    <div class="card-header border-0">
                        <div class="card-tools">
                            <a href="<?= base_url() . 'admin/product/create_product' ?>" class="btn btn-block btn-outline-primary btn-sm">Add Product</a>
                        </div>
                    </div>
                    <div class="card-innr">
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <label for="category_parent" class="col-form-label">Filter By Product Category</label>
                                <select id="category_parent" name="category_parent">
                                    <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                    </option>
                                    <?php
                                    // echo get_categories_option_html($categories);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="zipcode" class="col-form-label">Filter By Product Status</label>
                                <select class='form-control' name='status' id="status_filter">
                                    <option value=''>Select Status</option>
                                    <option value='1'>Approved</option>
                                    <option value='2'>Not-Approved</option>
                                    <option value='0'>Deactivated</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="seller_filter" class="col-form-label">Filter By Seller</label>
                                <select class='form-control' name='seller_id' id="seller_filter">
                                    <option value=""><?= (isset($sellers) && empty($sellers)) ? 'No Seller Exist' : 'Select Seller' ?>
                                </select>
                            </div>
                        </div> -->

                        <div class="row mb-4">
                            <div class="col-md-2">
                                Total : <span id="totalCount">-</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <select class='form-control comman_select2' id="changeCategory">
                                    <option value=''>Select Category</option>
                                    <?php getCategories(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button id="changeCategoryBtn" class="btn btn-success">Change Product Categories</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button id="assignAttributesBtn" class="btn btn-success">Assign Product Attributes</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button id="assignTagsBtn" class="btn btn-success">Assign Tags</button>
                                </div>
                            </div>
                        </div>

                        <hr>
                                
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Search by name, model, description....." id="customSearch" value="">
                            </div>
                            <div class="col-md-3">
                                <select class='form-control comman_select2' id="customCategory">
                                    <option value=''>Select Category</option>
                                    <?php getCategories(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class='form-control comman_select2' id="customAttributeSet">
                                    <option value=''>Select Attribute Set</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class='form-control comman_select2' id="customAttributeName">
                                    <option value=''>Select Attribute Name</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <select class=" col-md-12  form-control comman_select2" id="customNotAssign">
                                    <option value="">ASSIGN Attribute</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class=" col-md-12  form-control comman_select2" id="customBrand">
                                    <option value="">Select Brand</option>
                                    <?php
                                        foreach ($brands as $row) {
                                    ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class=" col-md-12  form-control comman_select2" id="customStatus">
                                    <option value="">Select Status</option>
                                    <option value="1">Enable</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button id="searchBtn" class="btn btn-success">Submit</button>
                                    <button id="deleteSelected" class="btn btn-danger">Delete</button>
                                    <button id="resetBtn" class="btn btn-warning">Reset</button>
                                </div>
                            </div>
                        </div>

                        <table id="productTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Brand</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                        
                    </div><!-- .card-innr -->
                </div><!-- .card -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(document).ready(function () {

        function fetchProducts(search = '', category = '', attribute_set = '', attribute_name = '', attribute_not_assign = '', brand = '', status = '') {
            return $('#productTable').DataTable({
                "destroy": true,
                "sDom":"ltipr",
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('admin/product/fetchProducts') ?>",
                    "type": "POST",
                    data: { search: search, category: category, attribute_set: attribute_set, attribute_name: attribute_name, attribute_not_assign: attribute_not_assign, brand: brand, status: status }
                },
                "columns": [
                    { data: 'select', orderable: false, searchable: false },
                    { data: 'image' },
                    { data: 'name' },
                    { data: 'brand' },
                    { data: 'category' },
                    { data: 'action' }
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var total = api.ajax.json().recordsTotal;
                    console.log(total);
                    $('#totalCount').text(total);
                }
            });
        }

        var table = fetchProducts();

          // Custom search
        $('#searchBtn').on('click', function() {
            var search = $('#customSearch').val();
            var category = $('#customCategory').val();
            var attribute_set = $('#customAttributeSet').val();
            var attribute_name = $('#customAttributeName').val();
            var attribute_not_assign = $('#customNotAssign').val();
            var brand = $('#customBrand').val();
            var status = $('#customStatus').val();
            table = fetchProducts(search, category, attribute_set, attribute_name, attribute_not_assign, brand, status);
        });

          // Reset filters
          $('#resetBtn').on('click', function() {
            $('#customSearch').val('');
            $("#customCategory").select2("val", "");
            $("#customBrand").select2("val", "");
            $("#customStatus").select2("val", "");
            fetchProducts();
        });

        // Select all checkboxes
        $('#selectAll').on('click', function() {
            $('.product-checkbox').prop('checked', this.checked);
        });      
        
        // Delete selected users
        $('#deleteSelected').on('click', function() {
            var selectedIds = $('.product-checkbox:checked').map(function() {
                return this.value;
            }).get();

            console.log("selectedIds", selectedIds);

            if (selectedIds.length > 0) {
                $.post("<?= site_url('admin/product/deleteProducts') ?>", { ids: selectedIds }, function(response) {
                    if (response.status === 'success') {
                        alert('Products deleted successfully');
                        table.ajax.reload();
                    } else {
                        alert('Failed to delete products');
                    }
                }, 'json');
            } else {
                alert('No products selected');
            }
        });

         // change product category
         $('#changeCategoryBtn').on('click', function() {
            var selectedIds = $('.product-checkbox:checked').map(function() {
                return this.value;
            }).get();
            var selectedCategory = $('#changeCategory').find(":selected").val();

            if(selectedCategory == ""){
                alert('please select category');
            }else{
                if (selectedIds.length > 0) {
                    $.post("<?= site_url('admin/product/changeProductsCategory') ?>", { ids: selectedIds, category: selectedCategory }, function(response) {
                        if (response.status === 'success') {
                            alert('Category updated successfully');
                            table.ajax.reload();
                        } else {
                            alert('Failed to updated products');
                        }
                    }, 'json');
                } else {
                    alert('please select at least one product to change their category');
                }
            }
        });

        $('#customCategory').on('change', function() {
            var categoryId = $(this).val();
            // console.log("categoryId", categoryId);
            if (categoryId) {
                $.ajax({
                    url: "<?= site_url('admin/product/getAttributeSetByCategory') ?>",
                    type: "POST",
                    data: { category_id: categoryId },
                    dataType: "json",
                    success: function(response) {
                        $('#customAttributeSet').empty().append('<option value="">-- Select Attribute Set --</option>');
                        $.each(response, function(index, attribute_set) {
                            $('#customAttributeSet').append('<option value="' + attribute_set.id + '">' + attribute_set.name + '</option>');
                        });
                    }
                });
            } else {
                $('#customAttributeSet').empty().append('<option value="">-- Select Attribute Set --</option>');
            }    
        });

        $('#customAttributeSet').on('change', function() {
            var attributeSetId = $(this).val();
            if (attributeSetId) {
                $.ajax({
                    url: "<?= site_url('admin/product/getAttributeNameByAttributeSet') ?>",
                    type: "POST",
                    data: { attribute_set_id: attributeSetId },
                    dataType: "json",
                    success: function(response) {
                        $('#customAttributeName').empty().append('<option value="">-- Select Attribute Name --</option>');
                        $.each(response, function(index, attribute_set) {
                            $('#customAttributeName').append('<option value="' + attribute_set.id + '">' + attribute_set.name + '</option>');
                        });
                    }
                });
            } else {
                $('#customAttributeName').empty().append('<option value="">-- Select Attribute Name --</option>');
            }    
        });

        // assign product attributes
        $('#assignAttributesBtn').on('click', function() {
            var selectedIds = $('.product-checkbox:checked').map(function() {
                return this.value;
            }).get();
            var selectedCategory = $('#changeCategory').find(":selected").val();



            if(selectedCategory == ""){
                alert('please select category');
            }else{
                if (selectedIds.length > 0) {

                    console.log("selectedIds", selectedIds);
                    console.log("selectedCategory", selectedCategory);

                    let redirect_url = "<?= site_url('admin/product/assign_product_attributes') ?>";
                    const product_ids = selectedIds.join(',');
                    redirect_url += '?category_id='+selectedCategory+'&product_ids='+product_ids;
                    console.log("redirect_url", redirect_url);
                    window.location.href = redirect_url;
                    
                } else {
                    alert('please select at least one product to assign attributes');
                }
            }
        });

        // assign product tags
        $('#assignTagsBtn').on('click', function() {
            var selectedIds = $('.product-checkbox:checked').map(function() {
                return this.value;
            }).get();

                if (selectedIds.length > 0) {

                    // console.log("selectedIds", selectedIds);

                    let redirect_url = "<?= site_url('admin/product/assign_product_tags') ?>";
                    const product_ids = selectedIds.join(',');
                    redirect_url += '?product_ids='+product_ids;
                    console.log("redirect_url", redirect_url);
                    window.location.href = redirect_url;
                    
                } else {
                    alert('please select at least one product to assign tags');
                }

        });

    });

</script>