<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Ticket System</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Ticket System</li>
                    </ol>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="col-md-12 main-content">
            <div class="card content-area p-4">
                <div class="card-innr">
                    
                    <div class="card-head">
                        <h4 class="card-title">Ticket System</h4>
                    </div>
                    
                    <div class="card-body">

                        <table id="custom_category_table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Ticket Type</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">subject</th>
                                    <th scope="col">email</th>
                                    <th scope="col">description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date Created</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ticket_result as $ticket) : ?>
                                <tr>
                                    <td><?= $ticket['id'] ?></td>
                                    <td><?= $ticket['ticket_type_id'] ?></td>
                                    <td><?= $ticket['user_id'] ?></td>
                                    <td><?= $ticket['subject'] ?></td>
                                    <td><?= $ticket['description'] ?></td>
                                    <td>
                                        <?php if($ticket['status'] == 1): ?>
                                        <a class="badge badge-success text-white">Active</a>
                                        <?php else: ?>
                                        <a class="badge badge-danger text-white">Inactive</a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $ticket['date_created'] ?></td>
                                    <td style="text-align: center; ">
                                        
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                </div><!-- .card-innr -->
            </div><!-- .card -->
        </div>
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>