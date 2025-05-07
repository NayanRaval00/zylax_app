<?= $this->include('frontend/layouts/header') ?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg') ?>)">
   <div class="container">
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url(); ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a></li>
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
                           <?php if (!empty($products)): ?>
                              <?php foreach ($products as $product): ?>
                                 <tr>
                                    <td class="product-thumbnail">
                                       <a href="<?= site_url('product/' . $product['slug']); ?>">
                                          <img src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>">
                                       </a>
                                    </td>
                                    <td class="product-name">
                                       <a href="<?= site_url('product/' . $product['slug']); ?>">
                                          <?= esc($product['name']) ?>
                                       </a>
                                    </td>
                                    <td class="product-subtotal">
                                       <span class="amount">₹<?= esc(number_format($product['price'], 2)) ?></span>
                                    </td>
                                    <td class="addtocart-btn">
                                       <button class="btn-orange-outline orange-fill">ADD TO CART &nbsp;<i class="bi bi-cart"></i></button>
                                    </td>
                                    <td class="product-remove">
                                       <center>
                                          <a href="<?= site_url('wishlist/remove/' . $product['id']) ?>"><i class="bi bi-x"></i></a>
                                       </center>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           <?php else: ?>
                              <tr>
                                 <td colspan="5" class="text-center">No items in your wishlist.</td>
                              </tr>
                           <?php endif; ?>
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