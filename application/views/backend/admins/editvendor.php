
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
            <h3 class="text-themecolor m-b-0 m-t-0">Profile Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Profile</li>
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
                    <h4 class="m-b-0 text-white">Edit Profile</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('name') !='')?'error':'';?>">
                                <h5>Name : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="name" class="form-control" required data-validation-required-message="This field is required" placeholder="Name" value="<?php if(set_value('name') != ''){ echo set_value('name');}else{ echo $data->name;}?>" pattern="[a-zA-Z0-9 ]+" data-validation-pattern-message="Only alphabet and numbers are allowed.">
                                    <div class="text-danger"><?php echo form_error('name');?></div>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                       
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('phone') !='')?'error':'';?>">
                                <h5>Phone : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="tel" class="form-control" placeholder="phone Address"  required data-validation-required-message="This field is required" name="phone" value="<?php if(set_value('phone') != ''){ echo set_value('phone');}else{ echo $data->phone;}?>">
                                    <div class="text-danger"><?php echo form_error('phone');?></div>
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
                        <div class="col-md-6">
                            <div class="easy form-group <?=(form_error('profile_pic') !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Profile Picture</h5>

                                        <input type="file"  id="input-file-disable-remove" class="dropify" data-show-remove="false" name="profile_pic" data-default-file="<?php echo base_url()."resources/uploads/vendors/".$data->profile_pic; ?>" />
                                        <div class="text-danger"><?php echo form_error('profile_pic');?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>

                  
                    </div>


               
            </div>

            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>categories" class="btn btn-inverse">Cancel</a>
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