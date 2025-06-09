<?php
switch ($data['user']->user_type_ids) {
    case 1:
        //$cntrlr = 'admin';
        $cntrlr = 'mydashboard';
        break;
    case 2:
        $cntrlr = 'manager';
        break;
    default:
        $cntrlr = 'dashboard';
        break;
}
if (isset($user->profile_picture) && $user->profile_picture != "") {
    $img = SITE_URL . '/assets/uploads/user/' . $user->profile_picture;
} else {
    $img = SITE_URL . '/assets/uploads/user/avatar.png';
}
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="brand-link text-center logo-mini">  
        <span class="brand-text font-weight-light mini-brand">PIA</span>
    </a>

    <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="brand-link text-center logo-lg"> 
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
                <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="d-block"><?= $user->user_name ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item" data-active="<?php echo $cntrlr; ?>">
                    <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item" data-active="adminV2">
                    <a href="<?= SITE_URL . '/adminV2' ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item" data-active="user,add_user">
                    <a href="<?= SITE_URL ?>/admin/user" class="nav-link">
                        <i class="nav-icon fas fa-user"></i> 
                        <p>
                            User Setup
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
                        <?php /*
                          <!-- <li class="nav-item" data-active="dicom_details">
                          <a href="<?= SITE_URL ?>/admin/dicom_details" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Open Studies</p>
                          </a>
                          </li>
                          <li class="nav-item" data-active="dicom_details_assigned">
                          <a href="<?= SITE_URL ?>/admin/dicom_details_assigned" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Assigned Studies</p>
                          </a>
                          </li>
                          <li class="nav-item" data-active="dicom_details_all">
                          <a href="<?= SITE_URL ?>/admin/dicom_details_all" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>All Studies</p>
                          </a>
                          </li>
                          <li class="nav-item" data-active="dicom_details_month">
                          <a href="<?= SITE_URL ?>/admin/dicom_details_month" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Current Month</p>
                          </a>
                          </li>
                          <li class="nav-item" data-active="dicom_details_month">
                          <a href="<?= SITE_URL ?>/adminstat/stat_all" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Stat Report</p>
                          </a>
                          </li>-->
                          <!-- <li class="nav-item" data-active="dicom_details_month">
                          <a href="<?= SITE_URL ?>/admin/dicom_details_month" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Current Month</p>
                          </a>
                          </li>-->
                         */ ?>

                        <li class="nav-item" data-active="dicom_details_all">
                            <a href="<?= SITE_URL ?>/admin/dicom_details_all" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Studies</p>
                            </a>
                        </li>

                        <li class="nav-item" data-active="dicom_details_month">
                            <a href="<?= SITE_URL ?>/admin/stat_report" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stat Report</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item" data-active="customer,add_customer">
                    <a href="<?= SITE_URL ?>/admin/customer" class="nav-link">
                        <i class="nav-icon fas fa-users"></i> 
                        <p>
                            Client Setup
                        </p>
                    </a>
                </li>    

                <li class="nav-item" data-active="analyses,analyses_add,analyses_category,analyses_category_add">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Analyses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" data-active="analyses,analyses_add">
                            <a href="<?= SITE_URL ?>/admin/analyses" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Global Analyses + Price List</p>
                            </a>
                        </li>
                        <li class="nav-item" data-active="analyses_category,analyses_category_add">
                            <a href="<?= SITE_URL ?>/admin/analyses_category" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Analyses Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SITE_URL ?>/turnaround_time" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>TAT Setup</p>
                            </a>
                        </li>                        
                    </ul>
                </li>

                <?php /*
                <li class="nav-item" data-active="analyses,analyses_add,analyses_category,analyses_category_add">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            TAT Setup
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" data-active="analyses,analyses_add">
                            <a href="<?= SITE_URL ?>/turnaround_time" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>TAT List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                */ ?>

                <?php /*
                  <li class="nav-item" data-active="miscellaneous_billing,add_miscellaneous_billing">
                  <a href="<?= SITE_URL ?>/admin/miscellaneous_billing" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                  Miscellaneous Billing
                  </p>
                  </a>
                  </li> */ ?>

                <li class="nav-item" data-active="billing,billing_summary_customer,billing_summary_detailed,billing_summary_analyst">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Accounts
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php /* <li class="nav-item" data-active="billing">
                          <a href="<?= SITE_URL ?>/admin/billing" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Billing Summary</p>
                          </a>
                          </li> */ ?>
                        <li class="nav-item" data-active="billing3">
                            <a href="<?= SITE_URL ?>/report/billing_summary_customer" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Billing Summary: Customer</p>
                            </a>
                        </li>
                        <li class="nav-item" data-active="billing2">
                            <a href="<?= SITE_URL ?>/report/billing_summary_detailed" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Billing Summary: Detailed</p>
                            </a>
                        </li>
                        <li class="nav-item" data-active="billing4">
                            <a href="<?= SITE_URL ?>/report/billing_summary_analyst" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Billing Summary: Analyst</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item" data-active="study_time_report,study_time_graph">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" data-active="study_time_report">
                            <a href="<?= SITE_URL ?>/report/study_time_report" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Study Time Report</p>
                            </a>
                        </li>
                        <?php /*
                          <li class="nav-item" data-active="study_time_graph">
                          <a href="<?= SITE_URL ?>/admin/study_time_graph" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Study Time Graph Report</p>
                          </a>
                          </li>
                         */ ?>
                    </ul>
                </li>

                <li class="nav-item" data-active="adminassign,assign,edit">
                    <a href="<?= SITE_URL ?>/adminassign" class="nav-link">
                        <i class="nav-icon fas fa-user-tag"></i> 
                        <p>
                            Admin Assigned
                        </p>
                    </a>
                </li>

                <li class="nav-item" data-active="ratereport">
                    <a href="<?= SITE_URL ?>/analystrate/ratereport" class="nav-link">
                        <i class="nav-icon fas fa-star-half-alt"></i> 
                        <p>
                            Performance
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
