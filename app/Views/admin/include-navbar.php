<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item my-auto">
            <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        
        <!-- google translate  -->
        <!-- <div id="google_translate_element"></div> -->

        <!-- start send admin notification  -->
        <?php
        // $notifications = fetch_details('system_notification',  NULL,  '*',  '3', '0',  'read_by', 'ASC',  '',  '');
        // $count_noti = fetch_details('system_notification',  ["read_by" => 0],  'count(id) as total');
        ?>

        <div id="refresh_notification"> </div>
        <div id="list" class="dropdown-menu dropdown-menu-lg dropdown-menu-right"></div>
        <!-- end send admin notification  -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-2x fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">Welcome <b>Admin ! </a>
                <!-- <a href="<?= base_url('admin/home/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a> -->
                <a href="<?= base_url('admin/logout') ?>" class="dropdown-item">
                    <i class="fa fa-sign-out-alt mr-2"></i> Log Out
                </a>
            </div>
        </li>
    </ul>
</nav>