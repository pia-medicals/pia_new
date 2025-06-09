<style type="text/css">
    .green-row { background-color: green !important; color: white; }
    .table td, .table th { padding-left: 6px; padding-right: 6px; font-size: 14px; }
    .table th { padding-right: 20px !important; font-size: 14px !important; }
    table.dataTable > thead .sorting::before { right: 2px; }
    table.dataTable > thead .sorting::after { right: 10px; }
    #dataTbl .bg-info { background-color: #d9edf7 !important; color: #000 !important; }
    #dataTbl .bg-success { background-color: #dff0d8 !important; color: #000 !important; }
    #dataTbl .bg-warning { background-color: #fcf8e3 !important; color: #000 !important; }
    #dataTbl .bg-default { background-color: #eee !important; color: #000 !important; }
    #dataTbl .bg-danger { background-color: #f2dede !important; color: #000 !important; }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Studies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active">My Studies</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card data-tb-style">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Studies assigned to you</h3>
                            <div class="ml-auto ms-auto">
                                <a href="<?= SITE_URL ?>/analyst/add_new_study" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($my_studies)): ?>
                                        <?php foreach ($my_studies as $study): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($study['studies_id'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($study['accession'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($study['patient_name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($study['mrn'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($study['status'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($study['created_at'] ?? '') ?></td>
                                                <td class="text-center action-icons">
                                                    <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_my?view=<?= htmlspecialchars($study['studies_id'] ?? '') ?>" class="btn btn-info btn-sm">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7">No studies found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>