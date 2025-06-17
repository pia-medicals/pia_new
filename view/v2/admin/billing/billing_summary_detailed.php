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
                        <form method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off" id="frmBilling">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" placeholder="Start Date" required="" value="">                           
                                        </div>								
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" placeholder="End Date" required="" value=""> 
                                        </div>								
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Choose Customer(s)</label>
                                            <select name="customers[]" id="customers" class="form-control" multiple="">
                                                <?php
                                                foreach ($customers as $key => $value) {
                                                    if (isset($_POST['customers'])) {
                                                        $an = $_POST['customers'];
                                                    } else {
                                                        $an = '';
                                                    }
                                                    if ($an == $value['user_id']) {
                                                        $sel_st = 'selected';
                                                    } else {
                                                        $sel_st = '';
                                                    }
                                                    echo '<option value="' . $value['user_id'] . '" ' . $sel_st . '>' . $value['user_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>								
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 32px;">  
                                                <button type="submit" class="btn btn-primary" id="btn_generate" name="btn_generate">Generate Report <i aria-hidden="true" class="fas fa-file-alt"></i></button>
                                                <button type="button" class="btn btn-warning" id="btn_clear" name="btn_clear">Clear <i class="fas fa-redo"></i></button>
                                            </div>	
                                            <div class="col-md-6">
                                                <div class="float-right" id="s_btn1" style="display:none;margin-top: 32px;">			
                                                    <button type="button" onclick="printTableData('pdf_div');" class="btn btn-info mb-1">Print Report <i aria-hidden="true" class="fas fa-print"></i></button>				
                                                    <button type="button" class="btn btn-success mb-1" id="export">Download CSV <i aria-hidden="true" class="fas fa-download"></i></button>					
                                                </div>
                                            </div>	
                                        </div>
                                    </div>								
                                </div>
                            </div>  
                    </div>
                    </form>		
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

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle"><b>Detailed Billing Summary Via Email</b></h3>
                <button type="button" class="close" aria-label="Close" id="clone">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="name">Enter Email Address</label>
                <input type="text" name="email" id="email" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cl">Close</button>
                <button type="button" id="sent_email" class="btn btn-primary">Send Mail</button>
            </div>
        </div>
    </div>
</div>

<script>

    $("#frmBilling").validate({
        submitHandler: function (form) {
            var formData = $("#frmBilling").serialize();
            var elem = '#btn_generate';
            $(elem).prop("disabled", true).html('Generating.. <i class="fa fa-spinner fa-spin"></i>');
            $('.my-det-val').html('');
            $("#s_btn1").hide();
            //  $(".worksheet_status").find("#btnMRNViewHide").hide();
            $.ajax({
                url: '/ajaxV2/billing_summary_detailed_ajax',
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Handle the response from the server
                    $("#s_btn1").show();
                    $(elem).html('Generate Report <i aria-hidden="true" class="fas fa-file-alt"></i>').attr("disabled", false);
                    $('.my-det-val').html(response);
                    //  $(".worksheet_status").find("#btnMRNViewHide").show();
                    //exe_fn();
                },
                error: function (xhr, status, error) {
                    $("#s_btn1").hide();
                    $(elem).html("Generate Report").attr("disabled", false);
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    });


    /*  $(".worksheet_status").on("click", "#btnMRNViewHide", function(){
     $(".worksheet_status").find(".mrn-bt").toggleClass("hide");
     });  */


    $('.viewValue').on('click', function () {
        var mrn = $('.mrnView').id;
        alert(mrn);
    });

    $("#start_date").datepicker({
        dateFormat: "yy-mm",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
            $('.date-picker').focusout();
        },
        beforeShow: function (input, inst) {
            inst.dpDiv.addClass('month_year_datepicker');
            if ((datestr = $(this).val()).length > 0) {
                year = datestr.substring(datestr.length - 4, datestr.length);
                month = datestr.substring(0, 2);
                $(this).datepicker('option', 'defaultDate', new Date(year, month - 1, 1));
                $(this).datepicker('setDate', new Date(year, month - 1, 1));
                $(".ui-datepicker-calendar").hide();
            }
        }
    });


    $("#end_date").datepicker({
        dateFormat: "yy-mm",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
            $('.date-picker').focusout();
        },
        beforeShow: function (input, inst) {
            inst.dpDiv.addClass('month_year_datepicker');
            if ((datestr = $(this).val()).length > 0) {
                year = datestr.substring(datestr.length - 4, datestr.length);
                month = datestr.substring(0, 2);
                $(this).datepicker('option', 'defaultDate', new Date(year, month - 1, 1));
                $(this).datepicker('setDate', new Date(year, month - 1, 1));
                $(".ui-datepicker-calendar").hide();
            }
        }
    });

    $("#export").click(function () {
        $('#pdf_div').csvExport();
    });

    $('#customers').multiselect({
        nonSelectedText: 'Choose Customer',
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        enableFullValueFiltering: true,
        maxHeight: 230,
        buttonWidth: '100%'
    });

    $("#btn_clear").on("click", function () {
        $("#start_date").val('');
        $("#end_date").val('');
        $('#customers').multiselect('deselectAll', false); // Deselect all options
        $('#customers').multiselect('updateButtonText');  // Update button text
        $('.my-det-val').html('');
        $("#s_btn1").hide();
    });

    function hide_btns() {
        $("#s_btn1").hide();
    }
</script>

