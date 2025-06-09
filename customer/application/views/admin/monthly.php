
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Monthly Report</h2>
    	<ol class="breadcrumb">
        	<li class="breadcrumb-item">
                <a href="<?php echo base_url('admin')?>">Monthly Report</a>
            </li>
			<li class="breadcrumb-item active">
                <strong>Monthly Report</strong>
            </li>
    	</ol>
    </div>
   
</div>
<div class="wrapper wrapper-content">
    <!--<div class="ibox float-e-margins">
		<div class="ibox-content">
			<form action="" method="post">
				<div class="form-group">
                        		<label for="Blog image">Select User</label>
                        		<select name="txtUser" class="form-control m-b" id="txtUser" required>
                                    <option value="">Select Type</option>
                                    <?php foreach($user as $value){ ?>
                                    <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                                    <?php } ?>
                                </select>
                        	</div>
				<div class="form-group">
					<input type="submit" name="btnReport" value="Report"/>
				</div>
			</form>
		</div>
	</div>-->
	<?php if(!empty($list)){?>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5><?php //echo $list[0]['name'] ?></h5>
	    </div>
    	<div class="ibox-content">
			
			<button type="button" class="btn btn-primary btn-flat" id="export">Download CSV</button>
			<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example" id="report-table">
                    <thead>
						<tr>
							<th>SL NO</th>
							<th>Item</th>
							<th>Customer</th>
							<th>Date</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>	
						
						<?php $i=1;foreach($listNew as $key=>$value){ if(!empty($value['analyses_performed'])){?>
						<tr class="noExl">
							<td><?php echo $i?></td>
							<td><?php echo $value['analyses_performed']?></td>
							<td><?php echo $value['name']?></td>
							<td><?php echo $value['date']?></td><?php ?>
							<td><?php echo $value['Numcount']?></td>
						</tr>
						<?php $i++;}  }?>
					</tbody>	
					<tfoot>
						<tr>
							<th>SL NO</th>
							<th>Item</th>
							<th>Count </th>
						</tr>
					</tfoot>
                </table>
    		</div>
			<!--<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dataTables-example" id="report-table1">
                    <thead>
						<tr>
							<th>SL NO</th>
							<th>Item</th>
							<th>Customer</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>	
						<?php foreach($list as $key=>$maindata){ if(!empty($maindata)){?>
						<thead>
							<tr class="noExl">
								<th colspan="4"><?php echo $key ?></th>
							</tr>
						</thead>
						<?php $i=1;foreach($maindata as $row=>$value){ if(!empty($value['analyses_performed'])){?>
						<tr class="noExl">
							<td><?php echo $i?></td>
							<td><?php echo $value['analyses_performed']?></td>
							<td><?php echo $value['name']?></td>
							<td><?php echo $value['Numcount']?></td>
						</tr>
						<?php $i++;}  }?>
						<?php  } }?>
					</tbody>	
					<tfoot>
						<tr>
							<th>SL NO</th>
							<th>Item</th>
							<th>Count </th>
						</tr>
					</tfoot>
                </table>
    		</div>-->
			
        </div>
    </div>
	<?php } ?>
</div>