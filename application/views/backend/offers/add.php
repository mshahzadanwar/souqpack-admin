

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Offers Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/offers";?>">Offers</a></li>
                <li class="breadcrumb-item active">Add New Offer</li>
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
                <?php foreach($languages as $language){
                    $data = $this->offer->get_offer_by_lang($language->id,$the_id);
                 ?>
                <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->title; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php if($language->is_default==1){ /* ?>

                    <div class="form-group <?=(form_error('category') !='')?'error':'';?>">
                        <label for="category">Select category : <span class="text-danger">*</span> </label>
                        <select onchange="getProducts(this,<?php echo $this->input->post('product')?$this->input->post('product'):0; ?>)" class="custom-select form-control required" id="category" name="category" required>
                                <option value="">Choose</option>
                               <?php foreach($categories->result() as $category){?>
                                   <option <?php if($category->id == $this->input->post('category') || $prev->category==$category->id){ echo 'selected="selected"';}?>  value="<?php echo $category->id;?>"><?php echo $category->title;?></option>
                                <?php } ?>

                        </select>
                        <div class="text-danger"><?php echo form_error('category');?></div>
                    </div>


                     <div class="easy add_products_view_inside_me">
                        <?php if($this->input->post('category')!="" || !empty($prev))
                        {
                            $cat = $this->input->post('category')?$this->input->post('category'):$prev->category;
                            $pos = $this->input->post('product')?$this->input->post('product'):$prev->product;
                            echo view_products_section_helper($cat,$pos);
                        }
                         ?>
                    </div>
                    <?php */ ?>


                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Products:</label>
                        <select class="select2 m-b-10 select2-multiple" name="products[]" style="width: 100%" multiple="multiple" data-placeholder="Choose">
                            <?php 
                            $products = $this->db->where("lparent",0)->where("is_deleted",0)->where("status",1)->get('products')->result_object();
                            foreach($products as $product){?>
                                <option

                                 value="<?php echo $product->id;?>"><?php echo $product->title.' - '.$product->sku;?></option>
                            <?php } ?>
                        </select>
                    </div>

                   




                    <div class="form-group <?=(form_error('discount_type') !='')?'error':'';?>">
                        <h5>Discount Type <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <select class="form-control form-control-line" name="discount_type" required>
                                <option <?php if($this->input->post("discount_type")=="0"  || $prev->discount_type=="0") echo "selected"; ?> value="0">None</option>
                                <option <?php if($this->input->post("discount_type")=="1"  || $prev->discount_type=="1") echo "selected"; ?> value="1">Flat</option>
                                <option <?php if($this->input->post("discount_type")=="2"  || $prev->discount_type=="2") echo "selected"; ?> value="2">Percent</option>
                            </select>
                            
                            <div class="text-danger"><?php echo form_error('discount_type');?></div>
                        </div>
                    </div>
                    <div class="form-group <?=(form_error('discount') !='')?'error':'';?>">
                        <h5>Discount <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="discount" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="0" value="<?php if(set_value('discount') != ''){ echo set_value('discount');}else echo $prev->discount;?>">
                            <div class="text-danger"><?php echo form_error('discount');?></div>
                        </div>
                    </div>


                    <div class="form-group <?=(form_error('applies_on_date') !='')?'error':'';?>">
                        <h5>Applies on date?</h5>
                        <div class="controls">
                           <input <?php if($this->input->post('applies_on_date')==1 || $prev->applies_on_date==1) echo "checked"; ?> type="checkbox" value="1" onclick="date_applies_checked(this)" name="applies_on_date">
                            
                            <div class="text-danger"><?php echo form_error('applies_on_date');?></div>
                        </div>
                    </div>


                    <div class="col-12 nopad date_applies" style="display: <?php if($this->input->post('applies_on_date')!=1) echo "none"; ?>;">
                        <div class="col-md-3 nopad">
                            <div class="form-group <?=(form_error('start_date') !='')?'error':'';?>">
                                <h5>Start Date: </h5>
                                <div class="controls">
                                    <input type="text" class="form-control publish required" name="start_date" value="<?php if(set_value('start_date') != ''){ echo set_value('start_date');}else echo date("Y-m-d",strtotime($prev->start_date)); ?>">
                                    <div class="text-danger"><?php echo form_error('start_date');?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 nopad">
                            <div class="form-group <?=(form_error('end_date') !='')?'error':'';?>">
                                <h5>End Date: </h5>
                                <div class="controls">
                                    <input type="text" class="form-control publish required" name="end_date" value="<?php if(set_value('end_date') != ''){ echo set_value('end_date');}else echo date("Y-m-d",strtotime($prev->end_date));?>">
                                    <div class="text-danger"><?php echo form_error('end_date');?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?=(form_error('min_qty') !='')?'error':'';?>">
                        <h5>On Purchase of Minimum quantity: <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <select class="form-control form-control-line" name="min_qty" required>
                                <option value="0">Any</option>
                                <?php for($i=1;$i<=20;$i++){ ?>
                                    <option <?php if($this->input->post('min_qty')==$i || $prev->min_qty==$i) ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            
                            <div class="text-danger"><?php echo form_error('min_qty');?></div>
                        </div>
                    </div>

                    <?php } ?>
                    <?php $input = $language->slug."[description]"; ?>

                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Description </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="<?php echo $input; ?>" ><?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->description;?></textarea>
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

                </div>
                <?php } ?>
            </div>
            <?php //echo $meta;?>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/offers" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>