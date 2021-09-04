

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Products Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $url."admin/products";?>">Products</a></li>
                <li class="breadcrumb-item active">Add New Product</li>
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
                    <h4 class="m-b-0 text-white">Information</h4>
                </div>
                <div class="card-body">
                    <h3 class=""><?php echo $product->title; ?></h3>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="white-box text-center" style="float: left;width: auto;"> <img width="300px" src="<?php echo base_url()."resources/uploads/products/".$product->image; ?>"> </div>


                            <span style="margin:10px 0px; float: left; width: 100%;"> --Other Images</span>

                            <?php
                            $other_images = $this->db->where('product_id',$product->id)->where('is_deleted',0)->get('product_images')->result_object();
                             foreach($other_images as $other_image){ ?>
                                <div class="card" style="float: left;width: auto; border:1px solid #fb9678; margin-right: 10px; ">
                                    <div class="card-header" style="color: white;">Image
                                        <a  class="deleted pull-right" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-product-image/<?php echo $other_image->id;?>" >
                                            <button class="btn btn-danger btn-sm" type="button">Remove</button>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                <img width="300px" src="<?php echo base_url()."resources/uploads/products/".$other_image->image; ?>"> </div>
                            </div>

                             <?php } ?>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-6">
                            <h4 class="box-title m-t-40">Product description</h4>
                            <p><?php echo $product->description; ?></p>
                            <h2 class="m-t-40">
                                <?php
                                    if($product->disount_type==0)
                                    {
                                        $discount = 0;
                                        $sign = "%";

                                    }
                                    elseif($product->disount_type==1)
                                    {
                                        $discount = $product->price-$product->discount;
                                        $sign = "USD";
                                    }
                                    elseif($product->disount_type==2)
                                    {
                                        $discount = (($product->discount/100)*$product->price);
                                        $sign="%";
                                    }
                                 ?>

                                $<?php echo $product->price-$discount; ?> USD 
                                <small class="text-success">(<?php
                                    echo $discount.$sign;
                                 ?> off)</small></h2>
                           
                           
                           
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="box-title m-t-40">General Info</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td>Title</td>
                                            <td> <?php echo $product->title; ?> </td>
                                        </tr>
                                        <tr>
                                            <td width="390">Brand</td>
                                            <td> <?php
                                            echo $this->db->where('id',$product->brand)->get('brands')->result_object()[0]->title;
                                             ?> </td>
                                        </tr>
                                        
                                       
                                        <tr>
                                            <td>Type</td>
                                            <td> <?php
                                            echo $this->db->where('id',$product->category)->get('categories')->result_object()[0]->title;
                                             ?> </td>
                                        </tr>
                                      
                                        <tr>
                                            <td>SKU</td>
                                            <td> <?php echo $product->sku; ?> </td>
                                        </tr>

                                        <tr>
                                            <td>Discount </td>
                                            <td>
                                                <?php if($product->discount_type=="0")
                                                {
                                                    echo "N/A";
                                                }elseif($product->discount_type=="1")
                                                {
                                                    echo "$".$product->discount."USD";
                                                }elseif ($product->discount_type=="2") {
                                                    echo $product->discount."%";
                                                }else echo "N/A"; ?> 
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Price (USD)</td>
                                            <td><?php echo $product->price; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Cost Price (USD)</td>
                                            <td><?php echo $product->cost_price; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">SEO</td>
                                        </tr>
                                        <tr>
                                            <td>Meta Title</td>
                                            <td> <?php echo $product->meta_title; ?> </td>
                                        </tr>

                                        <tr>
                                            <td>Meta keywords</td>
                                            <td> <?php echo $product->meta_keywords; ?> </td>
                                        </tr>

                                        <tr>
                                            <td>Meta Description</td>
                                            <td> <?php echo $product->meta_description; ?> </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <a href="<?=$url;?>admin/products" class="btn btn-info">Done</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>