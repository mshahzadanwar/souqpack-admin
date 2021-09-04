<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>resources/uploads/favicon/<?php echo $settings->site_favicon; ?>">
    <title><?php echo $title; ?> <?php echo $title?" - ":""; ?><?php
    echo $settings->site_title; echo ' | Admin';
     ?></title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="<?php echo base_url(); ?>resources/backend/morrisjs/morris.css" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="<?php echo base_url(); ?>resources/backend/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>resources/backend/css/style.min.css?time=<?php echo time();?>" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo base_url(); ?>resources/backend/css/pages/dashboard1.css" rel="stylesheet">

    <link href="<?=$assets;?>select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

    <link href="<?=$assets;?>sweetalert/sweetalert.css" rel="stylesheet" type="text/css">

    <link href="<?=$assets;?>switchery/dist/switchery.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?=$assets;?>dropify/dist/css/dropify.min.css">
    <link rel="stylesheet" href="<?=$assets;?>html5-editor/bootstrap-wysihtml5.css" />


    <link href="<?=$assets;?>bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=$assets;?>bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">


    <link href="<?=$assets;?>nestable/nestable.css" rel="stylesheet" type="text/css" />
    <?php if($this->uri->segment(2) != "add-product" && $this->uri->segment(2) != "edit-product"  && $this->uri->segment(2) != "seo"){?>
    <script src="<?php echo base_url(); ?>resources/backend/jquery/jquery-3.2.1.min.js"></script>
    <?php } else {?> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
    <?php } ?>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
    .card .card-header{
  background-color:#fb9678 !important;
}
.footer{
    margin-left: 0px;
}
.nopad{
    padding: 0px !important;
}
.page-titles{
    margin-top:20px !important;
}
.easy,.form-group{
    float: left;width: 100%;
}
.filter_by {    float: right;
    clear: both;
    display: flex;
    width: 100%;
    flex-direction: inherit;
    justify-content: flex-end;
    margin-bottom: 12px;
    align-items: flex-end;}
.card {clear: both;}
.filter_by label {float: right; margin-right: 20px;}
.filter_by span {float: right;}
.table-responsive { margin-top: 0 !important; }
.card-title {text-transform: uppercase; font-weight: bold;}
</style>
</head>

<body class="skin-default fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">
<img style="width: 150px" src="<?php echo base_url(); ?>resources/uploads/logo/<?php echo $settings->site_logo; ?>">
           </p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php $this->load->view('backend/common/header');?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php $this->load->view('backend/common/sidebar');?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <?=$content;?>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php $this->load->view('backend/common/footer');?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
	<script>
		var base_url = '<?php echo $url;?>';
	</script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="<?php echo base_url(); ?>resources/backend/popper/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>resources/backend/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url(); ?>resources/backend/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>resources/backend/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url(); ?>resources/backend/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url(); ?>resources/backend/js/custom.js"></script>
    <script src="<?php echo base_url(); ?>resources/backend/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="<?php echo base_url(); ?>resources/backend/raphael/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>resources/backend/morrisjs/morris.min.js"></script>
    <script src="<?php echo base_url(); ?>resources/backend/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Popup message jquery -->
    <script src="<?php echo base_url(); ?>resources/backend/toast-master/js/jquery.toast.js"></script>
    <!-- Chart JS -->
    <script src="<?php echo base_url(); ?>resources/backend/js/dashboard1.js"></script>
    <script src="<?php echo base_url(); ?>resources/backend/toast-master/js/jquery.toast.js"></script>
    <script src="<?=$assets;?>icheck/icheck.min.js"></script>
    <script src="<?=$assets;?>bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <script src="<?=$assets;?>js/validations.js"></script>
    <script src="<?=$assets;?>bootstrap-switch/bootstrap-switch.min.js"></script>
    <script src="<?=$assets;?>switchery/dist/switchery.min.js"></script>
    <script src="<?=$assets;?>dropify/dist/js/dropify.min.js"></script>

    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?=$assets;?>html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="<?=$assets;?>html5-editor/bootstrap-wysihtml5.js"></script>
    <script src="<?=$assets;?>tinymce/tinymce.min.js"></script>
    <script src="<?=$assets;?>moment/min/moment.min.js"></script>
    <script src="<?=$assets;?>bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?=$assets;?>bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

     <?php if($js == 'listing' || $js == 'dashboard'){?>
     <!-- This is data table -->
    <script src="<?=$assets;?>datatables/jquery.dataTables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script> -->
    <!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script> -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <?php } ?>

    <script src="<?=$assets;?>select2/dist/js/select2.full.min.js" type="text/javascript"></script>

    <script src="<?=$assets;?>sweetalert/sweetalert.min.js"></script>
    <script src="<?=$assets;?>sweetalert/jquery.sweet-alert.custom.js"></script>

    <script src="<?=$assets;?>nestable/jquery.nestable.js"></script>

    <?php if($jsfile != ''){?>
     <script src="<?=$assets;?><?php echo $jsfile;?>.js?time=<?php echo time();?>"></script>
    <?php } ?>
    <script>
	
         <?php if($dont_you_dare_validate != 55){ ?>
	
	
		! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green"
        }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();
    }(window, document, jQuery);
