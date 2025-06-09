<div class="dashboard_body">
  <?php
$this->alert();
//print_r($user_list['results']);
?>
  <h2>User</h2>
  <div class="col-md-12 p-b-15">
    <a href="<?=SITE_URL?>/admin/add_user"><button class="btn btn-primary btn-flat ">Add User</button></a>
  </div>

<div class="admin_table">  
  <table class="admin">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php foreach ($user_list['results'] as $key => $value) { ?>
      <tr>
      <td data-label="Name"><?=$value['name'] ?></td>
      <td data-label="Email"><?=$value['email'] ?></td>
      <td data-label="Date"><?=$value['created'] ?></td>
      <td data-label="Action" class="text-center">
        <a href="#" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
        <a href="#" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>

      </td>
    
    </tr>
<?php  } ?>



        </tbody>
</table>
</div>
<div class="col-md-12">
  <?=$user_list['pagination'] ?>
</div>



</div>