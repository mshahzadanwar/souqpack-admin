

<div class="modal fade add_modal" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Add Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form add_form" method="post" name="add_form" id="tag" action="<?php echo base_url('save-tag')?>" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="add">
                    <input type="hidden" name="id" value="">
                    <div id="message"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Language
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <select required="" class="form-control" name="tag_language_id_2">
                                                        <option value="2">English</option> 
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Tag Title
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <input type='text' name="tag_title_2" class="form-control" required>
                                                </div>
                                            </div>                                           
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Language
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <select required class="form-control" name="tag_language_id_1">
                                                        <option value="1">Arabic</option> 
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="withdrawinput1">Tag Title
                                                        <span style="color: red">*</span>
                                                    </label>
                                                    <input type='text' name="tag_title_1" class="form-control" required>
                                                </div>
                                            </div>                                           
                                        </div> 
                                        <br>
                                        <div class="row" style="float: right">
                                            <button type="submit" class="btn btn-success waves-effect waves-light" style="margin-right: 5px;">Save changes</button>
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