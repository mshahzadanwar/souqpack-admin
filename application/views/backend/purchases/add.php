<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Purchases Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/purchases";?>">Purchases</a></li>
                <li class="breadcrumb-item active">Add New Purchase</li>
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
                       
                    </h4>
                </div>
                 <?php foreach($languages as $language){ ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Product Name <?php if($language->is_default==1){ ?><span class="text-danger">*</span><?php } ?></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" <?php if($language->is_default==1){ ?> required data-validation-required-message="This field is required" <?php } ?> placeholder="Product Name" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <input type="hidden" name="<?php echo $language->slug."[must_do]"; ?>" value="<?php echo time(); ?>">

                    <?php if($language->is_default==1){ ?>

                    <?php $input = $language->slug."[avl_qty]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Stock (Total Available)<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" min="0"  name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Available Stock" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->avl_qty;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php $input = $language->slug."[sku]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>SKU<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text"  name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="SKU" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->sku;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>


                    <?php  /* $input = $language->slug."[vendor]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Vendor<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Vendor" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->vendor;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                     <?php $input = $language->slug."[size]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Size<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line"  placeholder="size" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->size;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>


                    <?php if(!is_region()){ ?>
                    <?php $input = "region_id"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="category">Choose Currency/Region: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required" name="<?php echo $input; ?>">

                               <?php 
                               $q_units = $this->db->where("status",1)->where("lang_id",2)->where("is_deleted",0)->get("regions")->result_object();
                               foreach($q_units as $q_unit){
                                ?>
                                     <option <?php if($q_unit->id == $this->input->post($input) || $prev->region_id==$q_unit->id){ echo 'selected="selected"';}?>  value="<?php echo $q_unit->id;?>"><?php echo $q_unit->currency;?> - <?php echo $q_unit->title;?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>

                    <?php } */ ?>


                    <?php $input = $language->slug."[price]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Cost Price<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" min="0" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Price" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->price;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php /* $input = $language->slug."[unit_price]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Unit Price<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" min="0"  step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Unit Price" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->unit_price;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php $input = $language->slug."[total_price]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Total Price<span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" min="0"  step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Total Price" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->total_price;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>


                    <?php $input = $language->slug."[discount]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Discount
                        <div class="controls">
                            <input type="number"  min="0"  step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Discount" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->discount;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php */ ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/purchases" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>