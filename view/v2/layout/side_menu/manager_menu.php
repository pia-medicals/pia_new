
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

    <li><a href="<?=SITE_URL ?>/manager"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>




        <li class="treeview" data-active="dicom_details,dicom_details_assigned">
          <a href="#">
            <i class="fa fa-hospital-o"></i>
            <span>Worksheet</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li  data-active="dicom_details"><a href="<?=SITE_URL ?>/manager/dicom_details"><i class="fa fa-circle-o"></i> Open Worksheet</a></li>
            <li  data-active="dicom_details_assigned"><a href="<?=SITE_URL ?>/manager/dicom_details_assigned"><i class="fa fa-circle-o"></i> Assigned Worksheet</a></li>
          </ul>
        </li>






       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

