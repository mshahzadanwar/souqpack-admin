<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Categories Management</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $url."admin";?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
                <?php if(!is_region()){ ?>

                    <a href="<?php echo $url;?>admin/add-category">
                        <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                    </a>
                    <a href="<?php echo $url;?>admin/category-display-order">
                        <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-bars"></i> Change Display Order</button>
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
            <div class="filter_by">
                    <label>Filter By: </label>
                    <span>
                        <?php
                            $all_parents = $this->db->query("SELECT title,id FROM categories where parent = 0 AND lparent = 0")->result_object();
                            //echo $this->db->last_query();
                        ?>
                        <form action="<?php echo base_url();?>admin/categories/set_filter" method="post" style="display: flex;">
                            <select name="filter" id="filter" class="form-control" required="" style="float: left;">
                                <option value="">-- Choose Parent Category--</option>
                                <option value="-1" <?php if($_SESSION['filter_cat'] == "-1") {echo "SELECTED";} ?> >All Parents</option>
                                <?php foreach ($all_parents as $key => $parent) {?>
                                    <option value="<?php echo $parent->id;?>" 
                                        <?php if($parent->id == $_SESSION['filter_cat']) {echo "SELECTED";} ?> 
                                    >
                                        <?php echo $parent->title;?>
                                    </option>
                                <?php } ?>
                            </select>
                            <button class="btn btn-primary" name="form_filter" type="submit" style="float: left;">Go</button>

                            <?php if(isset($_SESSION['filter_cat'])){?>
                                <button onclick="reset_form()" class="btn btn-secondary btn-sm" type="button" style="float: left;">Reset</button>
                            <?php } ?>
                        </form>
                    </span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Categories
                    </h4>
                    
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Parent</th>
                                    <th>Status</th>
                                    <th>Customisable</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Parent</th>
                                    <th>Status</th>
                                    <th>Customisable</th>
                                    <th>Data & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php 
                                // print_r($categories->result());
                                // die;
                            foreach($categories->result() as $category){

                                $parent = $this->db->where('id',$category->parent)
                                ->get('categories')
                                ->result_object();


                                $one = $category;

                                if($one->lparent==0)
                                {
                                    $two = $this->db->where("lparent",$one->id)->get("categories")->result_object()[0];
                                }
                                else
                                {
                                    $two = $this->db->where("id",$one->lparent)->get("categories")->result_object()[0];
                                }



                                if($one->lang_id==2)
                                {
                                    $customizer = $one->id;
                                }
                                else
                                {
                                    $customizer = $this->db->where("id",$one->lparent)->or_where("lparent",$one->id)->get("categories")->result_object()[0]->id;
                                }

                            ?>
                            <tr>
                                <td>
                                    <?php echo $one->title .' / ' .$two->title ;?>
                                </td>
                                <td>
                                    <?php if(empty($parent)){ ?>
                                        Self
                                    <?php } else { echo $parent[0]->title; } ?>
                                </td>
                            	<td>

                            		<?php if($category->status == 0){?>
                                        <a href="<?php echo $url.'admin/category-status/'.$category->id.'/'.$category->status;?>" ><span class="btn btn-danger">Inactive</span></a>
                            		<?php }else{?>
                                        <a href="<?php echo $url.'admin/category-status/'.$category->id.'/'.$category->status;?>" ><span class="btn btn-success">Active</span></a>
                            		<?php } ?>
                            	</td>

                                <td>
                                    <?php if($category->parent!=0){ ?>
                                    <?php if($category->cust == 0){?>
                                        <a href="<?php echo $url.'admin/category-cust/'.$category->id; ?>" ><span class="btn btn-danger btn-sm">No</span></a>
                                    <?php }else{?>
                                        <a href="<?php echo $url.'admin/category-cust/'.$category->id; ?>" ><span class="btn btn-success btn-sm">Yes</span></a>
                                        <a href="<?php echo $url.'admin/category-customize/'.$customizer;?>" ><span class="btn btn-info  btn-sm">Edit Form</span></a>
                                    <?php } ?>

                                    <?php }else{echo "--";} ?>
                                </td>



                            	<td >
                            		<?php echo date('d M, Y, h:i A',strtotime($category->created_at));?>
                            	</td>
                                <td>
                                <?php if(!is_region()){ ?>

                                    <a href="<?php echo $url."admin/";?>edit-category/<?php echo $category->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-pencil"></i></div></a>
                                    <a class="deleted" href="javascript:void(0);" data-url="<?php echo $url;?>admin/delete-category/<?php echo $category->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-delete"></i></div></a>

                                    <a href="<?php echo $url."admin/";?>add-category?replicate=<?php echo $category->id;?>"><div class="btn btn-info btn-circle"><i class="mdi mdi-reload"></i></div></a>
                                </td>
                            <?php } ?>
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

<script type="text/javascript">
    function reset_form(){
        window.location.href = '<?php echo base_url()."admin/categories/reset_filter";?>';
    }
</script>