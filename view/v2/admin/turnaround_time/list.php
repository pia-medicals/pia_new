<?php
switch ($_SESSION['user']->user_type_ids) {
    case 1:
        // $cntrlr = 'admin';
        $cntrlr = 'mydashboard';
        break;
    case 2:
        $cntrlr = 'manager';
        break;
    default:
        $cntrlr = 'dashboard';
        break;
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo !empty($page_title) ? $page_title : ''; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo !empty($page_title) ? $page_title : ''; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card data-tb-style">
                        <div class="card-header">
                        <form method="post" action="/tat/export_turnaround_time_info_excel" id="exportForm">
                                <h3 class="card-title">List</h3>
                                <input type="hidden" name="searchValue" id="searchValueInput" value="">
                                <button type="submit" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Excel</button>
                               <?php /* <a href="<?= SITE_URL ?>/admin/analyses_category_add" class="btn btn-primary float-right" style="margin-right: 2px;"><i aria-hidden="true" class="fas fa-plus"></i> Add</a> */ ?>
                                <a href="<?= SITE_URL ?>/addnew_turnaround_time" class="btn btn-primary float-right" style="margin-right: 2px;"><i aria-hidden="true" class="fas fa-plus"></i> Add</a>
                            </form>
                            <?php /* <h3 class="card-title">List</h3>
                            <a href="<?= SITE_URL ?>/ajaxV2/users_csv" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Export To CSV</a>
                            <a href="<?= SITE_URL ?>/admin/add_user" class="btn btn-primary float-right" style="margin-right: 2px;"><i aria-hidden="true" class="fas fa-user"></i> Add New TAT</a>
                            <a href="<?= SITE_URL ?>/addnew_turnaround_time" class="btn btn-primary float-right" style="margin-right: 2px;"><i aria-hidden="true" class="fas fa-clock"></i> Add New TAT</a> */ ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="w-50">TAT</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="w-50">TAT</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script>
    $(document).ready(function() {
        var dataTable = $('#dataTbl').DataTable({
            "order": [
                [0, "desc"]
            ],
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "columnDefs": [{
                    targets: 'no-sort',
                    orderable: false
                },
                {
                    responsivePriority: 1,
                    targets: [0, 2]
                }
            ],
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "ajax": {
                url: "/tat/get_turnaround_time_info",
                type: "post"
            }
        });

        $('#dataTbl').on('click', '.change_status', function() {
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to change the status!",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Yes, Change Status!",
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/tat/ajaxChangeTatStatus',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(data) {
                            mug_alert_lite(data.type, data.msg);
                            dataTable.draw();
                        }
                    });
                }
            });

        });

        $('#dataTbl').on('click', '.delete_link', function(e) {
            e.preventDefault();
            var ref = $(this).attr('rel');
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Turn Around Time?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/tat/delete_turnaround_time',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            ref: ref
                        },
                        success: function(data) {
                            dataTable.draw();
                            if (data.type == 'success') {
                                mug_alert_lite('success', data.msg);
                            } else {
                                if (data.msg != '') {
                                    mug_alert_lite('error', data.msg);
                                } else {
                                    mug_alert_lite('error', 'Something went wrong. Please try again later!!');
                                }
                            }
                        }
                    });
                }
            });
        });

        $('#export').click(function(e) {
            e.preventDefault(); // Prevent the default form submission

            $('#searchValueInput').val(dataTable.search());
            $('#exportForm').submit(); // Submit the form after setting the search value
        });

    });
</script>