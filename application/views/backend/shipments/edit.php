<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Shipment Methods Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/shipments";?>">Shipment Methods</a></li>
                <li class="breadcrumb-item active">Edit Shipment Method</li>
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
                    <h4 class="m-b-0 text-white">Edit Shipment Methods
                       
                    </h4>
                </div>
                 <?php foreach($languages as $language){

                    $data = $this->shipment->get_shipment_by_lang($language->id,$the_id);
                  ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $data->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php if($language->is_default==1){ ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="category">Select Category: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required" name="<?php echo $input; ?>">

                               <?php foreach($categories->result() as $pcategory){


                                ?>
                                <optgroup label="<?php echo $pcategory->title; ?>">
                                    <?php
                                        $sub_cats = $this->db->where("parent",$pcategory->id)->where("lparent",0)->get("categories")->result_object();
                                        foreach($sub_cats as $category){
                                     ?>
                                     <option <?php if($category->id == $this->input->post($input) || $data->category==$category->id){ echo 'selected="selected"';}?>  value="<?php echo $category->id;?>"><?php echo $category->title;?></option>


                                 <?php } ?>
                                </optgroup>
                                   
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                    <?php } ?>

                   <input type="hidden" name="<?php echo $language->slug."[row_id]"; ?>" value="<?php echo $data->id; ?>">
                </div>
                <?php } ?>
            </div>
            <?php echo $meta;?>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/shipments" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>