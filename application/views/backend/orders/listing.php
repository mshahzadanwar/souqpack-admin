<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Orders Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Orders</li>
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
                        <?php
                            $parent_cat = $this->db->query("SELECT title,id,parent,lparent FROM categories where parent = 0 AND lparent = 0")->result_object();
                            //echo $this->db->last_query();
                        ?>
                        <form action="<?php echo base_url();?>admin/orders/set_filter" method="post" style="display: flex;">
                            
                            <!-- WEB MOBILE CHANGE -->
                            <select name="status_web_change" id="status_web_change" class="form-control" style="float: left;">
                                <option value="">-- Choose Web/Mobile--</option>
                               
                                    <option value="1" 
                                        <?php if($_SESSION['status_web_change']=="1") {echo "SELECTED";} ?> 
                                    >
                                       Website Order(s)
                                    </option>
                                    <option value="2" 
                                        <?php if($_SESSION['status_web_change'] == "2") {echo "SELECTED";} ?> 
                                    >
                                       Mobile Order(s)
                                    </option>

                            </select>
                            <select name="status_payment" id="status_payment" class="form-control" style="float: left;">
                                <option value="">-- Choose Payment Status--</option>
                                    
                                    <option value="1" 
                                        <?php if($_SESSION['status_payment']=="1") {echo "SELECTED";} ?> 
                                    >
                                       Payment Success
                                    </option>
                                    <option value="2" 
                                        <?php if($_SESSION['status_payment'] == "2") {echo "SELECTED";} ?> 
                                    >
                                       Payment Error
                                    </option>
                                     <option value="0" 
                                        <?php if($_SESSION['status_payment']=="0") {echo "SELECTED";} ?> 
                                    >
                                       Payment Pending
                                    </option>

                            </select>
                            <button class="btn btn-primary" name="form_filter" type="submit" style="float: left;">Go</button>

                            <?php if(isset($_SESSION['status_web_change'])){?>
                                <button onclick="reset_form()" class="btn btn-secondary btn-sm" type="button" style="float: left;">Reset</button>
                            <?php } ?>
                        </form>
                    </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Orders <?php if(isset($_SESSION['status_web_change']) && $_SESSION['status_web_change'] == 1){echo "(From Website)";}else if(isset($_SESSION['status_web_change']) && $_SESSION['status_web_change'] == 2) {echo "(From Mobile)";}?></h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                    <label>Status</label>
                                    <select name="s_status" required>
                                        <option
                                        <?php if($_GET["s_status"]==1) echo "selected"; ?>
                                         value="1">Order Received</option>
                                        <option 
                                        <?php if($_GET["s_status"]==3) echo "selected"; ?>

                                        value="3">Prepairing</option>
                                        <option 
                                        <?php if($_GET["s_status"]==4) echo "selected"; ?>

                                        value="4">Shipped</option>
                                        <option 
                                        <?php if($_GET["s_status"]==5) echo "selected"; ?>

                                        value="5">Review</option>
                                        <option 
                                        <?php if($_GET["s_status"]==6) echo "selected"; ?>

                                        value="6">Closed</option>
                                        <option 
                                        <?php if($_GET["s_status"]==7) echo "selected"; ?>

                                        value="7">Cancelled</option>
                                    </select>

                                    <label style="margin-left: 10px;">From Date</label>
                                    <input type="text" class="publish2 required" name="start_date" 
                                    value="<?php echo $_GET["start_date"]; ?>" 
                                    />

                                    <label style="margin-left: 10px;">To Date</label>
                                    <input type="text" class="publish2 required" name="to_date" 
                                    value="<?php echo $_GET["to_date"]; ?>" 
                                    />

                                    <?php if($_GET["start_date"]!=""){ ?>
                                        <a href="<?php echo current_url(); ?>">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                Clear Filter
                                            </button>
                                        </a>
                                    <?php } ?>

                                    <button class="btn btn-sm btn-primary">Filter</button>

                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Product(s)</th>
                                    <th>Web/Mobile</th>
                                    <th>Grand Total</th>
                                    <th>Payment</th>
                                    
                                    <th>Status</th>
                                    <th>Mark as</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Product(s)</th>
                                    <th>Web/Mobile</th>
                                    <th>Grand Total</th>
                                    <th>Payment</th>
                                    
                                    <th>Status</th>
                                    <th>Mark as</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($orders->result() as $order){

                                $product = $this->db->where('id',$order->product_id)
                                ->get('products')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->user_id)
                                ->get('users')
                                ->result_object()[0];

                            ?>
                            <tr>
                                <td>
                                    #<?php echo $order->id;?>
                                </td>

                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
                                </td>

                                <td>
                                    <?php

                                    $products_order = $this->db->where('order_id',$order->id)->get('order_products')->result_object();
                                        $prod_ids = array(-1);

                                        foreach($products_order as $product){
                                            $prod_ids[] = $product->product_id;

                                        }

                                        $products = $this->db->where_in('id',$prod_ids)->where('is_deleted',0)->get('products')->result_object();
                                        foreach($products as $product)
                                        {
                                            $products_order_dis = $this->db->where('order_id',$order->id)->where('product_id',$product->id)->get('order_products')->result_object()[0];
                                            
                                            echo $product->title.'  ('.substr($products_order_dis->variation,1,-1).')<br> ';
                                        }
                                     ?>
                                </td>
                                 <td>
                                <?php 
                                    if($order->from_web_mobile == "1"){echo "<b style='color:green;'>WEBSITE</b>";}
                                    if($order->from_web_mobile == "2"){echo "<b style='color:purple;'>MOBILE</b>";} 
                                 ?>

                               </td>
                                <td> 
                                    <?php echo $order->total.' '.$order->currency; ?>
                                </td>
                                <td>
                                    <?php if($order->payment_done==1) { ?>
                                        <span class="btn btn-success btn-xs">Done</span>
                                    <?php }else if($order->payment_done==2) { ?>
                                        <span class="btn btn-danger btn-xs">Payment Error<br>
                                            <i>(<?php echo $order->payment_reason_rejct;?>)</span>
                                    <?php }else { ?>
                                        <span class="btn btn-warning btn-xs">Pending</span>
                                    <?php } ?>
                                    <br>
                                    <?php if($order->payment_method==4) echo "(COD)"; ?>
                                    <?php if($order->payment_method==1) echo "(PayPal)"; ?>
                                    <?php if($order->payment_method==3) echo "(PayFort)"; ?>

                                </td>
                              
                            	<td>
                                        
                                    <?php  if($order->status == 1 || $order->status==2){?>
                                    (Order Received)
                                    <?php } if($order->status == 3){?>
                                    (Prepairing)
                                    <?php } if($order->status == 4){?>
                                    (Shipped)<br>
                                    <?php echo "Tracking #".$order->shipping_number;?>
                                    <?php } if($order->status == 5){?>
                                    (Review)
                            		<?php } if($order->status == 6){?>
                                    (Completed)
                                    <?php } if($order->status == 7){?>
                                    <span style="color:red;">(Cancelled)

                                    <?php 
                                    if($order->cancelled_by=="USER") echo "By User, Reason: " .$order->reason;
                                        }
                                        if($order->status == 8){
                                            $refund = $this->db->query("SELECT * FROM refund WHERE pID = ".$order->id)->result_object()[0];
                                    ?>
                                    <span style="color:red;"><b style="color: orange;">(Refund Request)</b>
                                        <br><b>Reason:</b>
                                        <?php echo $refund->refund_reason;?>

                                    <?php }
                                     ?>
