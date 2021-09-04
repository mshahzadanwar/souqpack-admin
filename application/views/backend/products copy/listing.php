<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Products Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
                <a href="<?php echo $url;?>admin/add-product">
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
                    <h4 class="card-title">Products</h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Region</th>
                                    <th>Category</th>
                                    <th>Price </th>
                                    <th>Cost Price </th>
                                    <th>Min. Qty.</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Region</th>
                                    <th>Category</th>
                                    <th>Price </th>
                                    <th>Cost Price </th>
                                    <th>Min. Qty.</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach($products->result() as $product){

                                $category = $this->db->where('id',$product->category)
                                ->get('categories')
                                ->result_object();

                                $brand = $this->db->where('id',$product->brand)
                                ->get('brands')
                                ->result_object();


                                $units = $this->db->where('product_id',$product->id)
                                ->get('product_units')
                                ->result_object();

                            ?>
                            <tr>
                                <td>
                                    <?php echo $product->title;?>
                                </td>
                                <td>
                                    <?php 

                                    foreach($units as $qunit){
                                    if(is_region() && $qunit->region_id!=region_id()) continue;
                                    $region = $this->db->where("id",$qunit->region_id)->get("regions")->result_object()[0]; 
                                        if(!$region) echo "Default".', ';
                                        else echo $region->title.', ';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if(empty($category)){ ?>
                                        Self
                                    <?php } else { 



                                        $one = $category[0];

                                if($one->lparent==0)
                                {
                                    $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
                                }
                                else
                                {
                                    $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
                                }

                                echo $one->title . ' / ' .$two->title;

                                 } ?>
                                </td>
                               
                                <td>
                                    <?php foreach($units as $qunit){
                                        if(is_region() && $qunit->region_id!=region_id()) continue;
                                        $unit = $this->db->where('id',$qunit->region_id)
                                            ->get('regions')
                                            ->result_object()[0];

                                            echo $unit->currency.' '. ($qunit->price) . ' for '. $unit->title;
                                            echo "<br>";
                                    } ?>
                                </td>
                                <td>
                                    <?php foreach($units as $qunit){
                                        if(is_region() && $qunit->region_id!=region_id()) continue;
                                        $unit = $this->db->where('id',$qunit->region_id)
                                            ->get('regions')
                                            ->result_object()[0];

                                            echo $unit->currency.' '. ($qunit->cost_price) . ' for '. $unit->title;
                                            echo "<br>";
                                    } ?>
                                </td>
                                <td>
                                    <?php echo $product->min_order_qty?$product->min_order_qty:1; ?>
                                </td>
                                <td>
                                    <?php if($product->discount_type=="0")
                                    {
                                        echo "N/A";
                                    }elseif($product->discount_type=="1")
                                    {
                                        echo "Flat " .$product->discount;
                                    }elseif ($product->discount_type=="2") {
                                        echo $product->discount."%";
                                    }else echo "N/A"; ?>
                                </td>
                            	<td>
                            		<?php if($product->status == 0){?>
                                        <a href="<?php echo $url.'admin/product-status/'.$product->id.'/'.$product->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                            		<?php }else{?>
                                        <a href="<?php echo $url.'admin/product-status/'.$product->id.'/'.$product->status;?>" ><span class="btn btn-success">Active</span></a>
                            		<?php } ?>
                            	</td>


                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($product->created_at));?>
                            	</td>
                                <td>

                                    <a href="<?php echo $url."admin/";?>edit-product/<?php echo $product->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                    
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-product/<?php echo $product->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>

                                    <a href="<?php echo $url."admin/";?>add-product?replicate=<?php echo $product->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-reload"></i></div></a>

                                    <a href="<?php echo $url."admin/";?>product/<?php echo $product->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-television"></i></div></a>
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