<script src="<?=$assets;?>tinymce/tinymce.min.js"></script>
<?php $t = time(); ?>
<div class="card" >
     <div class="card-header">
        <h4 class="m-b-0 text-white pull-left">Section</h4>

        <button class="btn btn-danger btn-sm pull-right" onclick="removeDescpSection(this);">Remove</button>
    </div>
<div style="padding: 20px; background: #d8d8d8; border:1px dotted #d8d8d8; float: left; width: 100%;">
    <div class="form-group">
        <h5>Title of this section</h5>
        <?php $input_mine = "title_of_section".$m_lang;  ?>
        <input type="text" name="<?php echo $input_mine; ?>[]" class="form-control form-control-line" placeholder="Title" value="<?php echo $ex_des_val; ?>">
    </div>

    <div class="form-group ">
        <h5>Description </h5>
        <div class="controls">
            <?php $input_mine = "description2".$m_lang;  ?>
            <textarea 
            class="mymce<?php echo $t; ?> form-control form-control-line" 
            id="mymce<?php echo $t; ?>" 
            name="<?php echo $input_mine; ?>[]" ><?php echo $ex_des_title; ?></textarea>
            <div class="text-danger"></div>
        </div>
    </div>
</div>
</div>
