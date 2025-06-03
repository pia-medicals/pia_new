
<style type="text/css">
    .green-row {
        background-color: green !important;
        color: white; /* Optional: This makes the text white to contrast with the green */
    }
    /*    .table td, .table th {
            padding-left: 8px;
            padding-right: 8px;
            font-size: 16px;
        }
        table.dataTable > thead > tr > th:not(.sorting_disabled), table.dataTable > thead > tr > td:not(.sorting_disabled) {
            padding-right: 15px;
        }*/
    .table td, .table th {
        padding-left: 6px;
        padding-right: 6px;
        font-size: 14px;
    }
    .table th{
        padding-right: 20px !important;
        font-size: 14px !important;
    }
    table.dataTable > thead .sorting::before
    {
        right: 2px;
    }
    table.dataTable > thead .sorting::after{
        right: 10px;
    }

    #dataTbl .bg-info {
        background-color: #d9edf7 !important;
        color: #000 !important;
    }

    #dataTbl .bg-success {
        background-color: #dff0d8 !important;
        color: #000 !important;
    }

    #dataTbl .bg-warning {
        background-color: #fcf8e3 !important;
        color: #000 !important;
    }

    #dataTbl .bg-default {
        background-color: #eee !important;
        color: #000 !important;
    }

    #dataTbl .bg-danger {
        background-color: #f2dede !important;
        color: #000 !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Studies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active">All Studies</li>
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
                            <form method="post" action="/ajaxV3/stat_report_csv">
                                <select class="form-control float-left" id="days" name="days" style="width: 10%; margin-right: 10px;">
                                    <option value="">-- Show Last --</option>
                                    <option value="1">1 day</option>
                                    <option value="3">3 days</option>
                                    <option value="30">30 days</option>
                                </select>

                                <select class="form-control float-left" id="assignee" name="assignee" style="width: 15%; margin-right: 10px;">
                                    <option value="">-- Assignee --</option>
                                    <?php
                                    foreach ($asignee as $key => $row) {
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['user_name']; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <select class="form-control float-left" style="width: 12%; margin-right: 10px;" id="second_check" name="second_check">
                                    <option value="">-- Second Check --</option> 
                                    <option value="1">Yes</option>
                                    <option value="2" >No</option>
                                </select>

                                <select class="form-control float-left" style="width: 15%; margin-right: 10px;display:none;" id="second_assignee" name="second_assignee">
                                    <option value="">-- Reviewer --</option>
                                    <?php
                                    foreach ($asignee as $key => $row_second_assignee) {
                                        ?>
                                        <option value="<?php echo $row_second_assignee['user_id']; ?>"> <?php echo $row_second_assignee['user_name']; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>


                                <select class="form-control float-left" style="width: 12%; margin-right: 10px;" id="status" name="status">
                                    <option value="">-- Status --</option>
                                    <?php
                                    foreach ($analysis_statuses as $key => $row_status) {
                                        ?>
                                        <option value="<?php echo $row_status['status_id'] ?>"> <?php echo $row_status['status'] ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>



                                <button type="button" id="reset_filter" class="btn btn-danger" name="reset_filter">Reset Filter</button>
                                <button type="submit" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Export To CSV</button>

                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Received Date</th>

                                        <th>Accession</th>

                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Default TAT</th>

                                        <th>Webhook Customer</th>
                                        <th>Assignee</th>

                                        <th>Second Check</th>
                                        <th>Customer</th>
                                        <th>Site</th>
                                        <th>Description</th>
                                        <th>Status</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>                     
                                <tfoot>
                                    <tr>
                                        <th>Received Date</th>

                                        <th>Accession</th>

                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Default TAT</th>

                                        <th>Webhook Customer</th>
                                        <th>Assignee</th>

                                        <th>Second Check</th>
                                        <th>Customer</th>
                                        <th>Site</th>
                                        <th>Description</th>
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
    $(document).ready(function () {
        var dt = $('#dataTbl').DataTable({
            "lengthMenu": [[100], [100]],
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            //"responsive": true,
            /*"columnDefs": [
             {targets: 'no-sort', orderable: false},
             {responsivePriority: 1, targets: [0, 1, 5]},
             {responsivePriority: 2, targets: [-1, -3, -2]}
             ], */
            "autoWidth": false,
            "ajax": {
                url: "/ajaxV3/get_studies_info",
                type: "post",

                data: function (d) {
                    d.selectedDays = $('#days').val();
                    d.assignee = $('#assignee').val();
                    d.second_check = $("#second_check").val();
                    d.secondAssignee = $('#second_assignee').val();
                    d.status = $('#status').val();
                }
            },
            /*  "rowCallback": function (row, data) {
             // Assuming the 7th column (index 6) contains status
             if (data[11] == 'Not Assigned') { // Change index if needed
             $(row).css({
             "background-color": "#dc3545",
             "color": "black"
             });
             }
             
             if (data[11] == 'On hold') { // Change index if needed
             $(row).css({
             "background-color": "#ffc107",
             "color": "black"
             });
             }
             
             if (data[11] == 'In progress') { // Change index if needed
             $(row).css({
             "background-color": "#0dcaf0",
             "color": "black"
             });
             }
             
             if (data[11] == 'Completed') { // Change index if needed
             $(row).css({
             "background-color": "#198754",
             "color": "black"
             });
             }
             }*/
        });

        $('#days, #assignee, #second_assignee, #status, #second_check').on('change', function () {
            var second = $('#second_check').val();
            if (second == '1') {
                $("#second_assignee").show();
            } else {
                $("#second_assignee").hide();
            }
            dt.ajax.reload();
        });

        $("#reset_filter").on('click', function () {
            $('#second_check').val('');
            $("#second_assignee").val('');
            $('#days').val('');
            $('#assignee').val('');
            $('#status').val('');
            dt.ajax.reload();
        });

    });
</script>