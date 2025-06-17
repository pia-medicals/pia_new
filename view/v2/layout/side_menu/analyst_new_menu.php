<?php
// switch ($data['user']->user_type_ids) {
//     case 1:
//         //$cntrlr = 'admin';
//         $cntrlr = 'mydashboard';
//         break;
//     case 2:
//         $cntrlr = 'manager';
//         break;
//     default:
//         $cntrlr = 'dashboard';
//         break;
// }
if (isset($user->profile_picture) && $user->profile_picture != "") {
    $img = SITE_URL . '/assets/uploads/user/' . $user->profile_picture;
} else {
    $img = SITE_URL . '/assets/uploads/user/avatar.png';
}
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    

    <a href="" class="brand-link text-center logo-mini">
        <span class="brand-text font-weight-light mini-brand">PIA</span>
    </a>

    <a href="" class="brand-link text-center logo-lg">
        <span class="brand-text font-weight-light lg-brand">PIA Medical</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $img ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <!-- <a href="" class="d-block"><?= $user->user_name ?></a> -->
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item" data-active="<?php echo $cntrlr; ?>">
                    <a href="<?= SITE_URL ?>/analyst_dashboard" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>


                <li class="nav-item" data-active="dicom_details,dicom_details_assigned,dicom_details_all">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Studies
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">


                        <li class="nav-item" data-active="dicom_details_all">
                            <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_all" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Studies</p>
                            </a>
                        </li>

                        <li class="nav-item" data-active="dicom_details_my">
                            <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_my" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>MY Studies</p>
                            </a>
                        </li>

                    
                        <li class="nav-item" data-active="dicom_details_open">
                            <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_open" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Open Studies</p>
                            </a>
                        </li>
                        
                        <li class="nav-item" data-active="">
                            <a href="<?= SITE_URL ?>/analyst/current_month_studies" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Current Month</p>
                            </a>
                        </li>
                        <?php /* <li class="nav-item" data-active="dicom_details_month">
                            <a href="<?= SITE_URL ?>/customer_stat_report" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stat Report</p>
                            </a>
                        </li> */ ?>



                    </ul>
                </li> 

                <li class="nav-item">
                    <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>Miscellaneous Billing</p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>