<link href="<?php echo base_url('static/admin/css/plugins/dataTables/datatables.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('static/admin/css/plugins/sweetalert/sweetalert.css')?>" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Bills List</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
			<li class="breadcrumb-item active">
                <strong>Bills List</strong>
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
            <h5>All Bills</h5>
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
			                <th>Invoice No</th>
			                <th>Month & Year</th>
			                <th>Due Date</th>
			                <th>Total</th>
			                <th>Discount</th>
			                <th>Invoice Amount</th>
			                <th>Status</th>
			                <th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($dicom_docs as $key => $value) :?>
							<tr>
			                  <td><?php echo $value['ACB_Bills_Title'] ?></td>
			                  <td><?php echo $value['ACB_Bills_Desc']  ?></td>
			                  <td><?php echo $value['ACB_Bills_Invoice_No'] ?></td>
			                  <td><?php echo date('F', mktime(0, 0, 0, $value['ACB_Bills_Month'], 10));  ?>, <?php echo $value['ACB_Bills_Year'] ?></td>
			                  <td><?php echo date('d-m-Y',strtotime($value['ACB_Bills_Due'] ))?></td>
			                  <td><?php echo $value['ACB_Bills_Total']  ?></td>
			                  <td><?php echo $value['ACB_Bills_Discount'] ?></td>
			                  <td><?php echo $value['ACB_Bills_Invoice_Amount']  ?></td>
			                  <td id="status-<?php echo $value['ACB_ID_PK'];?>"><?php echo ($value['ACB_Status']==1)?'<span class="pcoded-badge label label-primary">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td>
			                  <td><a href="<?php echo 'http://dicon.tecbirds.com/assets/uploads/customer/bills/'.$value['ACB_Customer_ID_FK'].'/'.$value['ACB_Bills_Path'];?>" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a></td>
			                </tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Title</th>
			                <th>Description</th>
			                <th>Invoice No</th>
			                <th>Month & Year</th>
			                <th>Due Date</th>
			                <th>Total</th>
			                <th>Discount</th>
			                <th>Invoice Amount</th>
			                <th>Status</th>
			                <th>Actions</th>
						</tr>
					</tfoot>
                </table>
    		</div>
        </div>
    </div>
</div>
