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
    .pull-right {
        float: right;
    }
</style>
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

    <style>
        #password-rules {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            margin-top: 10px;
        }

        #password-rules.show {
            display: block;
            opacity: 1;
        }

        .card-expand {
            transition: max-height 0.6s ease;
            overflow: hidden;
            /* max-height: 600px; */
            /* max-height: 66vh; */
            /* adjust if needed */
        }

        .card-collapsed {
            /* max-height: 500px; */
            max-height: 75vh;
            /* default height */
        }

        .bold {
            font-weight: bold;
        }

        #max-length-warning {
            transition: opacity 0.3s ease;
        }
    </style>

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
                            <!-- <a href="<?= SITE_URL ?>/excel/get_excel_customer?cus=<?= $edit['client_account_id'] ?>" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Client Data</a> -->
                            <a href="<?= SITE_URL ?>/customer_excel_details?cus=<?= $edit['client_account_id']; ?>&ud=<?= $edit['user_id'] ?>" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Client Data</a>

                        </div>

                        <form role="Form" method="post" id="myForm" name="myForm" class="sep">

                            <div class="card-body card-expand card-collapsed">
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
                                            <label>Client Number</label>
                                            <input type="text" id="client_code" class="form-control num" name="client_code" maxlength="4" value="<?= $edit['client_number'] ?>" placeholder="Enter Client Number">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client Site Name</label>
                                            <input type="text" id="client_site_name" class="form-control" name="client_site_name" value="<?= $edit['client_site_name'] ?>" maxlength="100" placeholder="Enter Client Site Name">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Headquarters ?</label>
                                            <select id="is_headquarters" name="is_headquarters" class="form-control" required="">
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
                                                <?php
                                                foreach ($tat_ddwn as $key => $value) {
                                                    $combined_tat = $value['tat'] . ' ' . $value['tat_unit'];
                                                    $combined_seltat = $edit['contract_tat'] . ' ' . $edit['contract_tat_unit'];
                                                ?>
                                                    <?php /* <option value="<?php echo $value['tat']; ?>" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == $value['tat']) ? 'selected' : '' ?>><?php echo $value['tat']; ?></option> */ ?>
                                                    <?php /* <option value="<?php echo $combined_tat; ?>" <?= (isset($edit['contract_tat']) && $edit['contract_tat'] == $combined_tat) ? 'selected' : '' ?>><?php echo $combined_tat; ?></option> */ ?>
                                                    <?php /* <option value="<?php echo $combined_tat; ?>" <?= ($combined_seltat == $combined_tat) ? 'selected' : '' ?>><?php echo $combined_tat; ?></option> */ ?>
                                                    <option value="<?php echo $value['tat_id']; ?>" <?= ($combined_seltat == $combined_tat) ? 'selected' : '' ?>><?php echo $combined_tat; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" id="password" required="" class="form-control" name="password" placeholder="Enter New Password" maxlength="15">
                                            <ul id="password-rules" class="list-unstyled mt-2 mb-0">
                                                <p id="max-length-warning" style="display:none; color:#d9534f; font-weight:bold; margin-top:10px;">
                                                    <i class="fas fa-exclamation-triangle"></i> Maximum 15 characters allowed
                                                </p>
                                                <?php /* <li id="rule-length" class="text-danger"><i class="fas fa-times"></i> <em>At least 4 characters</em></li> */ ?>
                                                <li id="rule-minlength" class="text-danger"><i class="fas fa-times"></i> <em>At least 4 characters</em></li>
                                                <?php /* <li id="rule-maxlength" class="text-danger"><i class="fas fa-times"></i> <em>Maximum 15 characters allowed</em></li> */ ?>
                                                <li id="rule-uppercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one uppercase letter (A–Z)</em></li>
                                                <li id="rule-lowercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one lowercase letter (a–z)</em></li>
                                                <li id="rule-number" class="text-danger"><i class="fas fa-times"></i> <em>At least one number (0–9)</em></li>
                                                <li id="rule-special" class="text-danger"><i class="fas fa-times"></i> <em>At least one special character (!@#$...)</em></li>
                                            </ul>
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

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/admin/customer' ?>" class="btn btn-secondary float-right">Back</a>
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
                                <?php /* <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Setup</a>
                                </li> */ ?>
                                <li class="nav-item">
                                    <a class="nav-link active" id="subscription-tab" data-toggle="pill" href="#subscription" role="tab" aria-controls="subscription" aria-selected="true">Subscription</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="discount-tab" data-toggle="pill" href="#discount" role="tab" aria-controls="discount" aria-selected="false">Monthly Discount</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="maint_fees-tab" data-toggle="pill" href="#maint_fees" role="tab" aria-controls="maint_fees" aria-selected="false">Maintenance Fees</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">

                                <?php /* <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <?php
                                    $clnt_acc_id = $edit['client_account_id'];
                                    $analysis = $this->Admindb->getAnalysesDDWN($clnt_acc_id);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="analysis">Analysis</label>
                                                <select id="analysis" class="form-control first">
                                                    <option value="">-- Choose Analysis --</option>
                                                    <?php
                                                    foreach ($analysis as $key => $value) {
                                                        // echo '<option value="' . $value['analysis_id'] . '" rel="' . $value['analysis_number'] . '" itemref="' . $value['analysis_price'] . '" dataref="' . $value['time_to_analyze'] . '" itemprop="' . $value['analysis_invoicing_description'] . '">' . $value['analysis_name'] . '</option>';
                                                        echo '<option value="' . $value['analysis_id'] . '" rel="' . $value['analysis_number'] . '" itemref="' . $value['analysis_price'] . '" dataref="' . $value['time_to_analyze'] . '" itemprop="' . $value['analysis_invoicing_description'] . '">' . $value['analysis_name'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="rate">Price</label>
                                                <input type="text" id="rate" name="rate" class="form-control num" maxlength="10">
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
                                                <input type="hidden" id="min_time" name="min_time" class="form-control" maxlength="5">
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
                                                            <th>Analysis</th>
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
                                            <div style="display: <?php echo $dp; ?>; padding: 15px;" class="col-md-12 analysis-save-section">
                                                <button id="edit-analysis-button" class="btn btn-warning pull-right">Edit</button>
                                            </div>
                                            <div style="display: none; padding: 15px;" class="col-md-12 analysis-save-section">
                                                <button type="submit" id="submit_add_rate" name="submit_add_rate" class="btn btn-primary pull-right">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div> */ ?>


                                <div class="tab-pane fade show active" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                                    <?php
                                    include_once 'subscription_ajax.php';
                                    ?>
                                </div>


                                <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                                    <form method="post" id="frmDiscountSec">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="analysis">Analysis</label>
                                                    <select id="gt-analysis" class="form-control first">
                                                        <option value="">-- Choose Analysis --</option>
                                                        <?php
                                                        /*
                                                        foreach ($analysis as $key => $value) {
                                                            echo '<option value="' . $value['analysis_id'] . '" rel="' . $value['analysis_number'] . '" itemref="' . $value['analysis_price'] . '" dataref="' . $value['time_to_analyze'] . '" itemprop="' . $value['analysis_invoicing_description'] . '">' . $value['analysis_name'] . '</option>';
                                                        }
                                                            */
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="minimum_value">From</label>
                                                    <input type="number" id="minimum_value" min="<?= $max_disc['max_value'] + 1 ?>" class="form-control num" max="9999999999">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="max">To</label>
                                                    <input type="number" id="maximum_value" class="form-control num" min="<?= $max_disc['max_value'] + 2 ?>" max="9999999999">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="percentage">Percentage</label>
                                                    <input type="number" id="percentage" class="form-control perc" min="0.01" max="100">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="code" style="visibility: hidden; display: block">add</label>
                                                    <button id="add-discount" class="btn btn-success">Add to List</button>
                                                    <button type="button" id="delete-discount" class="btn btn-danger pull-right" style="display: none;">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <form class="container-fluid" method="post" id="frmMonthlyDiscount" name="frmMonthlyDiscount">
                                            <div class="col-md-12">
                                                <h3 class="text-center">Monthly Quantity Discount Pricing</h3>
                                            </div>
                                            <div class="admin_table mb-5">
                                                <table class="admin">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Analysis</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>Percentage</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="discount-tbody">
                                                        <?php
                                                        // include_once 'monthly_discount_ajax.php';
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


                                <div class="tab-pane fade" id="maint_fees" role="tabpanel" aria-labelledby="maint_fees-tab">

                                    <form class="container-fluid" method="post" id="frmMaintenanceFee" name="frmMaintenanceFee">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="types">Type</label>
                                                    <select name="maintenance_fee_type" required id="maintenance_fee_type" class="form-control" data-rule-required="true" aria-required="true">
                                                        <option value="">Choose Type</option>
                                                        <option value="monthly">Monthly</option>
                                                        <option value="yearly">Yearly</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" id="maintenance_fee_amount" required="" class="form-control" name="maintenance_fee_amount" min="0" max="9999999999">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label style="visibility: hidden; display: block">add</label>
                                                    <button type="submit" name="submit_maint_fees" id="submit_maint_fees" class="btn btn-primary pull-right">Set Fee</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-12">
                                            <h3 class="text-center">Maintenance Fees</h3>
                                        </div>
                                        <div class="admin_table mb-5">
                                            <table class="admin">
                                                <thead>
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Maintenance Type</th>
                                                        <th>Maintenance Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="maintenance-fee-tbody">
                                                    <?php
                                                    include_once 'maintenance_fees_ajax.php';
                                                    ?>
                                                </tbody>
                                            </table>
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
    /* $.validator.addMethod("minLengthCheck", function(value, element) {
        return value.length >= 4;
    }, "Password must be at least 4 characters long.");

    $.validator.addMethod("hasUppercase", function(value, element) {
        return /[A-Z]/.test(value);
    }, "Password must include at least one uppercase letter.");

    $.validator.addMethod("hasLowercase", function(value, element) {
        return /[a-z]/.test(value);
    }, "Password must include at least one lowercase letter.");

    $.validator.addMethod("hasNumber", function(value, element) {
        return /[0-9]/.test(value);
    }, "Password must include at least one number.");

    $.validator.addMethod("hasSpecialChar", function(value, element) {
        return /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value);
    }, "Password must include at least one special character.");

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
            password: {
                required: true,
                minLengthCheck: true,
                hasUppercase: true,
                hasLowercase: true,
                hasNumber: true,
                hasSpecialChar: true,
                maxlength: 15
            },
            is_active: {
                required: true
            }
        },
        messages: {
            password: {
                required: "Please enter a password.",
                maxlength: "Password cannot be longer than 15 characters."
            }
        },
        onkeyup: function(element, event) {
            if (element.id === "password") {
                $(element).valid();
            } else {
                // For other fields, you might want to keep the default behavior
                $(element).valid();
            }
        },
        submitHandler: function() {
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
                password: $("#password").val(),
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
            success: function(response) {
                if (response.success > 0) {
                    mug_alert_all('success', 'Success', response.msg);
                } else {
                    if (response.msg) {
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
    } */




    $(document).ready(function() {
        const passwordInput = $('#password');
        const form = $('#myForm');
        const cardBody = $('.card-body');
        const passwordRules = $('#password-rules');

        function validatePasswordRules(password) {
            let rules = {
                minlength: password.length >= 4,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            $('#password-rules li').removeClass('text-success text-danger bold')
                .find('i').removeClass('fa-check fa-times');

            for (let rule in rules) {
                let $li = $('#rule-' + rule);
                if (rules[rule]) {
                    $li.addClass('text-success');
                    $li.find('i').addClass('fas fa-check');
                } else {
                    $li.addClass('text-danger bold');
                    $li.find('i').addClass('fas fa-times');
                }
            }

            return Object.values(rules).every(Boolean);
        }

        function resetPasswordRulesUI() {
            $('#password-rules li')
                .removeClass('text-success bold')
                .addClass('text-danger')
                .each(function() {
                    $(this).find('i').removeClass('fa-check').addClass('fa-times');
                });

            passwordRules.removeClass('show').hide();
            cardBody.addClass('card-collapsed');
        }

        passwordInput.on('focus', function() {
            passwordRules.fadeIn(400, function() {
                passwordRules.addClass('show');
            });
            cardBody.removeClass('card-collapsed');
        });

        passwordInput.on('keydown', function(e) {
            const val = $(this).val();
            const controlKeys = [8, 37, 38, 39, 40, 46];

            if (val.length >= 15 && !controlKeys.includes(e.keyCode)) {
                $('#max-length-warning').fadeIn(200);
            } else {
                $('#max-length-warning').fadeOut(200);
            }
        });

        passwordInput.on('keyup', function() {
            const password = $(this).val();
            validatePasswordRules(password);

            if (password.length < 15) {
                $('#max-length-warning').fadeOut(200);
            }
        });

        form.validate({
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
                    required: true,
                    maxlength: 100
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
                password: {
                    required: true
                },
                is_active: {
                    required: true
                }
            },
            submitHandler: function() {
                const isValid = validatePasswordRules(passwordInput.val());
                if (!isValid) {
                    alert('Please ensure the password meets all the listed requirements.');
                    passwordInput.focus();
                    return false;
                }

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
                    password: $("#password").val(),
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
                success: function(response) {
                    if (response.success > 0) {
                        //form[0].reset();
                        resetPasswordRulesUI();
                        mug_alert_all('success', 'Success', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', response.msg || 'Something went wrong. Please try again later!!');
                    }
                    $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
                },
                error: function() {
                    $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });




    $(document).ready(function() {
        var x = $("#analysis-tbody tr").length;
        $("#add-row").click(function() {
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
            $('.analysis_ids').each(function() {
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
            var analysis_desc = $("#analysis option:selected").attr("itemprop") ? $("#analysis option:selected").attr("itemprop") : '';

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


        $("#delete-analysis").on("click", function() {
            var selectedValues = [];
            var checkedItemsDel = $("#analysis-tbody").find('.record-del:checked');
            var checkedItems = $("#analysis-tbody").find('.record:checked');
            if (checkedItemsDel.length === 0 && checkedItems.length === 0) {
                alert("Please select at least one record to delete.");
                return;
            }
            checkedItems.each(function() {
                $(this).closest("tr").remove();
            });
            checkedItemsDel.each(function() {
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
                    success: function() {
                        $("#analysis-tbody").load("/ajaxV2/analysis_price_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {
                            $(".analysis-save-section").css('display', 'none');
                            $("#edit-analysis-button").css('display', 'block');
                            $("#edit-analysis-button").parent(".analysis-save-section").show();
                            $("#delete-analysis").hide();
                            checkPriceLength();
                            x = $("#analysis-tbody tr").length;
                        });
                        $("#subscription").load("/ajaxV2/subscription_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {
                            $("#subscription .save-subscription").css('display', 'none');
                            $("#subscription #edit-subscription").css('display', 'block');
                            $("#subscription #edit-subscription").parent(".save-subscription").show();
                            $("#subscription #delete-subscription").show();
                            checkSubscriptionLength();
                            z = $("#subscription").find("#subscription-tbody tr").length;
                            enable_subscription();
                        });
                    }
                });
            }
            checkPriceLength();
            x = $("#analysis-tbody tr").length;
        });

        $("#edit-analysis-button").on("click", function(event) {
            event.preventDefault();
            $('.make-editable').prop("disabled", false);
            $(".analysis-save-section").css('display', 'block');
            $("#edit-analysis-button").css('display', 'none');
            $("#delete-analysis").show();
        });



        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Monthly Discount Tab
        var z = $("#discount-tbody tr").length;


        const $discountTbody = $('#discount-tbody');
        const $clientId = <?= $edit['client_account_id'] ?>;

        function loadAnalyseDiscountTable() {
            $.ajax({
                url: "/analyses_discount_list",
                type: "POST",
                // data: { selection: selection, client_id: $clientId },
                data: {
                    client_account_id: $clientId
                },
                dataType: "json",
                success: function(response) {
                    let rows = '';
                    if (response.data && response.data.length > 0) {
                        $.each(response.data, function(index, row) {
                            rows += `
                                <tr data-price-id="${row[5]}">
                                    <td>${row[0]}</td>
                                    <td class="analysis-name">${row[1]}</td>
                                    <td class="analysis-from">${row[2]}</td>
                                    <td class="analysis-to">${row[3]}</td>
                                    <td class="analysis-percent">${row[4]}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs mb-1 disc-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs disc-delete-btn mb-1" data-disc-id="${row['6']}"><i class="fas fa-trash-alt"></i> Delete</button>
                                        <button type="button" class="btn btn-primary btn-xs disc-save-btn mb-1 d-none" data-disc-id="${row['6']}"><i class="fa fa-save"></i> Save</button>
                                        <button type="button" class="btn btn-secondary btn-xs mb-1 disc-cancel-btn d-none"><i class="fa fa-redo"></i> Cancel</button>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        rows = '<tr><td colspan="6" class="text-center">No data available</td></tr>';
                    }
                    $discountTbody.html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }
        loadAnalyseDiscountTable();

        // Discount Edit
        $(document).on('click', '.disc-edit-btn', function() {
            const $row = $(this).closest('tr');

            const analysisName = $row.find('.analysis-name').text().trim();
            const analysisFrom = $row.find('.analysis-from').text().trim();
            const analysisTo = $row.find('.analysis-to').text().trim();
            const analysisPercent = $row.find('.analysis-percent').text().trim();

            //For comparison
            $row.data('original-name', analysisName);
            $row.data('original-from', analysisFrom);
            $row.data('original-to', analysisTo);
            $row.data('original-percent', analysisPercent);

            $row.find('.analysis-name').html(`<input type="text" id="analysis_name" class="form-control form-control-sm" value="${analysisName}">`);

            $row.find('.analysis-from').html(`<input type="number" id="minimum_value" min="<?= $max_disc['max_value'] + 1 ?>" class="form-control form-control-sm num" value="${analysisFrom}" max="9999999999">`);

            $row.find('.analysis-to').html(`<input type="number" id="maximum_value" class="form-control form-control-sm num" min="<?= $max_disc['max_value'] + 2 ?>" value="${analysisTo}" max="9999999999">`);

            $row.find('.analysis-percent').html(`<input type="number" id="percentage" class="form-control form-control-sm perc" value="${analysisPercent}" min="0.01" max="100">`);


            $row.find('.disc-edit-btn, .disc-delete-btn').addClass('d-none');
            $row.find('.disc-save-btn, .disc-cancel-btn').removeClass('d-none');
        });

        //Discount Cancel
        $(document).on('click', '.disc-cancel-btn', function() {
            const $row = $(this).closest('tr');
            const analysisName = $row.find('.analysis-name input').attr('value');
            const analysisFrom = $row.find('.analysis-from input').attr('value');
            const analysisTo = $row.find('.analysis-to input').attr('value');
            const analysisPercent = $row.find('.analysis-percent input').attr('value');

            $row.find('.analysis-name').text(analysisName);
            $row.find('.analysis-from').text(analysisFrom);
            $row.find('.analysis-to').text(analysisTo);
            $row.find('.analysis-percent').text(analysisPercent);

            $row.find('.disc-edit-btn, .disc-delete-btn').removeClass('d-none');
            // $row.find('.activate-btn').removeClass('d-none');
            // $row.find('.inactivate-btn').removeClass('d-none');
            $row.find('.disc-save-btn, .disc-cancel-btn').addClass('d-none');
        });

        //Discount Save
        $(document).on('click', '.disc-save-btn', function() {
            const $row = $(this).closest('tr');
            const priceId = $row.data('price-id');
            const discId = $(this).data('disc-id');

            // console.log($row[0].outerHTML); // View full row HTML
            // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

            const updatedTblData = {
                price_id: priceId,
                disc_id: discId,
                client_acc_id: $clientId,
                analysis_name: $row.find('.analysis-name input').val(),
                analysis_from: $row.find('.analysis-from input').val(),
                analysis_to: $row.find('.analysis-to input').val(),
                analysis_percent: $row.find('.analysis-percent input').val(),
                //description: $row.find('.analysis-desc input').val(),
                // price: $row.find('.analysis-price input').val(),
                // anumber: $row.find('.analysis-number input').val()
            };

            for (const key in updatedTblData) {
                if (updatedTblData[key] === '' || updatedTblData[key] === null) {
                    mug_alert_lite('warning', 'Please fill all fields before saving.');
                    return;
                }
            }

            const originalValues = {
                analysis_name: $row.data('original-name'),
                analysis_from: $row.data('original-from'),
                analysis_to: $row.data('original-to'),
                analysis_percent: $row.data('original-percent')
            };

            let hasChanges = false;
            for (const key in originalValues) {
                if (updatedTblData[key] !== originalValues[key]) {
                    hasChanges = true;
                    break;
                }
            }

            if (!hasChanges) {
                mug_alert_lite('info', 'No changes made.');
                return;
            }

            $.ajax({
                url: "/update_analysis_monthly_discount",
                type: "POST",
                data: updatedTblData,
                success: function(response) {

                    if (response == 0) {
                        mug_alert_lite('error', 'Please fill all fields before saving.');
                        return;
                    }

                    mug_alert_lite('success', 'Updated Successfully.');

                    $row.find('.analysis-name').text(updatedTblData.analysis_name);
                    $row.find('.analysis-from').text(updatedTblData.analysis_from);
                    $row.find('.analysis-to').text(updatedTblData.analysis_to);
                    $row.find('.analysis-percent').text(updatedTblData.analysis_percent);

                    $row.find('.disc-edit-btn, .disc-delete-btn').removeClass('d-none');
                    // $row.find('.activate-btn').removeClass('d-none');
                    // $row.find('.inactivate-btn').removeClass('d-none');
                    $row.find('.disc-save-btn, .disc-cancel-btn').addClass('d-none');


                },
                error: function() {
                    alert("Error saving data.");
                }
            });
        });

        //Discount Delete
        $(document).on('click', '.disc-delete-btn', function() {
            const $row = $(this).closest('tr');
            const priceId = $row.data('price-id');
            const discId = $(this).data('disc-id');

            // console.log($row[0].outerHTML); // View full row HTML
            // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

            const delTblData = {
                price_id: priceId,
                disc_id: discId,
                client_acc_id: $clientId,
            };


            if (confirm("Do you really want to delete this?")) {
                $.ajax({
                    url: "/delete_analysis_monthly_discount",
                    type: "POST",
                    data: delTblData,
                    success: function(response) {
                        mug_alert_lite('success', 'Deleted Successfully.');
                        loadAnalyseDiscountTable();
                    },
                    error: function() {
                        alert("Error saving data.");
                    }
                });
            }
        });

        // $("#add-discount").on("click", function(event) {
        //     if ($("#frmDiscountSec").valid()) {
        //         z++;
        //         event.preventDefault();
        //         var minimum_value = $("#minimum_value").val();
        //         var maximum_value = $("#maximum_value").val();
        //         var percentage = $("#percentage").val();
        //         if (!minimum_value) {
        //             alert('Please Enter Minimum Value');
        //             return false;
        //         }
        //         if (!maximum_value) {
        //             alert('Please Enter Maximum Value');
        //             return false;
        //         }
        //         if (!percentage) {
        //             alert('Please Enter Discount Percentage');
        //             return false;
        //         }
        //         if (parseInt(maximum_value) <= parseInt(minimum_value)) {
        //             alert('Maximum Value Must Be Greater Than Minimum Value');
        //             return false;
        //         }

        //         var markup = `<tr>
        //                                 <td>${z}</td>
        //                                 <td> 
        //                                    <input type="number" id="minimum_value${z}" class="make-disc-editable form-control num min-max min_val" value="${minimum_value}" name="minimum_value[]" required="" max="9999999999">
        //                                 </td>
        //                                 <td> 
        //                                    <input type="number" id="maximum_value${z}" class="make-disc-editable form-control num min-max max_val"  name="maximum_value[]" value="${maximum_value}" required="" max="9999999999">
        //                                 </td>
        //                                 <td>
        //                                     <input type="number" id="percentage${z}" class="make-disc-editable form-control perc" name="percentage[]" min="0.01" max="100" value="${percentage}" required="">
        //                                 </td>     
        //                                 <td>
        //                                    <input class="make-disc-editable discount_select" type="checkbox" id="discount_sel_${z}" name="discount_sel[]">
        //                                    <input type="hidden" class="discount_id" id="discount_id_${z}" name="discount_id[]" value="0">
        //                                 </td>
        //                              </tr>
        //                             `;

        //         $('.make-disc-editable').prop("disabled", false);

        //         $("#edit-discount-range").css('display', 'none');

        //         $("table #discount-tbody").append(markup);

        //         $(".save-discount-range").css('display', 'block');
        //         $("#delete-discount").show();

        //         var new_maxivalue = parseInt(maximum_value) + 1;

        //         $("#minimum_value").val('');
        //         $("#minimum_value").prop('min', new_maxivalue);
        //         $("#maximum_value").val('');
        //         $("#maximum_value").prop('min', (new_maxivalue + 2));
        //         $("#percentage").val('');
        //     }
        // });

        $('#add-discount').on('click', function(e) {
            e.preventDefault();

            var clientId = <?php echo $edit['client_account_id'] ?>;

            var $selectedOption = $('#gt-analysis option:selected');
            var analysisName = $selectedOption.text();
            var analysisValue = $selectedOption.val();

            var dataTableId = $selectedOption.data('table-id');

            var fromValue = $('#minimum_value').val();
            var toValue = $('#maximum_value').val();
            var percentage = $('#percentage').val();

            if (!analysisValue || !fromValue || !toValue || !percentage) {
                mug_alert_lite('error', 'Please fill all fields.');
                return;
            }

            if (dataTableId == "client") {
                mug_alert_lite('warning', 'Discount Already Exists');
                return;
            }

            const discountData = {
                client_account_id: clientId,
                analysname: analysisName,
                analysvalue: analysisValue,
                table_id: dataTableId,
                frm_value: fromValue,
                to_value: toValue,
                percent: percentage
            };

            //////////////////
            $.ajax({
                url: '/add_to_disclist',
                method: 'POST',
                data: discountData,
                dataType: 'json',
                success: function(response) {
                    // var $select = $('#gt-analysis');
                    // $select.empty().append('<option value="">-- Choose Analysis --</option>');
                    // $.each(response, function(index, item) {
                    //     $select.append('<option value="' + item.value + '">' + item.name + '</option>');
                    // });

                    $('#frmDiscountSec')[0].reset();

                    mug_alert_lite('success', 'Added to list.');

                    loadAnalyseDiscountTable();
                },
                error: function() {
                    alert('Failed to add. Please try again.');
                }
            });
            //////////////////

            // var rowCount = $('#discount-tbody tr').length + 1;

            // var newRow = `
            // <tr>
            //     <td>${rowCount}</td>
            //     <td>
            //         <input type="text" class="form-control" name="analysis_name[]" value="${analysisName}">
            //         <input type="hidden" name="analysis[]" value="${analysisValue}">
            //     </td>
            //     <td>
            //         <input type="number" class="form-control" name="from[]" value="${fromValue}" min="0">
            //     </td>
            //     <td>
            //         <input type="number" class="form-control" name="to[]" value="${toValue}" min="0">
            //     </td>
            //     <td>
            //         <input type="number" class="form-control" name="percentage[]" value="${percentage}" min="0.01" max="100" step="0.01">
            //     </td>
            //     <td><button type="button" class="btn btn-danger btn-sm remove-row">Delete</button></td>
            // </tr>
            // `;

            // $('#discount-tbody').append(newRow);

            // $('#gt-analysis').val('');
            // $('#minimum_value').val('');
            // $('#maximum_value').val('');
            // $('#percentage').val('');
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();

            // Re-number rows
            $('#discount-tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        });

        $("#delete-discount").on("click", function() {
            var selectedValues = [];
            var checkedItemsDel = $("#discount-tbody").find('.discount_sel:checked');
            var checkedItems = $("#discount-tbody").find('.discount_select:checked');
            if (checkedItemsDel.length === 0 && checkedItems.length === 0) {
                alert("Please select at least one record to delete.");
                return;
            }
            checkedItems.each(function() {
                $(this).closest("tr").remove();
            });
            checkedItemsDel.each(function() {
                selectedValues.push($(this).next(".discount_id").val());
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
                    url: "/ajaxV2/delete_monthly_discount_details",
                    dataType: "json",
                    success: function() {
                        $("#discount-tbody").load("/ajaxV2/monthly_discount_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {
                            $(".save-discount-range").css('display', 'none');
                            $("#edit-discount-range").css('display', 'block');
                            $("#edit-discount-range").parent(".save-discount-range").show();
                            $("#delete-discount").hide();
                            checkDiscountPriceLength();
                            z = $("#discount-tbody tr").length;
                        });
                    }
                });
            }
            checkDiscountPriceLength();
            z = $("#discount-tbody tr").length;
        });

        $("#edit-discount-range").on("click", function(event) {
            event.preventDefault();
            $('.make-disc-editable').prop("disabled", false);
            $(".save-discount-range").css('display', 'block');
            $("#edit-discount-range").css('display', 'none');
            $("#delete-discount").show();
        });

        //Monthly Discount Tab
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        var y = $("#subscription").find("#subscription-tbody tr").length;

        $("#subscription").on("click", "#add-sub-row", function() {
            y++;
            var count = $("#subscription").find("#count1").val();
            var analysis_id = $("#subscription").find("#analysis1").val();

            if (!analysis_id) {
                alert("Please select analysis");
                return false;
            }

            if (!count) {
                alert("Please enter count");
                return false;
            }

            var jaba = 0;

            $('#subscription #subscription-tbody .sub_analysis_id').each(function() {
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

            var analysis_name = $("#subscription #analysis1 option:selected").html();
            var markup = `
                           <tr>
                               <td>${y}</td>
                               <td style="text-align: left;">${analysis_name}</td>
                               <td> 
                                 <input type="number" id="sub_count${y}" class="form-control" value="${count}" name="sub_count[]" required="" min="0" max="9999999999">
                               </td>
                               <td>
                                 <input type="checkbox" class="subscription_select" id="sub_sel${y}" name="sub_sel[]">
                                 <input type="hidden" class="subscription_content_id" id="subscription_content_id${y}" value="0" name="subscription_content_id[]">
                                 <input type="hidden" class="subscription_ids" id="subscription_ids${y}" value="0" name="subscription_ids[]">    
                                 <input type="hidden" class="sub_analysis_id" id="sub_analysis_id${y}" name="sub_analysis_id[]" value="${analysis_id}">
                              </td>
                           </tr>`;

            $("#subscription #subscription-tbody").append(markup);

            $("#subscription .save-subscription").css('display', 'block');
            $("#subscription #edit-subscription").css('display', 'none');
            $('#subscription .make-subs-editable').prop("disabled", false);
            $("#subscription #delete-subscription").show();

            $("#subscription #analysis1").val('');
            $("#subscription #count1").val('');

            $("#subscription #subscription-tfoot").show();
        });

        $("#subscription").on("click", "#edit-subscription", function(event) {
            event.preventDefault();
            $('#subscription .make-subs-editable').prop("disabled", false);
            $("#subscription .save-subscription").css('display', 'block');
            $("#subscription #edit-subscription").css('display', 'none');
            $("#subscription #delete-subscription").show();
        });


        $("#subscription").on("click", "#delete-subscription", function() {
            var selectedValues = [];
            var checkedItemsDel = $("#subscription").find('#subscription-tbody .subscription_sel:checked');
            var checkedItems = $("#subscription").find('#subscription-tbody .subscription_select:checked');
            if (checkedItemsDel.length === 0 && checkedItems.length === 0) {
                alert("Please select at least one record to delete.");
                return;
            }
            checkedItems.each(function() {
                $(this).closest("tr").remove();
            });
            checkedItemsDel.each(function() {
                selectedValues.push($(this).next(".subscription_content_id").val());
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
                    url: "/ajaxV2/delete_subscription_details",
                    dataType: "json",
                    success: function() {
                        $("#subscription").load("/ajaxV2/subscription_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {
                            $("#subscription .save-subscription").css('display', 'none');
                            $("#subscription #edit-subscription").css('display', 'block');
                            $("#subscription #edit-subscription").parent(".save-subscription").show();
                            $("#subscription #delete-subscription").show();
                            checkSubscriptionLength();
                            z = $("#subscription").find("#subscription-tbody tr").length;
                            enable_subscription();
                        });
                    }
                });
            }
            checkSubscriptionLength();
            z = $("#subscription").find("#subscription-tbody tr").length;
        });


    });

    $("#frmAnalysesPrice").validate({
        submitHandler: function() {
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
                success: function(response) {
                    if (response.success > 0) {
                        $("#analysis-tbody").load("/ajaxV2/analysis_price_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {});
                        $("#subscription").load("/ajaxV2/subscription_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {
                            $("#subscription .save-subscription").css('display', 'none');
                            $("#subscription #edit-subscription").css('display', 'block');
                            $("#subscription #edit-subscription").parent(".save-subscription").show();
                            $("#subscription #delete-subscription").show();
                            checkSubscriptionLength();
                            z = $("#subscription").find("#subscription-tbody tr").length;
                            enable_subscription();
                        });
                        mug_alert_all('success', 'Success', 'Details Saved Successfully.');
                    } else {
                        if (response.msg) {
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
                    $("#submit_add_rate").prop("disabled", false).html('Save');
                },
                error: function(jqXHR, textStatus) {
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


    $("#frmMonthlyDiscount").validate({
        submitHandler: function() {
            $("#dis_submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            let formData = $('#frmMonthlyDiscount').serialize();
            const clientAccountId = $('#client_account_id').val();
            if (clientAccountId) {
                formData += `&id=${encodeURIComponent(clientAccountId)}`; // Manually append data
            }
            $.ajax({
                type: 'POST',
                data: formData,
                url: "/ajaxV2/save_monthly_discount_details",
                dataType: "json",
                timeout: 60000,
                success: function(response) {
                    if (response.success > 0) {
                        $("#discount-tbody").load("/ajaxV2/monthly_discount_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {});
                        mug_alert_all('success', 'Success', 'Details Saved Successfully.');
                    } else {
                        if (response.msg) {
                            mug_alert_all('error', 'Error', response.msg);
                        } else {
                            mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                        }
                    }
                    $('#frmMonthlyDiscount').find(".discount_sel").prop("checked", false);
                    $('.make-disc-editable').prop("disabled", true);
                    $(".save-discount-range").css('display', 'none');
                    $("#edit-discount-range").css('display', 'block');
                    $("#edit-discount-range").parent(".save-discount-range").show();
                    $("#delete-discount").hide();
                    $("#dis_submit").prop("disabled", false).html('Save');
                },
                error: function(jqXHR, textStatus) {
                    $("#dis_submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });

    function checkDiscountPriceLength() {
        var len = $("#discount-tbody tr").length;
        if (len === 0) {
            $(".save-discount-range").css('display', 'none');
            $("#delete-discount").hide();
        }
    }

    $("#discount").on("change", ".min-max", function() {
        var elem = $(this);
        let minInput = $(elem).parents("tr").find(".min_val").val();
        let newval = parseInt(minInput) + 1;
        $(elem).parents("tr").find(".max_val").prop('min', newval);
    });

    $("#minimum_value").on("change", function() {
        var val = $(this).val();
        var newval = parseInt(val) + 2;
        $("#maximum_value").prop('min', newval);
    });

    $("#analysis").on("change", function() {
        var price = $("#analysis option:selected").attr("itemref");
        var code = $("#analysis option:selected").attr("rel");
        var min_time = $("#analysis option:selected").attr("dataref");
        $("#rate").val(price);
        $("#code").val(code);
        $("#min_time").val(min_time);
    });

    enable_subscription();

    function enable_subscription() {
        $("#subscription #frmSubscription").validate({
            submitHandler: function() {
                $("#subscription #submit_subscription").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
                let formData = $("#subscription #frmSubscription").serialize();
                const clientAccountId = $('#client_account_id').val();
                if (clientAccountId) {
                    formData += `&id=${encodeURIComponent(clientAccountId)}`; // Manually append data
                }
                $.ajax({
                    type: 'POST',
                    data: formData,
                    url: "/ajaxV2/save_subscription_details",
                    dataType: "json",
                    timeout: 60000,
                    success: function(response) {
                        if (response.success > 0) {
                            $("#subscription").load("/ajaxV2/subscription_ajax", {
                                clientAccountId: clientAccountId
                            }, function(response, status, xhr) {
                                enable_subscription();
                            });
                            mug_alert_all('success', 'Success', 'Details Saved Successfully.');
                        } else {
                            if (response.msg) {
                                mug_alert_all('error', 'Error', response.msg);
                            } else {
                                mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                            }
                        }
                        $('#subscription #frmSubscription').find(".subscription_sel").prop("checked", false);
                        $('#subscription .make-subs-editable').prop("disabled", true);
                        $("#subscription .save-subscription").css('display', 'none');
                        $("#subscription #edit-subscription").css('display', 'block');
                        $("#subscription #edit-subscription").parent(".save-subscription").show();
                        $("#subscription #delete-subscription").hide();
                        $("#subscription #submit_subscription").prop("disabled", false).html('Save');
                    },
                    error: function(jqXHR, textStatus) {
                        $("#subscription #submit_subscription").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    }
                });
            }
        });
    }

    function checkSubscriptionLength() {
        var len = $("#subscription").find("#subscription-tbody tr").length;
        if (len === 0) {
            $("#subscription .save-subscription").css('display', 'none');
            $("#subscription #delete-subscription").hide();
        }
    }


    $("#frmMaintenanceFee").validate({
        submitHandler: function() {
            $("#submit_maint_fees").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            let formData = $('#frmMaintenanceFee').serialize();
            const clientAccountId = $('#client_account_id').val();
            if (clientAccountId) {
                formData += `&id=${encodeURIComponent(clientAccountId)}`; // Manually append data
            }
            $.ajax({
                type: 'POST',
                data: formData,
                url: "/ajaxV2/save_maintenance_fee_details",
                dataType: "json",
                timeout: 60000,
                success: function(response) {
                    if (response.success > 0) {
                        $("#maintenance-fee-tbody").load("/ajaxV2/maintenance_fee_ajax", {
                            clientAccountId: clientAccountId
                        }, function(response, status, xhr) {});
                        mug_alert_all('success', 'Success', 'Details Saved Successfully.');
                    } else {
                        if (response.msg) {
                            mug_alert_all('error', 'Error', response.msg);
                        } else {
                            mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                        }
                    }
                    $("#maintenance_fee_type").val('');
                    $("#maintenance_fee_amount").val('');
                    $("#submit_maint_fees").prop("disabled", false).html('Set Fee');
                },
                error: function(jqXHR, textStatus) {
                    $("#submit_maint_fees").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
        // var clientId = $('#client_account_id').val();
        var clientId = <?= $edit['client_account_id'] ?>;

        $.ajax({
            url: '/get_analyses',
            method: 'POST',
            data: {
                client_account_id: clientId
            },
            dataType: 'json',
            success: function(response) {
                var $select = $('#gt-analysis');
                $select.empty().append('<option value="">-- Choose Analysis --</option>');
                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '">' + item.name + '</option>');
                });
            },
            error: function() {
                alert('Failed to load analyses. Please try again.');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // const $discountTbody = $('#discount-tbody');
        // const $clientId = <?= $edit['client_account_id'] ?>;

        // function loadAnalyseDiscountTable() {
        //     $.ajax({
        //         url: "/analyses_discount_list",
        //         type: "POST",
        //         // data: { selection: selection, client_id: $clientId },
        //         data: {
        //             client_account_id: $clientId
        //         },
        //         dataType: "json",
        //         success: function(response) {
        //             let rows = '';
        //             if (response.data && response.data.length > 0) {
        //                 $.each(response.data, function(index, row) {
        //                     rows += `
        //                         <tr data-id="${row[5]}">
        //                             <td>${row[0]}</td>
        //                             <td class="analysis-name">${row[1]}</td>
        //                             <td class="analysis-desc">${row[2]}</td>
        //                             <td class="analysis-price">${row[3]}</td>
        //                             <td class="analysis-number">${row[4]}</td>
        //                             <td>
        //                                 <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
        //                                 <button type="button" class="btn btn-success btn-xs save-btn mb-1 d-none" data-price-id="${row['6']}">Save</button>
        //                                 <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
        //                             </td>
        //                         </tr>`;
        //                 });
        //             } else {
        //                 rows = '<tr><td colspan="6" class="text-center">No data available</td></tr>';
        //             }
        //             $discountTbody.html(rows);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("AJAX Error:", status, error);
        //         }
        //     });
        // }
        // loadAnalyseDiscountTable();
    });
</script>