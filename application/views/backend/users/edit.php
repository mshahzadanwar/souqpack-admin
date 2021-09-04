
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
            <h3 class="text-themecolor m-b-0 m-t-0">Users Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?=$url;?>admin/users">Users</a></li>
                <li class="breadcrumb-item active">Edit User</li>
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
            <?=form_open_multipart('',array('class'=>'form-material','novalidate'=>""));?>
            <div class="card ">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit user</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('fname') !='')?'error':'';?>">
                                <h5>First Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="fname" class="form-control" required data-validation-required-message="This field is required" placeholder="First Name" value="<?php if(set_value('fname') != ''){ echo set_value('fname');}else{ echo $data->first_name;}?>" >
                                    <div class="text-danger"><?php echo form_error('fname');?></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('lname') !='')?'error':'';?>">
                                <h5>Last Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="lname" class="form-control" required data-validation-required-message="This field is required" placeholder="Last Name" value="<?php if(set_value('lname') != ''){ echo set_value('lname');}else{ echo $data->last_name;}?>" >
                                    <div class="text-danger"><?php echo form_error('lname');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('email') !='')?'error':'';?>">
                                <h5>Email : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="Email Address" data-validation-regex-regex="([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})" data-validation-regex-message="Please enter the valid email address." required data-validation-required-message="This field is required" name="email" value="<?php if(set_value('email') != ''){ echo set_value('email');}else{ echo $data->email;}?>">
                                    <div class="text-danger"><?php echo form_error('email');?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-">
                            <div class="form-group <?=(form_error('code') !='')?'error':'';?>">
                                <h5 for="gender">Mobile Number : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control select2" id="code" name="code" data-validation-required-message="This field is required" required>
                                        <option value="">Choose Country Code</option>
                                        <?php foreach($countries->result() as $country){?>
                                            <option <?php if($this->input->post('code')== $country->phonecode){ echo 'selected="selected"';}else if($data->country_code == $country->phonecode){ echo 'selected="selected"';}?> value="<?php echo $country->phonecode;?>">+<?php echo $country->phonecode.' ('.$country->name.')';?></option>
                                        <?php } ?>

                                    </select>
                                    <div class="text-danger"><?php echo form_error('code');?></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=( form_error('mobile') !='')?'error':'';?>">
                                <h5 >&nbsp; </h5>
                                <div class="controls">
                                    <input type="text" name="mobile" class="form-control" required data-validation-required-message="This field is required" placeholder="Mobile Number" value="<?php if(set_value('mobile') != ''){ echo set_value('mobile');}else{ echo $data->mobile;}?>" pattern="[0-9]+" data-validation-pattern-message="Only numbers are allowed.">

                                </div>
                                <div class="text-danger"><?php echo form_error('mobile');?></div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('password') !='')?'error':'';?>">
                                <h5>Password : <small>(Leave Empty if you don't want to update password)</small></h5>
                                <div class="controls">
                                    <input type="password" class="form-control form-control-line" placeholder="*****"  name="password" >
                                    <div class="text-danger"><?php echo form_error('password');?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    


                </div>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>users" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>

<script>
    <?php if($this->input->post('country') != ''){?>
            var country = '<?php echo $this->input->post('country');?>';
    <?php }else{?>
            var country = '<?php echo $data->country_id;?>';
    <?php } ?>

    <?php if($this->input->post('state') != ''){?>
    var state = '<?php echo $this->input->post('state');?>';
    <?php }else{?>
    var state = '<?php echo $data->state_id;?>';
    <?php } ?>

    <?php if($this->input->post('city') != ''){?>
    var city = '<?php echo $this->input->post('city');?>';
    <?php }else{?>
    var city = '<?php echo $data->city_id;?>';
    <?php } ?>


</script>