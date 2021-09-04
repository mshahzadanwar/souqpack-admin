<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Admins Management</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $url;?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Admin Detail</li>
                        </ol>
                    </div>
 
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
               <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Admin Detail</h4>
                                <ul class="nav nav-pills m-t-30 justify-content-end m-b-30">
                                    <li class=" nav-item"> <a href="#navpills2-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Personal Info</a> </li>
                                   
                                </ul>
                                <div class="tab-content br-n pn">
                                    <div id="navpills2-1" class="tab-pane active">
                                        <div class="row col-md-12">
											<table id="example23" class="display nowrap table table-hover table-bordered" cellspacing="0" width="100%">
											<tr>
												<td>Name</td>
												<td><?php echo $client_detail->name;?></td>
											</tr>
											
											
											<tr>
												<td>Email</td>
												<td><?php echo $client_detail->email;?></td> 
											</tr>
											
											</table>
                                        </div>
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