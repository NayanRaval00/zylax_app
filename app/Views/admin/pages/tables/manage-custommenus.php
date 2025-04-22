<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Custom Menus</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom Menus</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" id="category_form" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Custom Menus</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <!-- <div class="col-md-12">
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-primary " autofocus="autofocus" id='list_view'><i class="fas fa-list"></i> List View</button>
                                <button type="button" class="btn btn-primary" id='tree_view'><i class="fas fa-stream"></i> Tree View</button>
                            </div>
                        </div> -->
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/menu/create_custommenu' ?>"
                                    class="btn btn-block  btn-outline-primary btn-sm">Add New Custom Custom Menus</a>
                            </div>
                        </div>
                        <div class="card-innr" id="list_view_html">
                            <div class="card-head">
                                <h4 class="card-title">Custom Menus</h4>
                            </div>
                            <div class="card-body">

                                <table id="customMenusTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Custom Menu</th>
                                            <th scope="col">Parent Menu</th>
                                            <th scope="col">Sort</th>
                                            <th scope="col">Sub Custom</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div><!-- .card-innr -->
                        <div id="tree_view_html">
                        </div>
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
        $('#customMenusTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/menu/fetchCustomMenus') ?>",
                "type": "POST"
            },
            // "columnDefs": [
            //     { "orderable": false, "targets": 3 }
            // ]
        });
    });

</script>