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
                    <h4 class="card-title">Most Selling Products</h4>

                    

                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                  <th>Title</th>
                                  <th>Qty</th>
                                  <th>Gross Sale</th>
                                  <th>Discounts</th>
                                  <th>Total Value</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                  <th>Title</th>
                                  <th>Qty</th>
                                  <th>Gross Sale</th>
                                  <th>Discounts</th>
                                  <th>Total Value</th>
                                  
                                </tr>
                            </tfoot>
                            <tbody>

                                <?php


                    $most_selling_products = most_selling_products();
                    $only_ids = $most_selling_products["ids"];
                    foreach($only_ids as $key=>$only_id)
                    {
                        if($only_id==-1) continue;

                         $wherehre = where_region_id();
                            $this->db->where_in("region_id",explode(',', $wherehre));
                        $this->db->where("id",$only_id["order_id"]);
                        $orders = $this->db->get("orders")->result_object();

                        foreach($orders as $order)
                            $order_ids[] = $order->id;
                        if(empty($order_ids)) $order_ids[] = -1;

                        $prods = $this->db->where("product_id",$only_id["id"])->get("order_products")->result_object();




                        $qty = 0;

                        $net_sale = 0;

                        foreach($prods as $prod){ 

                            $net_sale += $prod->total;
                            $qty += $prod->qty;

                        }


                       

                        $product = $this->db->where("id",$only_id["id"])->get("products")->result_object()[0];;
                    

                        

                     ?>
                     <tr>
                        <td>
                            <?php echo $product->title; ?>
                        </td>
                        <td>
                            <?php echo $qty; ?>
                        </td>
                        <td>
                            <?php echo with_currency($net_sale); ?>
                        </td>
                        <td>
                            <?php echo with_currency(0); ?>
                        </td>

                         <td>
                            <?php echo with_currency($net_sale); ?>
                        </td>
                         
                     </tr>

                 <?php } ?>
                            
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