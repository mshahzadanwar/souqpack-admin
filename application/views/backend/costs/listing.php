<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Purchase/Cost Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Purchase/Cost</li>
                </ol>
                <?php if(is_purchaser()){ ?>
                <a href="<?php echo $url;?>admin/add-cost">
                    <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                </a>
                <?php } ?>
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
                    <h4 class="card-title">Purchase/Cost</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Supplier Name</th>
                                    <?php if(!is_stock()){ ?>
                                    <th>Invoice</th>
                                    <?php } ?>
                                    <th>Added By</th>                                    
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Supplier Name</th>
                                    <?php if(!is_stock()){ ?>
                                    <th>Invoice</th>
                                    <?php } ?>
                                    <th>Added By</th>                                    
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($costs->result() as $cost){

                            
                                $person = $this->db->where("id",$cost->user_id)->get("admin")->result_object()[0];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $cost->invoice_no;?>
                                </td>
                                <td>
                                    <?php echo $cost->supplier_name;?>
                                </td>
                                <?php if(!is_stock()){ ?>
                                <td>
                                    <div class="easy">
                   <?php if($cost->invoice_file!=""){ ?>

                            <?php if($cost->is_image==1){ ?>
                                <div style="padding: 10px;margin-top: 10px">
                                    <img src="<?php echo base_url()."resources/uploads/costs/".$cost->invoice_file; ?>" width="100px" />
                                </div>
                            <?php } ?>
                            <span style="float: left;margin-top: 10px;border:1px dotted grey; background: #f0f0f0; padding: 2px 10px; border-radius: 4px; display: flex;flex-direction: row;">
                                <span style="color: purple;font-style: italic; font-size: 11px;justify-content: space-between;"><?php echo $cost->filename; ?></span>
                                <a style="margin-left: 20px;" href="<?php echo base_url()."admin/costs/download/".$cost->id; ?>" >
                                    <span >
                                       <i class="fa fa-download"></i> Download (<?php echo $cost->file_size; ?> KB)
                                    </span>
                                </a>

                            </span>
                           

                        <?php } ?>
                    </div>
                                </td>
                                <?php } ?>
                                <td>
                                   <?php echo $person->name; ?>
                                </td>

                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($cost->created_at));?>
                            	</td>
                                <td>

                                    <a href="<?php echo $url."admin/";?>view-cost/<?php echo $cost->id;?>">
                                        <div class="btn btn-info btn-circle">
                                            <i class="fa fa-tv"></i>
                                        </div>
                                    </a>
                                    <?php if(is_purchaser()){ ?>

                                    <a href="<?php echo $url."admin/";?>edit-cost/<?php echo $cost->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>


                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-cost/<?php echo $cost->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>
                                    <?php } ?>
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
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>
