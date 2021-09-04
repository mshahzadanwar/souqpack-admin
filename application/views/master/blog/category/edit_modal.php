<div class="modal fade edit_modal" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form editCat" method="post" name="editCat" id="editCat" action="<?php echo base_url('save-category')?>" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="edit">
                    <input type="hidden" name="id2" value="<?php echo $en['id'];?>">
                    <input type="hidden" name="id1" value="<?php echo $ar['id'];?>">
                    <div id="message"></div>
                    <div class="form-body"> 
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <nav class="nav">
                                            <a id="en" class="nav-link active active-lang" aria-current="page" href="javascript:chLang('en');">English</a>
                                            <a id="ar" class="nav-link inactive-lang" href="javascript:chLang('ar');">Arabic</a> 
                                        </nav>
                                         
                                        <div class="row mt-5" id="en-section"> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Category Name
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <input value="<?php echo $en['category_name']?>" type='text' id="category_name" name="category_name_2" class="form-control" required>
                                                </div>
                                            </div>  
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Slug
                                                    </label>
                                                    <input type='text' value="<?php echo $en['category_slug']?>" id="category_slug" name="category_slug" class="form-control" required>
                                                </div>
                                            </div>                                           
                                        </div>
                                        <div class="row mt-5 d-none" id="ar-section"> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Category Name
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <input type='text' name="category_name_1" class="form-control" value="<?php echo $ar['category_name']?>">
                                                </div>
                                            </div>                                           
                                        </div> 
                                        <br>
                                        <div class="row" style="float: right">
                                            <button type="button" class="btn btn-outline-success waves-effect waves-light" style="margin-right: 5px;" id="next-btn" onclick="chLang('ar')">Next</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light  d-none" style="margin-right: 5px;" id="save-btn">Save</button>
                                            <button type="button" class="btn btn-outline-danger waves-effect waves-light" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function chLang(lang){
        // alert(lang);
        if (lang=="en") {
            $('#ar').removeClass('active-lang');
            $('#ar').addClass('inactive-lang');
            $('#en').addClass('active-lang');

            $('#en-section').removeClass('d-none');
            $('#ar-section').addClass('d-none');
            $('#next-btn').removeClass('d-none');
            $('#save-btn').addClass('d-none');
        }
        else if(lang=="ar"){
            $('#en').removeClass('active-lang');
            $('#en').addClass('inactive-lang');
            $('#ar').addClass('active-lang');

            $('#ar-section').removeClass('d-none')
            $('#en-section').addClass('d-none')

            $('#save-btn').removeClass('d-none');
            $('#next-btn').addClass('d-none');
        }
    }


    $('#category_name').bind('keyup keypress blur', function(){  
        var myStr = $(this).val()
        myStr=myStr.toLowerCase();
        myStr=myStr.replace(/[^a-zA-Z0-9]/g,'-').replace(/\s+/g, "-");
        $('#category_slug').val(myStr); 
        $('#category_slug').val(); 
    });

    $( "#editCat").submit(function( event ) {        
        event.preventDefault();

        var allFields = [
            'mode',
            'id1',
            'id2',
            'category_name_1',
            'category_name_2',
            'category_slug',
        ];

        console.log(allFields);
        var new_data = {};
        for (var i = 0; i < allFields.length; i++) {
            new_data[allFields[i]] = $('input[name="'+allFields[i]+'"]').val();
        }
        console.log(new_data);


        jQuery.ajax({
            type    :   "POST", 
            url     :   '<?php echo base_url('save-category')?>',
            data    :   new_data,
            success: function(response) 
            {    
                var json_data = jQuery.parseJSON(response);       
                if (json_data.status==200) { 
                    Swal.fire({
                        title: "Success!",
                        text: json_data.message,
                        type: "success",
                        timer: 800
                    });
                    category_tbl._fnAjaxUpdate(); 
                }else{
                    Swal.fire(
                        "Internal Error",
                        json_data.message,
                        "error"
                    )
                } 
                $('.edit_modal').modal('hide');
            }, 
        });  
 
    });



</script>