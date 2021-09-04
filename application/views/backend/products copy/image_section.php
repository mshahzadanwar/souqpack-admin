
<div class="card" style="float: left;width: 49%; margin-left: 1px;">
     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">New Image</h4>

        <button class="btn btn-danger btn-sm pull-right" onclick="removeDescpSection(this);">Remove</button>
    </div>
<div style="padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;">
   
    <div class="easy form-group ">
        <div class="row">
            <div class="col-lg-12 nopad">
                <div class="card">
                    <div class="card-body">
                        <h5>Image</h5>
                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" data-default-file="<?php if($more_image_now!=""){

                                            echo base_url()."resources/uploads/products/".$more_image_now;
                                        } ?>" name="image_more<?php echo $m_lang; ?>[]" />
                       <input type="hidden" name="def_more<?php echo $m_lang; ?>[]" value="<?php echo $more_image_now; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
