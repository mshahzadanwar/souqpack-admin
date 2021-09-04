<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Purchases Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-purchase">
                    <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                </a>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchases</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    
                                    <th>Stock</th>
                                    <th>SKU</th>
                                    <!-- <th>Size</th> -->
                                    <!-- <th>Vendor</th> -->
                                    <th>Cost Price</th>
                                    
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Product Name</th>
                                    
                                     <th>Stock</th>
                                    <th>SKU</th>
                                    <!-- <th>Size</th>
                                    <th>Vendor</th> -->
                                    <th>Cost Price</th>
                                    
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($purchases->result() as $purchase){

                            

                            ?>
                            <tr>
                                <td>
                                    <?php echo $purchase->title;?>
                                </td>
                              

                                <td>
                                    <?php echo $purchase->avl_qty;?>
                                </td>
                                <td>
                                    <?php echo $purchase->sku;?>
                                </td>

                                <td>
                                    <?php echo $purchase->price;?>
                                </td>
                               
                            	<td>
                            		<?php if($purchase->status == 0){?>
                                        <a href="<?php echo $url.'admin/purchase-status/'.$purchase->id.'/'.$purchase->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                            		<?php }else{?>
                                        <a href="<?php echo $url.'admin/purchase-status/'.$purchase->id.'/'.$purchase->status;?>" ><span class="btn btn-success">Active</span></a>
                            		<?php } ?>
                            	</td>


                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($purchase->created_at));?>
                            	</td>
                                <td>

                                    <a href="javascript:viewDetails(<?php echo $purchase->id; ?>)">
                                        <div class="btn btn-info btn-circle">
                                            <i class="fa fa-tv"></i>
                                        </div>
                                    </a>


                                    <a href="<?php echo $url."admin/";?>edit-purchase/<?php echo $purchase->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>


                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-purchase/<?php echo $purchase->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>
                                </td>
                            </tr>

                            
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>


<div class="modal fade" id="view_details_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document" style="max-width: 70%;">
            <div class="modal-content">
              

            <div class="row">
                <div class="col-12">
                <?=form_open_multipart('',array('class'=>'form-material', 'id'=>"contactForm3", 'novalidate'=>""));?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Details</h4>
                    </div>

                    <div class="card-body details_body">
                        
                    </div>
                    <div class="card-body">
                        <div class="text-xs-right">
                            
                            <button type="button" class="close btn btn-inverse" data-dismiss="modal" aria-label="Close">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
