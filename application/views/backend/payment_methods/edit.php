
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
            <h3 class="text-themecolor m-b-0 m-t-0">Payment Methods Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?=$url;?>admin/payment-methods">payment-methods</a></li>
                <li class="breadcrumb-item active">Edit Payment Method</li>
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
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit Payment Method</h4>
                </div>
                <div class="card-body">

                    <div class="form-group <?=(form_error('title') !='')?'error':'';?>">
                        <h5>Title <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" name="title" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Title" value="<?php if(set_value('title') != ''){ echo set_value('title');}else{ echo $data->title;}?>" pattern="[a-zA-Z0-9 ]+" data-validation-pattern-message="Only alphabet and numbers are allowed.">
                            <div class="text-danger"><?php echo form_error('title');?></div>
                        </div>

                    </div>

                    <?php if($data->type==1){ ?>


                        <div class="form-group <?=(form_error('paypal_email') !='')?'error':'';?>">
                            <h5>PayPal Email <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="email" name="paypal_email" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="PayPal Email" value="<?php if(set_value('paypal_email') != ''){ echo set_value('paypal_email');}else{ echo $data->paypal_email;}?>" >
                                <div class="text-danger"><?php echo form_error('paypal_email');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('paypal_api') !='')?'error':'';?>">
                            <h5>PayPal API <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="paypal_api" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="PayPal API" value="<?php if(set_value('paypal_api') != ''){ echo set_value('paypal_api');}else{ echo $data->paypal_api;}?>" >
                                <div class="text-danger"><?php echo form_error('paypal_api');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('paypal_secret') !='')?'error':'';?>">
                            <h5>PayPal Secret <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="paypal_secret" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="PayPal Secret" value="<?php if(set_value('paypal_secret') != ''){ echo set_value('paypal_secret');}else{ echo $data->paypal_secret;}?>" >
                                <div class="text-danger"><?php echo form_error('paypal_secret');?></div>
                            </div>

                        </div>


                        <div class="form-group <?=(form_error('paypal_secret') !='')?'error':'';?>">
                            <h5>PayPal API Type <span class="text-danger">*</span></h5>
                            <label>
                                <input <?php if($data->paypal_api_type==1) echo "checked"; ?> type="radio" value="1" name="paypal_api_type"> Classic
                            </label>

                            <label>
                                <input <?php if($data->paypal_api_type==2) echo "checked"; ?> type="radio" value="2" name="paypal_api_type"> Soup
                            </label>

                        </div>



                    <?php } ?>

                    <?php if($data->type==2){ ?>

                        <div class="form-group <?=(form_error('stripe_api') !='')?'error':'';?>">
                            <h5>Stripe API <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="stripe_api" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Stripe API" value="<?php if(set_value('stripe_api') != ''){ echo set_value('stripe_api');}else{ echo $data->stripe_api;}?>" >
                                <div class="text-danger"><?php echo form_error('stripe_api');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('stripe_secret') !='')?'error':'';?>">
                            <h5>Stripe Secret <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="stripe_secret" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Stripe Secret" value="<?php if(set_value('stripe_secret') != ''){ echo set_value('stripe_secret');}else{ echo $data->stripe_secret;}?>" >
                                <div class="text-danger"><?php echo form_error('stripe_secret');?></div>
                            </div>


                    <?php } ?>

                    <?php if($data->type==6){ ?>
                        <div class="form-group <?=(form_error('company_name') !='')?'error':'';?>">
                            <h5>Company Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="company_name" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="company Name" value="<?php if(set_value('company_name') != ''){ echo set_value('company_name');}else{ echo $data->company_name;}?>" >
                                <div class="text-danger"><?php echo form_error('company_name');?></div>
                            </div>
                        </div>


                        <div class="form-group <?=(form_error('bank_name') !='')?'error':'';?>">
                            <h5>Bank Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="bank_name" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="Bank Name" value="<?php if(set_value('bank_name') != ''){ echo set_value('bank_name');}else{ echo $data->bank_name;}?>" >
                                <div class="text-danger"><?php echo form_error('bank_name');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('iban') !='')?'error':'';?>">
                            <h5>IBAN <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="iban" class="form-control form-control-line" required data-validation-required-message="This field is required" placeholder="IBAN" value="<?php if(set_value('iban') != ''){ echo set_value('iban');}else{ echo $data->iban;}?>" >
                                <div class="text-danger"><?php echo form_error('iban');?></div>
                            </div>
                        </div>

                        <hr style="    float: left;
    clear: both;
    width: 100%;
    border-top: 3px dotted #4f5467;">

                        <div class="form-group <?=(form_error('bank_name_2') !='')?'error':'';?>">
                            <h5>Bank Name 2</h5>
                            <div class="controls">
                                <input type="text" name="bank_name_2" class="form-control form-control-line" placeholder="Bank Name 2" value="<?php if(set_value('bank_name_2') != ''){ echo set_value('bank_name_2');}else{ echo $data->bank_name_2;}?>" >
                                <div class="text-danger"><?php echo form_error('bank_name_2');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('iban_2') !='')?'error':'';?>">
                            <h5>IBAN (Bank 2) </h5>
                            <div class="controls">
                                <input type="text" name="iban_2" class="form-control form-control-line" placeholder="IBAN 2" value="<?php if(set_value('iban_2') != ''){ echo set_value('iban_2');}else{ echo $data->iban_2;}?>" >
                                <div class="text-danger"><?php echo form_error('iban_2');?></div>
                            </div>
                        </div>

                    <hr style="    float: left;
                        clear: both;
                        width: 100%;
                        border-top: 3px dotted #4f5467;">
                        <div class="form-group <?=(form_error('bank_name_3') !='')?'error':'';?>">
                            <h5>Bank Name 3</h5>
                            <div class="controls">
                                <input type="text" name="bank_name_3" class="form-control form-control-line" placeholder="Bank Name 2" value="<?php if(set_value('bank_name_3') != ''){ echo set_value('bank_name_3');}else{ echo $data->bank_name_3;}?>" >
                                <div class="text-danger"><?php echo form_error('bank_name_3');?></div>
                            </div>

                        </div>

                        <div class="form-group <?=(form_error('iban_3') !='')?'error':'';?>">
                            <h5>IBAN (Bank 3) </h5>
                            <div class="controls">
                                <input type="text" name="iban_3" class="form-control form-control-line" placeholder="IBAN 3" value="<?php if(set_value('iban_3') != ''){ echo set_value('iban_3');}else{ echo $data->iban_3;}?>" >
                                <div class="text-danger"><?php echo form_error('iban_3');?></div>
                            </div>
                        </div>
                        
                    <?php } ?>

                    <?php if($data->type==7 || $data->type==5){ ?>

                   
                    <div class="form-group <?=(form_error('description') !='')?'error':'';?>">
                        <h5>Description </h5>
                        <div class="controls">
                            <textarea class="mymce form-control form-control-line" id="mymce" name="description" ><?php if(set_value('description') != ''){ echo set_value('description');}else{ echo $data->description;}?></textarea>
                            <div class="text-danger"><?php echo form_error('description');?></div>
                        </div>
                    </div>
                    <?php } ?>


                </div>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/payment-methods" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>