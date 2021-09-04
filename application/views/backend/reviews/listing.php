<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Order Reviews Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Order Reviews</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-review">
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
                    <h4 class="card-title">Reviews</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Image</th>
                                    <th>Data & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Image</th>
                                    <th>Data & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($reviews->result() as $review){

                                $user = $this->db->where('guest_id',$review->user_id)
                                ->get('users')
                                ->result_object()[0];

                                $user_phone = $user->code.$user->phone;

                            ?>
                            <tr>
                                <td>
                                    #00P<?php echo $review->order_id; ?>
                                </td>
                                <td>
                                    <?php echo $review->anony==1?"Anonymous":$user_phone; ?>
                                </td>

                                <td>
                                    <?php for($i=1; $i<=5; $i++){ ?>
                                        <i  class="fa fa-star<?php echo $i<=$review->rating?"":"-o"; ?>"></i>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php echo $review->review; ?>
                                </td>
                                <td>
                                    <?php if($review->image!=""){ ?>
                                    <img src="<?php echo base_url()."resources/uploads/reviews/".$review->image; ?>" style="width: 100px;">
                                <?php } ?>
                                </td>
                               
                            	
                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($review->created_at));?>
                            	</td>
                                <td>
                                    <?php if($review->status == 0){?>
                                        <span class="btn btn-warning btn-sm">Pending</span>
                                    <?php } if($review->status == 1){?>
                                        <span class="btn btn-success btn-sm">Approved</span>
                                    <?php } if($review->status == 2){?>
                                        <span class="btn btn-danger btn-sm">Rejected</span>
                                    <?php } ?>
                                </td>
                                <td>


                                    <?php if($review->status == 0 || $review->status == 2){?>

                                     <a title="Reject" href="<?php echo $url.'admin/review-status/'.$review->id.'/2';?>" ><span class="btn btn-danger btn-circle"><i class="fa fa-times"></i></span></a>

                                      <a title="Approve" href="<?php echo $url.'admin/review-status/'.$review->id.'/1';?>" ><span class="btn btn-success btn-circle"><i class="fa fa-check"></i></span></a>
                                      <?php } ?>


                                   
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-review/<?php echo $review->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>
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