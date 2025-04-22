<?php $db = \Config\Database::connect(); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Assign Products Attributes</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/product') ?>">Product</a></li>
                        <li class="breadcrumb-item active">Assign Products Attributes</li>
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

                        <?php if(isset($missmatch_category) && $missmatch_category != 1){ ?>

                             <!-- add attributes -->
                            <div id="attribute-container">       
                                <div class="row attribute-row mt-3">
                                    <div class="col-md-3">
                                        <select class="attribute-name form-control">
                                            <option value="">Select Attribute</option>
                                            <?php foreach ($attributeSets as $attribute_set) { ?>
                                                <option value="<?= $attribute_set['id'] ?>"><?= $attribute_set['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="attribute-value form-control">
                                            <option value="">Select Value</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control possible-value" placeholder="Possible Vaues of Attribute" />
                                    </div>
                                    <div class="col-md-3">
                                        <!-- <button type="button" class="btn btn-outline-primary add-attribute">Add</button> -->
                                        <button type="button" class="btn btn-outline-danger remove-attribute">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary add-attribute">Add New Attribute</button>
                                </div>
                                <div class="col-md-4">
                                    <label for="is_deleted" class="col-form-label">Is Delete Existing</label>
                                    <input type="checkbox" id="is_deleted" value="1" />
                                    <button type="button" id="saveAttributes" class="btn btn-primary">Assign Attributes</button>
                                </div>
                            </div>

                        <?php }else{ ?>

                            <div class="alert alert-warning">Selected Product category and attribute assigned category mismatch</div>
                           <div class="row">
                            <div class="col-md-4"></div>
                                <div class="col-md-4 text-center">
                                    <a href="<?= base_url('admin/product') ?>" class="btn btn-primary">Back to Product Page</a>
                                </div>
                            <div class="col-md-4"></div>
                           </div>

                        <?php } ?>
             
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
    // attributes level

    // Add new attribute row
    $(".add-attribute").on("click", function () {
        let newRow = $(".attribute-row:first").clone();
        // newRow.find("select").val(""); // Reset dropdowns
        // newRow.find("input").val(""); // Reset dropdowns

        // Reset the cloned dropdowns
        newRow.find(".attribute-name").find("select").val("");
        newRow.find(".attribute-value").empty().append('<option value="">Select Value</option>');

        $("#attribute-container").append(newRow);
    });

    // Remove attribute row
    $("#attribute-container").on("click", ".remove-attribute", function () {
        if ($(".attribute-row").length > 1) {
            $(this).closest(".attribute-row").remove();
        }
    });

    // Save attributes via AJAX
    $("#saveAttributes").click(function (e) {
        e.preventDefault();

        // new attributes 
        let newAttributes = [];
        $(".attribute-row").each(function () {
            let attribute_id = $(this).find(".attribute-name").val();
            let attribute_value_id = $(this).find(".attribute-value").val();
            let added_attribute_value = $(this).find(".possible-value").val();
            if (attribute_id && attribute_value_id) {
                newAttributes.push({ attribute_id, attribute_value_id, added_attribute_value });
            }
        });

        console.log("newAttributes", newAttributes);

        let productIds = "<?= $product_ids ?>"
        console.log("productIds", productIds);

        if (newAttributes.length === 0) {
            alert("Please select at least one attribute!");
            return;
        }


        const is_deleted = document.getElementById("is_deleted");

        let delete_attribute = 0;
        if (is_deleted.checked) {
            delete_attribute = 1;
        } else {
            delete_attribute = 0;
        }
        console.log("delete_attribute", delete_attribute);

        $.ajax({
            url: "<?= site_url('admin/product/saveMultipleProductAttributes') ?>", // Backend URL
            type: "POST",
            data: { attributes: newAttributes, products: productIds, is_deleted: delete_attribute },
            dataType: "json",
            success: function (response) {
                console.log("response", response);
                if (response.success) {
                    alert("Attributes saved successfully!");
                    let redirect_url = "<?= site_url('admin/product') ?>";
                    window.location.href = redirect_url;
                } else {
                    alert("Error saving attributes.");
                }
            },
            error: function () {
                alert("AJAX error.");
            }
        });
    });

    // $('.attribute-name').on('change', function() {
    $("#attribute-container").on("change", ".attribute-name", function () {
        var attributeSetId = $(this).val();
        console.log("attributeSetId", attributeSetId);
        let nearestAttributeNames = $(this).closest(".attribute-row").find(".attribute-value");
        if (attributeSetId) {
            $.ajax({
                url: "<?= site_url('admin/product/getAttributeNameByAttributeSet') ?>",
                type: "POST",
                data: { attribute_set_id: attributeSetId },
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
                    $.each(response, function(index, attribute_name) {
                        nearestAttributeNames.append('<option value="' + attribute_name.id + '">' + attribute_name.name + '</option>');
                    });
                }
            });
        } else {
            nearestAttributeNames.empty().append('<option value="">-- Select Attribute Name --</option>');
        }    
    });

});

</script>