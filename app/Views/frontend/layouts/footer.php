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
                    <form class="d-flex newsletter-form float-end" id="contactForm" method="post"
                        action="<?= site_url('subscribeNewsletter') ?>">
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
                    <a class="logo-footer text-left" href="#"><img src="/assets/frontend/images/zylax-logo.png" class=""
                            alt=""></a>

                    <a class="phone-number" href="tel:1300099529">1300 099529</a>
                    <p>13/4A Foundry Road, Seven Hills New South Wales 2147 Australia</p>
                    <a class="email" href="mailto:sales@zylax.com.au">sales@zylax.com.au</a>
                    <div class="social-share">
                        <a class="social-link " href="#"><img src="/assets/frontend/images/twitter-icon.png" class=""
                                alt=""></a>
                        <a class="social-link " href="#"><img src="/assets/frontend/images/fb-icon.png" class=""
                                alt=""></a>
                        <a class="social-link " href="#"><img src="/assets/frontend/images/insta-icon.png" class=""
                                alt=""></a>
                        <a class="social-link " href="#"><img src="/assets/frontend/images/youtube-icon.png" class=""
                                alt=""></a>
                        <a class="social-link " href="#"><img src="/assets/frontend/images/pinterest-icon.png" class=""
                                alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-6 col-lg-4">
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
                    <div class="col-6 col-lg-4">
                        <h5>Company</h5>
                        <ul class="list-unstyled">
                            <li><a href="/about-zylax-computers" class="">About Zylax Computer</a></li>
                            <li><a href="/contact-us" class="">Contact Us</a></li>
                            <li><a href="/blog" class="">Blog</a></li>
                            <!--<li><a href="#" class="">Career</a></li>
                           <li><a href="#" class="">Sitemap</a></li>
                           <li><a href="#" class="">Store Locations</a></li>-->
                        </ul>
                    </div>
                    <div class="col-6 col-lg-4">
                        <h5>Help center</h5>
                        <ul class="list-unstyled">
                            <!-- <li><a href="#" class="">Customer Service</a></li>-->
                            <li><a href="/privacy-and-policy" class="">Privacy Policy</a></li>

                            <li><a href="/terms-and-conditions" class="">Terms & Conditions</a></li>
                            <li><a href="/track-order" class="">Track Order</a></li>
                            <li><a href="/faq" class="">FAQ's</a></li>
                            <li><a href="/manual-cc-verfication" class="">Manual CC Verfication</a></li>
                            <li><a href="/Covid-Update" class="">Covid Update</a></li>

                            <!--<li><a href="#" class="">Product Support</a></li>-->
                        </ul>
                    </div>
                    <!-- <div class="col-6 col-lg-3">
                        <h5>Partner</h5>
                        <ul class="list-unstyled">
                           <li><a href="#" class="">Become Seller</a></li>
                           <li><a href="#" class="">Affiliate</a></li>
                           <li><a href="#" class="">Advertise</a></li>
                           <li><a href="#" class="">Partnership</a></li>
                        </ul>
                     </div>-->
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
                    <a class="" href="#"><img src="/assets/frontend/images/pay1.png" class="" alt=""></a>
                    <a class="" href="#"><img src="/assets/frontend/images/pay2.png" class="" alt=""></a>
                    <a class="" href="#"><img src="/assets/frontend/images/pay3.png" class="" alt=""></a>
                    <a class="" href="#"><img src="/assets/frontend/images/pay4.png" class="" alt=""></a>
                    <a class="" href="#"><img src="/assets/frontend/images/pay5.png" class="" alt=""></a>
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
document.getElementById("logout-btn").addEventListener("click", function() {
    localStorage.clear();
});
</script>
<?php } ?>

<script>
function add_to_cart(element) {
    if (!element) {
        console.error("add_to_cart() called without element!");
        return;
    }

    let userID = '<?php echo session()->has('user_id') ?>';
    let productId = element.getAttribute("pid");
    let productName = element.getAttribute("ppn");
    let productPrice = element.getAttribute("ppp");
    let cat_id = element.getAttribute("cat_id");
    let ppimage = element.getAttribute("ppimage");

    let configuration = [];

    // Function to clean text
    function cleanText(text) {
        return text.replace(/\s+/g, ' ').trim();
    }

    document.querySelectorAll('.customize-me .accordion-item').forEach(item => {
        let setName = item.querySelector('.accordion-button')?.textContent?.trim();
        let selectedRadio = item.querySelector('input[type="radio"]:checked');
        if (selectedRadio) {
            let label = selectedRadio.nextElementSibling?.textContent?.trim();
            let price = selectedRadio.value;
            configuration.push({
                [cleanText(setName)]: {
                    option: cleanText(label),
                    added_price: price
                }
            });
        }
    });

    let count = $("#prd_cnt_" + productId).val();
    let quantity = parseInt(count) || 1;

    function generateGuestSessionId() {
        let id = 'guest_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('guestSessionId', id);
        return id;
    }

    let guest_id = '';
    if (!userID) {
        guest_id = localStorage.getItem('guestSessionId') || generateGuestSessionId();
    }

    $.ajax({
        url: '/zylax/CheckoutController/add_to_cart',
        type: 'POST',
        data: {
            guest_id: guest_id,
            product_id: productId,
            product_name: productName,
            product_price: productPrice,
            cat_id: cat_id,
            quantity: quantity,
            ppimage: ppimage,
            configuration: JSON.stringify(Object.assign({}, ...configuration)) // convert to single object
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: "Added to Cart!",
                    text: productName + " has been added to your cart.",
                    icon: "success",
                    toast: true,
                    position: "top-end",
                    timer: 3000,
                }).then(() => {
                    // Refresh the page after the success message
                    window.location.href = "/zylax/add-to-cart";
                });
            } else {
                Swal.fire("Error!", response.message, "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

var cartContainer = document.getElementById("cartContainer");
var cartDropdown = document.getElementById("cartDropdown");

// Toggle dropdown on click
cartContainer.addEventListener("click", function (event) {
  event.stopPropagation(); // Prevent click from closing immediately
  cartDropdown.classList.toggle("active");
  $(".cart-dropdown").css("display", "block");
});

// Close dropdown if clicking outside
document.addEventListener("click", function (event) {
  if (!cartContainer.contains(event.target)) {
    cartDropdown.classList.remove("active");
  }
});

$(document).click(function (e) {
    if (!$(e.target).closest(".cart-container, #cartDropdown").length) {
      $("#cartDropdown").fadeOut();
    }
  });

  // Prevent dropdown from closing when clicking inside
  $("#cartDropdown").on("click", function (e) {
    e.stopPropagation();
  });
</script>
</body>

</html>