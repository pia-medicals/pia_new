<div class="dashboard_body content-wrapper">
  <section class="content">

    <div class="row">
     <div class=" col-md-6">
      <?php $this->alert(); ?>
      <div class="box box-primary pull-left">
        <div class="box-header with-border">
          <h2 class="box-title">Study Details</h2>
        </div>
        <div class=" col-md-12">
          <table class="table table-condensed">
            <tbody>

              <tr>
                <td>Accession</td>
                <td><?=$edit['accession'] ?></td>
              </tr>
              <tr>
                <td>MRN</td>
                <td><?=$edit['mrn'] ?></td>
              </tr>
              <tr>
                <td>Patient Name</td>
                <td><?=$edit['patient_name'] ?></td>
              </tr>
              <tr>
                <td>Site Procedure</td>
                <td><?=$edit['site_procedure'] ?></td>
              </tr>
              <tr>
                <td>Exam Time</td>
                <td><?=$edit['exam_time'] ?></td>
              </tr>
              <tr>
                <td>Status</td>
                <td><?=$edit['status'] ?></td>
              </tr>
              <tr>
                <td>Priority</td>
                <td><?=$edit['priority'] ?></td>
              </tr>
              <tr>
                <td>Site</td>
                <td><?=$edit['site'] ?></td>
              </tr>
              <tr>
                <td>Hospital</td>
                <td><?=$edit['hospital'] ?></td>
              </tr>
      <tr>
        <td>Customer</td>
        <td><?php
if(isset($edit['webhook_customer']) && !empty($edit['webhook_customer']))
  echo $edit['webhook_customer'];

         ?></td>
      </tr>

      <tr>
        <td>Description</td>
        <td><?php
if(isset($edit['webhook_description']) && !empty($edit['webhook_description']))
  echo $edit['webhook_description'];

         ?></td>
      </tr>

            </tbody>
          </table>

    <br>



    </div>
  </div>
</div>
</div>
</section>


</div>