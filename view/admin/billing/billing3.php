<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> 
<?php //print_r($wsheet); ?>
<div class="dashboard_body content-wrapper worksheet_status">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary fl100">
            <div class="box-header with-border">
                <h2 class="box-title">Billing Summary - Detailed</h2>
            </div>
            <div class="col-md-12 form_time">
                <form id="frmBilling" method="post" accept-charset="utf-8" class="admin_form inline-block">
                    <div class="">
                        <input type="text" id="start_date" required="" name="start_date" autocomplete="off" placeholder="Start Date" value="<?php if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
                        <?php
                        //  $sites = $this->Admindb->table_full_co('users', 'WHERE user_type_ids = 5');
                        ?>

                        <input type="text" id="end_date" required="" name="end_date" autocomplete="off" placeholder="End Date" value="<?php if (isset($_POST['end_date'])) echo $_POST['end_date']; ?>">
                        <?php
//$sites = $this->Admindb->table_full_co('users', 'WHERE user_type_ids = 5');
                        $sites = $this->Admindb->basic_users_by_group('5');
                        ?>

                        <select name="site[]" id="site" class="form-control"  multiple size="1" style="height: 1%; visibility: hidden;">

                            <?php
                            if(!empty($sites)){                                                                
                            foreach ($sites as $key => $value) {
                                if (isset($_POST['site'])) {
                                    $an = $_POST['site'];
                                } else {
                                    $an = '';
                                }
                                /* if ($an == $value['id']){
                                  $sel_st = 'selected';
                                  }
                                  else{
                                  $sel_st = '';
                                  } */
                                if (!empty($value['id']) && !empty($an) && in_array($value['id'], $an)) {
                                    $sel_st = 'selected';
                                } else {
                                    $sel_st = '';
                                }
                                echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
                            }
                            }
                            ?>
                        </select>

                        <button type="submit" class="btn btn-primary btn-flat" id="btn_generate">Generate Report</button><br><br>
                       <!-- <button type="button" class="btn btn-primary" id="export_mail">Send Report Mail</button>-->
                        <?php
                     /*   if (isset($_POST['start_date'])) {
                            if ($viewMrn != 1) {
                                ?>
                                <button type="submit" class="btn btn-primary btn-flat" name="btnMRNView">MRN No View</button>
                            <?php } if ($viewMrn == 1) { ?>
                                <button type="submit" class="btn btn-primary btn-flat" name="btnMRNMask">MRN No Mask</button>	
                                <?php
                            }
                        } //echo $viewMrn; */
                        ?>
                    </div>
                </form>
                <?php //if (isset($_POST['start_date'])) { ?>
                <div class="float-right buttons_action" id="s_btn1" style="display:none;">
                    <!--                        <button type="submit"  class="btn btn-primary btn-flat">Send data</button>-->
                    <button type="submit" onclick="printData('pdf_div');" class="btn btn-primary btn-flat">Print Report</button>
                    <button type="button" class="btn btn-primary btn-flat" id="export">Download CSV</button>

                </div>
                <?php //} ?>
            </div>

            <?php
// $this->debug($pers);
            ?>
            <div class="pdf_div">
                <div class="">  
                </div>

                <div id="pdf_div" class="admin_table my-det-val">

                </div>
            </div>
        </div>
    </section>
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

<style type="text/css">
    .ui-datepicker-calendar, button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all {
        display: none;
    }

    label#start_date-error, label#site-error {
        display: none !important;
    }
    table.admin.bold tr {
        font-size: 16px;
    }
    .inline-block{
        display: inline-block;
    }
    @media print {

    }

