<div class="dashboard_body content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php $this->alert(); ?>

        <div class="box">
          <div class="box-header">
            <h2 class="box-title">Analyses Categories </h2>
            <a href="<?=SITE_URL?>/admin/analyses_category_add"><button class="pull-right btn btn-primary btn-flat ">Add</button></a>
          </div>
          <!-- /.box-header -->
          <div class="box-body">


<table id="example2" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Category Name</th>
      <th>Action</th>

    </tr>
  </thead>
  <tfoot>
    <tr>
    <th>Category Name</th>
      <th>Action</th>
    </tr>
  </tfoot>
</table>

</div>
<!-- /.box-body -->
</div>
</div>
</div>
</section>

<script>

  $(document).ready(function(){
    /*$('#example1').DataTable(); */
    var dataTable=jQuery('#example2').DataTable({
      "processing": true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyses_category_info",
        type:"post"
      }
    }); 
  });

</script>

</div>