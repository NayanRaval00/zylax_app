

// function updateCartDisplay() {
//   var cart = JSON.parse(localStorage.getItem("cart")) || [];
//   var totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
//   var totalPrice = cart.reduce(
//     (sum, item) => sum + item.price * item.quantity,
//     0
//   );

//   // Check if a coupon is already applied in localStorage on page load
//   let appliedCoupon = localStorage.getItem("appliedCoupon");

//   if (appliedCoupon) {
//     appliedCoupon = JSON.parse(appliedCoupon);

//     // Update UI with saved coupon details
//     $("#coupon_code_val").val(appliedCoupon.discount);
//     if (appliedCoupon.discount_type === "percentage_type") {
//       $(".discount").text((parseFloat(appliedCoupon.discount) || 0).toFixed(2) + "%");
//     } else {
//       $(".discount").text((parseFloat(appliedCoupon.discount) || 0).toFixed(2));
//     }
//     $(".chk-ttl").html("$" + (parseFloat(appliedCoupon.new_total) || 0).toFixed(2));

//     // Hide the coupon input field since a coupon is already applied
//     $(".coupon_card").hide();
//   } else {
//     $(".chk-ttl").html(`$${totalPrice.toFixed(2)}`);
//   }

//   $(".cart-items").html(`${totalItems}`);
//   $(".chk-sub").html(`$${totalPrice.toFixed(2)}`);
//   $(".total-price").html(`$${totalPrice.toFixed(2)}`);

//   var cartList = $("#cartDropdown");
//   cartList.html("");

//   var checkout = $("#checkout-product");
//   checkout.html("");

//   if (cart.length === 0) {
//     cartList.html(
//       "<p class='text-center p-2 product-title' style='color:#000!important'>Your cart is empty.</p>"
//     );
//   } else {
//     cartList.empty();
//     let categoryIds = new Set(); // Collect unique category IDs

//     cart.forEach((item, index) => {
//       categoryIds.add(item.cat_id); // Add category ID to Set

//       // ✅ Clean addonInputs logic (injected here only)
//       let addonInputs = '';
//       if (item.configuration && Array.isArray(item.configuration)) {
//         item.configuration.forEach((config, configIndex) => {
//           const addonPrice = parseFloat(config.added_price || 0).toFixed(2);
//           addonInputs += `
//             <input type="hidden" name="addonSet[${index}][]" value="${config.set_name}">
//             <input type="hidden" name="addonName[${index}][]" value="${config.option}">
//             <input type="hidden" name="addonprice[${index}][]" value="${addonPrice}">
//           `;
//         });
//       } else {
//         addonInputs = `
//           <input type="hidden" name="addonSet[${index}][]" value="">
//           <input type="hidden" name="addonName[${index}][]" value="">
//           <input type="hidden" name="addonprice[${index}][]" value="0.00">
//         `;
//       }

//       cartList.append(`
//         <div class="d-flex align-items-center border-bottom p-2">
//           <img src="${item.image}" alt="Product" class="me-2" style="width: 60px; height: 60px;">
//           <div class="flex-grow-1">
//             <a class="mb-1 product-title">${item.name}</a>
//             <p class="text-danger">${item.quantity} x $${parseFloat(item.price).toFixed(2)}</p>
//           </div>
//           <button class="remove-item btn btn-sm btn-danger" onclick="removeItem('${index}')">X</button>
//         </div>
//       `);

//       checkout.append(`
//         <a href="#" class="list-group-item d-flex align-items-center">
//           <img src="${item.image}" alt="Product" class="me-3 height-64">
//           <input type="hidden" value="${item.id}" name="item_id[]">
//           <input type="hidden" value="${item.image}" name="item_image[]">
//           <input type="hidden" value="${item.name}" name="item_name[]">
//           <input type="hidden" value="${item.quantity}" name="item_qty[]">
//           <input type="hidden" value="${parseFloat(item.price).toFixed(2)}" name="item_price[]">
//           ${addonInputs}
//           <div class="flex-grow-1">
//             <p class="mb-0">${item.name}</p>
//             <p class="mb-0">${item.quantity} x <span class="text-orange fw-bold">$${parseFloat(item.price).toFixed(2)}</span></p>
//           </div>
//         </a>
//       `);
//     });

//     // Fetch shipping charges only once per unique category
//     $(".shipping_checkout").html("");
//     categoryIds.forEach((cat_id) => {
//       fetchShippingCharges(cat_id);
//     });

