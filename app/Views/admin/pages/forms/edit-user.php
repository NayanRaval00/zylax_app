<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Edit User</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                        <?php endif; ?>
                        <div class="card content-area p-4">
                            <form method="post" action="<?php echo base_url() ?>/admin/customer/updateusers"
                                class="profile-form" id="profile-form" action="<?= site_url('profile/update') ?>">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="hidden" value="<?= $user['id'] ?>" name="user_id">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="fname"
                                            value="<?= esc($user['fname'] ?? '') ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lname"
                                            value="<?= esc($user['lname'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label>Mail ID</label>
                                        <input type="email" class="form-control" name="email"
                                            value="<?= esc($user['email'] ?? '') ?>">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="mobile"
                                            value="<?= esc($user['mobile'] ?? '') ?>">
                                    </div>

                                    <div class="col-lg-4">
                                        <label>Company</label>
                                        <input type="text" class="form-control" name="company"
                                            value="<?= esc($user['company'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>Country</label>
                                        <select class="form-control" name="country_code" id="country">
                                            <option value="">Select...</option>
                                            <?php foreach($countries as $country): ?>
                                            <option value="<?= $country["id"]; ?>"
                                                <?= ($user['country_code'] == $country["id"]) ? 'selected' : '' ?>>
                                                <?= $country["name"]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Region/State</label>
                                        <select class="form-control" name="state" id="state">
                                            <option value="">Select...</option>
                                            <?php foreach($states as $state): ?>
                                            <option value="<?= $state["id"]; ?>"
                                                <?= ($user['state'] == $state["id"]) ? 'selected' : '' ?>>
                                                <?= $state["state"]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>City</label>
                                        <select class="form-control" name="city" id="city">
                                            <option value="">Select...</option>
                                            <?php foreach($cities as $city): ?>
                                            <option value="<?= $city["id"]; ?>"
                                                <?= ($user['city'] == $city["id"]) ? 'selected' : '' ?>>
                                                <?= $city["name"]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" name="pincode"
                                            value="<?= esc($user['pincode'] ?? '') ?>">
                                    </div>
                                    <div class="col-lg-12">
                                        <label>Address</label>
                                        <textarea id="address" name="address" rows="3" cols="50"
                                            class="form-control"><?= esc($user['address'] ?? '') ?></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Active : </label>

                                        <input id="yes-active" type="radio" name="status" value="1"
                                            <?= isset($user['active']) && $user['active'] == 1 ? 'checked' : '' ?>>
                                        &nbsp;<label for="yes-active">YES</label>

                                        &nbsp;&nbsp;&nbsp;&nbsp;

                                        <input id="no-active" type="radio" name="status" value="0"
                                            <?= isset($user['active']) && $user['active'] == 0 ? 'checked' : '' ?>>
                                        &nbsp;<label for="no-active">NO</label>
                                    </div>

                                    <div class="col-lg-12 text-end mt-3">
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                    </div>
                                </div>
                            </form>

                        </div><!-- .card-content -->
                    </div>
                </div><!-- .col-md-12 -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </section>

    <!-- /.content -->
</div>
<script>
</script>