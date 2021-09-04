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
	.valid_class {
		text-align: center;
    color: #11AB00;
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
    <title><?php echo settings()->site_title; ?></title>
    
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
                echo form_open("",$attr);?>
                <form class="form-horizontal form-material" id="loginform" autocomplete="off">
                   <div class="center">
                    <div class="text-center db">
                      <?php /*?><img src="<?php echo base_url(); ?>resources/backend/images/logo-text.png" alt="<?php echo settings()->site_title; ?>" /><?php */?>
                       <h2><strong>VENDOR PORTAL</strong></h2>
                        </div>
                    </div>
                    
                    
                    <div class="form-group m-t-40  <?=(form_error('password') !='')?'error':'';?>">
                       <?php 
					if(isset($_SESSION["invalid"])){
						echo "<span class='invalid_class'>".$_SESSION["invalid"]."</span>";
						unset($_SESSION["invalid"]);
					}
						if(isset($_SESSION["valid"])){
						echo "<span class='valid_class'>".$_SESSION["valid"]."</span>";
						unset($_SESSION["valid"]);
					}
				?>
                        
                            <div class="col-xs-12">
                                <?php
                                
                                $data = array(
                                    'name' => 'password',
                                    'id' => 'button',
                                    'placeholder' => 'New Password',
                                    'type' => 'password',
                                    'class' => 'form-control',
                                "id"=>"checkpassword"

                                );

                                echo form_input($data);
                            ?>
                            <?php if(form_error('password') != ''){?>
                            <div class="help-block"><ul role="alert"><li><?php echo form_error('password'); ?></li></ul></div>
                            <?php }?>
                            </div>
                            <div id="pswd_info">
                            <h4>Password must meet the following requirements:</h4>
                            <ul>
                                <li id="letter" class="invalid">At least <strong>one letter</strong></li>
                                <li id="capital" class="invalid">At least <strong>one capital letter</strong></li>
                                <li id="number" class="invalid">At least <strong>one number</strong></li>
                                <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
                            </ul>
                        </div>
                        </div>
                    <div class="form-group  <?=(form_error('cpassword') !='')?'error':'';?>">
                        <div class="col-xs-12">
                            <?php
                            
                            $data = array(
                                'name' => 'cpassword',
                                'id' => 'button',
                                'placeholder' => 'Confirm Password',
                                'type' => 'password',
                                'class' => 'form-control',
                            );

                            echo form_input($data);
                        ?>
                        <?php if(form_error('cpassword') != ''){?>
                        <div class="help-block"><ul role="alert"><li><?php echo form_error('cpassword'); ?></li></ul></div>
                        <?php }?>
                        </div>
                    
                   
                    
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                        <?php    $data = array(
                            'name' => 'submit',
                            'id' => 'button',
                            'value' => 'Reset',
                            'type' => 'submit',
                            'content' => 'Reset',
                            'class' => 'btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light btn_custom'
                        );

                        echo form_button($data);
                        ?>
                        </div>
                        
                        <div class="form-group text-center m-t-20 right">
                        	<a href="<?php echo base_url();?>admin/vendorlogin/signup">
                        		<strong>SIGN UP AS NEW VENDOR!</strong>
                        	</a>
					   </div>
                       <div class="form-group text-center m-t-20 right">
                            <a href="<?php echo base_url();?>admin/vendorlogin">
                                <strong>BACK TO LOGIN</strong>
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
    <script type="text/javascript">
    $(document).ready(function() {
    $('#checkpassword').keyup(function() {
        var pswd = $(this).val();
        
        if ( pswd.length < 8 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
        }
        
        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }
        
    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });
});

</script>
<style type="text/css">
    #pswd_info {
    position:absolute;
    width: 92%;
    padding: 15px;
    background: #ffffff;
    font-size: .875em;
    border-radius: 5px;
    box-shadow: 0 1px 3px #ccc;
    border: 1px solid #adadad;
    z-index: 1;
}#pswd_info h4 {
    margin:0 0 10px 0;
    padding:0;
    font-weight:normal;
}#pswd_info::before {
    content: "\25B2";
    position:absolute;
    top:-12px;
    left:45%;
    font-size:14px;
    line-height:14px;
    color:#ddd;
    text-shadow:none;
    display:block;
}
#pswd_info {
    display:none;
    z-index: 59999;
}
.invalid {
    background:url(<?php echo base_url(); ?>/resources/frontend/images/invalid.png) no-repeat 0 50%;
    padding-left:22px;
    line-height:24px;
    color:#ec3f41;
}
.valid {
    background:url(<?php echo base_url(); ?>/resources/frontend/images/valid.png) no-repeat 0 50%;
    padding-left:22px;
    line-height:24px;
    color:#3a7d34;
}
</style>
</body>

</html>