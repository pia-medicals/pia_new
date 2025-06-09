<link href="<?php echo base_url('static/admin/css/plugins/dataTables/datatables.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('static/admin/css/plugins/sweetalert/sweetalert.css')?>" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Documents List</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
			<li class="breadcrumb-item active">
                <strong>Documents List</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="alert alert-success alert-dismissable" id="delete-msg" style="display:none">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Package delete successfully. &emsp;&emsp;<a class="alert-link" href="#">Alert Success</a>.
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>All Documents</h5>
	    </div>
	    
    	<div class="ibox-content">
    		
			<div class="table-responsive">
				<!-- <style type="text/css">
					table{
						width: 100% !important;
						table-layout: fixed !important;
					}
				</style> -->
				
                <table class="table table-striped table-bordered table-hover" id="dicom-list">
                    <thead>
						<tr>
							<th>Title</th>
							<th>Description</th>
							<!-- <th>Document Added By</th>
					      	<th>Document Added On</th>
					      	<th>Document Updated By</th>
					      	<th>Document Updated On</th> -->
					      	<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($dicom_docs as $key => $doc) :?>
							<tr>
								<td><?php echo $doc['ACD_Docs_Title'] ?></td>
								<td><?php echo $doc['ACD_Docs_Desc'] ?></td>
								<!-- <td><?php //echo $doc['ACD_Docs_Desc'] ?></td>
								<td><?php echo date('d-m-Y H:i:s', strtotime($doc['ACD_User_Add_On'])) ?></td>
								<td><?php //echo $doc['ACD_Docs_Desc'] ?></td>
								<td><?php echo date('d-m-Y H:i:s', strtotime($doc['ACD_User_Updated_On'])) ?></td> -->
								<td id="status-<?php echo $doc['ACD_ID_PK'];?>"><?php echo ($doc['ACD_Status']==1)?'<span class="pcoded-badge label label-primary">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td>								
								<td><a href="<?php echo 'http://dicon.tecbirds.com/assets/uploads/customer/'.$doc['ACD_Customer_ID_FK'].'/'.$doc['ACD_Docs_Path'];?>" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Title</th>
							<th>Description</th>
							<!-- <th>Document Added By</th>
					      	<th>Document Added On</th>
					      	<th>Document Updated By</th>
					      	<th>Document Updated On</th> -->
					      	<th>Status</th>
							<th>Actions</th>
						</tr>
					</tfoot>
                </table>
    		</div>
        </div>
    </div>
</div>
