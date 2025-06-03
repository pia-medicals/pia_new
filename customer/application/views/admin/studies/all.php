<link href="<?php echo base_url('static/admin/css/plugins/dataTables/datatables.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('static/admin/css/plugins/sweetalert/sweetalert.css')?>" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Studies List</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Dashboard</a>
            </li>
			<li class="breadcrumb-item active">
                <strong>Studies List</strong>
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
            <h5>All Studies</h5>
	    </div>
	    
    	<div class="ibox-content">
    		<div class="dataTables_wrapper" > 
                <div class="dataTables_length" > 
			    	<label>
			    		<span class="margin-right-5">Show last </span> 
			    		<select name="day_select" aria-controls="admin_table" id="day_select" class="form-control width-87 height-35 margin-right-5">
		                          <option value="">All </option> 
		                          <option value="1"> 1 day </option>
		                          <option value="3"> 3 days</option>
		                          <option value="30">30 days</option>
		                      </select>
		                <span class="margin-right-5">Asignee</span> 
						<select name="asignee_select" aria-controls="admin_table" id="asignee_select" class="form-control width-150 height-35 margin-right-5">
						     <option value="">Select Asignee</option>
						      <?php if(isset($asignee)):
						        foreach ($asignee as $key => $value): ?>
						          <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
						        <?php endforeach; endif; ?>
						</select>
						<span class="margin-right-5">Status </span>
						<select name="status_select" aria-controls="admin_table" id="status_select" class="form-control width-140 height-35 margin-right-5">
						  <option value=""> None</option>
						  <option value="Completed" > Completed</option>
						  <option value="Under review" > Under review</option>
						  <option value="In progress" > In progress</option>
						  <option value="Cancelled" > Cancelled</option>
						  <option value="On hold" > On hold</option>  
						</select>
						<span class="margin-right-5">Second Check </span>
						<select name="secondcheck_select" aria-controls="admin_table" id="secondcheck_select" class="form-control width-140 height-35 margin-right-5">
			                <option value=""> None</option>
			                <option value="1"> Yes</option>
			                <option value="2" > No</option>
			             </select>

			            

			            <input type="button" id="reset_filter" value="Reset Filter" class="btn btn-danger" name="reset_filter" />
					</label>
					<div class="btn-group" role="group" aria-label="Basic example">
				  		<button type="button" class="btn btn-success btn-status" data-value="Completed">Completed</button>
				  		<button type="button" class="btn btn-info btn-status" data-value="In progress">Inprogress</button>
				  		<button type="button" class="btn btn-primary btn-days" data-value="1">Last 1 day</button>
				  		<button type="button" class="btn btn-warning btn-days" data-value="30">Last 1 month</button>
					</div>
			    </div>
		    </div>
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
							<th>Received Date</th>
							<th>Accession</th>
							<th>Patient Name</th>
							<th>MRN</th>
							<th>TAT</th>
							<th>Customer Name</th>
							<th>Assignee</th>
							<th>Second Check</th>
							<th>Description</th>
							<th>Status</th>
							<!-- <th>Action</th> -->
						</tr>
					</thead>
						
					<tfoot>
						<tr>
							<th>Received Date</th>
							<th>Accession</th>
							<th>Patient Name</th>
							<th>MRN</th>
							<th>TAT</th>
							<th>Customer Name</th>
							<th>Assignee</th>
							<th>Second Check</th>
							<th>Description</th>
							<th>Status</th>
							<!-- <th>Action</th> -->
						</tr>
					</tfoot>
                </table>
    		</div>
        </div>
    </div>
</div>
