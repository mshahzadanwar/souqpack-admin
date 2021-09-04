<div class="easy row"  style="position: relative; padding: 10px; border:1px dashed black; border-radius: 10px; background-color: #f5f5f5; margin-bottom: 10px;">
        <div class="col-6">
<div class="form-group">
    <h5>Option Value (English) (i.e 2KG):</h5>
    <input type="text" name="c_var_option_en[<?php echo $t; ?>][]" class="form-control form-control-line" placeholder="Value" value="<?php echo $value_en; ?>">
    
</div>
</div>
<div class="col-6">
    <div class="form-group" style="position: relative;">
        <h5>Option Value (Arabic) (i.e 2KG):</h5>
        <input type="text" name="c_var_option_ar[<?php echo $t; ?>][]" class="form-control form-control-line" placeholder="Value" value="<?php echo $value_ar; ?>">
        
    </div>
</div>

<div class="col-6">
    <div class="form-group" style="position: relative;">
        <h5>Price:</h5>
        <input type="number" step="0.5" min="0" name="c_var_price[<?php echo $t; ?>][]" class="form-control form-control-line" placeholder="Price" value="<?php echo $value_price; ?>">
        
    </div>
</div>
<div class="col-6">
    <div class="form-group" style="position: relative;">
        <h5>Stock: <small style="font-size: 11px;">(Uncheck if not in stock)</small></h5>
         <input <?php if($active_status==1) echo "checked"; ?> type="checkbox" name="c_var_status[<?php echo $t; ?>][]" value="1">
    </div>
</div>

<?php if($primary!=1){ ?> 
    <button 
    onclick="removeOption(this)" 
    style="position: absolute;right: 0;top:0;
    " 
     type="button" class="btn btn-sm btn-danger"><i class=" fa fa-times"></i></button>
 <?php } ?>
</div>