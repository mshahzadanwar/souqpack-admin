<script type="text/javascript">
    var on_this_page_rows = 2;
</script>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Costs Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/costs";?>">Costs</a></li>
                <li class="breadcrumb-item active">Add New Cost</li>
            </ol>
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
            <?=form_open_multipart('',array('class'=>'form-material','novalidate'=>""));?>

            <div class="card">
                
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Information
                       
                    </h4>
                </div>
                 
                 <div class="card-body ">
                    
                 

                   <div class="" style="flex-direction: row; display: flex; width: 100%; float: left; justify-content: space-between;">
                       <div class="supplierbox" style="margin: 10px; float: left;">
                        <div class="supplierbox_heading" style="flex-direction: row; margin-bottom: 10px; justify-content: space-between;">
                            <input 
                            placeholder="Supplier Name"
                            type="text" name="supplier_name" required="" value="<?php if(set_value("supplier_name") != ''){ echo set_value("supplier_name");}else echo $data->supplier_name; ?>">
                            <input 
                            placeholder="Supplier Phone"

                            type="tel" name="supplier_phone" required="" value="<?php if(set_value("supplier_phone") != ''){ echo set_value("supplier_phone");}else echo $data->supplier_phone; ?>">
                        </div>
                        <div class="supplierbox_address" style="">
                            <textarea 
                            placeholder="Supplier Address"

                            style="width: 100%" rows="5" name="supplier_address"><?php if(set_value("supplier_address") != ''){ echo set_value("supplier_address");}else echo $data->supplier_address; ?></textarea>
                        </div>
                       </div>


                       <div class="supplierbox" style="margin: 10px;  float: left; width: 20%;">
                        <div class="supplierbox_heading" style=" margin-bottom: 10px; display: flex; flex-direction: column;  justify-content: space-between;">
                            <label>Invoice #</label>
                            <input 
                            placeholder="Ref. No"
                            type="text" name="invoice_no" required="" value="<?php if(set_value("invoice_no") != ''){ echo set_value("invoice_no");}else echo $data->invoice_no; ?>">
                            <label style="margin-top: 10px">Invoice Date:</label>
                            <input 
                            placeholder="Date"
                            
                            type="date" name="date" required="" value="<?php if(set_value("date") != ''){ echo set_value("date");}else echo $data->date; ?>">
                        </div>
                       
                       </div>
                   </div>

                   <div class="easy" style="margin-top: 50px;">
                        <div class="tbale_dy" style="flex-direction: row;display: flex;">
                            <div style="width: 30px;">
                                <span>#</span>
                            </div>

                            <div style="">
                                <span>Name</span>
                            </div>
                            <div style="">
                                <span>Details</span>
                            </div>
                            <div style="">
                                <span>Quantity</span>
                            </div>
                            <div style="">
                                <span>Cost</span>
                            </div>
                            <div style="">
                                <span>Total</span>
                            </div>
                            <div style="">
                                <span>Accountant Response</span>
                            </div>
                            <div style="">
                                <span>Stock Response</span>
                            </div>
                        </div>
                        <div id="add_more_rows_in_me" class="easy pull-left" style="width: 100%;">
                            <?php 

                            $items = $this->db->where("cost_id",$data->id)->get("cost_items")->result_object();

                            foreach($items as $key=>$item){
                                $item = (array) $item;
                            
                           $item["item_no"] = $key+1;
                           $item["allow_remove"] = 455;
                           if(is_accountant() || is_stock())
                            $item["approve_reject"]=1;
                                echo new_row_cost($item); 

                                 }
                            ?>
                        </div>

                   </div>
                   <?php if(!is_stock()){ ?>
                   <div class="easy">
                   <?php if($data->invoice_file!=""){ ?>

                            <?php if($data->is_image==1){ ?>
                                <div style="padding: 10px;margin-top: 10px">
                                    <img src="<?php echo base_url()."resources/uploads/orders/".$data->invoice_file; ?>" width="100px" />
                                </div>
                            <?php } ?>
                            <span style="float: left;margin-top: 10px;border:1px dotted grey; background: #f0f0f0; padding: 2px 10px; border-radius: 4px; display: flex;flex-direction: row;">
                                <span style="color: purple;font-style: italic; font-size: 11px;justify-content: space-between;"><?php echo $data->filename; ?></span>
                                <a style="margin-left: 20px;" href="<?php echo base_url()."admin/costs/download/".$data->id; ?>" >
                                    <span >
                                       <i class="fa fa-download"></i> Download (<?php echo $data->file_size; ?> KB)
                                    </span>
                                </a>

                            </span>
                           

                        <?php } ?>
                    </div>

                        <?php } ?>
                  



                </div>

            </div>
            
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>


<div class="modal fade rejectItem" id="view_details_" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document" style="max-width: 70%;">
            <div class="modal-content">
              

            <div class="row">
                <div class="col-12">
                <?=form_open_multipart(base_url()."admin/costs/rejectItem",array('class'=>'', 'id'=>"contactForm3", 'novalidate'=>""));?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Reason
                            <i data-dismiss="modal" aria-label="Close" class="fa fa-times-circle" style="float: right;
margin-top: 3px; cursor: pointer;" data-toggle="tooltip" title="Close"></i>
                        </h4>
                    </div>

                    <div class="card-body details_body">
                        <?php $input = "reason"; ?>
                        <div class="form-group
                        <?=(form_error($input) !='')?'error':'';?>"  style="margin-bottom: 0; padding-bottom: 0;">
                            <h5>Reason </h5>
                            <div class="controls">
                                <textarea
                                required
                                class="form-control form-control-line enable_me"
                                name="<?php echo $input; ?>" ></textarea>
                                <input type="hidden" class="enable_me" value="" id="item_id" name="item_id" />
                               
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: 0; padding-top: 0;">
                        <div class="text-xs-right" style="float: right;">
                            
                            <button  class=" btn btn-primary">Reject Now</button>
                        </div>
                        
                    </div>
                </div>
            <?=form_close();?>

            </div>
        </div>
    </div>
</div>
</div>
