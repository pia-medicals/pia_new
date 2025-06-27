<?php
  $con = $this->getConnection();
$id = $data['sid'];
$id = base64_decode($id);

 $sql = "SELECT studies_id,client_account_ids,actual_tat  FROM studies where studies_id = '$id'";
 $querydata = mysqli_query($con, $sql);  
 $rowdata=mysqli_fetch_array($querydata);   
 $client_account_ids =  $rowdata['client_account_ids'];                       
 $actual_tat =  $rowdata['actual_tat'];                         
                         
 if((empty($actual_tat)) OR ($actual_tat == 0))
 {
 $sql1 = "SELECT contract_tat FROM client_details where client_account_id = '$client_account_ids'";
 $querydata1 = mysqli_query($con, $sql1);  
 $rowdata1=mysqli_fetch_array($querydata1);   
 $actual_tat =  $rowdata1['contract_tat']; 
 if(empty($actual_tat))
 {
  $actual_tat =  0;
 }

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
                   
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active">Edit Tat</li>
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
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Tat</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editUserFrm" name="editUserFrm" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                            <div class="card-body">
                                <?php //$this->alert(); ?>
                                <div class="form-group">
                                    <label>Update Tat</label>
                                    <input type="text" id="tat" class="form-control" required="" name="tat" value="<?php echo $actual_tat; ?>" maxlength="100">
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                                </div>

                               

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/admin/dicom_details_all' ?>" class="btn btn-secondary float-right">Cancel <i aria-hidden="true" class="fa fa-redo"></i></a>
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
    $("#editUserFrm").validate({
        rules: {
            tat: {
                required: true,
            }
        },
        submitHandler: function () {
            save_tat();
        }
    });

    function save_tat() {
       
        $.ajax({
            type: "POST",
            data: {
                tat: $("#tat").val(),
                id: $("#id").val()
            },
            url: "/ajaxV3/update_tat_value",
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
            }
           
        });
    }
</script>