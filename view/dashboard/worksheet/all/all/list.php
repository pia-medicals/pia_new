<div class="dashboard_body content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php $this->alert(); ?>

        <div class="box">
          <div class="box-header">
            <h2 class="box-title">All Studies</h2>
				<a href="<?=SITE_URL?>/dashboard/add_work_sheet"><button class="btn btn-primary btn-flat pull-right">Add</button></a>

          </div>
          <!-- /.box-header -->
          <div class="box-body">     

            <div class="dataTables_wrapper" > 
              <div class="dataTables_length" > 
               <span> 
                <label>Show last  <select name="day_select" aria-controls="admin_table" id="day_select" class="form-control" style="width: 87px; height: 30px;">
                  <option value="">All </option> 
                  <option value="1"> 1 day </option>
                  <option value="3"> 3 days</option>
                  <option value="30">30 days</option>
                </select>
                Asignee
                <select name="asignee_select" aria-controls="admin_table" id="asignee_select" class="form-control" style="width: 135px; height: 30px;">
                 <option value="">Select Asignee</option>
                 <option value="0">Not Assigned</option>
                 <?php if(isset($asignee)):
                  foreach ($asignee as $key => $value): ?>
                    <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
                  <?php endforeach; endif; ?>
                </select>
              
              
              Status <select name="status_select" aria-controls="admin_table" id="status_select" class="form-control" style="width: 139px; height: 30px;">
                <option value=""> None</option>
                <option value="Completed" > Completed</option>
                <option value="Under review" > Under review</option>
                <option value="In progress" > In progress</option>
                <option value="Cancelled" > Cancelled</option>
                <option value="On hold" > On hold</option>
             </label> </span> 

          <input type="button" id="reset_filter" value="Reset Filter" class="btn btn-danger" name="reset_filter"  style="margin-top: -8px;" /> 
            </label>

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
        <table id="all_worksheet" class="table table-bordered">
          <thead>
            <tr>
              <th>Received Date</th>
              <th>Accession</th>
              <th>Patient Name</th>
              <th>MRN</th>
              <th>Default TAT</th>
              <th>Webhook Customer</th>
              <th>Assigne</th>
              <th>Second Check</th>
              <th>Description</th>
              <th>Status</th>
              <th>Action</th>
              <th style="display: none;"></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Received Date</th>
              <th>Accession</th>
              <th>Patient Name</th>
              <th>MRN</th>           
              <th>Default TAT</th>   
              <th>Webhook Customer</th>
              <th>Assigne</th>
              <th>Second Check</th>   
              <th>Description</th>
              <th>Status</th>   
              <th>Action</th>
              <th style="display: none;"></th>
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
      "lengthMenu": [[100], [100]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_assignee:is_assignee}
      },
      "columnDefs":[
      {
       "targets":[2],
       "orderable":false,
     },
     ],
   });
   
 	setInterval(function () {
		  dataTable.ajax.reload();
	}, 120000);  
  }

  function load_day_data(is_day)
  {
    var dataTable = $('#all_worksheet').DataTable({
      "lengthMenu": [[100], [100]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_day:is_day}
      },
      "columnDefs":[
      {
       "targets":[2],
       "orderable":false,
     },
     ],
   });
  }
  
// $('#all_worksheet').on( 'draw.dt', function () {
//     $("tr td:nth-child(9):contains('Not Assigned')").addClass("btn btn-xs btn-danger");
//     $("tr td:nth-child(9):contains('In progress')").addClass("btn btn-xs btn-info");
//     $("tr td:nth-child(9):contains('Completed')").addClass("btn btn-xs btn-success");
//     $("tr td:nth-child(9):contains('Under review')").addClass("btn btn-xs btn-warning");
//     });

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

});
</script>
