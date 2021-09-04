<script src="<?=$assets;?>tinymce/tinymce.min.js"></script>
<?php $t = time().rand(22992,9292929); ?>
<div class="card" >


    <?php
        $sub_ids = "variations".$t;
    ?>


     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">Custom Variation</h4>

        <button class="btn btn-danger btn-sm pull-right" onclick="removeVariation(this);">Remove</button>
    </div>
   

    <div class="card-body"
    style="
    padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;"
                        >
    <div class="easy row">
        <div class="col-6">
            <div class="form-group">
                <h5>Title (English) (i.e Size):</h5>
                <input type="text" name="c_var_size_en[<?php echo $t; ?>]" class="form-control form-control-line" placeholder="Value" value="<?php echo $c_var_size_en; ?>">
            </div>
        </div>
         <div class="col-6">
            <div class="form-group">
                <h5>Title (Arabic) (i.e Size):</h5>
                <input type="text" name="c_var_size_ar[<?php echo $t; ?>]" class="form-control form-control-line" placeholder="Value" value="<?php echo $c_var_size_ar; ?>">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group" style="position: relative;">
                <h5>Hide From Website: <small style="font-size: 11px;">(Uncheck if don't want to show this option on website)</small></h5>
                 <input <?php if($c_var_status==1) echo "checked"; ?> type="checkbox" name="c_status[<?php echo $t; ?>]" value="1">
            </div>
        </div>
    </div>

    


    <?php
    if(empty($options))

     echo more_option($t,"","",1);
 else{
    foreach($options as $option)
    {
        echo more_option($t,$option->en,$option->ar,$option->price,$option->status,0);
    }
 }

      ?> 


    <div id="add_more_option_in_me<?php echo $t; ?>" class="easy pull-left" style="width: 100%;">
    </div>
    <div class="form-group easy">
        <button type="button" class="btn btn-info btn-sm" onclick="moreOption(this,'<?php echo $t; ?>');">+ Option</button>
    </div>
    


</div>
</div>
