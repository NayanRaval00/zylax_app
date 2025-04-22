<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Country List</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Countries </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Countries </h4>
                            </div>
                           
                                <table id="custom_category_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Numeric Code</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Phonecode</th>
                                            <th scope="col">Currency</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($countries_result as $countries) : ?>
                                        <tr>
                                            <td><?= $countries['id'] ?></td>
                                            <td><?= $countries['numeric_code'] ?></td>
                                            <td><?= $countries['name'] ?></td>
                                            <td><?= $countries['phonecode'] ?></td>
                                            <td><?= $countries['currency'] ?></td>
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