//     // Append the action buttons
//     cartList.append(`
//       <div class="d-flex justify-content-between p-2">
//         <a href="../add-to-cart"><button class="btn btn-success btn-sm view-cart">View Cart</button></a>
//         <a href="../checkout"><button class="btn btn-success btn-sm checkout">Checkout</button></a>
//       </div>
//     `);
//   }
// }



// $(document).ready(function () {
//   updateCartDisplay();

//   // Close cart dropdown when clicking outside
//   $(document).click(function (e) {
//     if (!$(e.target).closest(".cart-container, #cartDropdown").length) {
//       $("#cartDropdown").fadeOut();
//     }
//   });

//   // Prevent dropdown from closing when clicking inside
//   $("#cartDropdown").on("click", function (e) {
//     e.stopPropagation();
//   });
// });




// Function to remove an item
// function removeItem(index) {
//   let cart = JSON.parse(localStorage.getItem("cart")) || [];
//   let appliedCoupon = JSON.parse(localStorage.getItem("appliedCoupon")) || null;

//   if (cart.length > index) {
//     cart.splice(index, 1);
//     localStorage.setItem("cart", JSON.stringify(cart));

//     // Check if cart is empty after removal
//     if (cart.length === 0) {
//       localStorage.removeItem("appliedCoupon"); // Remove coupon
//       $(".chk-ttl").text("$0.00"); // Reset total
//       $(".discount").text("$0.00"); // Reset discount
//       $(".coupon_card").show(); // Show coupon input again
//     } else if (appliedCoupon) {
//       // If cart is not empty but a coupon was applied, remove it
//       localStorage.removeItem("appliedCoupon");
//       let originalTotal = calculateCartTotal(); // Function to recalculate total
//       $(".chk-ttl").text("$" + originalTotal.toFixed(2));
//       $(".discount").text("$0.00");
//       $(".coupon_card").show();
//     }

//     // Update UI dynamically
//     updateCartDisplay();
//     renderCartPage();

//     Swal.fire({
//       title: "Item Removed!",
//       text: cart.length === 0 ? "Your cart is empty. Applied coupon has been removed." : "This item has been removed from your cart.",
//       icon: "warning",
//       toast: true,
//       position: "top-end",
//       showConfirmButton: false,
//       timer: 1500,
//     });

//     // Refresh UI instead of full reload
//     setTimeout(function () {
//       updateCartDisplay();
//     }, 1000);
//   }
// }


// function fetchShippingCharges(category_id) {
//   $.ajax({
//     url: '/fetch-shipping',
//     type: 'POST',
//     data: { category_id: category_id },
//     dataType: 'json',
//     success: function (response) {
//       if (response.success) {
//         // let shipping = $("tbody tr:first");

//         let shippingMethods = response.shipping_methods;
//         if (!Array.isArray(shippingMethods)) {
//           shippingMethods = [shippingMethods]; // Convert to array if needed
//         }

//         var totalPrice = parseFloat($(".chk-sub").text().replace('$', ''));
//         // Calculate GST amount (18% GST)
//         var gstAmount = (response.product_gst / 100) * totalPrice;
//         // Calculate price excluding GST
//         var priceExcludingGst = totalPrice + gstAmount;
//         // Display the values
//         var gst_including = $(".gst_including");
//         gst_including.html(`
//                 <input type="hidden" name="total_product_gst" id="total_product_gst" value="${gstAmount.toFixed(2)}">
//                 <input type="hidden" name="exculde_product_amount"  value="${priceExcludingGst.toFixed(2)}">
//                 <td class="cart_total_label">GST Amount (${response.product_gst}%): <br> Price Inc. GST:</td>
//                 <td class="cart_total_amount">$${gstAmount.toFixed(2)} <br> $${priceExcludingGst.toFixed(2)}</td>
//               `);

//         shippingMethods.forEach(method => {
//           // Check if the shipping method is already displayed
//           if ($(`#flat-rate_${method.shipping_id}`).length === 0) {
//             let priceInclGST = parseFloat(method.price);
//             let gstRate = response.shipping_gst ? parseFloat(response.shipping_gst) : 0;

//             // Calculate price excluding GST
//             let priceExclGST = response.is_gst_included
//               ? (gstRate / 100 * priceInclGST)
//               : priceInclGST;

