<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Add New Admin</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
			<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin/user/manage/newlist')?>">Admin List</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Add New Admin</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            <a href="<?php echo base_url('admin/user/manage/newlist')?>" class="btn btn-primary">Admin List</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox ">
                <div class="ibox-title">
                	<h5>Add New Admin<small></small></h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
            	</div>
                <div class="ibox-content">
						<div class="alert alert-danger alert-dismissable" style="display:none" id="user-name-ex"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Login email already exists.&emsp;&emsp;<a class="alert-link" href="#">Alert Error</a>.</div>
                    	<form class="m-t" role="form" action="<?php echo base_url('user/manageuser');?>" method="post">
                        <div class="col-sm-12">
                        	<div class="form-group">
                            	<label>Admin Full Name</label> 
                                <input type="text" placeholder="Enter the Full Name" name="txtName" id="txtName" class="form-control" required/>
                            </div>
                            <div class="form-group">
                        		<label for="Blog image">Email</label>
                        		<input type="email" placeholder="Enter the email" name="txtEmail" id="txtEmail" class="form-control" required/>
                        	</div>
                        	<div class="form-group">
                            	<label for="Blog image">Password</label>
                                <input id="password" name="txtPassword" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Must have at least 6 characters' : ''); if(this.checkValidity()) form.password_two.pattern = this.value;" placeholder="Password" required class="form-control">


                            </div>
                        	<div class="form-group">
                        		<label for="Blog image">Password Confirm</label>
                        		<input id="password_two" name="password_two" type="password" pattern="^\S{6,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Please enter the same Password as above' : '');" placeholder="Verify Password" required class="form-control">
                        	</div>
                            
                            <div class="form-group">
                        		<label class="col-sm-2 col-form-label">Genter</label>
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="txtGenter" id="male" value="male" required> Male
								</label>
								&emsp;&emsp;
								<label class="form-check-label">
									<input class="form-check-input" type="radio" name="txtGenter" id="female" value="female" required> Female
								</label>
											
                        	</div>
                            <div class="form-group">
                        		<label for="Blog image">Admin Type</label>
                        		<select name="txtAdminType" class="form-control m-b" id="txtAdminType" required>
                                    <option value="">Select Type</option>
                                    <?php foreach($adminrole as $value){ ?>
                                    <option value="<?php echo $value['AR_ID_PK']?>"><?php echo $value['AD_Role_Name']?></option>
                                    <?php } ?>
                                </select>
                        	</div>
                        	<div>
                        		<button class="btn btn-primary btn-rounded btn-block" type="submit" id="add-new-btn"><i class="fa fa-check"></i>&emsp;Add New Admin</button>
                    		</div>
                        </div>
                        </form>
                        
                </div>
            </div>
        </div>
    </div>
</div>