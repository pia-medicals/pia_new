<div class="dashboard_body content-wrapper admin_dash">
     <?php //print_r($analyst_amount_per_month);die; ?>

    <?php //$this->debug($cases_by_analysesTypes); die;?>
    <?php //$fromdate   =   date('Y-m-d',strtotime($result_to));;echo " to "; echo $result_to; ?>
    <!-- Small boxes (Stat box) -->
    <form action="<?= SITE_URL ?>/admin" autocomplete="off" method="post" accept-charset="utf-8" style="padding: 0; display: inline;">
        <div class="col-lg-12 col-xs-12">
            <div class="col-lg-5 col-xs-5">
                <div class="form-group">
                    <label>Date From:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="from" value="<?php echo (!empty($result_from)) ? $result_from : ''; ?>" name="from" autocomplete="off" />
                    </div>
                    <!-- /.input group -->
                </div>
            </div>
            <div class="col-lg-5 col-xs-5">
                <div class="form-group">
                    <label>Date To:</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="to" name="to" value="<?php echo (!empty($result_to)) ? $result_to : ''; ?>" autocomplete="off"/>
                    </div>
                    <!-- /.input group -->
                </div>
            </div>
            <div class="col-lg-2 col-xs-2">
                <div class="form-group search-button-dashboard">
                    <button type="submit" class="btn btn-primary" name="btnSave">Submit</button>
                    <button type="submit" class="btn btn-primary" name="btnReset">Reset</button>
                </div>
            </div>
        </div> 
    </form>
   

 <section class="content">
        <div class="row widget_box analyst_dash">
            <!-- Date range -->
            <div class="col-lg-12 col-xs-12">
                <div class="col-md-8 col-lg-8 col-xs-8">
                    <?php if ($result_from && $result_to) { ?>
                        <h4>Showing results from <?php
                            echo $result_from;
                            echo " to ";
                            echo $result_to;
                            ?></h4>
                    <?php } ?>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-lg-12 col-xs-12">
                <?php //print_r($total_analyst_amount_per_month);
?>
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_count; ?></h3>
                            <p>Total Jobs</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <a href="<?= SITE_URL ?>/admin/dicom_details_all" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_assigned; ?></h3>
                            <p>Total Jobs Assigned</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-archive"></i>
                        </div>
                        <a href="<?= SITE_URL ?>/admin/dicom_details_assigned" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_Under_review; ?></h3>
                            <p>Total Jobs Under Review</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="col-lg-12 col-xs-12">
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-teal">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_In_progress; ?></h3>
                            <p>Total Jobs In Progress</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-hourglass-2"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_Not_assigned; ?></h3>
                            <p>Total Jobs Not Assigned</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-forward"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col paul-->
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_cancelled; ?></h3>
                            <p>Total Jobs Cancelled</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-remove"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_on_hold; ?></h3>
                            <p>Total Jobs On Hold</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-question-circle"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col paul-->
               
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner text-capitalize">
                            <h3><?= $jobs_Completed; ?></h3>
                            <p>Total Jobs Completed</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-suitcase"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner text-capitalize">
                            <h3><?= $checkdone; ?></h3>
                            <p>Total Number Of Second Check Done</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="col-lg-12 col-xs-12">
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner text-capitalize">
                            <h3><?= $checknotdone; ?></h3>
                            <p>Total Number Of Second Check Not Done</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-minus-o"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div>  
        <!-- /.row -->
        <!--        Line-->
        <div class="row dash-icons">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <hr class="box box-success mb-0" />
                </div>
            </div>
        </div>
        <!--        Line-->

        <div class="row dash-icons">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="box-success">
                        <div class="with-border">
                        </div>
                        <div class="">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analyst Hours</span>
                                    <span class="info-box-number"><?php echo $analyst_hours; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="box-success">
                        <div class="with-border">
                        </div>
                        <div class="">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Customers</span>
                                    <span class="info-box-number"><?= $customer_count; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="box-success">
                        <div class="with-border">
                        </div>
                        <div class="">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analysts</span>
                                    <span class="info-box-number"><?= $analyst_count; ?></span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

            </div>

        </div>
        <!--        <div class="row dash-icons">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analyst Hours</span>
                                    <span class="info-box-number"><?php echo $analyst_hours; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                        /.col 
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Customers</span>
                                    <span class="info-box-number"><?= $customer_count; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analysts</span>
                                    <span class="info-box-number"><?= $analyst_count; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                    </div>
                </div>-->
    </section>

   <div class="clearfix"></div>

    <!-- weekly count chart -->
 
    <div class="col-md-6"> 

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-capitalize">TOTAL AMOUNT MONTH WISE</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="analystamount" style="height: 230px; width: 487px;" width="300" height="155"></canvas>
                    <style type="text/css">
                        .dashboard_body::before {
                            background: #ecf0f5;
                            position: absolute;
                            left: 0;
                            right: 0;
                            top: 0;
                            bottom: 0;
                            margin: auto;
                            content: '';
                        }
                    </style>
                    <script type="text/javascript">
