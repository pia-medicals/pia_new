<div class="dashboard_body">
  <?php
$this->alert();
//print_r($clario_list['results']);
?>
  <h2>Clario</h2>

<div class="admin_table">  
  <table class="admin">
  <thead>
    <tr>
      <th>Accession</th>
      <th>MRN</th>
      <th>Patient Name</th>
      <th>Site Procedure</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php foreach ($clario_list['results'] as $key => $value) { ?>
      <tr>
      <td data-label="Accession"><?=$value['accession'] ?></td>
      <td data-label="MRN"><?=$value['mrn'] ?></td>
      <td data-label="Patient Name"><?=$value['patient_name'] ?></td>
      <td data-label="Site Procedure"><?=$value['site_procedure'] ?></td>
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
  <?=$clario_list['pagination'] ?>
</div>



</div>