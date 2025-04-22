<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Ticket Types</h4>
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
                        
                        <form class="form-horizontal" action="<?= base_url('admin/tickets/update_ticket_type'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php if (isset($fetched_data['id'])) { ?>
                                    <input type="hidden" name="edit_ticket_type" value="<?= @$fetched_data['id'] ?>">
                                <?php  } ?>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="<?= @$fetched_data['title'] ?>">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success"><?= (isset($fetched_data['id'])) ? 'Update Ticket Type' : 'Add Ticket Type' ?></button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <!--/.col-md-12-->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>