<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>)">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a></li>   
				<li class="breadcrumb-item active" aria-current="page">About Us</li>
			</ol>
		</nav>
	</div>
</section>

    <!-- Content Section -->
	<section class="py-5 faq-page">
		<div class="container">
				<div class="row justify-content-center">
				<div class="col-md-12">
					<div class="section-title text-center pb-3">
						<h1>Frequently Asked Questions</h2>
						<p>Find answers to common questions about our products, services, and policies. If you can’t find what you’re looking for, feel free to contact us.</p>
					</div>
				</div>
					<div class="col-md-10 ">
						<div class="accordion faq-accordion" id="myAccordion">
					
						<?php 
						$index = 0;
						foreach($faqs as $faq) { ?>

						<div class="accordion-item">
							<h2 class="accordion-header" id="headingTwo">
								<button type="button" class="accordion-button
								<?php 
								if($index != 0){
									echo "collapsed";
								}
							?>
								" data-bs-toggle="collapse" data-bs-target="#collapseTwo<?= $faq['id'] ?>"><?= $faq['question'] ?></button>
							</h2>
							<div id="collapseTwo<?= $faq['id'] ?>" class="accordion-collapse collapse 
							<?php 
								if($index == 0){
									echo "show";
								}
							?>
							" data-bs-parent="#myAccordion">
								<div class="card-body">
									<?= $faq['answer'] ?>
								</div>
							</div>
						</div>

						<?php $index++; } ?>

					</div>
					</div>
				</div>
		</div>
	</section>

<?= $this->include('frontend/layouts/footer') ?>