<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Product tag</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
            <li class="breadcrumb-item active">Product tag</li>
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
            <form class="form-horizontal" action="<?= base_url('admin/producttag/update_product_tag'); ?>" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php if (isset($fetched_data['id'])) { ?>
                  <input type="hidden" name="edit_product_tag" value="<?= @$fetched_data['id'] ?>">
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
                    <label for="description">Description </label>
                    <div class="mb-3">
                        <textarea name="description" class="textarea addr_editor" id="description" placeholder="Place some text here"><?= (isset($fetched_data['id'])) ? output_escaping(str_replace('\r\n', '&#13;&#10;', $fetched_data['description'])) : ''; ?></textarea>
                    </div>
                </div>

                <hr class="mt-4">
                <h4 class="bg-light m-0 px-2 py-3">SEO Configuration</h4>

                <div class="d-flex bg-light">
                    <div class="form-group col-sm-6">
                        <label for="seo_page_title" class="form-label form-label-sm d-flex">
                            SEO Page Title
                        </label>
                        <input type="text" class="form-control" id="seo_page_title"
                            placeholder="SEO Page Title" name="seo_page_title"
                            value="<?= isset($fetched_data['seo_page_title']) ? output_escaping($fetched_data['seo_page_title']) : "" ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="seo_meta_keyword" class="form-label form-label-sm d-flex">
                            SEO Meta Keywords
                        </label>
                        <input type="text" name='seo_meta_keyword' class='form-control' id='seo_meta_keyword' placeholder="SEO Meta Keywords" value="<?= isset($fetched_data['seo_meta_keywords']) ? output_escaping($fetched_data['seo_meta_keywords']) : "" ?>" />
                    </div>
                </div>
                <div class="d-flex bg-light">

                    <div class="form-group col-sm-6">
                        <label for="seo_meta_description" class="form-label form-label-sm d-flex">
                            SEO Meta Description
                        </label>
                        <textarea class="form-control" id="seo_meta_description"
                            placeholder="SEO Meta Keywords" name="seo_meta_description"><?= isset($fetched_data['seo_meta_description']) ? output_escaping($fetched_data['seo_meta_description']) : "" ?></textarea>
                    </div>

                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="seo_og_image">SEO Open Graph Image <small>(Recommended Size : 131 x 131 pixels)</small></label>
                            <div class="col-sm-10">
                                <div class='col-md-12'>
                                <input type="file" id="seo_og_image" name="seo_og_image" class="form-control" />
                                </div>                                                
                            </div>
                            <?php if($fetched_data['seo_og_image']): ?>
                                <label class="text-danger mt-3">*Only Choose When Update is necessary</label>
                                <div class="container-fluid row image-upload-section">
                                    <div class="col-md-3 col-sm-12 shadow p-3 mb-5 bg-white rounded m-4 text-center grow image">
                                        <div class="image-upload-div">
                                            <img class="img-fluid mb-2" src="<?= base_url().$fetched_data['seo_og_image'] ?>" alt="Image Not Found">
                                        </div>
                                        <input type="hidden" name="category_seo_image" value="<?= $fetched_data['seo_og_image'] ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-success"><?= (isset($fetched_data['id'])) ? 'Update Product Tag' : 'Add Product Tag' ?></button>
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