<?php $db = \Config\Database::connect(); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Assign Products Tags</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/product') ?>">Product</a></li>
                        <li class="breadcrumb-item active">Assign Products Tags</li>
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
             
                        <div class="row attribute-row mt-3">
                            <div class="col-md-12">
                                <label for="category_parent">Select Tags</label>
                                <select id="category_parent" name="tags[]" multiple>                            
                                    <?php getProductTagsMultiple(); ?>
                                </select>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <button type="button" id="saveProductTags" class="btn btn-primary">Assign Tags</button>
                            </div>
                        </div>                        

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

    // Save product tags via AJAX
    $("#saveProductTags").click(function (e) {
        e.preventDefault();

        let selectedTags = $("#category_parent").val();
        console.log("selectedTags", selectedTags); // Returns an array

        let productIds = "<?= $product_ids ?>"
        console.log("productIds", productIds);

        if (selectedTags.length === 0) {
            alert("Please select at least one tag!");
            return;
        }

        $.ajax({
            url: "<?= site_url('admin/product/saveMultipleProductTags') ?>", // Backend URL
            type: "POST",
            data: { tags: selectedTags, products: productIds },
            dataType: "json",
            success: function (response) {
                // console.log("response", response);
                if (response.success) {
                    alert("Product Tags saved successfully!");
                    let redirect_url = "<?= site_url('admin/product') ?>";
                    window.location.href = redirect_url;
                } else {
                    alert("Error saving Product Tags.");
                }
            },
            error: function () {
                alert("AJAX error.");
            }
        });
    });

});

</script>