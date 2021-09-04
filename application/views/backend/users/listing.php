<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Users Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-user">
                    <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                </a>
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
                    <h4 class="card-title">Users</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                   
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Orders</th>
                                    <th>Custom Orders</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Orders</th>
                                    <th>Custom Orders</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($users->result() as $user){?>
                            <tr>
                                <td>
                                    <?php echo $user->first_name.' '.$user->last_name;?>
                                </td>
                                <td>
                                    <?php echo $user->email;?>
                                </td>
                                <td>
                                    <?php echo $user->code.' '.$user->phone;?>
                                </td>
                               
                                <td>
                                    <?php if($user->status == 0){?>
                                        <a href="<?php echo $url.'admin/user-status/'.$user->id.'/'.$user->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                                    <?php }else{?>
                                        <a href="<?php echo $url.'admin/user-status/'.$user->id.'/'.$user->status;?>" ><span class="btn btn-success">Active</span></a>
                                    <?php } ?>
                                </td>


                                <td >
                                    <?php echo date('d M, Y, h:i A',strtotime($user->created_at));?>
                                </td>
                                <td>
                                    <a title="Orders" href="<?php echo $url."admin/orders?user_id=";?><?php echo $user->id;?>"><div class="btn btn-sm btn-primary"><i class="fa fa-tv"></i> (<?php
                                        $count = $this->db->where("user_id",$user->id)->count_all_results("orders");

                                        echo $count;

                                     ?>)</div></a>
                                </td>
                                <td>
                                    <a title="Orders" href="<?php echo $url."admin/c_orders?user_id=";?><?php echo $user->id;?>"><div class="btn btn-sm btn-primary"><i class="fa fa-tv"></i> (<?php
                                        $count_c = $this->db->where("user_id",$user->id)->count_all_results("c_orders");

                                        echo $count_c;

                                     ?>)</div></a>
                                </td>
                                <td>
                                    <a href="<?php echo $url;?>admin/edit-user/<?php echo $user->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-user/<?php echo $user->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>

                                    
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