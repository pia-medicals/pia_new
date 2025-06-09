<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?=$img ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?=$user->user_name ?></p>
        <a href="<?=SITE_URL ?>/hme"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <div class="clearfix"></div>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
       <li><a href="<?=SITE_URL ?>/hme"><i class="fa fa-home"></i> <span>Home</span></a></li>
      <li><a href="<?=SITE_URL ?>/admin"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

      <li data-active="user,add_user"><a href="<?=SITE_URL ?>/admin/user"><i class="fa fa-user"></i> <span>User</span></a></li>


      <li class="treeview" data-active="dicom_details,dicom_details_assigned,dicom_details_all">
        <a href="#">
          <i class="fa fa-hospital-o"></i>
          <span>Studies</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li data-active="dicom_details"><a href="<?=SITE_URL ?>/admin/dicom_details"><i class="fa fa-circle-o"></i> Open Studies</a></li>
          <li data-active="dicom_details_assigned"><a href="<?=SITE_URL ?>/admin/dicom_details_assigned"><i class="fa fa-circle-o"></i> Assigned Studies</a></li>
          <li data-active="dicom_details_all"><a href="<?=SITE_URL ?>/admin/dicom_details_all"><i class="fa fa-circle-o"></i> All Studies</a></li>
          <li data-active="dicom_details_month"><a href="<?=SITE_URL ?>/admin/dicom_details_month"><i class="fa fa-circle-o"></i> Current Month</a></li>
         <li data-active="dicom_details_month"><a href="<?=SITE_URL ?>/adminstat/stat_all"><i class="fa fa-circle-o"></i> Stat Report</a></li>
        </ul>
      </li>


      <li data-active="customer,add_customer"><a href="<?=SITE_URL ?>/admin/customer"><i class="fa fa-user"></i> <span>View Customers</span></a></li>

<!--      <li data-active="analyses,analyses_add"><a href="<?=SITE_URL ?>/admin/analyses"><i class="fa fa-list-alt"></i> <span>Analyses</span></a></li>-->
      
      
       <li class="treeview" data-active="analyses,analyses_add,analyses_category,analyses_category_add">
        <a href="#">
          <i class="fa fa-list-alt"></i>
          <span>Analyses</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li data-active="analyses,analyses_add"><a href="<?=SITE_URL ?>/admin/analyses"><i class="fa fa-circle-o"></i> Analyses Details</a></li>
          
          <li data-active="analyses_category,analyses_category_add"><a href="<?=SITE_URL ?>/admin/analyses_category"><i class="fa fa-circle-o"></i> Analyses Categories</a></li>
        </ul>
      </li>
      

      <!-- <li><a href="<?=SITE_URL ?>/admin/billing_hours"><i class="fa fa-book"></i> <span>Billing Hours</span></a></li> -->

      <li data-active="miscellaneous_billing,add_miscellaneous_billing"><a href="<?=SITE_URL ?>/admin/miscellaneous_billing"><i class="fa fa-book"></i><span>Miscellaneous Billing</span></a></li>
	
      <li class="treeview"  data-active="billing,billing_summary_customer,billing_summary_detailed,billing_summary_analyst">
        <a href="#">
          <i class="fa fa-hospital-o"></i>
          <span>Accounts</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li data-active="billing"><a href="<?=SITE_URL ?>/admin/billing"><i class="fa fa-circle-o"></i> Billing Summary</a></li>
          <li data-active="billing3"><a href="<?=SITE_URL ?>/admin/billing_summary_customer"><i class="fa fa-circle-o"></i> Billing Summary - Customer</a></li>
          <li data-active="billing2"><a href="<?=SITE_URL ?>/admin/billing_summary_detailed"><i class="fa fa-circle-o"></i> Billing Summary - Detailed</a></li>
          <li data-active="billing4"><a href="<?=SITE_URL ?>/admin/billing_summary_analyst"><i class="fa fa-circle-o"></i> Billing Summary - Analyst</a></li>
        </ul>
      </li>

         <li class="treeview" data-active="study_time_report,study_time_graph">
        <a href="#">
          <i class="fa fa-file"></i>
          <span>Reports</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
           <li data-active="study_time_report"><a href="<?=SITE_URL ?>/admin/study_time_report"><i class="fa fa-book"></i><span>Study Time Report</span></a></li>
         <li data-active="study_time_graph"><a href="<?=SITE_URL ?>/admin/study_time_graph"><i class="fa fa-book"></i><span>Study Time Graphical Report</span></a></li>
         
        </ul>
      </li>
      <li data-active="adminassign,assign,edit"><a href="<?=SITE_URL ?>/adminassign"><i class="fa fa-user"></i> <span>Admin Assigned</span></a></li>
      <li><a href="<?=SITE_URL ?>/analystrate/ratereport"><i class="fa fa-star-half-empty"></i> <span>Performance</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>