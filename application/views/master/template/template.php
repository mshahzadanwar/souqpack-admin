<?php if ($this->session->userdata()!="") { ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="description" content="">
    <meta name="author" content="Inlancer">
    

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('resources/uploads/favicon/d1d3e96f5e14c301c972ebe7ab391f1f.png')?>">
    


    <title><?php echo $title; ?></title>
    


    
    <!-- chartist CSS -->
    <link href="<?php echo base_url('resources/backend/morrisjs/morris.css')?>" rel="stylesheet">
    
    <!--Toaster Popup message CSS -->
    <link href="<?php echo base_url('resources/backend/toast-master/css/jquery.toast.css')?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('resources/backend/css/style.min.css')?>" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo base_url('resources/backend/css/pages/dashboard1.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('resources/backend/switchery/dist/switchery.min.css')?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url('resources/backend/dropify/dist/css/dropify.min.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('resources/backend/html5-editor/bootstrap-wysihtml5.css')?>" />
    <link href="<?php echo base_url('resources/backend/bootstrap-datepicker/bootstrap-datepicker.min.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('resources/backend/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('resources/backend/nestable/nestable.css')?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url('resources/backend/jquery/jquery-3.2.1.min.js')?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')?>"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')?>"></script>
    <![endif]-->
    <link href="<?php echo base_url('resources/backend/css/header.css')?>" rel="stylesheet" type="text/css"/>
 
    <?php  echo $this->assets_load->print_assets('header');  ?>  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

</head>

<body class="skin-default fixed-layout">
    
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">
                <img style="width: 150px" src="<?php echo base_url('resources/uploads/logo/dfea7b610b2b320e176ce648ee7f13fb.png')?>">
            </p>
        </div>
    </div>
    


    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">

        <?php echo $header; ?>
        <?php  echo $content; ?>
        <?php echo $footer; ?>   

    </div>



    <script language="javascript">APPLICATION_URL="<?php echo base_url(); ?>"</script>  
    <script> var base_url = '<?php echo base_url(); ?>'; </script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="<?php echo base_url('resources/backend/popper/popper.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/bootstrap/dist/js/bootstrap.min.js')?>"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url('resources/backend/js/perfect-scrollbar.jquery.min.js')?>"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url('resources/backend/js/waves.js')?>"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url('resources/backend/js/sidebarmenu.js')?>"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url('resources/backend/js/custom.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/jquery-sparkline/jquery.sparkline.min.js')?>"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="<?php echo base_url('resources/backend/raphael/raphael-min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/morrisjs/morris.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/jquery-sparkline/jquery.sparkline.min.js')?>"></script>
    <!-- Popup message jquery -->
    <script src="<?php echo base_url('resources/backend/toast-master/js/jquery.toast.js')?>"></script>
    <!-- Chart JS -->
    <script src="<?php echo base_url('resources/backend/js/dashboard1.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/toast-master/js/jquery.toast.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/icheck/icheck.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/js/validations.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/bootstrap-switch/bootstrap-switch.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/switchery/dist/switchery.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/dropify/dist/js/dropify.min.js')?>"></script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo base_url('resources/backend/html5-editor/wysihtml5-0.3.0.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/html5-editor/bootstrap-wysihtml5.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/tinymce/tinymce.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/js/jquery.tinymce.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/moment/min/moment.min.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')?>"></script>
    <script src="<?php echo base_url('resources/backend/bootstrap-datepicker/bootstrap-datepicker.min.js')?>"></script>
      
    <script src="<?php echo base_url('resources/backend/nestable/jquery.nestable.js')?>"></script>
    <!-- <script src="<?php echo base_url('resources/backend/js/brands_listing.js')?>"></script> -->
    <script>
    ! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green"
        }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();
    }(window, document, jQuery);
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
    ! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green"
        }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();
    }(window, document, jQuery);

    $(document).ready(function() { 
        $('.textarea_editor').wysihtml5(); 
    }); 
    </script>
    <div class="push-notification">
    </div>
    <script type="text/javascript">
    function langTab(id, sub_ids) {
        $(".lang_bodies" + sub_ids).hide();
        $(".lang-tabs" + sub_ids).removeClass("active_tab_lang");
        $(".lang-tabs" + sub_ids).find("span").removeClass("active_span_lang");

        $("#lang-tab-" + id).addClass("active_tab_lang");
        $("#lang-tab-" + id).find("span").addClass("active_span_lang");

        $("#lang-" + id).show();
    }
    </script>
    <style type="text/css">
    .active_tab_lang {
        background: #fb9678;
    }

    .active_span_lang {
        color: #fff;
    }
    </style>
    <?php   echo $this->assets_load->print_assets('footer');  ?>



 

</body>

</html> 

 

<?php  } ?>