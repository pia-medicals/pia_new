
<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="box box-primary">
      <div class="box-header with-border">
        <h2 class="box-title">Add Study</h2>
      </div>

      <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">

        <div class="form-group">
          <label for="accession">Accession</label>
          <input type="text" id="accession" name="accession" required class="form-control" placeholder="Accession">
        </div>

        <div class="form-group">
          <label for="mrn">MRN</label>
          <input type="text" id="mrn" name="mrn" required class="form-control" placeholder="MRN">
        </div>

        <div class="form-group">
          <label for="patient_name">Patient Name</label>
          <input type="text" id="patient_name" name="patient_name" required class="form-control" placeholder="Patient Name">
        </div>


        <div class="form-group">
          <label for="client_site_name">Site</label>
          <input type="text" id="client_site_name" name="client_site_name" class="form-control" placeholder="Site" required>
        </div>

        <div class="form-group">
          <label for="comment">Comment</label>
          <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment">
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-flat" id="submitbtn" name="submit">Add</button>
        </div>
      </form>
    </div>
  </section>
</div>