

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Users Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/users";?>">Users</a></li>
                <li class="breadcrumb-item active">Add New User</li>
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
            <div class="card">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Information
                        
                    </h4>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('fname') !='')?'error':'';?>">
                                <h5>First Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="fname" class="form-control" required data-validation-required-message="This field is required" placeholder="First Name" value="<?php if(set_value('fname') != ''){ echo set_value('fname');}else echo $prev->fname;?>" >
                                    <div class="text-danger"><?php echo form_error('fname');?></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('lname') !='')?'error':'';?>">
                                <h5>Last Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="lname" class="form-control" required data-validation-required-message="This field is required" placeholder="Last Name" value="<?php if(set_value('lname') != ''){ echo set_value('lname');}else echo $prev->lname;?>" >
                                    <div class="text-danger"><?php echo form_error('lname');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                       <!--  <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('gender') !='')?'error':'';?>">
                                <h5>Gender : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control" id="gender" name="gender" required data-validation-required-message="This field is required">
                                        <option value="">Choose</option>
                                        <option <?php if($this->input->post('gender')== 'Male' || $prev->gender=="Male"){ echo 'selected="selected"';}?> value="Male">Male</option>
                                        <option <?php if($this->input->post('gender')== 'Female' || $prev->gender=="Female"){ echo 'selected="selected"';}?> value="Female">Female</option>

                                    </select>
                                    <div class="text-danger"><?php echo form_error('gender');?></div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('email') !='')?'error':'';?>">
                                <h5>Email : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" class="form-control" placeholder="Email Address" data-validation-regex-regex="([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})" data-validation-regex-message="Please enter the valid email address." required data-validation-required-message="This field is required" name="email" value="<?php if(set_value('email') != ''){ echo set_value('email');}else echo $prev->email;?>">
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
                                            <option <?php if($this->input->post('code')== $country->phonecode || $prev->code==$country->phonecode){ echo 'selected="selected"';}?> value="<?php echo $country->phonecode;?>">+<?php echo $country->phonecode.' ('.$country->name.')';?></option>
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
                                    <input type="text" name="mobile" class="form-control" required data-validation-required-message="This field is required" placeholder="Mobile Number" value="<?php if(set_value('mobile') != ''){ echo set_value('mobile');}else echo $prev->mobile;?>" pattern="[0-9]+" data-validation-pattern-message="Only numbers are allowed.">

                                </div>
                                <div class="text-danger"><?php echo form_error('mobile');?></div>
                            </div>
                        </div>
                    </div>

                   <!--  <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group <?=(form_error('address') !='')?'error':'';?>">
                                <h5>Address : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <textarea class="form-control" name="address" rows="5" ><?php if(set_value('address') != ''){ echo set_value('address');}else echo $prev->address;?></textarea>
                                    <div class="text-danger"><?php echo form_error('address');?></div>
                                </div>

                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('country') !='')?'error':'';?>">
                                <h5 for="gender">Country : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control select2" id="country" name="country" data-validation-required-message="This field is required" required>
                                        <option value="">Choose Country</option>
                                        <?php foreach($countries->result() as $country){?>
                                            <option <?php if($this->input->post('country')== $country->id){ echo 'selected="selected"';}?> value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
                                        <?php } ?>

                                    </select>
                                    <div class="text-danger"><?php echo form_error('country');?></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('state') !='')?'error':'';?>">
                                <h5 for="gender">State : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control select2" id="state" name="state" data-validation-required-message="This field is required" required>
                                        <option value="">Choose State</option>


                                    </select>
                                    <div class="text-danger"><?php echo form_error('state');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('city') !='')?'error':'';?>">
                                <h5 for="gender">City : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control select2" id="city" name="city" data-validation-required-message="This field is required" required>
                                        <option value="">Choose City</option>


                                    </select>
                                    <div class="text-danger"><?php echo form_error('city');?></div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('zip') !='')?'error':'';?>">
                                <h5 for="gender">Zip Code : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <input type="text" name="zip" class="form-control" required data-validation-required-message="This field is required" placeholder="Zip Code" value="<?php if(set_value('zip') != ''){ echo set_value('zip');}?>" pattern="[0-9]+" data-validation-pattern-message="Only numbers are allowed.">

                                </div>
                                <div class="text-danger"><?php echo form_error('zip');?></div>
                            </div>
                        </div>
                    </div> -->
                     <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('password') !='')?'error':'';?>">
                                <h5>Password : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="password" class="form-control form-control-line" placeholder="*****"  required data-validation-required-message="This field is required" name="password" >
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
                    <a href="<?=$url;?>admin/users" class="btn btn-inverse">Cancel</a>
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
    var country = '<?php echo $this->input->post('country');?>';
    var state = '<?php echo $this->input->post('state');?>';
    var city = '<?php echo $this->input->post('city');?>';
</script>