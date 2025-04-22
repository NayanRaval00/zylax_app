<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Shipping Category</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Shipping Category</li>
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
            <form class="form-horizontal" action="<?= base_url('admin/shippingcategory/add_shipping_category'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php if (isset($fetched_data[0]['id'])) { ?>
                  <input type="hidden" name="edit_attribute_set" value="<?= @$fetched_data[0]['id'] ?>">
                <?php  } ?>

                <div class="form-group row">
                  <label for="shipping_name" class="col-sm-2 col-form-label">Shipping Method <span class='text-danger text-sm'>*</span></label>
                  <div class="col-md-6">
                      <select class=" col-md-12  form-control comman_select2" id="admin_brand_list" name="shipping_name">
                          <option value="">Select Shipping Method</option>
                            <?php
                                foreach ($shipping_result as $row) {
                            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php
                                }
                            ?>
                        </select>
                  </div>
                </div>

                <div class="form-group row">
                    <label for="category_parent" class="col-sm-2 col-form-label">Select Category</label>
                    <div class="col-md-6">
                        <select id="category_parent" name="category[]" multiple>                            
                            <?php getCategories(); ?>

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="price" class="col-sm-2 col-form-label">Price <span class='text-danger text-sm'>*</span></label>
                  <div class="col-md-6">
                    <input type="number" class="form-control" id="price" placeholder="Price" name="price" value="<?= @$fetched_data[0]['price'] ?>">
                  </div>
                </div>

                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success"><?= (isset($fetched_data[0]['id'])) ? 'Update Shipping Category' : 'Add Shipping Category' ?></button>
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