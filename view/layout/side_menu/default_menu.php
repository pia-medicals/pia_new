
      <div class="main side">
        <aside>
          <div class="sidebar left ">
            <div class="user-panel">
              <div class="pull-left image">
                <?php 
                  if(isset($user->profile_picture) && $user->profile_picture != "") 
                    $img = SITE_URL.'/assets/uploads/user/'.$user->profile_picture;
                  else
                    $img = SITE_URL.'/assets/uploads/user/avatar.png';
                ?>
                <img src="<?=$img ?>" class="img-responsive" alt="User Image">
              </div>
              <div class="pull-left info">
                <p><a class="edit_pro" href='<?=SITE_URL ?>/dashboard/profile'> <?=$user->name ?></a></p><!-- 
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
              </div>
            </div>
            <ul class="list-sidebar bg-defoult">
            <li> <a href="<?=SITE_URL ?>/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span></a> </li>


    </ul>
    </div>
    </aside>
    </div>