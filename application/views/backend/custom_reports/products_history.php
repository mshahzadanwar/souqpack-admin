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
                    <h4 class="card-title">Products Report</h4>

                    <div class="col-12">
                        <div class="right " style="float: right;">
                            <form>
                                <div style="flex-direction: row;">
                                    
                                    <label for="recipient-name" class="control-label">Product:</label>
                                    <select class="select2 m-b-10" name="product_id" data-placeholder="Choose">
                                        <?php 
                                        $wehere = where_region_id();

       
        
                            $this->db->where("products.lparent",0);
                            $this->db->where("products.is_deleted",0);
                            $this->db->select("products.*");
                            $this->db->order_by("products.id","DESC");

                            $this->db->from("products");

                            $this->db->join("product_units","products.id=product_units.product_id","LEFT");
                            $this->db->where_in("product_units.region_id",explode(",",$wehere));
                            $this->db->select("product_units.id as no_use");


                            $products = $this->db->get();


                                        
                                        foreach($products as $product){

                                            $has_orders = $this->db->where("product_id",$product->id)->count_all_results("order_products");
                                            if($has_orders==0) continue;
                                           
                                            ?>
                                            <option
                                            <?php if($_GET["product_id"]==$product->id) echo 'selected'; ?>

                                             value="<?php echo $product->id;?>"><?php echo $product->title; ?></option>
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

                                    <?php if($_GET["start_date"]!="" || $_GET["end_date"]!="" || $_GET["product_id"]!=""){ ?>
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
                                  <th>Product</th>
                                  <th>Gross Sales</th>
                                  <th>Discount</th>
                                  <th>Returns</th>
                                  <th>Net Sales</th>
                                  <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  <th>Product</th>
                                  <th>Gross Sales</th>
                                  <th>Discount</th>
                                  <th>Returns</th>
                                  <th>Net Sales</th>
                                  <th>Total Sales</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            
                            $this->db->select("products.*");
                            $this->db->where("products.lparent",0);
                            $this->db->where("products.is_deleted",0);
                            if($_GET["product_id"]!="")
                                $this->db->where("products.id",$_GET["product_id"]);
                            $this->db->from("products");


                            $this->db->join("order_products","order_products.product_id=products.id","LEFT");
                            $this->db->select("order_products.id as no1_id");
                            // $this->db->select("SUM(order_products.total) as no4_id");

                            $this->db->join("orders","orders.id=order_products.order_id","LEFT");
                            $this->db->select("orders.id as no_id");

                           
                            if($_GET["start_date"]!="")
                                $this->db->where("DATE(orders.created_at) >=",$_GET["start_date"]);
                            if($_GET["end_date"])
                                $this->db->where("DATE(orders.created_at) <=",$_GET["end_date"]);

                            $this->db->group_by("SUM(order_products.total)","DESC");
                            $products = $this->db->get();

                            // $products = $this->db->query("SELECT * FROM products WHERE is_deleted = 0 AND lparent = 0");

                            $sgross = 0;
                            $sdiscounts = 0;
                            $sreturns = 0;
                            $sshipping = 0;
                            $stax = 0;
                            $stotal = 0;

                            


                            foreach($products->result() as $product){

                                $order_products = $this->db->where("product_id",$product->id)->get("order_products")->result_object();
                                if(count($order_products)==0) continue;
                                $discounts = 0;
                                $gross = 0;
                                $returns = 0;
                                $shipping = 0;
                                $tax = 0;
                                $total = 0;
                                foreach($order_products as $order_product)
                                {
                                    $order_this = $this->db->where("id",$order_product->order_id)->get("orders")->result_object()[0];
                                    $count_order_products = $this->db->where("order_id",$order_this->id)->count_all_results("order_products");

                                    


                                    $same_prods_of_order_this = $this->db->where("product_id",$product->id)->where("order_id",$order_this->id)->get("order_products")->result_object();

                                    $prod_order_this_c_total = 0;
                                    foreach($same_prods_of_order_this as $same_prod_of_order_this) $prod_order_this_c_total += $same_prod_of_order_this->total;

                                    

                                    if($order_this->status==7)
                                    {
                                        
                                        $gross -= $prod_order_this_c_total;
                                        $shipping -= $order_this->shipping_fee / $count_order_products;
                                        $tax -= $order_this->shipping_fee / $count_order_products;
                                        $returns -= 0;
                                        $total -= $order_this->total;
                                    }
                                    else
                                    {
                                        $gross += $prod_order_this_c_total;
                                        $shipping += $order_this->shipping_fee / $count_order_products;
                                        $tax += $order_this->shipping_fee / $count_order_products;
                                        $returns += 0;
                                        $total += $order_this->total;
                                    }
                                }
                                $sgross += $gross;
                                $sreturns += $returns;
                                $sshipping += $shipping;
                                $stax += $tax;
                                $stotal += $total;

                                $to_print[] = array(
                                    "title"=>$product->id."--".$product->title,
                                    "gross"=>number_format($gross,2),
                                    "discounts"=>number_format(0),
                                    "returns"=>number_format($returns,2),
                                    "net"=>number_format(abs($gross-($shipping+$tax)),2),
                                    "total"=>$gross
                                );

                            }

                            $price = array_column($to_print, 'total');

                            array_multisort($price, SORT_DESC, $to_print);

                            foreach($to_print as $to_p){
                            ?>
                            <tr>
                                
                                
                                <td>
                                    <?php echo $to_p["title"];?>
                                </td>
                                
                               
                               
                                <td>
                                    <?php echo $to_p["gross"]; ?>
                                </td>

                                <td>
                                    <?php echo $to_p["discounts"]; ?>
                                </td>
                                <td>
                                    <?php echo $to_p["returns"]; ?>
                                </td>

                                <td>
                                    <?php echo $to_p["net"]; ?>
                                </td>
                               <td>
                                    <?php echo number_format($to_p["total"],2); ?>
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