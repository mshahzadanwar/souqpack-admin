<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Offers Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Offers</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-offer">
                    <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                </a>
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
                    <h4 class="card-title">Offers</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <!-- <th>Category</th> -->
                                    <th>Products</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Send as Newsletter</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <!-- <th>Category</th> -->
                                    <th>Products</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Send as Newsletter</th>

                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($offers->result() as $offer){

                                // $category = $this->db->where('id',$offer->category)
                                // ->get('categories')
                                // ->result_object();
                                $arr = explode(',',$offer->products);
                                if(empty($arr)) $arr = array(-1);


                                $products = $this->db->where_in('id',$arr)
                                ->get('products')
                                ->result_object();

                            ?>
                            <tr>
                                <td>
                                    <?php echo $offer->title;?>
                                </td>
                                
                                <td>
                                    <?php foreach($products as $product) echo $product->title."<br>"; ?>
                                </td>
                                <td>
                                    <?php if($offer->applies_on_date==1) echo date("d, M Y",strtotime($offer->start_date)) . ' - ' .  date("d, M Y",strtotime($offer->end_date));
                                    else echo "Any";
                                    ?>
                                </td>
                                <td>
                                    <?php echo $offer->min_qty==0?"Any":$offer->min_qty; ?>
                                </td>
                                
                            	<td>
                            		<?php if($offer->status == 0){?>
                                        <a href="<?php echo $url.'admin/offer-status/'.$offer->id.'/'.$offer->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                            		<?php }else{?>
                                        <a href="<?php echo $url.'admin/offer-status/'.$offer->id.'/'.$offer->status;?>" ><span class="btn btn-success">Active</span></a>
                            		<?php } ?>
                            	</td>


                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($offer->created_at));?>
                            	</td>
                                <td>
                                    <a href="<?php echo $url.'admin/send-offer-as-newsletter/'.$offer->id;?>" ><span class="btn btn-primary">Send</span></a>
                                </td>
                                <td>

                                    <a href="<?php echo $url."admin/";?>edit-offer/<?php echo $offer->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-offer/<?php echo $offer->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>

                                 
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