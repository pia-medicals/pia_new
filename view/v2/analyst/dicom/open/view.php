<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Study</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Study</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php $this->alert(); ?>

        <?php if (isset($_GET['assign']) && $_GET['assign'] == 1): ?>
            <div class="alert alert-warning alert-dismissible text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                This study is already assigned. Do you want to continue?
                <form method="post" action="<?= SITE_URL ?>/analyst/study?view=<?= htmlspecialchars($_GET['view']) ?>">
                    <input type="hidden" name="tat" value="<?= htmlspecialchars($_SESSION['atat'] ?? '') ?>">
                    <input type="hidden" name="customer" value="<?= htmlspecialchars($_SESSION['acustomer'] ?? '') ?>">
                    <input type="hidden" name="reasn" value="1">
                    <input type="hidden" name="studies_id" value="<?= htmlspecialchars($edit['studies_id'] ?? '') ?>">
                    <button type="submit" name="submit" class="btn btn-success btn-flat">Yes</button>
                    <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_open" class="btn btn-danger btn-flat">No</a>
                </form>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Study Details</h3>
            </div>
            <div class="card-body">
                <?php if (!isset($edit['studies_id']) || empty($edit['studies_id'])): ?>
                    <div class="alert alert-danger">Error: Study ID not available. Please select a valid study.</div>
                <?php else: ?>
                    <table class="table table-bordered">
                        <tbody>
                            <tr><td>Accession</td><td><?= htmlspecialchars($edit['accession'] ?? '') ?></td></tr>
                            <tr><td>MRN</td><td><?= htmlspecialchars($edit['mrn'] ?? '') ?></td></tr>
                            <tr><td>Patient Name</td><td><?= htmlspecialchars($edit['patient_name'] ?? '') ?></td></tr>
                            <tr><td>Site Name</td><td><?= htmlspecialchars($edit['client_site_name'] ?? '') ?></td></tr>
                            <tr><td>Exam Time</td><td><?= htmlspecialchars($edit['comment'] ?? '') ?></td></tr>
                            <tr><td>Status</td><td><?= htmlspecialchars($edit['second_comment'] ?? '') ?></td></tr>
                            <tr><td>Customer</td><td>
                                <?php 
                                    $customer_name = '';
                                    if (!empty($edit['client_account_ids'])) {
                                        $customer = $this->Admindb->get_user_by_id($edit['client_account_ids']);
                                        $customer_name = is_array($customer) ? ($customer['user_name'] ?? '') : $customer;
                                    }
                                    echo htmlspecialchars($customer_name);
                                ?>
                            </td></tr>
                            <tr><td>Description</td><td><?= htmlspecialchars($edit['description'] ?? '') ?></td></tr>
                        </tbody>
                    </table>

                    <form method="post" action="<?= SITE_URL ?>/analyst/study?view=<?= htmlspecialchars($_GET['view']) ?>" autocomplete="off" onsubmit="console.log('Form submitted');">
                        <?php $sites = $this->get_all_customers(); ?>
                        <div class="form-group">
                            <label for="customers">Customer</label>
                            <select id="customers" name="customer" class="form-control" required>
                                <option value="" disabled selected>Choose customer</option>
                                <?php foreach ($sites as $value): 
                                    $selected = ($edit['client_account_ids'] ?? '') == $value['user_id'] ? 'selected' : ''; ?>
                                    <option value="<?= htmlspecialchars($value['user_id']) ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($value['user_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="tat" id="tat" value="0">
                            <input type="hidden" name="studies_id" value="<?= htmlspecialchars($edit['studies_id']) ?>">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="submit" class="btn btn-primary btn-flat">Assign to me</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
    a.btn.btn-primary.btn-flat, button.btn.btn-primary.btn-flat {
        border-radius: 3px;
        background: linear-gradient(90deg, #18a0ee, #388cff);
        box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.3);
        color: white;
    }
    .select2-container--default .select2-selection--single {
        height: 35px;
        border: 1px solid #d2d6de !important;
    }
</style>