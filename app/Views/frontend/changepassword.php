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
<section class="pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-md-12 myaccount-area">
            <div class="row">
               <?= view('frontend/partials/profile-nav'); ?>
               <div class="col-md-9 change-password">
                  <h1 class="accountpage-title">Change Password</h1>
                  <p>Create a new password. Ensure it differs from previous ones for security.</p>
                  <br>
                  <?= view('frontend/partials/messages'); ?>
                  <form name="form" method="post" class="password-form" id="passwordChangeForm" action="<?= site_url('profile/changepassword') ?>">
                     <?= csrf_field(); ?>  <!-- CSRF Token for security -->
                     <div class="col-lg-6">
                        <label>Current Password</label>
                        <div class="input-group">
                           <input type="password" name="oldpassword" class="form-control passwordinput" id="password1" placeholder="Current Password" required>
                           <span class="input-group-text toggle-password" data-target="password1">
                              <i class="bi bi-eye-fill"></i>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <label>New Password</label>
                        <div class="input-group">
                           <input type="password" name="newpassword" class="form-control passwordinput" id="password3" placeholder="New Password" required>
                           <span class="input-group-text toggle-password" data-target="password3">
                              <i class="bi bi-eye-fill"></i>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <label>Confirm Password</label>
                        <div class="input-group">
                           <input type="password" name="confirmpassword" class="form-control passwordinput" id="password2" placeholder="Confirm Password" required>
                           <span class="input-group-text toggle-password" data-target="password2">
                              <i class="bi bi-eye-fill"></i>
                           </span>
                        </div>
                     </div>
                     <div class="col-lg-6 mt-3">
                        <button type="submit" class="btn btn-primary">Change Password</button>
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