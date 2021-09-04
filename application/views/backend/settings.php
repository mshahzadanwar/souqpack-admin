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
            <h3 class="text-themecolor m-b-0 m-t-0">Site Settings</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Site Settings</li>
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
            <?=form_open_multipart('',array('class'=>'m-t-40 form-material','novalidate'=>""));?>

            <div class="card card-outline-info">
               <div class="card-header">
                    <h4 class="m-b-0 text-white">Site Settings</h4>
                </div>
                <div class="card-body">

                       	<div class="form-group">
                       	<div class="row">
							
							<div class="col-lg-6 col-md-6">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Logo</h4>
										
										<input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="logo" data-default-file="<?=$root;?>resources/uploads/logo/<?php echo $data->site_logo;?>" />
										
									</div>
								</div>
							</div>
						</div>
						</div>

                        <div class="form-group">
                        <div class="row">
                            
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Logo Small</h4>
                                        
                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="logo_small" data-default-file="<?=$root;?>resources/uploads/logo/<?php echo $data->site_logo_small;?>" />
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                        <div class="row">
                            
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Site Favicon</h4>
                                        
                                        <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="favicon" data-default-file="<?=$root;?>resources/uploads/favicon/<?php echo $data->site_favicon;?>" />
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <h5>Title <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="title" class="form-control" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value('title') != ''){ echo set_value('title');}else{ echo $data->site_title;}?>"> 
                                <div class="text-danger"><?php echo form_error('title');?></div>
                                </div>
                            
                        </div>
                        <div class="form-group">
                            <h5>Title Arabic<span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="title_ar" class="form-control" required data-validation-required-message="This field is required" placeholder="Title Arabic" value="<?php if(set_value('title_ar') != ''){ echo set_value('title_ar');}else{ echo $data->site_title_ar;}?>"> 
                                <div class="text-danger"><?php echo form_error('title_ar');?></div>
                                </div>
                            
                        </div>

                        <div class="form-group">
                            <h5>Meta Title (English) <span class="text-danger">*</span></h5>
                            <div class="controls">
                            <input type="text" name="meta_title_en" class="form-control" required data-validation-required-message="This field is required" placeholder="Meta Title English" value="<?php if(set_value('meta_title_en') != ''){ echo set_value('meta_title_en');}else{ echo $data->meta_title_en;}?>"> 
                            <div class="text-danger"><?php echo form_error('meta_title_en');?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title (Arabic) <span class="text-danger">*</span></h5>
                            <div class="controls">
                            <input type="text" name="meta_title_ar" class="form-control" required data-validation-required-message="This field is required" placeholder="Meta Title English" value="<?php if(set_value('meta_title_ar') != ''){ echo set_value('meta_title_ar');}else{ echo $data->meta_title_ar;}?>"> 
                            <div class="text-danger"><?php echo form_error('meta_title_ar');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Meta Description (English)</h5>
                            <div class="controls">
                                <textarea name="meta_desc_en" id="meta_desc_en" class="form-control"  placeholder="Meta Description English"><?php if(set_value('meta_desc_en') != ''){ echo set_value('meta_desc_en');}else{ echo $data->meta_desc_en;}?></textarea>
                            <div class="text-danger"><?php echo form_error('meta_desc_en');?></div>
                            </div>
                        </div>

                         <div class="form-group">
                            <h5>Meta Description (Arabic)</h5>
                            <div class="controls">
                                <textarea name="meta_desc_ar" id="meta_desc_ar" class="form-control"  placeholder="Meta Description English"><?php if(set_value('meta_desc_ar') != ''){ echo set_value('meta_desc_ar');}else{ echo $data->meta_desc_ar;}?></textarea>
                            <div class="text-danger"><?php echo form_error('meta_desc_ar');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Meta Keywords (English)</h5>
                            <div class="controls">
                                <textarea name="meta_keys_en" id="meta_keys_en" class="form-control"  placeholder="Meta Keywords English"><?php if(set_value('meta_keys_en') != ''){ echo set_value('meta_keys_en');}else{ echo $data->meta_keys_en;}?></textarea>
                            <div class="text-danger"><?php echo form_error('meta_keys_en');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Meta Keywords (Arabic)</h5>
                            <div class="controls">
                                <textarea name="meta_keys_ar" id="meta_keys_ar" class="form-control"  placeholder="Meta Keywords English"><?php if(set_value('meta_keys_ar') != ''){ echo set_value('meta_keys_ar');}else{ echo $data->meta_keys_ar;}?></textarea>
                            <div class="text-danger"><?php echo form_error('meta_keys_ar');?></div>
                            </div>
                        </div>


                         <div class="form-group">
                            <h5>Footer About (English) <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="footer_about" class="form-control" required data-validation-required-message="This field is required" placeholder="Footer About" value="<?php if(set_value('footer_about') != ''){ echo set_value('footer_about');}else{ echo $data->footer_about;}?>"> 
                                <div class="text-danger"><?php echo form_error('footer_about');?></div>
                                </div>
                            
                        </div>
                        <div class="form-group">
                            <h5>Footer About (Arabic)<span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="footer_about_ar" class="form-control" required data-validation-required-message="This field is required" placeholder="Footer About" value="<?php if(set_value('footer_about_ar') != ''){ echo set_value('footer_about_ar');}else{ echo $data->footer_about_ar;}?>"> 
                                <div class="text-danger"><?php echo form_error('footer_about_ar');?></div>
                                </div>
                            
                        </div>


                        <div class="form-group">
                            <h5>Email Address </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Email Address" data-validation-regex-regex="([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})" data-validation-regex-message="Please enter the valid email address." name="email" value="<?php if(set_value('email') != ''){ echo set_value('email');}else{ echo $data->email;}?>"> 
                                <div class="text-danger"><?php echo form_error('email');?></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show Email</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_email" <?php if($data->show_email==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Mobile Number</h5>
                            <div class="controls">
                                <input type="text" name="mobile" class="form-control" required data-validation-required-message="This field is required" placeholder="Mobile Number" value="<?php if(set_value('mobile') != ''){ echo set_value('mobile');}else{ echo $data->mobile;}?>"> 
                                <div class="text-danger"><?php echo form_error('mobile');?></div>
                                </div>
                            
                        </div>
                        <div class="form-group ">
                            <h5>Show Mobile</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_mobile" <?php if($data->show_mobile==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Address</h5>
                            <div class="controls">
                                <textarea name="address" id="address" class="form-control"  placeholder="Address"><?php if(set_value('address') != ''){ echo set_value('address');}else{ echo $data->address;}?></textarea>
                            <div class="text-danger"><?php echo form_error('address');?></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show address</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_address" <?php if($data->show_address==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Copy Rights </h5>
                            <div class="controls">
                                <textarea name="rights" id="rights" class="form-control"  placeholder="Copy Rights"><?php if(set_value('rights') != ''){ echo set_value('rights');}else{ echo $data->copy_right;}?></textarea>
                            <div class="text-danger"><?php echo form_error('rights');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Snapchat URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Snapchat Page URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="snapchat"  value="<?php if(set_value('snapchat') != ''){ echo set_value('snapchat');}else{ echo $data->snapchat;}?>">
                                <div class="text-danger"><?php echo form_error('snapchat');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Instagram URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Instagram Page URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="instagram"  value="<?php if(set_value('instagram') != ''){ echo set_value('instagram');}else{ echo $data->instagram;}?>">
                                <div class="text-danger"><?php echo form_error('instagram');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Facebook URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Facebook Page URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="facebook"  value="<?php if(set_value('facebook') != ''){ echo set_value('facebook');}else{ echo $data->facebook;}?>">
                                <div class="text-danger"><?php echo form_error('facebook');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Twitter URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Twitter URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="twitter"  value="<?php if(set_value('twitter') != ''){ echo set_value('twitter');}else{ echo $data->twitter;}?>">
                                <div class="text-danger"><?php echo form_error('twitter');?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>YouTube URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="YouTube URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="youtube"  value="<?php if(set_value('youtube') != ''){ echo set_value('youtube');}else{ echo $data->youtube;}?>">
                                <div class="text-danger"><?php echo form_error('youtube');?></div>
                            </div>
                        </div>

                        <?php /* ?>

                        <div class="form-group">
                            <h5>Facebook Page URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Facebook Page URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="facebook"  value="<?php if(set_value('facebook') != ''){ echo set_value('facebook');}else{ echo $data->facebook;}?>">
                                <div class="text-danger"><?php echo form_error('facebook');?></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Facebook Page URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Facebook Page URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="facebook"  value="<?php if(set_value('facebook') != ''){ echo set_value('facebook');}else{ echo $data->facebook;}?>">
                                <div class="text-danger"><?php echo form_error('facebook');?></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show facebook</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_fb" <?php if($data->show_fb==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Twitter URL</h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Twitter URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="twitter"  value="<?php if(set_value('twitter') != ''){ echo set_value('twitter');}else{ echo $data->twitter;}?>">
                               <div class="text-danger"><?php echo form_error('twitter');?></div> 
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show Twitter</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_tw" <?php if($data->show_tw==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>LinkedIn URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="LinkedIn URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="linkedin"  value="<?php if(set_value('linkedin') != ''){ echo set_value('linkedin');}else{ echo $data->linkedin;}?>">
                                <div class="text-danger"><?php echo form_error('linkedin');?></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show linkedin</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_li" <?php if($data->show_li==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Google Plus URL </h5>
                            <div class="controls">
                                <input type="text" class="form-control" placeholder="Google Plus URL" data-validation-regex-regex="((http[s]?|ftp[s]?):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*" data-validation-regex-message="Only Valid URL's" name="gplus"  value="<?php if(set_value('gplus') != ''){ echo set_value('gplus');}else{ echo $data->google_plus;}?>">
                                <div class="text-danger"><?php echo form_error('gplus');?></div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <h5>Show Google Plus</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_gp" <?php if($data->show_gp==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Skype ID </h5>
                            <div class="controls">
                                <input type="text" name="skype" class="form-control"  data-validation-required-message="This field is required" placeholder="Skype ID" value="<?php if(set_value('skype') != ''){ echo set_value('skype');}else{ echo $data->skype;}?>"> </div>
                            <div class="text-danger"><?php echo form_error('skype');?></div>
                        </div>
                        <div class="form-group ">
                            <h5>Show Skype</h5>
                            <div class="controls ">
                            <div class="switchery-demo m-b-20">
                                <input  name="show_skype" <?php if($data->show_skype==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                            </div>
                            </div>
                        </div>
                        <?php */ ?>
                        

                        <div class="form-group">
                            <h5 for="shipping_fee">Shipping Free</h5>
                            <input type="number" name="shipping_fee" class="form-control shipping_fee"  placeholder="i.e 50" value="<?php echo $data->shipping_fee; ?>">
                        </div>
                        <div class="form-group">
                            <h5 for="tax">TAX</h5>
                            <input type="number" name="tax" class="form-control tax"  placeholder="i.e 50" value="<?php echo $data->tax; ?>">
                        </div>


                        <div class="form-group">
                            <h5 for="currency">Currency</h5>
                            <input type="text" name="currency" class="form-control currency"  placeholder="Enter Currency Symbol" value="<?php echo $data->currency; ?>">
                        </div>

                        <div class="form-group">
                            <h5 for="currency_ar">Currency in Arabic</h5>
                            <input type="text" name="currency_ar" class="form-control currency_ar"  placeholder="Enter Currency Symbol In Arabic" value="<?php echo $data->currency_ar; ?>">
                        </div>

                        <div class="form-group">
                            <h5 for="currency_position">Currency Position</h5>

                            <label style="float: left;width: 100%;">
                            <input type="radio" name="currency_position" id="currency_position" class=" currency_position_1" <?php if($data->currency_position==1) echo "checked"; ?> placeholder="Enter Currency Position" value="1"> Left
                            </label>

                            <label style="float: left;width: 100%;">
                            <input type="radio" name="currency_position" id="currency_position" class=" currency_position_2" <?php if($data->currency_position==2) echo "checked"; ?>  placeholder="Enter Currency Position" value="2"> Right
                            </label>
                        </div>

                        <div class="form-group">
                            <h5 for="currency_space">Put a space after/before currency?</h5>
                            <div class="controls ">
                                <div class="switchery-demo m-b-20">
                                    <input  name="currency_space" <?php if($data->currency_space==1) echo "checked"; ?> value="1" type="checkbox" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" />
                                </div>
                            </div>
                           
                           
                        </div>
                        
                        
                </div>
            </div>


            <?php echo $meta; ?>



            <div class="text-xs-right">
                <button type="submit" class="btn btn-info">Submit</button>
                <button type="reset" class="btn btn-inverse">Cancel</button>
            </div>
         <?=form_close();?>

         <div class="col-md-12" style="margin-bottom: 20px;"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
