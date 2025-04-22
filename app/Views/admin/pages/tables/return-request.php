<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Return Request</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Return Request</li>
                    </ol>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="request_rating_modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Return Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 main-content">
            <div class="card content-area p-4">
                <div class="card-innr">
                    <div class="gaps-1-5x"></div>
                    <table class='table-striped' id='return_request_table' data-toggle="table" data-url="<?= base_url('admin/return_request/view_return_request_list') ?>" data-click-to-select="true"
                     data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-query-params="queryParams">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="order_id" data-sortable="true">Order ID</th>
                                <th data-field="order_item_id" data-sortable="true">Order Item ID</th>
                                <th data-field="user_name" data-sortable="false">Username</th>
                                <th data-field="product_name" data-sortable="false">Product Name</th>
                                <th data-field="price" data-sortable="false">Price</th>
                                <th data-field="seller_id" data-sortable="false">Seller ID</th>
                                <th data-field="discounted_price" data-sortable="false" data-visible="false">Discounted Price</th>
                                <th data-field="quantity" data-sortable="false">Quantity</th>
                                <th data-field="sub_total" data-sortable="false">Sub Total</th>
                                <th data-field="status" data-sortable="false">Status</th>
                                <th data-field="operate" data-sortable="false">Actions</th>
                            </tr>
                        </thead>
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