<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Bank Transfer Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bank Transfer</li>
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
                        <form action="<?php echo base_url();?>admin/corders/set_filter" method="post" style="display: flex;">
                                <?php //echo $_SESSION['status_payment_custom'];?>
                            <select name="status_payment" id="status_payment" class="form-control" style="float: left;">
                                <option value="">-- Choose Status--</option>
                                    
                                    <option value="1" 
                                        <?php if($_SESSION['status_payment_custom']=="1") {echo "SELECTED";} ?> 
                                    >
                                       Approved
                                    </option>
                                    <option value="0" 
                                        <?php if($_SESSION['status_payment_custom']=="0") {echo "SELECTED";} ?> 
                                    >
                                       Pending Approval
                                    </option>

                                    <option value="-1" 
                                        <?php if($_SESSION['status_payment_custom']=="-1") {echo "SELECTED";} ?> 
                                    >
                                       Down Payment Pending
                                    </option>
                                    <option value="-2" 
                                        <?php if($_SESSION['status_payment_custom']=="-2") {echo "SELECTED";} ?> 
                                    >
                                       Final Payment Pending
                                    </option>
                                    <option value="-3" 
                                        <?php if($_SESSION['status_payment_custom']=="-3") {echo "SELECTED";} ?> 
                                    >
                                       Down Payment Approved
                                    </option>
                                    <option value="-4" 
                                        <?php if($_SESSION['status_payment_custom']=="-4") {echo "SELECTED";} ?> 
                                    >
                                       Final Payment Approved
                                    </option>

                            </select>
                            <button class="btn btn-primary" name="form_filter" type="submit" style="float: left;">Go</button>

                            <?php if(isset($_SESSION['filter_web_custom'])){?>
                                <button onclick="reset_form()" class="btn btn-secondary btn-sm" type="button" style="float: left;">Reset</button>
                            <?php } ?>
                        </form>
                    </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Bank Transfer</h4>

                  
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>user</th>
                                    <th>Receipt</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>user</th>
                                    <th>Receipt</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                             if($_SESSION['status_payment_custom']=="-1" || $_SESSION['status_payment_custom']=="-2" || $_SESSION['status_payment_custom']=="-3" || $_SESSION['status_payment_custom']=="-4"){

                                        if($_SESSION['status_payment_custom']=="-1"){
                                            $sts = ' AND `ORD`.payment_done_part_1 = 0';
                                        }
                                        if($_SESSION['status_payment_custom']=="-2"){
                                            $sts = ' AND `ORD`.payment_done_part_2 = 0';
                                        }
                                        if($_SESSION['status_payment_custom']=="-3"){
                                            $sts = ' AND `ORD`.payment_done_part_1 = 1';
                                        }
                                        if($_SESSION['status_payment_custom']=="-4"){
                                            $sts = ' AND `ORD`.payment_done_part_2 = 1';
                                        }
                                    $corders = $this->db->query("SELECT * FROM bank_payment_recipts AS BN, c_orders AS ORD WHERE `ORD`.id = `BN`.oID ".$sts."")->result_object();
                             } else {
                                $this->db->order_by("id","DESC");
                                if(isset($_SESSION['filter_web_custom']) && ($_SESSION['status_payment_custom']==1 || $_SESSION['status_payment_custom']==0)){
                                    $this->db->where("status",$_SESSION['status_payment_custom']);
                                }
                                $corders=$this->db->get("bank_payment_recipts")->result_object();
                            }
                            //echo $this->db->last_query();
                            foreach($corders as $order){

                                $this_cat = $this->db->where('id',$order->cat_id)
                                ->get('categories')
                                ->result_object()[0];



                                $corder = $this->db->where('id',$order->oID)
                                ->get('c_orders')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->uID)
                                ->get('users')
                                ->result_object()[0];

                            ?>
                            <tr>
                                <td>
                                    #<?php echo $corder->id;?>
                                </td>
                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
                                </td>
                                
                                <td> 
                                    <?php if($order->file_name!=""){?>
                                    <a href="<?php echo base_url();?>resources/uploads/orders/<?php echo $order->file_name;?>"  download>
                                    <img src="<?php echo base_url();?>resources/uploads/orders/<?php echo $order->file_name;?>" height="80">
                                    </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($corder->payment_done_part_1==1) { ?>
                                        <span class="btn btn-success btn-xs">Down Payment Arrived</span>
                                    <?php }else{ ?>
                                        <span class="btn btn-warning btn-xs">Down Payment Pending</span>
                                    <?php } ?>
                                    <br>
                                    <?php if($corder->payment_done_part_1==1){ if($corder->payment_done_part_2==1) { ?>
                                        <span class="btn btn-success btn-xs" style="margin-top: 10px;">Remaining Payment Arrived</span>
                                    <?php }else{ ?>
                                        <span class="" style="color: red;margin-top: 10px;">Remaining Payment Pending</span>
                                    <?php } } ?>

                                    

                                </td>
                               
                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($order->create_at));?>
                            	</td>
                                <td>
                                    <?php if($order->status == 0){?>
                                    <a href="<?php echo base_url();?>admin/corders/apprve_payment/<?php echo $order->task_id;?>/<?php echo $order->id;?>">
                                        <button type="button" class="btn btn-primary btn-sm">Approve</button>
                                    </a>
                                    <?php } else {echo "Approved";} ?>
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

<script type="text/javascript">
    function reset_form(){
        window.location.href = '<?php echo base_url()."admin/corders/reset_filter";?>';
    }
</script>