<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Attribute Value</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Attribute Value</li>
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
                        
                        <!-- form start  -->
                        <form class="form-horizontal" action="<?= base_url('admin/attributevalue/add_attribute_value'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="attributes" class="col-sm-2 col-form-label">Select Attributes <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="attributes" name="attributes_id">
                                            <option value=""> None </option>
                                            <?php foreach ($attributes as $row) {
                                            ?>
                                                <option value="<?= $row['id'] ?>" <?= (isset($fetched_data[0]['attribute_id']) && $fetched_data[0]['attribute_id'] == $row['id']) ? 'selected' : '' ?>> <?= $row['name'] ?>
                                                </option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="value" class="col-sm-2 col-form-label">Value <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" pattern="^[^,]*$" id="value" placeholder="value" name="value" value="<?= @$fetched_data[0]['value'] ?>">
                                    </div>
                                </div>
                                <?php
                                $no_display_image =  $no_display_color = "";
                                if (isset($fetched_data[0]['id'])) { ?>
                                    <input type="hidden" name="edit_attribute_value" value="<?= @$fetched_data[0]['id'] ?>">
                                <?php  } ?>
                                <?php
                                if (isset($fetched_data[0]['swatche_type']) && $fetched_data[0]['swatche_type'] == "1") {
                                    $no_display_image = 'style="display: none;"';
                                } else if (isset($fetched_data[0]['swatche_type']) && $fetched_data[0]['swatche_type'] == "2") {
                                    $no_display_color = 'style="display: none;"';
                                } else {
                                    $no_display_image =  $no_display_color = 'style="display: none;"';
                                }
                                ?>
                                <!-- <div class="form-group row">
                                    <label for="attribute_value_type" class="col-sm-2 col-form-label">Select Attribute Swatche Type </label>
                                    <div class="col-sm-10">
                                        <select class="form-control swatche_type" name="swatche_type">
                                            <option value="0" <?= (@$fetched_data[0]['swatche_type'] == "0") ? "selected" : ""; ?>> Default </option>
                                            <option value="1" <?= (@$fetched_data[0]['swatche_type'] == "1") ? "selected" : ""; ?>> Color </option>
                                            <option value="2" <?= (@$fetched_data[0]['swatche_type'] == "2") ? "selected" : ""; ?>> Image </option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group row" id="swatche_color" <?= $no_display_color  ?>>
                                    <label for="value" class="col-sm-2 col-form-label">Select Color </label>
                                    <div class="col-sm-10">
                                        <input type="color" class="form-control" id="swatche_value" placeholder="Color hex" name="swatche_value" value="<?= (isset($fetched_data[0]['swatche_type']) && !empty($fetched_data) && $fetched_data[0]['swatche_type'] == "1") ? $fetched_data[0]['swatche_value'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row" id="swatche_image" <?= $no_display_image  ?>>
                                    <label for="swatche_image" class="col-sm-2 col-form-label">Select Image </label>
                                    <div class="col-sm-6">
                                        <input type="file" id="swatche_image" name="swatche_image" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success"><?= (isset($fetched_data[0]['id'])) ? 'Update Attribute Value' : 'Add Attribute Value' ?></button>
                                </div>
                            </div>

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