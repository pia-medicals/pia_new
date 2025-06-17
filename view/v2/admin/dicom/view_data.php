<?php
  $con = $this->getConnection();
$id = $data['sid'];
$id = base64_decode($id);

  $sql = "SELECT studies.studies_id, studies.accession, studies.mrn, studies.patient_name, studies.analyst_id, studies.second_analyst_id, studies.dicom_webhook_ids, studies.status_ids, studies.created_at, studies.actual_tat, studies.client_account_ids, dicom_webhook_details.dicom_webhook_id, dicom_webhook_details.webhook_customer, dicom_webhook_details.webhook_description, studies.client_site_name, t4.user_name, t5.user_name AS assignee_name, t6.status, t7.user_name AS second_checker, t3.client_name, t2.contract_tat, t2.contract_tat_unit, t2.contract_tat_minutes FROM studies "
                . "LEFT JOIN dicom_webhook_details ON (studies.dicom_webhook_ids = dicom_webhook_details.dicom_webhook_id)";
        $sql .= " LEFT JOIN client_details t2 ON (studies.client_account_ids = t2.client_account_id)"
                . " LEFT JOIN clients t3 ON (t2.client_ids = t3.client_id)"
                . " LEFT JOIN users t4 ON (t2.user_ids = t4.user_id)"
                . " LEFT JOIN users t5 ON (studies.analyst_id = t5.user_id)"
                . " LEFT JOIN analysis_status t6 ON (studies.status_ids = t6.status_id)"
                . " LEFT JOIN users t7 ON (studies.second_analyst_id = t7.user_id)";

        $sql .= " WHERE 1=1 and studies.studies_id = '$id'";
 
 $query = mysqli_query($con, $sql);


  while ($row = mysqli_fetch_array($query)) {
            $subdata = array();
            // $usergroup = $this->user_group_name($row[5]);

            $studies_id = $row['studies_id'];
            $originalDate = $row['created_at'];
            $newDate = date("m-d-Y h:i A", strtotime($originalDate));
            //$newTime =date("h:i", strtotime($originalDate));
            if (!empty($originalDate)) {
                $datadata = $newDate;
                // $subdata[] = $newTime;
            } else {
                $datadata = $row['created_at'];
                // $subdata[] = $row[8];
            }
          

           
         

            $ctat = trim($row['contract_tat'] . ' ' . $row['contract_tat_unit']);
            $actual_tat = $row['actual_tat'];
            if(!empty($actual_tat))
            { 
               $at = $actual_tat;
            }
            else
            {
            $at = $ctat;    
            }

           

            //   $first_ananlyst_name = $this->Admindb->get_user_by_id($row['analyst_id']);
            $assignee_name = !empty($row['assignee_name']) ? $row['assignee_name'] : '';
           
            //$second_ananlyst_name = $this->Admindb->get_user_by_id($row['second_analyst_id']);
            $second_checker = !empty($row['second_checker']) ? $row['second_checker'] : '';
            

            // $client_details = $this->Admindb->get_client_details_by_id($row['client_account_ids']);
            $client_name = !empty($row['client_name']) ? $row['client_name'] : '';
          

            // site
           $site = !empty($row['client_site_name']) ? $row['client_site_name'] : '';

            $s_status = !empty($row['status']) ? $row['status'] : '';



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
                        <li class="breadcrumb-item active">View Details</li>
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
                            <h3 class="card-title">View Study Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editUserFrm" name="editUserFrm" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                            <div class="card-body">
                               


                                <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <label>Received Date: </label>
                                    <?php echo $datadata; ?>
    </div>
    <div class="col-sm-4">
      <label>Accession: </label>
     <?php echo $row['accession']; ?>
    </div>
    <div class="col-sm-4">
     <label>Patient Name: </label>
     <?php echo $row['patient_name']; ?>
    </div>
  </div>
</div><br><br>


 <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <label>MRN: </label>
     <?php echo  $row['mrn']; ?>
    </div>
    <div class="col-sm-4">
      <label>Default TAT: </label>
    <?php echo $at; ?>
    </div>
    <div class="col-sm-4">
     <label>Webhook Customer: </label>
 <?php echo  $row['webhook_customer']; ?>
    </div>
  </div>
</div><br><br>


 <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <label>Assignee: </label>
   <?php echo $assignee_name; ?>
    </div>
    <div class="col-sm-4">
      <label>Second Check: </label>
    <?php echo $second_checker; ?>
    </div>
    <div class="col-sm-4">
     <label>Customer: </label>
    <?php echo $client_name; ?>
    </div>
  </div>
</div><br><br>



<div class="container">
  <div class="row">
    <div class="col-sm-4">
     <label>Site: </label>
   <?php echo $site; ?>
    </div>
    <div class="col-sm-4">
      <label>Description: </label>
    <?php echo $row['webhook_description']; ?>
    </div>
    <div class="col-sm-4">
     <label>Status: </label>
    <?php echo $s_status; ?>
    </div>
  </div>
</div>


 <div class="card-footer">
                                
                                <a href="<?= SITE_URL . '/admin/dicom_details_all' ?>" class="btn btn-secondary float-right">Back</a>
                            </div>
                               

                 


                 <div class="card-footer text-center text-secondary mt-2">
                                <h5 class="h5 text-bold">View Study Details</h5>
                            </div>         
                       
                    </div>
                    <!-- /.card -->         
                </div>         
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?php

}
?>

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