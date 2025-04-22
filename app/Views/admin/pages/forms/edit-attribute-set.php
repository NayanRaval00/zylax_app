<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Attribute set</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Attribute set</li>
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
            <form class="form-horizontal" action="<?= base_url('admin/attributeset/update_attribute_set'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php if (isset($fetched_data['id'])) { ?>
                  <input type="hidden" name="edit_attribute_set" value="<?= @$fetched_data['id'] ?>">
                  <input type="hidden" name="edit_attribute_set_category" value="<?= $selected_categories ?>">
                <?php  } ?>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Name <span class='text-danger text-sm'>*</span></label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?= @$fetched_data['name'] ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="slug" class="col-sm-2 col-form-label">Slug <span class='text-danger text-sm'>*</span></label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="slug" placeholder="Slug" name="slug" value="<?= @$fetched_data['slug'] ?>">
                  </div>
                </div>

                <div class="form-group row">
                    <label for="category_parent" class="col-sm-2 col-form-label">Select Category</label>
                    <div class="col-md-6">
                        <select id="category_parent" name="category[]" multiple>                            
                            <?php getCategoriesMultiple(0, '-', $selected_categories); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success"><?= (isset($fetched_data['id'])) ? 'Update Attribute Set' : 'Add Attribute Set' ?></button>
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