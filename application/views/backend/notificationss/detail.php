<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Notifications Detail</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $url;?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo $url;?>all-notifications">Notifications</a></li>
                            <li class="breadcrumb-item active">Notifications Detail</li>
                        </ol>
                    </div>
                    
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                
                
                
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Notifications Detail</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" role="form">
                                    <div class="form-body">
                                        <h3 class="box-title">Detail</h3>
                                        <hr class="m-t-0 m-b-40">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-4">From</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> <?php echo $notificationss->title;?> </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-4">Content:</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> <?php echo $notificationss->content;?> </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-4">Visit:</label>
                                                    <div class="col-md-8">
                                                        <a class="btn btn-info" href="<?php echo $url.$notificationss->url; ?>">Visit</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                       
                                        <!--/row-->
                                        
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                    <a href="<?php echo $url;?>all-notifications" class="btn btn-success"> Back</a>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> </div>
                                        </div>      
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                
               
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                
            </div>

             <!-- Modal Start Here -->
    
                <!-- sample modal content -->
                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal" style="display: none;">
                 <form method="post" action="<?php echo $url;?>reply" id="modal_data" enctype="multipart/form-data">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Notifications Reply</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text"  name="email" id="replyemail" class="form-control" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="subject" id="replysubject" value="" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="1">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                                <textarea id="mymce" class="message" name="message"></textarea>
                                        </div>
                                    </div>
                                    <div class="text-danger"><?php echo form_error('message');?></div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    <input type="file" name="file" id="input-file-now" class="dropify" />
                                </div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit"  id="submit" class="btn btn-info">Submit</button>
                                <button type="submit" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </form>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- ============================================================== -->
                <!-- Modal End Here -->