<!DOCTYPE html>
<html>
<?php echo view('admin/include-head.php'); ?>

<body class="hold-transition sidebar-mini layout-fixed ">
    <div class=" wrapper ">
        <?php echo view('admin/pages/' . $main_page); ?>
    </div>
    <?php echo view('admin/include-script.php'); ?>
</body>

</html>