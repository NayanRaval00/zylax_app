<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>About Us</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">About Us</li>
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
            <form class="form-horizontal" action="<?= base_url('admin/aboutus/update_about_us_settings'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body pad">
                <label for="other_images"> About Us </label>
                <!-- <a href="<?= base_url('admin/about-us/about-us-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View About Us'><i class='fa fa-eye'></i></a> -->
                <div class="mb-3">
                  <textarea name="about_us_input_description" class="textarea addr_editor" placeholder="Place some text here">
                          <?= @$about_us ?>
                        </textarea>
                </div>
                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success">Update About Us</button>
                </div>
              </div>

              <!-- /.card-body -->
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