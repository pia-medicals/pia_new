<style>
    /* Table Cell Padding and Font */
    .table td, .table th {
        padding-left: 6px;
        padding-right: 6px;
        font-size: 14px;
    }
    .table th {
        font-size: 14px !important;
    }

    /* Action icon spacing */
    .action-icons a {
        margin-right: 8px;
    }
    .action-icons a:last-child {
        margin-right: 0;
    }

    /* DataTables "Show entries" dropdown customization */
    div.dataTables_length select {
        width: 80px !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        height: auto !important;
        border-radius: 4px !important;
        line-height: 1.4;
    }

    div.dataTables_length label {
        font-size: 14px;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
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

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Miscellaneous Billing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Miscellaneous Billing</li>
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
                            <h3 class="card-title mb-0">Billing List</h3>
                            <div class="ml-auto ms-auto">
                                <a href="<?= SITE_URL ?>/analyst/add_miscellaneous_billing" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="misc_billing_table" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($miscellaneous_billing['results']) && !empty($miscellaneous_billing['results'])): ?>
                                        <?php foreach ($miscellaneous_billing['results'] as $value): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($value['name'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($value['analysis_invoicing_description'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($value['analysis_client_price'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($value['created_at'] ?? '') ?></td>
                                                <td class="text-center action-icons">
                                                    <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing?edit=<?= $value['miscellaneous_billing_id'] ?>" class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5">No miscellaneous billing records found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                             <div class="bottom-title">Miscellaneous Billing</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function () {
        $('#misc_billing_table').DataTable({
            "order": [[3, "desc"]] // Sort by "Date" column
        });
    });
</script>