<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Notification</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Send Notification</li>
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
                        <form class="form-horizontal" action="<?= base_url('admin/notificationsettings/send_notifications'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_area" name="edit_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="type" class="control-label">Send to <span class='text-danger text-sm'>*</span></label>
                                    <select name="send_to" id="send_to" class="form-control type_event_trigger" required="">
                                        <option value="all_users">All Users</option>
                                        <!-- <option value="specific_user">Specific User</option> -->
                                    </select>
                                </div>
                                <!-- for users -->
                                <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'users') ? '' : 'd-none' ?>
                                <div class="form-group row notification-users <?= $hiddenStatus ?>">
                                    <label for="user_id" class="control-label"> Users <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <input type="hidden" name="user_id" id="noti_user_id" value="">
                                        </select>
                                        <select name="select_user_id[]" class="search_user w-100" multiple data-placeholder=" Type to search and select users" onload="multiselect()">
                                            <?php
                                            // if (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'users') {
                                            //     $user_details = fetch_details('users', ['id' => $row['type_id']], 'id,name');
                                            //     if (!empty($user_details)) {
                                            ?>
                                                    <!-- <option value="<?php // echo $user_details[0]['id'] ?>" selected> <?php // echo $user_details[0]['name'] ?></option> -->
                                            <?php
                                            //     }
                                            // }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="type" class="control-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <select name="type" id="type" class="form-control type_event_trigger" required="">
                                            <!-- <option value=" ">Select Type</option> -->
                                            <option value="default" <?= (@$fetched_data[0]['type'] == "default") ? 'selected' : ' ' ?>>Default</option>
                                            <!-- <option value="categories" <?= (@$fetched_data[0]['type'] == "categories") ? 'selected' : ' ' ?>>Category</option>
                                            <option value="products" <?= (@$fetched_data[0]['type'] == "products") ? 'selected' : ' ' ?>>Product</option>
                                            <option value="notification_url" <?= (@$fetched_data[0]['type'] == "notification_url") ? 'selected' : ' ' ?>>Notification URL</option> -->
                                        </select>
                                    </div>
                                </div>

                                <div id="type_add_html">
                                    <!-- for category -->
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'categories') ? '' : 'd-none' ?>
                                    <div class="form-group notification-categories <?= $hiddenStatus ?> ">
                                        <label for="category_id"> Categories <span class='text-danger text-sm'>*</span></label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select category </option>
                                            <?php
                                            if (!empty($categories)) {
                                                foreach ($categories as $row) {
                                                    $selected = ($row['id'] == $fetched_data[0]['type_id'] && strtolower($fetched_data[0]['type']) == 'categories') ? 'selected' : '';
                                            ?>
                                                    <option value="<?= $row['id'] ?>" <?= $selected ?>> <?= $row['name'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- for notification url -->
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'notification_url') ? '' : 'd-none' ?>
                                    <div class="form-group notification-url <?= $hiddenStatus ?> ">

                                        <label for="notification_url"> Link <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" placeholder="https://example.com" name="link" value="<?= isset($fetched_data[0]['link']) ? output_escaping($fetched_data[0]['link']) : "" ?>">
                                    </div>
                                    <!-- for products -->
                                    <?php $hiddenStatus = (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'products') ? '' : 'd-none' ?>
                                    <div class="form-group row notification-products <?= $hiddenStatus ?>">
                                        <label for="product_id" class="control-label">Products <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-12">
                                            <select name="product_id" class="search_admin_product w-100" data-placeholder=" Type to search and select products" onload="multiselect()">
                                                <?php
                                                if (isset($fetched_data[0]['id']) && $fetched_data[0]['type']  == 'products') {
                                                    $product_details = fetch_details('products', ['id' => $row['type_id']], 'id,name');
                                                    if (!empty($product_details)) {
                                                ?>
                                                        <option value="<?= $product_details[0]['id'] ?>" selected> <?= $product_details[0]['name'] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="control-label ">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="title" id="title" value="<?= (isset($fetched_data[0]['title']) ? $fetched_data[0]['title'] : '') ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message" class="control-label">Message <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-md-12">
                                        <textarea name='message' class="form-control"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="checkbox" name="image_checkbox" id="image_checkbox">
                                        <span>Include Image</span>
                                    </div>
                                    <div class="col-md-12 d-none include_image">
                                        <label for="message" class="control-label">Image <small>(Recommended Size : 80 x 80 pixels)</small></label>
                                        <div class="col-sm-10">
                                            <div class='col-md-3'><a class="uploadFile img btn btn-primary text-white btn-sm" data-input='image' data-isremovable='1' data-is-multiple-uploads-allowed='0' data-toggle="modal" data-target="#media-upload-modal" value="Upload Photo"><i class='fa fa-upload'></i> Upload</a></div>
                                            <?php
                                            if (file_exists(FCPATH . @$fetched_data[0]['image']) && !empty(@$fetched_data[0]['image'])) {
                                            ?>
                                                <div class="container-fluid row image-upload-section">
                                                    <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                                        <div class='image-upload-div'><img class="img-fluid mb-2" src="<?= BASE_URL() . $fetched_data[0]['image'] ?>" alt="Image Not Found"></div>
                                                        <input type="hidden" name="image" value='<?= $fetched_data[0]['image'] ?>'>
                                                    </div>
                                                </div>
                                            <?php
                                            } else { ?>
                                                <div class="container-fluid row image-upload-section">
                                                    <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image d-none">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success">Send Notification</button>
                                </div>


                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title">Notification Details</h4>
                        </div>
                        <div class="card-innr">
                        
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Type</th>
                                        <!-- <th scope="col">Image</th> -->
                                        <!-- <th scope="col">Link</th> -->
                                        <th scope="col">Message</th>
                                        <th scope="col">Send to</th>
                                        <th scope="col">User(s) Name</th>
                                        <!-- <th scope="col">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notifications_result as $notifications) : ?>
                                    <tr>
                                        <td><?= $notifications['id'] ?></td>
                                        <td><?= $notifications['title'] ?></td>
                                        <td><?= $notifications['type'] ?></td>
                                        <!-- <td>
                                            <div class="image-box-100">
                                                <a href="<?= base_url().$notifications['image'] ?>"
                                                    data-toggle="lightbox" data-gallery="gallery">
                                                    <img class="rounded" src="<?= base_url().$notifications['image'] ?>">
                                                </a>
                                            </div>
                                        </td> -->
                                        <!-- <td><?= $notifications['link'] ?></td> -->
                                        <td><?= $notifications['message'] ?></td>
                                        <td><?= $notifications['send_to'] ?></td>
                                        <td><?= $notifications['users_id'] ?></td>
                                        <!-- <td style="text-align: center; ">
                                            <a href="<?= base_url('admin/offer/edit_offer?edit_id='.$notifications['id']) ?>"
                                                class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                                                data-id="1" data-url="admin/category/create_category"><i
                                                    class="fa fa-pen"></i></a>
                                            <a class="delete-offer btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="<?= $notifications['id'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td> -->
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