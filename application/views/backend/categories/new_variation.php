
<div style="
padding: 20px;
float: left;
width: 100%;
border:1px dotted #ccc; margin-top:10px; background: #fdfdfd;"> 

<button type="button" class="btn btn-danger" style="    float: right;
    text-transform: uppercase;
    font-size: 11px;" onclick="remove_full_item_table(this,<?php echo $real_id;?>)"> Remove Full ITEM NAME</button>

<div class="col-6">
    <div class="form-group">
        <h5>Item Name (En) <span class="text-danger">*</span></h5>
        <div class="controls">
            <input type="text" name="c_title[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Item Name" value="<?php echo $variation->c_title; ?>">
        </div>
    </div>
</div>

<div class="col-6">
    <div class="form-group">
        <h5>Item Name (Ar) <span class="text-danger">*</span></h5>
        <div class="controls">
            <input type="text" name="c_title_ar[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Item Name" value="<?php echo $variation->c_title_ar; ?>">
        </div>
    </div>
</div>
<?php $input = "c_image_".$variation->id; ?>
    <div class="easy<?=(form_error($input) !='')?'error':'';?>">
        <div class="row">
            <div class="col-lg-12">
                <h5>Featured Image</h5>
            </div>
           
            <div class="col-lg-6 col-md-6 nopad new_image_<?php echo $variation->id;?>" id="new_div_after<?php echo $variation->id;?>">
                <div class="card">
                    <div class="card-body">
                        
                        <input type="hidden" name="variation_id[<?php echo $variation->id; ?>]" value="<?php echo $insert_id; ?>" >
                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="<?php if($variation->cust_image!=""){
                            echo base_url()."resources/uploads/categories/".$variation->cust_image;
                        } ?>" />
                        <input type="hidden" value="<?php echo $variation->cust_image; ?>" name="pre_selected_image[<?php echo $variation->id; ?>]">
                    </div>

                </div>
            </div>

            <div id="add_more_images_in_me<?php echo $variation->id; ?>" class="easy pull-left" style="width: 100%;">
                <?php
                    $more_images_now = $this->db->where("category_id",$data->id)->where("v_id",$real_id)->get("category_images")->result_object();
                    //echo $this->db->last_query();
                    foreach($more_images_now as $mire_key=>$more_image_now)
                    {
                        $this->data["more_image_now"] = $more_image_now->image;
                        $this->data["more_id"] = $more_image_now->id;
                        $this->data["var_id"] = $variation->id;
                        $this->load->view("backend/categories/image_section",$this->data);
                    }
                ?>
            </div>
            
        </div>
        <div class="form-group easy">
            <button type="button" class="btn btn-info btn-sm" onclick="moreImage_Variation(this,<?php echo $variation->id; ?>);">+ New Image</button>
        </div>
        <!-- <div class="col-lg-12" style="margin-bottom: 19px;">
                <button type="button" name="new_image" id="new_image" onclick="add_new_image_push(<?php echo $variation->id;?>)" class="btn btn-sm btn-info left"> New Image </button>
            </div> -->
    </div>

    <div class="show_description_texteditor<?php echo $variation->id; ?>" style="    clear: both;
    float: left;
    width: 100%;margin-bottom: 20px">
            <div class="col-md-6" style="float: left;">
                <label>Description (English)</label>
                <textarea name="c_descps_en[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Item Description in English"  id="mymce"><?php echo $variation->c_descps_en; ?></textarea>
            </div>
            <div class="col-md-6"  style="float: left;">
                <label>Description (Arabic)</label>
                <textarea name="c_descps_ar[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Item Description in Arabic"  id="mymce"><?php echo $variation->c_descps_ar; ?></textarea>
            </div>
    </div>


    <div class="show_meta_tags<?php echo $variation->id; ?>" style="clear: both;float: left;width: 100%;margin-bottom: 20px">
            <div class="col-md-6" style="float: left;">
                <label>Meta Details (English)</label>
                <input type="text" name="meta_title_en[<?php echo $variation->id; ?>]" class="form-control" placeholder="Meta Title (English)" value="<?php echo $variation->meta_title_en; ?>" style="margin-bottom: 10px;">
                <textarea name="meta_descps_en[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Meta Description (English)"  style="margin-bottom: 10px; height: 50px;"><?php echo $variation->meta_descps_en; ?></textarea>
                <textarea name="meta_keys_en[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Meta Keyword (English)" style="margin-bottom: 10px; height: 50px;"><?php echo $variation->meta_keys_en; ?></textarea>
            </div>
            <div class="col-md-6"  style="float: left;">
                <label>Meta Details (Arabic)</label>
                <input type="text" name="meta_title_ar[<?php echo $variation->id; ?>]" class="form-control" placeholder="Meta Title (Arabic)" value="<?php echo $variation->meta_title_ar; ?>"  style="margin-bottom: 10px;">
                <textarea name="meta_descps_ar[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Meta Description (Arabic)" style="margin-bottom: 10px; height: 50px;"><?php echo $variation->meta_descps_ar; ?></textarea>
                <textarea name="meta_keys_ar[<?php echo $variation->id; ?>]" class="form-control form-control-line" placeholder="Meta Keyword (Arabic)" style="margin-bottom: 10px; height: 50px;"><?php echo $variation->meta_keys_ar; ?></textarea>
            </div>
    </div>

