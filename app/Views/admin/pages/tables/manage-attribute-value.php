<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Manage Attribute Value</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Attribute Value</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div id="attribute_value_id" class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Attribute Value</h5>
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
                        <div class="card-header border-0">
                            <div class="card-tools">
                                <a href="<?= base_url() . 'admin/attributevalue' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Attribute Value</a>
                            </div>
                        </div>
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Attribute Value</h4>
                            </div>
                           
                            <table id="custom_category_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Attribute Name</th>
                                        <th scope="col">Attribute Value</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attribute_values_result as $attribute_values) : ?>
                                    <tr>
                                        <td><?= $attribute_values['id'] ?></td>
                                        <td><?= $attribute_values['attribute_name'] ?></td>
                                        <td><?= '$'.$attribute_values['value'] ?></td>
                                        <td>
                                            <?php if($attribute_values['status'] == 1): ?>
                                                <span class="badge badge-success text-white">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger text-white">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center; ">
                                            <!-- <a href="<?= base_url('admin/attributevalue/edit_attribute_value?edit_id='.$attribute_values['id']) ?>"
                                                class=" btn action-btn btn-success btn-xs mr-1 mb-1" $attribute_values="Edit"
                                                data-id="1" data-url="admin/category/create_category"><i
                                                    class="fa fa-pen"></i>
                                            </a> -->
                                            <?php if($attribute_values['status'] == 1): ?>
                                                <a class="btn btn-warning action-btn btn-xs update_active_status ml-1 mr-1 mb-1" data-table="categories" title="Deactivate" href="<?= base_url('admin/attributevalue/update_attribute_value_status/'.$attribute_values['id'].'/0') ?>">
                                                    <i class="fa fa-eye-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-primary action-btn mr-1 mb-1 ml-1 btn-xs update_active_status" data-table="categories" href="<?= base_url('admin/attributevalue/update_attribute_value_status/'.$attribute_values['id'].'/1') ?>" title="Active">
                                                    <i class="fa fa-eye"></i>
                                                </a>                                                        
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
