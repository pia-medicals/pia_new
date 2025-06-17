<div class="content-wrapper dashboard_body">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title mb-0">Add Miscellaneous Billing</h3>
      </div>

      <form method="post" action="" class="admin_form" autocomplete="off">
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name <span style="color:red">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="Billing Name">
          </div>

          <div class="form-group">
            <label for="analysis_invoicing_description">Description</label>
            <input type="text" name="analysis_invoicing_description" id="analysis_invoicing_description" class="form-control" placeholder="Billing Description">
          </div>

          <div class="form-group">
            <label for="analysis_client_price">Price</label>
            <input type="number" name="analysis_client_price" id="analysis_client_price" class="form-control" step="0.01" placeholder="Price">
          </div>

          <div class="form-group">
            <label for="client_account_ids">Client</label>
            <select name="client_account_ids" id="client_account_ids" class="form-control" required>
              <option value="">-- Select a client --</option>
              <?php foreach ($clients as $client): ?>
                <option value="<?= htmlspecialchars($client['client_account_id']) ?>">
                  <?= htmlspecialchars($client['user_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="count">Count</label>
            <input type="number" name="count" id="count" class="form-control" placeholder="Enter Count">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary" id="submitbtn" name="submit">
            Add <i class="fa fa-save" aria-hidden="true"></i>
          </button>
          <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing" class="btn btn-secondary btn-flat float-right">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>
