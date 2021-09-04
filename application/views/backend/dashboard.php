
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Dashboard</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
               <!--  <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button> -->
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Info box -->
    <!-- ============================================================== -->
    <?php if(!is_region()){ ?>
    <div class="card-group">
        <div class="card">

            <div class="card-body">
              <a href="<?php echo base_url();?>admin/users">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-screen-desktop"></i></h3>
                                <p class="text-muted">Customers</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-primary"><?php echo $result = $this->db->query('
            SELECT *
            FROM users
            WHERE is_deleted  = 0
            AND (email != "" OR phone != "")'
        )->num_rows(); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
              </a>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
      
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
              <a href="<?php echo base_url();?>admin/products">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-doc"></i></h3>
                                <p class="text-muted">Products</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-purple"><?php 
                                    echo total_products();
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
              </a>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
              <a href="<?php echo base_url();?>admin/orders">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-bag"></i></h3>
                                <p class="text-muted">Orders</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-success"><?php 
                                    echo total_orders();
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
              </a>
            </div>
        </div>

        <!-- Column -->
        <div class="card">
            <div class="card-body">
              <a href="<?php echo base_url();?>admin/c_orders">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-bag"></i></h3>
                                <p class="text-muted">Custom Orders</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-success"><?php 
                                $cust_ordes = $this->db->query("SELECT * FROM c_orders")->num_rows();
                                    echo $cust_ordes;
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-green" role="progressbar" style="width: 100%;background-color: #9100b5; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" ></div>
                        </div>
                    </div>
                </div>
              </a>
            </div>
        </div>
    </div>
    <style type="text/css">
      .bg-purple {
        background: #9b00c2
      }
    </style>
