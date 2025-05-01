<?php  if(!empty($data) || isset($referenceID)){?>
<?= $this->include('frontend/layouts/header') ?>

<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg'); ?>
)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bi bi-house-door" style="color: #EB4227;"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a href="/">Success</a></li>
            </ol>
        </nav>
    </div>
</section>
<!-- Content Section -->
<section class="order-thankyou-msg py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="assets/frontend/images/CheckCircle.png" />
                <h1 class="text-green mb-2">Thank you for your order from <strong>Zylax Computers</strong>. Once your
                    package ships or ready for pickup, we will send you an email or a message.
                </h1>
                <p>You can check the status of your order by <strong><a href="<?= base_url('my-order') ?>">logging into
                            your account</a></strong>.</p>
                <p>If you have questions about your order, you can call us at <a href="tel:1300 099529">1300 099529</a>.
                </p>
                <h3 class="h3">Your order # is <span class="text-green"><?php echo !empty($data['order_id'])? $data['order_id']: $referenceID; ?></span>.</h3>
                <p>We'll email you an order confirmation with details and tracking info. Zylax Computers is full one
                    stop technology shop and please visit us or send a email to <a
                        href="mailto:sales@zylax.com.au">sales@zylax.com.au</a> for any special request or enquiries you
                    have.</p>
                <br>
                <a class="btn btn-primary" href="<?= base_url() ?>">Continue Shopping</a>
                <br><br>
                <hr>
                <div class="row text-box mt-5">
                    <div class="col-md-6">
                        <ul class="contact-info">
                            <li>
                                <address>
                                    <p>
                                        Zylax Computers<br />
                                        Address: 13/4A Foundry Road, <br />
                                        Seven Hills NSW 2147,<br />
                                        Australia
                                    </p>
                                </address>
                            </li>
                            <li>
                                <p>
                                    Phone: 1300 099529
                                </p>
                            </li>
                            <li>
                                <p>
                                    ABN Number: 50 095 556 586
                                </p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">

                        <p>
                            If you have chosen bank transfer, please use the following banking details for bank transfer
                            and send us the remittance invoice.
                        </p>
                        <p>Please use your order number as reference number in transfer details. Thank You.</p>
                        <p>Please deposit money into following account:</p>
                        <p>
                            BSB: 082365<br />
                            Account number: 529150551
                        </p>

                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<?= $this->include('frontend/layouts/footer') ?>

<script>
var before = (new Date()).getMinutes();
setTimeout(function() {
    if ((new Date()).getMinutes() - before >= 1) {
        window.location = '<?= base_url('my-order') ?>';
    }
    localStorage.clear();
}, 1000);
</script>
<?php } ?>