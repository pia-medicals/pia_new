<link href="<?php echo base_url('static/admin/css/plugins/dataTables/datatables.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('static/admin/css/plugins/sweetalert/sweetalert.css')?>" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Admin List</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
			<li class="breadcrumb-item active">
                <strong>Admin List</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            <a href="<?php echo base_url('admin/user/manage/addnew')?>" class="btn btn-primary">Add New Admin</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="alert alert-success alert-dismissable" id="delete-msg" style="display:none">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Package delete successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>User List</h5>
	    </div>
    	<div class="ibox-content">
			<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example">
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
							<td id="status-<?php echo $value['ACL_ID_PK'];?>"><?php echo ($value['ACL_Status']==1)?'<span class="pcoded-badge label label-primary">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td> 
							<td>
                            	<a href="<?php echo base_url('admin/user/manage/edit/'.$value['ACL_ID_PK']);?>" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="Treatment Edit"><i class="fa fa-paste"></i></a>&emsp;
                                <button type="button" class="btn btn-warning btn-circle btn-outline change-status" id="check-<?php echo $value['ACL_ID_PK']?>" data-status="<?php echo $value['ACL_Status'];?>"><i class="fa fa-check"></i></button>&emsp;
                            	<button type="button" class="btn btn-danger btn-circle btn-outline delete" id="delete-<?php echo $value['ACL_ID_PK'];?>"><i class="fa fa-times"></i></button>
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
</div>