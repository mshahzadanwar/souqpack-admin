<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Products Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/products";?>">Products</a></li>
                <li class="breadcrumb-item active">Add New Product</li>
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
                    <h4 class="m-b-0 text-white">Information </h4>
                </div>
                <?php foreach($languages as $language){ ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >

                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
<?php /*
                        <div class="controls">
                            <?php /*$purchases = $this->db->query("SELECT * FROM `purchases` where `status` = 1 AND lparent = 0")->result_object();
                            ?>
                            <select required name="<?php echo $input; ?>" class="form-control select2" data-validation-required-message="This field is required">
                                <option value="">---SELECT PRODUCT FROM PURCHAS---</option>
                                <?php foreach ($purchases as $key => $purchase) { ?>
                                    <option value="<?php echo $purchase->title;?>"><?php if(set_value($input) == $purchase->title){echo "SELECTED";}?><?php echo $purchase->title;?></option>
                                <?php } ?>
                                
                            </select>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div> */?>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line autocomplete_<?php echo $language->slug;?>"   placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->title;?>" data-noresults-text="Nothing to see here.">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>
                    <?php if($language->is_default==1){ ?>
                    <?php $input = $language->slug."[sku]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>SKU <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line sku_class" required data-validation-required-message="This field is required" placeholder="SKU" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $prev->sku;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                  
                    <?php $input = "out_of_stock"; ?>
                    <div class="form-group">
                        <h5>Out of Stock <small>(Check If you want to make this product out of stock)</small></h5>
                        <label style="margin-left: 20px;">
                           <input
                           <?php echo set_value($input)=="1"?  "checked":""; ?>

                            type="checkbox" name="<?php echo $input; ?>" value="1">
                       Out Of Stock  </label>

                    </div>
                    
                    <?php $input = $language->slug."[delivery]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Delivery <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Delivery" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->delivery;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    


                     <div class="col-4">

                    <?php $input = $language->slug."[popular]"; ?>
                    <div class="form-group">
                        <label>
                           <input
                           <?php echo set_value($input)=="1"?  "checked":""; ?>
                            type="checkbox" name="<?php echo $input; ?>" value="1">
                       Popular Product </label>

                    </div>
                    </div>
                    
                    <div class="col-4">

                    <?php $input = $language->slug."[main]"; ?>
                    <div class="form-group">
                        <label>
                           <input
                           <?php echo set_value($input)=="1"?  "checked":""; ?>
                            type="checkbox" name="<?php echo $input; ?>" value="1">
                       Main Product </label>

                    </div>
                    </div>
                    <div class="col-4">


                    <?php $input = $language->slug."[top_selling]"; ?>
                    <div class="form-group">
                        <label>
                           <input
                           <?php echo set_value($input)=="1"?  "checked":""; ?>

                            type="checkbox" name="<?php echo $input; ?>" value="1">
                       Top Selling Product </label>

                    </div>
                    </div>
                    <div class="col-4">

                    <?php $input = $language->slug."[top_rated]"; ?>
                    <div class="form-group">
                        <label>
                           <input 
                           <?php echo set_value($input)=="1"?  "checked":""; ?>

                           type="checkbox" name="<?php echo $input; ?>" value="1">
                       Top Rated Product </label>

                    </div>

                    </div>

                    <?php $input = $language->slug."[category]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="category">Select Category: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required" name="<?php echo $input; ?>">

                               <?php foreach($categories->result() as $pcategory){
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
                                     <option <?php if($value_id == $this->input->post($input) || $prev->category==$value_id){ echo 'selected="selected"';}?>  value="<?php echo $value_id;?>"><?php echo $one->title . ' / ' .$two->title ;?></option>


                                 <?php } ?>
                                </optgroup>
                                   
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                    <?php /* ?>
                    <?php $input = $language->slug."[brand]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="brand">Select Brand: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required"  name="<?php echo $input; ?>">

                               <?php foreach($brands->result() as $brand){?>
                                   <option <?php if($brand->id == $this->input->post($input) || $prev->brand==$brand->id){ echo 'selected="selected"';}?>  value="<?php echo $brand->id;?>"><?php echo $brand->title;?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                    <?php */ ?>
                    
                    <?php $input = $language->slug."[store]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="store">Select Store: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required"  name="<?php echo $input; ?>">

                                <option value="0">No Store</option>

                               <?php foreach($stores->result() as $store){?>
                                   <option <?php if($store->id == $this->input->post($input) || $prev->store==$store->id){ echo 'selected="selected"';}?>  value="<?php echo $store->id;?>"><?php echo $store->title;?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>

                    <?php $input = $language->slug."[qty_unit]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <label for="store">Quantity Unit: <span class="text-danger">*</span> </label>
                        <select class="custom-select form-control required"  name="<?php echo $input; ?>">

                                <option value="0">None</option>

                               <?php foreach($quantity_units->result() as $store){?>
                                   <option <?php if($store->id == $this->input->post($input) || $prev->qty_unit==$store->id){ echo 'selected="selected"';}?>  value="<?php echo $store->id;?>"><?php echo $store->title;?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                    <?php /* $input = $language->slug."[price]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Price (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="<?php echo get_currency(); ?>" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->price;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php */ ?>

                    <div id="add_more_units_in_me" class="easy pull-left" style="width: 100%;">
                        <?php echo new_unit(region_id(),0,true); ?>
                    </div>
                    <?php  /*if(!is_region()){ ?>

                    <div class="form-group easy">
                        <button type="button" class="btn btn-info btn-sm" onclick="moreUnit(this);">+ Region Price</button>
                    </div>
                    <?php }*/ ?>

                    <?php /* $input = $language->slug."[cost_price]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Cost Price (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="<?php echo get_currency(); ?>" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->cost_price;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php */ ?>
                    <?php $input = $language->slug."[discount_type]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Discount Type <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <select class="form-control form-control-line" name="<?php echo $input; ?>" required>
                                <option <?php if($this->input->post($input)=="0"  || $prev->discount_type=="0") echo "selected"; ?> value="0">None</option>
                                <option <?php if($this->input->post($input)=="1"  || $prev->discount_type=="1") echo "selected"; ?> value="1">Flat</option>
                                <option <?php if($this->input->post($input)=="2"  || $prev->discount_type=="2") echo "selected"; ?> value="2">Percent</option>
                            </select>

                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php $input = $language->slug."[discount]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Discount <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="0" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo 0;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php $input = "min_order_qty"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Minimum Order Qty. <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" min="1" step="1" name="<?php echo $input; ?>" class="form-control form-control-line min_or_qty" required data-validation-required-message="This field is required" placeholder="0" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo "1";?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php } ?>

                    
                    <?php $input = $language->slug."[packaging_box]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Packaging Box</h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Packaging Box" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->packaging_box;?>">
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    
                    <?php $input = $language->slug."[sdescription]"; ?>
                    <div class="form-group
                    <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Short Description </h5>
                        <div class="controls">
                            <textarea
                            class="mymce form-control form-control-line"
                            id="mymce"
                            name="<?php echo $input; ?>" ><?php
                            if(set_value($input) != ''){
                                echo set_value($input);
                            }else echo $prev->sdescription;?></textarea>
                            <div class="text-danger"><?php
                            echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php $input = $language->slug."[description]"; ?>
                    <div class="form-group
                    <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Long Description </h5>
                        <div class="controls">
                            <textarea
                            class="mymce form-control form-control-line"
                            id="mymce"
                            name="<?php echo $input; ?>" ><?php
                            if(set_value($input) != ''){
                                echo set_value($input);
                            }else echo $prev->description;?></textarea>
                            <div class="text-danger"><?php
                            echo form_error($input);?></div>
                        </div>
                    </div>


                    <div id="add_more_descps_in_me<?php echo $language->slug; ?>" class="easy pull-left" style="width: 100%;">

                        <?php

                        $input = "description2".$language->slug;
                        $m_lang = $language->slug;
                         foreach($this->input->post($input) as $k=>$v){
                           $this->data["ex_des_val"] = $this->input->post("title_of_section".$m_lang);
                            $this->data["ex_des_title"] =  $v;
                            $this->data["m_lang"] =  $language->slug;
                            

                            $this->load->view('backend/products/description_section',$this->data);

                        }

                        // foreach(json_decode($prev->$input) as $k=>$v){
                        //     $v = (array) $v;

                        //     $this->data[$input] = key($v);
                        //     $this->data['title_of_section'] =  $v[key($v)];

                        //     $this->load->view('backend/products/description_section',$this->data);

                        // }
                         ?>

                    </div>
                    <div class="form-group easy">
                        <button type="button" class="btn btn-info btn-sm" onclick="moreDescp(this,'<?php echo $language->slug; ?>');">+ Description Section</button>
                    </div>


                    <?php $input = $language->slug."[meta_title]"; ?>
                 <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Title </h5>
                    <div class="controls">
                    <?php 
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Title',
                        'class' => 'form-control',
                        'value'=>set_value($input)

                    );
                    echo form_input($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                </div>

                    <?php $input = $language->slug."[meta_keys]"; ?>

                 <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Keywords </h5>
                    <div class="controls">
                    <?php
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Keywords',
                        'class' => 'form-control',
                        'value'=>set_value($input),
                        'rows'    => '3',
                    );
                    echo form_textarea($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                </div>


                 <?php $input = $language->slug."[meta_desc]"; ?>
               <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Description </h5>
                    <div class="controls">
                    <?php
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Descriptions',
                        'class' => 'form-control',
                        'id'    => 'exampleTextarea',
                        'rows'    => '3',
                        'value'=>set_value($input)
                    );
                    echo form_textarea($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    
                    <?php $input = "image".$language->slug; ?>
                    <div class="easy form-group <?=(form_error($input) !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Featured Image</h5>

                                        <input type="file" multiple id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" />
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="add_more_images_in_me<?php echo $language->slug; ?>" class="easy pull-left" style="width: 100%;">
                    </div>


                    <div class="form-group easy">
                        <button type="button" class="btn btn-info btn-sm" onclick="moreImage(this,'<?php echo $language->slug; ?>');">+ Image</button>
                    </div>


                    <div class="form-group easy">
                        <hr>
                    </div>
                    <div class="form-group easy">
                        <h4></h4>
                    </div>
                    <!-- ///// -->
                </div>
                <?php } ?>
            </div>

<?php /* ?>

            <div class="card">

               

                <div class="card-header">
                    <h4 class="m-b-0 text-white">Variations

                    </h4>
                </div>
                
                    <div class="card-body " >
                        <div id="add_more_variation_in_me<?php echo $language->slug; ?>" class="easy pull-left" style="width: 100%;">
                        </div>
                        <div class="form-group easy">
                            <button type="button" class="btn btn-info btn-sm" onclick="moreVariation(this,'<?php echo $language->slug; ?>');">+ Variation</button>
                        </div>
                    </div>
            </div>

<?php */ ?>
            
            <div class="card">

               

                <div class="card-header">
                    <h4 class="m-b-0 text-white">Custom Variations

                    </h4>
                </div>
                
                    <div class="card-body " >
                        <div id="add_more_cvariation_in_me" class="easy pull-left" style="width: 100%;">
                        </div>
                        <div class="form-group easy">
                            <button type="button" class="btn btn-info btn-sm" onclick="morecVariation(this);">+ Custom Variation</button>
                        </div>
                    </div>
            </div>
            
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/products" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>


