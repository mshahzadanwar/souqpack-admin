<div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Notifications</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
             
            </div>
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
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">All Notifications</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>From</th>
                                                <th>Content</th>
                                                <th>Data & Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Status</th>
                                                <th>From</th>
                                                <th>Content</th>
                                                <th>Data & Time</th>
                                                <th>Action</th>

                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php foreach($notifi->result() as $notification){?>
                                        <tr>
                                        	<td class="td-cursor" data-id="<?php echo $notification->id;?>" data-url="notification-detail" data-redirct="1">
                                        		<?php if($notification->read_it == 1){?>
                                        		<span class="btn btn-success">Unread</span>
                                        		<?php }else{?>
                                        		<span class="btn btn-primary">Read</span>
                                        		<?php } ?>
                                        	</td>
                                        	<td class="td-cursor" data-id="<?php echo $notification->id;?>" data-url="notification-detail" data-redirct="1">
                                        		<?php echo $notification->title;?>
                                        	</td>
                                        	<td class="td-cursor" data-id="<?php echo $notification->id;?>" data-url="notification-detail" data-redirct="1">
                                        		<?php echo $notification->content;?>
                                        	</td>
                                        	<td class="td-cursor" data-id="<?php echo $notification->id;?>" data-url="notification-detail" data-redirct="1">
                                        		<?php echo date('F d, Y, h:i:a',strtotime($notification->created_at));?>
                                        	</td> 
                                            <td class="td-cursor">
                                                <a class="btn btn-info" href="<?php echo $url.$notification->url; ?>">Visit</a>
                                                <a class="btn btn-danger" href="<?php echo $url.'dashboard/delete_notificaton/'.$notification->id; ?>">Delete</a>
                                            </td> 
                                        </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Modal Start Here -->
    
                <!-- sample modal content -->
                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal" style="display: none;">
                 <form method="post" action="<?php echo $url;?>reply" id="modal_data" enctype="multipart/form-data">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Quote Reply</h4>
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
                
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>