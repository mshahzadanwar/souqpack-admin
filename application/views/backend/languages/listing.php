<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Language Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Language</li>
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
                    <h4 class="card-title">Language</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>View Type</th>
                                    <th>Flag</th>
                                    <!-- <th>Status</th> -->
                                    <th>Default</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>View Type</th>
                                    <th>Flag</th>
                                    <!-- <th>Status</th> -->
                                    <th>Default</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
								
								foreach($languages->result() as $language){?>
                            <tr>
                                <td>
                                    <?php echo $language->title;?>
                                </td>
                                <td>
                                    <?php echo $language->direction;?>
                                </td>
                                <td>
                                    <img style="width: 100px" src="<?php echo $url."resources/";?>uploads/languages/<?php echo $language->image;?>">
                                </td>
                               
                                <!-- <td>
                                    <?php if($language->status == 0){?>
                                        <a href="<?php echo $url.'admin/language-status/'.$language->id.'/'.$language->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                                    <?php }else{?>
                                        <a href="<?php echo $url.'admin/language-status/'.$language->id.'/'.$language->status;?>" ><span class="btn btn-success">Active</span></a>
                                    <?php } ?>
                                </td> -->
                                <td>
                                    <?php if($language->is_default == 0){?>
                                        <a href="#<?php //echo $url.'admin/language-default/'.$language->id.'/'.$language->is_default;?>" ><span class="btn btn-danger">No</span></a>
                                    <?php }else{?>
                                        <a href="#<?php //echo $url.'admin/language-default/'.$language->id.'/'.$language->is_default;?>" ><span class="btn btn-success">YES</span></a>
                                    <?php } ?>
                                </td>


                                <td>

                                    <a href="<?php echo $url;?>admin/edit-language/<?php echo $language->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                   
                                    
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