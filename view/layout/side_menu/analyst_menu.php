
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$img ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$user->name ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <div class="clearfix"></div>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

    <li><a href="<?=SITE_URL ?>/dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>




        <li class="treeview" data-active="open_work_sheets,my_work_sheets,dicom_details_all,add_work_sheet">
          <a href="#">
            <i class="fa fa-hospital-o"></i>
            <span>Studies</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li data-active="open_work_sheets,add_work_sheet"><a href="<?=SITE_URL ?>/dashboard/open_work_sheets"><i class="fa fa-circle-o"></i> Open Studies</a></li>
            <li  data-active="my_work_sheets"><a href="<?=SITE_URL ?>/dashboard/my_work_sheets"><i class="fa fa-circle-o"></i> My Studies</a></li>
            <li data-active="dicom_details_all"><a href="<?=SITE_URL ?>/dashboard/dicom_details_all"><i class="fa fa-circle-o"></i> All Studies</a></li>
            <li data-active="dicom_details_month"><a href="<?=SITE_URL ?>/dashboard/dicom_details_month"><i class="fa fa-circle-o"></i> Current Month</a></li>
          </ul>
        </li>




    <li data-active="miscellaneous_billing,add_miscellaneous_billing"><a href="<?=SITE_URL ?>/dashboard/miscellaneous_billing"><i class="fa fa-book"></i><span>Miscellaneous Billing</span></a></li>
    <li data-active="assigned_customer"><a href="<?=SITE_URL ?>/dashboard/assigned_customer"><i class="fa fa-user"></i><span>Admin Assigned Customers</span></a></li>





       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>





















