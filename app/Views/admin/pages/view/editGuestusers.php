<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit Guest User</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Guest User</li>
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
                        <div class="card content-area p-4">
                        <form action="<?php echo base_url() ?>/admin/adminorders/updateguestuser" method="POST" class="form-horizontal">
                            <div class="card-body">
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success">
                                        <?= session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                <?php endif; ?>
                                <div class="row">
                                    <input type="hidden" value="<?= $user['id'] ?>" name="user_id">
                                    <div class="col-md-4">
                                        <label for="username" class="col-form-label">Username <span class='text-danger text-sm'>*</span></label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                            value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="email" class="col-form-label">Email <span class='text-danger text-sm'>*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                            value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="password" class="col-form-label">Password <span class='text-danger text-sm'>*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                            value="<?= htmlspecialchars($user['password']) ?>" required>
                                    </div>
                                </div>
                                <div class="ro">
                                    <div class="col-md-2 mt-2">
                                        <button class="btn btn-success" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div><!-- .card-content -->
                    </div>
                </div><!-- .col-md-12 -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </section>

    <!-- /.content -->
</div>
<script>
</script>