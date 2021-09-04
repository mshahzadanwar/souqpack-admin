
<div class="" style="float: left;width: 49%; margin-left: 4px;    display: flex;
    flex-direction: column;">
     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">New Image</h4>

        <button class="btn btn-danger btn-sm pull-right" onclick="removeMoreImage(this,<?php echo $more_id;?>);">Remove</button>
    </div>
<div style="padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;">
   
    <div class="easy">
        <div class="row">
            <div class="col-lg-12 nopad">
                <div class="card">
                    <div class="card-body">
                    
                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" data-default-file="<?php if($more_image_now!=""){

                                            echo base_url()."resources/uploads/categories/".$more_image_now;
                                        } ?>" name="image_more_<?php echo $_POST['var_id']?$_POST['var_id']:$var_id;?>[]" />
                       <input type="hidden" name="def_more_<?php echo $var_id;?>[]" value="<?php echo $more_image_now; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
