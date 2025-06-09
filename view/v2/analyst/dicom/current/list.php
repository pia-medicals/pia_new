<?php
$month = $selected_month ?? date('m');
$year = $selected_year ?? date('Y');
?>

<style>
    .table td, .table th {
        padding-left: 6px;
        padding-right: 6px;
        font-size: 14px;
    }
    .table th {
        font-size: 14px !important;
    }
    .action-icons a {
        margin-right: 8px;
    }
    .action-icons a:last-child {
        margin-right: 0;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Studies for <?= date('F Y', strtotime($year . '-' . $month . '-01')) ?></h1>
                    <p class="text-muted mb-0">Select month to filter results</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Studies Current Month</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php $this->alert(); ?>
        <div class="container-fluid">
            <form method="get" action="" class="form-inline mb-3">
                <label for="month" class="mr-2">Select Month:</label>
                <select name="month" id="month" class="form-control mr-2">
                    <?php for ($m = 1; $m <= 12; $m++): 
                        $monthVal = str_pad($m, 2, '0', STR_PAD_LEFT);
                        $selected = ($monthVal == $month) ? 'selected' : '';
                    ?>
                        <option value="<?= $monthVal ?>" <?= $selected ?>><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
                    <?php endfor; ?>
                </select>

                <label for="year" class="mr-2">Select Year:</label>
                <select name="year" id="year" class="form-control mr-2">
                    <?php 
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= 1900; $y--): 
                        $selected = ($y == $year) ? 'selected' : '';
                    ?>
                        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>


            <div class="card-header d-flex align-items-center">
    <h3 class="card-title mb-0">Studies List</h3>
    <div class="ml-auto">
        <a href="<?= SITE_URL ?>/analyst/add_new_study" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add
        </a>
    </div>
</div>

                        <div class="card-body table-responsive">
                            <table id="open_studies_table" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Received Date</th>
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Expected Time</th>
                                        <th>Client Site Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($open_studies)): ?>
                                        <?php foreach ($open_studies as $row): ?>
                                            <tr>
                                                <td><?= date("m-d-Y h:i:s A", strtotime($row['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($row['accession'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['mrn'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['expected_time'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['client_site_name'] ?? '') ?></td>
                                                <td class="text-center action-icons">
                                                    <a href="<?= SITE_URL ?>/analyst/study?view=<?= $row['studies_id'] ?>" class="btn btn-info btn-xs">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center">No studies found for <?= date('F Y', strtotime($year . '-' . $month . '-01')) ?>.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function () {
        $('#open_studies_table').DataTable({
            "lengthMenu": [[25], [25]],
            "order": [[0, "asc"]]
        });
    });
</script>