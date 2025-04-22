<footer class="text-white footer">
         <div class="newsletter py-3">
            <div class="container">
               <div class="row align-items-center">
                  <!-- Left Side: Text -->
                  <div class="col-md-5">
                     <h4 class="newsletter-title">Newsletter Sign UP</h4>
                  </div>
                  <!-- Right Side: Form -->
                  <div class="col-md-7 ">
                  <form class="d-flex newsletter-form float-end" id="contactForm" method="post" action="<?= site_url('subscribeNewsletter') ?>">
                     <input type="email" class="form-control me-2" name="email" placeholder="Email Address" required>
                     <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                     <button type="submit" id="submitBtn">Submit</button>
                  </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="container footer-content">
            <div class="row">
               <div class="col-md-4">
                  <div class="footerBlock">
                     <a class="logo-footer text-left" href="#"><img src="assets/frontend/images/zylax-logo.png" class="" alt=""></a>
                     <p>HOTLINE 24/7</p>
                     <a class="phone-number" href="tel:1300099529">1300 099529</a>
                     <p>13/4A Foundry Road, Seven Hills New South Wales 2147 Australia</p>
                     <a class="email" href="mailto:sales@zylax.com.au">sales@zylax.com.au</a>
                     <div class="social-share">
                        <a class="social-link " href="#"><img src="assets/frontend/images/twitter-icon.png" class="" alt=""></a>
                        <a class="social-link " href="#"><img src="assets/frontend/images/fb-icon.png" class="" alt=""></a>
                        <a class="social-link " href="#"><img src="assets/frontend/images/insta-icon.png" class="" alt=""></a>
                        <a class="social-link " href="#"><img src="assets/frontend/images/youtube-icon.png" class="" alt=""></a>
                        <a class="social-link " href="#"><img src="assets/frontend/images/pinterest-icon.png" class="" alt=""></a>
                     </div>
                  </div>
               </div>
               <div class="col-md-8">
                  <div class="row">
                     <div class="col-6 col-lg-3">
                        <h5>Information</h5>
                        <ul class="list-unstyled">
                           <li><a href="#" class="">Laptops</a></li>
                           <li><a href="#" class="">PC & Computers</a></li>
                           <li><a href="#" class="">Cell Phones</a></li>
                           <li><a href="#" class="">Tablets</a></li>
                           <li><a href="#" class="">Gaming & VR</a></li>
                           <li><a href="#" class="">networks</a></li>
                           <li><a href="#" class="">Cameras</a></li>
                           <li><a href="#" class="">Sounds</a></li>
                           <li><a href="#" class="">Office</a></li>
                        </ul>
                     </div>
                     <div class="col-6 col-lg-3">
                        <h5>company</h5>
                        <ul class="list-unstyled">
                           <li><a href="#" class="">About Swoo</a></li>
                           <li><a href="#" class="">Contact</a></li>
                           <li><a href="#" class="">Career</a></li>
                           <li><a href="#" class="">Blog</a></li>
                           <li><a href="#" class="">Sitemap</a></li>
                           <li><a href="#" class="">Store Locations</a></li>
                        </ul>
                     </div>
                     <div class="col-6 col-lg-3">
                        <h5>help center</h5>
                        <ul class="list-unstyled">
                           <li><a href="#" class="">Customer Service</a></li>
                           <li><a href="#" class="">Policy</a></li>
                           <li><a href="#" class="">Terms & Conditions</a></li>
                           <li><a href="#" class="">Trach Order</a></li>
                           <li><a href="#" class="">FAQs</a></li>
                           <li><a href="#" class="">My Account</a></li>
                           <li><a href="#" class="">Product Support</a></li>
                        </ul>
                     </div>
                     <div class="col-6 col-lg-3">
                        <h5>partner</h5>
                        <ul class="list-unstyled">
                           <li><a href="#" class="">Become Seller</a></li>
                           <li><a href="#" class="">Affiliate</a></li>
                           <li><a href="#" class="">Advertise</a></li>
                           <li><a href="#" class="">Partnership</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- <p>&copy; 2025 My Website. All Rights Reserved.</p> -->
         <div class="footer-copyright">
            <div class="container">
               <div class="row">
                  <div class="col-md-6">
                     <p>&copy; 2025 <b>Zylax</b>, All Rights Reserved.</p>
                  </div>
                  <div class="col-md-6 mastercard-logo">
                     <a class="" href="#"><img src="assets/frontend/images/pay1.png" class="" alt=""></a>
                     <a class="" href="#"><img src="assets/frontend/images/pay2.png" class="" alt=""></a>
                     <a class="" href="#"><img src="assets/frontend/images/pay3.png" class="" alt=""></a>
                     <a class="" href="#"><img src="assets/frontend/images/pay4.png" class="" alt=""></a>
                     <a class="" href="#"><img src="assets/frontend/images/pay5.png" class="" alt=""></a>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <?php if (!session()->has('user_id')) { ?>
      <?php echo view('modals/login_modal'); ?>
      <?php } ?>
      <!-- Jquery JS -->
      <script src="<?= base_url("assets/frontend/js/jquery.min.js");?>"></script>
      <!-- Bootstrap 5.3 JS -->
      <script src="<?= base_url("assets/frontend/js/bootstrap.bundle.min.js");?>"></script>
      <!-- Swiper JS -->
      <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
      <!-- Custom JS -->
      <script src="<?= base_url("assets/frontend/js/custom.js");?>"></script>
      <script src="<?= base_url("assets/frontend/js/cart.js");?>"></script>
      <!-- Backend js -->
      <script src="<?= base_url("assets/frontend/js/backend.js");?>"></script>
      <script src="https://www.google.com/recaptcha/api.js?render=6LfyYusqAAAAABkY-_4PGAwAheQzXvEX1fxqbiQq"></script>

      <?php
      $profileScript = session()->get('profileScript');
      if ($profileScript) {
         echo $profileScript;
         session()->remove('profileScript'); // Optional: Remove after use
      }
      ?>
      <?php if($profileScript){ ?>
      <script>
         document.getElementById("logout-btn").addEventListener("click", function () {
            localStorage.clear();
         });
      </script>
      <?php } ?>
   </body>
</html>