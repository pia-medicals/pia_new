<?php
// File: v2/analyst/dicom/my/edit.php
?>

<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Study</h3>
      </div>

      <?php $is_completed = ($edit_studies['status_ids'] ?? '') == 1; ?>

      <!-- Debug: Log categories -->
      <?php error_log("edit.php categories: " . print_r($categories, true)); ?>

      <form id="editStudyForm" role="form" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
        <div class="card-body">

          <!-- Readonly Fields in Table Format -->
          <table class="table table-bordered">
            <tbody>
              <?php foreach ([
                'accession' => 'Accession',
                'mrn' => 'MRN',
                'patient_name' => 'Patient Name',
                'client_site_name' => 'Site',
                'webhook_customer' => 'Client'
              ] as $field => $label): ?>
                <tr>
                  <th><?= $label ?></th>
                  <td><input type="text" class="form-control" name="<?= $field ?>" value="<?= htmlspecialchars($edit_studies[$field] ?? '') ?>" readonly></td>
                </tr>
              <?php endforeach; ?>
              <tr>
                <th>Description</th>
                <td><textarea class="form-control" name="webhook_description" readonly><?= htmlspecialchars($edit_studies['comment'] ?? '') ?></textarea></td>
              </tr>
              <tr>
                <th>Technologist</th>
                <td><input type="text" name="analyst" class="form-control" value="<?= htmlspecialchars($edit_studies['analyst_name'] ?? '') ?>" readonly></td>
              </tr>
            </tbody>
          </table>

          <!-- Second Check Dropdown -->
          <div class="form-group">
            <label for="second_analyst_id">Second Check</label>
            <select name="second_analyst_id" class="form-control">
              <option value="">-- Select --</option>
              <?php foreach ($other_users as $u): ?>
                <option value="<?= htmlspecialchars($u['user_id']) ?>" <?= ($edit_studies['second_analyst_id'] ?? '') == $u['user_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($u['user_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Status Dropdown -->
          <div class="form-group">
            <label for="status_ids">Status</label>
            <select name="status_ids" class="form-control">
              <option value="">-- Select Status --</option>
              <?php foreach ($statuses as $status): ?>
                <option value="<?= htmlspecialchars($status['status_id']) ?>" <?= ($edit_studies['status_ids'] ?? '') == $status['status_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($status['status']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Analysis Performed -->
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Analysis Performed</label>
            <div class="col-sm-6">
              <select id="analysis_performed" class="form-control">
                <option value="">-- Select --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= htmlspecialchars(strtolower(trim($cat))) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-3">
              <button type="button" class="btn btn-success btn-flat w-100" id="addAnalysisBtn">Add</button>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
              <ul id="analysis_list" class="list-group">
                <?php
                $selected_analyses = [];
                if (!empty($edit_studies['studies_id'])) {
                    $analyses = $this->Admindb->get_analyses_performed_by_study($edit_studies['studies_id']);
                    foreach ($analyses as $analysis) {
                        if (!empty($analysis['analysis_performed'])) {
                            $selected_analyses[] = trim($analysis['analysis_performed']);
                        }
                    }
                }
                foreach ($selected_analyses as $item): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($item) ?></span>
                    <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                  </li>
                <?php endforeach; ?>
              </ul>
              <input type="hidden" name="analysis_performed_list" id="analysis_performed_list" value="<?= htmlspecialchars(implode(',', $selected_analyses)) ?>">
            </div>
          </div>

          <input type="hidden" name="studies_id" value="<?= htmlspecialchars($edit_studies['studies_id']) ?>">
        </div>

        <div class="card-footer">
          <?php if (!$is_completed): ?>
            <button type="submit" class="btn btn-primary btn-flat" id="submit" name="submit">Save <i class="fa fa-save"></i></button>
          <?php else: ?>
            <button type="button" class="btn btn-warning btn-flat" id="reopenBtn">Reopen <i class="fa fa-undo"></i></button>
          <?php endif; ?>
          <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_all" class="btn btn-secondary btn-flat float-right">Back</a>
        </div>
      </form>
    </div>
  </section>
</div>

<!-- Styles -->
<style>
  .admin_form .form-group label {
    font-weight: 500;
    font-size: 14px;
  }
  .admin_form .form-control {
    font-size: 14px;
    padding: 6px 10px;
  }
  .btn-flat {
    border-radius: 3px;
    box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.2);
  }
  .btn-primary.btn-flat {
    background-color: #007bff;
    color: white;
  }
  .btn-secondary.btn-flat {
    background-color: #6c757d;
    color: white;
  }
  .btn-success.btn-flat {
    background-color: #28a745;
    color: white;
  }
  .btn-danger.btn-sm {
    font-size: 12px;
    padding: 2px 8px;
  }
  table.table td, table.table th {
    vertical-align: middle;
    font-size: 14px;
    padding: 8px;
  }
  table.table input[readonly],
  table.table textarea[readonly] {
    background-color: #f8f9fa;
    border: none;
    box-shadow: none;
  }
  .list-group-item {
    font-size: 14px;
    padding: 8px;
  }
</style>

<!-- Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const reopenBtn = document.getElementById('reopenBtn');
  const addBtn = document.getElementById('addAnalysisBtn');
  const analysisSelect = document.getElementById('analysis_performed');
  const analysisList = document.getElementById('analysis_list');
  const hiddenInput = document.getElementById('analysis_performed_list');
  const form = document.getElementById('editStudyForm');

  // Add analysis to list
  addBtn.addEventListener('click', function () {
    const selectedValue = analysisSelect.value.trim().toLowerCase();
    if (!selectedValue) {
      alert('Please select an analysis to add.');
      return;
    }

    const existingItems = Array.from(analysisList.querySelectorAll('li span')).map(span =>
      span.textContent.trim().toLowerCase()
    );

    if (existingItems.includes(selectedValue)) {
      alert('This analysis is already added.');
      return;
    }

    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.innerHTML = `
      <span>${selectedValue}</span>
      <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
    `;
    analysisList.appendChild(li);
    updateHiddenInput();
    analysisSelect.value = ''; // Reset dropdown
  });

  // Remove analysis from list
  analysisList.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-btn')) {
      e.target.closest('li').remove();
      updateHiddenInput();
    }
  });

  // Update hidden input with comma-separated list
  function updateHiddenInput() {
    const items = Array.from(analysisList.querySelectorAll('li span')).map(span =>
      span.textContent.trim()
    );
    hiddenInput.value = items.join(',');
  }

  // Form validation on submit
  form.addEventListener('submit', function (e) {
    console.log('Submitting analysis_performed_list:', hiddenInput.value);
    const statusSelect = form.querySelector('select[name="status_ids"]');
    if (!statusSelect.value) {
      e.preventDefault();
      alert('Please select a status.');
      statusSelect.focus();
      return;
    }
    if (!hiddenInput.value) {
      e.preventDefault();
      alert('Please add at least one analysis.');
      analysisSelect.focus();
      return;
    }
  });

  // Reopen study
  if (reopenBtn) {
    reopenBtn.addEventListener('click', function () {
      if (confirm('Are you sure you want to reopen this study?')) {
        fetch('<?= SITE_URL ?>/analyst/reopen_study', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          credentials: 'same-origin',
          body: new URLSearchParams({
            study_id: '<?= htmlspecialchars($edit_studies['studies_id']) ?>'
          })
        })
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.text();
        })
        .then(response => {
          alert('Study reopened successfully.');
          const submitBtn = document.createElement('button');
          submitBtn.type = 'submit';
          submitBtn.id = 'submit';
          submitBtn.name = 'submit';
          submitBtn.className = 'btn btn-primary btn-flat';
          submitBtn.innerHTML = 'Save <i class="fa fa-save"></i>';
          reopenBtn.replaceWith(submitBtn);
        })
        .catch(err => {
          alert('Error reopening study.');
          console.error(err);
        });
      }
    });
  }
});
</script>