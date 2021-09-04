<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
               
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Admins Management</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Admins</li>
                            </ol>
                            <a href="<?php echo $url;?>admin/add-admin">
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
                                <h4 class="card-title">Admins</h4>
                                
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                               
                                                <th>Roles</th>
                                                <th>Status</th>
                                                <th>Data & Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                
                                                <th>Roles</th>
                                                <th>Status</th>
                                                <th>Data & Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php foreach($admins->result() as $client){

                                            $roles = $this->db->where('admin_id',$client->id)->get('admin_roles')->result_object();

                                            $is_super_admin = 0; 
                                            foreach($roles as $role)
                                                if($role->role==-1)
                                                {
                                                    $is_super_admin=1;
                                                    

                                                    break;
                                                }

                                            ?>
                                        <tr>
                                            <td>
                                                <?php echo $client->name;?>
                                            </td>
                                            <td>
                                                <?php echo $client->email;?>
                                            </td>
                                            <?php /* ?>
                                            <td>
                                                <?php 
                                                if($client->region_id == 0)
                                                {
                                                    echo '<span class="btn btn-success btn-sm" style="padding:0px 5px;">Admin (All Regions)</span>';
                                                }
                                                else
                                                {
                                                    $region = $this->db->where("id",$client->region_id)->get("regions")->result_object()[0];
                                                    echo '<span class="btn btn-success btn-sm" style="padding:0px 5px;">'.$region->title.'</span>';
                                                }

                                                 ?>
                                            </td>
                                            <?php */ ?>
                                            <td>
                                                <?php 
                                                if($is_super_admin == 1)
                                                {
                                                    $pre_text = $client->region_id!=0?"Region ":"";
                                                    echo '<span class="btn btn-success btn-sm" style="padding:0px 5px;">'.$pre_text.'Super Admin</span>';
                                                }
                                                else
                                                {
                                                    
                                                    foreach($roles as $key=>$role)
                                                    {
                                                        echo $key%3==0?"<br>":"";
                                                        echo '<a title="roles" href="'.$url.'admin/edit-admin-roles/'.$client->id.'"><span class="btn btn-warning m-l-5 m-t-5 " style="padding:0px 5px;">'.get_role_name($role->role).'</span></a>';
                                                    }
                                                }

                                                 ?>
                                            </td>
                                           
                                        	<td>
                                                 <?php if($client->id!=1){ ?>
                                        		<?php if($client->status == 0){?>
                                                    <a href="<?php echo $url.'admin/admin-status/'.$client->id.'/'.$client->status;?>" ><span class="btn btn-danger btn-sm">Inactive</span></a>
                                        		<?php }else{?>
                                                    <a href="<?php echo $url.'admin/admin-status/'.$client->id.'/'.$client->status;?>" ><span class="btn btn-success  btn-sm">Active</span></a>
                                                <?php } ?>
                                        		<?php } ?>
                                        	</td>


                                        	<td >
                                        		<?php echo date('d M, Y, h:i A',strtotime($client->created_at));?>
                                        	</td>
                                            <td>
                                                <?php if($client->id!=1){ ?>
                                                <?php if(check_role(-1) && $client->id!=$this->session->userdata("admin_id")){ ?>
                                                    <a title="roles" href="<?php echo $url;?>admin/edit-admin-roles/<?php echo $client->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-lock"></i></div></a>


                                                      <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-admin/<?php echo $client->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>

                                                <?php } ?>
                                                <a href="<?php echo $url;?>admin/edit-admin/<?php echo $client->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                              

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