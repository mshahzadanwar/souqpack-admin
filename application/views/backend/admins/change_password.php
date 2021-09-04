
<style>
    .dropify-wrapper .dropify-message p{
        text-align: center;
    }
</style>
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Admins Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?=$url;?>admin/admins">Admins</a></li>
                <li class="breadcrumb-item active">Change Password</li>
            </ol>
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
            <?=form_open_multipart('',array('class'=>'m-t-40','novalidate'=>""));?>
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Change Password</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('opassword') !='')?'error':'';?>">
                                <h5>Old Password : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="password" name="opassword" class="form-control" required data-validation-required-message="This field is required" placeholder="" value="<?php if(set_value('opassword') != ''){ echo set_value('opassword');}else{ echo $data->name;}?>" >
                                    <div class="text-danger"><?php echo form_error('opassword');?></div>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('password') !='')?'error':'';?>">
                                <h5>New Password : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="password" name="password" class="form-control" required data-validation-required-message="This field is required" placeholder="" value="<?php if(set_value('password') != ''){ echo set_value('password');}else{ echo $data->name;}?>" >
                                    <div class="text-danger"><?php echo form_error('password');?></div>
                                </div>

                            </div>
                        </div>
                        
                    </div>


                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('cpassword') !='')?'error':'';?>">
                                <h5>Confirm New Password : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="password" name="cpassword" class="form-control" required data-validation-required-message="This field is required" placeholder="" value="<?php if(set_value('cpassword') != ''){ echo set_value('cpassword');}else{ echo $data->name;}?>" >
                                    <div class="text-danger"><?php echo form_error('cpassword');?></div>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>

            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="<?=$url;?>admin/admins" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>
