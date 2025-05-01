<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>View Admin</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ol>
                </div>
            </div>
            <!-- <div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id='customer-address-modal'>
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Address Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card content-area p-4">
                        <form id="ordersData" method="post">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="order_daterange" cla ss="form-label"><strong>Date Range</strong></label>
                                    <input id="order_daterange" class="form-control" type="text" name="order_daterange"
                                        placeholder="Select Date Range">
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label"><strong>Keyword</strong></label>
                                    <input type="text" id="orderEmail" name="orderEmail" class="form-control"
                                        placeholder="Search by Keyword...">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <input type="submit" class="btn btn-success w-100" value="Search">
                                </div>
                            </div>
                        </form>
                    </div><!-- .card-content -->
                </div><!-- .col-md-12 -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card content-area p-4">
                        <table id="orderTable" class="table table-striped display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="false">Name</th>
                                    <th data-field="email" data-sortable="true">Email</th>
                                    <th data-field="mobile" data-sortable="true">Mobile No</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- .card-content -->
                </div><!-- .col-md-12 -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
$(document).ready(function () {
    let table = $('#orderTable').DataTable({
        "sDom":"ltipr",
        scrollX: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('admin/Customer/fetch_admin') ?>",
            "type": "POST",
            "data": function (d) {
                d.order_daterange = $('#order_daterange').val() || '';
                d.orderEmail = $('#orderEmail').val() || '';
            }
        }
    });

    // Reload table when filters change
    $("#ordersData").on("submit", function (event) {
        event.preventDefault();
        table.ajax.reload(null, false); // Keep pagination state after reload
    });
});


$(document).ready(function () {
    $('#order_daterange').daterangepicker({
        autoUpdateInput: false, // Do not auto-fill input
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        }
    });

    // When a date is selected, update the input
    $('#order_daterange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    // Clear input when cancel is clicked
    $('#order_daterange').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
});

</script>