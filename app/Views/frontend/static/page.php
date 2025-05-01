<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>)">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a></li>   
				<li class="breadcrumb-item active" aria-current="page"><?= $page['menu_name'] ?></li>
			</ol>
		</nav>
	</div>
</section>

 <!-- Content Section -->
 <section class="services-tabs-content pt-2 pb-5">

	<div class="container services-tab-data mt-4">
		<?php if(isset($page['page_type']) && $page['page_type'] == "Static"): ?>
			<div class="row">
				<div class="col-md-12">
					<?= isset($page['description']) ? output_escaping($page['description']) : "" ?>
				</div>
			</div>
		<?php else: ?>
			
			<div class="row">
                <div class="col-md-3">
                    <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
						<?php foreach ($sidebar as $key => $left_menu) { ?>
							<li class="nav-item"  >
								<a class="nav-link <?php 
									if($activePage == $left_menu['slug']){
										echo "active";
									}
								?>" href="<?= base_url($left_menu['slug'])?>"><?= $left_menu['menu_name'] ?></a>
							</li>                       
						<?php } ?>
                    </ul>
                </div>
                <div class="col-md-9 services-tab-data">
                    <div class="tab-content">
                        <div class="tab-pane active">
							<?= isset($page['description']) ? output_escaping($page['description']) : "" ?>
							<?php 
								if(isset($page['page_script']) && $page['page_script'] !=""){
									include($page['page_script']);
								}
							?>
                        </div>
                    </div>
                </div>
            </div>

		<?php endif; ?>
	</div>

</section>

<?= $this->include('frontend/layouts/footer') ?>