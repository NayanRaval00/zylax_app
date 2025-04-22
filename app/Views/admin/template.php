<!DOCTYPE html>
<html>
<?php echo view('admin/include-head.php'); ?>
<div id="loading">
    <div class="lds-ring">
        <div></div>
    </div>
</div>
<body class="hold-transition sidebar-mini layout-fixed ">
    <div class=" wrapper ">
        <?php echo view('admin/include-navbar.php') ?>
        <?php echo view('admin/include-sidebar.php'); ?>
        <?php echo view('admin/pages/' . $main_page); ?>
        <?php echo view('admin/include-footer.php'); ?>
    </div>
    <?php echo view('admin/include-script.php'); ?>
</body>

</html>