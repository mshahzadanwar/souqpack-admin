<div class="container-fluid">
    <style type="text/css">
        .beef{
            float: left;width: 100%;flex-direction: row;display: flex;justify-content: space-between;
        }
        .beef div{
            padding: 5px 0px;
            margin-bottom: 5px;
        }
    </style>
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
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sales</h4>

                    <div class="beef">
                        <div>Gross Sales</div>
                        <div><?php
                            $wherehre = where_region_id();
                            $this->db->where_in("region_id",explode(',', $wherehre));
                           $this->db->where("is_deleted",0);
                            // $this->db->where("status >",3);
                            if($_GET["start_date"]!="")
                            $this->db->where("DATE(created_at) >=",$_GET["start_date"]);
                            if($_GET["end_date"]!="")
                            $this->db->where("DATE(created_at) <=",$_GET["end_date"]);
                            $this->db->order_by($field,$order);
                            $orders = $this->db->get("orders")->result_object();

                            $gross = 0;
                            $net = 0;
                            $shipping = 0;
                            $tax = 0;
                            $return = 0;
                            foreach($orders as $order){

                                if($order->status==7)
                                {
                                    $return += $order->total;
                                }
                                else
                                {
                                    $gross += $order->total;
                                    $shipping += $order->shipping_fee;
                                    $tax += $order->tax;
                                    $net += ($order->total) - ($order->tax+$order->shipping_fee);
                                }

                            }

                            echo with_currency(number_format($gross,2));

                        ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Discounts</div>
                        <div><?php echo with_currency(0); ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Returns</div>
                        <div><?php echo with_currency(number_format($return,2)); ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Net Sales</div>
                        <div><?php echo with_currency(number_format($net,2)); ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Shipping</div>
                        <div><?php echo with_currency(number_format($shipping,2)); ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Taxes</div>
                        <div><?php echo with_currency(number_format($tax,2)); ?></div>
                        
                    </div>
                    <div style="width: 100%; border-bottom: 1px solid #ccc;float: left;"></div>
                    <div class="beef">
                        <div>Total Sales</div>
                        <div><?php echo with_currency(number_format($gross,2)); ?></div>
                        
                    </div>
                
                </div>
            </div>
          
        </div>

        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gross Profits</h4>

                    <div class="beef">
                        <div>Net Sales</div>
                        <div><?php

                         

                            $cost = 0;
                            foreach($orders as $order){

                              

                                $order_products = $this->db->where("order_id",$order->id)->get("order_products")->result_object();

                                foreach($order_products as $order_product)
                                {
                                    $price_ob = $this->db->where("product_id",$order_product->product_id)->where("region_id",$order->region_id)->get("product_units")->result_object()[0];

                                    $cost += $price_ob->cost_price * $order_product->qty;
                                }

                            }

                            echo with_currency(number_format($net,2));

                        ?></div>
                        
                    </div>

                    <div class="beef">
                        <div>Cost Of Sold Goods</div>
                        <div><?php echo with_currency(number_format($cost,2)); ?></div>
                    </div>

                   
                    <div style="width: 100%; border-bottom: 1px solid #ccc;float: left;"></div>
                    <div class="beef">
                        <div>Gross Profits</div>
                        <div><?php echo with_currency(number_format($net-$cost,2)); ?></div>
                        
                    </div>
                
                </div>
            </div>
          
        </div>


        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payments</h4>

                    <div class="beef">
                        <div>Cash On Delivery</div>
                        <div><?php

                          
                            $cod = 0;
                            $ipay = 0;
                            
                            foreach($orders as $order){
                                if($order->payment_method==4)
                                    $cod += $order->total;
                                else
                                    $ipay += $order->total;

                                
                            }

                            echo with_currency(number_format($cod,2));

                        ?></div>
                        
                    </div>

                  
                    <div class="beef">
                        <div>PayFort</div>
                        <div><?php echo with_currency(number_format($ipay,2)); ?></div>
                        
                    </div>

                   

                    

                   
                    <div style="width: 100%; border-bottom: 1px solid #ccc;float: left;"></div>
                    <div class="beef">
                        <div>Total</div>
                        <div><?php echo with_currency(number_format($gross,2)); ?></div>
                        
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