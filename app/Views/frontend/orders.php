<?php
// foreach ($data as $key => $value) {
//    # code...
//    echo"<pre>";
//    print_r($value);
// }

?>
<?= $this->include('frontend/layouts/header') ?>
<section class="breadcrumb-img"  style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg') ?>)">
   <div class="container">
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url();?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">My Profile</li>
         </ol>
      </nav>
   </div>
</section>
<!-- Content Section -->
<section class="pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-md-12 myaccount-area">
            <div class="row">
            <?= view('frontend/partials/profile-nav'); ?>
               <div class="col-md-9 order-history">
                  <h1 class="accountpage-title">Order History</h1>
                  <div class="table-content table-responsive">
                     <table class="table">
                        <thead>
                           <tr class="second-head">
                              <th>ORDER ID</th>
                              <th>STATUS</th>
                              <th>INVOICE</th>
                              <th>DATE</th>
                              <th>TOTAL</th>
                              <th>Details</th>
                              <th>Invoice</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($data as $order){ ?>
                           <tr>
                              <td>#<?php echo $order['tracking_order_id'] ?></td>
                              <td class="status-in-progress"><?php echo $order['order_status'] ?></td>
                              <th><a href="#">Invoice</a> </th>
                              <td><?php echo date("d-M-Y : h:iA", strtotime($order['created_at'])) ?></td>
                              <td><?php echo '$'.number_format($order['tran_total_amt'], 2) ?></td>
                              <td><a href=" <?php echo base_url('user/orders/showDetails/'.$order['tracking_order_id']) ?>" class="text-danger"><b>View Details →</b></a></td>
                              <td><a href=" <?php echo base_url('user/orders/downloadInvoice/'.$order['tracking_order_id']) ?>" class="text-danger"><b>Invoice →</b></a></td>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
                  <br>
                  <div class="row">
                     <ul class="pagination">
                        <li><a href="#" class="leftarrow"><i class="bi bi-arrow-left"></i></a></li>
                        <!-- Disabled Previous Button -->
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#" class="rightarrow"><i class="bi bi-arrow-right"></i></a></li>
                        <!-- Next Button -->
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->include('frontend/layouts/footer') ?>