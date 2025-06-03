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

                                <?php
                                $analysis_category = $this->Admindb->getAnalysesCategoryDDWN();
                                ?>

                                <div class="form-group">
                                    <label for="name">Analysis Name</label>
                                    <input type="text" id="name" class="form-control" required="" name="name" placeholder="Analysis Name" maxlength="100">
                                </div>

                                <div class="form-group">
                                    <label for="category">Analysis Category</label>
                                    <select name="category" id="category" class="form-control" required="">
                                        <option value="">-- Choose Category --</option>
                                        <?php
                                        foreach ($analysis_category as $key => $value) {
                                            echo '<option value="' . $value['category_id'] . '">' . $value['category_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="part_number">Item Number</label>
                                    <input type="text" id="part_number" class="form-control num" required="" name="part_number" maxlength="4" placeholder="Item Number">
                                </div>

                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" id="price" class="form-control num" required="" name="price" placeholder="Price" maxlength="8">
                                </div>

                                <div class="form-group">
                                    <label for="minimum_time">Default Time</label>
                                    <select id="minimum_time" name="minimum_time" class="form-control" required="">
                                        <option value="">--Select--</option>
                                        <?php
                                        foreach ($tat_ddwn as $key => $value) {
                                        ?>
                                         <?php /*   <option value="<?php echo $value['tat']; ?>" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == $value['tat']) ? 'selected' : '' ?>><?php echo $value['tat']; ?></option> */ ?>

                                         <option value="<?php echo $value['tat_id']; ?>"><?php echo $value['tat'] . ' ' . $value['tat_unit']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea placeholder="Enter Description" id="description" class="form-control" required="" name="description" maxlength="200"></textarea>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <?php /* <a href="<?= SITE_URL . '/admin/analyses' ?>" class="btn btn-secondary float-right">Back <i aria-hidden="true" class="fa fa-redo"></i></a> */ ?>
                                <a href="<?= isset($page_id) ? SITE_URL . '/analyses_rates?edit=' . $page_id : SITE_URL . '/admin/analyses' ?>" class="btn btn-secondary float-right">
                                    Back
                                </a>

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
<script>
    /* 
     $(function () {
     $(".admin_form").validate({
     submitHandler: function (form) {
     // Show the 'Please wait' message
     $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
     form.submit();
     }
     });
     });
     */

    $(".num").keypress(function(event) {
        // Numeric input restriction
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });

    $("#myForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            category: {
                required: true
            },
            part_number: {
                required: true,
                //minlength: 4,
                maxlength: 4,
                digits: true
            },
            price: {
                required: true,
                maxlength: 10,
                digits: true
            },
            minimum_time: {
                required: true,
                maxlength: 5,
                //digits: true
            },
            description: {
                required: true,
                maxlength: 200
            }
        },
        submitHandler: function() {

            // Force blur to apply formatting before reading the value
            $('#part_number').blur();

            // Optionally: Format again in case it somehow didn't happen
            let partVal = $("#part_number").val().trim();
            if (/^\d+$/.test(partVal)) {
                partVal = partVal.padStart(4, '0');
                $("#part_number").val(partVal);
            }

            save_analyses_details();
        }
    });

    function save_analyses_details() {
        $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {
                name: $("#name").val(),
                category: $("#category").val(),
                part_number: $("#part_number").val(),
                price: $("#price").val(),
                minimum_time: $("#minimum_time").val(),
                description: $("#description").val()
            },
            url: "/ajaxV2/save_analyses_details",
            dataType: "json",
            timeout: 60000,
            success: function(response) {
                if (response.success > 0) {
                    $("#myForm")[0].reset();
                    mug_alert_all('success', 'Success', response.msg);
                } else if (response.success === 'warning') {
                    mug_alert_all('warning', 'Warning', response.msg);
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