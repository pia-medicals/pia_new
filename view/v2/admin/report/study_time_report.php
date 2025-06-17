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

$select_array = array(
    1 => 'Super Admin',
    2 => 'Manager',
    3 => 'Analyst',
    4 => 'Patient',
    5 => 'Customer'
);
?> 
<style>
    .ui-datepicker-calendar{
        display: none;
    }
    .ui-datepicker {
        padding: 0px !important;
    }
    .multiselect-native-select{
        display: block;
    }
    .multiselect-selected-text{
        float: left;
    }
    #submit{
        display: block;
    }
    .style-green {
        font-size: 20px;
        color: green;
    }
    .style-red {
        font-size: 20px;
        color: red;
    }
</style>
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/validate/cmxform.css">
<script src="<?= ADMIN_LTE3 ?>/validate/jquery.validate.js"></script>
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/jquery-ui/jquery-ui.min.css">
<script src="<?= ADMIN_LTE3 ?>/plugins/jquery-ui/jquery-ui.min.js"></script>

<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" integrity="sha512-tlP4yGOtHdxdeW9/VptIsVMLtgnObNNr07KlHzK4B5zVUuzJ+9KrF86B/a7PJnzxEggPAMzoV/eOipZd8wWpag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" integrity="sha512-YwbKCcfMdqB6NYfdzp1NtNcopsG84SxP8Wxk0FgUyTvgtQe0tQRRnnFOwK3xfnZ2XYls+rCfBrD0L2EqmSD2sA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= SITE_URL ?>/assets/js/csvExport.min.js"></script>  
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
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off" id="frmRpt" action="<?= SITE_URL ?>/ajaxV2/study_time_report_excel">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Show last</label>
                                            <select name="is_day" id="is_day" class="form-control clsSel">
                                                <option value="">All </option> 
                                                <option value="1"> 1 day </option>
                                                <option value="3"> 3 days</option>
                                                <option value="30">30 days</option>
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Assignee</label>
                                            <select name="is_assignee" id="is_assignee" class="form-control clsSel">
                                                <option value="">--Select--</option>
                                                <?php
                                                foreach ($analysts as $key => $value) {
                                                    echo '<option value="' . $value['user_id'] . '">' . $value['user_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Customer</label>
                                            <select name="is_customer" id="is_customer" class="form-control clsSel">
                                                <option value="">--Select--</option>
                                                <?php
                                                foreach ($customers as $key => $value) {
                                                    echo '<option value="' . $value['user_id'] . '">' . $value['user_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="is_status" id="is_status" class="form-control clsSel">
                                                <option value="">None</option>
                                                <?php
                                                foreach ($statuses as $key => $value) {
                                                    echo '<option value="' . $value['status_id'] . '">' . $value['status'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Time Management</label>
                                            <select name="is_time_mgmt" id="is_time_mgmt" class="form-control clsSel">
                                                <option value="">None</option> 
                                                <option value="TimeNotAdded">Time Not Added</option>
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Time Difference Sorting</label>
                                            <select name="is_sorting" id="is_sorting" class="form-control clsSel">
                                                <option value="">None</option> 
                                                <option value="1" >Ascending Order</option>
                                                <option value="2" >Descending Order</option>   
                                            </select>
                                        </div>								
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">  
                                                <button type="submit" class="btn btn-success" id="export">Export To CSV <i aria-hidden="true" class="fas fa-file-excel"></i></button>	
                                                <button type="button" class="btn btn-warning" id="btn_clear" name="btn_clear">Clear <i class="fas fa-redo"></i></button>
                                            </div>	
                                        </div>
                                    </div>

                                </div>
                            </div>  

                        </form>
                    </div>	

                    <div class="card data-tb-style">
                        <div class="card-header">
                            <h3 class="card-title" style="float: right;">AT:Analyst Time,&nbsp; EAT:Expected Analyst Time</h3>                            
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>                      
                                        <th>Customer</th>
                                        <th>Patient Name</th>
                                        <th>AT [Minutes]</th>
                                        <th>EAT [Minutes]</th>                                                  
                                        <th>Time Difference</th>
                                        <th>Assignee</th>                                           
                                        <th>Status</th>
                                    </tr>
                                </thead>                     
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th> 
                                        <th></th> 
                                    </tr>
                                </tfoot> 
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>


                </div>
                <!-- left column -->
                <div class="col-md-12">
                    <div class="pdf_div">             
                        <div id="pdf_div" class="admin_table my-det-val">

                        </div>
                    </div>
                </div>
                <!-- /.card -->         
            </div>         
        </div>
    </section>
    <!-- /.content -->
</div>
<script>
    $('#is_assignee').multiselect({
        nonSelectedText: 'Select Assignee',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 240,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_assignee').multiselect('deselectAll', false); // Deselect all
            $('#is_assignee').multiselect('select', $(option).val()); // Select the clicked option
        }
    });
    $('#is_customer').multiselect({
        nonSelectedText: 'Select Customer',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 240,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_customer').multiselect('deselectAll', false); // Deselect all
            $('#is_customer').multiselect('select', $(option).val()); // Select the clicked option
        }
    });
    $('#is_day').multiselect({
        nonSelectedText: 'All',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 240,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_day').multiselect('deselectAll', false); // Deselect all
            $('#is_day').multiselect('select', $(option).val()); // Select the clicked option
        }
    });
    $('#is_status').multiselect({
        nonSelectedText: 'None',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_status').multiselect('deselectAll', false); // Deselect all
            $('#is_status').multiselect('select', $(option).val()); // Select the clicked option
        }
    });
    $('#is_time_mgmt').multiselect({
        nonSelectedText: 'None',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 240,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_time_mgmt').multiselect('deselectAll', false); // Deselect all
            $('#is_time_mgmt').multiselect('select', $(option).val()); // Select the clicked option
        }
    });
    $('#is_sorting').multiselect({
        nonSelectedText: 'None',
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 240,
        buttonWidth: '100%',
        enableClickableOptGroups: true, // Ensures single selection behavior
        onChange: function (option, checked) {
            $('#is_sorting').multiselect('deselectAll', false); // Deselect all
            $('#is_sorting').multiselect('select', $(option).val()); // Select the clicked option
        }
    });

    $(document).ready(function () {
        load_data();
    });

    $(".clsSel").on("change", function () {
        load_data();
    });

    $("#btn_clear").on("click", function () {
        $('#is_day').multiselect('deselectAll', false);
        $('#is_day').multiselect('select', ""); // Select the first option (All)
        $('#is_day').multiselect('refresh'); // Refresh to update UI

        $('#is_assignee').multiselect('deselectAll', false);
        $('#is_assignee').multiselect('select', ""); // Select the first option (All)
        $('#is_assignee').multiselect('refresh'); // Refresh to update UI

        $('#is_customer').multiselect('deselectAll', false);
        $('#is_customer').multiselect('select', ""); // Select the first option (All)
        $('#is_customer').multiselect('refresh'); // Refresh to update UI

        $('#is_status').multiselect('deselectAll', false);
        $('#is_status').multiselect('select', ""); // Select the first option (All)
        $('#is_status').multiselect('refresh'); // Refresh to update UI

        $('#is_time_mgmt').multiselect('deselectAll', false);
        $('#is_time_mgmt').multiselect('select', ""); // Select the first option (All)
        $('#is_time_mgmt').multiselect('refresh'); // Refresh to update UI

        $('#is_sorting').multiselect('deselectAll', false);
        $('#is_sorting').multiselect('select', ""); // Select the first option (All)
        $('#is_sorting').multiselect('refresh'); // Refresh to update UI

        $('#dataTbl').DataTable().search('');

        load_data();
    });


    function load_data() {
        var is_customer = $('#is_customer').val();
        var is_assignee = $('#is_assignee').val();
        var is_day = $('#is_day').val();
        var is_status = $('#is_status').val();
        var is_time_mgmt = $('#is_time_mgmt').val();
        var is_sorting = $('#is_sorting').val();

        if ($.fn.DataTable.isDataTable('#dataTbl')) {
            $('#dataTbl').DataTable().destroy();
        }

        var dt = $('#dataTbl').DataTable({
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();

                var numOnly = function (i) {
                    return typeof i === 'string' ? parseFloat(i.replace(/[^\d.]/g, '')) || 0 : i;
                };

                var pageTotal = api
                        .column(3, {page: 'current'})
                        .data()
                        .reduce((a, b) => numOnly(a) + numOnly(b), 0)
                        .toFixed(2);

                var pageTotal1 = api
                        .column(4, {page: 'current'})
                        .data()
                        .reduce((a, b) => numOnly(a) + numOnly(b), 0)
                        .toFixed(2);

                $(api.column(3).footer()).html(pageTotal + ' MIN');
                $(api.column(4).footer()).html(pageTotal1 + ' MIN');
            },
            "lengthMenu": [[100], [100]],
            "order": [[0, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/ajaxV2/get_study_time_report",
                type: "post",
                data: function (d) {
                    d.is_day = is_day;
                    d.is_assignee = is_assignee;
                    d.is_customer = is_customer;
                    d.is_status = is_status;
                    d.is_time_mgmt = is_time_mgmt;
                    d.is_sorting = is_sorting;
                }
            }
        });
    }
</script>