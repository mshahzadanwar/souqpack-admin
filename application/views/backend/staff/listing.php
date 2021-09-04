<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
               
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Staff Management</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo ucfirst($type)."s"; ?></li>
                            </ol>
                            <a href="<?php echo $url;?>admin/add-staff?type=<?php echo $type; ?>">
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
                                <h4 class="card-title"><?php echo ucfirst($type)."s"; ?></h4>
                                
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Profile Picture</th>
                                                <th>Status</th>
                                                <th>Data & Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Profile Picture</th>
                                                <th>Status</th>
                                                <th>Data & Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php foreach($admins->result() as $client){

                                            
                                            ?>
                                        <tr>
                                            <td>
                                                <?php echo $client->name;?>
                                            </td>
                                           
                                            <td>
                                                <?php echo $client->email;?>
                                            </td>
                                            <td>
                                                <img src="<?php echo base_url()."resources/uploads/profiles/".$client->admin_profile_pic; ?>" style="width: 40px; border-radius: 50%; height: 40px">
                                            </td>
                                            
                                        	<td>
                                        		<?php if($client->status == 0){?>
                                                    <a href="<?php echo $url.'admin/staff-status/'.$client->id.'/'.$client->status;?>" ><span class="btn btn-danger btn-sm">Inactive</span></a>
                                        		<?php }else{?>
                                                    <a href="<?php echo $url.'admin/staff-status/'.$client->id.'/'.$client->status;?>" ><span class="btn btn-success  btn-sm">Active</span></a>
                                                <?php } ?>
                                        	</td>


                                        	<td >
                                        		<?php echo date('d M, Y, h:i A',strtotime($client->created_at));?>
                                        	</td>
                                            <td>
                                              

                                                  
                                                <a href="<?php echo $url;?>admin/edit-staff/<?php echo $client->id;?>?type=<?php echo $type; ?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                                <?php /* ?>
                                                <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-staff/<?php echo $client->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>
                                                <?php */ ?>
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