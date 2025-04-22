<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Upload Images</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Upload Images</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">

         <!-- atttribute set add modal  -->
        <div class="modal fade" id="attributeSet_add" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Upload New Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <section class="content">
                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="card card-info">
                            <!-- form start -->
                            <form class="form-horizontal" action="<?= base_url('admin/staticpages/upload_new_image'); ?>" method="POST" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Image <span class='text-danger text-sm'>*</span></label>
                                        <div class="col-md-6">
                                            <input type="file" id="image" name="image" class="form-control" required="true" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-success" id="submit_btn">Upload</button>
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
                </div>
            </div>
            </div>
        </div>
        
            <div class="row">
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="" data-toggle="modal" data-target="#attributeSet_add" class="btn btn-block  btn-outline-primary btn-sm">Upload Image</a>
                            </div>
                        </div>
                        <div class="card-innr" id="list_view_html">
                            <div class="card-head">
                                <h4 class="card-title">Upload Images</h4>
                            </div>
                            
                            <div class="card-body">

                                <table id="staticPagesImagesTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sr No.</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                            
                        </div><!-- .card-innr -->
                    </div><!-- .card-innr -->
                </div><!-- .card -->
            </div>
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>

<script>
    $(document).ready(function () {
        $('#staticPagesImagesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/staticpages/fetchUploadImages') ?>",
                "type": "POST"
            },
            // "columnDefs": [
            //     { "orderable": false, "targets": 3 }
            // ]
        });
    }); 
</script>