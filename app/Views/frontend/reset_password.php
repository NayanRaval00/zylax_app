<?= $this->include('frontend/layouts/header') ?>

<section class="pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-md-12 myaccount-area">
            <div class="row">
               <div class="col-md-3"></div>
               <div class="col-md-9 change-password">
                  <h1 class="accountpage-title">Reset Your Password</h1>
                  <br>
                  <?= view('frontend/partials/messages'); ?>
                  <form name="form" method="post" class="password-form" id="passwordChangeForm" action="<?= site_url('reset-password') ?>">
                     <?= csrf_field(); ?>  <!-- CSRF Token for security -->
                     <input type="hidden" name="token" value="<?= esc($token) ?>">
                     
                     <div class="col-lg-6">
                        <label>New Password</label>
                        <div class="input-group">
                           <input type="password" name="newpassword" class="form-control passwordinput1" id="password3" placeholder="New Password" required>
                           <span class="input-group-text toggle-password1" data-target="password3">
                              <i class="bi bi-eye-fill"></i>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <label>Confirm Password</label>
                        <div class="input-group">
                           <input type="password" name="confirmpassword" class="form-control passwordinput2" id="password2" placeholder="Confirm Password" required>
                           <span class="input-group-text toggle-password2" data-target="password2">
                              <i class="bi bi-eye-fill"></i>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-6 mt-3">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                     </div>
                  </form>


                  <br>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?= $this->include('frontend/layouts/footer') ?>