//             let gstAmount = priceInclGST - priceExclGST;
//             $(`
//                         <tr>
//                           <td class="cart_total_label">
//                               <div class="radio-info" style="display: flex; align-items: center; gap: 10px;">
//                                   <input type="hidden" name="item_shipid" value="${method.shipping_id}">
//                                   <input type="hidden" name="item_shipprice" value="${priceInclGST.toFixed(2)}">
//                                   <input type="hidden" name="ship_gst" value="${priceExclGST.toFixed(2)}">
//                                   <input type="hidden" name="exclude_ship_amount" value="${gstAmount.toFixed(2)}">
//                                   <input type="radio" id="flat-rate_${method.shipping_id}" class="shipping_method"
//                                       data-price="${priceInclGST.toFixed(2)}" name="radio-flat-rate">

//                                   <label for="flat-rate_${method.shipping_id}" style="display: flex; align-items: center; gap: 8px;">
//                                       <img width="24px" height="24px"
//                                           src="https://www.zylax.com.au/assets/front/images/icons/shipping-icon.png"
//                                           alt="Shipping">
//                                       <span>${method.shipping_name}</span>
//                                   </label>
//                               </div>

//                               <div class="cart_total_label" style="margin-top: 5px;">
//                                   <span>Excl. GST:</span><br>
//                                   <span>GST:</span><br>
//                               </div>
//                           </td>

//                           <td class="cart_total_amount" style="text-align: right; vertical-align: middle;">
//                               <span>$${gstAmount.toFixed(2)}</span><br>
//                               <span>$${priceExclGST.toFixed(2)}</span><br>
//                           </td>
//                       </tr>
//                       `).insertAfter("tbody#shipping_checkout tr:first");
//           }
//         });

//         // Set default shipping price (if first radio button exists)
//         let firstShippingMethod = document.querySelector('input[name="radio-flat-rate"]');
//         if (firstShippingMethod && !$("input[name='radio-flat-rate']:checked").length) {
//           firstShippingMethod.checked = true;
//           updateTotalPrice();
//         }

//         // Attach event listener to update total when shipping is selected
//         $(".shipping_method").off("change").on("change", function () {
//           updateTotalPrice();
//         });
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error('Error fetching shipping charges:', error);
//     }
//   });
// }



// Function to update total price when a shipping option is selected
// function updateTotalPrice() {
//   let appliedCoupon = localStorage.getItem("appliedCoupon");

//   if (appliedCoupon) {
//     appliedCoupon = JSON.parse(appliedCoupon);

//     // Update UI with saved coupon details
//     $("#discount_price").val(appliedCoupon.discount);
//     $("#discount_type").val(appliedCoupon.discount_type);


//     if (appliedCoupon.discount_type === "percentage_type") {
//       $(".discount").text((parseFloat(appliedCoupon.discount) || 0).toFixed(2) + "%");
//     } else {
//       $(".discount").text((parseFloat(appliedCoupon.discount) || 0).toFixed(2));
//     }
//     $(".chk-ttl").html("$" + (parseFloat(appliedCoupon.new_total) || 0).toFixed(2));

//     $('#total_amt').val(parseFloat(appliedCoupon.new_total));

//     // Hide the coupon input field since a coupon is already applied
//     $(".coupon_card").hide();
//   } else {
//     let subtotal = parseFloat($(".chk-sub").text().replace('$', '')) || 0;
//     let selectedShipping = $("input[name='radio-flat-rate']:checked");
//     let gstamt = parseFloat($('#total_product_gst').val()) || 0;
//     let shippingCost = selectedShipping.length ? parseFloat(selectedShipping.attr("data-price")) : 0;
//     $(".chk-ttl").html(`$${(subtotal + shippingCost + gstamt).toFixed(2)}`);
//     $('#total_amt').val(parseFloat(subtotal + shippingCost + gstamt));
//   }

//   // let coupon =  parseFloat($('#coupon_code_val').val()) || 0;
// }



// setTimeout(() => {
//   localStorage.clear();
//   console.log("LocalStorage cleared after 1 second (testing).");
// }, 30 * 60 * 1000);


// function avail_couponcode() {
//   let coupon_id = $("#coupon_id").val();
//   let total = $(".chk-ttl").text().replace("$", "").trim();
//   total = parseFloat(total);

//   // Check if a coupon is already applied
//   if (localStorage.getItem("appliedCoupon")) {
//     Swal.fire({
//       title: "Error",
//       text: "A coupon is already applied. Remove it before applying a new one.",
//       icon: "error",
//       toast: true,
//       position: "top-end",
//       showConfirmButton: true,
//       timer: 3000,
//     });
//     return;
//   }

