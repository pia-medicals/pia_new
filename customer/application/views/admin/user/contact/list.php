<?php //var_dump($list) ?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Contact Customer</h2>
    	<ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo base_url('admin/user/manage/newlist')?>">Admin List</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Contact List</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            <a href="<?php echo base_url('admin')?>" class="btn btn-primary">Dashboard</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<?php foreach($list as $value){?>
		<div class="col-lg-2">
			<div class="contact-box center-version">
				<a href="javascript:void(0)">
					<?php 
					if(!empty($value['ACL_Customer_Image_Thumb'])){
						$profileImage   =   base_url('static/upload/user/thumb/'.$value['ACL_Customer_Image_Thumb']);
					}
					else{
						$profileImage   =   ($value['ACL_Gender']=='male')?base_url('static/admin/default/img_avatar.png'):base_url('static/admin/default/img_avatar2.png'); 
					}
					?>
					<img alt="image" class="rounded-circle" src="<?php echo $profileImage?>" width="128" height="128">
					<h3 class="m-b-xs"><strong><?php echo $value['ACL_Fisrt_Name'].' '.$value['ACL_Last_Name'];?></strong></h3>
					<div class="font-bold"><?php echo $value['AD_Role_Name'];?></div>
					<address class="m-t-md">
						<strong><?php echo $value['ACL_Email'];?></strong><br>
						<?php echo $value['ACL_About_Customer'];?>
					</address>
					<div class="font-bold"><?php echo $value['ACL_Phone_Number'];?></div>
				</a>
				<div class="contact-box-footer">
					<div class="m-t-xs btn-group">
						<a href=""  class="btn btn-xs btn-primary"><i class="fa fa-phone"></i> Call </a>
						<a href=""  class="btn btn-xs btn-warning"><i class="fa fa-envelope"></i> Message</a>
						<?php if($value['ACL_Status']==1){ ?>
						<a href=""  class="btn btn-xs btn-danger"><i class="fa fa-user-plus"></i> Block</a>
						<?php } else{?>
						<a href=""  class="btn btn-xs btn-success"><i class="fa fa-user-plus"></i> Active</a>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>







