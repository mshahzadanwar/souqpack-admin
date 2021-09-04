

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Pages Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/pages";?>">Pages</a></li>
                <li class="breadcrumb-item active">Add New page</li>
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
                        href="<?php echo base_url()."admin/add-page?replicate=1" ?>">
                            <button type="button" class="btn btn-inverse btn-sm">
                                Replicate Previous Entry
                            </button>
                        </a>
                    </h4>
                </div>
                <?php foreach($languages as $language){ ?>
                 <div class="card-body lang_bodieslisting" id="lang-<?php echo $language->id; ?>listing"
                    style="display: <?php echo $language->id==$active?"":"none"; ?>;"
                    >
                    
                    <?php $input = $language->slug."[title]"; ?>
                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="<?php echo $input; ?>" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value($input) != ''){ echo set_value($input);}else echo $prev->title;?>" >
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>

                    </div>

                    <?php if($language->is_default==1 && 2==3){ ?>

                    <div class="form-group <?=(form_error('slug') !='')?'error':'';?>">
                        <h5>Slug <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="slug" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="slug" value="<?php if(set_value('slug') != ''){ echo set_value('slug');}?>">
                            <div class="text-danger"><?php echo form_error('slug');?></div>
                        </div>

                    </div>

                    <?php } ?>
                    <?php $input = $language->slug."[descriptions]"; ?>

                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <h5>Content </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="<?php echo $input; ?>" ><?php if(set_value($input) != ''){ echo set_value($input);}?></textarea>
                            <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>

                    <?php $input = $language->slug."_image"; ?>

                    <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 nopad">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Banner Image</h5>

                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" />
                                        <div class="text-danger"><?php echo form_error($input);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php $input = $language->slug."[meta_title]"; ?>
                 <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Title </h5>
                    <div class="controls">
                    <?php 
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Title',
                        'class' => 'form-control',
                        'value'=>set_value($input)

                    );
                    echo form_input($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                </div>

                    <?php $input = $language->slug."[meta_keys]"; ?>

                 <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Keywords </h5>
                    <div class="controls">
                    <?php
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Keywords',
                        'class' => 'form-control',
                        'value'=>set_value($input),
                        'rows'    => '3',
                    );
                    echo form_textarea($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                    </div>
                </div>


                 <?php $input = $language->slug."[meta_desc]"; ?>
               <div class="form-group <?=(form_error($input) !='')?'error':'';?>">
                    <h5>Meta Description </h5>
                    <div class="controls">
                    <?php
                    
                    $data1= array(
                        'name' => $input,
                        'type' => 'text',
                        'placeholder' => 'Meta Descriptions',
                        'class' => 'form-control',
                        'id'    => 'exampleTextarea',
                        'rows'    => '3',
                        'value'=>set_value($input)
                    );
                    echo form_textarea($data1);
                    ?>
                        <div class="text-danger"><?php echo form_error($input);?></div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/pages" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>