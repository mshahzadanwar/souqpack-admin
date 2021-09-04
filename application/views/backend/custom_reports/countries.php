<?php $junks = $orders; ?>
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
                    <h4 class="card-title">Country - Custom Order Reports</h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                    <label for="recipient-name" class="control-label">Country:</label>
                                    <select class="select2 m-b-10" name="country" data-placeholder="Choose">
                                        <option value="">Choose</option>
                                        <?php 
                                        $countries = array();
                                        foreach($orders->result() as $order)
                                        {
                                            $json = json_decode($order->address_text);

                                            if(!in_array(strtoupper($json->country), $countries))
                                                $countries[] = strtoupper($json->country);
                                        }
                                        
                                        foreach($countries as $driver){?>
                                            <option
                                            <?php if(urldecode($_GET["country"])==$driver) echo 'selected'; ?>

                                             value="<?php echo $driver;?>"><?php echo $driver; ?></option>
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

                                    <?php if($_GET["start_date"]!="" || $_GET["to_date"]!="" || $_GET["country"]!=""){ ?>
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
                            <?php
                            $orders = $orders->result();
                            if(isset($_GET["country"]))
                            {
                                foreach($orders as $key=>$order)
                                {
                                    $json = json_decode($order->address_text);

                                    if(strtoupper($json->country)!=$_GET["country"])
                                        unset($orders[$key]);
                                }
                            }

                             foreach($orders as $order){
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
                                <td><?php echo number_format($total_values/count($orders),2); echo ' '.$order->currency; ?></td>
                                <td><?php echo count($orders); ?></td>
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
                        <h6>Country Reports</h6>
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th>Country</th>
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
                                    <th>Country</th>
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
                            <?php
                            

                            
                             foreach($countries as $country)
                             {


                                if($_GET["country"]!="" && $_GET["country"]!=$country) continue;

                                $junkss = $junks->result();
                                
                                foreach($junkss as $key=>$order)
                                {
                                    $json = json_decode($order->address_text);

                                    if(strtoupper($json->country)!=$country)
                                    {

                                        unset($junkss[$key]);
                                    }
                                }

                                 foreach($junkss as $order){
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
                                <td><?php echo $country; ?></td>
                                <td><?php echo number_format($total_values/count($junkss),2); echo ' '.$order->currency; ?></td>
                                <td><?php echo count($junkss); ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                <td><?php echo '0 '.$order->currency; ?></td>
                                <td><?php echo '0 '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_shippings,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_taxes,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>

                                
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
                            <?php foreach($orders as $order){

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