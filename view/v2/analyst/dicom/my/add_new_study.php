<div class="content-wrapper dashboard_body">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Add Study</h3>
      </div>

      <form role="form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
        <div class="card-body">
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
            <label for="client_site_name">Institution</label>
            <input type="text" id="client_site_name" name="client_site_name" class="form-control" placeholder="Institution" required>
          </div>

          <div class="form-group">
            <label for="client_account_ids">Client</label>
            <select name="client_account_ids" id="client_account_ids" class="form-control" required>
              <option value="">-- Select a client --</option>
              <?php foreach ($clients as $client): ?>
                <option value="<?= htmlspecialchars($client['client_account_id']) ?>">
                  <?= htmlspecialchars($client['client_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="comment">Comment</label>
            <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary" id="submitbtn" name="submit">
            Add <i class="fa fa-save" aria-hidden="true"></i>
          </button>
          <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_all" class="btn btn-secondary btn-flat float-right">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>