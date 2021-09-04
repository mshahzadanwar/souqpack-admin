<!DOCTYPE html>
<html lang="en">
<style>
	.invalid_class {
		text-align: center;
    color: #f00;
    float: left;
    width: 100%;
    margin-bottom: 10px;
	}
</style>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $assets; ?>images/favicon.png">
    <title>SIGNUP - <?php echo settings()->site_title; ?></title>
    
    <!-- page css -->
    <link href="<?php echo $assets; ?>css/pages/login-register-lock.css?time=<?php echo time();?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $assets; ?>css/style.min.css" rel="stylesheet">
    
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php echo $title; ?></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="login-register login-sidebar" style="background-image:url(<?php echo $assets; ?>images/background/login-register.jpg);">
        <div class="login-box card">
            <div class="card-body">
               
               	
                <?php 
                $attr = array('class'=>'form-horizontal form-material','id'=>'loginform');
                
                if(form_error('rest_email')!=''){
                    
                    $attr['style'] = 'display:none;';
                    
                }
                echo form_open(base_url().'admin/vendorlogin/signup_user',$attr);?>
                <form class="form-horizontal form-material" id="loginform" autocomplete="off">
                   <div class="center">
                    <a href="javascript:void(0)" class="text-center db">
                        
                        <img src="<?php echo base_url(); ?>resources/backend/images/logo-text.png" alt="<?php echo settings()->site_title; ?>" /></a>
                    </div>
                    
                    
                    <div class="form-group m-t-40  <?=(form_error('email') !='')?'error':'';?>">
						<?php 
							if(validation_errors()){
								echo "<span class='invalid_class'>".validation_errors()."</span>";
							}
						?>
                     <div class="col-xs-12">
                            <?php
                            
                            $data = array(
                                'name' => 'name',
                                'id' => 'button',
                                'placeholder' => 'Full Name',
                                'type' => 'text',
                                'class' => 'form-control',
                                "required"=>true,
								
                                'value' => set_value('name')
                            );

                            echo form_input($data);
                        ?>
                        
                        </div>
                        
                        
                        
                        <div class="col-xs-12 m-t-20">
                            <?php
                            
                            $data = array(
                                'name' => 'email',
                                'id' => 'button',
                                'placeholder' => 'Email',
                                'type' => 'email',
                                'class' => 'form-control',
                                "required"=>true,
								
                                'value' => set_value('email')
                            );

                            echo form_input($data);
                        ?>
                        
                        </div>
                        <div class="col-xs-12 m-t-20">
                            <?php
                            
                            $data = array(
                                'name' => 'phone',
                                'id' => 'button',
                                'placeholder' => 'Phone',
                                'type' => 'text',
                                'class' => 'form-control',
                                "required"=>true,
                                
                                'value' => set_value('phone')
                            );

                            echo form_input($data);
                        ?>
                        
                        </div>
                    </div>
                    <div class="form-group  <?=(form_error('password') !='')?'error':'';?>">
                        <div class="col-xs-12">
                            <?php
                            
                            $data = array(
                                'name' => 'password',
                                'id' => 'button',
                                'placeholder' => 'Password',
                                "required"=>true,
                                'type' => 'password',
                                'class' => 'form-control'
                            );

                            echo form_input($data);
                        ?>
                       
                        </div>
                    </div>
                   
                    
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                        <?php    $data = array(
                            'name' => 'submit',
                            'id' => 'button',
                            'value' => 'SIGNUP',
                            'type' => 'submit',
                            'content' => 'SIGNUP',
                            'class' => 'btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light btn_custom'
                        );

                        echo form_button($data);
                        ?>
                        </div>
                        
                        <div class="form-group text-center m-t-20 right">
                    	<a href="<?php echo base_url()?>admin/vendorlogin">
                    		Login Now!
                    	</a>
					</div>
                    </div>
                   
                </form>

                <?php echo form_close(); ?>
             

            </div>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo $assets; ?>jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo $assets; ?>popper/popper.min.js"></script>
    <script src="<?php echo $assets; ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
    </script>
    
</body>

</html>