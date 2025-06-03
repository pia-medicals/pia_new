<div class="dashboard_body content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Miscellaneous billing</h2>

              <a href="<?=SITE_URL?>/dashboard/add_miscellaneous_billing"><button class="btn btn-primary btn-flat pull-right">Add</button></a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="admin_table" class="table table-bordered table-striped">



  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Name</th>
      <th>Description</th>
      <th>Price</th><!-- 
      <th>Count</th> -->
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php

//$this->debug($miscellaneous_billing);die;
if(isset($miscellaneous_billing['results']) && !empty($miscellaneous_billing['results'])){
  if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
 foreach ($miscellaneous_billing['results'] as $key => $value) { ?>
      <tr><!-- 
      <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td> -->
      <td data-label="Name"><?=$value['name'] ?></td>
      <td data-label="Description"><?=$value['description'] ?></td>
      <td data-label="Price"><?=$value['price'] ?></td><!-- 
      <td data-label="count"><?=$value['count'] ?></td> -->
      <td data-label="Action" class="text-center">
        <a href="<?=SITE_URL.'/dashboard/miscellaneous_billing?edit='.$value['id'] ?>" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a><!-- 
        <a href="<?=SITE_URL.'/dashboard/miscellaneous_billing?delete='.$value['id'] ?>" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a> -->

      </td>
    
    </tr>
<?php  } } ?>



        </tbody>
</table>
            </div>
          </div>
</div>
</div>
</section>

</div>


<script type="text/javascript">


</script>