
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
                <li class="breadcrumb-item active">Edit Admin</li>
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
            <?=form_open_multipart('',array('class'=>'m-t-40'));?>
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit Admin</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('fname') !='')?'error':'';?>">
                                <h5>Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="fname" class="form-control" required data-validation-required-message="This field is required" placeholder="Name" value="<?php if(set_value('fname') != ''){ echo set_value('fname');}else{ echo $data->name;}?>" pattern="[a-zA-Z0-9 ]+" data-validation-pattern-message="Only alphabet and numbers are allowed.">
                                    <div class="text-danger"><?php echo form_error('fname');?></div>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                       
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('email') !='')?'error':'';?>">
                                <h5>Email : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="email" class="form-control" placeholder="Email Address" required name="email" value="<?php if(set_value('email') != ''){ echo set_value('email');}else{ echo $data->email;}?>">
                                    <div class="text-danger"><?php echo form_error('email');?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="easy form-group <?=(form_error('profile_pic') !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Profile Picture</h5>

                                        <input type="file"  id="input-file-disable-remove" class="dropify" data-show-remove="false" name="profile_pic" data-default-file="<?php echo base_url()."resources/uploads/profiles/".$data->admin_profile_pic; ?>" />
                                        <div class="text-danger"><?php echo form_error('profile_pic');?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('password') !='')?'error':'';?>">
                                <h5>Password : <span class="text-danger">Leave Empty if you don't want to update password.</span></h5>
                                <div class="controls">
                                    <input type="password" class="form-control form-control-line" placeholder="*****"  required data-validation-required-message="This field is required" name="password" >
                                    <div class="text-danger"><?php echo form_error('password');?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!is_region() && 2==4){ ?>

                    <div class="row">

                        <div class="col-6" style="float: left;">
                            <?php $input = "region_id"; ?>
                                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                                    <label for="category">Select Region: <span class="text-danger">*</span> </label>
                                    <select class="custom-select form-control required" name="<?php echo $input; ?>">


                                        <optgroup label="Super Admin">
                                            <option value="0">All Regions</option>
                                        </optgroup>
                                        <optgroup label="Region Admin - Select Region">

                                           <?php 
                                           $q_units = $this->db->where("status",1)->where("lparent",0)->where("is_deleted",0)->get("regions")->result_object();
                                           foreach($q_units as $q_unit){


                                            ?>
                                                 <option <?php if($q_unit->id == $this->input->post($input) || $q_unit->id==$data->region_id){ echo 'selected="selected"';}?>  value="<?php echo $q_unit->id;?>"><?php echo $q_unit->title;?></option>
                                            <?php } ?>
                                        </optgroup>
                                    </select>
                                    <div class="text-danger"><?php echo form_error($input);?></div>
                                </div>
                        </div>
                    </div>

                    <?php } ?>
                </div>
            </div>

            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
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
