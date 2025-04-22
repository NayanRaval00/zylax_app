<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4> Manage Promo Code</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Manage Promo Code</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Manage Promo Code</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id='add_promocode'>
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Promo Code</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/promocode/create_promo_code' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Promo Code</a>
                            </div>
                        </div>
                        <div class="card-innr">
                          
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Promo Code</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Discount type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Is Cashback</th>
                                        <!-- <th scope="col">View Promocode</th> -->
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($promo_result as $promo) : ?>
                                    <tr>
                                        <td><?= $promo['id'] ?></td>
                                        <td><?= $promo['promo_code'] ?></td>
                                        <td>
                                            <div class="image-box-100">
                                                <a href="<?= base_url().$promo['image'] ?>"
                                                    data-toggle="lightbox" data-gallery="gallery">
                                                    <img class="rounded" src="<?= base_url().$promo['image'] ?>">
                                                </a>
                                            </div>
                                        </td>
                                        <td><?= $promo['message'] ?></td>
                                        <td><?= $promo['start_date'] ?></td>
                                        <td><?= $promo['end_date'] ?></td>
                                        <td><?= $promo['discount'] ?></td>
                                        <td><?= $promo['discount_type'] ?></td>
                                        <td>
                                            <?php if($promo['status'] == 1): ?>
                                            <a class="badge badge-success text-white">Active</a>
                                            <?php else: ?>
                                            <a class="badge badge-danger text-white">Inactive</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($promo['is_cashback'] == 1): ?>
                                            <a class="badge badge-success text-white">ON</a>
                                            <?php else: ?>
                                            <a class="badge badge-danger text-white">OFF</a>
                                            <?php endif; ?>
                                        </td>
                                        <!-- <td><?= $promo['list_promocode'] ?></td> -->
                                        <td style="text-align: center; ">
                                            <a href="<?= base_url('admin/promocode/edit_promo_code?edit_id='.$promo['id']) ?>"
                                                class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                                                data-id="1" data-url="admin/category/create_category"><i
                                                    class="fa fa-pen"></i></a>
                                            <a class="delete-promo-code btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="<?= $promo['id'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
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