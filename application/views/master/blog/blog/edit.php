<style type="text/css">
    .active-lang{
        background: #3a9452;
        border-radius: 6px;
        color: #fff;
        font-size: 12px;
    }
    .active-lang:hover{  
        color: #fff;
        /*font-size: 14px;*/
    }
    .inactive-lang:hover{  
        color: #000;
        /*font-size: 14px;*/
    }
    .inactive-lang{
        border: 0.5px solid aliceblue;
        margin-left: 10px;
        border-radius: 6px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #000000;
    }
    textarea {
        resize: vertical; /* user can resize vertically, but width is fixed */
    }


</style>

<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Edit Blog</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin');?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('blog-master');?>">Blogs</a></li>
                    <li class="breadcrumb-item active">Edit Blog</li>
                </ol> 
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-12">
            <form class="form editBlog" method="post" name="editBlog" id="editBlog" action="<?php echo base_url('save-blog')?>" enctype="multipart/form-data">
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
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Category
                                                        <span style="color: red">*</span>
                                                    </label>    
                                                    <select id="blog_category_id" name="blog_category_id" class="form-control form-control-line">
                                                        <?php if (!empty($categories)) { 
                                                            foreach($categories as $key=>$value) {  
                                                        ?> 
                                                            <option 
                                                            <?php if ($en['blog_category_id']==$value->category_id){echo 'selected="selected"'; }?>
                                                            value="<?php echo $value->category_id?> ">
                                                                <?php echo $value->category_name?> 
                                                            </option>
                                                        <?php } }  ?>
                                                    </select> 
                                                </div>
                                            </div>  
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Tags
                                                        <span style="color: red">*</span>
                                                    </label>    
                                                    <select multiple="multiple" id="blog_tag_ids" class="select2 form-control form-control-line" name="blog_tag_ids[]">
                                                        <?php if (!empty($tags)) { 
                                                            $atags = explode(',', $en['blog_tag_ids']); 
                                                            foreach($tags as $key=>$value) {  
                                                        ?> 
                                                            <option 
                                                            <?php  
                                                                if (in_array($value->tag_id, $atags))
                                                                    {echo 'selected="selected"'; }
                                                            ?> 

                                                            value="<?php echo $value->tag_id?>">
                                                                <?php echo $value->tag_title?> 
                                                            </option>
                                                        <?php } }  ?>
                                                    </select> 
                                                </div>
                                            </div>  

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Title
                                                        <span style="color: red">*</span>
                                                    </label>    
                                                    <input value="<?php echo $en['blog_title'];?>" type='text' id="blog_title2" name="blog_title2" class="form-control form-control-line" required> 
                                                </div>
                                            </div>  
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Slug
                                                    </label>
                                                    <input  value="<?php echo $en['blog_slug'];?>" type='text' id="blog_slug" name="blog_slug" class="form-control" required>
                                                </div>
                                            </div>  
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Blog Image 
                                                    </label>     
                                                    <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="blog_image2" data-default-file="<?php echo base_url('resources/uploads/blog/thumb/').$en['blog_main_image'];?>" />
                                                    <div class="text-danger"></div> 
                                                </div>
                                            </div>   
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Short Description
                                                    </label>
                                                    <textarea id="blog_short_description2" name="blog_short_description2" class="form-control"><?php echo $en['blog_short_description'];?></textarea>
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Content
                                                    </label>
                                                    <textarea id="blog_content2"  class="form-control"><?php echo $en['blog_content'];?></textarea>
                                                </div>
                                            </div>                                      
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Title
                                                    </label>
                                                    <input type='text'  value="<?php echo $en['blog_meta_title'];?>" id="blog_meta_title2" name="blog_meta_title2" class="form-control">
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Keywords
                                                    </label>
                                                    <textarea id="blog_meta_keywords2" name="blog_meta_keywords2" class="form-control"><?php echo $en['blog_meta_keywords'];?></textarea> 
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Description
                                                    </label>
                                                    <textarea id="blog_meta_description2" name="blog_meta_description2" class="form-control"><?php echo $en['blog_meta_description'];?></textarea>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row mt-5 d-none" id="ar-section"> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Title
                                                        <span style="color: red">*</span>
                                                    </label>    
                                                    <input type='text'  value="<?php echo $ar['blog_title'];?>" id="blog_title1" name="blog_title1" class="form-control form-control-line" required> 
                                                </div>
                                            </div>    

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Blog Image 
                                                    </label>     
                                                    <input type="file" id="input-file-disable-remove" class="dropify" data-show-remove="false" name="blog_image1" data-default-file="<?php echo base_url('resources/uploads/blog/thumb/').$ar['blog_main_image'];?>"  />
                                                    <div class="text-danger"></div> 
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Short Description
                                                    </label>
                                                    <textarea id="blog_short_description1" name="blog_short_description1" class="form-control"><?php echo $ar['blog_short_description'];?></textarea>
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Content
                                                    </label>
                                                    <textarea id="blog_content1"   class="form-control" class="textarea_editor mymce"><?php echo $ar['blog_content'];?></textarea>
                                                </div>
                                            </div>                                      
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Title
                                                    </label>
                                                    <input type='text' id="blog_meta_title1" name="blog_meta_title1" value="<?php echo $ar['blog_meta_title'];?>" class="form-control" >
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Keywords
                                                    </label>
                                                    <textarea id="blog_meta_keywords1" name="blog_meta_keywords1" class="form-control"><?php echo $ar['blog_meta_keywords'];?></textarea> 
                                                </div>
                                            </div> 
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Meta Description
                                                    </label>
                                                    <textarea id="blog_meta_description1" name="blog_meta_description1" class="form-control"><?php echo $ar['blog_meta_description'];?></textarea>
                                                </div>
                                            </div>                                       
                                        </div> 
                                        <br>
                                        <div class="row" style="float: right">
                                            <button type="button" class="btn btn-outline-success waves-effect waves-light" style="margin-right: 5px;" id="next-btn" onclick="chLang('ar')">Next</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light  d-none" style="margin-right: 5px;" id="save-btn">Save</button>
                                            <button onclick="window.location.href='<?php echo base_url('blog-master');?>'" type="button" class="btn btn-outline-danger waves-effect waves-light" data-dismiss="modal">Close</button>
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

  


 


