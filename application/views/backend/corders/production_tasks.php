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
                            <?php foreach($tasks as $task){
                               

                            ?>
                            <tr>
                                <td>
                                    #00OP<?php echo $task->id;?>
                                </td>

                              
                                <td>
                                    <?php echo $task->whg;?>
                                </td>
                               
                                <td>
                                    <?php 
                                    $options = json_decode($task->options);
                                    foreach($options as $option)
                                    {
                                        echo "<b>".$option->title.'</b>: '.$option->value;
                                        echo "<br>";
                                    }
                                    ?>
                                </td>

                               
                            	<td>  
                                    <?php  if($task->production_status == 0){?>
                                    <span style="color:orange;">Processing</span>
                                    <?php } if($task->production_status == 1){?>
                                    <span style="color:purple;">Prepairing</span>
                                    <?php } if($task->production_status == 2){?>
                                    <span style="color:red;">Ready to Deliver</span>
                                    <?php } if($task->production_status == 3){?>
                                        <span style="color:red;">Delivered</span>
                                    <?php } if($task->production_status == 4){?>
                                        <span style="color:green;">Completed</span>
                                    <?php } ?>
                            	</td>
                                

                               
                            	<td >
                            		<?php 
                                   
                                    echo date('d M, Y',strtotime($task->production_deadline));
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