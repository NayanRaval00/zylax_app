<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Slider Image For Add-on Offers and other benefits </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Slider</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/slider/create_slider' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Slider</a>
                            </div>
                        </div>
                        <div class="card-innr">
                        
                            <div class="card-head">
                                <h4 class="card-title">Sliders</h4>
                            </div>
                            <div class="card-body">

                                <table id="custom_category_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($slider_result as $slider) : ?>
                                        <tr>
                                            <td><?= $slider['id'] ?></td>
                                            <td><?= $slider['type'] ?></td>
                                            <td>
                                                <div class="image-box-100">
                                                    <a href="<?= base_url().$slider['image'] ?>"
                                                        data-toggle="lightbox" data-gallery="gallery">
                                                        <img class="rounded" src="<?= base_url().$slider['image'] ?>">
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="text-align: center; ">
                                                <a href="<?= base_url('admin/slider/edit_slider?edit_id='.$slider['id']) ?>"
                                                    class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                                                    data-id="1" data-url="admin/category/create_category"><i
                                                        class="fa fa-pen"></i></a>
                                                <a class="delete-slider btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="<?= $slider['id'] ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                        
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


</div>