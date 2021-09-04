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
    <style type="text/css">
      .dt-buttons {display: none !important;}
    </style>
    <div class="row" style="margin-top: 50px;">
        <div class="col-12">
            <div class="filter_by">
                    <label>Filter By: </label>
                    <span>
                        <?php

                            $parent_cat = $this->db->query("SELECT title,id,parent,lparent FROM categories where parent = 0 AND lparent = 0")->result_object();
                            //echo $this->db->last_query();
                        ?>
                        <form action="<?php echo base_url();?>admin/reports/set_filter" method="post" style="display: flex;">
                            <select name="categorys" id="categorys" class="form-control" style="float: left;">
                                <option value="">-- Choose Category--</option>
                                <option value="-1" <?php if(!isset($_SESSION['ffilter_cats'])){echo "SELECTED";}?>> All Categories </option>
                                <?php 
                                        foreach ($parent_cat as $key => $parent) {
                                        $all_sub_cat = $this->db->query("SELECT title,id,parent,lparent FROM categories where parent = ".$parent->id." AND lparent = 0")->result_object();
                                    ?>
                                    <option value="<?php echo $parent->id;?>" 
                                        <?php if($parent->id == $_SESSION['ffilter_cats']) {echo "SELECTED";} ?> 
                                    >
                                       <?php echo $parent->title;?>
                                    </option>

                                    <?php foreach ($all_sub_cat as $key => $sub) {?>
                                        <option value="<?php echo $sub->id;?>" 
                                        <?php if($sub->id == $_SESSION['ffilter_cats']) {echo "SELECTED";} ?> 
                                    >
                                        &nbsp;&nbsp;&nbsp;&nbsp;-- <?php echo $sub->title;?>
                                    </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>

                            </select>
                            <button class="btn btn-primary" name="form_filter" type="submit" style="float: left;">Go</button>

                            <?php if(isset($_SESSION['ffilter_cats'])){?>
                                <button onclick="reset_form()" class="btn btn-secondary btn-sm" type="button" style="float: left;">Reset</button>
                            <?php } ?>
                        </form>
                    </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">(Custom Orders) Category Sale Report</h4>
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
                            

                            if(isset($_SESSION['ffilter_cats'])){
                                  //$subcat = -1;
                                  $subcat_list = $_SESSION["ffilter_cats"];
                                  $select_cat = $this->db->query("SELECT * FROM categories WHERE is_deleted = 0 AND status = 1 AND lparent = 0 AND id = ".$_SESSION['ffilter_cats'])->result_object();
                                  if($select_cat[0]->parent == 0){
                                        $select_sub = $this->db->query("SELECT * FROM categories WHERE is_deleted = 0 AND status = 1 AND lparent = 0 AND parent = ".$_SESSION['ffilter_cats'])->result_object();
                                          foreach ($select_sub as $key => $sub) {
                                            $subcat .= $sub->id.",";
                                          }
                                        $subcat_list = substr($subcat,0,-1);
                                  }
                                  
                                  
                                //echo $_SESSION['ffilter_cats'];
                                $orders = $this->db->query('SELECT *
                                            FROM c_orders 
                                            WHERE 
                                            cat_id IN ('.$subcat_list.')
                                            AND
                                            is_deleted  = 0'
                                          )->result_object();
                                // echo $this->db->last_query();
                            } else {
                            $orders = $this->db->query('
                                            SELECT *
                                            FROM c_orders 
                                            WHERE 
                                            is_deleted  = 0
                                            
                                            ORDER BY id DESC
                                            '
                                          )->result_object();
                            }
                            $total_shippings=0;
                            $total_taxes=0;
                            $total_values=0;
                            $push = array();
                            foreach($orders as $order){
                                  
                                  // $product = $this->db->where('id',$order->product_id)
                                  // ->get('products')
                                  // ->result_object()[0];

                                  // $customer = $this->db->where('id',$order->user_id)
                                  // ->get('users')
                                  // ->result_object()[0];
                                 
                                    $total_values += $order->all_total;
                                    $orders_count = count($orders);
                          
                                  $total_shippings += $order->shipping;
                                  $total_taxes += $order->tax;

                             }
                             $toral_value_price = $total_values + $total_shippings + $total_taxes;
                             ?>

                            <tr>
                                <td><?php echo $orders_count?$orders_count:0; ?></td>
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                
                                <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                                <td><?php echo number_format($total_shippings,2).  ' '.$order->currency; ?></td>
                                
                                <td><?php echo number_format($toral_value_price,2).  ' '.$order->currency; ?></td>

                                
                            </tr>
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
        window.location.href = '<?php echo base_url()."admin/reports/reset_filter";?>';
    }
</script>