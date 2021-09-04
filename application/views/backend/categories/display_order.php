
<style>
    .dropify-wrapper .dropify-message p{
        text-align: center;
    }
</style>
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Categories Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=$url;?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?=$url;?>admin/categories">Categories</a></li>
                <li class="breadcrumb-item active">Edit Category</li>
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
            <input type="hidden" value="" name="json_order" id="json_order">
            <div class="card ">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit Categories Display Order</h4>
                </div>
                <div class="card-body">
                    <div class="myadmin-dd dd" id="nestable">
                        <ol class="dd-list">
                            <?php foreach($categories->result() as $category){ ?>
                                <li class="dd-item" data-id="<?php echo $category->id; ?>">
                                    <div class="dd-handle"> <?php echo $category->title; ?> </div>

                                    <?php $childs = $this->db->where('parent',$category->id)->where('is_deleted',0)
                                        ->order_by('display_priority',"ASC")
                                        ->get('categories')->result_object();
                                        if(!empty($childs)){ ?>
                                            <ol class="dd-list">
                                        <?php } 

                                        foreach($childs as $child){
                                            ?>
                                            <li class="dd-item" data-id="<?php echo $child->id; ?>">
                                                <div class="dd-handle"><?php echo $child->title; ?></div>
                                            </li>

                                            <?php
                                        }

                                        if(!empty($childs)){ ?>
                                            </ol>
                                        <?php }
                                    ?>

                                </li>
                            <?php } ?>
                           
                            
                        </ol>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="text-xs-right">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="<?=$url;?>admin/categories" class="btn btn-inverse">Cancel</a>
                </div>
            </div>
            <?=form_close();?>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

</div>