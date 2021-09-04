<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Coupons Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-coupon">
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
                    <h4 class="card-title">coupons</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($coupons->result() as $coupon){

                                $parent = $this->db->where('id',$coupon->parent)
                                ->get('coupons')
                                ->result_object();

                            ?>
                            <tr>
                                <td>
                                    <?php echo $coupon->title;?>
                                </td>
                                <td>
                                    <?php echo $coupon->code;?>
                                </td>
                                <td>
                                    <?php if($coupon->discount_type=="0")
                                    {
                                        echo "N/A";
                                    }elseif($coupon->discount_type=="1")
                                    {
                                        echo "$".$coupon->discount."USD";
                                    }elseif ($coupon->discount_type=="2") {
                                        echo $coupon->discount."%";
                                    }else echo "N/A"; ?>
                                </td>
                                <td>
                                    <?php echo $coupon->from_date . ' to '.$coupon->to_date; ?>
                                </td>
                               
                            	<td>
                            		<?php if($coupon->status == 0){?>
                                        <a href="<?php echo $url.'admin/coupon-status/'.$coupon->id.'/'.$coupon->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                            		<?php }else{?>
                                        <a href="<?php echo $url.'admin/coupon-status/'.$coupon->id.'/'.$coupon->status;?>" ><span class="btn btn-success">Active</span></a>
                            		<?php } ?>
                            	</td>


                            	
                                <td>

                                    <a href="<?php echo $url."admin/";?>edit-coupon/<?php echo $coupon->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-coupon/<?php echo $coupon->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>
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