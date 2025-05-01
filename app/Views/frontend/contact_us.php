<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>)">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a></li>   
				<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
			</ol>
		</nav>
	</div>
</section>

<!-- Content Section -->
<section class="py-5 pb-4 contact-page deals-of-the-day">
	<div class="container">
			<div class="row ">
			<div class="col-md-12">
				<div class="section-title  pb-3">
					<h1>Ready to work with us</h2>
					<p>Contact us for all your questions and opinions</p>
				</div>
			</div>
			<div class="white-container row">
				<div class="col-md-8 ">

					<?php if (session()->getFlashdata('success')): ?>
						<div style="color: green;">
							<?= session()->getFlashdata('success') ?>
						</div>
					<?php endif; ?>
			
					<?php if (session()->has('validation')): ?>
						<div style="color: red;">
						<?= session('validation')->listErrors(); ?>
						</div>
					<?php endif; ?>

					<form method="post" action="<?= base_url('contact-us') ?>">
						<div class="mb-3">
						<div class="col-md-12">
							<label for="firstName" class="form-label">First Name *</label>
							<input type="text" class="form-control" id="firstName" name="firstName" value="<?= old('firstName') ?>">
						</div>
						<div class="col-md-12">
							<label for="lastName" class="form-label">Last Name *</label>
							<input type="text" class="form-control" id="lastName" name="lastName" value="<?= old('lastName') ?>">
						</div>
						</div>
				
						<div class="mb-3">
						<label for="email" class="form-label">Email Address *</label>
						<input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>">
						</div>
				
						<div class="mb-3">
						<label for="phone" class="form-label">Phone Number (Optional)</label>
						<input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
						</div>
				
						<div class="mb-3">
						<label for="country" class="form-label">Country / Region *</label>
						<select class="form-select" id="country" name="country">
							<option selected disabled value="">Choose...</option>
							<option value="United States" <?php if(old('country') && old('country') == 'United States'){ echo "selected"; } ?>>United States</option>
							<option value="Canada" <?php if(old('country') && old('country') == 'Canada'){ echo "selected"; } ?>>Canada</option>
							<option value="United Kingdom" <?php if(old('country') && old('country') == 'United Kingdom'){ echo "selected"; } ?>>United Kingdom</option>
							<option value="Australia" <?php if(old('country') && old('country') == 'Australia'){ echo "selected"; } ?>>Australia</option>
							<option value="Other" <?php if(old('country') && old('country') == 'Other'){ echo "selected"; } ?>>Other</option>
						</select>
						</div>
				
						<div class="mb-3">
						<label for="subject" class="form-label">Subject (Optional)</label>
						<input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject') ?>">
						</div>
				
						<div class="mb-3">
						<label for="message" class="form-label">Message</label>
						<textarea class="form-control" id="message" rows="4" name="message"><?= old('message') ?></textarea>
						</div>
				
						<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" id="updatesCheckbox" name="updatesCheckbox" value="yes">
						<label class="form-check-label" for="updatesCheckbox">
							I want to receive news and updates once in a while. By submitting, I’m agreed to the <a href="#">Terms & Conditions</a>.
						</label>
						</div>
				
						<button type="submit" class="btn btn-primary color-red">Send Message</button>
					</form>
				</div>
				<div class="col-md-4">
					<div class="gray-box">

						<p> <strong>New South Wales</strong><br>
							13/4A Foundry Road,<br>
								Seven Hills, NSW-2147, Australia</p>
								<p><strong>Sydney CBD Location</strong><br>
									Shop 5, Ground Floor,<br>
									189 Kent Streeet<br>
									Sydney-2000<br>
									Ph: 02 86071055</p>

						<!-- <p> If you need any help please ring us or send an email to <a href="mailTo:sales@zylax.com.au">sales@zylax.com.au</a> and we will reply you back.</p> -->
						
						<div class="social-share">
							<a class="social-link " href="#"><img src="assets/images/twitter-icon.png" class="" alt=""></a>
							<a class="social-link " href="#"><img src="assets/images/fb-icon.png" class="" alt=""></a>
							<a class="social-link " href="#"><img src="assets/images/insta-icon.png" class="" alt=""></a>
							<a class="social-link " href="#"><img src="assets/images/youtube-icon.png" class="" alt=""></a>
							<a class="social-link " href="#"><img src="assets/images/pinterest-icon.png" class="" alt=""></a>
						</div>

					</div>
					<div class="image-container mt-4">
						<img src="assets/images/contact.png" class="img-fluid" alt="Contact Us">
					</div>
				
				</div>
			</div>
			</div>
	</div>
</section>

<section class="flat-map pb-5">
	<div id="flat-map" class="container pdmap">
		<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d106135.68983368011!2d150.955192!3d-33.767376!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12a275e895ccfb%3A0x85a024631930379c!2s13%2F4A+Foundry+Rd%2C+Seven+Hills+NSW+2147%2C+Australia!5e0!3m2!1sen!2sin!4v1558958951487!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
	</div>
</section>

<?= $this->include('frontend/layouts/footer') ?>