//   $.ajax({
//     url: '/CheckoutController/validate_coupon',
//     type: "POST",
//     data: { coupon_id: coupon_id, total: total },
//     dataType: "json",
//     success: function (response) {
//       if (response.status === "success") {
//         // Save applied coupon in localStorage
//         localStorage.setItem("appliedCoupon", JSON.stringify(response.coupon));

//         // Update UI
//         $("#coupon_code_val").val(response.coupon.new_total);

//         if (response.coupon.discount_type === "percentage_type") {
//           $(".discount").text((parseFloat(response.coupon.discount) || 0).toFixed(2) + "%");
//         } else {
//           $(".discount").text((parseFloat(response.coupon.discount) || 0).toFixed(2));
//         }

//         $(".chk-ttl").text("$" + (parseFloat(response.coupon.new_total) || 0).toFixed(2));

//         Swal.fire({
//           title: "Success",
//           text: "Coupon applied successfully!",
//           icon: "success",
//           toast: true,
//           position: "top-end",
//           showConfirmButton: false,
//           timer: 3000,
//         });

//         $(".coupon_card").hide();
//       } else {
//         Swal.fire({
//           title: "Error",
//           text: response.message,
//           icon: "error",
//           toast: true,
//           position: "top-end",
//           showConfirmButton: true,
//           timer: 3000,
//         });

//         // Clear invalid coupon from localStorage
//         localStorage.removeItem("appliedCoupon");
//         $(".discount").text("");
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error("AJAX Error: ", error);
//       alert("Something went wrong. Please try again.");
//     }
//   });
// }

// function removeCoupon() {
//   $.ajax({
//       url: '/CheckoutController/remove_coupon',
//       type: "POST",
//       dataType: "json",
//       success: function(response) {
//           Swal.fire({
//               title: "Success",
//               text: response.message,
//               icon: "success",
//               toast: true,
//               position: "top-end",
//               showConfirmButton: false,
//               timer: 3000,
//           });

//           // Remove coupon from localStorage
//           localStorage.removeItem("appliedCoupon");

//           // Reset UI
//           $(".discount").text("$0.00");
//           $(".chk-ttl").text("$" + $(".original-total").text());
//           $(".coupon_card").show();
//       },
//       error: function(xhr, status, error) {
//           console.error("Error removing coupon: ", error);
//           alert("Something went wrong. Please try again.");
//       }
//   });
// }

const hostUrl = window.location.origin;

$('#search_anything').on('input', function () {
  let input_query = $(this).val();

  if (input_query === "") {
    $('.dropdown-content').hide();
    $('#category-container-auto').empty();
    $('#product-container-auto').empty();
    return;
  }

  $.ajax({
    url: "/Findsearch/search",
    method: "POST",
    dataType: 'json',
    data: {
      input_query: input_query
    },
    beforeSend: function () {
      $('.dropdown-content').show();
      $('#category-container-auto').html('<li>Loading...</li>');
      $('#product-container-auto').empty(); // 🧹 Clear previous product results
    },
    success: function (response) {
      // console.log("AJAX success:", response);

      $('#category-container-auto').empty();
      $('#productSerachName').text(input_query);

      // Handle categories
      if (response.categories && response.categories.length > 0) {
        response.categories.forEach(function (category) {
          $('#category-container-auto').append(
            `<li data-id="${category.category_id}" uri="${category.category_slug}">
              ${category.category_name}
            </li>`
          );
        });
      } else {
        if (response.brands && response.brands.length > 0) {
          response.brands.forEach(function (brands) {
            $('#category-container-auto').append(
              `<li data-id="${brands.brand_id}" uri="${brands.brand_slug}">
                ${brands.brand_name}
              </li>`
            );
          });
        } else {
          $('#category-container-auto').append('<li>No categories found</li>');
        }
      }

      // Handle products
      if (response.products && response.products.length > 0) {
        response.products.forEach(function (product) {
          const imagePath = product.product_img.startsWith('/') ? product.product_img : '/' + product.product_img;
          const url = product.product_slug.startsWith('/') ? product.product_slug : '/' + product.product_slug;
          const purl = hostUrl + url;
          const imageUrl = hostUrl + imagePath;
          $('#product-container-auto').append(`
            <div class="product-item">
              <a href="${purl}">
                <div class="prduct-img">
                  <img class="img-fluid" src="${imageUrl}" alt="${product.product_name}">
                </div>
                <div class="content">
                  <h6>${product.product_name}</h6>
                  <div class="product-price"><span class="price">$${product.product_price}</span></div>
                </div>
              </a>
            </div>
          `);
        });
      } else {
        $('#product-container-auto').append('<div>No products found</div>');
      }

      // Add "See More" link just once at the bottom
      $('#product-container-auto .seemoreDiv').remove();

      let input_query_clean = input_query.replace(/ /g, "+");
      // Now append the new one
      $('#product-container-auto').append(`
        <div class="seemoreDiv">
          <a href="/autosearch?q=${input_query_clean}">
            See More in "${input_query}"
          </a>
        </div>
      `);
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
      $('#category-container-auto').html('<li>Error loading results</li>');
    },
    complete: function () {
      if ($('#category-container-auto').children().length === 0) {
        $('.dropdown-content').hide();
      }
    }
  });
});

