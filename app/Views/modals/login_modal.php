<div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-body p-4">
            <div class="row">
               <!-- Left Column -->
               <div class="col-md-6">
                  <div class="text-center mb-3">
                     <img src="<?php echo base_url('assets/frontend/images/zylax-logo.png'); ?>" alt="Zylax Logo" class="img-fluid" style="max-width: 120px;">
                  </div>
                  <!-- Login Form -->
                  <div id="loginForm">
                     <h3 class="text-center">Login</h3>
                     <p class="text-center">Login to access your Zylax account</p>
                     <div class="message">
                     </div>
                     <form method="POST">
                        <div class="mb-2">
                           <label for="email" class="form-label">Email</label>
                           <input type="email" class="form-control" id="email" name="email_or_username" required/>
                        </div>
                        <div class="mb-2 position-relative">
                           <label for="password" class="form-label">Password</label>
                           <input type="password" class="form-control type-password" id="password" name="password" required />
                           <span class="toggle-password position-absolute end-0 top-50 me-3" style="cursor: pointer;">
                           <i class="bi bi-eye-slash"></i>
                           </span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                           <div>
                              <input type="checkbox" id="rememberMe">
                              <label for="rememberMe">Remember me</label>
                           </div>
                           <a href="#" id="forgotPasswordLink" class="text-decoration-none">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                     </form>
                     <p class="text-center mt-2 mb-0">
                        Don’t have an account? <a href="#" id="goToSignUp" class="text-decoration-none">Sign up</a>
                     </p>
                  </div>
                  <!-- Forgot Password Form (Initially Hidden) -->
                  <div id="forgotPasswordForm" class="d-none">
                     <a href="#" id="backToLogin" class="d-block mb-2">&lt; Back to Login</a>
                     <h3>Forgot your password?</h3>
                     <p>Don’t worry, happens to all of us. Enter your email below to recover your password.</p>
                     <div class="message">
                     </div>
                     <form method="POST">
                        <div class="mb-3">
                           <label for="forgotEmail" class="form-label">Email</label>
                           <input type="email" class="form-control" id="forgotEmail" name="email" placeholder="Enter your email">
                        </div>
                        <button type="submit" id="forgetp" class="btn btn-warning w-100 mt-3">Reset Password</button>
                     </form>
                  </div>
                  <!-- Sign Up Form (Initially Hidden) -->
                  <div id="signUpForm" class="d-none">
                     <h3 class="text-center">Sign up</h3>
                     <p class="text-center">Let’s get you all set up so you can access your personal account.</p>
                     <div class="message"></div>
                     <form method="POST">
                        <div class="row">
                           <!-- First Name and Last Name -->
                           <div class="col-md-6 mb-2 form-group">
                              <label for="firstName" class="form-label">First Name</label>
                              <input type="text" class="form-control" id="firstName" name="fname" required />
                           </div>
                           <div class="col-md-6 mb-2 form-group">
                              <label for="lastName" class="form-label">Last Name</label>
                              <input type="text" class="form-control" id="lastName" name="lname" required />
                           </div>
                           <!-- Email and Phone Number -->
                           <div class="col-md-6 mb-2 form-group">
                              <label for="signUpEmail" class="form-label">Email</label>
                              <input type="email" class="form-control" id="signUpEmail" name="email" required />
                           </div>
                           <div class="col-md-6 mb-2 form-group">
                              <label for="phoneNumber" class="form-label">Phone Number</label>
                              <input type="text" class="form-control" id="phoneNumber" name="mobile" required />
                           </div>
                           <!-- Password and Confirm Password -->
                           <div class="col-md-6 mb-2 form-group ">
                              <label for="signUpPassword" class="form-label">Password</label>
                              <input type="password" class="form-control type-password" id="signUpPassword" name="password" required />
                              <span class="toggle-password position-absolute end-0 top-50 me-3" style="cursor: pointer;">
                              <i class="bi bi-eye-slash"></i>
                              </span>
                           </div>
                           <div class="col-md-6 mb-2 form-group ">
                              <label for="confirmPassword" class="form-label">Confirm Password</label>
                              <input type="password" class="form-control type-password" id="confirmPassword"  name="confirmPassword" required />
                              <span class="toggle-password position-absolute end-0 top-50 me-3" style="cursor: pointer;">
                              <i class="bi bi-eye-slash"></i>
                              </span>
                           </div>
                           <div class="col-md-12 mb-2 form-group">
                              <label for="companyname" class="form-label">Company Name</label>
                              <input type="text" class="form-control" id="companyname" name="companyname" />
                           </div>
                        </div>
                        <!-- Terms and Privacy Policy Checkbox -->
                        <div class="mb-3">
                           <input type="checkbox" id="termsAgree"  name="termsAgree">
                           <label for="termsAgree">I agree to all the Terms and Privacy Policies</label>
                        </div>
                        <!-- Create Account Button -->
                        <button type="submit" class="btn btn-primary w-100">Create Account</button>
                     </form>
                     <p class="text-center mt-2 mb-0">
                        Already have an account? <a href="#" id="goToLogin" class="text-decoration-none">Login</a>
                     </p>
                  </div>
                  <p class="text-center mt-2">Or login with</p>
                  <div class="d-flex justify-content-center gap-3">
                     <a href="<?= site_url('facebook/login'); ?>" class="btn btn-light col-md-4"><img src="<?php echo base_url('assets/frontend/images/login-icon-fb.png'); ?>" alt="FB" style="height: 24px;"></a>
                     <a href="<?= site_url('google-login'); ?>" class="btn btn-light col-md-4"><img src="<?php echo base_url('assets/frontend/images/login-icon-google.png'); ?>" alt="Google" style="height: 24px;"></a>
                     <a href="<?= site_url('apple-login'); ?>" class="btn btn-light col-md-4"><img src="<?php echo base_url('assets/frontend/images/login-icon-apple.png'); ?>" alt="Apple" style="height: 24px;"></a>
                  </div>
               </div>
               <!-- Right Column -->
               <div class="col-md-6 d-none d-md-block popup-image">
                  <img src="<?php echo base_url('assets/frontend/images/login-image.png'); ?>" class="img-fluid rounded" alt="Login Image">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>