$(document).ready(function () {

    // login toggle password
    $(".toggle-password").click(function () {
        let input = $(this).parent().find(".type-password");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            input.attr("type", "password");
            icon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
    });

    $(".toggle-password1").click(function () {
        let input = $(this).parent().find(".passwordinput1");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            input.attr("type", "password");
            icon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
    });

    $(".toggle-password2").click(function () {
        let input = $(this).parent().find(".passwordinput2");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("bi-eye-slash").addClass("bi-eye");
        } else {
            input.attr("type", "password");
            icon.removeClass("bi-eye").addClass("bi-eye-slash");
        }
    });

    $(".product-detail-content-block .product-colors li").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    });

    $(".wishlist-btn, .wishlist").click(function (e) {
        e.preventDefault();
        $(this).find("i").toggleClass("bi-heart-fill");
        $(this).find("i").toggleClass("bi-heart");
        $(this).toggleClass("active");
        
    });
    
    let listview = false;
    $(".product-listing-filter .listview").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
        $(".product-listing-filter .gridview").removeClass("active");
        $(".product-listing .row").addClass("list-view");
        listview = true;
    });

    $(".product-listing-filter .gridview").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
        $(".product-listing-filter .listview").removeClass("active");
        $(".product-listing .row").removeClass("list-view");
        listview = false;
    });
    
    $(".product-detail-content-block .compare-btn").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
    });
    

    // Switch to Forgot Password Form
    $("#forgotPasswordLink").click(function (e) {
        e.preventDefault();
        $("#loginForm").addClass("d-none");
        $("#forgotPasswordForm").removeClass("d-none");
        $(".popup-image img").attr("src","assets/frontend/images/forgot-pass-image.png");
        
    });

    // Back to Login Form
    $("#backToLogin").click(function (e) {
        e.preventDefault();
        $("#forgotPasswordForm").addClass("d-none");
        $("#loginForm").removeClass("d-none");
        $(".popup-image img").attr("src","assets/frontend/images/login-image.png");
    });

    // Switch to Sign Up Form
    $("#goToSignUp").click(function (e) {
        e.preventDefault();
        $("#loginForm").addClass("d-none");
        $("#signUpForm").removeClass("d-none");
        $(".popup-image img").attr("src","assets/frontend/images/sign-up-image.png");
    });

    // Back to Login Form from Sign Up
    $("#goToLogin").click(function (e) {
        e.preventDefault();
        $("#signUpForm").addClass("d-none");
        $("#loginForm").removeClass("d-none");
        $(".popup-image img").attr("src","assets/frontend/images/login-image.png");
    });


    // Toggle submenu on click for mobile
    $(".submenu-toggle").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior

        var $submenu = $(this).next(".dropdown-menu");

        if ($submenu.hasClass("show")) {
            $submenu.removeClass("show").slideUp(200); // Close submenu
        } else {
            $(".dropdown-menu .dropdown-menu").removeClass("show").slideUp(200); // Close other submenus
            $submenu.addClass("show").slideDown(200); // Open clicked submenu
        }
    });

    // Close submenus when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".dropdown-submenu").length) {
            $(".dropdown-menu .dropdown-menu").removeClass("show").slideUp(200);
        }
    });

    $('.mega-menu-btn').click(function() {
        $('#megaMenupro').toggle();
        $('.header').toggleClass("active");
        $(this).toggleClass("active");
    });

    $('.category-item').click(function() {
         var icon = $(this).find('i').attr('class');
        var title = $(this).find('.category-title').text(); // Get the title from the span
        var description = $(this).find('.desc').text();
        var iconImage = $(this).find('.icon-box').html();
        var subCategories = $(this).find('.sub-category').html();
        var featuredBlock = $(this).find('.featured-block').html();
        // $('#parentCategoryTitle').html('<div class="icon-box"><i class="' + icon + '"></i></div> '); // Include the icon
        $('#parentCategoryTitle').html('<div class="icon-box">' + iconImage + '</div> '); // Include the icon
        $('#parentCategoryDescription').html("<span>"+title+"</span>"+"<p>"+description+"</p>");
        $('#subCategory .sub-category-list').html(subCategories);
        $('#subCategory .child-feature-block').html("");
        $('#subCategory .child-feature-block').html(featuredBlock);
        
        


        $('.main-categories').hide();
        $('#backButton').show();
        $('#subCategory').show();
          $('#megaMenupro').addClass("overlay-bg");
    });

    $('#backButton').click(function() {
        $('#subCategory').hide();
        $('#backButton').hide();
        $('.main-categories').show();
          $('#megaMenupro').removeClass("overlay-bg");
    });

    $('.sub-category-item').hover(function() {
        $(this).find('ul').show();
    }, function() {
        $(this).find('ul').hide();
    });
  
  
  
     // Function to check if the device is mobile (adjust width as needed)
    function isMobile() {
        return $(window).width() <= 768;  // Adjust this value to your desired mobile breakpoint
    }

    // // Check on document ready and window resize events
    if (isMobile()) {
        // Modify the href of anchor elements with class 'arrow-icon'
        // $('.sub-category-item a.arrow-icon').each(function() {
        //     $(this).attr('href', 'javascript:void(0)');
        // });
      
       $('#megaMenupro .close-icon').click(function() {
       
         $(".btn-menu-mega").trigger("click");

        });
      
        //   $(document).on('click', '.child-menu-mega .sub-category-item', function() {
        //     if($(this).hasClass("arrow-icon")){
        //       $(".child-menu-mega .sub-category-item").removeClass("open");
        //       $(this).toggleClass("open");
        //       $(".child-menu-mega").find("ul").removeClass("show");
        //       if($(this).hasClass("open")){
        //         $(this).find("ul").addClass("show");
        //       }else{
        //         $(this).find("ul").removeClass("show");
        //         $(this).removeClass("open");
        //       }
              
        //     }

        // });

        // Parent Arrow Toggle (1st level)
$(document).on('click', '.sub-category-item.arrow-icon > .arrow-toggle', function(e) {
    if (isMobile()) {
      e.preventDefault();
      e.stopPropagation();
  
      const parent = $(this).closest('.sub-category-item');
  
      // Close other top-level dropdowns only (not nested)
      $('.sub-category-item.arrow-icon').not(parent).removeClass('open').children('ul').removeClass('show');
  
      parent.toggleClass('open');
      parent.children('ul').first().toggleClass('show');
    }
  });
  
  // Child Arrow Toggle (2nd level)
  $(document).on('click', '.sub-category-item .arrow-toggle-child', function(e) {
    if (isMobile()) {
      e.preventDefault();
      e.stopPropagation();
  
      const parent = $(this).closest('.sub-category-item');
  
      // Close sibling dropdowns (same level only)
      parent.siblings('.arrow-icon').removeClass('open').children('ul').removeClass('show');
  
      parent.toggleClass('open');
      parent.children('ul').first().toggleClass('show');
    }
  });

        


        $("#toggleBtn").click(function () {
            if(listview == true){
                $(".product-listing-container .filter-container").toggleClass("show-filter");
                $(".product-listing-filter .listview").toggleClass("active");
                $(".product-listing-filter .gridview").toggleClass("active");
                $(".product-listing .row").toggleClass("list-view");
                $(".product-listing.wider .row .col-md-4").css("width", "100%");
                

            }else{
                $(".product-listing-container .filter-container").toggleClass("show-filter");
            }
            
           
        });

         
    }else{
        $("#toggleBtn").click(function () {
            $(".product-listing-container .filter-container").toggleClass("hide-filter");
            $(".product-listing-container .product-listing").toggleClass("wider");
        });
    }

    // Optionally, recheck on window resize
    $(window).resize(function() {
        if (isMobile()) {
            $('.sub-category-item a.arrow-icon').each(function() {
                $(this).attr('href', 'javascript:void(0)');
            });
        } else {
            // Revert to the original href if the device is not mobile (optional)
            $('.sub-category-item a.arrow-icon').each(function() {
                $(this).attr('href', $(this).data('original-href')); // Assuming you store the original href
            });
        }
    });

     window.addEventListener("scroll", function() {
            var logoNav = document.querySelector(".top-menu");
            var header = document.querySelector(".header");
            
            if (window.scrollY > header.offsetHeight) {
                header.classList.add("sticky");
                logoNav.classList.add("d-none");
            } else {
                header.classList.remove("sticky");
                logoNav.classList.remove("d-none");
            }
        });

    var HomeSwiper = new Swiper(".home-slider", {
        loop: true,
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    var homeCategory = new Swiper(".category-swiper", {
        slidesPerView: 7, // Default for large screens
        loop: true,
        spaceBetween: 15,
        navigation: {
            nextEl: ".swiper-category-button-next",
            prevEl: ".swiper-category-button-prev",
        },
        // pagination: {
        //     el: ".swiper-category-pagination",
        //     clickable: true,
        // },
        breakpoints: {
            320: {
                slidesPerView: 2, // Mobile: 2 per slide
            },
            768: {
                slidesPerView: 4, // Tablet: 4 per slide
            },
            1024: {
                slidesPerView: 7, // Desktop: 7 per slide
            }
        }
    });

    var HomeTestimonials = new Swiper(".testimonial-slider", {
        // slidesPerView: 3,
        spaceBetween: 20,
        // pagination: {
        //     el: ".swiper-pagination",
        //     clickable: true,
        // },
        breakpoints: {
            768: {
                slidesPerView: 1
            },
            1024: {
                slidesPerView: 3
            }
        },
        navigation: {
            nextEl: ".swiper-testimonial-button-next",
            prevEl: ".swiper-testimonial-button-prev",
        }
    });

    var brandSlider = new Swiper(".brand-slider", {
        loop: true,
        // autoplay: {
        //     delay: 3000, // 3 seconds per slide
        //     disableOnInteraction: false,
        // },
        slidesPerView: 1, // Default for mobile
        spaceBetween: 20,
        breakpoints: {
            768: {
                slidesPerView: 1
            },
            1024: {
                slidesPerView: 3
            }
        },
        navigation: {
            nextEl: ".swiper-brand-button-next",
            prevEl: ".swiper-brand-button-prev",
        }
    });

    var bestSellerSlider = new Swiper(".best-seller-slider", {
        loop: true,
        // autoplay: {
        //     delay: 3000, // 3 seconds per slide
        //     disableOnInteraction: false,
        // },
        slidesPerView: 1, // Default for mobile
        spaceBetween: 20,
        breakpoints: {
            768: {
                slidesPerView: 1
            },
            1024: {
                slidesPerView: 3
            }
        },
        navigation: {
            nextEl: ".swiper-best-seller-button-next",
            prevEl: ".swiper-best-seller-button-prev",
        }
    });

    // Check if the sliders exist on the page before initializing
    if ($(".main-product-slider").length && $(".product-thumbnail-slider").length) {
        // Initialize the Thumbnail Slider
        var thumbnailSlider = new Swiper(".product-thumbnail-slider", {
            loop: false,
            spaceBetween: 3,
            slidesPerView: 6,
            breakpoints: {
                768: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 6
                }
            },
            
        });

        // Initialize the Main Slider
        var mainProductSlider = new Swiper(".main-product-slider", {
            spaceBetween: 10,
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-prodcut-detail-button-next",
                prevEl: ".swiper-prodcut-detail-button-prev",
            },
            thumbs: {
                swiper: thumbnailSlider,
            },
        });
    } else {
        console.warn("Sliders not found on the page.");
    }


    

        const $minRange = $("#minRange");
        const $maxRange = $("#maxRange");
        const $minValue = $("#minValue");
        const $maxValue = $("#maxValue");
        const $sliderActive = $(".slider-active");
    
        if ($minRange.length && $maxRange.length && $minValue.length && $maxValue.length && $sliderActive.length) {
            const minGap = 50; // Minimum gap between min and max
    
            function updateSlider(event) {
                let minVal = parseInt($minRange.val());
                let maxVal = parseInt($maxRange.val());
    
                if (maxVal - minVal < minGap) {
                    if ($(event.target).is($minRange)) {
                        $minRange.val(maxVal - minGap);
                    } else {
                        $maxRange.val(minVal + minGap);
                    }
                    minVal = parseInt($minRange.val());
                    maxVal = parseInt($maxRange.val());
                }
    
                $minValue.text("$" + minVal);
                $maxValue.text("$" + maxVal);
    
                // Update active track position
                const minPercent = (minVal / $minRange.attr("max")) * 100;
                const maxPercent = (maxVal / $maxRange.attr("max")) * 100;
                $sliderActive.css({
                    left: minPercent + "%",
                    width: (maxPercent - minPercent) + "%"
                });
            }
    
            $minRange.on("input", updateSlider);
            $maxRange.on("input", updateSlider);
    
            updateSlider(); // Initialize slider positions
        }



        //about page sliders
var testiSlider = new Swiper(".about-testimonial-slider", {
    loop: true,
     autoplay: {
         delay: 5000, // 3 seconds per slide
         disableOnInteraction: false,
     },
    slidesPerView: 1, // Default for mobile
    spaceBetween: 20,
    breakpoints: {
        768: {
            slidesPerView: 1
        },
        1024: {
            slidesPerView: 1
        }
    },
    navigation: {
        nextEl: ".swiper-brand-button-next",
        prevEl: ".swiper-brand-button-prev",
    }
});
var clientSlider = new Swiper(".clients-slider", {
    loop: true,
     autoplay: {
         delay: 5000, // 3 seconds per slide
         disableOnInteraction: false,
     },
    slidesPerView: 1, // Default for mobile
    
    breakpoints: {
        768: {
            slidesPerView: 2
        },
        1024: {
            slidesPerView: 5
        }
    },
    navigation: {
        nextEl: ".swiper-brand-button-next",
        prevEl: ".swiper-brand-button-prev",
    }
});



});