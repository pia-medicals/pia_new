<div class="dashboard_body content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert();?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">My Studies</h2>
				<a href="<?=SITE_URL?>/dashboard/add_work_sheet"><button class="btn btn-primary btn-flat pull-right">Add</button></a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="box box-primary">
  <div class="box-header with-border">
    <h6 class="" style="text-align: right;margin-right: 37px;"></h6>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">
<table id="analyst_assigned_worksheet" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>MRN</th>
      <th>Default TAT</th>
      <th>Patient Name</th>
      <th>Assigne</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>

    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>Date</th>
      <th>MRN</th>
      <th>Default TAT</th>
      <th>Patient Name</th>
      <th>Assigne</th>
      <th>Description</th>
      <th>Status</th>
      <th>Action</th>

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

 <script type="text/javascript">
    $(document).ready(function(){
    var dataTable=$('#analyst_assigned_worksheet').DataTable({
     "lengthMenu": [[100], [100]],
      "order": [[ 0, "desc" ]],
      "processing": true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyst_assigned_worksheet_info",
        type:"post"
      }
    }); 
	setInterval(function () {
		  dataTable.ajax.reload();
	}, 120000);	
  });

</script> 