<?= $this->include('frontend/layouts/header') ?>
<section class="breadcrumb-img" style="background-image:url(<?= base_url('assets/frontend/images/breadcrump.jpg') ?>)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url();?>"><i class="bi bi-house-door"
                            style="color: #EB4227;"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Addresses</li>
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
                        <?= view('frontend/partials/messages'); ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <h1 class="accountpage-title mb-0">Manage Addresses</h1>
                            <button type="button" class="btn btn-orange ms-auto" data-bs-toggle="modal"
                                data-bs-target="#loginModal">
                                Add Address
                            </button>
                        </div>
                        <hr>
                        <div class="table-content table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="second-head">
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Pincode</th>
                                        <th>Address</th>
                                        <th>Prmary Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($address as $addr) { ?>
                                    <tr>
                                        <td><?= $addr['email'] ?></td>
                                        <td><?= $addr['phoneno'] ?></td>
                                        <td><?= $addr['country_name'] ?></td> <!-- Changed from <th> to <td> -->
                                        <td><?= $addr['state_name'] ?></td>
                                        <td><?= $addr['city_name'] ?></td>
                                        <td><?= $addr['pincode'] ?></td>
                                        <td><?= $addr['address'] ?></td>
                                        <td class="text-center">
                                        <?php if ($addr['status_addr'] == 0): ?>
                                            <i class="bi bi-ban text-danger fs-5"></i> <!-- Disabled icon -->
                                        <?php else: ?>
                                            <i class="bi bi-check text-success fs-3"></i> <!-- Enabled icon -->
                                        <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#updateModal<?= $addr['address_id'] ?>">
                                                <i class="bi bi-pencil-square fs-6"></i>
                                            </a>
                                            <a href="<?= site_url('profile/address-delete/') . $addr['address_id'] ?>"
                                                onclick="return confirm('Are you sure you want to delete this address?');">
                                                <i class="bi bi-archive-fill text-danger fs-6"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Update Modal -->
                                    <div class="modal fade login-modal" id="updateModal<?= $addr['address_id'] ?>"
                                        tabindex="-1" aria-labelledby="updateModalLabel<?= $addr['address_id'] ?>"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="updateModalLabel<?= $addr['address_id'] ?>">Update Address
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" id="profile-update-form"
                                                        action="<?= site_url('profile/update-address') ?>">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="email" class="form-label">Mail ID</label>
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" value="<?= $addr['email'] ?>" required>
                                                                <input type="hidden" name="address_id" id="address_id"
                                                                    value="<?= $addr['address_id'] ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="phoneno" class="form-label">Phone
                                                                    Number</label>
                                                                <input type="tel" class="form-control" id="phoneno"
                                                                    name="phoneno" pattern="[0-9]{10}"
                                                                    placeholder="Enter 10-digit phone number"
                                                                    value="<?= $addr['phoneno'] ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Country</label>
                                                                <select class="form-control" name="country_code"
                                                                    id="country">
                                                                    <option value="">Select...</option>
                                                                    <?php foreach($countries as $country) { ?>
                                                                    <option value="<?= $country["id"]; ?>"
                                                                        <?= ($addr['country_code'] == $country["id"]) ? 'selected' : '' ?>>
                                                                        <?= $country["name"]; ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="state"
                                                                    class="form-label">Region/State</label>
                                                                <select class="form-control" name="state" id="state">
                                                                    <option value="">Select...</option>
                                                                    <?php foreach($states as $state) { ?>
                                                                    <option value="<?= $state["id"]; ?>"
                                                                        <?= ($addr['state_code'] == $state["id"]) ? 'selected' : '' ?>>
                                                                        <?= $state["state"]; ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="city" class="form-label">City</label>
                                                                <select class="form-control" name="city" id="city">
                                                                    <option value="">Select...</option>
                                                                    <?php foreach($cities as $city) { ?>
                                                                    <option value="<?= $city["id"]; ?>"
                                                                        <?= ($addr['city_code'] == $city["id"]) ? 'selected' : '' ?>>
                                                                        <?= $city["name"]; ?>
                                                                    </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="pincode" class="form-label">Pincode</label>
                                                                <input type="text" class="form-control" id="pincode"
                                                                    name="pincode" pattern="[0-9]{6}"
                                                                    placeholder="Enter 6-digit pincode"
                                                                    value="<?= $addr['pincode'] ?>" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="address" class="form-label">Address</label>
                                                                <textarea class="form-control" id="address"
                                                                    name="address" rows="3"
                                                                    placeholder="Enter full address..."
                                                                    required><?= $addr['address'] ?></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input class="form-check-input status_addr_update" type="checkbox"
                                                                    id="status_addr_update-<?= $addr['address_id'] ?>"
                                                                    name="status_addr_update" <?= ($addr['status_addr'] == "1") ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="status_addr_update-<?= $addr['address_id'] ?>">
                                                                    Set as primary address
                                                                </label>
                                                            </div>
                                                            <div class="col-md-6 text-end">
                                                                <button type="submit"
                                                                    class="btn btn-orange">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- add address Modal -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Manage Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="profile-form" action="<?= site_url('profile/insert-address') ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Mail ID</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phoneno" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneno" name="phoneno" pattern="[0-9]{10}"
                                placeholder="Enter 10-digit phone number" required>
                        </div>
                        <div class="col-md-6">
                            <label>Country</label>
                            <select class="form-control" name="country_code" id="country">
                                <option value="">Select...</option>
                                <?php foreach($countries as $country) {?>
                                <option value="<?= $country["id"];?>"
                                    <?= ($user['country_code'] == $country["id"]) ? 'selected' : '' ?>>
                                    <?= $country["name"];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">Region/State</label>
                            <select class="form-control" name="state" id="state">
                                <option value="">Select...</option>
                                <?php foreach($states as $state) {?>
                                <option value="<?= $state["id"];?>"
                                    <?= ($user['state'] == $state["id"]) ? 'selected' : '' ?>><?= $state["state"];?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <select class="form-control" name="city" id="city">
                                <option value="">Select...</option>
                                <?php foreach($cities as $city) {?>
                                <option value="<?= $city["id"];?>"
                                    <?= ($user['city'] == $city["id"]) ? 'selected' : '' ?>><?= $city["name"];?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" pattern="[0-9]{6}"
                                placeholder="Enter 6-digit pincode" required>
                        </div>
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                placeholder="Enter full address..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <input class="form-check-input" type="checkbox" name="status_addr" id="status_addr">
                            <label class="form-check-label" for="status_addr">
                                Set a primary address
                            </label>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-orange">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- add address Modal -->
<?php
$stateUrl = site_url('location/getStates');
$cityUrl = site_url('location/getCities');
$profileScript = <<<EOD
<script>
    $(document).ready(function() {
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

    document.getElementById("status_addr").addEventListener("change", function() {
        if (this.checked) {
            this.value = "1"; // Pass value when checked
        } else {
            this.value = "0"; // Optional: Change value when unchecked
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".status_addr_update").forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                this.value = this.checked ? "1" : "0"; // Set value based on checked state
            });
        });
    });
</script>

EOD;


session()->set('profileScript', $profileScript);
?>
<?= $this->include('frontend/layouts/footer') ?>