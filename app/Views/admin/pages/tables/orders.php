<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Orders</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
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
                                <div class="col-md-2">
                                    <label for="orderStatusGet" class="form-label"><strong>Order Status</strong></label>
                                    <select id="orderStatusGet" name="order_status" class="form-control">
                                        <option value="all">All Orders</option>
                                        <option value="progress">In Progress</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancel">Canceled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="paymentStatusGet" class="form-label"><strong>Payment
                                            Status</strong></label>
                                    <select id="paymentStatusGet" name="payment_status" class="form-control">
                                        <option value="all" selected>All Payments</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="refunded">Refunded</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label>User Type</label>
                                    <select class="form-control" name="usertype" id="usertype">
                                        <option value="">Select...</option>
                                        <option value="guest"> Guest</option>
                                        <option value="regular">Regular</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                    <input type="submit" class="btn btn-success w-100" value="Search">
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
                                    <th scope="col">Tracking Id</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Order Status</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">View</th>
                                    <th scope="col">Print</th>
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
            "url": "<?= base_url('admin/adminorders/fetch_order') ?>",
            "type": "POST",
            "data": function (d) {
                d.order_daterange = $('#order_daterange').val() || '';
                d.orderEmail = $('#orderEmail').val() || '';
                d.order_status = $('#orderStatusGet').val() || 'all';
                d.payment_status = $('#paymentStatusGet').val() || 'all';
                d.usertype = $('#usertype').val() || '';
            }
        }
    });

    // Reload table when filters change
    $("#ordersData").on("submit", function (event) {
        event.preventDefault();
        table.ajax.reload(null, false); // Keep pagination state after reload
    });
});



// $(document).ready(function() {
//     // Get today's date in YYYY-MM-DD format
//     var today = moment().format('YYYY-MM-DD');

//     // Set default date range to today's date
//     $('#order_daterange').daterangepicker({
//         startDate: today,
//         endDate: today,
//         locale: {
//             format: 'YYYY-MM-DD',
//             cancelLabel: 'Clear'
//         }
//     });

//     // Update input value when date is selected
//     $('#order_daterange').on('apply.daterangepicker', function(ev, picker) {
//         $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
//         'YYYY-MM-DD'));
//     });

//     // Clear input when cancel is clicked
//     $('#order_daterange').on('cancel.daterangepicker', function(ev, picker) {
//         $(this).val('');
//     });
// });

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


// $(document).ready(function () {
//     $("#ordersData").submit(function (event) {
//         event.preventDefault();

//         $.ajax({
//             url: '<?php echo base_url('admin/adminorders/fetch_order') ?>',
//             type: $(this).attr("method"),
//             data: $(this).serialize(),
//             success: function (response) {
//                 // $("#orderTable").html();
//                 console.log("Form submitted successfully:", response);
//             },
//             error: function (xhr, status, error) {
//                 console.error("Error submitting form:", error);
//             }
//         });
//     });
// });

</script>