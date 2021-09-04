<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Languages Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/languages";?>">Languages</a></li>
                <li class="breadcrumb-item active">Add New Language</li>
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
                            <div class="form-group <?=(form_error('title') !='')?'error':'';?>">
                                <h5>Title : <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="title" class="form-control" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value('title') != ''){ echo set_value('title');}else echo $prev->title;?>">
                                    <div class="text-danger"><?php echo form_error('title');?></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group <?=(form_error('direction') !='')?'error':'';?>">
                                <h5>Direction : <span class="text-danger">*</span> </h5>
                                <div class="controls">
                                    <select class="custom-select form-control" id="direction" name="direction" required data-validation-required-message="This field is required">
                                        <option value="">Choose</option>
                                        <option <?php if($this->input->post('direction')== 'LTR' || $prev->direction=="LTR"){ echo 'selected="selected"';}?> value="LTR">LTR</option>
                                        <option <?php if($this->input->post('direction')== 'RTL' || $prev->direction=="RTL"){ echo 'selected="selected"';}?> value="RTL">RTL</option>

                                    </select>
                                    <div class="text-danger"><?php echo form_error('direction');?></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group <?=(form_error('image') !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Flag</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="image" data-default-file="" />
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
                    <a href="<?=$url;?>admin/languages" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>