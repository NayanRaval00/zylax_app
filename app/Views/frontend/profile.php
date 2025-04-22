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
<!-- Content Section -->
<section class="pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-md-12 myaccount-area">
            <div class="row">
               
               <?= view('frontend/partials/profile-nav'); ?>

               <div class="col-md-9 my-profile">
                  <h1 class="accountpage-title">My Profile</h1>
                  <hr>
                  <div class="d-flex align-items-center mb-3">
                     <div class="position-relative">
                        <img src="<?= session()->get('user_image') ?: base_url('assets/frontend/images/profile1.jpg'); ?>" alt="Profile Picture" class="profile-pic" id="profileImage" />
                        <i class="bi bi-pencil-square edit-icon" id="showInputBtn"></i>
                     </div>
                     <button class="btn btn-orange ms-auto" id="enableFormBtn">
                     <i class="bi bi-pencil-square"></i> &nbsp;Edit Profile
                     </button>
                  </div>
                  <div class="row">
                     <div class="col-lg-6">
                        <input type="file" class="form-control" id="inputField" name="profileimg" style="display: none; margin-top: 20px;">	
                     </div>
                  </div>
                  
                  <br>
                  <?= view('frontend/partials/messages'); ?>
                  
                  <form method="post" class="profile-form" id="profile-form" action="<?= site_url('profile/update') ?>">
                     <?= csrf_field() ?>
                     <div class="row">
                        <div class="col-lg-6">
                              <label>First Name</label>
                              <input type="text" class="form-control" name="fname" value="<?= esc($user['fname'] ?? '') ?>" disabled>
                        </div>
                        <div class="col-lg-6">
                              <label>Last Name</label>
                              <input type="text" class="form-control" name="lname" value="<?= esc($user['lname'] ?? '') ?>" disabled>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-6">
                              <label>Mail ID</label>
                              <input type="email" class="form-control" name="email" value="<?= esc($user['email'] ?? '') ?>" disabled>
                        </div>
                        <div class="col-lg-6">
                              <label>Phone Number</label>
                              <input type="text" class="form-control" name="mobile" value="<?= esc($user['mobile'] ?? '') ?>" disabled>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-3">
                              <label>Country</label>
                              <select class="form-control" name="country_code" disabled id="country">
                                 <option value="">Select...</option>
                                 <?php foreach($countries as $country) {?>
                                 <option value="<?= $country["id"];?>" <?= ($user['country_code'] == $country["id"]) ? 'selected' : '' ?>><?= $country["name"];?></option>
                                 <?php } ?>
                              </select>

                              
                        </div>
                        <div class="col-lg-3">
                              <label>Region/State</label>
                              <select class="form-control" name="state" id="state" disabled>
                                 <option value="">Select...</option>
                                 <?php foreach($states as $state) {?>
                                 <option value="<?= $state["id"];?>" <?= ($user['state'] == $state["id"]) ? 'selected' : '' ?>><?= $state["state"];?></option>
                                 <?php } ?>
                              </select>
                        </div>
                        <div class="col-lg-3">
                              <label>City</label>
                              <select class="form-control" name="city" id="city" disabled>
                                 <option value="">Select...</option>
                                 <?php foreach($cities as $city) {?>
                                 <option value="<?= $city["id"];?>" <?= ($user['city'] == $city["id"]) ? 'selected' : '' ?>><?= $city["name"];?></option>
                                 <?php } ?>
                              </select>
                        </div>


                        <div class="col-lg-3">
                              <label>Pincode</label>
                              <input type="text" class="form-control" name="pincode" value="<?= esc($user['pincode'] ?? '') ?>" disabled>
                        </div>
                        <div class="col-lg-12">
										<label>Address</label>
											<textarea id="address" name="address" value="<?= esc($user['address'] ?? '') ?>" rows="3" cols="50" disabled><?= esc($user['address'] ?? '') ?></textarea>
										</div>
                        <div class="col-lg-12">
                              <button type="submit" class="btn btn-orange ms-auto" disabled> Save Changes</button>
                        </div>
                     </div>
                  </form>

               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php
$uploadUrl = site_url('profile/uploadProfileImage');
$stateUrl = site_url('location/getStates');
$cityUrl = site_url('location/getCities');
$profileScript = <<<EOD
<script>
    $(document).ready(function() {
        $("#showInputBtn").click(function() {
            $("#inputField").fadeToggle(); // Toggle with smooth animation
        });

        $("#inputField").change(function () {
            var formData = new FormData();
            formData.append("profileimg", $("#inputField")[0].files[0]);
            $.ajax({
                  url: "{$uploadUrl}",
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (response) {
                     if (response.status === "success") {
                        $("#profileImage").attr("src", response.image_url);
                        $("#inputField").fadeToggle();
                     } else {
                        alert(response.message);
                     }
                  },
                  error: function () {
                     alert("Something went wrong!");
                  }
            });
         });


         // Load states when country changes
        $('#country').change(function () {
            var country_id = $(this).val();
            if (country_id !== '') {
                $.ajax({
                    url: "{$stateUrl}",
                    type: "POST",
                    data: { country_id: country_id },
                    success: function (response) {
                        $('#state').html(response);
                        $('#city').html('<option value="">Select...</option>'); // Reset cities
                    }
                });
            } else {
                $('#state').html('<option value="">Select...</option>');
                $('#city').html('<option value="">Select...</option>');
            }
        });

        // Load cities when state changes
        $('#state').change(function () {
            var state_id = $(this).val();
            if (state_id !== '') {
                $.ajax({
                    url: "{$cityUrl}",
                    type: "POST",
                    data: { state_id: state_id },
                    success: function (response) {
                        $('#city').html(response);
                    }
                });
            } else {
                $('#city').html('<option value="">Select...</option>');
            }
        });

    });

    document.getElementById("enableFormBtn").addEventListener("click", function() {
        const form = document.getElementById("profile-form");
        const elements = form.elements;
        let isDisabled = elements[0].disabled;

        for (let i = 0; i < elements.length; i++) {
            elements[i].disabled = !isDisabled;
        }

        if (isDisabled) {
            this.innerHTML = "<i class='bi bi-lock-fill'></i> &nbsp; Close Edit";
        } else {
            this.innerHTML = "<i class='bi bi-pencil-square'></i> &nbsp; Edit Profile";
        }
    });
</script>

EOD;


session()->set('profileScript', $profileScript);
?>


<?= $this->include('frontend/layouts/footer') ?>
