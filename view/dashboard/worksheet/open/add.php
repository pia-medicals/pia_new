
<style>
    .custom-form-container {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 32px 24px 24px 24px;
        margin-top: 32px;
        margin-bottom: 32px;
        /* Extra margin for large screens */
        margin-left: 100px;
    }
    @media (min-width: 992px) {
        .custom-form-container {
            margin-left: 40px;
        }
    }
    .custom-form-container .form-group {
        margin-bottom: 20px;
    }
    .custom-form-container label {
        font-weight: 500;
        margin-bottom: 6px;
    }
</style>

<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="row">
      <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-2">
        <div class="custom-form-container">
          <h2 class="mb-4" style="font-size:1.5rem; font-weight:600;">Add Study</h2>
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
              <label for="client_account_ids">Site</label>
              <select id="client_account_ids" name="client_account_ids" class="form-control" required>
                <option value="">Select Site</option>
                <?php if (!empty($sites)): ?>
                  <?php foreach ($sites as $site): ?>
                    <option value="<?= htmlspecialchars($site['client_account_id']) ?>">
                      <?= htmlspecialchars($site['client_site_name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="comment">Comment</label>
              <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment">
            </div>
            <div class="form-group mb-0">
              <button type="submit" class="btn btn-primary btn-flat" id="submitbtn" name="submit">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>