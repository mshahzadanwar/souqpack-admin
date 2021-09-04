<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Sliders Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/sliders";?>">Sliders</a></li>
                <li class="breadcrumb-item active">Add New Slider</li>
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
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    
                    
                    <?php $input = $language->slug."[category]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="category">Select Category: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required" name="<?php echo $input; ?>">

                               <?php 
                             $categories = $this->db->where('is_deleted',0)
                                        ->where('parent',0)
                                        ->where('lparent',0)
                                        ->get('categories');
                               foreach($categories->result() as $pcategory){
                                $one = $pcategory;

                                if($one->lparent==0)
                                {
                                    $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
                                }
                                else
                                {
                                    $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
                                }

                                ?>
                                <optgroup label="<?php echo $one->title . ' / ' .$two->title ;?>">
                                    <?php
                                        $sub_cats = $this->db->where("parent",$pcategory->id)->where("lparent",0)->get("categories")->result_object();
                                        foreach($sub_cats as $category){


                                        $one = $category;

                                        if($one->lparent==0)
                                        {
                                            $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
                                        }
                                        else
                                        {
                                            $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
                                        }

                                        $value_id = $one->lang_id==2?$one->id:$two->id;


                                     ?>
                                     <option <?php if($value_id == $this->input->post($input) || $prev->category==$value_id){ echo 'selected="selected"';}?>  value="<?php echo $two->slug;?>"><?php echo $one->title . ' / ' .$two->title ;?></option>


                                 <?php } ?>
                                </optgroup>
                                   
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
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

                   

                </div>
                <?php } ?>
            </div>
            <?php echo $meta;?>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/sliders" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>