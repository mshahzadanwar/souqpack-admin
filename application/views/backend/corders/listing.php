<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Customized Orders Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customized Orders</li>
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
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customized Orders</h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                    <label>Admin Status</label>
                                    <select name="admin_status" required>

                                        <option
                                        <?php if($_GET["admin_status"]==-1) echo "selected"; ?>
                                         value="1">Any</option>
                                            <?php
                                    $status = admin_statuses();
                                     ?>

                                         <?php foreach($status as $k=>$v){ ?>
                                            <option
                                            <?php if($k==$_GET["admin_status"]) echo "selected"; ?>
                                             value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                         <?php } ?>
                                        
                                    </select>




                                    <label style="margin-left: 10px;">From Date</label>
                                    <input type="text" class="publish2 required" name="start_date" 
                                    value="<?php echo $_GET["start_date"]; ?>" 
                                    />

                                    <label style="margin-left: 10px;">To Date</label>
                                    <input type="text" class="publish2 required" name="to_date" 
                                    value="<?php echo $_GET["to_date"]; ?>" 
                                    />

                                    <?php if($_GET["start_date"]!="" || $_GET["to_date"]!="" || $_GET["admin_status"]!=""){ ?>
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
                                    <th>Qty.</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Prints</th>
                                    <th>Mobile/Web</th>
                                    <th>Logo</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Delivery Date</th>
                                    <th>Grand Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Qty.</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Prints</th>
                                    <th>Mobile/Web</th>
                                    <th>Logo</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Delivery Date</th>
                                    <th>Grand Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            if($_GET["start_date"]!="")
                                $this->db->where("DATE(created_at) >=",strtotime($_GET["start_date"]));

                            if($_GET["to_date"]!="")
                                $this->db->where("DATE(created_at) <=",strtotime($_GET["to_date"]));
                            if($_GET["admin_status"]!="")
                                $this->db->where("admin_status",$_GET["admin_status"]);
                             if($_GET["user_id"]!=""){
                                $corders=$this->db->where("user_id",$_GET["user_id"])->order_by("id","DESC")->get("c_orders")->result_object();
                             } else{
                                $corders=$this->db->order_by("id","DESC")->get("c_orders")->result_object();
                            }
                            
                            foreach($corders as $order){

                                $this_cat = $this->db->where('id',$order->cat_id)
                                ->get('categories')
                                ->result_object()[0];

                                $p_cat = $this->db->where('id',$this_cat->parent)
                                ->get('categories')
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
                                    
                                    <?php if($order->old_qty !="0"){?>
                                        <?php echo $order->qty;?>
                                        <i style="color: #ccc;"><?php echo $order->old_qty;?></i>
                                    <?php } else {?>
                                        <?php echo $order->qty;?>
                                    <?php }?>
                                    <?php if($order->payment_done_part_2 ==0){?>
                                    <br>
                                    <button onclick="update_qty_custom(<?php echo $order->id;?>,<?php echo $order->cat_id;?>,<?php echo $order->qty;?>)" type="button" class="btn btn-xs btn-primary">Update</button>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php echo $order->whg;?>
                                </td>
                                <td>
                                    <?php echo $order->print_face_title;?>
                                </td>
                                <td>
                                    <?php /*
                                    $options = json_decode($order->options);
                                    foreach($options as $option)
                                    {
                                        echo "<b>".$option->title.'</b>: '.$option->value;
                                        echo "<br>";
                                    }*/
                                    ?>
                                    <?php 
                                        if($order->from_web_mobile == "1"){echo "<b style='color:green;'>WEBSITE</b>";}
                                        if($order->from_web_mobile == "2"){echo "<b style='color:purple;'>MOBILE</b>";} 
                                    ?>
                                </td>

                                <td>
                                    <?php 
                                    if($order->logo_type==1)
                                    {
                                        echo "Uploaded";
                                        echo "<br>";
                                        $ext = pathinfo($order->file_name, PATHINFO_EXTENSION);
                                        if($ext == "jpg" || $ext == "png" || $ext == "jpg") {
                                        echo "<img src='".base_url()."/resources/uploads/orders/".$order->file_name."' width='50px' />";
                                        }else{
                                            echo "<img src='".base_url()."/resources/uploads/orders/PDF_file_icon.svg' width='30px' />";
                                        } 
                                    }
                                    else if($order->logo_type==2)
                                    {
                                        echo "(Create Logo)";
                                        echo "<br>";

                                        echo "Name: ".$order->logo_name;
                                        echo "<br>";
                                        echo "Desc: ".$order->logo_desc;
                                    }
                                    ?>
                                </td>
                                

                                

                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
                                </td>

                                <td>
                                    <?php

                                    echo $p_cat->title .' / '.$this_cat->title;
                                     ?>
                                </td>
                                <td>
                                    <?php 
                                        $Date1 = $order->created_at; 
                                        $days = $this_cat->delivery." ".$this_cat->delivery_type;
                                        $Date2 = date('F, d Y', strtotime($Date1 . " + ".$days));
                                        echo $Date2;
                                    ?>
                                </td>
                                <td> 
                                    <?php echo with_currency($order->all_total); ?>
                                </td>
                                <td>
                                    <?php if($order->payment_done_part_1==1) { ?>
                                        <span class="btn btn-success btn-xs">Down Payment Arrived</span>
                                    <?php }else{ ?>
                                        <span class="btn btn-warning btn-xs">Down Payment Pending</span>
                                    <?php } ?>

                                    <?php if($order->payment_done_part_1==1){ if($order->payment_done_part_2==1) { ?>
                                        <span class="btn btn-success btn-xs">Remaining Payment Arrived</span>
                                    <?php }else{ ?>
                                        <span class="btn btn-warning btn-xs">Remaining Payment Pending</span>
                                    <?php } } ?>

                                    

                                </td>
                               
                            	<td>
                                    (<?php echo admin_status($order->admin_status); ?>)
                            	</td>
                                

                               
                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($order->created_at));?>
                            	</td>
                                <td>


                                    <a href="<?php echo $url."admin/";?>view-c_order/<?php echo $order->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-tv"></i></div></a>

                                   

                                    <!-- <a href="<?php echo $url."admin/";?>view-invoice-template/0/<?php echo $order->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-file-o"></i></div></a> -->
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

<div id="updateQty" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Order Quantity</h4>

        <button type="button" class="close" onclick="closeModal()" data-dismiss="modal">&times;</button>

      </div>
      <form method="post" action="" id="formId">
      <div class="modal-body driver_modal_body">
        <div class="form-group " >
          <label for="desc">Quantity</label>
          <div class="controls ">
              <div class="switchery-demo">
                  <input type="number" step="1" required placeholder="Enter New Quantity" class="form-control form-control-line oldQty" name="qty" ></textarea>
              </div>
          </div>
        </div>
       <div class="form-group " >
          <label for="desc">Please type your comment/Reason</label>
          <div class="controls ">
              <div class="switchery-demo m-b-20">
                  <textarea required="" rows=5 placeholder="Please comment down in details, why you are updating the Quantity.... " class="form-control form-control-line" name="desc" ></textarea>
              </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal()" data-dismiss="modal">Close</button>
         <button  class="btn btn-primary" >Update Quantity</button>
      </div>
    </form>
    </div>
  </div>
</div>


<script type="text/javascript">
    function update_qty_custom(id,cat_id,qty) {
        $("#updateQty").modal('show');
        $(".oldQty").val(qty);
        var url = "<?php echo base_url();?>admin/corders/update_price_custom_order/"+id+"/"+cat_id;
        $('#formId').attr('action', url);
    }
    function closeModal(){
        $("#updateQty").modal('hide');
        $('#formId').attr('action', '');
        $(".oldQty").val('');
    }
</script>