let dropdownHovered = false;

$(".dropdown-content").on("mouseenter", function () {
  dropdownHovered = true;
});

$(".dropdown-content").on("mouseleave", function () {
  dropdownHovered = false;
  $('.dropdown-content').hide();
});

$("body").on("mouseover", "#category-container-auto li", function () {
  const input_query = $('#search_anything').val();

  const cat_slug = $(this).attr('uri');
  const data_id = $(this).attr('data-id');


  if (input_query === "") {
    $('.dropdown-content').hide();
    $('#product-container-auto').empty();
    return;
  }

  $.ajax({
    url: "/Findsearch/autosearchbycategory",
    method: "POST",
    dataType: 'json',
    data: {
      input_query: input_query,
      data_id: data_id
    },
    beforeSend: function () {
      $('.dropdown-content').show();
      $('#product-container-auto').empty();
    },
    success: function (response) {
      $('#productSerachName').text(input_query);

      if (response.products && response.products.length > 0) {
        response.products.forEach(function (product) {
          if (!product.product_name || !product.product_slug || !product.product_img || !product.product_price) {
            return;
          }
          const imagePath = product.product_img.startsWith('/') ? product.product_img : '/' + product.product_img;
          const url = product.product_slug.startsWith('/') ? product.product_slug : '/' + product.product_slug;
          const purl = hostUrl + url;
          const imageUrl = hostUrl + imagePath;
          $('#product-container-auto').append(`
            <div class="product-item">
              <a href="${purl}">
                <div class="prduct-img">
                  <img class="img-fluid" src="${imageUrl}" alt="${product.product_name}">
                </div>
                <div class="content">
                  <h6>${product.product_name}</h6>
                  <div class="product-price"><span class="price">$${product.product_price}</span></div>
                </div>
              </a>
            </div>
          `);
        });
      } else {
        $('#product-container-auto').append('<div>No products found</div>');
      }

      $('#product-container-auto .seemoreDiv').remove();
      $('#product-container-auto').append(`
        <div class="seemoreDiv">
          <a href="${cat_slug}">
            See More in "${input_query}"
          </a>
        </div>
      `);
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
    },
    complete: function () {
      // Delay hide so user can move to dropdown
      setTimeout(function () {
        if (!dropdownHovered && $('#product-container-auto').children().length === 0) {
          $('.dropdown-content').hide();
        }
      }, 300); // small delay to allow hover
    }
  });
});


$('.remove-fromcart').on('click', function() {
  var productId = $(this).data('product-id'); // Get the product ID

  // Confirm removal
  if (confirm('Are you sure you want to remove this item from the cart?')) {
      // Send AJAX request to delete the item from the cart
      $.ajax({
        url: '/zylax/CheckoutController/deleteCart', // Replace with the correct route to your controller
          type: 'POST',
          data: { 
              product_id: productId 
          },
          success: function(response) {
              // Handle the response (assuming the controller returns success)
              if (response.success) {
                Swal.fire({
                    title: "Removed to Cart!",
                    text: "Product has been added to your cart.",
                    icon: "success",
                    toast: true,
                    position: "top-end",
                    timer: 3000,
                }).then(() => {
                    // Refresh the page after the success message
                    location.reload();
                });
              } else {
                Swal.fire(
                    'Error!',
                    'There was an error removing the item.',
                    'error'
                );
              }
          },
          error: function() {
            Swal.fire(
                'Error!',
                'Error! Could not connect to the server.',
                'error'
            );
          }
      });
  }
});