</style>
<script>
//$('.mrnView').on('click',function(){
//	var id	=	this.id.split('-');
//	$('#'+this.id).hide();
//	$('#hide-'+id[1]).show();
//});
//$('.mrnHide').on('click',function(){
//	var id	=	this.id.split('-');
//	$('#'+this.id).hide();
//	$('#view-'+id[1]).show();
//});
  /*  $('.viewValue').on('click', function () {
        //alert(1);
        var mrn = $('.mrnView').id;
        alert(mrn);
    });*/

    $("#frmBilling").on("submit", function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var elem = '#btn_generate';
        $(elem).html("Generating.. <i class='fa fa-spin fa-spinner'></i>").attr("disabled", true);
        $('.my-det-val').html('');
        $("#s_btn1").hide();
        $(".worksheet_status").find("#btnMRNViewHide").hide();
        $.ajax({
            url: '/ajax/get_billing_detail_ajax', // Update with the actual path to your PHP script
            type: 'POST',
            data: formData,
            success: function (response) {
                // Handle the response from the server
                $(elem).html("Generate Report").attr("disabled", false);
                $('.my-det-val').html(response);
                $("#s_btn1").show();
                $(".worksheet_status").find("#btnMRNViewHide").show();
                exe_fn();
            },
            error: function (xhr, status, error) {
                $("#s_btn1").hide();
                $(elem).html("Generate Report").attr("disabled", false);
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
    
    $(".worksheet_status").on("click", "#btnMRNViewHide", function(){
        $(".worksheet_status").find(".mrn-bt").toggleClass("hide");
    });

</script>


<script src="//<?= ASSET ?>js/csvExport.min.js"></script>  
<script>
    $("#export").click(function () {
        $('#pdf_div').csvExport();
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#site').multiselect({
            nonSelectedText: 'Choose Customer',
            includeSelectAllOption: true,
            maxHeight: 230,
            buttonWidth: '150px'
        });


        $("#start_date").datepicker({
            dateFormat: "yy-mm",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            onClose: function (dateText, inst) {

//alert(1);
                //function isDonePressed(){
                //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

                // if (isDonePressed()){
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');

                $('.date-picker').focusout();//Added to remove focus from datepicker input box on selecting date
                //}
            },
            beforeShow: function (input, inst) {

                inst.dpDiv.addClass('month_year_datepicker')

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
                //function isDonePressed(){
                //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

                // if (isDonePressed()){
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');

                $('.date-picker').focusout();//Added to remove focus from datepicker input box on selecting date
                //}
            },
            beforeShow: function (input, inst) {

                inst.dpDiv.addClass('month_year_datepicker')

                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length - 4, datestr.length);
                    month = datestr.substring(0, 2);
                    $(this).datepicker('option', 'defaultDate', new Date(year, month - 1, 1));
                    $(this).datepicker('setDate', new Date(year, month - 1, 1));
                    $(".ui-datepicker-calendar").hide();
                }
            }
        });


    });
</script>
<!--
<script type="text/javascript">
    $("#export_mail").click(function () {
        var elem = $(this);
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var custumers = $("#site").val();
        // alert(start_date);
        if (start_date == '' && end_date == '' && custumers == null) {
            alert("Please Choose Date and Customers");
        } else {
            $(elem).html("Sending.. <i class='fa fa-spin fa-spinner'></i>").attr("disabled", true);
            $.ajax({
                url: "/ajax/get_billing_pdf",
                type: 'POST',
                dataType: 'JSON',
                data: {"start_date": start_date, "end_date": end_date, "custumers": custumers},
                success: function (data)
                {
                    $(elem).html("Send Report Mail").attr("disabled", false);
                    if (data.success == 1) {
                        alert("Report generated and email sent successfully!");
                    } else {
                        alert("Message could not be sent. Please check gmail smtp settings.");
                    }
                }
            });
        }
    });
</script>

-->

<script type="text/javascript">
    $("#export_mail").click(function () {
        //  var elem = $(this);
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var custumers = $("#site").val();
        // alert(end_date);
        if (start_date == '' || end_date == '' || custumers == null) {
            alert("Please Choose Date and Customers");
        } else {
            $('#exampleModalCenter').modal('show');
        }
    });
</script>

<script type="text/javascript">
    $("#sent_email").click(function () {
        var elem = $(this);
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var custumers = $("#site").val();
        var email = $("#email").val();
        if (email == '') {
            alert("Please Enter Email");
        } else {
            $(elem).html("Sending.. <i class='fa fa-spin fa-spinner'></i>").attr("disabled", true);
            $.ajax({
                url: "/ajax/get_billing_pdf",
                type: 'POST',
                dataType: 'JSON',
                data: {"start_date": start_date, "end_date": end_date, "custumers": custumers, "email": email},
                success: function (data)
                {
                    $(elem).html("Send Mail").attr("disabled", false);
                    if (data.success == 1) {
                        $("#start_date").val('');
                        $("#end_date").val('');
                        $("#site").val('');
                        $("#sent_email").val('');
                        $('#exampleModalCenter').modal('hide');
                        alert("Report generated and email sent successfully!");
                    } else {
                        $("#start_date").val('');
                        $("#end_date").val('');
                        $("#site").val('');
                        $("#sent_email").val('');
                        $('#exampleModalCenter').modal('hide');
                        alert("Message could not be sent. Please check gmail smtp settings.");
                    }
                }
            });

        }
    });
</script>

<script type="text/javascript">
    $("#cl").click(function () {
        $("#start_date").val('');
        $("#end_date").val('');
        $("#site").val('');
        $("#sent_email").val('');
        $("#sent_email").html("Send Mail").attr("disabled", false);
        $('#exampleModalCenter').modal('hide');
    });
</script>

<script type="text/javascript">
    $("#clone").click(function () {
        $("#start_date").val('');
        $("#end_date").val('');
        $("#site").val('');
        $("#sent_email").val('');
        $("#sent_email").html("Send Mail").attr("disabled", false);
        $('#exampleModalCenter').modal('hide');
    });

    function exe_fn() {
        $("body").trigger('resize');
    }

</script>

