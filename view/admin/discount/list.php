<div class="dashboard_body content-wrapper">
<?php
$this->alert();
//print_r($clario_list['results']);
?>
  <h2>Pricing Discount</h2>

    <div class="col-md-12 p-b-15">
    <a href="<?=SITE_URL?>/admin/discount_pricing_add"><button class="btn btn-primary btn-flat ">Add</button></a>
  </div>

<div class="admin_table">  
  <table class="admin">
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Min</th>
      <th>Max</th>
      <th>Percentage</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php
 if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
 foreach ($discount_pricing_list['results'] as $key => $value) { ?>

      <tr><!-- 
      <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td> -->
      <td data-label="min"><?=$value['minimum_value'] ?></td>
      <td data-label="max"><?=$value['maximum_value'] ?></td>
      <td data-label="percentage"><?=$value['percentage'] ?>%</td>
      <td data-label="Action" class="text-center">
        <a href="<?=SITE_URL.'/admin/discount_pricing?edit='.$value['id'] ?>" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
        <a href="<?=SITE_URL.'/admin/discount_pricing?delete='.$value['id'] ?>" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>

      </td>
    
    </tr>
<?php  } ?>



        </tbody>
</table>
</div>


</div>

