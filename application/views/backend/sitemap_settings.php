<style>
	  .dropify-wrapper .dropify-message p{
		  text-align: center;
	  }
      .seo .col-md-6 {
        float: left;
      }
 </style>
   <div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">SiteMap Settings</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item active">SiteMap Settings</li>
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
        <div class="col-12 seo">
            <?=form_open_multipart(base_url().'admin/seo/update_seo',array('class'=>'m-t-40 form-material','novalidate'=>""));?>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Main URL (Souqpack.com)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="base_freq" class="form-control base_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('base_freq') != ''){ echo set_value('base_freq');}else{ echo $data->base_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('base_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="base_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('base_pr') != ''){ echo set_value('base_pr');}else{ echo $data->base_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('base_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Categories URL(s)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="cat_freq" class="form-control cat_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('cat_freq') != ''){ echo set_value('cat_freq');}else{ echo $data->cat_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('cat_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="cat_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('cat_pr') != ''){ echo set_value('cat_pr');}else{ echo $data->cat_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('cat_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Products URL(s)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="pro_freq" class="form-control pro_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('pro_freq') != ''){ echo set_value('pro_freq');}else{ echo $data->pro_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('pro_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="pro_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('pro_pr') != ''){ echo set_value('pro_pr');}else{ echo $data->pro_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('pro_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

             <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Pages URL(s)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="page_freq" class="form-control page_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('page_freq') != ''){ echo set_value('page_freq');}else{ echo $data->page_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('page_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="page_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('page_pr') != ''){ echo set_value('page_pr');}else{ echo $data->page_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('page_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Cart URL(s)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="cart_freq" class="form-control cart_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('cart_freq') != ''){ echo set_value('cart_freq');}else{ echo $data->cart_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('cart_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="cart_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('cart_pr') != ''){ echo set_value('cart_pr');}else{ echo $data->cart_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('cart_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">WishList URL(s)</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="wish_freq" class="form-control wish_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('wish_freq') != ''){ echo set_value('wish_freq');}else{ echo $data->wish_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('wish_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="wish_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('wish_pr') != ''){ echo set_value('wish_pr');}else{ echo $data->wish_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('wish_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Login URL</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="log_freq" class="form-control log_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('log_freq') != ''){ echo set_value('log_freq');}else{ echo $data->log_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('log_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="log_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('log_pr') != ''){ echo set_value('log_pr');}else{ echo $data->log_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('log_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline-info">
                   <div class="card-header">
                        <h4 class="m-b-0 text-white">Signup URL</h4>
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <h5>Frequency <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="sign_freq" class="form-control sign_freq" required data-validation-required-message="This field is required" placeholder="frequency" value="<?php if(set_value('sign_freq') != ''){ echo set_value('sign_freq');}else{ echo $data->sign_freq;}?>"> 
                                    <div class="text-danger"><?php echo form_error('sign_freq');?></div>
                                    </div>
                                
                            </div>
                            <div class="form-group">
                                <h5>priority<span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" name="sign_pr" class="form-control" required data-validation-required-message="This field is required" placeholder="Priority" value="<?php if(set_value('sign_pr') != ''){ echo set_value('sign_pr');}else{ echo $data->sign_pr;}?>"> 
                                    <div class="text-danger"><?php echo form_error('sign_pr');?></div>
                                    </div>
                            </div>

                    </div>
                </div>
            </div>

            <div class="text-xs-right col-md-6" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-info">Submit</button>
            </div>
         <?=form_close();?>

         <div class="col-md-12" style="margin-bottom: 20px;"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
