<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>User Setting</h2>
    	<ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Admin Setting</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            <a href="<?php echo base_url('admin')?>" class="btn btn-primary">Dashboard</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
<?php switch ($status){
		case 	'me':
			echo '<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Admin updation incorrect.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>';
		break;
		case	'sf':
			echo '<div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Admin update successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.</div>';
		break;
	}
?>
<div class="alert alert-danger alert-dismissable" style="display:none" id="user-name-ex">Login name already exists.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                	<h5>Basic Info<small>&emsp;Updated Deatils</small></h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
            	</div>
                <div class="ibox-content">
                    	<div class="alert alert-danger alert-dismissable" id="basic-info-error" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Your basic info has been updated incorrectly.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
                        <div class="alert alert-success alert-dismissable" id="basic-info-success" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Your basic info has been updated successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.</div>
                    	<form class="m-t" id="bioinfoForm" method="post">
                        <div class="col-sm-12">
                        	<div class="form-group">
                            	<label>First Name</label> 
                                <input type="text" placeholder="Enter the First Name" name="txtFirstName" id="txtFirstName" class="form-control" value="<?php echo $user['ACL_Fisrt_Name'];?>" required>
                            </div>
							<div class="form-group">
                            	<label>Last Name</label> 
                                <input type="text" placeholder="Enter the Last Name" name="txtLastName" id="txtLastName" class="form-control" value="<?php echo (!empty($user['ACL_Last_Name']))?$user['ACL_Last_Name']:'';?>">
                            </div>
                            <div class="form-group">ACL_Email
                        		<label for="Blog image">Email</label>
                        		<input type="email" placeholder="Enter the email" name="txtEmail" id="txtEmail" class="form-control" value="<?php echo $user['ACL_Email'];?>"/>
                        	</div>
                            <div class="form-group">
                        		<label for="Blog image">Mobile</label>
                        		<input type="number" placeholder="Enter the mobile number" name="txtMobile" id="txtMobile" class="form-control"  value="<?php echo (!empty($user['ACL_Phone_Number']))?$user['ACL_Phone_Number']:'';?>"/>
                        	</div>
							<div class="form-group">
                        		<label class="col-sm-2 col-form-label">Genter</label>
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="txtGenter" id="male" value="male"  <?php if($user['ACL_Gender']=='male'){?>checked<?php }?> required> Male
								</label>
								&emsp;&emsp;
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="txtGenter" id="female" value="female" <?php if($user['ACL_Gender']=='female'){?>checked<?php }?> required> Female
								</label>
                        	</div>
                            <div class="form-group">
                        		<label for="Blog image">About Me</label>
                        		<textarea class="form-control" name="txtAbout" id="txtAbout"></textarea>
                        	</div>
                        	<div>
                        		<button class="btn btn-info btn-rounded btn-block" type="submit" id="add-new-btn"><i class="fa fa-check"></i>&emsp;Update Info</button>
                    		</div>
                        </div>
                        </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
			<div class="ibox ">
                <div class="ibox-title">
                	<h5>Upload Profile Image<small>&emsp;Upload <strong>square</strong> image, maximum upload file size <strong>1 MB</strong></small></h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
            	</div>
                <div class="ibox-content">
                    	<div class="alert alert-danger alert-dismissable" id="upload-error" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><span id="uploadimage-error"></span>.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
                        <div class="alert alert-success alert-dismissable" id="upload-success" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Image upload has been updated successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.</div>
                    	<form class="m-t" role="form" id="upload-image">
                        <div class="col-sm-12">
							<div class="profile-image">
								<?php if(!empty($user['ACL_Customer_Image_Thumb'])){ ?>
                        			<img src="<?php echo base_url('static/upload/user/thumb/'.$user['ACL_Customer_Image_Thumb']); ?>" class="rounded-circle circle-border m-b-md" alt="profile">
								<?php } else{?>
									<img src="<?php echo ($user['ACL_Gender']=='male')?base_url('static/admin/default/img_avatar.png'):base_url('static/admin/default/img_avatar2.png'); ?>" class="rounded-circle circle-border m-b-md" alt="profile">
								<?php } ?>
                    		</div>
                        	<div class="form-group" style="padding-top: 30px;">
                        		<label for="Blog image">Profile Image</label>
                        		<input type="file" name="txtImage" id="txtImage" />
                        	</div>
                        	<div>
                        		<button class="btn btn-warning btn-rounded btn-block" type="submit" id="add-upload"><i class="fa fa-cloud-upload"></i>&emsp;Upload Image</button>
                    		</div>
                        </div>
                        </form>
                </div>
            </div>
            <div class="ibox ">
                <div class="ibox-title">
                	<h5>Reset Password<small>&emsp;Updated Password</small></h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
            	</div>
                <div class="ibox-content">
                    	<div class="alert alert-danger alert-dismissable" id="password-error" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Your password has been updated incorrectly.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
                        <div class="alert alert-success alert-dismissable" id="password-success" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Your password has been updated successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.</div>
                        <div class="alert alert-danger alert-dismissable" id="old-password-error" style="display:none;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Your old password has been entered incorrectly.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
                    	<form class="m-t" role="form" id="form-rest-password">
                        <div class="col-sm-12">
                        	<div class="form-group">
                            	<label>Old Password</label> 
                                <input type="password" placeholder="Enter the old password" name="txtOldPassword" id="txtOldPassword" class="form-control" value="" required>
                            </div>
                            
                            <div class="form-group">
                            	<label for="Blog image">Rest Password</label>
                                <input id="txtPassword" name="txtPassword" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) form.password_two.pattern = this.value;" placeholder="Password" class="form-control">


                            </div>
                        	<div class="form-group">
                        		<label for="Blog image">Password Confirm</label>
                        		<input id="password_two" name="password_two" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Please enter the same Password as above' : '');" placeholder="Verify Password" class="form-control">
                        	</div>
                        	<div>
                        		<button class="btn btn-success btn-rounded btn-block" type="submit" id="add-reset" disabled="disabled"><i class="fa fa-check"></i>&emsp;Reset Password</button>
                    		</div>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>