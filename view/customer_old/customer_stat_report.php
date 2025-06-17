<?php
if (isset($_SESSION['user'])) {
?>

    <style>
        .multiselect-native-select {
            display: block;
        }

        .multiselect-selected-text {
            float: left;
        }

        .multiselect-native-select div {
            width: 100% !important;
        }


        .select2-container--bootstrap4 .select2-selection--single {
            display: flex;
            align-items: center;
            overflow: hidden;
            /* Prevent potential overflow issues */
        }

        .select2-selection .select2-selection--single .select2-selection--clearable {
            line-height: 1.5 !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            line-height: 1.5;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            padding-left: 12px;
            /* Adjust as needed */
            flex-grow: 1;
            /* Allow placeholder to take remaining space */
            color: #495057;
        }

        .select2-margin+.select2-container {
            margin-right: 8px;
            /* Or whatever margin you prefer */
            margin-bottom: 8px;
        }

        /* #dataTbl th,
        td {
            white-space: nowrap;
            width: auto;
        } */


        @media (min-width: 768px) {

            /* Medium screens and up */
            .select2-margin+.select2-container {
                margin-bottom: 0;
            }
        }
    </style>

    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" integrity="sha512-tlP4yGOtHdxdeW9/VptIsVMLtgnObNNr07KlHzK4B5zVUuzJ+9KrF86B/a7PJnzxEggPAMzoV/eOipZd8wWpag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" integrity="sha512-YwbKCcfMdqB6NYfdzp1NtNcopsG84SxP8Wxk0FgUyTvgtQe0tQRRnnFOwK3xfnZ2XYls+rCfBrD0L2EqmSD2sA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Stat Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/customer_dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Stat Report</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" action="/customerDetails/new_single_stat_info_excel" id="exportForm">
                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Show last</label>
                                                <select name="days" id="days" class="form-control clsSel">
                                                    <option value="">All</option>
                                                    <option value="1">1 day</option>
                                                    <option value="3">3 days</option>
                                                    <option value="30">30 days</option>
                                                </select>
                                            </div>
                                        </div>

                                        <?php

                                        $sql = "SELECT user_id,user_name FROM users where user_type_ids = 3";
                                        $sqlstatus = "SELECT status_id ,status  FROM analysis_status";
                                        $con = $this->getConnection();
                                        $query = mysqli_query($con, $sql);
                                        $querysecond = mysqli_query($con, $sql);
                                        $querystatus = mysqli_query($con, $sqlstatus);


                                        ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Assignee</label>
                                                <select name="assignee" id="assignee" class="form-control clsSel">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    while ($row = mysqli_fetch_array($query)) {
                                                    ?>
                                                        <option value="<?php echo $row[0] ?>"> <?php echo $row[1] ?> </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Second Assignee</label>
                                                <select name="second_assignee" id="second_assignee" class="form-control clsSel">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    while ($row_second_assignee = mysqli_fetch_array($querysecond)) {
                                                    ?>
                                                        <option value="<?php echo $row_second_assignee[0] ?>"> <?php echo $row_second_assignee[1] ?> </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" id="status" class="form-control clsSel">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    while ($row_status = mysqli_fetch_array($querystatus)) {
                                                    ?>
                                                        <option value="<?php echo $row_status[0] ?>"> <?php echo $row_status[1] ?> </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                        <input type="hidden" name="searchValue" id="searchValueInput" value="">

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" name="export" id="export" class="btn btn-success ms-auto"><i aria-hidden="true" class="fas fa-file-excel"></i> Export To Excel</button>
                                                    <button type="button" class="btn btn-warning" id="btn_clear" name="btn_clear">Clear <i class="fas fa-redo"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="card data-tb-style">
                            <div class="card-body">
                                <table id="dataTbl" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Received Date</th>
                                            <th>Received Time</th>

                                            <th>Accession</th>

                                            <th>Assignee</th>
                                            <th>Second Check</th>


                                            <th>Client Name</th>

                                            <th>Client Number</th>
                                            <th>Site Code</th>
                                            <th>Analysis Performed</th>
                                            <th>Pia Analysis Code</th>

                                            <th>Item Numbers</th>
                                            <th>Status</th>
                                            <th>Completed Date</th>
                                            <th>Completed Time</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Received Date</th>
                                            <th>Received Time</th>

                                            <th>Accession</th>

                                            <th>Assignee</th>
                                            <th>Second Check</th>


                                            <th>Client Name</th>

                                            <th>Client Number</th>
                                            <th>Site Code</th>
                                            <th>Analysis Performed</th>
                                            <th>Pia Analysis Code</th>

                                            <th>Item Numbers</th>
                                            <th>Status</th>
                                            <th>Completed Date</th>
                                            <th>Completed Time</th>
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
            var dt = $('#dataTbl').DataTable({
                "lengthMenu": [
                    [100],
                    [100]
                ],
                "order": [
                    [5, "desc"]
                ],
                "processing": true,
                "serverSide": true,
                "responsive": true,
                /*"columnDefs": [
                    {targets: 'no-sort', orderable: false},
                    {responsivePriority: 1, targets: [0, 1, 5]},
                    {responsivePriority: 2, targets: [-1, -3, -2]}
                ], */
                "autoWidth": false,
                "ajax": {
                    // url: "/customerDetails/single_stat_info",
                    url: "/customerDetails/new_single_stat_info",
                    type: "post",

                    data: function(d) {
                        d.selectedDays = $('#days').val();
                        d.assignee = $('#assignee').val();
                        d.secondAssignee = $('#second_assignee').val();
                        d.status = $('#status').val();
                    }
                }
            });

            $('#days, #assignee, #second_assignee, #status').on('change', function() {
                dt.ajax.reload();
            });

            $('#btn_clear').click(function() {
                $('form')[0].reset();
                $('.clsSel').val('').trigger('change');
                dt.ajax.reload();
            });

            $('#export').click(function(e) {
                e.preventDefault(); // Prevent the default form submission

                $('#searchValueInput').val(dt.search()); // Set search value
                $('#exportForm').submit(); // Submit the form after setting the search value
            });


        });
    </script>



<?php

} else {
    $this->add_alert('danger', 'Unauthorized Access!');
    $this->redirect('customer_login');
    exit();
}
?>