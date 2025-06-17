<div class="dashboard_body content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Analyses </h2>

              <a href="<?=SITE_URL?>/admin/analyses_add"><button class="pull-right btn btn-primary btn-flat ">Add</button></a>

                   <a href="<?=SITE_URL?>/excel/get_excel_analysis"><button class="pull-right btn btn-primary btn-flat " style="margin-right: 10px;">Download</button></a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
<!--               <table id="admin_table" class="table table-bordered table-striped">



  <thead>
    <tr><th>S.No.</th>
      <th>Name</th>
      <th>Category</th>
      <th>Part Number</th>
      <th>Price</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php
//print_r($salesforce_code_list['results']);die;
//if(isset($salesforce_code_list['results'])){
 // if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
 //foreach ($salesforce_code_list['results'] as $key => $value) { ?>
      <tr><td data-label="S.No."><?php// echo ($key+1)+(($page-1) * 10); ?></td>
      <td data-label="Name"><?//=$value['name'] ?></td>
      <td data-label="Category"><?//=$this->Admindb->get_by_id('analyses_Category',$value['category'])['category'] ?></td>
      <td data-label="Part Number"><?//=str_pad($value['part_number'], 4, '0', STR_PAD_LEFT); ?></td>
      <td data-label="Price">$ <?//=$value['price'] ?></td>
      <td data-label="Action" class="text-center">
        <a href="<?//=SITE_URL.'/admin/analyses?edit='.$value['id'] ?>" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
        <a href="<?//=SITE_URL.'/admin/analyses?delete='.$value['id'] ?>" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>

      </td>
    
    </tr>
<?php//  } } ?>



        </tbody>
</table>
  <h1>Server Side analyses table</h1>
 -->

<table id="analyses_table" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
       <th>Minimum Time</th>
      <th>Action</th>

    </tr>
  </thead>
  <tfoot>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Minimum Time</th>
      <th>Action</th>

    </tr>
  </tfoot>
</table>
            </div>
          </div>
</div>
</div>
</section>
</div>


<script type="text/javascript">
    $(document).ready(function(){
    var dataTable=$('#analyses_table').DataTable({
      "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
      "order": [[ 0, "desc" ]],
      "processing": true,
      "serverSide":true,
      "ajax":{
        url:"/ajax/get_analyses_info",
        type:"post"
      }
    }); 
  });

</script>

</script>