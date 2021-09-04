
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
    .new-notification{
        background: #f0f0f0;
    }
</style>