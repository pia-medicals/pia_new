<div class="dashboard_body content-wrapper">


    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Open Studies</h2>
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
<table id="open_worksheets" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Received Date</th>
      
      <th>Accession</th>
      <th>Patient Name</th>
      <th>MRN</th>
      <th>Default TAT</th>
      <th style="width:180px">Customer</th>
      <th>Description</th>
      <th>Action</th>

    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>Received Date</th>
      
      <th>Accession</th>
      <th>Patient Name</th>
      <th>MRN</th>
      <th>Default TAT</th>
      <th>Customer</th>
      <th>Description</th>
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

<script>

  $(document).ready(function(){
    /*$('#example1').DataTable(); */
    var dataTable=$('#open_worksheets').DataTable({
    //  "lengthMenu": [[100], [100]],
    "lengthMenu": [[50, 100, 500], [50, 100, 500]],
      "order": [[ 0, "desc" ]],
      "processing": true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_open_worksheets_info",
        type:"post"
      }
    }); 
	setInterval(function () {
		  dataTable.ajax.reload();
	}, 120000);	
  });

</script>


</div>