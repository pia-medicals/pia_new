<body class="">
	<div id="wrapper">
		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> 
                        	<span>
                                <img alt="image" class="img-circle" src="<?php echo $user['userImage']?>" width="64" height="64"/>
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                                <span class="clear">
                                     <span class="block m-t-xs"> <strong class="font-bold"></strong></span> 
                                     <span class="text-muted text-xs block"><b><?php echo ucfirst($user['loginName']);?></b></span> 
                                 </span> 
                             </a>
                            
                        </div>
                        <div class="logo-element">PIA</div>
					</li>
					<li>
                        <a href="<?php echo base_url('dashboard')?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span></a>
                    </li>
					<li>
                        <a href=""><i class="fa fa-podcast"></i> <span class="nav-label">Customer</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url('customer/profile');?>">Profile</a></li>
                            <li><a href="<?php echo base_url('customer/studies/all');?>">Studies</a></li>
                            <li><a href="<?php echo base_url('customer/documents/');?>">Contract</a></li>
                            <li><a href="<?php echo base_url('customer/bills/');?>">Bills</a></li>
                            
                        </ul>
                    </li>
                    <?php if(($user['loginType']==1)||($user['loginType']==3)){ ?>
                     <li>
                        <a href=""><i class="fa fa-users"></i> <span class="nav-label">User</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url('user/manageuser/addnew');?>">Add New User</a></li>
                            <li><a href="<?php echo base_url('user/manageuser/userlist');?>">User List</a></li>
							<li><a href="<?php echo base_url('user/contact');?>">Contact</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li><span class="m-r-sm text-muted welcome-message">Welcome to Piament Customer Portal</span></li>
                        <!--<li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            	<i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46h ago</small>
                                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a4.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right text-navy">5h ago</small>
                                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">23h ago</small>
                                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="">
                                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                        </a>
                                    </div>
                                </li>
                        	</ul>
                    	</li>-->
                        <!--<li class="dropdown">
                        	<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        		<i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                        	</a>
                        	<ul class="dropdown-menu dropdown-alerts">
                        		<li>
                        			<a href="">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                        			</a>
                        		</li>
                        		<li class="divider"></li>
                        		<li>
                        			<a href="">
                        				<div>
                        					<i class="fa fa-twitter fa-fw"></i> 3 New Followers
                        					<span class="pull-right text-muted small">12 minutes ago</span>
                        				</div>
                        			</a>
                        		</li>
                        		<li class="divider"></li>
                        		<li>
                        			<a href="">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                        			</a>
                        		</li>
                        		<li class="divider"></li>
                        		<li>
                        			<div class="text-center link-block">
                       			 		<a href="">
                        					<strong>See All Alerts</strong>
                        					<i class="fa fa-angle-right"></i>
                        				</a>
                        			</div>
                        		</li>
                        	</ul>
                        </li>-->
                     <li>
						<a href="<?php echo base_url('user/setting/manageinfo');?>">
							<i class="fa fa-cogs"></i> Setting
						</a>
					</li>   
					<li>
						<a href="<?php echo base_url('dashboard/logout');?>">
							<i class="fa fa-sign-out"></i> Log out
						</a>
					</li>
				</ul>
			</nav>
	</div>