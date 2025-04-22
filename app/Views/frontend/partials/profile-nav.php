<?php
$uri = service('uri');
$currentPath = ltrim($uri->getPath(),"/"); // Get full path without domain
?>

<div class="col-md-3">
    <div class="dashboard-menu">
        <ul class="nav flex-column" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= ($currentPath == 'auth/my-account') ? 'active' : '' ?>" 
                   href="<?= site_url('auth/my-account') ?>">
                    <i class="bi bi-person mr-10"></i>My Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPath == 'auth/manage-address') ? 'active' : '' ?>" 
                   href="<?= site_url('auth/manage-address') ?>">
                    <i class="bi bi-house mr-10"></i>Manage Addresses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPath == 'user/orders' || strpos($currentPath, 'user/orders/showDetails/') === 0) ? 'active' : '' ?>" 
                href="<?= site_url('user/orders') ?>">
                    <i class="bi bi-shop-window mr-10"></i>Order History
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPath == 'user/wishlists') ? 'active' : '' ?>" 
                   href="<?= site_url('user/wishlists') ?>" role="tab">
                    <i class="bi bi-heart mr-10"></i>My Wishlist
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($currentPath == 'user/change-password') ? 'active' : '' ?>" 
                   href="<?= site_url('user/change-password') ?>">
                    <i class="bi bi-key mr-10"></i>Change Password
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('auth/logout') ?>">
                    <i class="bi bi-box-arrow-right mr-10"></i>Logout
                </a>
            </li>
        </ul>
    </div>
</div>
