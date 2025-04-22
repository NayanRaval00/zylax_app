<?= $this->include('frontend/layouts/header') ?>

<section class="pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-md-12 myaccount-area">
            <div class="row">
               <div class="col-md-3"></div>
               <div class="col-md-3 change-password">
                  <h1 class="accountpage-title">Thank you</h1>
                  <br>
                  <?= view('frontend/partials/messages'); ?>
                  <br>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>


<?= $this->include('frontend/layouts/footer') ?>