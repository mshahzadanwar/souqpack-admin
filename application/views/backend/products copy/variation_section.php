<script src="<?=$assets;?>tinymce/tinymce.min.js"></script>
<?php $t = time().rand(22992,9292929); ?>
<div class="card" >


    <?php
        $sub_ids = "variations".$t;
        require ("./application/views/backend/common/lang_select.php");
    ?>


     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">Variation</h4>

        <button class="btn btn-danger btn-sm pull-right" onclick="removeVariation(this);">Remove</button>
    </div>
    <?php foreach($languages as $language){



        $this->db->group_start();
        $this->db->where("lang_id",$language->id);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where("id",$the_variation_id);
        $this->db->or_where("lparent",$the_variation_id);
        $this->db->group_end();
        $variation = $this->db->get('variations')->result_object()[0];

        

        $var_type = $variation->type;
        $var_value = $variation->title;
        $var_vimg = $variation->image;
                            
     ?>

    <div class="card-body lang_bodies<?php echo $sub_ids; ?>" id="lang-<?php echo $language->id; ?><?php echo $sub_ids; ?>"
    style="display: <?php echo $language->id==$active?"":"none"; ?>; 
    padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;"
                        >
    <div class="form-group">
        <h5>Type</h5>
        <?php $input = $language->slug."[type][".$ti."]"; ?>
        <select class="form-control" name="<?php echo $input; ?>">
            <option <?php echo $var_type=="Color"?"selected":""; ?> value="Color">Color</option>
            <option <?php echo $var_type=="Size"?"selected":""; ?> value="Size">Size</option>
            <option <?php echo $var_type=="Weight"?"selected":""; ?> value="Weight">Weight</option>
            <option <?php echo $var_type=="Model"?"selected":""; ?> value="Model">Model</option>
        </select>
    </div>

    <div class="form-group">
        <h5>Value (e.g: Red, Small)</h5>
        <?php $input = $language->slug."[value][".$ti."]"; ?>
        <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Value" value="<?php echo $var_value; ?>">
        <?php if($language->is_default==1){ ?>
            <input type="hidden"  name="variations[]" value="<?php echo $t; ?>" >
        <?php } ?>
    </div>
    <div class="easy form-group ">
        <div class="row">
            <div class="col-lg-6 col-md-6 nopad">
                <div class="card">
                    <div class="card-body">
                        <h5>Image <small>(Optional)</small></h5>

                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="vimg<?php echo $language->slug; ?>[<?php echo $t; ?>]" data-default-file="<?php if($var_vimg!=""){

                                            echo base_url()."resources/uploads/products/".$var_vimg;
                                        } ?>" />
                        <input type="hidden" name="def_vimg<?php echo $language->slug; ?>[<?php echo $t; ?>]" value="<?php echo $var_vimg; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php } ?>
</div>
