<?= $this->include('frontend/layouts/header') ?>

 <!-- Content Section -->
 <section class="page-not-found py-5">
        <div class="container">
            <div class="row">
            <div class="col-md-12 text-center">
                <div class="page-not-found-img-container pb-4"> 
                    <img src="<?= base_url('assets/frontend/images/page-not-found.png') ?>"  />
                </div>
                    <h2>404, Page not founds</h2>
                    <p>Something went wrong. It's look that your requested could not be found. It's look like the link is broken or the page is removed.</p>
                    <a href="javascript:window.history.go(-1);" class="btn btn-primary "><i class="bi bi-arrow-left-short"></i> Go Back</a>
                    <a href="<?= base_url(); ?>" class="btn btn-primary white"><i class="bi bi-house-door"></i> Go To home</a>
            </div>
            </div>
        </div>
</section>

<?= $this->include('frontend/layouts/footer') ?>