<div id="add_more_tables_in_me<?php echo $variation->id; ?>" class="easy pull-left" style="

 width: 100%;

 ">
    <?php
    $v_tables = $this->db->where("variation_id",$real_id)->get("v_table")->result_array();
      
    foreach($v_tables as $v_table)
    {
        unset($v_table["category"]);
        $idvtable = $v_table["id"];
        unset($v_table["id"]);
        $v_table["variation_id"]=$variation->id;

        $this->db->insert("tmp_table",$v_table);
        // echo $this->db->last_query();exit();
        $table_id = $this->db->insert_id();

        $rows = $this->db->where("table_id",$idvtable)->get("v_rows")->result_array();
        foreach($rows as $row)
        {
            unset($row["id"]);
            $row["table_id"]=$table_id;
            $this->db->insert("tmp_rows",$row);
        }

        echo newTableV2($table_id,false,$variation->id,$idvtable);
    }
    if(empty($v_tables))
    {
        echo newTableV2(0,false,$variation->id);
    }
    ?>
</div>
<div class="form-group easy">
    <!-- <input type="number" placeholder="Prints" class="prints" name="prints">
    <input type="number" placeholder="Faces" class="faces" name="faces"> -->
    <button type="button" class="btn btn-info btn-sm" onclick="morePriceV2(<?php echo $variation->id; ?>);">+ ADD NEW PRICE TABLE</button>
</div>




<div class="">
    <div id="add_more_cvariation_in_me_<?php echo $insert_id; ?>" class="easy pull-left" style="width: 100%;">
        <?php
        $custom_variations = $this->db->where("variation_id",$real_id)->where("category",$cat_id)->get("cat_options")->result_object();
        $ob["title_en"] = "";
        $ob["title_ar"] = "";
        $ob["price"] = 100;
        $ob["disabled"] = 0;

        $ob["options"] = json_decode(array());

        $ci = &get_instance();
        if(!empty($custom_variations))
            //echo $ci->load->view("backend/categories/cvariation_section",$ob,true);
        foreach($custom_variations as $key=>$custom_variation)
        {

            c_variationv2($custom_variation,$variation->id);
        } ?>
    </div>
    <hr style="    float: left;
                   clear: both;
                   border: 1px dashed #d0d0d0;
                   width: 100%;" 
    /> 
    <div class="form-group easy">
        <button type="button" class="btn btn-danger btn-sm" onclick="morecVariation(<?php echo $insert_id; ?>,this);">+ New Custom Variation</button>
    </div>
</div>


</div>
