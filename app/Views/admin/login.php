<!DOCTYPE html>
<html>
<?php echo view('admin/include-head.php'); ?>
    <body class="hold-transition login-page bg-admin">
        <img src="<?= base_url('assets/admin/images/eshop_img.jpg') ?>" class="h-100 w-100">
        <div class="overlay"></div>
        
            <div class="login-box">
                <!-- /.login-logo -->
                <div class="container-fluid ">
                    <div class="card-body login-card-body">
                        <div class="login-logo">
                            <a href="<?= base_url() . 'admin/login' ?>"><img src="<?php echo get_image_url($logo, 'thumb', 'sm'); ?>"></a>
                        </div>
                        <h2 class="text-dark">Welcome Back!</h2>
                        <p class="text-dark mb-4">Please login to your account</p>

                        <?php if(session()->getFlashdata('msg')): ?>
                            <p style="color: red; text-align: center;"><?php echo session()->getFlashdata('msg'); ?></p>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/loginProcess') ?>" method="post">
                            <div class="mb-3">
                                <label for="identity" class="form-label text-dark">Username </label>
                                <input type="text" class="form-control form-input" name="identity" id="identity" placeholder="Enter Your Username" value="">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-dark">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-input passwordToggle" name="password" id="password" placeholder="Enter Your Password" value="">
                                    <span class="input-group-text togglePassword" style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-12 mb-3 text-right">
                                    <a href="<?= base_url('/admin/login/forgot_password') ?>" class="text-dark">Forgot Password ?</a>
                                </div> -->
                                <div class="col-8 mb-4">
                                    <div class="check-primary">
                                        <input type="checkbox" name="remember" id="remember">
                                        <label for="remember" class="form-check-label">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>

                                <!-- /.col -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-block p-2 btn-signin">Sign In</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
        
        <!-- Footer -->
        <?php echo view('admin/include-script.php'); ?>
    </body>
</html>