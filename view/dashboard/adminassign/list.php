<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
              <h2 class="box-title">Admin Assigned Customers</h2>
              
            </div>

            <div class="box-body">
            	<table id="customer_table" class="table table-bordered table-striped">
            		<thead>
					    <tr>
					     	<th>Customer Name</th>
					      <th>Customer Added By</th>
					      <th>Customer Added On</th>
					      <th>Customer Updated By</th>
					      <th>Customer Updated On</th>
					    </tr>
					 </thead>
					<tfoot>
					    <tr>
				      	<th>Customer Name</th>
				      	<th>Customer Added By</th>
				      	<th>Customer Added On</th>
				      	<th>Customer Updated By</th>
				      	<th>Customer Updated On</th>

					    </tr>
					</tfoot>
            	</table>
            </div>
            
            
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    var dataTable=$('#customer_table').DataTable({
    
      "lengthMenu": [ [100], [100] ],
      "order": [[ 2, "desc" ]],
      "ajax":{
        url:"/ajax/ajaxGetAssignedCustomerInfoDashboard",
        type:"post"
      },
    }); 
  });

</script>
