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
                    <h1>Stat Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
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
                <div class="col-12">

                    <div class="card data-tb-style">
                        <div class="card-header">
                            
                           <form method="post" action="/ajaxV3/stat_report_csv">
                            <select class="form-control float-left" id="days" name="days" style="width: 15%; margin-right: 10px;">
                            	<option value="">SELECT DAYS</option>
                            	 <option value="1"> 1 day </option>
                          <option value="3"> 3 days</option>
                          <option value="30">30 days</option>
                            </select>

                          <?php

                           $sql = "SELECT user_id,user_name FROM users where user_type_ids = 3";
                           $sqlstatus = "SELECT status_id ,status  FROM analysis_status";
                            $con = $this->getConnection();
                             $query = mysqli_query($con, $sql);
                              $querysecond = mysqli_query($con, $sql);
                               $querystatus = mysqli_query($con, $sqlstatus);

                                
                             ?>
                              <select class="form-control float-left" id="assignee" name="assignee" style="width: 20%; margin-right: 10px;">
                            	<option value="">SELECT ASSIGNEE</option>
                            	<?php
                            	while ($row = mysqli_fetch_array($query)) {
                            		?>
                            	 <option value="<?php echo $row[0] ?>"> <?php echo $row[1] ?> </option>
                                 <?php
                             }
                             ?>
                            </select>



                             <select class="form-control float-left" style="width: 20%; margin-right: 10px;" id="second_assignee" name="second_assignee">
                            	<option value="">SELECT SECOND ASSIGNEE</option>
                            	<?php
                            	while ($row_second_assignee = mysqli_fetch_array($querysecond)) {
                            		?>
                            	 <option value="<?php echo $row_second_assignee[0] ?>"> <?php echo $row_second_assignee[1] ?> </option>
                                 <?php
                             }
                             ?>
                            </select>



                              <select class="form-control float-left" style="width: 20%; margin-right: 10px;" id="status" name="status">
                            	<option value="">SELECT STATUS</option>
                            	<?php
                            	while ($row_status = mysqli_fetch_array($querystatus)) {
                            		?>
                            	 <option value="<?php echo $row_status[0] ?>"> <?php echo $row_status[1] ?> </option>
                                 <?php
                             }
                             ?>
                            </select>




                           <button type="submit" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Export To CSV</button>

                         </form>
                            
                        </div>
                        <!-- /.card-header -->
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
                                          <th>Analysis Perfomed</th>
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
                                          <th>Analysis Perfomed</th>
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
    $(document).ready(function () {
        var dt = $('#dataTbl').DataTable({
             "lengthMenu": [[100], [100]],
            "order": [[5, "desc"]],
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
                url: "/ajaxV3/get_stat_info",
                type: "post",

                  data: function (d) {
                d.selectedDays = $('#days').val(); 
                d.assignee = $('#assignee').val(); 
                d.secondAssignee = $('#second_assignee').val();
                d.status = $('#status').val();
            }
            }
        });

      $('#days, #assignee, #second_assignee, #status').on('change', function () {
        dt.ajax.reload();
    });


    });
</script>