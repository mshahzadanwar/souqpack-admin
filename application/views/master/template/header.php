<header class="topbar">
    
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo base_url()."admin"; ?>">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                   <!--  <img src="<?php echo base_url(); ?>resources/uploads/logo/<?php echo $settings->site_logo_small; ?>" alt="<?php echo $settings->site_title; ?>" class="dark-logo" style="width: 30px;" /> -->
                    <!-- Light Logo icon -->
                    <img src="<?php echo base_url(); ?>resources/uploads/logo/<?php echo $settings->site_logo_small; ?>" alt="<?php echo $settings->site_title; ?>" class="light-logo"  style="width: 30px;"/>
                </b>
                <!--End Logo icon -->
                <!-- Logo text --><span>
                 <!-- dark Logo text -->
                 <img src="<?php echo base_url(); ?>resources/uploads/logo/<?php echo $settings->site_logo; ?>" alt="<?php echo $settings->site_title; ?>" class="dark-logo" style="width: 130px;" />
                 <!-- Light Logo text -->    
                 <img src="<?php echo base_url(); ?>resources/uploads/logo/<?php echo $settings->site_logo; ?>" class="light-logo" alt="<?php echo $settings->site_title; ?>" style="width: 130px;" /></span> </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-sm-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <li class="nav-item" style="display: none;">
                    <form class="app-search d-none d-md-block d-lg-block">
                        <input type="text" class="form-control" placeholder="Search & enter">
                    </form>
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                <?php  if(is_admin()){ ?>
                <li class="nav-item dropdown">
                    <a style="margin-right: 20px;" class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-dollar"></i>
                        <?php 


                        $count_unread = count_unread(1); if($count_unread>0){ ?><div class="notify"> <span class="heartbit"></span> <span
                            style="position: absolute;top: -40px;right: 10px; font-size: 13px;"
                            ><?php echo $count_unread; ?></span> </div><?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <ul>
                            <li>
                                <div class="drop-title">Payment Notifications</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <?php 

                                    $cloum = notif_read_column();
                                    foreach(notifications(1) as $notification){

                                        ?>
                                        <a <?php if($notification->$cloum == 0){?> class="new-notification" <?php } ?> href="<?php 

                                        if($notification->function=="")
                                            echo base_url()."admin/dashboard/open_notif/".$notification->id;
                                        else
                                            echo base_url()."admin/dashboard/".$notification->function."/".$notification->id;
                                         ?>">
                                            <div class="btn btn-success btn-circle"><i class="mdi mdi-account-location"></i></div>
                                            <div class="mail-contnet">
                                                <h5><?php echo $notification->desc;?></h5></div>
                                        </a>
                                    <?php } ?>


                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link" href="<?php echo $url;?>admin/dashboard/read_all"> <strong>Mark all as read</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>
            <?php  if(!is_purchaser() &&  !is_accountant() && !is_stock()){ ?>
                <li class="nav-item dropdown">
                    <a style="margin-right: 20px;" class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                        <?php 


                        $count_unread = count_unread(); if($count_unread>0){ ?><div class="notify"> <span class="heartbit"></span> <span
                            style="position: absolute;top: -40px;right: 10px; font-size: 13px;"
                            ><?php echo $count_unread; ?></span> </div><?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <ul>
                            <li>
                                <div class="drop-title">General Notifications</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <?php 

                                    $cloum = notif_read_column();
                                    foreach(notifications() as $notification){

                                        ?>
                                        <a <?php if($notification->$cloum == 0){?> class="new-notification" <?php } ?> href="<?php 
                                        if($notification->function=="")
                                            echo base_url()."admin/dashboard/open_notif/".$notification->id;
                                        else
                                            echo base_url()."admin/dashboard/".$notification->function."/".$notification->id; ?>">
                                            <div class="btn btn-success btn-circle"><i class="mdi mdi-account-location"></i></div>
                                            <div class="mail-contnet">
                                                <h5><?php echo $notification->desc;?></h5></div>
                                        </a>
                                    <?php } ?>


                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link" href="<?php echo $url;?>admin/dashboard/read_all"> <strong>Mark all as read</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php } ?>


                <!-- REJECTION OF STOCK AND PURCHASES -->
                <?php  if(is_stock() || is_purchaser()){ ?>
                <li class="nav-item dropdown">
                    <a style="margin-right: 20px;" class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
                        <?php 

                        
                        $count_unread = count_unread(1); if($count_unread>0){ ?><div class="notify"> <span class="heartbit"></span> <span
                            style="position: absolute;top: -40px;right: 10px; font-size: 13px;"
                            ><?php echo $count_unread; ?></span> </div><?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <ul>
                            <li>
                                <div class="drop-title">Purchase Notifications</div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <?php 

                                    $cloum = notif_read_column();
                                    foreach(notifications(1) as $notification){

                                        ?>
                                        <a <?php if($notification->$cloum == 0){?> class="new-notification" <?php } ?> href="<?php 

                                        if($notification->function=="")
                                            echo base_url()."admin/dashboard/open_notif/".$notification->id;
                                        else
                                            echo base_url()."admin/dashboard/".$notification->function."/".$notification->id;
                                         ?>">
                                            <div class="btn btn-success btn-circle"><i class="mdi mdi-account-location"></i></div>
                                            <div class="mail-contnet">
                                                <h5><?php echo $notification->desc;?></h5></div>
                                        </a>
                                    <?php } ?>


                                </div>
                            </li>
                            <li>
                                <!-- <a class="nav-link text-center link" href="<?php echo $url;?>admin/dashboard/read_all"> <strong>Mark all as read</strong> <i class="fa fa-angle-right"></i> </a> -->
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>
                
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                
               
                <!-- <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li> -->
            </ul>
        </div>
    </nav>
</header>
<style type="text/css">
.new-notification {
    background: #f0f0f0;
}
</style>
<aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div><img src="<?php echo base_url(); ?>resources/uploads/<?php  echo is_vendor()?"vendors/".$this->session->userdata("admin_profile_pic"):"profiles/".$this->session->userdata("admin_profile_pic"); ?>" alt="user-img" class="img-circle"></div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php if($this->session->userdata("admin_name")!="")

                            { echo $this->session->userdata("admin_name"); }

                            else { echo is_vendor()?"Vendor":"Admin"; } ?> <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
                                <a href="<?php echo base_url();  if(is_vendor()){ echo "admin/vendor-profile"; } else echo "admin/my-profile"; ?>" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                                <!-- text-->
                               

                               
                                <!-- text-->
                                <?php if(check_role(2)){ ?>

                                 <div class="dropdown-divider"></div>
                                <a href="<?php echo base_url()."admin/settings"; ?>" class="dropdown-item"><i class="ti-settings"></i> Setting</a>
                                <a href="<?php echo base_url()."admin/seo/settings"; ?>" class="dropdown-item"><i class="ti-settings"></i> Sitemap Settings</a>
                                <?php } ?>
                                <!-- text-->
                                <div class="dropdown-divider"></div>

                                
                                <!-- text-->
                                <a href="<?php echo base_url().'admin/logout'; ?>" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
						<li class="<?=($active == 'dashboard')?'active':'';?>"> <a class="waves-effect waves-dark" href="<?php echo base_url().'admin/dashboard';?>" aria-expanded="false"><i class="fa fa-dashboard"></i><span class="hide-menu">Dashboard</span></a>
						</li>

                        <?php if(check_role(-1) && !is_region()){ ?>

						<li class="<?=($active == 'languages')?'active':'';?>"> <a class="waves-effect waves-dark" href="<?php echo base_url().'admin/languages';?>" aria-expanded="false"><i class="fa fa-globe"></i><span class="hide-menu">Languages</span></a>
						</li>
                       
                        
                         <li class="<?=($active == 'push')?'active':'';?>"> 
                            <a class="
                            waves-effect waves-dark" 
                            href="<?php
                            echo base_url()."admin/push-notifications";
                             ?>"
                            >
                            <i class="fa fa-bell-o"></i>
                            <span>
                                Send Push Notifications
                            </span>
                        </a>
                        </li>

                        
                        <li > <a class="waves-effect waves-dark" href="<?php echo base_url().'admin/footers';?>" aria-expanded="false"><i class="fa fa-file-text-o"></i><span class="hide-menu">Footer Text</span></a>
                        </li>
                         <?php } ?>

                        <?php if(check_role(1)){ ?>
                        <li class="<?=($active == 'category')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-apple-keyboard-command"></i><span class="hide-menu">Categories</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'category')?'active':'';?>"><a class="<?=($sub == 'categories')?'active':'';?>" href="<?php echo $url."admin/";?>categories">Categories <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("categories",false); ?>
                                </span></a></li>
                                <?php if(!is_region()){ ?>
                                <li class="<?=($sub == 'add-category')?'active':'';?>"><a class="<?=($sub == 'add-category')?'active':'';?>" href="<?php echo $url."admin/";?>add-category">Add New Category</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-categories">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("categories",true); ?>
                                </span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
					<?php if(check_role(4) && !is_region()){ ?>
                        <li class="<?=($active == 'brands')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-bars"></i><span class="hide-menu">Our Clients</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'brands')?'active':'';?>"><a class="<?=($sub == 'brands')?'active':'';?>" href="<?php echo $url."admin/";?>brands">Our Clients <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("brands",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-brand')?'active':'';?>"><a class="<?=($sub == 'add-brand')?'active':'';?>" href="<?php echo $url."admin/";?>add-brand">Add New Client</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-brands">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("brands",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(29)){ ?>
                        <li class="<?=($active == 'reports')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-bars"></i><span class="hide-menu">Reports</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'order_reports')?'active':'';?>"><a class="<?=($sub == 'reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/orders">Order Reports</a></li>

                                <li class="<?=($sub == 'customer_reports')?'active':'';?>"><a class="<?=($sub == 'customer_reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/customers">Customer Reports</a></li>

                                <li class="<?=($sub == 'categories_sales')?'active':'';?>"><a class="<?=($sub == 'category_sales')?'active':'';?>" href="<?php echo $url."admin/";?>reports/category_sales">Categories Sales Reports</a></li>

                                <li class="<?=($sub == 'country_reports')?'active':'';?>"><a class="<?=($sub == 'country_reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/countries">Country Reports</a></li>

                                <li class="<?=($sub == 'top_selling')?'active':'';?>"><a class="<?=($sub == 'top_selling')?'active':'';?>" href="<?php echo $url."admin/";?>reports/top_selling">Top Selling Reports</a></li>


                                <li class="<?=($sub == 'products_reports')?'active':'';?>"><a class="<?=($sub == 'products_reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/products_reports">Products Report</a></li>

                                <li class="<?=($sub == 'finance_reports')?'active':'';?>"><a class="<?=($sub == 'finance_reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/finance_reports">Finance Report</a></li>

                                <li class="<?=($sub == 'item_reports')?'active':'';?>"><a class="<?=($sub == 'item_reports')?'active':'';?>" href="<?php echo $url."admin/";?>reports/item_reports">Item History Report</a></li>
                                
                            </ul>
                        </li>
                    <?php } ?>
                     <?php if(check_role(40)){ ?>
                        <li class="<?=($active == 'creports')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-bars"></i><span class="hide-menu">Custom Reports</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'order_reports')?'active':'';?>"><a class="<?=($sub == 'creports')?'active':'';?>" href="<?php echo $url."admin/";?>CReports/orders">Order Reports</a></li>

                                <li class="<?=($sub == 'customer_reports')?'active':'';?>"><a class="<?=($sub == 'customer_reports')?'active':'';?>" href="<?php echo $url."admin/";?>CReports/customers">Customer Reports</a></li>

                                <li class="<?=($sub == 'categories_sales')?'active':'';?>"><a class="<?=($sub == 'category_sales')?'active':'';?>" href="<?php echo $url."admin/";?>CReports/category_sales">Categories Sales Reports</a></li>

                            
                                <li class="<?=($sub == 'finance_reports')?'active':'';?>"><a class="<?=($sub == 'finance_reports')?'active':'';?>" href="<?php echo $url."admin/";?>CReports/finance_reports">Finance Report</a></li>

                               
                                
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(27)){ ?>
                        <li class="<?=($active == 'sliders')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-tv"></i><span class="hide-menu">Sliders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'sliders')?'active':'';?>"><a class="<?=($sub == 'sliders')?'active':'';?>" href="<?php echo $url."admin/";?>sliders">sliders <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("sliders",false); ?>
                                </span></a></li>
                                <?php if(!is_region()){ ?>

                                <li class="<?=($sub == 'add-slider')?'active':'';?>"><a class="<?=($sub == 'add-slider')?'active':'';?>" href="<?php echo $url."admin/";?>add-slider">Add New slider</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-sliders">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("sliders",true); ?>
                                </span></a></li>
                            <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(-1) && !is_region()){ ?>


                        <li class="<?=($sub == 'regions')?'active':'';?>"><a class="<?=($sub == 'regions')?'active':'';?>" href="<?php echo $url."admin/";?>regions"> <i class="fa fa-globe"></i> Region</a></li>
                        <?php /* ?>
                        <li class="<?=($active == 'regions')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-globe"></i><span class="hide-menu">Regions</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'regions')?'active':'';?>"><a class="<?=($sub == 'regions')?'active':'';?>" href="<?php echo $url."admin/";?>regions">Regions <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("regions",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-region')?'active':'';?>"><a class="<?=($sub == 'add-region')?'active':'';?>" href="<?php echo $url."admin/";?>add-region">Add New Region</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-regions">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("regions",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                        <?php */ ?>
                    <?php } ?>
                    <?php if(check_role(28)){ ?>
                        <li class="<?=($active == 'purchases')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-money"></i><span class="hide-menu">Purchases</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'purchases')?'active':'';?>"><a class="<?=($sub == 'purchases')?'active':'';?>" href="<?php echo $url."admin/";?>purchases">Purchases <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $wherehere = where_region_id();
                                    echo $this->db->where("lparent",0)->where("is_deleted",0)->count_all_results("purchases"); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-purchase')?'active':'';?>"><a class="<?=($sub == 'add-purchase')?'active':'';?>" href="<?php echo $url."admin/";?>add-purchase">Add New Purchase</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-purchases">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    // $wherehere = where_region_id();
                                    echo $this->db->where("lparent",0)->where("is_deleted",1)->where_in("region_id",explode(',', $wherehere))->count_all_results("purchases"); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(33) || is_purchaser() || is_accountant() || is_stock()){ ?>
                        <li class="<?=($active == 'cost')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-money"></i><span class="hide-menu">Purchase/Cost</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'cost')?'active':'';?>"><a class="<?=($sub == 'cost')?'active':'';?>" href="<?php echo $url."admin/";?>costs">Purchase/Cost <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $wherehere = where_region_id();
                                    echo $this->db->where("lparent",0)->where("is_deleted",0)->count_all_results("costs"); ?>
                                </span></a></li>
                                <?php if(is_purchaser()){ ?>
                                <li class="<?=($sub == 'add-cost')?'active':'';?>"><a class="<?=($sub == 'add-cost')?'active':'';?>" href="<?php echo $url."admin/";?>add-cost">Add New Purchase/Cost</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-cost">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    // $wherehere = where_region_id();
                                    echo $this->db->where("lparent",0)->where("is_deleted",1)->count_all_results("costs"); ?>
                                </span></a></li>
                            <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(19) && !is_region()){ ?>
                        <li class="<?=($active == 'stores')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-building"></i><span class="hide-menu">Stores</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'stores')?'active':'';?>"><a class="<?=($sub == 'stores')?'active':'';?>" href="<?php echo $url."admin/";?>stores">Stores <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("stores",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-store')?'active':'';?>"><a class="<?=($sub == 'add-store')?'active':'';?>" href="<?php echo $url."admin/";?>add-store">Add New Store</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-stores">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("stores",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>
					<?php if(check_role(5)){ ?>
                        <li class="<?=($active == 'product')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-diamond"></i><span class="hide-menu">Products</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'product')?'active':'';?>"><a class="<?=($sub == 'products')?'active':'';?>" href="<?php echo $url."admin/";?>products">Products <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_vlisting("products",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-product')?'active':'';?>"><a class="<?=($sub == 'add-product')?'active':'';?>" href="<?php echo $url."admin/";?>add-product">Add New Product</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-products">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_vlisting("products",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(14)){ ?>
                        <li class="<?=($active == 'orders')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-shopping-cart"></i><span class="hide-menu">Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'orders')?'active':'';?>"><a class="<?=($sub == 'orders')?'active':'';?>" href="<?php echo $url."admin/";?>orders">Orders <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    //$wherehere = where_region_id();
                                    echo $this->db->where("is_deleted",0)->count_all_results("orders"); ?>
                                </span></a></li>
                                
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(32)){ ?>
                        <li class="<?=($active == 'c_orders')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-shopping-cart"></i><span class="hide-menu">Custom Orders</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'c_orders')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>c_orders">Custom Orders <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $wherehere = where_region_id();
                                    echo $this->db->where("is_deleted",0)->where_in("region_id",explode(',', $wherehere))->count_all_results("c_orders"); ?>
                                </span></a></li>                               
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(check_role(38)){ ?>
                        <li class="<?=($active == 'bank')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-university"></i><span class="hide-menu">Bank Transfers</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'bank')?'active':'';?>"><a class="<?=($sub == 'bank')?'active':'';?>" href="<?php echo $url."admin/";?>bank-transfer">Bank Transfers <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $wherehere = where_region_id();
                                    echo $this->db->count_all_results("bank_payment_recipts"); ?>
                                </span></a></li>                               
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(15)){ ?>
                        <li class="<?=($active == 'refund')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-retweet"></i><span class="hide-menu">Refund Requests</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'refund')?'active':'';?>"><a class="<?=($sub == 'refund')?'active':'';?>" href="<?php echo $url."admin/";?>refund-request">Refund Requests <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    //$wherehere = where_region_id();
                                    echo $this->db->count_all_results("refund"); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    

                   

                    <?php if(is_designer()){ ?>
                        <li class="<?=($active == 'my_tasks')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-file-text-o"></i><span class="hide-menu">My Tasks</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>pending-tasks">Pending Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    echo $this->db->where_in("status",array(1,0))->where("designer_id",$this->session->userdata("admin_id"))->count_all_results("designer_tasks"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>accepted-tasks">Accepted Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    echo $this->db->where_in("status",array(4))->where("designer_id",$this->session->userdata("admin_id"))->count_all_results("designer_tasks"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>late-tasks">Late Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $today_date = date("Y-m-d H:i:s");

                                    echo $this->db->where("designer_id",$this->session->userdata("admin_id"))->where("deadline <",$today_date)->where_in("status",array(0,3))->count_all_results("designer_tasks"); ?>
                                </span></a></li>
                               
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(is_production()){ ?>
                        <li class="<?=($active == 'my_tasks')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-file-text-o"></i><span class="hide-menu">My Tasks</span></a>
                            <ul aria-expanded="false" class="collapse">
                               

                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>pending-tasks">Pending Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    echo $this->db->where_in("production_status",array(0,1))->where("production_id",$this->session->userdata("admin_id"))->count_all_results("c_orders"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>accepted-tasks">Completed Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    echo $this->db->where_in("production_status",array(2,3,4))->where("production_id",$this->session->userdata("admin_id"))->count_all_results("c_orders"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'my_tasks')?'active':'';?>"><a class="<?=($sub == 'c_orders')?'active':'';?>" href="<?php echo $url."admin/";?>late-tasks">Late Tasks <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php 
                                    $today_date = date("Y-m-d H:i:s");

                                    ?>
                                    <?php 
                                    echo $this->db->where_in("production_status",array(0,1))->where("production_deadline <",$today_date)->where("production_id",$this->session->userdata("admin_id"))->count_all_results("c_orders"); ?>
                                </span></a></li>
                               
                            </ul>
                        </li>
                    <?php } ?>

                    

                    <?php if(check_role(25)){ ?>
                        <li class="<?=($active == 'reviews')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-star"></i><span class="hide-menu">Reviews</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'reviews')?'active':'';?>"><a class="<?=($sub == 'reviews')?'active':'';?>" href="<?php echo $url."admin/";?>reviews">Reviews <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->count_all_results("order_reviews");; ?>
                                </span></a></li>
                               
                            </ul>
                        </li>
                    <?php } ?>


                    

                    <?php if(check_role(6) && !is_region()){ ?>
                        <li class="<?=($active == 'quantity-units')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-adjust"></i><span class="hide-menu">Quantity Units </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'quantity-unit')?'active':'';?>"><a class="<?=($sub == 'quantity-units')?'active':'';?>" href="<?php echo $url."admin/";?>quantity-units">Quantity Units <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("quantity_units",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-quantity-unit')?'active':'';?>"><a class="<?=($sub == 'add-quantity-unit')?'active':'';?>" href="<?php echo $url."admin/";?>add-quantity-unit">Add New Quantity Unit</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-quantity-units">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("quantity_units",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(24) && !is_region()){ ?>
                        <li class="<?=($active == 'shipments')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-truck"></i><span class="hide-menu">Shipment Methods</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'shipments')?'active':'';?>"><a class="<?=($sub == 'shipments')?'active':'';?>" href="<?php echo $url."admin/";?>shipments">Shipment Methods <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("shipments",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-shipment')?'active':'';?>"><a class="<?=($sub == 'add-shipment')?'active':'';?>" href="<?php echo $url."admin/";?>add-shipment">Add New Shipment Method</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-shipments">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("shipments",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>



                     <?php if(check_role(10) && !is_region()){ ?>
                        <li class="<?=($active == 'offers')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-bell-o"></i><span class="hide-menu">Offers</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'offers')?'active':'';?>"><a class="<?=($sub == 'offers')?'active':'';?>" href="<?php echo $url."admin/";?>offers">Offers <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("offers",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-offer')?'active':'';?>"><a class="<?=($sub == 'add-offer')?'active':'';?>" href="<?php echo $url."admin/";?>add-offer">Add New Offer</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-offers">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("offers",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(21) && 2==4){ ?>
                        <li class="<?=($active == 'notifications')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-bell-o"></i><span class="hide-menu">Notifications</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'notifications')?'active':'';?>"><a class="<?=($sub == 'notifications')?'active':'';?>" href="<?php echo $url."admin/";?>notifications">Notifications <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("notifications",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-notification')?'active':'';?>"><a class="<?=($sub == 'add-notification')?'active':'';?>" href="<?php echo $url."admin/";?>add-notification">Add New Notification</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-notifications">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("notifications",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>


                    <?php if(check_role(20) && !is_region()){ ?>
                        <li class="<?=($active == 'coupons')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-money"></i><span class="hide-menu">Coupons</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'coupons')?'active':'';?>"><a class="<?=($sub == 'coupons')?'active':'';?>" href="<?php echo $url."admin/";?>coupons">Coupons <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("coupons",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-coupon')?'active':'';?>"><a class="<?=($sub == 'add-coupon')?'active':'';?>" href="<?php echo $url."admin/";?>add-coupon">Add New Coupon</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-coupons">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("coupons",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>



                    <?php if(check_role(3)){

                    $result_adm_count = $this->db->query('
            SELECT *
            FROM admin
            WHERE is_deleted  = 0
            AND satff = 0
            '
        )->num_rows(); ?>
                        <li class="<?=($active == 'admins')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-lock"></i><span class="hide-menu">Admins</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'admins')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/";?>admins">Admins <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $result_adm_count; ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-admin')?'active':'';?>"><a class="<?=($sub == 'add-admin')?'active':'';?>" href="<?php echo $url."admin/";?>add-admin">Add New Admin</a></li>
                                <li class="<?=($sub == 'trash-admins')?'active':'';?>"><a class="<?=($sub == 'trash-admins')?'active':'';?>" href="<?php echo $url."admin/";?>trash-admins">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("admin",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(31)){ ?>
                        <li class="<?=($active == 'staff')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-lock"></i><span class="hide-menu">Staff</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'designer')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/staff/";?>designer">Designers <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->where("role",-2)->group_by("admin_id")->count_all_results("admin_roles"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'purchaser')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/staff/";?>purchaser">Purchase/Cost <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->where("role",-3)->group_by("admin_id")->count_all_results("admin_roles"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'accountant')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/staff/";?>accountant">Accountant <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->where("role",-4)->group_by("admin_id")->count_all_results("admin_roles"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'stock')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/staff/";?>stock">Stock Managers <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->where("role",-5)->group_by("admin_id")->count_all_results("admin_roles"); ?>
                                </span></a></li>


                                <li class="<?=($sub == 'production')?'active':'';?>"><a class="<?=($sub == 'admins')?'active':'';?>" href="<?php echo $url."admin/staff/";?>production">Production Managers <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $this->db->where("role",-6)->group_by("admin_id")->count_all_results("admin_roles"); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(22) && 2==3){ ?>
                        <li class="<?=($active == 'vendors')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-lock"></i><span class="hide-menu">Vendors</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'vendors')?'active':'';?>"><a class="<?=($sub == 'vendors')?'active':'';?>" href="<?php echo $url."admin/";?>vendors">Vendors <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("vendors",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-vendor')?'active':'';?>"><a class="<?=($sub == 'add-vendor')?'active':'';?>" href="<?php echo $url."admin/";?>add-vendor">Add New Vendor</a></li>
                                <li class="<?=($sub == 'trash-vendors')?'active':'';?>"><a class="<?=($sub == 'trash-vendors')?'active':'';?>" href="<?php echo $url."admin/";?>trash-vendors">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("vendors",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>


                    <?php if(check_role(7) && !is_region()){ ?>
                        <li class="<?=($active == 'faqs')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-question-circle"></i><span class="hide-menu">FAQs</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'faq')?'active':'';?>"><a class="<?=($sub == 'faqs')?'active':'';?>" href="<?php echo $url."admin/";?>faqs">FAQs <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("faqs",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-faq')?'active':'';?>"><a class="<?=($sub == 'add-faq')?'active':'';?>" href="<?php echo $url."admin/";?>add-faq">Add New FAQ</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-faqs">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("faqs",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if(check_role(8)){ ?>
                        <?php /*?><li class="<?=($active == 'questionnaires')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-apple-keyboard-command"></i><span class="hide-menu">Questionnaires</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'questionnaire')?'active':'';?>"><a class="<?=($sub == 'questionnaires')?'active':'';?>" href="<?php echo $url."admin/";?>questionnaires">Questionnaires <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("questionnaires",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-questionnaire')?'active':'';?>"><a class="<?=($sub == 'add-questionnaire')?'active':'';?>" href="<?php echo $url."admin/";?>add-questionnaire">Add New Questionnaire</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-questionnaires">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("questionnaires",true); ?>
                                </span></a></li>
                            </ul>
                        </li><?php */?>
                    <?php } ?>
                    <?php if(check_role(9)){ ?>
                        <?php /*?><li class="<?=($active == 'questions')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-apple-keyboard-command"></i><span class="hide-menu">Questions</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'question')?'active':'';?>"><a class="<?=($sub == 'questions')?'active':'';?>" href="<?php echo $url."admin/";?>questions">Questions <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("questions",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-question')?'active':'';?>"><a class="<?=($sub == 'add-question')?'active':'';?>" href="<?php echo $url."admin/";?>add-question">Add New Question</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-questions">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("questions",true); ?>
                                </span></a></li>
                            </ul>
                        </li><?php */?>
                    <?php } ?>


                    <?php if(check_role(16) && !is_region()){ ?>
                        <li class="<?=($active == 'payment_methods')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-money"></i><span class="hide-menu">Payment Methods</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'payment_method')?'active':'';?>"><a class="<?=($sub == 'payment_methods')?'active':'';?>" href="<?php echo $url."admin/";?>payment-methods">Payment Methods <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo 
                                    $this->db->query("SELECT * FROM payment_methods WHERE show_web = 1")->num_rows();
                                    //count_listing("payment_methods",false); ?>
                                </span></a></li>
                                
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(17) && !is_region()){ ?>
                        <li class="<?=($active == 'invoice_templates')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-file-text-o"></i><span class="hide-menu">Invoice Templates</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'invoice_templates')?'active':'';?>"><a class="<?=($sub == 'invoice_templates')?'active':'';?>" href="<?php echo $url."admin/";?>invoice-templates">Invoice Templates <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("invoice_templates",false); ?>
                                </span></a></li>
                                
                            </ul>
                        </li>
                    <?php } ?>




                    <?php if(check_role(11) && !is_region()){ ?>
                        <li class="<?=($active == 'emails')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-envelope-o"></i><span class="hide-menu">Emails</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'emails')?'active':'';?>"><a class="<?=($sub == 'emails')?'active':'';?>" href="<?php echo $url."admin/";?>emails">Emails <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("email_template",false); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>


                    <?php if(check_role(12) && !is_region()){ ?>
                        <li class="<?=($active == 'pages')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-file-o"></i><span class="hide-menu">Pages</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'pages')?'active':'';?>"><a class="<?=($sub == 'pages')?'active':'';?>" href="<?php echo $url."admin/";?>pages">Pages <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("pages",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-page')?'active':'';?>"><a class="<?=($sub == 'add-page')?'active':'';?>" href="<?php echo $url."admin/";?>add-page">Add New Page</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-pages">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("pages",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(51) && !is_region()){ ?>
                        <!--<li class="<?=($active == 'blogs')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-newspaper-o"></i><span class="hide-menu">Blogs</span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li class="<?=($sub == 'blogs')?'active':'';?>"><a class="<?=($sub == 'blogs')?'active':'';?>" href="<?php echo $url."admin/";?>blogs">Blogs <span class="badge badge-pill badge-cyan ml-auto">-->
                        <!--            <?php echo count_listing("blogs",false); ?>-->
                        <!--        </span></a></li>-->
                        <!--        <li class="<?=($sub == 'add-blog')?'active':'';?>"><a class="<?=($sub == 'add-blog')?'active':'';?>" href="<?php echo $url."admin/";?>add-blog">Add New Blog</a></li>-->
                        <!--        <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-blogs">Trash <span class="badge badge-pill badge-cyan ml-auto">-->
                        <!--            <?php echo count_listing("blogs",true); ?>-->
                        <!--        </span></a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                    <?php } ?>

                    <?php if(check_role(13) && !is_region()){ ?>
                        <li class="<?=($active == 'users')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Users</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'users')?'active':'';?>"><a class="<?=($sub == 'users')?'active':'';?>" href="<?php echo $url."admin/";?>users">Users <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $result = $this->db->query('
            SELECT *
            FROM users
            WHERE is_deleted  = 0
            AND (email != "" OR phone != "")'
        )->num_rows(); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-user')?'active':'';?>"><a class="<?=($sub == 'add-user')?'active':'';?>" href="<?php echo $url."admin/";?>add-user">Add New User</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-users">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("users",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if(check_role(15)){ ?>
                        <?php /*?><li class="<?=($active == 'affiliates')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-apple-keyboard-command"></i><span class="hide-menu">Affiliates </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'affiliates')?'active':'';?>"><a class="<?=($sub == 'affiliates')?'active':'';?>" href="<?php echo $url."admin/";?>affiliates">Affiliates <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("affiliates",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-affiliate')?'active':'';?>"><a class="<?=($sub == 'add-affiliate')?'active':'';?>" href="<?php echo $url."admin/";?>add-affiliate">Add New Affiliate</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-affiliates">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("affiliates",true); ?>
                                </span></a></li>
                            </ul>
                        </li><?php */?>
                    <?php } ?>
                    
                    <?php if(check_role(51) && !is_region()){ ?>
                    <li class="<?=($active == 'blog')?'active':'';?>"> 
                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                            <i class="fa fa-newspaper-o"></i><span class="hide-menu">Blogs</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li class="<?=($sub == 'blog')?'active':'';?>">
                                <a class="<?=($sub == 'blog')?'active':'';?>" href="<?php echo base_url('blog-master')?>">
                                    Blogs
                                    <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $result = $this->db->query('
                                        SELECT blog_id FROM blog WHERE is_delete  = 0 AND blog_language_id=2')->num_rows(); 
                                    ?>
                                    </span>
                                </a>
                            </li>
                            <li class="<?=($sub == 'blog')?'active':'';?>">
                                <a class="<?=($sub == 'blog')?'active':'';?>" href="<?php echo base_url('category-master')?>">
                                    Blog Categories
                                    <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $result = $this->db->query('
                                        SELECT category_id FROM blog_categories WHERE is_delete  = 0 AND category_language_id=2')->num_rows(); 
                                    ?>
                                    </span>
                                </a>
                            </li>
                            <li class="<?=($sub == 'blog')?'active':'';?>">
                                <a class="<?=($sub == 'blog')?'active':'';?>" href="<?php echo base_url('tag-master')?>">
                                    Blog Tags
                                    <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo $result = $this->db->query('
                                        SELECT tag_id FROM blog_tags WHERE is_delete  = 0 AND tag_language_id=2')->num_rows(); 
                                    ?>
                                    </span>
                                </a>
                            </li>
                            <li class="<?=($sub == 'add-user')?'active':'';?>">
                                <a class="<?=($sub == 'add-user')?'active':'';?>" href="<?php echo base_url('add-blog')?>">
                                    Add New Blog
                                </a>
                            </li> 
                             
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(check_role(18) && 2!=2){ ?>
                        <li class="<?=($active == 'company-details')?'active':'';?>"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-apple-keyboard-command"></i><span class="hide-menu">Company Details</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="<?=($sub == 'company-details')?'active':'';?>"><a class="<?=($sub == 'company-details')?'active':'';?>" href="<?php echo $url."admin/";?>company-details">Company Details <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("company_details",false); ?>
                                </span></a></li>
                                <li class="<?=($sub == 'add-company-detail')?'active':'';?>"><a class="<?=($sub == 'add-company-detail')?'active':'';?>" href="<?php echo $url."admin/";?>add-company-detail">Add New Company Detail</a></li>
                                <li class="<?=($sub == 'trash')?'active':'';?>"><a class="<?=($sub == 'trash')?'active':'';?>" href="<?php echo $url."admin/";?>trash-company-details">Trash <span class="badge badge-pill badge-cyan ml-auto">
                                    <?php echo count_listing("company_details",true); ?>
                                </span></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                       
                        <li class="nav-small-cap">--- Other</li>
                        
                        <li> <a class="waves-effect waves-dark" href="<?php echo $url.'admin/change-password'; ?>" aria-expanded="false"><i class="fa fa-circle-o text-success"></i><span class="hide-menu">Change Password</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="<?php echo $url.'admin/logout'; ?>" aria-expanded="false"><i class="fa fa-circle-o text-success"></i><span class="hide-menu">Log Out</span></a></li>
                       
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

<div class="page-wrapper">