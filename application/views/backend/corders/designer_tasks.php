<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">My Tasks Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Tasks</li>
                </ol>
              
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
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
                                    <th>Order ID</th>
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
                                    <th>Order ID</th>
                                    <th>Size (W*H*G)</th>
                                    <th>Options</th>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($tasks as $task){
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
                                    #00CP<?php echo $task->order_id;?>
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
                            		<?php 
                                    if(is_designer())
                                    echo date('d M, Y',strtotime($task->deadline));
                                    else
                                    echo date('d M, Y',strtotime($order->production_deadline));
                                    ?>
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