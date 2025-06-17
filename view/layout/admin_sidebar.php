<?php 

switch ($data['user']->user_type_ids) {
        case 1:
           $cntrlr = 'admin';
          break;
        case 2:
           $cntrlr = 'manager';
          break;
        
        default:
          $cntrlr = 'dashboard';
          break;
      }

 ?>
  <header class="main-header">
    <!-- Logo -->
    <a href="<?=SITE_URL ?>/<?=$cntrlr ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>PIA</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">PIA Medical</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
 

                <?php 
            if(isset($user->profile_picture) && $user->profile_picture != "") 
            $img = SITE_URL.'/assets/uploads/user/'.$user->profile_picture;
            else
            $img = SITE_URL.'/assets/uploads/user/avatar.png';
          ?>

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="<?=SITE_URL ?>/hme" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=$img ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$user->user_name ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">

                <img src="<?=$img ?>" class="img-circle" alt="User Image">

                <p>
                  <?=$user->user_name ?>
                  <small>Member since <?=date("d-m-Y", strtotime($user->created_at)); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->






              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?=SITE_URL ?>/<?=$cntrlr ?>/profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?=SITE_URL ?>/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        
        </ul>
      </div>
    </nav>
  </header>

















<div class="black_bg"></div>




