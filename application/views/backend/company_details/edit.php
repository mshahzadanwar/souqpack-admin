<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Company Details Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/company-details";?>">Company Details</a></li>
                <li class="breadcrumb-item active">Edit Company Detail</li>
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
                    <h4 class="m-b-0 text-white">Information</h4>
                </div>
                <div class="card-body">
                    

                    <div class="form-group <?=(form_error('company_name') !='')?'error':'';?>">
                        <h5>Company Name <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="company_name" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Company Name" value="<?php if(set_value('company_name') != ''){ echo set_value('company_name');}else echo $data->title;?>" >
                            <div class="text-danger"><?php echo form_error('company_name');?></div>
                        </div>
                    </div>

                    <div class="form-group <?=(form_error('email') !='')?'error':'';?>">
                        <h5>Email <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="email" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Email" value="<?php if(set_value('email') != ''){ echo set_value('email');}else echo $data->email;?>" >
                            <div class="text-danger"><?php echo form_error('email');?></div>
                        </div>

                    </div>

                    <div class="form-group <?=(form_error('phone') !='')?'error':'';?>">
                        <h5>Phone </h5>
                        <div class="controls">
                            <input type="text" name="phone" class="form-control form-control-line" placeholder="Phone" value="<?php if(set_value('phone') != ''){ echo set_value('phone');}else echo $data->phone;?>" >
                            <div class="text-danger"><?php echo form_error('phone');?></div>
                        </div>

                    </div>

                    <div class="form-group <?=(form_error('mobile') !='')?'error':'';?>">
                        <h5>Mobile </h5>
                        <div class="controls">
                            <input type="text" name="mobile" class="form-control form-control-line" placeholder="Mobile" value="<?php if(set_value('mobile') != ''){ echo set_value('mobile');}else echo $data->mobile;?>" >
                            <div class="text-danger"><?php echo form_error('mobile');?></div>
                        </div>

                    </div>
                    <div class="form-group <?=(form_error('address') !='')?'error':'';?>">
                        <h5>Address </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="address" ><?php if(set_value('address') != ''){ echo set_value('address');}else echo $data->address;?></textarea>
                            <div class="text-danger"><?php echo form_error('address');?></div>
                        </div>
                    </div>

                   
                    <div class="form-group <?=(form_error('description') !='')?'error':'';?>">
                        <h5>Description </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="description" ><?php if(set_value('description') != ''){ echo set_value('description');}else echo $data->description;?></textarea>
                            <div class="text-danger"><?php echo form_error('description');?></div>
                        </div>
                    </div>
                    <div class="form-group <?=(form_error('image') !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Logo</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="image" data-default-file="<?php echo base_url()."resources/uploads/company_details/".$data->image; ?>" />
                                        <div class="text-danger"><?php echo form_error('image');?></div>
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
                    <a href="<?=$url;?>admin/company-details" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>