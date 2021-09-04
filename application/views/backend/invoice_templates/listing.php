<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Invoice Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Invoice Management</li>
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
        <?php foreach($invoice_templates->result() as $invoice_template){ ?>
        <div class="col-3">
            <div class="card" style="box-shadow: 2px 2px 9px 2px #b9b9b9; min-height: 322px; ">
                <div class="card-body">
                    <div class="easy"><img src="<?php echo base_url()."resources/backend/images/".$invoice_template->image; ?>" width="100%"></div>
                    <h4 class="card-title text-center easy" style="margin:20px 0px;"><?php echo $invoice_template->title; ?></h4>

                     <div class="easy text-center">
                        <input <?php if($invoice_template->status==1) echo "checked"; ?> type="checkbox" data-color="#26c6da" data-secondary-color="#f62d51" name="active" value="1" class="js-switch" data-id="<?php echo $invoice_template->id; ?>">
                    </div>

                    <div class="easy text-center" style="margin-top: 20px;">
                        <a target="_blank" href="<?php echo base_url()."admin/view-invoice-template/".$invoice_template->id; ?>"><button class="btn btn-sm btn-primary"><i class="fa fa-tv"></i> View</button></a>

                    </div>
                   
                    
                    
                </div>
            </div>
          
        </div>

    <?php } ?>
    <?php 
        $tersm = $this->db->query("SELECT * FROM invoice_templates WHERE id = 1 ")->result_object()[0];
    ?>
    <div class="col-md-9">
        <form action="<?php echo base_url();?>admin/invoice_templates/update_terms" method="post">
            <div class="form-group <?=(form_error('description_en') !='')?'error':'';?>">
                <h5>Terms & Conditions (English) </h5>
                <div class="controls">
                    <textarea required class="mymce form-control form-control-line" name="description_en" rows="5" ><?php if(set_value('description_en') != ''){ echo set_value('description_en');}else{ echo $tersm->description_en;}?></textarea>
                    <div class="text-danger"><?php echo form_error('description_en');?></div>
                </div>
            </div>
             <div class="form-group <?=(form_error('description_ar') !='')?'error':'';?>">
                <h5>Terms & Conditions (Arabic) </h5>
                <div class="controls">
                    <textarea required class="mymce form-control form-control-line" name="description_ar" rows="5" ><?php if(set_value('description_ar') != ''){ echo set_value('description_ar');}else{ echo $tersm->description_ar;}?></textarea>
                    <div class="text-danger"><?php echo form_error('description_ar');?></div>
                </div>
            </div>
            <div class="form-group" style="margin-top: 0;">
                <button class="btn btn-primary right">Update</button>
            </div>
        </form>
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
<style type="text/css">
    .invoice_text {
        text-align: center;
    margin-top: 18px;
    font-size: 12px;
    }
</style>