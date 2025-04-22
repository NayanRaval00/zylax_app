<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Ticket Types</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Ticket Types</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div id="attribute_value_id" class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Ticket Type Value</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/tickets/manage_ticket_types' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Ticket Types</a>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Ticket Types</h4>
                            </div>
                           
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align: center; ">ID</th>
                                        <th scope="col" style="text-align: center; ">Title</th>
                                        <th scope="col" style="text-align: center; ">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ticket_types_result as $ticket_type) : ?>
                                    <tr>
                                    <td style="text-align: center; "><?= $ticket_type['id'] ?></td>
                                        <td style="text-align: center; "><?= $ticket_type['title'] ?></td>
                                        <td style="text-align: center; ">
                                            <a href="<?= base_url('admin/tickets/edit_ticket_type?edit_id='.$ticket_type['id']) ?>"
                                                class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                                                data-id="1" data-url="admin/category/create_category"><i
                                                    class="fa fa-pen"></i></a>
                                            <a class="delete-ticket-type btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="<?= $ticket_type['id'] ?>">
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