</span>
                                
                                    
                            	</td>
                                <td>
                                    <?php if($order->payment_done==1) { ?>
                                        <?php  if($order->status == 1 || $order->status==2){?>
                                         <a href="<?php echo $url.'admin/order-status/'.$order->id.'/3';?>" ><span class="btn btn-info btn-xs">Prepairing</span></a>
                                        <?php } if($order->status == 3){?>
                                        <a href="javascript:;" onclick="show_modal('<?php echo $order->id;?>','4')" ><span class="btn btn-info btn-xs">Shipped</span></a>


                                        <?php /* ?>
                                        <a href="<?php echo $url.'admin/order-status/'.$order->id.'/4';?>" ><span class="btn btn-info btn-xs">Shipped</span></a>
                                        <?php */ ?>
                                        <?php } if($order->status == 11){?>
                                        <a href="<?php echo $url.'admin/order-status/'.$order->id.'/5';?>" ><span class="btn btn-info btn-xs">Review</span></a>
                                        <?php } if($order->status == 5 || $order->status == 4){?>
                                        <a href="<?php echo $url.'admin/order-status/'.$order->id.'/6';?>" ><span class="btn btn-info btn-xs">Mark as Delivered</span></a>
                                        <?php } if($order->status == 1 || $order->status==2){?>
                                        <a href="<?php echo $url.'admin/order-status/'.$order->id.'/7';?>" ><span class="btn btn-info btn-xs">Cancel</span></a>
                                        <?php }  ?>
                                        <?php if($order->status == 6){echo "ORDER COMPLETED";}?>
                                    <?php } else{ ?>
                                        WAITING FOR PAYMENT..
                                    <?php } ?>
                                </td>

                               
                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($order->created_at));?>
                            	</td>
                                <td>

                                   

                                    <a href="<?php echo $url."admin/";?>view-invoice-template/0/<?php echo $order->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-file-o"></i></div></a>
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
        <h4 class="modal-title">Add Shipping Number:</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form method="post" action="">
      <div class="modal-body driver_modal_body">
        <div class="">
            <div class="card-bodyd" style="padding: 0; display: block !important;">
                
                <input type="text" id="shipp_num" class="form-control" required name="shipp_num" />
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
        window.location.href = '<?php echo base_url()."admin/orders/reset_filter";?>';
    }

    function show_modal(id,status){
        $("#readyToDeliver").modal('show');
        var ul = '<?php echo $url.'admin/order-status/';?>'+id+'/'+status;
        $("form").attr('action', ul);
    }

    function closeModal(){
        $("#readyToDeliver").modal('hide');
         $("form").attr('action', '');
    }

</script>