<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Customers</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-innr">


                            <div class="">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#content1">List Users</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#content2">List Admins</a>
                                    </li>

                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="content1">
                                        <div class="gaps-1-5x row d-flex adjust-items-center">
                                            <h1>List Users</h1>
                                        </div>
                                        <table id="custom_category_table" class='table-striped'>
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-sortable="true">ID</th>
                                                    <th data-field="name" data-sortable="false">Name</th>
                                                    <th data-field="email" data-sortable="true">Email</th>
                                                    <th data-field="mobile" data-sortable="true">Mobile No</th>
                                                    <th data-field="balance" data-sortable="true">Balance</th>
                                                    <th data-field="date" data-sortable="true">Date</th>
                                                    <th data-field="status" data-sortable="true">Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($users as $user) : ?>
                                                    <tr>
                                                        <td><?= $user['id'] ?></td>
                                                        <td><?= $user['username'] ?></td>
                                                        <td><?= $user['email'] ?></td>
                                                        <td><?= $user['mobile'] ?></td>
                                                        <td><?= $user['balance'] ?></td>
                                                        <td><?= $user['created_at'] ?></td>
                                                        <td><?= $user['status'] == 1 ? 'Active' : 'Inactive' ?></td>

                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tbody>

                                                <tr>
                                                    <td colspan="8" class="text-center">No data found</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="content2">
                                        <div class="gaps-1-5x row d-flex adjust-items-center">
                                            <h1>List Admins</h1>
                                        </div>
                                        <table id="" class='table table-striped'>
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-sortable="true">ID</th>
                                                    <th data-field="name" data-sortable="false">UserName</th>
                                                    <th data-field="email" data-sortable="true">Email</th>
                                                    <th data-field="mobile" data-sortable="true">Mobile No</th>
                                                    <th data-field="date" data-sortable="true">Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($admins as $admin) : ?>
                                                    <tr>
                                                        <td><?= $admin['id'] ?></td>
                                                        <td><?= $admin['username'] ?></td>
                                                        <td><?= $admin['email'] ?></td>
                                                        <td><?= $admin['mobile'] ?></td>
                                                        <td><?= $admin['created_at'] ?></td>

                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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