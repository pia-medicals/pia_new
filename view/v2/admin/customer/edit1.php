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
<style>
    .pull-right{
        float: right;
    }
</style>
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
                            <a href="<?= SITE_URL ?>/excel/get_excel_customer?cus=<?= $edit['client_account_id'] ?>" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Customer Data</a>

                        </div>

                        <form role="Form" method="post" id="myForm" name="myForm" class="sep">

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Enter Client Name" required="" maxlength="100" value="<?= $edit['client_name'] ?>">
                                            <input type="hidden" id="id" name="id" value="<?= $edit['user_id'] ?>">
                                            <input type="hidden" id="client_id" name="client_id" value="<?= $edit['client_id'] ?>">
                                            <input type="hidden" id="client_account_id" name="client_account_id" value="<?= $edit['client_account_id'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" id="email" class="form-control" required="" name="email" placeholder="Example: john.doe@gmail.com" maxlength="100" value="<?= $edit['email'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Site Code</label>
                                            <input type="text" id="site_code" class="form-control" name="site_code" value="<?= $edit['site_code'] ?>" maxlength="5" placeholder="Enter Site Code">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client Code</label>
                                            <input type="text" id="client_code" class="form-control num" name="client_code" maxlength="4" value="<?= $edit['client_number'] ?>" placeholder="Enter Client Code">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client Site Name</label>
                                            <input type="text" id="client_site_name" class="form-control" name="client_site_name"  value="<?= $edit['client_site_name'] ?>" maxlength="100" placeholder="Enter Client Site Name">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Headquarters ?</label>
                                            <select  id="is_headquarters" name="is_headquarters" class="form-control" required="">
                                                <option value="">-- Select --</option>
                                                <option value="1" <?= (isset($edit['is_headquarters']) && $edit['is_headquarters'] == 1) ? 'selected' : '' ?>>Yes</option>
                                                <option value="0" <?= (isset($edit['is_headquarters']) && $edit['is_headquarters'] == 0) ? 'selected' : '' ?>>No</option>
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Address Line 1</label>
                                            <input type="text" name="address_line1" id="address_line1" class="form-control" value="<?= $edit['address_line1'] ?>" maxlength="100" placeholder="Enter Address Line 1" required="">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" name="address_line2" id="address_line2" class="form-control" value="<?= $edit['address_line2'] ?>" maxlength="50" placeholder="Enter Address Line 2">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" id="city" class="form-control" value="<?= $edit['city'] ?>" maxlength="50" placeholder="Enter City">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" name="state" id="state" class="form-control" value="<?= $edit['state'] ?>" maxlength="50" placeholder="Enter State">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" name="zipcode" id="zipcode" class="form-control" value="<?= $edit['zipcode'] ?>" maxlength="10" placeholder="Enter Zip Code">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control num" maxlength="15" placeholder="Enter Phone Number" value="<?= $edit['phone_number'] ?>">
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Default Turn Around Time</label>
                                            <select id="contract_tat" name="contract_tat" class="form-control customers_choose" required="">
                                                <option value="">Choose Default TAT</option>
                                                <option value="2" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == 2) ? 'selected' : '' ?>>2 Hours</option>
                                                <option value="4" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == 4) ? 'selected' : '' ?>>4 Hours</option>
                                                <option value="6" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == 6) ? 'selected' : '' ?>>6 Hours</option>
                                                <option value="24" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == 24) ? 'selected' : '' ?>>24 Hours</option>
                                            </select>
                                        </div>
                                    </div>  

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select id="is_active" name="is_active" class="form-control" required="">
                                                <option value="">-- Select --</option>
                                                <option value="1" <?= (isset($edit['is_active']) && $edit['is_active'] == 1) ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= (isset($edit['is_active']) && $edit['is_active'] == 0) ? 'selected' : '' ?>>Inactive</option>                                        
                                                <option value="2" <?= (isset($edit['is_active']) && $edit['is_active'] == 2) ? 'selected' : '' ?>>Dormant</option>
                                            </select>
                                        </div>    
                                    </div>  

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                    <a href="<?= SITE_URL . '/admin/customer' ?>" class="btn btn-secondary float-right">Cancel <i aria-hidden="true" class="fa fa-redo"></i></a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- /.card -->         
                </div>         
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Price</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="discount-tab" data-toggle="pill" href="#discount" role="tab" aria-controls="discount" aria-selected="false">Monthly Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="subscription-tab" data-toggle="pill" href="#subscription" role="tab" aria-controls="subscription" aria-selected="false">Subscription</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="maint_fees-tab" data-toggle="pill" href="#maint_fees" role="tab" aria-controls="maint_fees" aria-selected="false">Maintenance Fees</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">

                                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <?php
                                    $analysis = $this->Admindb->getAnalysesDDWN();
                                    ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="analysis">Analysis</label>
                                                <select id="analysis" class="form-control first">
                                                    <option value="">-- Choose Analysis --</option>
                                                    <?php
                                                    foreach ($analysis as $key => $value) {
                                                        echo '<option value="' . $value['analysis_id'] . '" rel="' . $value['analysis_number'] . '" itemref="' . $value['analysis_price'] . '" dataref="' . $value['time_to_analyze'] . '" itemprop="' . $value['analysis_invoicing_description'] . '">' . $value['analysis_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>       
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="rate">Price</label>
                                                <input type="text" id="rate" name="rate" class="form-control num" maxlength="10" >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code">Item Number</label>
                                                <input type="text" id="code" name="code" class="form-control num" maxlength="4">
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="display: none;">
                                            <div class="form-group">
                                                <label for="min_time">Minimum Time</label>
                                                <input type="hidden" id="min_time" name="min_time" class="form-control" maxlength="5" >
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="code" style="visibility: hidden; display: block">add</label>
                                                <button type="button" id="add-row" class="btn btn-success">Add To List</button>
                                                <button type="button" id="delete-analysis" class="btn btn-danger pull-right" style="display: none;">Delete</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <form class="container-fluid" method="post" id="frmAnalysesPrice" name="frmAnalysesPrice">
                                            <div class="col-md-12">
                                                <h3 class="text-center">Analyses Price Details</h3>
                                            </div>
                                            <div class="admin_table mb-5">
                                                <table class="admin">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Analyses</th>
                                                            <th>Description</th>
                                                            <th>Price</th>
                                                            <th>Item</th>
                                                            <th style="display:none;">Minimum Time</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="analysis-tbody">
                                                        <?php
                                                        include_once 'analysis_price_ajax.php';
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php
                                            $dp = 'none';
                                            if (isset($analyses_rate) && $analyses_rate != false) {
                                                $dp = 'block';
                                            }
                                            ?>
                                            <div  style="display: <?php echo $dp; ?>; padding: 15px;" class="col-md-12 analysis-save-section">
                                                <button id="edit-analysis-button" class="btn btn-warning pull-right">Edit</button>
                                            </div> 
                                            <div  style="display: none; padding: 15px;" class="col-md-12 analysis-save-section">
                                                <button type="submit" id="submit_add_rate" name="submit_add_rate" class="btn btn-primary pull-right">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="minimum_value">From</label>
                                                <input type="number" id="minimum_value" min="<?= $max_disc['max_value'] + 1 ?>" class="form-control num" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="max">To</label>
                                                <input type="number" id="maximum_value" class="form-control num" min="<?= $max_disc['max_value'] + 2 ?>" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="percentage">Percentage</label>
                                                <input type="number" id="percentage" class="form-control num"  min="0.01" max="100" maxlength="3">                                                
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="code" style="visibility: hidden; display: block">add</label>
                                                <button id="add-discount" class="btn btn-success">Add to List</button>
                                                <button type="button" id="delete-discount" class="btn btn-danger pull-right" style="display: none;">Delete</button>                             
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <form  class="container-fluid" method="post" id="frmMonthlyDiscount" name="frmMonthlyDiscount">
                                            <div class="col-md-12">
                                                <h3 class="text-center">Monthly Quantity Discount Pricing</h3>
                                            </div>
                                            <div class="admin_table mb-5">
                                                <table class="admin">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>Percentage</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="discount-tbody">
                                                        <?php
                                                        include_once 'monthly_discount_ajax.php';
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php
                                            $dpl = 'none';
                                            if (!empty($discount_pricing_list['results'])) {
                                                $dpl = 'block';
                                            }
                                            ?>
                                            <div style="display: <?php echo $dpl; ?>; padding: 15px;" class="col-md-12 save-discount-range">
                                                <button id="edit-discount-range" class="btn btn-warning pull-right">Edit</button>
                                            </div> 
                                            <div style="display: none; padding: 15px;" class="col-md-12 save-discount-range">
                                                <button type="submit" id="dis_submit" name="dis_submit" class="btn btn-primary pull-right">Save</button>
                                            </div>
                                        </form>
                                    </div>                                      

                                </div>


                                <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                                    <form class="container-fluid" id="admin_form2" method="post" data-form-validate="true"  novalidate="novalidate">
                                        <?php
                                        if (isset($subscription) && !empty($subscription))
                                            $arry_to_remove = array_column($subscription, 'analysis');

                                        $analysis = $this->Admindb->get_my_analyses($_GET['edit'])['results'];
                                        ?>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="analysis">Analysis</label>
                                                    <select  id="analysis1" class="form-control">
                                                        <option selected disabled>Choose Analysis</option>
                                                        <?php
                                                        foreach ($analysis as $key => $value) {
                                                            //  if(in_array($value['id'], $arry_to_remove)) continue;
                                                            //if(isset($edit['analysis']) && $edit['analysis'] == $value['id']) $sel = 'selected'; else $sel = '';
                                                            echo '<option value="' . $value['id'] . '" ' . $sel . ' >' . $value['name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="customer" id="customer" value="<?= $_GET['edit'] ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="count">Count</label>
                                                    <input type="number" id="count" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="code" style="visibility: hidden; display: block">add</label>

                                                    <button type="button" id="add-sub-row" class="btn btn-success">Add To List</button>
                                                    <button type="button" id="delete-subscri" class="btn btn-primary pull-right">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="tab-pane fade" id="maint_fees" role="tabpanel" aria-labelledby="maint_fees-tab">
                                    <form class="container-fluid " method="post" enctype="multipart/form-data" data-form-validate="true"  novalidate="novalidate">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="types">Type</label>
                                                    <select name="maintenance_fee_type" required id="maintenance_fee_type" class="form-control" data-rule-required="true" aria-required="true">
                                                        <option selected disabled>Choose Type</option>
                                                        <option value="monthly" <?php if (isset($meta->maintenance_fee_type) && $meta->maintenance_fee_type == 'monthly') echo "selected"; ?>>Monthly</option>
                                                        <option value="yearly" <?php if (isset($meta->maintenance_fee_type) && $meta->maintenance_fee_type == 'yearly') echo "selected"; ?>>Yearly</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="maint_fee">Amount</label>
                                                    <input type="number" id="maintenance_fee_amount" required class="form-control" name="maintenance_fee_amount" value="<?= $this->issetEcho($meta, 'maintenance_fee_amount') ?>">

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="code" style="visibility: hidden; display: block">add</label>
                                                    <button type="submit" name="submit_maint_fees" class="btn btn-primary pull-right">Set Fee</button>
                                                </div>
                                            </div>


                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>



</div>


<script>
    $("#myForm").validate({
        rules: {
            client_name: {
                required: true,
                maxlength: 100
            },
            email: {
                required: true,
                email: true,
                maxlength: 100
            },
            site_code: {
                maxlength: 5
            },
            client_code: {
                maxlength: 4,
                digits: true
            },
            client_site_name: {
                maxlength: 100
            },
            is_headquarters: {
                required: true
            },
            address_line1: {
                maxlength: 100,
                required: true
            },
            address_line2: {
                maxlength: 50
            },
            city: {
                maxlength: 50
            },
            state: {
                maxlength: 50
            },
            zipcode: {
                maxlength: 10
            },
            phone_number: {
                maxlength: 15
            },
            is_active: {
                required: true
            }
        },
        submitHandler: function () {
            edit_customer_details();
        }
    });

    function edit_customer_details() {
        $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {
                client_name: $("#client_name").val(),
                email: $("#email").val(),
                site_code: $("#site_code").val(),
                client_code: $("#client_code").val(),
                client_site_name: $("#client_site_name").val(),
                is_headquarters: $("#is_headquarters").val(),
                address_line1: $("#address_line1").val(),
                address_line2: $("#address_line2").val(),
                city: $("#city").val(),
                state: $("#state").val(),
                zipcode: $("#zipcode").val(),
                phone_number: $("#phone_number").val(),
                contract_tat: $("#contract_tat").val(),
                active: $("#is_active").val(),
                id: $("#id").val(),
                client_id: $("#client_id").val(),
                client_account_id: $("#client_account_id").val()
            },
            url: "/ajaxV2/edit_customer_details",
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

    $("#analysis").on("change", function () {
        /* var analysis = $("#analysis option:selected").val(); */
        var price = $("#analysis option:selected").attr("itemref");
        var code = $("#analysis option:selected").attr("rel");
        var min_time = $("#analysis option:selected").attr("dataref");
        $("#rate").val(price);
        $("#code").val(code);
        $("#min_time").val(min_time);
    });


    $(document).ready(function () {
        var x = $("#analysis-tbody tr").length;
        $("#add-row").click(function () {
            x++;
            var rate = $("#rate").val();
            var code = $("#code").val();
            var min_time = $('#min_time').val();
            var analysis_id = $("#analysis").val();

            if (!analysis_id) {
                alert("Please Select Analysis");
                return false;
            }

            if (!rate) {
                alert("Please Enter Price");
                return false;
            }

            if (!code) {
                alert("Please Enter Item Number");
                return false;
            }
            var jaba = 0;
            $('.analysis_ids').each(function () {
                var text = $(this).val();
                if (text == analysis_id) {
                    alert("Already Exists");
                    jaba = 1;
                    return false;
                }
            });

            if (jaba == 1) {
                return false;
            }

            var analysis_name = $("#analysis option:selected").html();
            var analysis_desc = $("#analysis option:selected").attr("itemprop");

            var markup = `<tr>
                               <td hidden=""><input class="analysis_ids" type="hidden" value="${analysis_id}" id="analysis_id_${x}" name="analysis_id[]"></td>
                               <td>${x}</td>
                               <td style="text-align: left;">
                                <input type="text" id="analysis_name${x}" class="make-editable form-control" value="${analysis_name}" name="analysis_name[]" required="" maxlength="100"> 
                               </td>
                               <td>
                                <textarea id="analysis_desc${x}" class="make-editable form-control" name="analysis_desc[]" required="" maxlength="200">${analysis_desc}</textarea>
                                </td>    
                               <td> 
                                <input type="text" id="rate${x}" class="make-editable form-control num" value="${rate}" name="rate[]" required="" maxlength="10">
                               </td>
                               <td>
                                <input value="${code}" type="text" id="code${x}" class="make-editable form-control num" name="code[]" required="" maxlength="4">
                              </td>
                              <td style="display:none;"> 
                                <input type="hidden" id="min_time${x}" class="make-editable form-control num" value="${min_time}" name="min_time[]" required="" maxlength="5">
                               </td>
                               <td>
                                <input type="checkbox" class="make-editable record" id="record_${x}" name='record[]'>
                                <input type="hidden" id="analysis_client_price_id_${x}" name="analysis_client_price_id[]" value="0">    
                              </td>
                           </tr>`;

            $('.make-editable').prop("disabled", false);

            $("#edit-analysis-button").css('display', 'none');

            $("table #analysis-tbody").append(markup);

            $(".analysis-save-section").css('display', 'block');
            $("#delete-analysis").show();


            $("#analysis").val('');
            $("#rate").val('');
            $("#code").val('');

        });


        $("#delete-analysis").on("click", function () {
            var selectedValues = [];
            var checkedItemsDel = $("#analysis-tbody").find('.record-del:checked');
            var checkedItems = $("#analysis-tbody").find('.record:checked');
            if (checkedItemsDel.length === 0 && checkedItems.length === 0) {
                alert("Please select at least one record to delete.");
                return;
            }
            checkedItems.each(function () {
                $(this).closest("tr").remove();
            });
            checkedItemsDel.each(function () {
                selectedValues.push($(this).next(".analysis_client_price_id").val());
                $(this).closest("tr").remove();
            });
            if (selectedValues.length > 0) {
                var clientAccountId = $("#client_account_id").val();
                $.ajax({
                    type: "POST",
                    data: {
                        selectedValues: selectedValues,
                        client_account_id: clientAccountId
                    },
                    url: "/ajaxV2/delete_analyses_price_details",
                    dataType: "json",
                    success: function () {
                        $("#analysis-tbody").load("/ajaxV2/analysis_price_ajax", {clientAccountId: clientAccountId}, function (response, status, xhr) {
                            $(".analysis-save-section").css('display', 'none');
                            $("#edit-analysis-button").css('display', 'block');
                            $("#edit-analysis-button").parent(".analysis-save-section").show();
                            $("#delete-analysis").hide();
                            checkPriceLength();
                            x = $("#analysis-tbody tr").length;
                        });
                    }
                });
            }
            checkPriceLength();
            x = $("#analysis-tbody tr").length;
        });

        $("#edit-analysis-button").on("click", function (event) {
            event.preventDefault();
            $('.make-editable').prop("disabled", false);
            $(".analysis-save-section").css('display', 'block');
            $("#edit-analysis-button").css('display', 'none');
            $("#delete-analysis").show();
        });

        var z = $("#discount-tbody tr").length;

        $("#add-discount").click(function (event) {
            z++;
            event.preventDefault();
            var minimum_value = $("#minimum_value").val();
            var maximum_value = $("#maximum_value").val();
            var percentage = $("#percentage").val();
            if (!minimum_value) {
                alert('Please Enter Minimum Value');
                return false;
            }
            if (!maximum_value) {
                alert('Please Enter Maximum Value');
                return false;
            }
            if (!percentage) {
                alert('Please Enter Discount Percentage');
                return false;
            }

            var markup = `<tr>
                                        <td>${z}</td>
                                        <td> 
                                           <input type="number" id="minimum_value${z}" class="make-disc-editable form-control num" value="${minimum_value}" name="minimum_value[]" required="" maxlength="10">
                                        </td>
                                        <td> 
                                           <input type="number" id="maximum_value${z}" class="make-disc-editable form-control num"  name="maximum_value[]" value="${maximum_value}" required="" maxlength="10">
                                        </td>
                                        <td>
                                            <input type="number" id="percentage${z}" class="make-disc-editable form-control num" name="percentage[]" min="0.01" max="100" value="${percentage}" required="" maxlength="3">
                                        </td>     
                                        <td>
                                           <input class="make-disc-editable discount_select" type="checkbox" id="discount_sel_${z}" name="discount_sel[]">
                                           <input type="hidden" class="discount_id" id="discount_id_${z}" name="discount_id[]" value="0">
                                        </td>
                                     </tr>
                                    `;

            $('.make-disc-editable').prop("disabled", false);

            $("#edit-discount-range").css('display', 'none');

            $("table #discount-tbody").append(markup);

            $(".save-discount-range").css('display', 'block');
            $("#delete-discount").show();

            var new_maxivalue = parseInt(maximum_value) + 1;

            $("#minimum_value").val('');
            $("#minimum_value").attr('min', new_maxivalue);
            $("#maximum_value").val('');
            $("#maximum_value").attr('min', (new_maxivalue + 2));
            $("#percentage").val('');
        });

        $("#minimum_value").on("change", function () {
            var val = $(this).val();
            var newval = parseInt(val) + 2;
            $("#maximum_value").attr('min', newval);
        });

        /*   $("#delete-discount").click(function () {
         $("table #discount-tbody").find('input[name="record"]').each(function () {
         if ($(this).is(":checked")) {
         $(this).parents("tr").remove();
         }
         });
         }); */

        $("#edit-discount-range").on("click", function (event) {
            event.preventDefault();
            $('.make-disc-editable').prop("disabled", false);
            $(".save-discount-range").css('display', 'block');
            $("#edit-discount-range").css('display', 'none');
            $("#delete-discount").show();
        });


    });

    $("#frmAnalysesPrice").validate({
        submitHandler: function () {
            $("#submit_add_rate").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            let formData = $('#frmAnalysesPrice').serialize();
            const clientAccountId = $('#client_account_id').val();
            if (clientAccountId) {
                formData += `&id=${encodeURIComponent(clientAccountId)}`; // Manually append data
            }
            $.ajax({
                type: 'POST',
                data: formData,
                url: "/ajaxV2/save_analyses_price_details",
                dataType: "json",
                timeout: 60000,
                success: function (response) {
                    if (response.success > 0) {
                        $("#analysis-tbody").load("/ajaxV2/analysis_price_ajax", {clientAccountId: clientAccountId}, function (response, status, xhr) {
                        });
                        mug_alert_all('success', 'Success', 'Details Saved Successfully.');
                    } else
                    {
                        if (response.msg != '') {
                            mug_alert_all('error', 'Error', response.msg);
                        } else {
                            mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                        }
                    }
                    $('#frmAnalysesPrice').find(".record-del").prop("checked", false);
                    $('.make-editable').prop("disabled", true);
                    $(".analysis-save-section").css('display', 'none');
                    $("#edit-analysis-button").css('display', 'block');
                    $("#edit-analysis-button").parent(".analysis-save-section").show();
                    $("#delete-analysis").hide();
                    $("#submit_add_rate").prop("disabled", false).html('Submit');
                },
                error: function (jqXHR, textStatus) {
                    $("#submit_add_rate").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });

    function checkPriceLength() {
        var len = $("#analysis-tbody tr").length;
        if (len === 0) {
            $(".analysis-save-section").css('display', 'none');
            $("#delete-analysis").hide();
        }
    }




</script>