<?php } ?>
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
         <?php if($dont_you_dare_validate != 55){ ?>
		! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green"
        }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();
    }(window, document, jQuery);

<?php } ?>
    $(document).ready(function() {

        $('.textarea_editor').wysihtml5();


    });
	<?php if($js == 'listing'){?>
			$(document).ready(function() {
				$('#myTable').DataTable();
				$(document).ready(function() {
					var table = $('#example').DataTable({
						"columnDefs": [{
							"visible": false,
							"targets": 2
						}],
						"order": [
							[2, 'asc']
						],
						"displayLength": 25,
						"drawCallback": function(settings) {
							var api = this.api();
							var rows = api.rows({
								page: 'current'
							}).nodes();
							var last = null;
							api.column(2, {
								page: 'current'
							}).data().each(function(group, i) {
								if (last !== group) {
									$(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
									last = group;
								}
							});
						}
					});
					// Order by the grouping
					$('#example tbody').on('click', 'tr.group', function() {
						var currentOrder = table.order()[0];
						if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
							table.order([2, 'desc']).draw();
						} else {
							table.order([2, 'asc']).draw();
						}
					});
				});
			});
			
	<?php } ?>
	</script>
	
	<?php if($this->session->flashdata('msg') !=''){ ?>
	<script>
	$(function(){
		$.toast({
            heading: 'Success',
            text: '<?php echo $this->session->flashdata('msg');?>',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3500, 
            stack: 6
          });
	});
	</script>
	<?php } ?>
	<?php if($this->session->flashdata('err') !=''){ ?>
	<script>
	$(function(){
		
		$.toast({
            heading: 'Error',
            text: '<?php echo $this->session->flashdata('err');?>',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'error',
            hideAfter: 3500
            
          });
		
	});
	</script>
	<?php } ?>
	<?php if($this->session->flashdata('inf') !=''){ ?>
	<script>
	$(function(){
		
		 $.toast({
            heading: 'Info',
            text: '<?php echo $this->session->flashdata('inf');?>',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'info',
            hideAfter: 3000, 
            stack: 6
          });
		
	});
	</script>
	<?php } ?>
	<?php if($this->session->flashdata('war') !=''){ ?>
	<script>
	$(function(){
		
		 $.toast({
            heading: 'Warning',
            text: '<?php echo $this->session->flashdata('war');?>',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'warning',
            hideAfter: 3500, 
            stack: 6
          });
		
	});
	</script>
	<?php } ?>
	<div class="push-notification">
		
	</div>
    <script type="text/javascript">
        function langTab(id,sub_ids)
        {
            $(".lang_bodies"+sub_ids).hide();
            $(".lang-tabs"+sub_ids).removeClass("active_tab_lang");
            $(".lang-tabs"+sub_ids).find("span").removeClass("active_span_lang");

            $("#lang-tab-"+id).addClass("active_tab_lang");
            $("#lang-tab-"+id).find("span").addClass("active_span_lang");

            $("#lang-"+id).show();
        }
    </script>
    <style type="text/css">
        .active_tab_lang{
            background: #fb9678;
        }
        .active_span_lang{
            color:#fff;
        }
    </style>

<?php if($this->uri->segment(2) == "add-product" || $this->uri->segment(2) == "edit-product"  || $this->uri->segment(2) == "seo"){?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript">
<?php 
    $purchases = $this->db->query("SELECT title FROM purchases WHERE status = 1 AND lang_id = 2")->result_object();
    foreach($purchases as $pur){
        $pur_tags .= '"'.$pur->title.'",';
    }
    $pur_tags = substr($pur_tags, 0,-1);

?>
$(function() {
  var availableTags = [<?php echo $pur_tags;?>];
  
  $(".autocomplete_english").autocomplete({
    source: availableTags,
    select: function (event, ui) { 
       $.ajax({
          type: 'POST',
          url: '<?php echo base_url();?>admin/products/get_value_auto_select',  //whatever any url
          data: {label: ui.item.label},
          success: function(message) { 
                if(message.action == "success"){
                    $(".sku_class").val(message.sku);
                    $(".autocomplete_arabic").val(message.arabic_title);
                    $(".min_or_qty").val(message.min_qty);
                } 
            },
          dataType: 'json'
       });

    }
  });
});

$(function() {
  var availableTags_SEO = ["NEVER","YEARLY","MONTHLY","WEEKLY","DAILY","HOURLY","ALWAYS"];
  $(".base_freq,.cat_freq,.pro_freq,.page_freq,.cart_freq,.wish_freq,.log_freq,.sign_freq").autocomplete({
    source: availableTags_SEO,
  });
});

</script>
<style type="text/css">
.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.ui-autocomplete > li {
    padding: 5px 20px;
    cursor: pointer;
}
.ui-autocomplete > li.ui-state-focus {
    background-color: #DDD;
    cursor: pointer;
}
.ui-helper-hidden-accessible {
    display: none;
}
</style>
<?php } ?>
</body>

</html>