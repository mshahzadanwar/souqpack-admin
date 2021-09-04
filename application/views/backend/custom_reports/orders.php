<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row" style="margin-top: 50px;">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Custom Orders Report</h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                   
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
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                  
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                 
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Total Sales</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            $total_shippings=0;
                        $total_taxes=0;
                        $total_values=0;
                            foreach($orders->result() as $order){
                              
                                $product = $this->db->where('id',$order->product_id)
                                ->get('products')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->user_id)
                                ->get('users')
                                ->result_object()[0];

                                $total_values += $order->all_total;

                                $total_shippings += $order->shipping;
                                $total_taxes += $order->tax;

                             }

                             ?>

                            <tr>
                                
                                <td><?php echo count($orders->result()); ?></td>
                                <td><?php echo number_format($total_values,2).  ' SAR'; ?></td>
                                
                                <td><?php echo number_format($total_values,2).  ' SAR'; ?></td>
                                <td><?php echo number_format($total_shippings,2).  ' SAR'; ?></td>
                                <td><?php echo number_format($total_values+$total_shippings,2).  ' SAR'; ?></td>

                                
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive m-t-40">
                 
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                  <th>Order #</th>
                                  <th>Category</th>
                                  <th>Net Sale</th>
                                  <th>Shipping</th>
                                  <th>Total Value</th>
                                  <th>Customer</th>
                                  <th>Payment</th>
                                  <th>Status</th>
                                  <th>Data & Time</th>
                                  <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  <th>Order #</th>
                                  <th>Category</th>
                                  <th>Net Sale</th>
                                  <th>Shipping</th>
                                  <th>Total Value</th>
                                  <th>Customer</th>
                                  <th>Payment</th>
                                  <th>Status</th>
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
                                    <?php

                                    $products_order = $this->db->where('id',$order->cat_id)->get('categories')->result_object()[0];
                                        
                                            echo $products_order->title;
                                     ?>
                                </td>
                                
                                <td> 
                                    <?php 

                                    $tota_total = $order->all_total;

                                    $shipping=$order->shipping;
                                    $tax=$order->tax;

                                    $total_junk = $shipping+$tax;

                                    $net_sale = $tota_total - $total_junk;

                                     echo number_format($net_sale,2).' SAR '; ?>
                                </td>
                                
                                <td>
                                    <?php echo $order->shipping.' SAR'; ?>
                                </td>
                               
                                <td>
                                    <?php echo $order->all_total.'SAR '.$order->currency; ?>
                                </td>
                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
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

                                    <?php if($order->payment_method==4) echo "(COD)"; ?>
                                    <?php if($order->payment_method==1) echo "(PayPal)"; ?>
                                    <?php if($order->payment_method==3) echo "(PayFort)"; ?>

                                </td>
                                

                                <td>
                                    <?php echo admin_status($order->admin_status); ?>
                                </td>
                               

                               
                                <td >
                                    <?php echo date('d M, Y, h:i A',strtotime($order->created_at));?>
                                </td>
                                <td>

                                   

                                   <a href="<?php echo $url."admin/";?>view-c_order/<?php echo $order->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-tv"></i></div></a>
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