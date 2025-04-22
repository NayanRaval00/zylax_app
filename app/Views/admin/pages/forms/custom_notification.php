<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/customnotification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                                <input type="hidden" id="udt_title" value="<?= @$fetched_data[0]['title'] ?>">
                            <?php
                            }
                            ?>
                            <div class=" card-body">
                                
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 control-label">Types <span class='text-danger text-sm'> * </span></label>
                                    <div class="col-sm-10">
                                        <select name="type" class="form-control type">
                                            <option value=" ">Select Types</option>
                                            <option value="otp">Otp</option>
                                            <option value="place_order">Place Order</option>
                                            <option value="seller_place_order">Seller Place Order</option>
                                            <option value="ticket_status">Ticket Status</option>
                                            <option value="settle_cashback_discount">Settle Cashback Discount</option>
                                            <option value="settle_seller_commission">Settle Seller Commission</option>
                                            <option value="customer_order_received">Customer Order Received</option>
                                            <option value="customer_order_processed">Customer Order Processed</option>
                                            <option value="delivery_boy_order_processed">Delivery Boy Order Processed</option>
                                            <option value="customer_order_shipped">Customer Order Shipped</option>
                                            <option value="customer_order_delivered">Customer Order Delivered</option>
                                            <option value="customer_order_cancelled">Customer Order Cancelled</option>
                                            <option value="customer_order_returned">Customer Order Returned</option>
                                            <option value="delivery_boy_return_order_assign">Delivery Boy Return Order Assign</option>
                                            <option value="customer_order_returned_request_decline">Customer Order Returned Request Decline</option>
                                            <option value="customer_order_returned_request_approved">Customer Order Returned Request Approved</option>
                                            <option value="delivery_boy_order_deliver">Delivery Boy Order Deliver</option>
                                            <option value="wallet_transaction">Wallet Transaction</option>
                                            <option value="bank_transfer_receipt_status">Bank Transfer Receipt Status</option>
                                            <option value="bank_transfer_proof">Bank Transfer Proof</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" id="update_title" class="form-control update_title" placeholder="Title Name" value="<?= (isset($fetched_data[0]['title'])) ? $fetched_data[0]['title'] : ""; ?>" />
                                    </div>
                                </div>
                                <div class="form-group row place_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< order_id >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag_input"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" id="text-box" class="form-control text-box" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row place_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>

                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row seller_place_order <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'seller_place_order') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< application_name >', '< order_id >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row settle_cashback_discount <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'settle_cashback_discount') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row settle_seller_commission <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'settle_seller_commission') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_received <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_received') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_processed <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_processed') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row delivery_boy_order_processed <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'delivery_boy_order_processed') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_shipped <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_shipped') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_delivered <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_delivered') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_cancelled <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_cancelled') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_returned <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_returned') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row delivery_boy_return_order_assign <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'delivery_boy_return_order_assign') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_returned_request_approved <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_returned_request_approved') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row customer_order_returned_request_decline <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'customer_order_returned_request_decline') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< cutomer_name >', '< order_item_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row wallet_transaction <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'wallet_transaction') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< currency >', '< returnable_amount >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row ticket_status <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'ticket_status') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row ticket_message <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'ticket_message') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row bank_transfer_receipt_status <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< status >', '< order_id >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row bank_transfer_proof <?= (isset($fetched_data[0]['id'])  && $fetched_data[0]['type'] == 'bank_transfer_proof') ? '' : 'd-none' ?>">
                                    <label for="message" class="col-sm-2 col-form-label"></label></label>
                                    <?php
                                    $hashtag = ['< order_id >', '< application_name >'];
                                    foreach ($hashtag as $row) { ?>
                                        <div class="col">
                                            <div class="hashtag"><?= $row ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
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
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
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
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                        
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($custom_notifications_result as $notifications) : ?>
                                    <tr>
                                        <td><?= $notifications['id'] ?></td>
                                        <td><?= $notifications['title'] ?></td>
                                        <td><?= $notifications['type'] ?></td>
                                        <td><?= $notifications['message'] ?></td>
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