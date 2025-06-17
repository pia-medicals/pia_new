<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Study</h3>
      </div>

      <form id="editStudyForm" role="form" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
        <div class="card-body">

          <!-- Readonly Fields -->
          <?php foreach ([
            'accession' => 'Accession',
            'mrn' => 'MRN',
            'patient_name' => 'Patient Name',
            'client_site_name' => 'Site',
            'webhook_customer' => 'Client'
          ] as $field => $label): ?>
            <div class="form-group">
              <label><?= $label ?></label>
              <input type="text" class="form-control" name="<?= $field ?>" value="<?= htmlspecialchars($edit_studies[$field] ?? '') ?>" readonly>
            </div>
          <?php endforeach; ?>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="webhook_description" readonly><?= htmlspecialchars($edit_studies['comment'] ?? '') ?></textarea>
          </div>

          <div class="form-group">
            <label for="analyst">Technologist</label>
            <input type="text" name="analyst" id="analyst" class="form-control" value="<?= htmlspecialchars($user->user_name ?? '') ?>" readonly>
          </div>

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

          <div class="form-group">
            <label for="status_ids">Status</label>
            <select name="status_ids" id="status_ids" class="form-control">
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
                  <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
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
                $selected = explode(',', $edit_studies['analysis_performed'] ?? '');
                foreach ($selected as $item):
                  $item = trim($item);
                  if ($item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <?= htmlspecialchars($item) ?>
                      <button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button>
                    </li>
                <?php endif; endforeach; ?>
              </ul>
              <input type="hidden" name="analysis_performed_list" id="analysis_performed_list" value="<?= htmlspecialchars(implode(',', $selected)) ?>">
            </div>
          </div>

          <input type="hidden" name="studies_id" value="<?= htmlspecialchars($edit_studies['studies_id']) ?>">
        </div>

        <div class="card-footer">
           <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
          <a href="<?=SITE_URL ?>/analyst/analyst_dicom_details_all" class="btn btn-secondary btn-flat float-right">Back</a>
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
</style>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const addBtn = document.getElementById('addAnalysisBtn');
  const select = document.getElementById('analysis_performed');
  const list = document.getElementById('analysis_list');
  const hidden = document.getElementById('analysis_performed_list');
  let selectedItems = new Set(hidden.value.split(',').filter(Boolean));

  function updateHidden() {
    hidden.value = Array.from(selectedItems).join(',');
  }

  addBtn.addEventListener('click', () => {
    const value = select.value.trim();
    if (!value || selectedItems.has(value)) return;

    selectedItems.add(value);
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.textContent = value;

    const btn = document.createElement('button');
    btn.className = 'btn btn-danger btn-sm remove-btn';
    btn.textContent = 'Remove';
    btn.onclick = () => {
      li.remove();
      selectedItems.delete(value);
      updateHidden();
    };

    li.appendChild(btn);
    list.appendChild(li);
    updateHidden();
  });

  list.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const li = this.closest('li');
      const text = li.childNodes[0].nodeValue.trim();
      selectedItems.delete(text);
      li.remove();
      updateHidden();
    });
  });
});
</script>
