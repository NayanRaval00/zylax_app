<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Contact Us</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Contact Us</li>
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
            <!-- form start -->
            <form class="form-horizontal" action="<?= base_url('admin/contactus/update_contact_settings'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body pad">
                <label for="other_images">Contact Us </label>
                <!-- <a href="<?= base_url('admin/contact-us/contact-us-page') ?>" target='_blank' class="btn btn-primary btn-xs" title='View Contact Us'><i class='fa fa-eye'></i></a> -->
                <div class="mb-3">

                  <textarea name="contact_input_description" class="textarea addr_editor" placeholder="Place some text here text_editor">
                          <?= $contact_info ?>
                        </textarea>
                </div>

                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success">Update Contact Info</button>
                </div>
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