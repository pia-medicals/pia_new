<!-- Content Wrapper. Contains page content -->
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
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo!empty($page_title) ? $page_title : ''; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo!empty($page_title) ? $page_title : ''; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php /*
              <form action="<?= SITE_URL ?>/admin" autocomplete="off" method="post" accept-charset="utf-8" style="padding: 0; display: inline;">
              <div class="row">
              <div class="col-lg-5 col-md-4">
              <div class="form-group">
              <label>Date From:</label>
              <div class="input-group m-date" id="fromdate" data-target-input="nearest">
              <input type="text" id="from" name="from" class="form-control datetimepicker-input" data-target="#fromdate" value="<?php echo (!empty($result_from)) ? $result_from : ''; ?>" />
              <div class="input-group-append" data-target="#fromdate" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
              </div>
              </div>
              </div>
              </div>
              <div class="col-lg-5 col-md-4">
              <div class="form-group">
              <label>Date To:</label>
              <div class="input-group m-date" id="todate" data-target-input="nearest">
              <input type="text" id="to" name="to" class="form-control datetimepicker-input" data-target="#todate" value="<?php echo (!empty($result_to)) ? $result_to : ''; ?>" />
              <div class="input-group-append" data-target="#todate" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
              </div>
              </div>
              </div>
              </div>
              <div class="col-lg-2 col-md-4">
              <div class="form-group search-button-dashboard">
              <button type="submit" class="btn btn-primary" name="btnSave">Submit</button>
              <button type="submit" class="btn btn-secondary" name="btnReset">Reset</button>
              </div>
              </div>
              </div>
              </form>
             */ ?>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= !empty($jobs_count) ? $jobs_count : 0; ?></h3>
                            <p>Total Jobs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file"></i>
                        </div>
                        <a href="<?= SITE_URL ?>/adminV2/dicom_details_all" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= !empty($jobs_assigned) ? $jobs_assigned : 0; ?></h3>
                            <p>Total Jobs Assigned</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-archive"></i>
                        </div>
                        <a href="<?= SITE_URL ?>/adminV2/dicom_details_assigned" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= !empty($jobs_Under_review) ? $jobs_Under_review : 0; ?></h3>
                            <p>Total Jobs Under Review</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= !empty($jobs_In_progress) ? $jobs_In_progress : 0; ?></h3>
                            <p>Total Jobs In Progress</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3><?= !empty($jobs_Not_assigned) ? $jobs_Not_assigned : 0; ?></h3>
                            <p>Total Jobs Not Assigned</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-forward"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= !empty($jobs_cancelled) ? $jobs_cancelled : 0; ?></h3>
                            <p>Total Jobs Cancelled</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= !empty($jobs_on_hold) ? $jobs_on_hold : 0; ?></h3>
                            <p>Total Jobs On Hold</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-question-circle"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= !empty($jobs_Completed) ? $jobs_Completed : 0; ?></h3>
                            <p>Total Jobs Completed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-suitcase"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3><?= !empty($checkdone) ? $checkdone : 0; ?></h3>
                            <p>Total No. Of Second Check Done</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3><?= !empty($checknotdone) ? $checknotdone : 0; ?></h3>
                            <p>Total No. Of Second Check Not Done</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-minus"></i>
                        </div>
                        <div class="small-box-footer"></div>
                    </div>
                </div>


            </div>


            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Analyst Hours</span>
                            <span class="info-box-number"><?php echo!empty($analyst_hours) ? $analyst_hours : 0; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Customers</span>
                            <span class="info-box-number"><?= !empty($customer_count) ? $customer_count : 0; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Analysts</span>
                            <span class="info-box-number"><?= !empty($analyst_count) ? $analyst_count : 0; ?></span>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row mt-3">

                <!-- weekly count chart -->

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Total Amount Month Wise</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <!--                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                <i class="fas fa-times"></i>
                                                            </button>-->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="analystamount" width="300" height="134"></canvas>                           
                                <?php
                                if (!empty($total_analyst_amount_per_month)) {
                                    $analystdata = array(
                                        'amount' => array(),
                                        'month' => array()
                                    );
                                    foreach ($total_analyst_amount_per_month as $key => $eachmonth) {
                                        $analystdata['amount'][] = $eachmonth;
                                        $analystdata['month'][] = date('F', mktime(0, 0, 0, $key, 10));
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var labels = <?= json_encode($analystdata['month']) ?>;
                                            var type = 'bar';
                                            var yaxis = 'AMOUNT IN $';
                                            var xaxis = 'MONTH';
                                            var data = [{
                                                    label: 'TOTAL AMOUNT IN $',
                                                    data: <?= json_encode($analystdata['amount']) ?>,
                                                    backgroundColor: ['#00c0ef', '#00c0ef', '#00c0ef', '#00c0ef', '#00c0ef'],
                                                    borderColor: ['#00c0ef', '#00c0ef', '#00c0ef', '#00c0ef', '#00c0ef', '#00c0ef'],
                                                    fill: false,
                                                    borderWidth: 1
                                                },
                                            ];
                                            chartthediv('analystamount', labels, data, type, yaxis, xaxis);
                                        });
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>                

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recently Added Customers</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 m-tbl">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Email</th>
                                            <!-- <th>Code</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($jobs_last_user)) {
                                            foreach ($jobs_last_user as $uservalue) {
                                                ?>
                                                <tr>
                                                    <td><i class="fa fa-fw fa-user text-primary" ></i>&emsp;<a href="<?= SITE_URL ?>/admin/customer?edit=<?= $uservalue['user_id']; ?>"><?php echo ucwords($uservalue['user_name']); ?></a></td>
                                                    <td><?php echo $uservalue['email']; ?></td>
                                                    <!-- <td>
                                                        <?php
                                                        if ($uservalue['user_meta'] != '') {
                                                            $userMetaJson = json_decode($uservalue['user_meta'], TRUE);
                                                            echo $userMetaJson['customer_code'];
                                                        }
                                                        ?>
                                                    </td> -->
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="3">Customers not listed</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                            
                        <div class="card-footer text-center">
                            <a href="<?= SITE_URL ?>/admin/customer" class="uppercase">View All Customers</a>
                        </div>                        
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recently Added Studies</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 m-tbl">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Customer</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($jobs_last_study)) {
                                            foreach ($jobs_last_study as $study) {
                                                ?>
                                                <tr>
                                                    <td><i class="fa fa-fw fa-file text-primary"></i>&emsp;<a href="<?= SITE_URL ?>/admin/dicom_details_all"><?php echo ucfirst($study['patient_name']) ?></a></td>
                                                    <td><?php echo $study['webhook_customer']; ?></td>
                                                    <td><?php echo $study['webhook_description']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="3">Studies not listed</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                        <div class="card-footer text-center">
                            <a href="<?= SITE_URL ?>/admin/dicom_details_all" class="uppercase">View All Studies</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cases by Analysis Type</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="case_analysestype" width="300" height="134"></canvas>
                                <?php
                                if (!empty($cases_by_analysesTypes)) {
                                    $weeklydata = array(
                                        'day' => array(),
                                        'count' => array()
                                    );
                                    foreach ($cases_by_analysesTypes as $key => $value) {
                                        $weeklydata['count'][] = $value;
                                        $weeklydata['day'][] = $key;
                                    }
                                    ?>                           
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var labels = <?= json_encode($weeklydata['day']) ?>;
                                            var type = 'pie';
                                            var yaxis = 'CASE COUNT';
                                            var xaxis = 'MONTH';
                                            var data = [{
                                                    label: 'CASE COUNT',
                                                    data: <?= json_encode($weeklydata['count']) ?>,
                                                    backgroundColor: ['#00a65a', '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#2164cb', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a'],
                                                    borderColor: ['#48ba59', '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', '#2164cb', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59'],
                                                    fill: false,
                                                    borderWidth: 1
                                                },
                                            ];
                                            chartthediv_pie('case_analysestype', labels, data, type, yaxis, xaxis);
                                        });
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Total Works & Completed Works Per Day</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="analystamount1" width="300" height="134"></canvas>
                                <?php
                                if (!empty($daily_work_count) && !empty($daily_completed_work_count)) {
                                    $day = array_column($daily_work_count, 'day');
                                    $count = array_column($daily_work_count, 'count');
                                    $c = array_combine($day, $count);

                                    $weeklydata = array(
                                        'day' => array(),
                                        'count' => array()
                                    );

                                    foreach ($c as $key => $value) {
                                        $weeklydata['count'][] = $value;
                                        $weeklydata['day'][] = $key;
                                    }
                                    //daily completed work   

                                    $day1 = array_column($daily_completed_work_count, 'day');
                                    $count1 = array_column($daily_completed_work_count, 'count');

                                    $b = array_combine($day1, $count1);

                                    $dailydata = array(
                                        'day' => array(),
                                        'count' => array()
                                    );

                                    foreach ($b as $key => $value) {
                                        $dailydata['count'][] = $value;
                                        $dailydata['day'][] = $key;
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var labels = <?php
                                if (!empty($weeklydata['day'])) {
                                    echo json_encode($weeklydata['day']);
                                }
                                ?>;
                                            var type = 'bar';
                                            var yaxis = 'CASE COUNT';
                                            var xaxis = 'MONTH';
                                            var data = [{
                                                    label: 'WORK COUNT',
                                                    data: <?php
                                if (!empty($weeklydata['count'])) {
                                    echo json_encode($weeklydata['count']);
                                }
                                ?>,
                                                    backgroundColor: ['#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a'],
                                                    borderColor: ['#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59'],
                                                    fill: false,
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: 'Completed',
                                                    data: <?php
                                if (!empty($dailydata['count'])) {
                                    echo json_encode($dailydata['count']);
                                }
                                ?>,
                                                    backgroundColor: ['#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0'],
                                                    borderColor: ['#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0'],
                                                    fill: false,
                                                    borderWidth: 1
                                                }

                                            ];
                                            chartthediv('analystamount1', labels, data, type, yaxis, xaxis);
                                        });
                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">EAT Vs AT Comparison</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0 m-tbl">
                                    <thead>
                                        <tr> 
                                            <th>MRN</th>
                                            <th>Accession</th>
                                            <th>AT</th>
                                            <th>EAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($actual_vs_expected)) {
                                            foreach ($actual_vs_expected as $at_vs_eat) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $at_vs_eat['mrn'] ?></td>
                                                    <td><?php echo $at_vs_eat['accession'] ?></td>                                        
                                                    <td><?php echo $at_vs_eat['analyst_hours']; ?></td>
                                                    <td><?php echo $at_vs_eat['expected_time']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4">Details not listed</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                        <div class="card-footer text-center">
                            <a href="<?= SITE_URL ?>/admin/study_time_report" class="uppercase">View Study Time Report</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">EAT Vs AT for Different Analyst</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="differentAnalyst" width="300" height="134"></canvas>                           
                                    <?php
                                    if (!empty($analyst_actual_vs_expected)) {
                                        $analyst = array_column($analyst_actual_vs_expected, 'analyst');
                                        $AT_hours = array_column($analyst_actual_vs_expected, 'analyst_hours');

                                        $c = array_combine($analyst, $AT_hours);

                                        $timedetailsAT = array(
                                            'analyst' => array(),
                                            'analyst_hours' => array()
                                        );

                                        foreach ($c as $key => $value) {
                                            $timedetailsAT['analyst_hours'][] = $value;
                                            $timedetailsAT['analyst'][] = $key;
                                        }

                                        $analyst1 = array_column($analyst_actual_vs_expected, 'analyst');
                                        $EAT_hours = array_column($analyst_actual_vs_expected, 'expected_time');

                                        $b = array_combine($analyst1, $EAT_hours);

                                        $timedetailsEAT = array(
                                            'analyst' => array(),
                                            'expected_time' => array()
                                        );

                                        foreach ($b as $key => $value) {
                                            $timedetailsEAT['expected_time'][] = $value;
                                            $timedetailsEAT['analyst'][] = $key;
                                        }
                                        ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var labels = <?php
                                    if (!empty($timedetailsAT['analyst'])) {
                                        echo json_encode($timedetailsAT['analyst']);
                                    }
                                    ?>;
                                            var type = 'bar';
                                            var yaxis = 'EAT Vs AT';
                                            var xaxis = 'ANALYST';
                                            var data = [{
                                                    label: 'EAT',
                                                    data: <?php
                                    if (!empty($timedetailsEAT['expected_time'])) {
                                        echo json_encode($timedetailsEAT['expected_time']);
                                    }
                                    ?>,
                                                    backgroundColor: ['#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a', '#00a65a'],
                                                    borderColor: ['#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59', '#48ba59'],
                                                    fill: false,
                                                    borderWidth: 1
                                                },
                                                {
                                                    label: 'AT',
                                                    data: <?php
                                    if (!empty($timedetailsAT['analyst_hours'])) {
                                        echo json_encode($timedetailsAT['analyst_hours']);
                                    }
                                    ?>,
                                                    backgroundColor: ['#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0', '#9c27b0b0'],
                                                    borderColor: ['#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0', '#9c27b0'],
                                                    fill: false,
                                                    borderWidth: 1
                                                }
                                            ];
                                            chartthediv('differentAnalyst', labels, data, type, yaxis, xaxis);
                                        });

                                    </script>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>    

            </div>

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= ADMIN_LTE3 ?>/plugins/moment/moment.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/chart.js/Chart.bundle.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/js/custom.js"></script>
<script>
                                        $(function () {
                                            $('.m-date').datetimepicker({
                                                format: 'L'
                                            });
                                        });
</script>   