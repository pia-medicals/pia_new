<div class="dashboard_body content-wrapper">
<style type="text/css">
  .select2-container--default .select2-selection--single{
    height: 35px;
    border: 1px solid #d2d6de !important;
  }
</style>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php $this->alert(); ?>

        <div class="box">
          <div class="box-header">
            <h2 class="box-title">All Studies</h2>
          </div>
         
          <div class="box-body">         

<div class="dataTables_wrapper" > 
                <div class="dataTables_length" > 
   <span> 
     <form method="post" action="/ajax/downloaddata_completefilternewcsv">
<label>Show last  <select name="day_select" aria-controls="admin_table" id="day_select" class="form-control select2" style="width: 87px; height: 30px;">
                          <option value="">All </option> 
                          <option value="1"> 1 day </option>
                          <option value="3"> 3 days</option>
                          <option value="30">30 days</option>
                      </select>
Assignee
  <select name="asignee_select" aria-controls="admin_table" id="asignee_select" class="form-control select2" style="width: 145px; height: 30px;">
     <option value="">Select Assignee</option>
      <?php if(isset($asignee)):
        foreach ($asignee as $key => $value): ?>
          <option value="<?= $value['user_id'] ?>"   > <?= $value['user_name'] ?> </option> 
        <?php endforeach; endif; ?>
</select>

Status <select name="status_select" aria-controls="admin_table" id="status_select" class="form-control select2" style="width: 139px; height: 30px;">
<option value=""> Select Status</option>
  <option value="Completed" > Completed</option>
  <option value="Under review" > Under review</option>
  <option value="In progress" > In progress</option>
  <option value="Cancelled" > Cancelled</option>
  <option value="On hold" > On hold</option>  
  <option value="Not Assigned" > Not Assigned</option>  
</select>
Second Check <select name="secondcheck_select" aria-controls="admin_table" id="secondcheck_select" class="form-control select2" style="width: 139px; height: 30px;">
                <option value=""> Select</option>
                <option value="1"> Yes</option>
                <option value="2" > No</option>
             </select>

             <span class="hdn">Reviewer</span>
                <span class="hdn"><select name="asignee_second" aria-controls="admin_table" id="asignee_second" class="form-control select2 hdn" style="width: 145px; height: 30px;">
                 <option value="">Select Reviewer</option>
                 <?php if(isset($asignee)):
                  foreach ($asignee as $key => $value): ?>
                    <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
                  <?php endforeach; endif; ?>
                </select></span>
<input type="button" id="reset_filter" value="Reset Filter" class="btn btn-danger" name="reset_filter"  style="margin-top: -8px;" /> 
 <input type="submit" name="export" class="btn btn-success" value="Export To CSV" style="margin-top: -8px;" />
</form>
</label> </span> 

</div>
</div>
<br>
              

<div class="box box-primary">
  <div class="box-header with-border">
    <h6 class="" style="text-align: right;margin-right: 37px;"></h6>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">

<!--    <table id="all_worksheet" class="table table-bordered table-striped">-->
      <table id="all_worksheet" class="table table-bordered">
  <thead>
    <tr>
      <th>Received Date</th>      
      <th>Accession</th>
      <th>Patient Name</th>
      <th>MRN</th>
      <th>Default TAT</th>
  <!--    <th>Webhook Customer</th>
      <th>Assignee</th>
      <th style="width:180px">Customer</th>
      <th>Second Check</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>
      <th style="display: none;"></th>-->
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>Received Date</th>     
      <th>Accession</th>
      <th>Patient Name</th>
      <th>MRN</th>
      <th>Default TAT</th>
      <!--<th>Webhook Customer</th>
      <th>Assignee</th>
      <th>Customer</th>
      <th>Second Check</th>   
      <th>Description</th>
      <th>Status</th>   
      <th>Action</th>
      <th style="display: none;"></th>-->
    </tr>
  </tfoot>
</table>
  </div>
</div>
</div>
</div>
</div>
</section>

</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 load_data();

 function load_data(is_assignee)
 {
  var dataTable = $('#all_worksheet').DataTable({        
  // "lengthMenu": [[50], [50]],
  "lengthMenu": [[50, 100, 500], [50, 100, 500]],
    "order": [[ 0, "desc" ]],
   "processing":true,
   "serverSide":true,
   "ajax":{
    url:"/ajax/get_all_worksheet_info",
    type:"POST",
    data:{is_day:$('#day_select').val(), is_assignee:is_assignee, is_second:$('#secondcheck_select').val(), asignee_second:$('#asignee_second').val(), status_select:$('#status_select').val()}  
   },
   "columnDefs":[
    {
     "targets":[2],
     "orderable":false,
    },
   ],
  });
/* 	setInterval(function () {
		  dataTable.ajax.reload();
	}, 120000);   */
        
        
           
         
 }
  

function load_day_data(is_day)
 {
  var dataTable = $('#all_worksheet').DataTable({
    "lengthMenu": [[50], [50]],
    "order": [[ 0, "desc" ]],
   "processing":true,
   "serverSide":true,
   "ajax":{
    url:"/ajax/get_all_worksheet_info",
    type:"POST",
    data:{is_day:is_day, is_assignee:$('#asignee_select').val(), is_second:$('#secondcheck_select').val(), asignee_second:$('#asignee_second').val(), status_select:$('#status_select').val()}
   },
   "columnDefs":[
    {
     "targets":[2],
     "orderable":false,
    },
   ],
  });
 }
 function load_second_data(is_second)
  {
    var dataTable = $('#all_worksheet').DataTable({
      "lengthMenu": [[50], [50]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_day:$('#day_select').val(), is_assignee:$('#asignee_select').val(), is_second:is_second, asignee_second:$('#asignee_second').val(), status_select:$('#status_select').val()}
      },
      "columnDefs":[
      {
       "targets":[2],
       "orderable":false,
     },
     ],
   });
  }

 $(document).on('change', '#asignee_select', function(){
  var asignee = $(this).val();
  $('#all_worksheet').DataTable().destroy();
  if(asignee != '')
  {
   load_data(asignee);
  }
  else
  {
   load_data();
  }
 });

 $(document).on('change', '#day_select', function(){
  var day = $(this).val();
  $('#all_worksheet').DataTable().destroy();
  if(day != '')
  {
   load_day_data(day);
  }
  else
  {
   load_data();
  }
 });
 $('.hdn').hide();
 $(document).on('change','#secondcheck_select,#asignee_second',function(){
	var second = $('#secondcheck_select').val();
  //alert(second);
  if(second == 1){
     $('.hdn').show();
  } else {
    $('.hdn').hide();
  }
	$('#all_worksheet').DataTable().destroy();
	if(second!=''){
		load_second_data(second);
	}
	else{
     load_data();
   }
 });
 
});
</script>
