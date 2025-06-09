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
                        <form id="myForm" name="myForm" role="Form" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off" >
                            <div class="card-body">
                                <?php //$this->alert(); ?>
                                <div class="form-group">
                                    <label for="category">Analysis Category Name</label>
                                    <input type="text" id="category" class="form-control" required="" name="category" value="<?= $edit['category_name'] ?>" maxlength="100">
                                    <input type="hidden" id="id" name="id" value="<?= $edit['category_id'] ?>">
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
                                <a href="<?= SITE_URL . '/admin/analyses_category' ?>" class="btn btn-secondary float-right">Back</a>
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
    $(function () {
        bsCustomFileInput.init();
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    });

    $("#myForm").validate({
        rules: {
            category: {
                required: true,
                maxlength: 100
            }
        },
        submitHandler: function () {
            edit_analyses_category_details();
        }
    });

    function edit_analyses_category_details() {
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
                category: $("#category").val(),
                active: is_active,
                id: $("#id").val()
            },
            url: "/ajaxV2/edit_analyses_category_details",
            dataType: "json",
            timeout: 60000,
            success: function (response) {
                if (response.success > 0) {
                    mug_alert_all('success', 'Success', response.msg);
                } else
                {
                    if (response.msg != '') {
                        mug_alert_all('error', 'Error', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                    }
                }
                $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
            },
            error: function (jqXHR, textStatus) {
                $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
            }
        });
    }
</script>