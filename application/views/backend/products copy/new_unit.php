
<div class="card" style="float: left;width: 49%; margin-left: 1px;">
     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">New Region Price</h4>
        <?php if($sticky==false && !is_region()){ ?>
        <button class="btn btn-danger btn-sm pull-right" onclick="removeDescpSection(this);">Remove</button>
        <?php } ?>
    </div>
<div style="padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;">
   
    <div class="easy form-group ">
        <div class="row">
            <div class="col-lg-12 nopad">
                <div class="card">
                    <div class="card-body">
                        <div class="col-6" style="float: left;">
                                <?php $input = "region_id[]"; ?>
                                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                                    <label for="category">Select Region: <span class="text-danger">*</span> </label>
                                    <select class="custom-select form-control required" name="<?php echo $input; ?>">

                                           <?php 
                                           $q_units = $this->db->where("status",1)->where("lparent",0)->where("is_deleted",0)->get("regions")->result_object();
                                           foreach($q_units as $q_unit){


                                            ?>
                                                 <option 

                                                 <?php if(is_region() && region_id()!=$q_unit->id) echo "disabled"; ?>
                                                <?php if($q_unit->id == $this->input->post($input) || $region_id==$q_unit->id){ echo 'selected="selected"';}?>  value="<?php echo $q_unit->id;?>"><?php echo $q_unit->title;?></option>


                                            
                                               
                                            <?php } ?>
                                    </select>
                                    <div class="text-danger"><?php echo form_error($input);?></div>
                                </div>
                            </div>
                            <div class="col-6" style="float: left;">
                               <?php $input = "price[]"; ?>
                                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                                    <h5>Selling Price  <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="$" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $price;?>">
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6" style="float: left;">
                               <?php $input = "cost_price[]"; ?>
                                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                                    <h5>Cost Price  <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="$" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $cost_price;?>">
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6" style="float: left;">
                               <?php $input = "vat[]"; ?>
                                <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                                    <h5>VAT %  <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="number" step="0.1" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="$" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $vat;?>">
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
