<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">           
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Customer Address</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Customer Address</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php

// print_r($address);

// die;
     ?>
    <section class="content address-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='customer-address-table' data-toggle="table">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">Id</th>
                                        <th data-field="mobile" data-sortable="false">Email</th>
                                        <th data-field="mobile" data-sortable="false">Company</th>
                                        <th data-field="address" data-sortable="false" data-visible="false">Mobile</th>
                                        <th data-field="landmark" data-sortable="false">Address 1</th>
                                        <th data-field="area" data-sortable="false">Adress 2</th>
                                        <th data-field="city" data-sortable="false">City</th>
                                        <th data-field="state" data-sortable="false">State</th>
                                        <th data-field="pincode" data-sortable="false">Pincode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach($address as $useraddress){
                                    ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $useraddress['email'] ?></td>
                                        <td><?php echo $useraddress['company'] ?></td>
                                        <td><?php echo $useraddress['phoneno'] ?></td>
                                        <td><?php echo $useraddress['address_1'] ?></td>
                                        <td><?php echo $useraddress['address_2'] ?></td>
                                        <td><?php echo $useraddress['city'] ?></td>
                                        <td><?php echo $useraddress['state'] ?></td>
                                        <td><?php echo $useraddress['pincode'] ?></td>
                                    </tr>
                                    <?php $i++; } ?>
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
