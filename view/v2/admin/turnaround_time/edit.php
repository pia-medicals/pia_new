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
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/validate/cmxform.css">
<script src="<?= ADMIN_LTE3 ?>/validate/jquery.validate.js"></script>
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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <form id="myForm" name="myForm" role="Form" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                            <div class="card-body">
                                <?php //$this->alert(); 
                                ?>
                                <!-- <div class="form-group">
                                    <label for="category">Edit TAT</label>
                                    <input type="text" id="new_tat" class="form-control num" required="" name="new_tat" value="<?= $edit['tat'] ?>" maxlength="3">
                                    <input type="hidden" id="id" name="id" value="<?= $edit['tat_id'] ?>">
                                </div> -->
                                <div class="form-group">
                                    <label for="new_tat">New TAT</label>
                                    <div class="input-group">
                                        <?php
                                        //$tat = explode(" ", $edit['tat']);
                                        ?>
                                        <?php /* <input type="text" id="new_tat" class="form-control num" required name="new_tat" value="<?= $tat[0]; ?>" placeholder="Enter TAT" min="0" step="0.5"> */ ?>
                                        <input type="text" id="new_tat" class="form-control num" required name="new_tat" value="<?= $edit['tat']; ?>" placeholder="Enter TAT" min="0" step="0.5">
                                        <select id="tat_unit" class="form-control" name="tat_unit" required>
                                            <?php /* <option value="Hours" <?php if ($tat[2] == "Hours") echo "selected"; ?>>Hours</option>
                                            <option value="Minutes" <?php if ($tat[2] == "Minutes") echo "selected"; ?>>Minutes</option> */ ?>
                                            <option value="Hours" <?php if ($edit['tat_unit'] == "Hours") echo "selected"; ?>>Hours</option>
                                            <option value="Minutes" <?php if ($edit['tat_unit'] == "Minutes") echo "selected"; ?>>Minutes</option>
                                        </select>
                                        <input type="hidden" id="id" name="id" value="<?= $edit['tat_id'] ?>">
                                    </div>
                                    <small class="form-text text-muted">Enter only 30 if choosing "Minutes". Maximum is 48 hours.</small>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <input type="checkbox" id="active" name="active" <?php if ($edit['is_active'] == true) echo "checked"; ?> data-bootstrap-switch data-on-text="Active" data-off-text="Inactive" data-off-color="danger" data-on-color="secondary">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/turnaround_time' ?>" class="btn btn-secondary float-right">Back</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= ADMIN_LTE3 ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>

    $(".num").keypress(function(event) {
        // Numeric input restriction
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });

    $('#new_tat').on('input', function() {
        var tatValue = $(this).val();
        var tatUnitSelect = $('#tat_unit');

        //Check if the input value is '1' and the unit is 'Hours'
        if (tatValue === '1' && tatUnitSelect.val() === 'Hours') {
            //Change the option text to 'Hour' if it's currently 'Hours'
            if (tatUnitSelect.find('option[value="Hours"]').length) {
                tatUnitSelect.find('option[value="Hours"]').text('Hour');
            }
        } else {
            //Change the option text back to 'Hours' for any other input
            if (tatUnitSelect.find('option[value="Hours"]').length) {
                tatUnitSelect.find('option[value="Hours"]').text('Hours');
            }
            if (!tatUnitSelect.find('option[value="Minutes"]').length) {
                 tatUnitSelect.append($('<option>', {
                    value: 'Minutes',
                    text: 'Minutes'
                 }));
            } else {
                tatUnitSelect.find('option[value="Minutes"]').text('Minutes');
            }
        }
    });

    
    //This is optional but good for completeness if a user might change the unit first.
    $('#tat_unit').on('change', function() {
        var tatValue = $('#new_tat').val();
        var tatUnitSelect = $(this);

        if (tatValue === '1' && tatUnitSelect.val() === 'Hours') {
            if (tatUnitSelect.find('option[value="Hours"]').length) {
                tatUnitSelect.find('option[value="Hours"]').text('Hour');
            }
        } else {
             if (tatUnitSelect.find('option[value="Hours"]').length) {
                tatUnitSelect.find('option[value="Hours"]').text('Hours');
            }
        }
    });


    $(function() {
        // bsCustomFileInput.init();
        // $("input[data-bootstrap-switch]").each(function() {
        //     $(this).bootstrapSwitch('state', $(this).prop('checked'));
        // });

        
            bsCustomFileInput.init();
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

            // Now place the validation here
            $("#myForm").validate({
                rules: {
                    new_tat: {
                        required: true,
                        maxlength: 3,
                        number: true // instead of digits
                    },
                    tat_unit: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    const tatValue = parseFloat($("#new_tat").val());
                    const tatUnit = $("#tat_unit").val();

                    if (isNaN(tatValue) || tatValue <= 0) {
                        mug_alert_all('warning', "Please enter a positive number for TAT.");
                        return false;
                    }

                    if (tatUnit === 'Minutes') {
                        if (tatValue !== 30) {
                            mug_alert_all('warning', "Only 30 minutes is allowed if 'Minutes' is selected.");
                            return false;
                        }
                    } else if (tatUnit === 'Hours') {
                        if (!Number.isInteger(tatValue)) {
                            mug_alert_all('warning', "Please enter a whole number for hours.");
                            return false;
                        }
                        if (tatValue > 48) {
                            mug_alert_all('warning', "Maximum allowed value is 48 hours.");
                            return false;
                        }
                    }

                    edit_tat_details();
                }
            });
        

    });

    // $("#myForm").validate({
    //     rules: {
    //         new_tat: {
    //             required: true,
    //             maxlength: 3,
    //             digits: true
    //         },
    //         tat_unit: {
    //             required: true
    //         }
    //     },
    //     submitHandler: function (form) {
    //         const tatValue = parseFloat($("#new_tat").val());
    //         const tatUnit = $("#tat_unit").val();

    //         // Custom validation
    //         if (isNaN(tatValue) || tatValue <= 0) {
    //             /*alert("Please enter a positive number for TAT.");*/
    //             mug_alert_all('warning',"Please enter a positive number for TAT.");
    //             return false;
    //         }

    //         if (tatUnit === 'minutes') {
    //             if (tatValue !== 30) {
    //                 /* alert("Only 30 minutes is allowed if 'Minutes' is selected."); */
    //                 mug_alert_all('warning',"Only 30 minutes is allowed if 'Minutes' is selected.");
    //                 return false;
    //             }
    //         } else if (tatUnit === 'hours') {
    //             if (!Number.isInteger(tatValue)) {
    //                 /* alert("Please enter a whole number for hours."); */
    //                 mug_alert_all('warning',"Please enter a whole number for hours.");
    //                 return false;
    //             }
    //             if (tatValue > 48) {
    //                 /* alert("Maximum allowed value is 48 hours."); */
    //                 mug_alert_all('warning',"Maximum allowed value is 48 hours.");
    //                 return false;
    //             }
    //         }

    //         edit_tat_details();
    //     }
    // });

    function edit_tat_details() {
        var is_active = '';
        if ($('#active').prop('checked')) {
            is_active = 1;
        } else {
            is_active = 0;
        }
        $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {
                new_tat: $("#new_tat").val(),
                tat_unit: $("#tat_unit").val(),
                active: is_active,
                id: $("#id").val()
            },
            url: "/tat/edit_tat",
            dataType: "json",
            timeout: 60000,
            success: function(response) {
                if (response.success == 11) {
                    //mug_alert_all('warning', 'Warning', response.msg);
                    mug_alert_all('warning', 'Warning', response.msg);
                } else if (response.success > 0) {
                    mug_alert_all('success', 'Success', response.msg);
                } else {
                    if (response.msg != '') {
                        mug_alert_all('error', 'Error', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                    }
                }
                $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
            },
            error: function(jqXHR, textStatus) {
                $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
            }
        });
    }
</script>