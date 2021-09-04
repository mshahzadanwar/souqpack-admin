<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">ORDER REFUND MANAGEMENT</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Refund Requests</li>
                </ol>
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
             <div class="filter_by">
                    <label>Filter By: </label>
                    <span>
                        <form action="<?php echo base_url();?>admin/orders/set_refund_filter" method="post" style="display: flex;">
                            
                            <select name="status_refund" id="status_refund" class="form-control" style="float: left;">
                                <option value="">-- Choose Refund Status--</option>
                                    
                                    <option value="0" 
                                        <?php if($_SESSION['status_refund']=="0") {echo "SELECTED";} ?> 
                                    >
                                       Received
                                    </option>
                                    <option value="1" 
                                        <?php if($_SESSION['status_refund'] == "1") {echo "SELECTED";} ?> 
                                    >
                                      Request Accepted
                                    </option>
                                     <option value="2" 
                                        <?php if($_SESSION['status_refund']=="2") {echo "SELECTED";} ?> 
                                    >
                                       Money Transferred
                                    </option>
                                    <option value="3" 
                                        <?php if($_SESSION['status_refund']=="3") {echo "SELECTED";} ?> 
                                    >
                                       Completed
                                    </option>
                                    <option value="4" 
                                        <?php if($_SESSION['status_refund']=="4") {echo "SELECTED";} ?> 
                                    >
                                       Declined
                                    </option>
                            </select>
                            <button class="btn btn-primary" name="form_filter" type="submit" style="float: left;">Go</button>

                            <?php if(isset($_SESSION['filter_web_ref'])){?>
                                <button onclick="reset_form()" class="btn btn-secondary btn-sm" type="button" style="float: left;">Reset</button>
                            <?php } ?>
                        </form>
                    </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order (REFUND) </h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Bank Name</th>
                                    <th>Account #</th>
                                    <th>Account Holder Name</th>
                                    <th>Bank Address</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Mark as</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Bank Name</th>
                                    <th>Account #</th>
                                    <th>Account Holder Name</th>
                                    <th>Bank Address</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Mark as</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($refund as $order){

                                $product = $this->db->where('id',$order->product_id)
                                ->get('products')
                                ->result_object()[0];

                                $order_det = $this->db->where('id',$order->pID)
                                ->get('orders')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->uID)
                                ->get('users')
                                ->result_object()[0];

                            ?>
                            <tr>
                                <td>
                                    <?php echo $order->id;?>
                                </td>
                                <td>
                                    #<?php echo $order->pID;?>
                                </td>
                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
                                </td>
                                <td>
                                   <?php echo $order_det->total;?> <?php echo $order_det->currency;?>
                                </td>
                               
                                 <td>
                                <?php 
                                    echo $order->bank_name;
                                 ?>

                               </td>
                                <td> 
                                    <?php echo $order->account_number; ?>
                                </td>
                                <td> 
                                    <?php echo $order->account_holder; ?>
                                </td>
                                <td> 
                                    <?php echo $order->bank_address; ?>
                                </td>
                                <td> 
                                    (Customer)<br>
                                    <?php echo $order->refund_reason; ?>
                                </td>
                            	<td>
                                    <?php  if($order->status == 0){?>
                                    (Received)<br>
                                    <a href="<?php echo $url.'admin/refund-status/'.$order->id.'/1';?>" ><span class="btn btn-info btn-xs">Mark as Confirmed</span></a>
                                    <a href="javascript:;" onclick="show_modal('<?php echo $order->id;?>','4')" >
                                        <span class="btn btn-danger btn-xs">Mark as Declined</span>
                                    </a>
                                    <?php } if($order->status == 1){?>
                                    (Confirmed)<br>
                                    <a href="<?php echo $url.'admin/refund-status/'.$order->id.'/2';?>" ><span class="btn btn-info btn-xs">Mark as Money Transferred</span></a>
                                    <?php } if($order->status == 2){?>
                                    (Money Transferred)<br>
                                    <a href="<?php echo $url.'admin/refund-status/'.$order->id.'/3';?>" ><span class="btn btn-info btn-xs">Mark as Completed</span></a>
                                    <?php } if($order->status == 3){?>
                                    (Completed)
                            		<?php } if($order->status == 4){?>
                                    <b style="color: red">(Declined)</b><br>
                                    <b>Reason:</b>
                                    <br><?php echo $order->cancel_reason;?>
                                    <?php } ?>
                            	</td>
                               
                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($order->date_created));?>
                            	</td>
                                <td>
                                    <a target="_blank" href="<?php echo $url."admin/";?>view-invoice-template/0/<?php echo $order->pID;?>"><div class="btn btn-info btn-circle"><i class="fa fa-file-o"></i></div></a>
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

<div id="readyToDeliver" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Rejection Reason:</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form method="post" action="">
      <div class="modal-body driver_modal_body">
        <div class="">
            <div class="card-bodyd" style="padding: 0; display: block !important;">
                <textarea name="reason_rejection" id="reason_rejection" class="form-control" required></textarea>
            </div>
        </div>
      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Later</button>
         <button  class="btn btn-primary" >Update Status</button>
      </div>
    </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    function reset_form(){
        window.location.href = '<?php echo base_url()."admin/orders/reset_filter_ref";?>';
    }

    function show_modal(id,status){
        $("#readyToDeliver").modal('show');
        var ul = '<?php echo $url.'admin/refund-status/';?>'+id+'/'+status;
        $("form").attr('action', ul);
    }

    function closeModal(){
        $("#readyToDeliver").modal('hide');
         $("form").attr('action', '');
    }

</script>