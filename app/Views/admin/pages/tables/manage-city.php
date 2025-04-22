<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage City</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">City</li>
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
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/area/add_city'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_city" name="edit_city" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                                <input type="hidden" id="city_name" name="city_name" value="<?= @$fetched_data[0]['name'] ?>">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="city_name">City Name <span class='text-danger text-sm'>*</span></label>
                                    </div>
                                    <div class="form-group col-md-9">
                                        <input type="text" class="form-control" name="city_name" id="city_name" value="<?= (isset($fetched_data[0]['name']) ? $fetched_data[0]['name'] : '') ?>" <?= isset($fetched_data[0]['id']) ? 'disabled' : ''  ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="minimum_free_delivery_order_amount" class="control-label">Minimum Free Delivery Order Amount <span class='text-danger text-xs'>*</span></label>
                                    </div>
                                    <div class="form-group col-md-9">
                                        <input type="number" class="form-control" name="minimum_free_delivery_order_amount" id="minimum_free_delivery_order_amount" min="0" value="<?= (isset($fetched_data[0]['minimum_free_delivery_order_amount']) ? $fetched_data[0]['minimum_free_delivery_order_amount'] : '') ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="delivery_charges" class="control-label">Delivery Charges <span class='text-danger text-xs'>*</span></label>
                                    </div>
                                    <div class="form-group col-md-9">
                                        <input type="number" class="form-control" name="delivery_charges" id="delivery_charges" min="0" value="<?= (isset($fetched_data[0]['delivery_charges']) ? $fetched_data[0]['delivery_charges'] : '') ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update City' : 'Add City' ?></button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit City</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title">City Details</h4>
                        </div>
                        <div class="card-innr">
                            
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Minimum Free Delivery Order Amount</th>
                                        <th scope="col">Delivery Charges</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cities_result as $cities) : ?>
                                    <tr>
                                        <td><?= $cities['id'] ?></td>
                                        <td><?= $cities['name'] ?></td>
                                        <td><?= $cities['minimum_free_delivery_order_amount'] ?></td>
                                        <td><?= $cities['delivery_charges'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>