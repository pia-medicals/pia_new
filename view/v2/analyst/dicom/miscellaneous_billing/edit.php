<div class="content-wrapper dashboard_body">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Miscellaneous Billing</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing">Miscellaneous Billing</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <?php $this->alert(); ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Update Billing Entry</h3>
      </div>

      <form method="post" action="" class="admin_form">
        <div class="card-body">

          <input type="hidden" name="miscellaneous_billing_id" value="<?= htmlspecialchars($edit_billing['miscellaneous_billing_id'] ?? '') ?>">

          <div class="form-group">
            <label for="name">Name <span style="color:red">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($edit_billing['name'] ?? '') ?>" maxlength="100">
          </div>

          <div class="form-group">
            <label for="analysis_invoicing_description">Description</label>
            <input type="text" name="analysis_invoicing_description" id="analysis_invoicing_description" class="form-control" maxlength="255" value="<?= htmlspecialchars($edit_billing['analysis_invoicing_description'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="analysis_client_price">Price</label>
            <input type="number" name="analysis_client_price" id="analysis_client_price" class="form-control" step="0.01" value="<?= htmlspecialchars($edit_billing['analysis_client_price'] ?? '') ?>">
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
            <input type="number" name="count" id="count" class="form-control" value="<?= htmlspecialchars($edit_billing['count'] ?? '') ?>">
          </div>

        </div>

         <div class="card-footer">
          <button type="submit" class="btn btn-primary" id="submit" name="submit">Update <i aria-hidden="true" class="fa fa-save"></i></button>
          <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing" class="btn btn-secondary float-right">Back</a>
        </div>
      </form>
    </div>
  </section>

  <style>
    .form-control {
      font-size: 14px;
      padding: 6px 10px;
      box-shadow: none;
      border: 1px solid #ced4da;
    }

    .form-control:focus {
      border-color: #80bdff;
      outline: 0;
      box-shadow: none;
    }

    .admin_form .form-group label {
      font-weight: 500;
      font-size: 14px;
    }

    .btn-flat {
      border-radius: 3px;
      box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.2);
    }

    .btn-primary.btn-flat {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .btn-primary.btn-flat:hover {
      background-color: #0056b3;
      border-color: #004085;
    }

    .btn-secondary.btn-flat {
      background-color: #6c757d;
      border-color: #6c757d;
      color: white;
    }

    .btn-secondary.btn-flat:hover {
      background-color: #5a6268;
      border-color: #4e555b;
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
    }

    .card-primary {
      border-top: 3px solid #007bff;
    }

    .dashboard_body {
      background: #f4f6f9;
    }
  </style>
</div>
