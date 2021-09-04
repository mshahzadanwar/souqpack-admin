<style type="text/css">
    div.dataTables_wrapper div.dataTables_length label {
        font-size: 14px;
    }
    .input-group-sm>.input-group-append>select.btn:not([size]):not([multiple]), .input-group-sm>.input-group-append>select.input-group-text:not([size]):not([multiple]), .input-group-sm>.input-group-prepend>select.btn:not([size]):not([multiple]), .input-group-sm>.input-group-prepend>select.input-group-text:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
        font-size: 14px;
    }
</style>


<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor"><?php echo $grid['grid_name'];?></h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin');?>">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $grid['grid_name'];?></li>
                </ol> 
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-12">
            <div class="card"> 
                <div class="card-body">
                    <form  name="<?php echo $grid['grid_tbl_name']; ?>_form" id="<?php echo $grid['grid_tbl_name']; ?>_form" method="post"> 
                        <div class="table-responsive">
                            <table id="<?php echo $grid['grid_tbl_name']; ?>_tbl" class="table table-striped" >
                                <thead>
                                    <tr>
                                        <?php foreach ($grid['grid_columns'] as $key => $value) {  ?>
                                          <th <?php echo $value['column_width'] ?> <?php echo $value['column_style'] ?> <?php echo $value['column_class'] ?> > 
                                            <?php echo $value['column_name'];?> 
                                          </th> 
                                        <?php  } ?>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
</div>

<div class="appends"></div>
<?php foreach ($extra_pages as $key => $value) { $this->load->view($value); } ?>





<script type="text/javascript">
  jQuery("input:text:visible:first").focus();
  
  function myDelete(id){
    Swal.fire({
      title: "Confirm",
      text: "Are you sure you want to delete this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#1FAB45",
      confirmButtonText: "Yes, Delete it.",
      cancelButtonText: "Cancel",
      buttonsStyling: true
    }).then((result) => {
        if (result.value == true) {
          jQuery.ajax({
            type: "POST",
            url: "<?php echo $grid['grid_delete_url'] ?>"+id,
            cache: false,
            success: function(response) {
                var json_data = jQuery.parseJSON(response);       
              if (json_data.status=="200") { 
                Swal.fire({
                title: "Success!",
                text: "Data deleted successfully!",
                type: "success",
                timer: 800});
                <?php echo $grid['grid_tbl_name']; ?>_tbl._fnAjaxUpdate(); 
              }else{
                  Swal.fire(
                  "Internal Error",
                  "Oops,Error Occurred.",
                  "error"
                  )};
            }
          });
        } else{
            Swal.fire({
            title: "Cancelled",
            text: "Your data is safe Now! ",
            type:"error",
            timer:800
            })  ;
        }
    }, 
    function (dismiss) {
      if (dismiss === "cancel") {
        Swal.fire(
        "Internal Error",
        "Oops, Some Error Occurred.",
        "error"
        );
      }
    })
  }

  jQuery( "#add_modal" ).on('shown', function(){ 
    // jQuery('.datepicker').css('z-index', 500);
  });


  jQuery(document).ready(function() { 
      // jQuery('.select2').select2();
      var dd = { 
          beforeSend: function() 
          { 
          
          },
          uploadProgress: function(event, position, total, percentComplete) 
          {
            
          },
          success: function() 
          {
          },
          complete: function(response) 
          {
            console.log(response.responseText);
            var result = jQuery.parseJSON(response.responseText)  ;
            if (result.status == 200) 
            {
              Swal.fire({
                type: 'success',
                title: 'Successfully saved',
                showConfirmButton: false,
                timer: 1500
              });
              <?php echo $grid['grid_tbl_name']; ?>_tbl._fnAjaxUpdate();
               
              jQuery('#<?php echo $grid['grid_tbl_name']; ?>').trigger("reset"); 
              
            }else{
              Swal.fire({
                type: 'warning',
                title: 'Oops',
                text: result.message,
                showConfirmButton: false,
                timer: 2000
              });
            } 
          },
          error: function()
          {

          } 
      }; 

      jQuery("#<?php echo $grid['grid_tbl_name']; ?>").ajaxForm(dd);
   
  });
   
        
  var is_first_time_load = true;
  jQuery(document).ready(function(){ 
    <?php echo $grid['grid_tbl_name']; ?>_tbl = jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').dataTable({
                        "processing": true,
                        "fixedHeader": true,
                        "serverSide": true, 
                        "bAutoWidth": true, 
                        /*"scrollY":300,   */
                        "iDisplayLength": <?php echo $grid['grid_tbl_length']; ?>,
                        "ajaxSource": "<?php echo $grid['grid_dt_url']?>",
                        "aoColumns": [<?php foreach($grid['grid_columns'] as $key=>$value) { if($value['column_sortable']=='true'){ echo "{ 'bSortable' : true}," ;}  else {echo "{ 'bSortable' : false}," ;} }?>                     
                        ],
                        "order":[['<?php echo $grid['grid_order_by']; ?>','<?php echo $grid['grid_order_by_type']; ?>']],
                        "sDom": "<'row'<'col-sm-9 col-xs-9'l><'col-sm-3 col-xs-3'f>r>t<'row'<'col-sm-5 hidden-xs paging-class'i><'col-sm-7 col-xs-12 clearfix'p>>",
                        
                        'fnDrawCallback' : function(){
                          jQuery('th:first-child').removeClass('sorting_desc');
                          jQuery('th:first-child').removeClass('sorting');
                          if(is_first_time_load){
                            
                            <?php if (isset($grid['grid_add_button']) && $grid['grid_add_button']==false){

                            }else { ?>
                            <?php if (isset($grid['grid_add_url']) && $grid['grid_add_url']!='') { ?> 
                            jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_length').prepend('<a href="<?php echo $grid['grid_add_url']?>"><button type="button" class="btn  btn-dark pull-left" href="<?php echo $grid['grid_add_url']?>" style="margin-right:10px;float:left">Add <?php echo $grid['grid_tbl_display_name']; ?></button></a>');
                            <?php } else {  ?>
                              jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_length').prepend('<button type="button" class="btn add_<?php echo $grid['grid_tbl_name']; ?> btn-dark pull-left" data-toggle="modal"  data-target=".add_modal" style="margin-right:10px;float:left">Add <?php echo $grid['grid_tbl_display_name']; ?></button>');
                            <?php } }  ?>
      
                            
                            jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_length select').addClass("form-control");
                            jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl_filter input').addClass("form-control"); 
                            is_first_time_load = false;
                          } 
                        },
    });  


    jQuery('#<?php echo $grid['grid_tbl_name']; ?>_tbl').delay(100).css("width","100%");  
  });
 

  function edit(id) {   
    jQuery.ajax({
      type: "GET", 
      url: '<?php echo $grid['grid_data_url']?>'+id,
      success: function(response) 
        {   
          jQuery('.appends').html(response);
          jQuery('#edit_modal').modal('show'); 
          $(".select2").select2()  
        }, 
    });  
  }

       
</script>
  