<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Edit Study Details</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php $this->alert(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card card-primary">
                        <div class="card-header"><h3 class="card-title mb-0">Study Details</h3></div>
                        <!-- ... the rest of your HTML above remains unchanged -->
<form method="POST">
    <div class="card-body">
        <!-- Readonly Fields -->
        <?php foreach ([
            'accession' => 'Accession',
            'mrn' => 'MRN',
            'patient_name' => 'Patient Name',
            'client_site_name' => 'Site',
            'webhook_customer' => 'Customer'
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
            <label for="analyst">Analyst</label>
            <input type="text" name="analyst" id="analyst" class="form-control" value="<?= htmlspecialchars($user->user_name ?? '') ?>" readonly>
        </div>

        <!-- ✅ FIXED: Use user_id for option values -->
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

        <!-- Analysis Performed Section -->
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
                <button type="button" class="btn btn-success w-100" id="addAnalysisBtn">Add</button>
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

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="<?= SITE_URL ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>


                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Multi-Select -->
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
