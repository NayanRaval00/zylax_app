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
               <div class="col-md-9 my-wishlist cart-area">
                  <h1 class="accountpage-title">My Wishlist</h1>
                  <div class="table-content table-responsive">
                     <table class="table">
                        <thead>
                           <tr class="second-head">
                              <th colspan="2">PRODUCT</th>
                              <th>PRICE</th>
                              <th colspan="2">ACTION</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td class="product-thumbnail"><a href="product-details.html"><img src="assets/images/image 2.png" alt=""></a></td>
                              <td class="product-name"><a href="product-details.html">IPASON – Gaming Desktop – AMD 3000G</a></td>
                              <td class="product-subtotal"><span class="amount">$130.00</span></td>
                              <td class="addtocart-btn">
                                 <button class="btn-orange-outline orange-fill" name="wishlist-btn" type="submit">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                              </td>
                              <td class="product-remove">
                                 <center><a href="#"><i class="bi bi-x"></i></a></center>
                              </td>
                           </tr>
                           <tr>
                              <td class="product-thumbnail"><a href="product-details.html"><img src="assets/images/image 2.png" alt=""></a></td>
                              <td class="product-name"><a href="product-details.html">IPASON – Gaming Desktop – AMD 3000G</a></td>
                              <td class="product-subtotal"><span class="amount">$130.00</span></td>
                              <td class="addtocart-btn">
                                 <button class="btn-orange-outline orange-fill" name="wishlist-btn" type="submit">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                              </td>
                              <td class="product-remove">
                                 <center><a href="#"><i class="bi bi-x"></i></a></center>
                              </td>
                           </tr>
                           <tr>
                              <td class="product-thumbnail"><a href="product-details.html"><img src="assets/images/image 2.png" alt=""></a></td>
                              <td class="product-name"><a href="product-details.html">IPASON – Gaming Desktop – AMD 3000G</a></td>
                              <td class="product-subtotal"><span class="amount">$130.00</span></td>
                              <td class="addtocart-btn">
                                 <button class="btn-orange-outline orange-fill" name="wishlist-btn" type="submit">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                              </td>
                              <td class="product-remove">
                                 <center><a href="#"><i class="bi bi-x"></i></a></center>
                              </td>
                           </tr>
                           <tr>
                              <td class="product-thumbnail"><a href="product-details.html"><img src="assets/images/image 2.png" alt=""></a></td>
                              <td class="product-name"><a href="product-details.html">IPASON – Gaming Desktop – AMD 3000G</a></td>
                              <td class="product-subtotal"><span class="amount">$130.00</span></td>
                              <td class="addtocart-btn">
                                 <button class="btn-orange-outline orange-fill" name="wishlist-btn" type="submit">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                              </td>
                              <td class="product-remove">
                                 <center><a href="#"><i class="bi bi-x"></i></a></center>
                              </td>
                           </tr>
                           <tr>
                              <td class="product-thumbnail"><a href="product-details.html"><img src="assets/images/image 2.png" alt=""></a></td>
                              <td class="product-name"><a href="product-details.html">IPASON – Gaming Desktop – AMD 3000G</a></td>
                              <td class="product-subtotal"><span class="amount">$130.00</span></td>
                              <td class="addtocart-btn">
                                 <button class="btn-orange-outline orange-fill" name="wishlist-btn" type="submit">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                              </td>
                              <td class="product-remove">
                                 <center><a href="#"><i class="bi bi-x"></i></a></center>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?= $this->include('frontend/layouts/footer') ?>