<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Stores Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/stores";?>">Stores</a></li>
                <li class="breadcrumb-item active">Add New Store</li>
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
                <?php
                $sub_ids = "listing";
                    require ("./application/views/backend/common/lang_select.php");
                ?>

                <div class="card-header">
                    <h4 class="m-b-0 text-white">Information
                        <a 
                        href="<?php echo base_url()."admin/add-brand?replicate=1"; ?>">
                            <button type="button" class="btn btn-inverse btn-sm">
                                Replicate Previous Entry
                            </button>
                        </a>
                    </h4>
                </div>
                 <?php foreach($languages as $language){ ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php if($language->is_default==1){ ?>

                    <?php $input = $language->slug."[region_id]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="store">Quantity Unit: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required"  name="<?php echo $input; ?>">

                                <option value="0">All</option>

                               <?php 
                               $regions = $this->db->where("status",1)
                               ->where('lparent',0)
                               ->where('is_deleted',0)->get("stores");
                               foreach($regions->result() as $store){?>
                                   <option <?php if($store->id == $this->input->post($input) || $prev->region_id==$store->id){ echo 'selected="selected"';}?>  value="<?php echo $store->id;?>"><?php echo $store->title;?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>


                    <?php $input = $language->slug."[email]"; ?>

                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Email <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="email" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="email" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->email;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>
                    <?php $input = $language->slug."[phone]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Phone <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="tel" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Phone" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->phone;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php $input = $language->slug."[address]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Address <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Address" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->address;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php } ?>

                    
                    <?php $input = $language->slug."[description]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Description </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="<?php echo $input; ?>" ><?php if(set_value($input) != ''){ echo set_value($input);}?></textarea>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>



                    


                    <?php $input = $language->slug."_image"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Image</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" />
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    


                    <?php $input = $language->slug."_banner"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Banner</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" />
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <?php } ?>
            </div>
            <?php echo $meta;?>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/stores" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>