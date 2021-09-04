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
                    <h4 class="m-b-0 text-white">Add New Cost Information
                       
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


                       <div class="supplierbox" style="margin: 10px;  float: left;">
                        <div class="supplierbox_heading" style=" margin-bottom: 10px; display: flex; flex-direction: column;  justify-content: space-between;">
                            <label>Invoice #</label>
                            <input 
                            placeholder="Ref. No"
                            type="text" name="invoice_no" required="" value="<?php if(set_value("invoice_no") != ''){ echo set_value("invoice_no");}else echo $data->invoice_no; ?>">
                             <label style="margin-top: 10px">Invoice Date:</label>
                            <input 
                            placeholder="Date"
                            style="margin-top: 10px"
                            type="date" name="date" required="" value="<?php if(set_value("date") != ''){ echo set_value("date");}else echo $data->date; ?>">
                        </div>
                       
                       </div>
                   </div>

                   <div class="easy" style="margin-top: 10px;">
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
                            <?php if(!is_purchaser()){ ?>
                                <div style="">
                                    <span>Accountant Response</span>
                                </div>
                                <div style="">
                                    <span>Stock Response</span>
                                </div>
                            <?php } ?>
                        </div>
                        <div id="add_more_rows_in_me" class="easy pull-left" style="width: 100%;">
                            <?php 
                           $row["item_no"] = 1;
                           $row["allow_remove"] = 455;
                                echo new_row_cost($row); 
                            ?>
                        </div>

                        <div class="easy" style="margin-top: 10px;">
                            <button onclick="moreRowCost()" type="button" class="btn btn-sm btn-primary">+ Add New Row</button>
                        </div>
                   </div>

                   <div class="easy " style="margin-top: 10px;">
                     <?php $input = "invoice"; ?>
                        <div class="easy form-group <?=(form_error($input) !='')?'error':'';?>">
                            <div class="row">

                                <div class="col-lg-6 col-md-6 nopad">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5>Invoice</h5>

                                            <input 
                                            
                                            type="file"  id="input-file-disable-remove" class="dropify" data-show-remove="false" name="<?php echo $input; ?>" data-default-file="" />
                                            <div class="text-danger"><?php echo form_error($input);?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>



                </div>

            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/costs" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>