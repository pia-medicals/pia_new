<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static\admin\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static\admin\assets\pages\data-table\css\buttons.dataTables.min.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static\admin\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css');?>">
<div class="pcoded-content">
	<div class="pcoded-inner-content">
		<div class="main-body">
			<div class="page-wrapper">
				<!-- Page-header start -->
				<div class="card borderless-card">
					<div class="card-block inverse-breadcrumb">
						<div class="breadcrumb-header">
							<h5>User List</h5>
							<span>Supper admin can view the list</span>
						</div>
						<div class="page-header-breadcrumb">
							<ul class="breadcrumb-title">
								<li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')?>"><i class="icofont icofont-home"></i></a></li>
								<li class="breadcrumb-item"><a href="<?php echo base_url('user/manageuser/addnew')?>">Add New User</a></li>
								<li class="breadcrumb-item text-warning"><b>User List</b></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- Page-header end -->
				<div class="page-body">
					<div class="row">
						<div class="col-sm-12">
							<!-- Zero config.table start -->
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>User Details</h5>
                                                    

                                                </div>
                                                <div class="card-block">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                                            <thead>
																<tr>
																	<th>User Name</th>
																	<th>Email</th>
																	<th>Phone</th>
																	<th>Type</th>
																	<th>Status</th>
																	<th>Action</th>
																</tr>
                                                            </thead>
                                                            <tbody>
																<?php foreach($list as $value){?>
                                                                <tr>
                                                                	<td><?php echo $value['ACL_Fisrt_Name'].' '.$value['ACL_Last_Name']?></td>   
																	<td><?php echo $value['ACL_Email']?></td> 
																	<td><?php echo $value['ACL_Phone_Number']?></td> 
																	<td><?php echo ucfirst($value['AD_Role_Name'])?></td> 
																	<td><?php echo ($value['ACL_Status']==1)?'<span class="pcoded-badge label label-success">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td> 
																	<td>
																		<button class="btn btn-success btn-outline-success btn-icon"><i class="icofont icofont-eye-alt"></i></button>&emsp;
																		<button class="btn btn-warning btn-outline-warning btn-icon"><i class="icofont icofont-check-circled"></i></button>&emsp;
																		<button class="btn btn-danger btn-outline-danger btn-icon"><i class="icofont icofont-close-circled"></i></button>
																	</td> 
                                                                </tr>
																<?php } ?>
															</tbody>	
                                                            <tfoot>
																<tr>
																	<th>User Name</th>
																	<th>Email</th>
																	<th>Phone</th>
																	<th>Gender</th>
																	<th>Status</th>
																	<th>Action</th>
                                                            	</tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Zero config.table end -->
								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>