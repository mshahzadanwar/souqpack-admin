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
                    <h4 class="card-title">Customer - Custom Order Reports </h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                    <label for="recipient-name" class="control-label">Customer:</label>
                                    <select class="select2 m-b-10" name="customer_id" data-placeholder="Choose">
                                        <?php 
                                        $drivers = $this->db->where("is_guest",0)->where("is_deleted",0)->where("status",1)->get('users')->result_object();

                                        $wehere = where_region_id();
                                        foreach($drivers as $driver){

                                            $has_orders = $this->db->where_in("region_id",$wehere)->where("user_id",$driver->id)->count_all_results("orders");
                                            if($has_orders==0) continue;
                                            if($driver->first_name=="") continue;
                                            ?>
                                            <option
                                            <?php if($_GET["customer_id"]==$driver->id) echo 'selected'; ?>

                                             value="<?php echo $driver->id;?>"><?php echo $driver->first_name.' '.$driver->last_name;?></option>
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

                                    <?php if($_GET["start_date"]!="" || $_GET["customer_id"]!="" || $_GET["to_date"]!=""){ ?>
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
                    <?php /* ?>

                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                  <th>Average Order Value</th>
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                  <th>Discounts</th>
                                  <th>Returns</th>
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Tax</th>
                                  <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  <th>Average Order Value</th>
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                  <th>Discounts</th>
                                  <th>Returns</th>
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Tax</th>
                                  <th>Total Sales</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($orders->result() as $order){
$total_shippings=0;
                        $total_taxes=0;
                        $total_values=0;
                                $product = $this->db->where('id',$order->product_id)
                                ->get('products')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->user_id)
                                ->get('users')
                                ->result_object()[0];

                                $total_values += $order->total;

                                $total_shippings += $order->shipping_fee;
                                $total_taxes += $order->tax;

                             }

                             ?>

                            <tr>
                                <td><?php echo number_format($total_values/count($orders->result()),2); echo ' '.$order->currency; ?></td>
                                <td><?php echo count($orders->result()); ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                <td><?php echo '0 '.$order->currency; ?></td>
                                <td><?php echo '0 '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_shippings,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_taxes,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>

                                
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php */ ?>

                    <div class="table-responsive m-t-40">
                        <h6>Customer Orders</h6>
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th>Cusomter</th>
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Tax</th>
                                  <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Customer</th>
                                  <th>Orders</th>
                                  <th>Gross Sale</th>
                                  <th>Net Sales</th>
                                  <th>Shipping</th>
                                  <th>Tax</th>
                                  <th>Total Sales</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 

                            $total_shippings=0;
                                $total_taxes=0;
                                $total_values=0;

                            if($_GET["customer_id"]!="")
                              $this->db->where("id",$_GET["customer_id"]);

                            $drivers = $this->db->where("is_guest",0)->where("is_deleted",0)->where("status",1)->get('users')->result_object();

                             foreach($drivers as $driver){

                            $has_orders = $this->db->where("user_id",$driver->id)->count_all_results("orders");
                            if($has_orders==0) continue;
                            if($driver->first_name=="") continue;



                            $this->db->where("user_id",$driver->id);

                            if($_GET["start_date"]!="")
                              $this->db->where("DATE(created_at) >=",$_GET["start_date"]);

                            if($_GET["to_date"]!="")
                              $this->db->where("DATE(created_at) <=",$_GET["to_date"]);

                            $orders = $this->db->get("c_orders");

                            foreach($orders->result() as $order){


                              // if($_GET["start_date"]!="")
                              // {
                              //     if($order->created_at<$_GET["start_date"]) continue;
                              // }
                              // if($_GET["to_date"]!="")
                              // {
                              //     if($order->created_at>$_GET["to_date"]) continue;
                              // }



                                
                                $product = $this->db->where('id',$order->product_id)
                                ->get('products')
                                ->result_object()[0];

                               
                                $total_values += $order->all_total;

                                $total_shippings += $order->shipping;
                                $total_taxes += $order->tax;

                             }

                             if($total_values==0) continue;


                            ?>

                            <tr>
                                <td><?php echo $order->anonymous==1?"anonymous":('#'.$driver->id.', '.$driver->code.' '.$driver->phone . ', ' .$driver->first_name. ' '.$driver->last_name. ', '.$driver->email);?></td>
                                
                                <td><?php echo count($orders->result()); ?></td>
                                <td><?php echo number_format($total_values,2).  ' SAR '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values,2).  ' SAR  '.$order->currency; ?></td>
                                <td><?php echo number_format($total_shippings,2).  ' SAR  '.$order->currency; ?></td>
                                <td><?php echo number_format($total_taxes,2).  '  SAR '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values+$total_shippings,2).  ' SAR  '.$order->currency; ?></td>

                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php /* ?>
                    
                    <div class="table-responsive m-t-40">
                        <h6>Selected Filter</h6>

                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                  <th>Order #</th>
                                  <th>Product(s)</th>
                                  <th>Net Sale</th>
                                  <th>Tax</th>
                                  <th>Shipping</th>
                                  <th>Discount</th>
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
                                  <th>Product(s)</th>
                                  <th>Net Sale</th>
                                  <th>Tax</th>
                                  <th>Shipping</th>
                                  <th>Discount</th>
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
                                    #00P<?php echo $order->id;?>
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
                                            echo $product->title.', ';
                                     ?>
                                </td>
                                
                                <td> 
                                    <?php 

                                    $tota_total = $order->total;

                                    $shipping=$order->shipping_fee;
                                    $tax=$order->tax;

                                    $total_junk = $shipping+$tax;

                                    $net_sale = $tota_total - $total_junk;

                                     echo $net_sale.' '.$order->currency; ?>
                                </td>
                                <td>
                                    <?php echo $order->tax.' '.$order->currency; ?>
                                </td>
                                <td>
                                    <?php echo $order->shipping_fee.' '.$order->currency; ?>
                                </td>
                                <td>
                                    0
                                </td>
                                <td>
                                    <?php echo $order->total.' '.$order->currency; ?>
                                </td>
                                <td>
                                    <?php echo $order->anonymous==1?"anonymous":('#'.$customer->id.', '.$customer->code.' '.$customer->phone . ', ' .$customer->first_name. ' '.$customer->last_name. ', '.$customer->email);?>
                                </td>
                                <td>
                                    <?php if($order->payment_done==1) { ?>
                                        <span class="btn btn-success btn-xs">Done</span>
                                    <?php }else{ ?>
                                        <span class="btn btn-warning btn-xs">Pending</span>
                                    <?php } ?>

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
                                    (Shipped)
                                    <?php } if($order->status == 5){?>
                                    (Review)
                                    <?php } if($order->status == 6){?>
                                    (Completed)
                                    <?php } if($order->status == 7){?>
                                    (Cancelled)
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
                    <?php */ ?>
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