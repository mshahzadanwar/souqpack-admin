

<?php
$today_date = date("Y-m-d H:i:s");
$pending_tasks = $this->db->where("designer_id",$this->session->userdata("admin_id"))->where_in("status",array(0,3))->count_all_results("designer_tasks");
$late_tasks = $this->db->where("designer_id",$this->session->userdata("admin_id"))->where("deadline <",$today_date)->where_in("status",array(0,3))->count_all_results("designer_tasks");
$completed_tasks = $this->db->where("designer_id",$this->session->userdata("admin_id"))->where_in("status",array(2,4))->count_all_results("designer_tasks");
 ?>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-screen-desktop"></i></h3>
                                <p class="text-muted">Pending Tasks</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-primary"><?php echo $pending_tasks; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
      
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-doc"></i></h3>
                                <p class="text-muted">Accepted Tasks</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-success"><?php 
                                    echo $completed_tasks;
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
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="icon-bag"></i></h3>
                                <p class="text-muted">Late Tasks</p>
                            </div>
                            <div class="ml-auto">
                                <h2 class="counter text-danger"><?php 
                                    echo $late_tasks;
                                ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

  

<?php 


$tasks = $this->db->where("designer_id",$this->session->userdata("admin_id"))->where("DATE(deadline) <",$today_date)->where_in("status",array(0,3))->get("designer_tasks")->result_object();
if(!empty($tasks)){

?>


<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Late Tasks</h4>

                  
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Task ID</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Task ID</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            
                            foreach($tasks as $task){
                                $order = $this->db->where("id",$task->order_id)->get("c_orders")->result_object()[0];

                                $this_cat = $this->db->where('id',$order->cat_id)
                                ->get('categories')
                                ->result_object()[0];

                                $p_cat = $this->db->where('id',$this_cat->parent)
                                ->get('categories')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->user_id)
                                ->get('users')
                                ->result_object()[0];

                            ?>
                            <tr>
                                <td>
                                    #00TS<?php echo $task->id;?>
                                </td>

                              
                                <td>
                                    <?php echo $order->whg;?>
                                </td>
                               
                                <td>
                                    <?php 
                                    $options = json_decode($order->options);
                                    foreach($options as $option)
                                    {
                                        echo "<b>".$option->title.'</b>: '.$option->value;
                                        echo "<br>";
                                    }
                                    ?>
                                </td>

                               
                              


                               

                               
                               
                                <td>  
                                    <?php  if($task->status == 0){?>
                                    <span style="color:orange;">Pending</span>
                                    <?php } if($task->status == 1){?>
                                    <span style="color:purple;">Delivered</span>
                                    <?php } if($task->status == 2){?>
                                    <span style="color:red;">Rejected</span>
                                    <?php } if($task->status == 3){?>
                                        <span style="color:red;">Revision</span>
                                    <?php } if($task->status == 4){?>
                                        <span style="color:green;">Completed</span>
                                    <?php } ?>
                                </td>
                                

                               
                                <td >
                                    <?php echo date('d M, Y',strtotime($task->deadline));?>
                                </td>
                                <td>


                                    <a href="<?php echo $url."admin/";?>view-task/<?php echo $task->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-tv"></i></div></a>

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

<?php } ?>



<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Tasks</h4>

                  
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Task ID</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Task ID</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                            $tasks = $this->db->where("designer_id",$this->session->userdata("admin_id"))->where_in("status",array(0,3))->get("designer_tasks")->result_object();
                            foreach($tasks as $task){
                                $order = $this->db->where("id",$task->order_id)->get("c_orders")->result_object()[0];

                                $this_cat = $this->db->where('id',$order->cat_id)
                                ->get('categories')
                                ->result_object()[0];

                                $p_cat = $this->db->where('id',$this_cat->parent)
                                ->get('categories')
                                ->result_object()[0];

                                $customer = $this->db->where('id',$order->user_id)
                                ->get('users')
                                ->result_object()[0];

                            ?>
                            <tr>
                                <td>
                                    #00TS<?php echo $task->id;?>
                                </td>

                              
                                <td>
                                    <?php echo $order->whg;?>
                                </td>
                               
                                <td>
                                    <?php 
                                    $options = json_decode($order->options);
                                    foreach($options as $option)
                                    {
                                        echo "<b>".$option->title.'</b>: '.$option->value;
                                        echo "<br>";
                                    }
                                    ?>
                                </td>

                               
                              


                               

                               
                               
                                <td>  
                                    <?php  if($task->status == 0){?>
                                    <span style="color:orange;">Pending</span>
                                    <?php } if($task->status == 1){?>
                                    <span style="color:purple;">Delivered</span>
                                    <?php } if($task->status == 2){?>
                                    <span style="color:red;">Rejected</span>
                                    <?php } if($task->status == 3){?>
                                        <span style="color:red;">Revision</span>
                                    <?php } if($task->status == 4){?>
                                        <span style="color:green;">Completed</span>
                                    <?php } ?>
                                </td>
                                

                               
                                <td >
                                    <?php echo date('d M, Y, h:i A',strtotime($task->created_at));?>
                                </td>
                                <td>


                                    <a href="<?php echo $url."admin/";?>view-task/<?php echo $task->id;?>"><div class="btn btn-info btn-circle"><i class="fa fa-tv"></i></div></a>

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
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
