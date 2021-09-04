<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">FAQs Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/faqs";?>">FAQs</a></li>
                <li class="breadcrumb-item active">Edit FAQ</li>
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
            <div class="card">
                <?php
                $sub_ids = "listing";
                    require ("./application/views/backend/common/lang_select.php");
                ?>
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Information
                        <a 
                        href="<?php echo base_url()."admin/add-faq?replicate=1" ?>">
                            <button type="button" class="btn btn-inverse btn-sm">
                                Replicate Previous Entry
                            </button>
                        </a>
                    </h4>
                </div>
                <?php foreach($languages as $language){
                    $data = $this->faq->get_faq_by_lang($language->id,$the_id);
                 ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $data->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                     <?php $input = $language->slug."[description]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Answer </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="<?php echo $input; ?>" ><?php if(set_value($input) != ''){ echo set_value($input);}else echo $data->description; ?></textarea>
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
                    <a href="<?=$url;?>admin/faqs" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>