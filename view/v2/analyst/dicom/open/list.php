<style>
    .table td,
    .table th {
        padding-left: 6px;
        padding-right: 6px;
        font-size: 14px;
    }

    .table th {
        padding-right: 20px !important;
        font-size: 14px !important;
    }

    table.dataTable > thead .sorting::before {
        right: 2px;
    }

    table.dataTable > thead .sorting::after {
        right: 10px;
    }

    .action-icons a {
        margin-right: 6px;
    }

    .action-icons a:last-child {
        margin-right: 0;
    }
    
    .bottom-title {
        background: #f9f9f9;
        padding: 14px;
        text-align: center;
        font-size: 30px;
        font-weight: 600;
        color: #444;
    }
</style>
<script>
    $(document).ready(function () {
        $('#dataTbl').DataTable({
            responsive: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10
        });
    });
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Open Studies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Open Studies</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php $this->alert(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card data-tb-style">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Open Studies List</h3>
                            <div class="ml-auto">
                                <a href="<?= SITE_URL ?>/analyst/add_new_study" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>Received Date</th>
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Expected Time</th>
                                        <th>Client Site</th>
                                        <th>Action</th>
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
                                                    <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_open?view=<?= htmlspecialchars($row['studies_id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No open studies found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Received Date</th>
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Expected Time</th>
                                        <th>Client Site</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="bottom-title">Open Studies</div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>