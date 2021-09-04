
<style>
    .dropify-wrapper .dropify-message p{
        text-align: center;
    }
</style>
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Emails Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?=$url;?>admin/emails">Emails</a></li>
                <li class="breadcrumb-item active">Edit Email</li>
            </ol>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <?=form_open_multipart('',array('class'=>'form-material','novalidate'=>""));?>
            <div class="card ">
                <?php
                 $sub_ids = "listing";
                    require ("./application/views/backend/common/lang_select.php");
                ?>
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit Email</h4>
                </div>
                <?php foreach($languages as $language){
                    $data = $this->email->get_email_by_lang($language->id,$the_id);
                 ?>
                <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    <?php $input = $language->slug."[name]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Name <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" placeholder="Name" value="<?php if(set_value($input) != ''){ echo set_value($input);} else echo $data->name; ?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    
                    <?php $input = $language->slug."[subject]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Subject <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Name" value="<?php if(set_value($input) != ''){ echo set_value($input);}else{ echo $data->subject;}?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <?php if($language->is_default==1){ ?>
                    <div class="form-group <?=(form_error('code') !='')?'error':'';?>">
                        <h5>E-Code <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="code" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Name" value="<?php if(set_value('code') != ''){ echo set_value('code');}else{ echo $data->code;}?>" >
                            <div class="text-danger"><?php echo form_error('code');?></div>
                        </div>
                    </div>

                    <div class="form-group <?=(form_error('email') !='')?'error':'';?>">
                        <h5>Email <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="email" name="email" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Name" value="<?php if(set_value('email') != ''){ echo set_value('email');}else{ echo $data->email;}?>" >
                            <div class="text-danger"><?php echo form_error('email');?></div>
                        </div>
                    </div>

                    <?php } ?>
                   <?php $input = $language->slug."[content]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Content </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="<?php echo $input; ?>" ><?php if(set_value($input) != ''){ echo set_value($input);}else{ echo $data->content;}?></textarea>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                    <input type="hidden" name="<?php echo $language->slug."[row_id]"; ?>" value="<?php echo $data->id; ?>">

                </div>
            <?php } ?>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>emails" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>