<script type="text/javascript">
     

    $(document).ready(function() { 
        tinymce.init({
            selector: "textarea#blog_content1",
            theme: "modern",
            height: 200,
            // inline: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

        });
        tinymce.init({
            selector: "textarea#blog_content2",
            theme: "modern",
            height: 200,
            // inline: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

        });
    }); 

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
        window.scrollTo(0, 0);
    }
 
    $('#blog_title2').bind('keyup keypress blur', function(){  
        var myStr = $(this).val()
        myStr=myStr.toLowerCase();
        myStr=myStr.replace(/[^a-zA-Z0-9]/g,'-').replace(/\s+/g, "-");
        $('#blog_slug').val(myStr); 
    });

    $( "#editBlog").submit(function( event ) {        
        event.preventDefault(); 

        var ar_blog_image = $('input[name="blog_image1"]').prop('files')[0]; 
        var en_blog_image = $('input[name="blog_image2"]').prop('files')[0];
         
        
        var allFields = [       
            'mode',
            "id1",
            "id2",
            'blog_category_id', 
            'blog_slug',

            'blog_title1',
            'blog_title2',
  
            'blog_meta_title1',
            'blog_meta_title2',

        ]; 

        var required_fileds = [       
            'mode',
            "id1",
            "id2",
            'blog_category_id', 
            'blog_slug',

            'blog_title1',
            'blog_title2', 
              
        ]; 
         

        var issueT=0;
        for (var i = 0; i < required_fileds.length; i++) {
            var tmpData = $('input[name="'+required_fileds[i]+'"]').val();
            if (typeof(tmpData) == "undefined" || tmpData==null) {
                issueT=1;
                var tmpData = $('select[name="'+required_fileds[i]+'"]').val();
                if (typeof(tmpData) == "undefined" || tmpData==null) { 
                    alert("Please fill proper "+required_fileds[i]); 
                    issueT=1;  
                }else{
                    issueT=0;
                }
            } 
        }

        var blog_tag_ids=$('#blog_tag_ids').val();
        if (typeof(tmpData) == "undefined" || tmpData==null) {
            alert("Please select valid tags"); 
            issueT=1;
        }

        var blog_meta_keywords1 = $('textarea[name="blog_meta_keywords1"]').val();
        var blog_meta_keywords2 = $('textarea[name="blog_meta_keywords2"]').val();
        var blog_meta_description1 = $('textarea[name="blog_meta_description1"]').val();
        var blog_meta_description2 = $('textarea[name="blog_meta_description2"]').val();
        var blog_short_description1 = $('textarea[name="blog_short_description1"]').val();
        var blog_short_description2 = $('textarea[name="blog_short_description2"]').val();
            
 

        var ar_content = tinyMCE.get('blog_content1').getContent();
        var en_content = tinyMCE.get('blog_content2').getContent();

        if (typeof (en_content)=='undefined' || en_content==null || en_content=="") {
            alert("Please fill proper English Content"); 
            issueT=1;
        }
        if (typeof (ar_content)=='undefined' || ar_content==null || ar_content=="") {
            alert("Please fill proper English Content"); 
            issueT=1;
        }

        if (issueT==0) {
            var fd = new FormData();
            if (typeof(en_blog_image)!=='undefined' && en_blog_image.length!=0) {
                fd.append('blog_image1', en_blog_image,en_blog_image.name); 
            }
            if (typeof(ar_blog_image)!=='undefined' && ar_blog_image.length!=0) { 
                fd.append('blog_image2', ar_blog_image,ar_blog_image.name);
            }
     
     
            fd.append('blog_content1',ar_content);
            fd.append('blog_content2',en_content); 
            
            fd.append('blog_meta_keywords1',blog_meta_keywords1);
            fd.append('blog_meta_keywords2',blog_meta_keywords2);
            fd.append('blog_meta_description1',blog_meta_description1);
            fd.append('blog_meta_description2',blog_meta_description2);
            fd.append('blog_short_description1',blog_short_description1);
            fd.append('blog_short_description2',blog_short_description2);
            
            fd.append('blog_tag_ids',blog_tag_ids);
     
            var new_data = {}; 
            for (var i = 0; i < allFields.length; i++) { 
                var tmpData = $('input[name="'+allFields[i]+'"]').val();
                if (typeof(tmpData) == "undefined" || tmpData==null) {
                    var tmpData = $('select[name="'+required_fileds[i]+'"]').val();
                    if (typeof(tmpData) == "undefined" || tmpData==null) {     
                        tmpData=""; 
                    }   
                }
                fd.append(allFields[i], tmpData); 
            } 


            $.ajax({
                type    :   "POST", 
                url     :   '<?php echo base_url('save-blog')?>',
                data    :   fd,
                contentType: false,
                processData: false,
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
                        window.location.href="<?php echo base_url('blog-master')?>";
                    }else{
                        Swal.fire(
                            "Internal Error",
                            json_data.message,
                            "error"
                        )
                    } 
                    
                }, 
            });  
        } 
 
    });



</script>