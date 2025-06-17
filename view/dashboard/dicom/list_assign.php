<div class="dashboard_body content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
  <?php $this->alert(); ?>

          <div class="box">
            <div class="box-header">
              <h2 class="box-title">Assigned Worksheets</h2>


            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="admin_table" class="table table-bordered table-striped">





  <thead>
    <tr><!-- 
      <th>S.No.</th> --><!-- 
      <th>Accession</th> -->
      <th>Date</th>
      <th>MRN</th>
      <th>Patient Name</th>
      <th>Assigne</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>

<?php
if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
 foreach ($dicom_details_list['results'] as $key => $value) {

if($value['status'] == 'Completed') $label = 'success'; elseif($value['status'] == 'Under review') $label = 'warning'; else $label = 'info';
  ?>
      <tr><!-- 
      <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td> --><!-- 
      <td data-label="Accession"><?=$value['accession'] ?></td> -->
      <td data-label="MRN"><?=date("d/m/Y", strtotime($value['date'])) ?></td>
      <td data-label="MRN"><?=$value['mrn'] ?></td>
      <td data-label="Patient Name"><?=$value['patient_name'] ?></td>
      <td data-label="Assigne"><?=$this->Admindb->get_by_id('users',$value['assignee'])['name'] ?></td>
      <td data-label="Status"><div class="btn btn-xs btn-<?=$label ?>"><?=$value['status'] ?></div></td>
      <td data-label="Action" class="text-center">
        <a href="<?=SITE_URL.'/dashboard/dicom_details_assigned?edit='.$value['id'] ?>" class="btn btn-primary btn-flat">View</a>

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