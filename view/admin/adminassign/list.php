<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
              <h2 class="box-title">Admin Assigned Customers</h2>
              <a href="<?php echo SITE_URL.'/adminassign/assign'; ?>" class="btn btn-primary pull-right">Assign</a>
            </div>

            <div class="box-body">
            	<table id="customer_table" class="table table-bordered table-striped">
            		<thead>
					    <tr>
					     	<th>Customer Name</th>
					      	<th>Analyst Name</th>
					      	<th>Customer Added By</th>
					      	<th>Customer Added On</th>
					      	<th>Customer Updated By</th>
					      	<th>Customer Updated On</th>
					      	<th>Status</th>
					      	<th>Action</th>
					    </tr>
					 </thead>
					<tfoot>
					    <tr>
					      	<th>Customer Name</th>
					      	<th>Analyst Name</th>
					      	<th>Customer Added By</th>
					      	<th>Customer Added On</th>
					      	<th>Customer Updated By</th>
					      	<th>Customer Updated On</th>
					      	<th>Status</th>
					      	<th>Action</th>

					    </tr>
					</tfoot>
            	</table>
            </div>
            
            
        </div>
    </section>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
<script type="text/javascript">
    $(document).ready(function(){
    	
    var dataTable=$('#customer_table').DataTable({
    
      "lengthMenu": [ [100], [100] ],
      "order": [[ 3, "desc" ]],
      "ajax":{
        url:"/ajax/ajaxGetAssignedCustomerInfo",
        type:"post"
      },
    }); 

    $('#customer_table').on('click', '.change_status', function(){
    	var status      =   $(this).attr('data-status');
    	var id 			=	$(this).attr('data-id');
    	var $t 			= 	$(this);
    	swal({
		  title: "Are you sure?",
		  text: "Are you sure you want to change the status!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-info",
		  confirmButtonText: "Yes, Change Status!",
		  cancelButtonText :   "No, Cancel !",
		  closeOnConfirm: false
		},
		function(){
			$.ajax({
	            url     :   '/ajax/ajaxchangeStatus',
	            type    :   'POST',
	            data    :   {id    :  id,status    :  status},
	            success:function(data){
	            	console.log(data);
	            	if (data.type = 'success') {
	            		var newstatus   =  status==1?5:1;
	            	} else{
	            		var newstatus 	=  status;
	            	}
	                console.log($(this));
	                $t.attr('data-status',newstatus);
	                if (newstatus == 1) {
	                	$t.parent().prev().find('span.spanstatus').removeClass('badge-danger');
	                	$t.parent().prev().find('span.spanstatus').text('Active');
	                	$t.parent().prev().find('span.spanstatus').addClass('badge-primary');
	                }else {
	                	$t.parent().prev().find('span.spanstatus').removeClass('badge-primary');
	                	$t.parent().prev().find('span.spanstatus').text('Block');
	                	$t.parent().prev().find('span.spanstatus').addClass('badge-danger');
	                }
	                    
	                swal("Changed!", "Your currnt status is changed.", "success");
	            },
	            error:function(){}
	        }, 'json');
		});
	})

    $('#customer_table').on('click', '.assign-delete-btn', function(){
    	var id 			=	$(this).attr('data-delete');
    	var $t 			= 	$(this);
    	swal({
		  title: "Are you sure?",
		  text: "Are you sure you want to delete this entry!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Yes, Delete It!",
		  cancelButtonText :   "No, Cancel !",
		  closeOnConfirm: false
		},
		function(){
			$.ajax({
	            url     :   '/ajax/ajaxDeleteAdminAssigned',
	            type    :   'POST',
	            data    :   {id    :  id},
	            success:function(data){
	            	console.log(data);
    				if (data.type = 'success') {
    					$t.parent().parent().remove();
    					swal("Deleted!", "Your entry has beeen deleted.", "success");
    				}
	                
	            },
	            error:function(){}
	        }, 'json');
		});
    })

  });

</script>
