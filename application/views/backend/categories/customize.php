<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Categories Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/categories";?>">Categories</a></li>
                <li class="breadcrumb-item active">Customize Category</li>
            </ol>
        </div>
    </div>
   <style type="text/css">
       .form-group {width: 49%; float: left; margin: 1% 0.5%;}
   </style>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <?=form_open_multipart('',array('class'=>'form-material',"id"=>"catFormCust"));?>
            <div class="card">
                

                
                <?php
                $sub_ids = "listing";
                ?>

                <div class="card-header">
                    <h4 class="m-b-0 text-white">Customize Category
                    </h4>
                </div>
                <?php 
                // $data = $this->category->get_cat_by_lang($language->id,$the_id);
                 ?>
                <div class="card-body "  >


                    <?php $input = "logo_price"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>New Logo Price (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="New Logo Price (<?php echo get_currency(); ?>)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->logo_price; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>


                     <?php /*$input = "stamps_price"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Stamps Price (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Stamps Price (<?php echo get_currency(); ?>)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->stamps_price; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>


                    <?php  $input = "pc_price"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Piece Price (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Piece Price (<?php echo get_currency(); ?>)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->pc_price; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>


                    <?php $input = "min_qty"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Min. Order Qty. <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Min. Order Qty." value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->min_qty; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php */ ?>

                    <?php $input = "shipping"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Shipping Charges (<?php echo get_currency(); ?>) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Shipping Charges (<?php echo get_currency(); ?>)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->shipping; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>


                    <?php $input = "vat"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>VAT (%) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="VAT (%)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->vat; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>





                    <div class="form-group <?=(form_error('delivery') !='')?'error':'';?>">
                        <label for="delivery">Delivery in: <span class="text-danger">*</span> </label>
                        <?php $input ="delivery"; ?>
                        <select class="custom-select form-control " id="delivery" name="<?php echo $input; ?>">
                          
                               <?php 
                               for($i=1; $i<=30; $i++){

                               
                                ?>
                                   <option <?php if($i == $this->input->post($input) || $data->delivery==$i){ echo 'selected="selected"';}?>  value="<?php echo $i;?>"><?php echo $i; ?></option>
                                <?php } ?>
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                    <div class="form-group <?=(form_error('delivery_type') !='')?'error':'';?>">
                        <label for="delivery_type">Delivery Time Type: <span class="text-danger">*</span> </label>
                        <?php $input ="delivery_type"; ?>
                        <select class="custom-select form-control " id="delivery_type" name="<?php echo $input; ?>">
                          
                              
                                <option
                                    <?php 
                                    if("Hours" == $this->input->post($input) 
                                        || $data->delivery_type=="Hours")
                                    { echo 'selected="selected"';}
                                    ?>  
                                    value="Hours">Hours
                                </option>

                                <option
                                    <?php 
                                    if("Days" == $this->input->post($input) 
                                        || $data->delivery_type=="Days")
                                    { echo 'selected="selected"';}
                                    ?>  
                                    value="Days">Days
                                </option>

                                <option
                                    <?php 
                                    if("Months" == $this->input->post($input) 
                                        || $data->delivery_type=="Months")
                                    { echo 'selected="selected"';}
                                    ?>  
                                    value="Months">Months
                                </option>
                               
                        </select>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>

                    <?php $input = "note_text_en"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Notes (English) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" id="<?php echo $input; ?>" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Add Notes To display (English)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->note_text_en; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php $input = "note_text_ar"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Notes (Arabic) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" id="<?php echo $input; ?>" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Add Notes To display (Arabic)" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->note_text_ar; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php $input = "terms_en"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Terms & Conditions (English) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <textarea rows="3" id="<?php echo $input; ?>" name="<?php echo $input; ?>" class="form-control" placeholder="Terms & Conditions (English)" ><?php echo strip_tags($data->terms_en); ?></textarea>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php $input = "terms_ar"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Terms & Conditions (Arabic) <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <textarea rows="3" id="<?php echo $input; ?>" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Terms & Conditions (Arabic)"><?php echo strip_tags($data->terms_ar); ?></textarea>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                     <?php /* ?>
                    <?php $input = "colors"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5># of Color Print <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" data-colors="0" id="<?php echo $input; ?>" onchange="more_table_price_option(this,'colors')" step="1"  min="0" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="# of Color Print" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->colors; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php $input = "faces"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5># of Faces <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="number" data-faces="1" data-oldprive="0" id="<?php echo $input; ?>" onchange="more_table_price_option(this,'faces')" step="1" min="1" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="# of Faces" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->faces; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                 <?php */ ?>



                <div id="add_more_variations_in_me" class="easy pull-left" style="width: 100%;">
                    <?php
                    $v_variations = $this->db->where("category",$data->id)->get("v_variations")->result_array();

                    foreach($v_variations as $v_variation)
                    {
                        // unset($v_variation["category"]);
                        $idvtable = $v_variation["id"];
                        unset($v_variation["id"]);

                        //$ci->db->where("category",$cat_id)->get("v_variations")->result_object()[0];
                        $this->db->insert("tmp_variations",$v_variation);
                        $variation_id = $this->db->insert_id();

                        // $tables = $this->db->where("variation_id",$idvtable)->get("v_tables")->result_array();

                        // foreach($tables as $table)
                        // {
                        //     $idvtable = $table["id"];
                        //     unset($table["id"]);
                        //     $table["variation_id"] = $variation_id;
                        //     $this->db->insert("tmp_table",$row);
                        //     $table_id = $this->db->insert_id();

                        //     $rows = $this->db->where("table_id",$idvtable)->get("v_rows")->result_array();

                        //     foreach($rows as $row)
                        //     {
                        //         unset($row["id"]);
                        //         $row["table_id"]=$table_id;
                        //         $this->db->insert("tmp_rows",$row);
                        //     }
                        // }

                        

                        // echo newTableV2($table_id);
                        echo newVariationV2($variation_id,$data->id,$idvtable);
                    }
                    ?>
                </div>
                <div class="form-group easy">
                    <!-- <input type="number" placeholder="Prints" class="prints" name="prints">
                    <input type="number" placeholder="Faces" class="faces" name="faces"> -->
                    <button type="button" class="btn btn-warning btn-sm" onclick="moreVariationV2();">+ ADD NEW ITEM NAME</button>
                </div>
                

            



            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="button" onclick="submitForm()" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/categories" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>