<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage blog's category</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"> Category Blogs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" id="category_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">
                        <div class="col-md-12">

                        </div>
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/blogs/create_category' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Category</a>
                            </div>
                        </div>
                        <div class="card-innr" id="list_view_html">
                            <div class="card-head">
                                <h4 class="card-title">Category Blogs</h4>
                            </div>
                           
                             <div class="card-body">

                                <table id="custom_category_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($category_result as $category) : ?>
                                        <tr>
                                            <td><?= $category['name'] ?></td>
                                            <td>
                                                <div class="image-box-100">
                                                    <a href="<?= base_url().$category['image'] ?>"
                                                        data-toggle="lightbox" data-gallery="gallery">
                                                        <img class="rounded" src="<?= base_url().$category['image'] ?>">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($category['status'] == 1): ?>
                                                <a class="badge badge-success text-white">Active</a>
                                                <?php else: ?>
                                                <a class="badge badge-danger text-white">Inactive</a>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center; ">
                                                <a href="<?= base_url('admin/blogs/edit_category?edit_id='.$category['id']) ?>"
                                                    class=" btn action-btn btn-success btn-xs mr-1 mb-1" title="Edit"
                                                    data-id="1" data-url="admin/category/create_category"><i
                                                        class="fa fa-pen"></i></a>
                                                <a class="delete-blog-category btn action-btn btn-danger btn-xs mr-1 mb-1 ml-1" title="Delete" href="javascript:void(0)" data-id="<?= $category['id'] ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php if($category['status'] == 1): ?>
                                                    <a class="btn btn-warning action-btn btn-xs update_active_status ml-1 mr-1 mb-1" data-table="categories" title="Deactivate" href="<?= base_url('admin/blogs/update_category_status/'.$category['id'].'/0') ?>">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a class="btn btn-primary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="categories" href="<?= base_url('admin/blogs/update_category_status/'.$category['id'].'/1') ?>" title="Active">
                                                        <i class="fa fa-eye"></i>
                                                    </a>                                                        
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>

                        </div><!-- .card-innr -->
                        <div id="tree_view_html">
                        </div>
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