<?php
$analystdata = array(
    'amount' => array(),
    'month' => array()
);
foreach ($total_analyst_amount_per_month as $key => $eachmonth) {
    $analystdata['amount'][] = $eachmonth;
    $analystdata['month'][] = date('F', mktime(0, 0, 0, $key, 10));
}
?>
                        jQuery(document).ready(function () {
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


                </div>
            </div>
            <!-- /.box-body -->
        </div>

    </div>

     <!-- /.RC Customers Data list -->  
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recently Added Customers</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Customer Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($jobs_last_user)) {
                                foreach ($jobs_last_user as $uservalue) {
                                    ?>
                                    <tr>
                                        <td><i class="fa fa-fw fa-user text-primary" ></i>&emsp;<a href="<?= SITE_URL ?>/admin/customer?edit=<?= $uservalue['id']; ?>"><?php echo ucwords($uservalue['name']); ?></a></td>
                                        <td><?php echo $uservalue['email']; ?></td>
                                        <td>
                                            <?php
                                            if ($uservalue['user_meta'] != '') {
                                                $userMetaJson = json_decode($uservalue['user_meta'], TRUE);
                                                echo $userMetaJson['customer_code'];
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td> Customers not listed</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="box-footer text-center">
                        <a href="<?= SITE_URL ?>/admin/customer" class="uppercase">View All Customers</a>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
    <!-- /.RC END Customers Data list --> 
    <!-- /.RC  Added Studies Data list --> 
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recently Added Studies</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
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
                                    <td> Customers not listed</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="box-footer text-center">
                        <a href="<?= SITE_URL ?>/admin/dicom_details_all" class="uppercase">View All Studies</a>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>       
    </div>
    <!-- /.RC END Added Studies Data list -->  
    <!--  Cases by Analysis Type -->
    <div class="col-md-6"> 

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-capitalize"> Cases by Analysis Type</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="case_analysestype" style="height: 230px; width: 487px;" width="300" height="155"></canvas>
                    <script type="text/javascript">
<?php
$weeklydata = array(
    'day' => array(),
    'count' => array()
);

foreach ($cases_by_analysesTypes as $key => $value) {
    $weeklydata['count'][] = $value;
    $weeklydata['day'][] = $key;
}
?>
                        jQuery(document).ready(function () {
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


                </div>
            </div>
            <!-- /.box-body -->
        </div>


    </div>

    <!-- TOTAL HOURS MONTH WISE -->


    <!--  Total Works & Completed works per day -->

    <div class="col-md-6"> 

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-capitalize"> Total Works & Completed works per day</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="analystamount1" style="height: 230px; width: 487px;" width="300" height="155"></canvas>

                    <script type="text/javascript">

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
}
?>
                        jQuery(document).ready(function () {
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


                </div>
            </div>
            <!-- /.box-body -->
        </div>


    </div>

 <!-- / Estimated Analyst Time Vs Analyst logged Time -->  
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">EAT Vs AT Comparison </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
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
                                    <td> Details not listed</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="box-footer text-center">
                        <a href="<?= SITE_URL ?>/admin/study_time_report" class="uppercase">View Study Time Report</a>
                    </div>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
    <!-- / END Actual time vs. Expected time --> 
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">EAT Vs AT for Different Analyst</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
           <div class="box-body">
               <?php    //print_r($analyst_actual_vs_expected); ?>
                <div class="chart">
                    <canvas id="differentAnalyst" style="height: 230px; width: 487px;" width="300" height="155"></canvas>

                    <script type="text/javascript">

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
    //

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
}
?>
                        jQuery(document).ready(function () {
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


                </div>
            </div>
            <!-- /.box-body -->
        </div>       
    </div>
    <!-- / END Analyst Performance -->  
    
    
    
    
    
    
    
    
    <style>
        .search-button-dashboard {
            position: absolute;
            top: 24px;
            bottom: 0;
        }
    </style>
</div>
    
    
    
    
    
    
    
    
    <style>
        .search-button-dashboard {
            position: absolute;
            top: 24px;
            bottom: 0;
        }
    </style>
</div>