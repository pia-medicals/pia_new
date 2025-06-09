
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
                                <?php
                                if(isset($miscellaneous_billing['results']) && !empty($miscellaneous_billing['results'])){
                                    foreach ($miscellaneous_billing['results'] as $value) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($value['name'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($value['analysis_invoicing_description'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($value['analysis_client_price'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($value['created_at'] ?? '') ?></td>
                                            <td class="text-center action-icons">
                                                <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing?edit=<?= $value['miscellaneous_billing_id'] ?>" class="edit_link" title="Edit">
                                                    <i class="fa fa-edit" style="color:#007bff;" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php  }
                                } ?>
                                </tbody>
                            </table>
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
        "order": [[3, "desc"]] // Sort by the 4th column ("Date") in descending order
    });
});

</script>