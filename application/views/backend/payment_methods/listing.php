<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Payment Methods Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payment Methods</li>
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
        <?php foreach($payment_methods->result() as $payment_method){ ?>
        <div class="col-3">
            <div class="card" style="box-shadow: 2px 2px 9px 2px #b9b9b9; ">
                <div class="card-body">
                    <div class="easy"><img src="<?php echo base_url()."resources/backend/images/".$payment_method->image; ?>" width="100%"></div>
                    <h4 class="card-title text-center easy" style="margin:20px 0px;"><?php echo $payment_method->title; ?></h4>

                    <!--  <div class="easy text-center">
                        <input <?php if($payment_method->status==1) echo "checked"; ?> type="checkbox" data-color="#26c6da" data-secondary-color="#f62d51" name="active" value="1" class="js-switch" data-id="<?php echo $payment_method->id; ?>">
                    </div> -->

                    <div class="easy text-center" style="margin-top: 20px;">
                        <a href="<?php echo base_url()."admin/edit-payment-method/".$payment_method->id; ?>"><button class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button></a>

                    </div>
                   
                    
                    
                </div>
            </div>
          
        </div>

    <?php } ?>
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