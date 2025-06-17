<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
<style>
/*@font-face {
font-family	: 	'Glyphicons Halflings';
src			:	url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot');
src			:	url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.woff') format('woff'),
url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
}*/
</style>
<link rel="stylesheet" href="//<?=ASSET ?>css/fontawesome-stars.css">
<link href="http://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Source+Code+Pro" rel="stylesheet" type="text/css">

<div class="dashboard_body content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
      <div id="second-msg">
        <?php $this->alert(); ?>
		</div>
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
                <label>Show last  <select name="day_select" aria-controls="admin_table" id="day_select" class="form-control select2" style="width: 87px; height: 30px;">
                  <option value="">All </option> 
                  <option value="1"> 1 day </option>
                  <option value="3"> 3 days</option>
                  <option value="30">30 days</option>
                </select>
                Assignee
                <select name="asignee_select" aria-controls="admin_table" id="asignee_select" class="form-control select2" style="width: 145px; height: 30px;">
                 <option value="">Select Assignee</option>
                 <option value="0">Not Assigned</option>
                 <?php if(isset($asignee)):
                  foreach ($asignee as $key => $value): ?>
                    <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
                  <?php endforeach; endif; ?>
                </select>
              
              
              Status <select name="status_select" aria-controls="admin_table" id="status_select" class="form-control select2" style="width: 139px; height: 30px;">
                <option value=""> None</option>
                <option value="Completed" > Completed</option>
                <option value="Under review" > Under review</option>
                <option value="In progress" > In progress</option>
                <option value="Cancelled" > Cancelled</option>
                <option value="On hold" > On hold</option>
                 <option value="Not Assigned" > Not Assigned</option> 
             </select> 
             Second Check <select name="secondcheck_select" aria-controls="admin_table" id="secondcheck_select" class="form-control select2" style="width: 139px; height: 30px;">
                <option value=""> None</option>
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
              
           </label>
             </span> 

          <input type="button" id="reset_filter" value="Reset Filter" class="btn btn-danger" name="reset_filter"  style="margin-top: -8px;" /> 
            

          </div>
          <div class="alert alert-info alert-dismissible" id="succes-analyst" style="display:none;"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Alert!</h4>
                Change analyst successfully
              </div>
        </div>
        <div class="alert alert-error alert-dismissible" id="error-analyst" style="display:none;"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Alert!</h4>
                Change analyst error
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
              <th>Assignee</th>
              <th style="width:180px">Customer</th>
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
              <th>Assignee</th>
              <th>Customer</th>
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
<!-- RC LOAD MODEL -->
<div class="modal modal-info fade" id="modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title">Review</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Analyst</label>
                    <select class="form-control" id="review-analyst">
                        <option value="0">Select Analyst</option>
                        <?php foreach($analysts as $key => $value){?>
                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                        <?php } ?>
                    </select>
                    <label>Analyst Time(minutes)</label>
                    <input type="number" name="txtAnalystHours" id="txtAnalystHours" class="form-control"/>
                    <label>Comments</label>
                    <textarea cols="5" rows="5" class="form-control" name="txtComments" id="txtComments"></textarea>
                    <label>Analyst Rating</label>
                    <select id="example-fontawesome" name="analystRating" autocomplete="off">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
            	<button type="button" class="btn btn-outline" id="review-save">Save changes</button>
            </div>
        </div>
    	<!-- /.modal-content -->
    </div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script src="//<?=ASSET ?>js/jquery.barrating.js"></script>
<script src="//<?=ASSET ?>js/examples.js"></script>

<script type="text/javascript" language="javascript" >
  $(document).ready(function(){
   
   load_data();
   


   function load_data(is_assignee)
   {
    var dataTable = $('#all_worksheet').DataTable({
      "lengthMenu": [[50], [50]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_assignee:is_assignee, status_select:$('#status_select').val()}
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
      "lengthMenu": [[50], [50]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_day:is_day, status_select:$('#status_select').val()}
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
        data:{is_second:is_second, asignee_second:$('#asignee_second').val(), status_select:$('#status_select').val()}
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
  $('.hdn').hide();
 $(document).on('change','#secondcheck_select,#asignee_second',function(){
	var second = $('#secondcheck_select').val();
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

function getreview(reviewid){
    $('#succes-analyst').hide();
    $('#modal-info').modal('show');
    $.ajax({
        url   : '/ajax/getreview',
        type  : 'POST',
        data  : {reviewid:reviewid},
        success:function(data){
            //alert(data);
            var jsonData    =   JSON.parse(data);
            //alert(jsonData[0].review_user_id);
            if(jsonData[0].review_user_id!=''){
                $('#txtAnalystHours').val(jsonData[0].second_analyst_hours);
                $('#review-analyst').val(jsonData[0].review_user_id);
                $('#txtComments').val(jsonData[0].second_comment);
                $('#example-fontawesome').val(jsonData[0].second_check_rate);
            }
        },
    error:function(){}
    });
    $('#review-save').on('click',function(){
        var analyst         = $('#review-analyst option:selected').val();
        var analystHours    = $('#txtAnalystHours').val();
        var comments        = $('#txtComments').val();
        var rate            = $('#example-fontawesome').val();
		//alert(analyst);
		if(analyst!=0){
        $.ajax({
            url   : '/ajax/userreview',
            type  : 'POST',
            data  :{
                analyst         : analyst,
                reviewid        : reviewid,
                analystHours    : analystHours,
                comments        : comments,
                rate            : rate
            },
            success:function(data){
				//alert(data);
                if(data==1){
                    $('#review-analyst').val(0);
                    $('#txtAnalystHours').val('');
                    $('#txtComments').val('');
                    $('#modal-info').modal('hide');
                    $('#succes-analyst').show(); 
                    //load_second_data($('#secondcheck_select').val());
                    $('#all_worksheet').DataTable().destroy();
                    if ($('#secondcheck_select').val() !=''){
                        load_second_data_after_review($('#secondcheck_select').val());
                    } 
                    else {
                        load_data_after_review();
                    }
                        $('.alert').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-info"></i> Alert!</h4>Successfully completed second check process.');
                }
                else{
                    $('#review-analyst').val(0);
                    $('#txtAnalystHours').val('');
                    $('#txtComments').val('');
                    $('#modal-info').modal('hide');
                    $('#error-analyst').show();
                }       
            },
            error:function(){}
        });
		}
		else{
			alert('please select the analyst');
		}
    })
}

 function load_second_data_after_review(is_second)
  {
    var dataTable = $('#all_worksheet').DataTable({
      "lengthMenu": [[50], [50]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_second:is_second, status_select:$('#status_select').val()}
      },
      "columnDefs":[
      {
       "targets":[2],
       "orderable":false,
     },
     ],
   });
	   
  }
  function load_data_after_review(is_assignee)
   {
    var dataTable = $('#all_worksheet').DataTable({
      "lengthMenu": [[50], [50]],
      "order": [[ 0, "desc" ]],
      "processing":true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_all_worksheet_info",
        type:"POST",
        data:{is_assignee:is_assignee, status_select:$('#status_select').val()}
      },
      "columnDefs":[
      {
       "targets":[2],
       "orderable":false,
     },
     ],
   });
   
  /*setInterval(function () {
      dataTable.ajax.reload();
  }, 120000); */ 
  }

</script>