<?php } ?>
 <div class="row" style="margin-top: 10px;margin-bottom: 10px;">
        <div class="col-md-12">
             <div id="chart_div2"></div>
        </div>
    </div>
     <div class="row" style="margin-top: 10px;margin-bottom: 10px;">
        <div class="col-md-12">
             <div id="categories_order"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
             <div id="chart_divdd"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
             <div id="chart_div"></div>
        </div>
    </div>

   


   

    <div class="row" style="margin-top: 0px;margin-bottom: 10px;">
        <div class="col-md-12">
             <div id="chart_div4"></div>
        </div>
    </div>


   <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gross Sales Report</h4>
            <div class="table-responsive m-t-40">
                <table id="example23" class="display example23_table nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>

                        <tr>
                          <th>Date</th>
                          <th>Orders</th>
                          <th>Gross Sale</th>
                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th>Date</th>

                          <th>Orders</th>
                          <th>Gross Sale</th>
                          
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php
                      $i = 29;
                      while($i>=0)
                      {
                        $day_string = $i<2?"Day":"Days";
                        $date = date("Y-m-d",strtotime("-".$i." ".$day_string));
                        $orders = $this->db->where("DATE(created_at)",$date)->get("orders");
                        $i--;
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

                        $total_values += $order->total;

                        $total_shippings += $order->shipping_fee;
                        $total_taxes += $order->tax;

                     }
                     if(count($orders->result()) > 0){
                     ?>
                        <tr>
                            <td><?php echo date("d F, Y",strtotime($date)); ?></td>
                            <td><?php echo count($orders->result()); ?></td>
                            <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                        </tr>
                <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gross Sales Report (Custom Orders)</h4>
            <div class="table-responsive m-t-40">
                <table id="example23" class="display example23_table nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>

                        <tr>
                          <th>Date</th>
                          <th>Orders</th>
                          <th>Gross Sale</th>
                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th>Date</th>

                          <th>Orders</th>
                          <th>Gross Sale</th>
                          
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php
                      $i = 29;
                      while($i>=0)
                      {
                        $day_string = $i<2?"Day":"Days";
                        $date = date("Y-m-d",strtotime("-".$i." ".$day_string));
                        $orders = $this->db->where("DATE(created_at)",$date)->get("c_orders");
                        $i--;
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

                        $total_shippings += $order->shipping_fee;
                        $total_taxes += $order->tax;

                     }
                     if(count($orders->result()) > 0){
                     ?>
                        <tr>
                            <td><?php echo date("d F, Y",strtotime($date)); ?></td>
                            <td><?php echo count($orders->result()); ?></td>
                            <td><?php echo number_format($total_values,2).  ' '.$order->currency; ?></td>
                        </tr>
                <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

</div>
</div>



    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
$most_selling_products = most_selling_products();

$most_selling_products_bars = most_selling_products();
 ?>

 <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartddd);
        google.charts.setOnLoadCallback(drawChart_live);
        google.charts.setOnLoadCallback(categories_orders);

        function drawChart_live() {
        //   var data = google.visualization.arrayToDataTable([
        //   ['Year', 'Sales', 'Expenses', 'Profit'],
        //   ['2014', 1000, 400, 200],
        //   ['2015', 1170, 460, 250],
        //   ['2016', 660, 1120, 300],
        //   ['2017', 1030, 540, 350]
        // ]);

            var data = google.visualization.arrayToDataTable([
          ['Day', 'Gross Sale (<?php echo get_currency(); ?>)', 'Custom Orders (<?php echo get_currency(); ?>)'],
          <?php
          $i = 29;
          while($i>=0)
          {
            $day_string = $i<2?"Day":"Days";
            $date = date("Y-m-d",strtotime("-".$i." ".$day_string));
            $return = 0;
            $return_c = 0;

            $this->db->where("DATE(created_at)",$date);
            $all_orders = $this->db->get("orders")->result_object();
            foreach($all_orders as $one_order)
            {
                $return += $one_order->total;
            }

            $this->db->where("DATE(created_at)",$date);
            $all_orders_C = $this->db->get("c_orders")->result_object();
            foreach($all_orders_C as $one_orderc)
            {
                $return_c += $one_orderc->total;
            }

            echo "['".$date."', ".$return.",".$return_c."],";
            $i--;
          }

           ?>
        ]);
        //   var data = google.visualization.arrayToDataTable([
        //   ['Day', 'Gross Sale (<?php //echo get_currency(); ?>)'],
        //   <?php
        //   $i = 29;
        //   while($i>=0)
        //   {
        //     $day_string = $i<2?"Day":"Days";
        //     $date = date("Y-m-d",strtotime("-".$i." ".$day_string));
        //     $return = 0;

        //         $this->db->where("DATE(created_at)",$date);
        //         $all_orders = $this->db->get("orders")->result_object();
        //         foreach($all_orders as $one_order)
        //         {
        //             $return += $one_order->total;
        //         }
        //     echo "['".$date."', ".$return."],";
        //     $i--;
        //   }

        //    ?>
        // ]);

          var options = {
            title: "SALES SUMMARY REPORT",
            bar: {groupWidth: "105%"},
            height: 400,
            legend: { position: "none" },
          };
          var chart = new google.visualization.ColumnChart(document.getElementById("chart_div2"));
          chart.draw(data, options);
      }

    
      // BEST SELLING PRODUCTS
      <?php
        $pro_lis = $this->db->query("SELECT * FROM products WHERE status = 1 AND lparent = 0")->result_object();
        foreach ($pro_lis as $key => $pro_val) {
        $orders_most= $this->db->query('SELECT  *, OP.price AS op_Pp
                                        FROM orders AS O,
                                        `order_products` AS OP,
                                        `products` AS PR
                                        WHERE O.id = OP.order_id
                                        AND OP.product_id = PR.id
                                        AND PR.id IN ('.$pro_val->id.')
                                        AND O.is_deleted  = 0
                                        AND O.status != 7
                                        ORDER BY O.id DESC'
                                      )->result_object();

        if(count($orders_most) > 0){
            $count_mst_products = $this->db->query("SELECT SUM(qty) AS SUMCOUNT FROM order_products WHERE product_id = ".$pro_val->id)->result_object()[0];
            $rand_colors_ms = mt_rand(1, 999999);
            $rand_colors_ms = "#".$rand_colors_ms;   
            $charts_data_ms .= '['.'"'.$pro_val->title.'"'.', '.$count_mst_products->SUMCOUNT.', "'.$rand_colors_ms.'"],';
        }
        // /
        }
         $charts_data_ms = substr($charts_data_ms, 0,-1);
         $charts_data_ms = $charts_data_ms?$charts_data_ms:"['',0,'#ccc']";
        ?>
    function drawChartddd() {
      var data2 = google.visualization.arrayToDataTable([
        ["Category", "Qty", { role: "style" } ],
        <?php echo $charts_data_ms;?>
      ]);

      var view = new google.visualization.DataView(data2);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "MOST SELLING PRODUCTS",
        bar: {groupWidth: "90%"},
        height: 350,
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_divdd"));
      chart.draw(view, options);
  }

      // CATEGORIES WISE REPORTS
      <?php
        $categories_lis = $this->db->query("SELECT * FROM categories WHERE status = 1 AND lparent = 0")->result_object();
        foreach ($categories_lis as $key => $cat_val) {
        $orders_cart= $this->db->query('SELECT  *, OP.price AS op_Pp
                                        FROM orders AS O,
                                        `order_products` AS OP,
                                        `products` AS PR
                                        WHERE O.id = OP.order_id
                                        AND OP.product_id = PR.id
                                        AND PR.category IN ('.$cat_val->id.')
                                        AND O.is_deleted  = 0
                                        AND O.status != 7
                                        ORDER BY O.id DESC'
                                      )->result_object();

          if(count($orders_cart) > 0){
              $rand_colors = mt_rand(1, 999999);
              $rand_colors = "#".$rand_colors;   
              $charts_data .= '['.'"'.$cat_val->title.'"'.', '.count($orders_cart).', "'.$rand_colors.'"],';
          }
          //echo "bilal".$charts_data;
        // else {
        //   $charts_data = "['None',0,'#ccc'],";
        // }
        }
        $charts_data = substr($charts_data, 0,-1);
        $charts_data = $charts_data?$charts_data:"['',0,'#ccc']";
        ?>
    function categories_orders() {
      var data = google.visualization.arrayToDataTable([
        ["Category", "Ord", { role: "style" } ],
        <?php echo $charts_data;?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "CATEGORIES ORDERS",
        bar: {groupWidth: "90%"},
        height: 350,
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("categories_order"));
      chart.draw(view, options);
  }
  </script>
  <style type="text/css">
      #chart_divdd text,#chart_div2 text,#categories_order text {
        /*text-transform: uppercase;;*/
      }
  </style>