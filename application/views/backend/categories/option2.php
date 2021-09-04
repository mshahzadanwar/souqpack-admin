<script src="<?=$assets;?>tinymce/tinymce.min.js"></script>
<?php $t = time(); ?>
<div class="card" >
     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">Option</h4>
        <?php if(!$sticky){ ?>
            <button class="btn btn-danger btn-sm pull-right" onclick="removeDescpSection(this);">Remove</button>
        <?php } ?>
    </div>
<div style="padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;">
    <div class="col-6" style="float: left;">
        <div class="form-group">
            <h5>Title (en)</h5>
            <?php $input_mine = "title[]";  ?>
            <input type="text" name="<?php echo $input_mine; ?>" class="form-control form-control-line" placeholder="Title" value="<?php echo $title; ?>">
        </div>
        <div class="form-group">
            <h5>Values (en)</h5>
            <?php $input_mine = "values[]";  ?>
            <textarea name="<?php echo $input_mine; ?>" placeholder="One per line" class="form-control" rows="5"><?php echo $values; ?></textarea>
        </div>
        <div class="form-group">
            <h5>Disabled?</h5>
            <?php $input_mine = "disabled[]";  ?>
            <select name="<?php echo $input_mine; ?>" class="form-control">
                <option <?php if($disabled!=1) echo "selected"; ?> value="0">No</option>
                <option <?php if($disabled==1) echo "selected"; ?> value="1">Yes</option>
            </select>
        </div>
        <div class="form-group">
            <h5>Price (<?php echo get_currency(); ?>)</h5>
            <?php $input_mine = "price[]";  ?>
            <input type="number" step="0.1" name="<?php echo $input_mine; ?>" placeholder="Price (<?php echo get_currency(); ?>)" class="form-control" value="<?php echo $price; ?>" />
        </div>
    </div>
    <div class="col-6" style="float: left;">
        <div class="form-group">
            <h5>Title (ar)</h5>
            <?php $input_mine = "title_ar[]";  ?>
            <input type="text" name="<?php echo $input_mine; ?>" class="form-control form-control-line" placeholder="Title" value="<?php echo $title_ar; ?>">
        </div>
        <div class="form-group">
            <h5>Values (en)</h5>
            <?php $input_mine = "values_ar[]";  ?>
            <textarea name="<?php echo $input_mine; ?>" placeholder="One per line" class="form-control" rows="5"><?php echo $values_ar; ?></textarea>
        </div>
    </div>
</div>
</div>
