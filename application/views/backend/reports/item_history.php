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
                    <h4 class="card-title">Item History Report</h4>


                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                 
                                  <th>Item Name</th>
                                  <th>Total Stock</th>
                                  <th>Qty Sold</th>
                                  <th>Qty in stock</th>
                                  <th>Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  
                                  <th>Item Name</th>
                                  <th>Total Stock</th>
                                   <th>Qty Sold</th>
                                  <th>Qty in stock</th>
                                  <th>Status</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            //$wehere = where_region_id();

                            $this->db->where("products.lparent",0);
                            $this->db->where("products.is_deleted",0);
                            $this->db->select("products.*");
                            $this->db->order_by("products.id","DESC");

                            $this->db->from("products");

                            $this->db->join("product_units","products.id=product_units.product_id","LEFT");
                            //$this->db->where_in("product_units.region_id",explode(",",$wehere));
                            $this->db->select("product_units.id as no_use");


                            $orders = $this->db->get();
                            // $orders = $this->db->query("SELECT * FROM products WHERE is_deleted = 0 AND lparent = 0");
                            foreach($orders->result() as $order){

                                $completed_orders = $this->db->query("SELECT SUM(op.qty) AS sold_qty, op.id FROM order_products AS op JOIN orders AS o ON op.order_id = o.id WHERE op.product_id = ".$order->id." AND o.status > 1")->result_object()[0];
                               
                                
                                $purchases = $this->db->where('title',$order->title)
                                ->where("lparent",0)
                                ->where("status",1)
                                ->get('purchases')
                                ->result_object()[0];

                                //echo $this->db->last_query();

                               
                            ?>
                            <tr>
                                
                                
                                <td>
                                    <?php echo $order->title;?>
                                </td>
                                
                                <td> 
                                    <?php echo $purchases->avl_qty;?>
                                </td>
                                <td>
                                    <?php echo $completed_orders->sold_qty; ?>
                                </td>

                                <td>
                                    <?php echo $purchases->avl_qty-$completed_orders->sold_qty; ?>
                                </td>
                               
                               

                               
                                <td >
                                   <?php if($order->status == 0){?>
                                        <a href="<?php echo $url.'admin/product-status/'.$order->id.'/'.$order->status;?>?type=purc" ><span class="btn btn-danger">Inactive</span></a>
                                    <?php }else{?>
                                        <a href="<?php echo $url.'admin/product-status/'.$order->id.'/'.$order->status;?>?type=purc" ><span class="btn btn-success">Active</span></a>
                                    <?php } ?>
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