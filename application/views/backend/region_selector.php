 <?php if(!is_region()){ ?>

    <div class="row">

        <div class="col-6" style="float: left;">
            <?php $input = "region_id"; ?>
                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <label for="category">Select Region: <span class="text-danger">*</span> </label>
                    <select class="custom-select form-control required" name="<?php echo $input; ?>">


                        <optgroup label="Super Admin">
                            <option value="0">Default Region</option>
                        </optgroup>
                        <optgroup label="Specific Region">

                           <?php 
                           $q_units = $this->db->where("status",1)->where("lparent",0)->where("is_deleted",0)->get("regions")->result_object();
                           foreach($q_units as $q_unit){
                                ?>
                                <option 
                                <?php 
                                if($q_unit->id == $this->input->post($input) || $q_unit->id==$region_id)
                                    { 
                                        echo 'selected="selected"';
                                    }
                                ?>  
                                value="<?php echo $q_unit->id;?>">
                                    <?php echo $q_unit->title;?>
                                </option>
                            <?php } ?>
                        </optgroup>
                    </select>
                    <div class="text-danger"><?php echo form_error($input);?></div>
                </div>
        </div>
    </div>

<?php } ?>