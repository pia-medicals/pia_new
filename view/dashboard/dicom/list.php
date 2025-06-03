<div class="dashboard_body content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Open Worksheets</h2>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="admin_table" class="table table-bordered table-striped">




  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Accession</th>
      <th>MRN</th>
      <th style="width:180px">Customer</th>
      <th>Description</th>
      <th>Patient Name</th>
      <th>Site Procedure</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php
if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
 foreach ($dicom_details_list['results'] as $key => $value) { ?>
      <tr><!-- 
      <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td> -->
      <td data-label="Accession"><?=$value['accession'] ?></td>
      <td data-label="MRN"><?=$value['mrn'] ?></td>
      <td data-label="MRN"><?=$value['webhook_customer'] ?></td>
      <td data-label="MRN"><?=$value['webhook_description'] ?></td>
      <td data-label="Patient Name"><?=$value['patient_name'] ?></td>
      <td data-label="Site Procedure"><?=$value['site_procedure'] ?></td>
      <td data-label="Action" class="text-center">
        <a href="<?=SITE_URL.'/dashboard/dicom_details?edit='.$value['id'] ?>" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
        <a href="<?=SITE_URL.'/dashboard/dicom_details?delete='.$value['id'] ?>" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>

      </td>
    
    </tr>
<?php  } ?>



        </tbody>


</table>
            </div>
          </div>
</div>
</div>
</section>



</div>