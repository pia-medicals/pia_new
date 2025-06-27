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
            /* max-height: 75vh; */
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

                                    <?php /*
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="input-group">
                                                <input type="password" id="password" required="" class="form-control" name="password" placeholder="Enter New Password" maxlength="15">
                                                <div class="input-group-append">
                                                    <button class="btn btn-light border border-start-0" type="button" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <ul id="password-rules" class="list-unstyled mt-2 mb-0">
                                                <p id="max-length-warning" style="display:none; color:#d9534f; font-weight:bold; margin-top:10px;">
                                                    <i class="fas fa-exclamation-triangle"></i> Maximum 15 characters allowed
                                                </p>
                                                <li id="rule-minlength" class="text-danger"><i class="fas fa-times"></i> <em>At least 4 characters</em></li>
                                                <li id="rule-uppercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one uppercase letter (A–Z)</em></li>
                                                <li id="rule-lowercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one lowercase letter (a–z)</em></li>
                                                <li id="rule-number" class="text-danger"><i class="fas fa-times"></i> <em>At least one number (0–9)</em></li>
                                                <li id="rule-special" class="text-danger"><i class="fas fa-times"></i> <em>At least one special character (!@#$...)</em></li>
                                            </ul>
                                        </div>
                                    </div> */ ?>

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

                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <h5 class="h5 text-bold text-secondary m-0 flex-grow-1 text-center"><?php echo !empty($page_title) ? $page_title : ''; ?></h5>
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
                                    /* include_once 'subscription_ajax.php'; */
                                    include_once 'new_subscription_ajax.php';
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

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="minimum_value">From</label>
                                                    <input type="text" id="minimum_value" min="<?= $max_disc['max_value'] + 1 ?>" class="form-control num" max="9999999999">
                                                </div>
                                            </div>
                                            <?php /*<div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="max">To</label>
                                                    <input type="text" id="maximum_value" class="form-control num" min="<?= $max_disc['max_value'] + 2 ?>" max="9999999999">
                                                </div>
                                            </div> */ ?>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="maximum_value" class="form-label">To</label>
                                                    <div class="input-group">
                                                        <input type="text" id="maximum_value" class="form-control border border-end-0 num" min="<?= $max_disc['max_value'] + 2 ?>" max="9999999999" disabled>
                                                        <div class="input-group-text border border-start-0">
                                                            <input type="checkbox" id="enable_maximum_value" title="Enable 'To' field">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>





                                            <?php /* <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="percentage">Percentage</label>
                                                    <input type="number" id="percentage" class="form-control perc" min="0.01" max="100">
                                                </div>
                                            </div> */ ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="percentage">Price</label>
                                                    <input type="text" id="price" class="form-control num">
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
                                                            <th>Item Number</th>
                                                            <th>Analysis Name</th>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>Price</th>
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

                                    <form method="post" id="frmMaintFeeSec">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="analysis">Analysis</label>
                                                    <select id="maint-analysis" class="form-control first">
                                                        <option value="">-- Choose Analysis --</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ItemNumber">Description</label>
                                                    <input type="text" id="m_desc" class="form-control bg-white">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="ItemNumber">Item Number</label>
                                                    <input type="text" id="mitem_number" class="form-control bg-white num" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="price">List Price</label>
                                                    <input type="text" id="mdefault_price" class="form-control bg-white num" readonly>
                                                </div>
                                            </div>
                                            <?php /* <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="percentage">Percentage</label>
                                                    <input type="number" id="percentage" class="form-control perc" min="0.01" max="100">
                                                </div>
                                            </div> */ ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="monthlyFee">Monthly Fee</label>
                                                    <input type="text" id="monthly_fee" class="form-control num">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="code" style="visibility: hidden; display: block">add</label>
                                                    <button id="add-mFee" class="btn btn-success">Add to List</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <form class="container-fluid" method="post" id="frmMaintenanceFee" name="frmMaintenanceFee">
                                            <div class="col-md-12">
                                                <h3 class="text-center">Maintenance Fees</h3>
                                            </div>
                                            <div class="admin_table mb-5">
                                                <table class="admin">
                                                    <thead>
                                                        <tr>
                                                            <th>Item Number</th>
                                                            <th>Description</th>
                                                            <th>Price</th>
                                                            <th>Monthly Fee</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="maintenance-tbody">
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

                                    <?php /* Old code 
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
                                    Old code */ ?>




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
    $(".num").keypress(function(event) {
        <?php /* Numeric input restriction */ ?>
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });

    <?php /* $(".dnum").keypress(function(event) {
        // Get the current value of the input field
        const currentValue = $(this).val();

        // Allow digits (0-9) and the decimal point (.)
        if (event.which === 46) { // ASCII for '.'
            // Allow only one decimal point
            if (currentValue.includes('.')) {
                event.preventDefault();
            }
        } else if (event.which < 48 || event.which > 57) { // ASCII for 0-9
            event.preventDefault();
        }
    }); */ ?>

    $(".znum").on('input', function() {
        let inputValue = $(this).val();
        <?php /* If the input starts with '0' and is followed by another digit (not a decimal point)
        then remove the leading '0'. */ ?>
        if (inputValue.length > 1 && inputValue.startsWith('0') && !inputValue.startsWith('0.')) {
            $(this).val(parseFloat(inputValue));
        }
    });

    $('#enable_maximum_value').on('change', function() {
        $('#maximum_value').prop('disabled', !this.checked);
        if (!this.checked) {
            $('#maximum_value').val('');
        }
    });

    // $('#enable_maximum_value').on('change', function() {
    //     const $input = $('#maximum_value');

    //     if (this.checked) {
    //         $input.prop('disabled', false)
    //             .removeClass('bg-light')
    //             .addClass('bg-white');
    //     } else {
    //         $input.prop('disabled', true)
    //             .removeClass('bg-white')
    //             .addClass('bg-light')
    //             .val('');
    //     }
    // });



    $(document).ready(function() {

        // $('#togglePassword').on('click', function() {

        //     const passwordField = $('#password');

        //     const passwordFieldType = passwordField.attr('type');

        //     const passwordToggleIcon = $(this).find('i');

        //     if (passwordFieldType === 'password') {
        //         passwordField.attr('type', 'text');
        //         passwordToggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        //     } else {
        //         passwordField.attr('type', 'password');
        //         passwordToggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        //     }
        // });

        // const passwordInput = $('#password');
        const form = $('#myForm');
        const cardBody = $('.card-body');
        // const passwordRules = $('#password-rules');

        // function validatePasswordRules(password) {
        //     let rules = {
        //         minlength: password.length >= 4,
        //         uppercase: /[A-Z]/.test(password),
        //         lowercase: /[a-z]/.test(password),
        //         number: /[0-9]/.test(password),
        //         special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        //     };

        //     $('#password-rules li').removeClass('text-success text-danger bold')
        //         .find('i').removeClass('fa-check fa-times');

        //     for (let rule in rules) {
        //         let $li = $('#rule-' + rule);
        //         if (rules[rule]) {
        //             $li.addClass('text-success');
        //             $li.find('i').addClass('fas fa-check');
        //         } else {
        //             $li.addClass('text-danger bold');
        //             $li.find('i').addClass('fas fa-times');
        //         }
        //     }

        //     return Object.values(rules).every(Boolean);
        // }

        // function resetPasswordRulesUI() {
        //     $('#password-rules li')
        //         .removeClass('text-success bold')
        //         .addClass('text-danger')
        //         .each(function() {
        //             $(this).find('i').removeClass('fa-check').addClass('fa-times');
        //         });

        //     passwordRules.removeClass('show').hide();
        //     cardBody.addClass('card-collapsed');
        // }

        // passwordInput.on('focus', function() {
        //     passwordRules.fadeIn(400, function() {
        //         passwordRules.addClass('show');
        //     });
        //     cardBody.removeClass('card-collapsed');
        // });

        // passwordInput.on('keydown', function(e) {
        //     const val = $(this).val();
        //     const controlKeys = [8, 37, 38, 39, 40, 46];

        //     if (val.length >= 15 && !controlKeys.includes(e.keyCode)) {
        //         $('#max-length-warning').fadeIn(200);
        //     } else {
        //         $('#max-length-warning').fadeOut(200);
        //     }
        // });

        // passwordInput.on('keyup', function() {
        //     const password = $(this).val();
        //     validatePasswordRules(password);

        //     if (password.length < 15) {
        //         $('#max-length-warning').fadeOut(200);
        //     }
        // });

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
                // password: {
                //     required: true
                // },
                is_active: {
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                // if (element.attr("name") == "password") {
                //     error.appendTo(element.closest('.form-group'));
                // } else {
                error.insertAfter(element);
                // }
            },
            submitHandler: function() {
                // const isValid = validatePasswordRules(passwordInput.val());
                // if (!isValid) {
                //     alert('Please ensure the password meets all the listed requirements.');
                //     passwordInput.focus();
                //     return false;
                // }

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
                    // password: $("#password").val(),
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
                    // if (response.success > 0) {
                    if (response.success == 1) {
                        // form[0].reset();
                        // resetPasswordRulesUI();
                        mug_alert_all('success', 'Success', response.msg);
                    } else if (response.success == 2) {
                        mug_alert_all('warning', 'Warning', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', response.msg || 'Something went wrong. Please try again later!!');
                    }
                    $("#submit").prop("disabled", false).html('Save <i class="fa fa-save"></i>');
                },
                error: function() {
                    $("#submit").prop("disabled", false).html('Retry <i class="fas fa-redo"></i>');
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

        /*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Subscription Tab*/
        var tlength = $("#subscription-tbody tr").length;

        const $subscriptionTbody = $('#subscription-tbody');
        const $cId = <?= $edit['client_account_id'] ?>;

        $(document).ready(function() {
            /* console.log('DOM ready - loading table'); */
            loadAnalyseSubscriptionTable();
        });

        function loadAnalyseSubscriptionTable() {
            $.ajax({
                url: "/analyses_subscription_list",
                type: "POST",
                data: {
                    client_account_id: $cId
                },
                dataType: "json",
                success: function(response) {
                    let rows = '';
                    if (response.data && response.data.length > 0) {
                        $.each(response.data, function(index, row) {

                            rows += `
                                <tr data-subid="${row[4]}" data-subctid="${row[6]}" data-acpd="${row[5]}" data-from="fetch">
                                    <td class="subsc-analysis-item">${row[0]}</td>
                                    <td class="subsc-analysis-name">${row[1]}</td>
                                    <td class="subsc-analysis-count">${row[2]}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs mb-1 subsc-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs delete-subscription-btn mb-1"><i class="fas fa-trash-alt"></i> Delete</button>
                                        <button type="button" class="btn btn-primary btn-xs subsc-save-btn mb-1 d-none"><i class="fa fa-save"></i> Save</button>
                                        <button type="button" class="btn btn-secondary btn-xs mb-1 subsc-cancel-btn d-none">Cancel</button>
                                    </td>
                                </tr>`;
                            document.getElementById('new_sub_amount').value = row[3];


                        });
                    } else {
                        rows = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                    }

                    $subscriptionTbody.html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }

            });

        }
        loadAnalyseSubscriptionTable();

        $('#add-subscription').on('click', function(e) {
            $("#add-subscription").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            e.preventDefault();

            var clientId = <?php echo $edit['client_account_id'] ?>;

            var clientsubId = <?php echo $client_sub_id ?>;

            var $selalOption = $('#gt-subanalysis option:selected');

            var subanalysisName = $selalOption.text();
            var subanalysisValue = $selalOption.val();

            var dTableId = $selalOption.data('table-id');
            var dataaPrice = $selalOption.data('analysesprice');
            var dataadesc = $selalOption.data('analysesdesc');

            var subitemNumber = $('#sub_item_number').val();
            var subCount = $('#new_sub_count').val();

            const subscriptionTbody = document.getElementById('subscription-tbody');

            if (!subanalysisValue || !subitemNumber || !subCount) {
                mug_alert_lite('error', 'Please fill all fields.');
                $("#add-subscription").prop("disabled", false).html('Add to List');
                return;
            }

            let alreadyInTable = false;

            if (subanalysisValue && subitemNumber && subCount) {

                if (dTableId === "client") {
                    $('#subscription-tbody tr').each(function() {
                        if ($(this).data('acpd') == subanalysisValue) {
                            alreadyInTable = true;
                            return false;
                        }
                    });
                }

                if (alreadyInTable) {
                    mug_alert_lite('warning', 'This analysis is already added to the list.');
                    $("#add-subscription").prop("disabled", false).html('Add to List');
                    return;
                }

                if (!alreadyInTable) {

                    const subscriptionData = {
                        client_account_id: clientId,
                        client_sub_id: clientsubId,
                        subanalysname: subanalysisName,
                        subanalysvalue: subanalysisValue,
                        dtable_id: dTableId,
                        data_aprice: dataaPrice,
                        data_adesc: dataadesc,
                        data_anumber: subitemNumber,
                        data_scount: subCount
                    };

                    $.ajax({
                        url: '/add_new_subscriptions',
                        type: 'POST',
                        data: subscriptionData,
                        dataType: 'json',
                        success: function(response) {
                            $('#frmSubscriptionSec')[0].reset();
                            mug_alert_lite('success', 'Added to list.');
                            loadAnalyseSubscriptionTable();

                            $("#add-subscription").prop("disabled", false).html('Add to List');

                            /* For reloading the analyses list */
                            $.ajax({
                                url: '/get_analyses_for_subscription',
                                method: 'POST',
                                data: {
                                    client_account_id: clientId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    var $select = $('#gt-subanalysis');
                                    $select.empty().append('<option value="">-- Choose Analysis --</option>');
                                    $.each(response, function(index, item) {
                                        $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.anumber + '" data-analysesprice="' + item.price + '" data-analysesdesc="' + item.description + '">' + item.name + '</option>');
                                    });
                                },
                                error: function() {
                                    alert('Failed to load analyses. Please try again.');
                                }
                            });

                            $('#gt-subanalysis').on('change', function() {
                                var selectedOption = $(this).find('option:selected');
                                /*var price = selectedOption.data('price');
                                $('#sub_price').val(price);*/
                                var anumber = selectedOption.data('itemnumber');
                                $('#sub_item_number').val(anumber);
                            });


                        },
                        error: function() {
                            alert('Failed to add. Please try again.');
                            $("#add-subscription").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                        }
                    });
                }

            }
        });


        $(document).on('click', '.save-added-subscription', function(e) {
            $("#save-added-subscription").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            e.preventDefault();

            const subAmount = $('#new_sub_amount').val().trim();


            const realRows = $('#subscription-tbody tr').filter(function() {
                return !$(this).text().includes('No data available');
            });

            if (realRows.length === 0) {
                mug_alert_lite('error', 'You cannot set a subscription amount without selecting any analysis.');
                $("#save-added-subscription").prop("disabled", false).html('Save <i class="fa fa-save"></i>');
                return;
            }

            if (!subAmount) {
                mug_alert_lite('error', 'Please enter a subscription amount.');
                $("#save-added-subscription").prop("disabled", false).html('Save <i class="fa fa-save"></i>');
                return;
            }


            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to update the subscription amount?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Yes",
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        url: '/save_added_subscriptions',
                        type: 'POST',
                        data: {
                            clientid: <?php echo $edit['client_account_id'] ?>,
                            subscription_amount: subAmount,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                mug_alert_lite('success', 'Subscription amount saved successfully!');
                                $("#save-added-subscription").prop("disabled", false).html('Save <i class="fa fa-save"></i>');
                            } else {
                                mug_alert_lite('error', response.message || 'Error saving subscriptions.');
                                $("#save-added-subscription").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            mug_alert_lite('error', 'AJAX error occurred.');
                            $("#save-added-subscription").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                        }
                    });
                }
            });
        });



        /* Subscription Edit */
        $(document).on('click', '.subsc-edit-btn', function() {
            const $row = $(this).closest('tr');

            const analysisName = $row.find('.subsc-analysis-name').text().trim();
            const analysisCount = $row.find('.subsc-analysis-count').text().trim();


            //For comparison
            $row.data('original-name', analysisName);
            $row.data('original-count', analysisCount);

            $row.find('.subsc-analysis-name').html(`<input type="text" id="sub_analysis_name" class="form-control form-control-sm" value="${analysisName}">`);
            $row.find('.subsc-analysis-count').html(`<input type="text" id="sub_analysis_count" class="form-control form-control-sm num" value="${analysisCount}">`);


            $row.find('.subsc-edit-btn, .delete-subscription-btn').addClass('d-none');
            $row.find('.subsc-save-btn, .subsc-cancel-btn').removeClass('d-none');
        });

        /* Subscription Cancel */
        $(document).on('click', '.subsc-cancel-btn', function() {
            const $row = $(this).closest('tr');
            const analysisName = $row.find('.subsc-analysis-name input').attr('value');
            const analysisCount = $row.find('.subsc-analysis-count input').attr('value');

            $row.find('.subsc-analysis-name').text(analysisName);
            $row.find('.subsc-analysis-count').text(analysisCount);

            $row.find('.subsc-edit-btn, .delete-subscription-btn').removeClass('d-none');
            $row.find('.subsc-save-btn, .subsc-cancel-btn').addClass('d-none');
        });

        /* Subscription Edit Save */
        $(document).on('click', '.subsc-save-btn', function() {
            $(".subsc-save-btn").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            $(".subsc-cancel-btn").prop("disabled", true);
            const $row = $(this).closest('tr');
            const acpdId = $row.data('acpd');
            const subId = $row.data('subid');
            const subctId = $row.data('subctid');

            const updatesTblData = {
                client_acc_id: $clientId,
                acpd_id: acpdId,
                sub_id: subId,
                subct_id: subctId,
                subanalysis_name: $row.find('.subsc-analysis-name input').val(),
                subanalysis_count: $row.find('.subsc-analysis-count input').val(),
            };

            for (const key in updatesTblData) {
                if (updatesTblData[key] === '' || updatesTblData[key] === null) {
                    mug_alert_lite('warning', 'Please fill all fields before saving.');
                    $(".subsc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                    $(".subsc-cancel-btn").prop("disabled", false);
                    return;
                }
            }


            const originalValues = {
                subanalysis_name: $row.data('original-name'),
                subanalysis_count: $row.data('original-count'),
            };

            let hasChanges = false;
            for (const key in originalValues) {
                if (updatesTblData[key] !== originalValues[key]) {
                    hasChanges = true;
                    break;
                }
            }

            if (!hasChanges) {
                mug_alert_lite('info', 'No changes made.');
                $(".subsc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                $(".subsc-cancel-btn").prop("disabled", false);
                return;
            }

            $.ajax({
                url: "/update_analysis_subscription",
                type: "POST",
                data: updatesTblData,
                success: function(response) {

                    mug_alert_lite('success', 'Updated Successfully.');

                    $row.find('.subsc-analysis-name').text(updatesTblData.subanalysis_name);
                    $row.find('.subsc-analysis-count').text(updatesTblData.subanalysis_count);

                    $row.find('.subsc-edit-btn, .delete-subscription-btn').removeClass('d-none');
                    $row.find('.subsc-save-btn, .subsc-cancel-btn').addClass('d-none');
                    $(".subsc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                    $(".subsc-cancel-btn").prop("disabled", false);

                },
                error: function() {
                    alert("Error saving data.");
                    $(".subsc-save-btn").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    $(".subsc-cancel-btn").prop("disabled", false);
                }
            });
        });

        /*Subscription Delete*/
        $(document).on('click', '.delete-subscription-btn', function() {
            const $row = $(this).closest('tr');
            const acpdId = $row.data('acpd');
            const subId = $row.data('subid');
            const subctId = $row.data('subctid');



            const delTblData = {
                acpd_id: acpdId,
                sub_id: subId,
                subctId: subctId
            };

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete the Subscription for this Analysis?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_analysis_subscription",
                        type: "POST",
                        data: delTblData,
                        success: function(response) {
                            mug_alert_lite('success', 'Deleted Successfully.');
                            loadAnalyseSubscriptionTable();
                        },
                        error: function() {
                            alert("Error saving data.");
                        }
                    });
                }
            });

        });

        // $(document).on('click', '.save-subscriptions', function() {
        //     const $row = $(this).closest('tr');
        //     const priceId = $row.data('price-id');
        //     const discId = $(this).data('disc-id');



        //     const updatedTblData = {
        //         price_id: priceId,
        //         disc_id: discId,
        //         client_acc_id: $clientId,
        //         analysis_name: $row.find('.analysis-name input').val(),
        //         analysis_from: $row.find('.analysis-from input').val(),
        //         analysis_to: $row.find('.analysis-to input').val(),
        //         analysis_price: $row.find('.analysis-price input').val(),
        //     };

        //     for (const key in updatedTblData) {
        //         if (updatedTblData[key] === '' || updatedTblData[key] === null) {
        //             mug_alert_lite('warning', 'Please fill all fields before saving.');
        //             return;
        //         }
        //     }

        //     const originalValues = {
        //         analysis_name: $row.data('original-name'),
        //         analysis_from: $row.data('original-from'),
        //         analysis_to: $row.data('original-to'),
        //         analysis_price: $row.data('original-price')
        //     };

        //     let hasChanges = false;
        //     for (const key in originalValues) {
        //         if (updatedTblData[key] !== originalValues[key]) {
        //             hasChanges = true;
        //             break;
        //         }
        //     }

        //     if (!hasChanges) {
        //         mug_alert_lite('info', 'No changes made.');
        //         return;
        //     }

        //     $.ajax({
        //         url: "/update_analysis_monthly_discount",
        //         type: "POST",
        //         data: updatedTblData,
        //         success: function(response) {

        //             if (response == 0) {
        //                 mug_alert_lite('error', 'Please fill all fields before saving.');
        //                 return;
        //             }

        //             mug_alert_lite('success', 'Updated Successfully.');

        //             $row.find('.analysis-name').text(updatedTblData.analysis_name);
        //             $row.find('.analysis-from').text(updatedTblData.analysis_from);
        //             $row.find('.analysis-to').text(updatedTblData.analysis_to);
        //             $row.find('.analysis-price').text(updatedTblData.analysis_price);

        //             $row.find('.disc-edit-btn, .disc-delete-btn').removeClass('d-none');
        //             $row.find('.disc-save-btn, .disc-cancel-btn').addClass('d-none');


        //         },
        //         error: function() {
        //             alert("Error saving data.");
        //         }
        //     });
        // });

        /*/Subscription Tab
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/



        /*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Monthly Discount Tab*/
        var z = $("#discount-tbody tr").length;


        const $discountTbody = $('#discount-tbody');
        const $clientId = <?= $edit['client_account_id'] ?>;

        function loadAnalyseDiscountTable() {
            $.ajax({
                url: "/analyses_discount_list",
                type: "POST",
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
                                    <td>${row[7]}</td>
                                    <td class="analysis-name">${row[1]}</td>
                                    <td class="analysis-from">${row[2]}</td>
                                    <td class="analysis-to">${(row[3] === null) ? "75+" : row[3]}</td>
                                    <td class="analysis-price">${row[4]}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs mb-1 disc-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs disc-delete-btn mb-1" data-disc-id="${row['6']}"><i class="fas fa-trash-alt"></i> Delete</button>
                                        <button type="button" class="btn btn-primary btn-xs disc-save-btn mb-1 d-none" data-disc-id="${row['6']}"><i class="fa fa-save"></i> Save</button>
                                        <button type="button" class="btn btn-secondary btn-xs mb-1 disc-cancel-btn d-none">Cancel</button>
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

        /* Discount Edit */
        $(document).on('click', '.disc-edit-btn', function() {
            const $row = $(this).closest('tr');

            const analysisName = $row.find('.analysis-name').text().trim();
            const analysisFrom = $row.find('.analysis-from').text().trim();
            const analysisTo = $row.find('.analysis-to').text().trim();
            const analysisPrice = $row.find('.analysis-price').text().trim();

            /*For comparison*/
            $row.data('original-name', analysisName);
            $row.data('original-from', analysisFrom);
            //$row.data('original-to', analysisTo);
            $row.data('original-to', (analysisTo === "75+") ? "" : analysisTo);
            $row.data('original-price', analysisPrice);

            $row.find('.analysis-name').html(`<input type="text" id="analysis_name" class="form-control form-control-sm" value="${analysisName}">`);

            $row.find('.analysis-from').html(`<input type="text" id="minimum_value" min="<?= $max_disc['max_value'] + 1 ?>" class="form-control form-control-sm num" value="${analysisFrom}" max="9999999999">`);

            $row.find('.analysis-to').html(`<input type="text" id="maximum_value" class="form-control form-control-sm num" min="<?= $max_disc['max_value'] + 2 ?>" value="${(analysisTo === "75+") ? "" : analysisTo}" max="9999999999">`);

            $row.find('.analysis-price').html(`<input type="text" id="price" class="form-control form-control-sm num" value="${analysisPrice}">`);


            $row.find('.disc-edit-btn, .disc-delete-btn').addClass('d-none');
            $row.find('.disc-save-btn, .disc-cancel-btn').removeClass('d-none');
        });

        /*Discount Cancel*/
        $(document).on('click', '.disc-cancel-btn', function() {
            const $row = $(this).closest('tr');
            const analysisName = $row.find('.analysis-name input').attr('value');
            const analysisFrom = $row.find('.analysis-from input').attr('value');
            const analysisTo = $row.find('.analysis-to input').attr('value');
            const analysisPrice = $row.find('.analysis-price input').attr('value');

            $row.find('.analysis-name').text(analysisName);
            $row.find('.analysis-from').text(analysisFrom);
            $row.find('.analysis-to').text(analysisTo === "" ? "75+" : analysisTo);
            $row.find('.analysis-price').text(analysisPrice);

            $row.find('.disc-edit-btn, .disc-delete-btn').removeClass('d-none');
            $row.find('.disc-save-btn, .disc-cancel-btn').addClass('d-none');
        });

        /*Discount Save*/
        $(document).on('click', '.disc-save-btn', function() {
            $(".disc-save-btn").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            $(".disc-cancel-btn").prop("disabled", true);

            const $row = $(this).closest('tr');
            const priceId = $row.data('price-id');
            const discId = $(this).data('disc-id');


            const updatedTblData = {
                price_id: priceId,
                disc_id: discId,
                client_acc_id: $clientId,
                analysis_name: $row.find('.analysis-name input').val(),
                analysis_from: $row.find('.analysis-from input').val(),
                analysis_to: $row.find('.analysis-to input').val(),
                analysis_price: $row.find('.analysis-price input').val(),
            };

            for (const key in updatedTblData) {
                if (key !== 'analysis_to' && (updatedTblData[key] === '' || updatedTblData[key] === null)) {
                    mug_alert_lite('warning', 'Please fill all fields before saving.');
                    $(".disc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                    $(".disc-cancel-btn").prop("disabled", false);
                    return;
                }
            }

            const originalValues = {
                analysis_name: $row.data('original-name'),
                analysis_from: $row.data('original-from'),
                analysis_to: $row.data('original-to'),
                analysis_price: $row.data('original-price')
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
                $(".disc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                $(".disc-cancel-btn").prop("disabled", false);
                return;
            }

            // Normalize 'analysis_to'
            let rawToValue = updatedTblData.analysis_to.trim();
            let toValue = rawToValue === '' ? '75+' : rawToValue;
            let alreadyExists = false;

            $('#discount-tbody tr').each(function() {
                const $eachRow = $(this);

                // Skip the current row being edited (match by disc-id or price-id)
                if (
                    $eachRow.data('price-id') == priceId &&
                    $eachRow.find('.disc-save-btn').data('disc-id') == discId
                ) {
                    return; // Skip comparing with itself
                }

                const rowPriceId = $eachRow.data('price-id');
                const rowFrom = $eachRow.find('.analysis-from').text().trim();
                const rowTo = $eachRow.find('.analysis-to').text().trim();

                if (
                    rowPriceId == priceId &&
                    rowFrom === updatedTblData.analysis_from &&
                    rowTo === toValue
                ) {
                    alreadyExists = true;
                    return false; // Break the loop
                }
            });

            if (alreadyExists) {
                mug_alert_lite('warning', 'Discount for this analysis with the same "From" and "To" range already exists.');
                $(".disc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                $(".disc-cancel-btn").prop("disabled", false);
                return;
            }


            $.ajax({
                url: "/update_analysis_monthly_discount",
                type: "POST",
                data: updatedTblData,
                success: function(response) {

                    if (response == 0) {
                        mug_alert_lite('error', 'Please fill all fields before saving.');
                        $(".disc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                        $(".disc-cancel-btn").prop("disabled", false);
                        return;
                    }

                    mug_alert_lite('success', 'Updated Successfully.');

                    $row.find('.analysis-name').text(updatedTblData.analysis_name);
                    $row.find('.analysis-from').text(updatedTblData.analysis_from);
                    $row.find('.analysis-to').text(updatedTblData.analysis_to === "" ? "75+" : updatedTblData.analysis_to);
                    $row.find('.analysis-price').text(updatedTblData.analysis_price);

                    $row.find('.disc-edit-btn, .disc-delete-btn').removeClass('d-none');
                    $row.find('.disc-save-btn, .disc-cancel-btn').addClass('d-none');

                    $(".disc-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                    $(".disc-cancel-btn").prop("disabled", false);


                },
                error: function() {
                    alert("Error saving data.");
                    $(".disc-save-btn").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    $(".disc-cancel-btn").prop("disabled", false);
                }
            });
        });

        /*Discount Delete*/
        $(document).on('click', '.disc-delete-btn', function() {
            const $row = $(this).closest('tr');
            const priceId = $row.data('price-id');
            const discId = $(this).data('disc-id');


            const delTblData = {
                price_id: priceId,
                disc_id: discId,
                client_acc_id: $clientId,
            };


            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Discount?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
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

        });

        //////Old One///////////////////////
        // $('#add-discount').on('click', function(e) {
        //     e.preventDefault();

        //     var clientId = <?php echo $edit['client_account_id'] ?>;

        //     var $selectedOption = $('#gt-analysis option:selected');
        //     var analysisName = $selectedOption.text();
        //     var analysisValue = $selectedOption.val();

        //     var dataTableId = $selectedOption.data('table-id');

        //     var fromValue = $('#minimum_value').val();
        //     var toValue = $('#maximum_value').val();
        //     /*var percentage = $('#percentage').val();*/
        //     var price = $('#price').val();

        //     if (!analysisValue || !fromValue || !toValue || !price) {
        //         mug_alert_lite('error', 'Please fill all fields.');
        //         return;
        //     }


        //     let alreadyIndTable = false;

        //     if (dataTableId === "client") {
        //         $('#discount-tbody tr').each(function() {
        //             if ($(this).data('price-id') == analysisValue) {
        //                 alreadyIndTable = true;
        //                 return false;
        //             }
        //         });
        //     }

        //     if (alreadyIndTable) {
        //         mug_alert_lite('warning', 'Discount Already Exists.');
        //         return;
        //     }


        //     if (!alreadyIndTable) {
        //         const discountData = {
        //             client_account_id: clientId,
        //             analysname: analysisName,
        //             analysvalue: analysisValue,
        //             table_id: dataTableId,
        //             frm_value: fromValue,
        //             to_value: toValue,
        //             /* percent: percentage */
        //             pricee: price
        //         };

        //         /*////////////////*/
        //         $.ajax({
        //             url: '/add_to_disclist',
        //             method: 'POST',
        //             data: discountData,
        //             dataType: 'json',
        //             success: function(response) {
        //                 /* var $select = $('#gt-analysis');
        //                 $select.empty().append('<option value="">-- Choose Analysis --</option>');
        //                 $.each(response, function(index, item) {
        //                     $select.append('<option value="' + item.value + '">' + item.name + '</option>');
        //                 }); */

        //                 $('#frmDiscountSec')[0].reset();

        //                 mug_alert_lite('success', 'Added to list.');

        //                 loadAnalyseDiscountTable();

        //                 $.ajax({
        //                     url: '/get_analyses',
        //                     method: 'POST',
        //                     data: {
        //                         client_account_id: clientId
        //                     },
        //                     dataType: 'json',
        //                     success: function(response) {
        //                         var $select = $('#gt-analysis');
        //                         $select.empty().append('<option value="">-- Choose Analysis --</option>');
        //                         $.each(response, function(index, item) {
        //                             $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-price="' + item.price + '">' + item.name + '</option>');
        //                         });
        //                     },
        //                     error: function() {
        //                         alert('Failed to load analyses. Please try again.');
        //                     }
        //                 });

        //                 $('#gt-analysis').on('change', function() {
        //                     var selectedOption = $(this).find('option:selected');
        //                     var price = selectedOption.data('price');
        //                     $('#price').val(price);
        //                 });
        //             },
        //             error: function() {
        //                 alert('Failed to add. Please try again.');
        //             }
        //         });
        //         /*////////////////*/
        //     }

        //     /* var rowCount = $('#discount-tbody tr').length + 1;

        //     var newRow = `
        //     <tr>
        //         <td>${rowCount}</td>
        //         <td>
        //             <input type="text" class="form-control" name="analysis_name[]" value="${analysisName}">
        //             <input type="hidden" name="analysis[]" value="${analysisValue}">
        //         </td>
        //         <td>
        //             <input type="number" class="form-control" name="from[]" value="${fromValue}" min="0">
        //         </td>
        //         <td>
        //             <input type="number" class="form-control" name="to[]" value="${toValue}" min="0">
        //         </td>
        //         <td>
        //             <input type="number" class="form-control" name="percentage[]" value="${percentage}" min="0.01" max="100" step="0.01">
        //         </td>
        //         <td><button type="button" class="btn btn-danger btn-sm remove-row">Delete</button></td>
        //     </tr>
        //     `;

        //     $('#discount-tbody').append(newRow);

        //     $('#gt-analysis').val('');
        //     $('#minimum_value').val('');
        //     $('#maximum_value').val('');
        //     $('#percentage').val(''); */
        // });
        //////Old One///////////////////////

        //Prev code
        // $('#add-discount').on('click', function(e) {
        //     $("#add-discount").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
        //     e.preventDefault();

        //     var clientId = <?php echo $edit['client_account_id'] ?>;

        //     var $selectedOption = $('#gt-analysis option:selected');
        //     var analysisName = $selectedOption.text();
        //     var analysisValue = $selectedOption.val();

        //     var dataTableId = $selectedOption.data('table-id');
        //     var aNumber = $selectedOption.data('itemnumber');

        //     var fromValue = parseInt($('#minimum_value').val());
        //     var toValue = parseInt($('#maximum_value').val());
        //     var price = $('#price').val();

        //     if (!analysisValue || !fromValue || !toValue || !price) {
        //         mug_alert_lite('error', 'Please fill all fields.');
        //         $("#add-discount").prop("disabled", false).html('Add to List');
        //         return;
        //     }


        //     if (fromValue > toValue) {
        //         mug_alert_lite('error', 'The "From" value cannot be greater than the "To" value.');
        //         $("#add-discount").prop("disabled", false).html('Add to List');
        //         return;
        //     }

        //     let alreadyExists = false;

        //     if (dataTableId === "client") {
        //         $('#discount-tbody tr').each(function() {
        //             var $row = $(this);
        //             var rowPriceId = $row.data('price-id');
        //             var rowAnalysisFrom = parseInt($row.find('.analysis-from').text());
        //             var rowAnalysisTo = parseInt($row.find('.analysis-to').text());

        //             if (rowPriceId == analysisValue && rowAnalysisFrom === fromValue && rowAnalysisTo === toValue) {
        //                 alreadyExists = true;
        //                 return false;
        //             }
        //         });
        //     }

        //     if (alreadyExists) {
        //         mug_alert_lite('warning', 'Discount for this analysis with the same "From" and "To" range already exists.');
        //         $("#add-discount").prop("disabled", false).html('Add to List');
        //         return;
        //     }


        //     const discountData = {
        //         client_account_id: clientId,
        //         analysname: analysisName,
        //         analysvalue: analysisValue,
        //         table_id: dataTableId,
        //         itm_number: aNumber,
        //         frm_value: fromValue,
        //         to_value: toValue,
        //         pricee: price
        //     };

        //     $.ajax({
        //         url: '/add_to_disclist',
        //         method: 'POST',
        //         data: discountData,
        //         dataType: 'json',
        //         success: function(response) {
        //             $('#frmDiscountSec')[0].reset();
        //             mug_alert_lite('success', 'Added to list.');
        //             loadAnalyseDiscountTable();
        //             $("#add-discount").prop("disabled", false).html('Add to List');
        //             $.ajax({
        //                 url: '/get_analyses',
        //                 method: 'POST',
        //                 data: {
        //                     client_account_id: clientId
        //                 },
        //                 dataType: 'json',
        //                 success: function(response) {
        //                     var $select = $('#gt-analysis');
        //                     $select.empty().append('<option value="">-- Choose Analysis --</option>');
        //                     $.each(response, function(index, item) {
        //                         $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.number + '" data-price="' + item.price + '">' + item.name + '</option>');
        //                     });
        //                 },
        //                 error: function() {
        //                     alert('Failed to load analyses. Please try again.');
        //                 }
        //             });

        //             $('#gt-analysis').on('change', function() {
        //                 var selectedOption = $(this).find('option:selected');
        //                 var price = selectedOption.data('price');
        //                 $('#price').val(price);
        //             });
        //         },
        //         error: function() {
        //             alert('Failed to add. Please try again.');
        //             $("#add-discount").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
        //         }
        //     });
        // });
        //Prev code

        $('#add-discount').on('click', function(e) {
            e.preventDefault();
            $("#add-discount").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

            var clientId = <?php echo $edit['client_account_id']; ?>;
            var $selectedOption = $('#gt-analysis option:selected');
            var analysisName = $selectedOption.text();
            var analysisValue = $selectedOption.val();
            var dataTableId = $selectedOption.data('table-id');
            var aNumber = $selectedOption.data('itemnumber');
            var fromValue = parseInt($('#minimum_value').val());
            var toValue = parseInt($('#maximum_value').val());
            var price = $('#price').val();

            // if (!analysisValue || !fromValue || !price) {
            //     mug_alert_lite('error', 'Please fill all fields.');
            //     $("#add-discount").prop("disabled", false).html('Add to List');
            //     return;
            // }

            var isToValueEnabled = $('#enable_maximum_value').is(':checked');
            var toValueRaw = $('#maximum_value').val().trim();
            var toValue = isToValueEnabled ? toValueRaw : '75+';

            if (!analysisValue || !fromValue || !price || (isToValueEnabled && toValueRaw === '')) {
                mug_alert_lite('error', 'Please fill all fields.');
                $("#add-discount").prop("disabled", false).html('Add to List');
                return;
            }


            if (fromValue > toValue) {
                mug_alert_lite('error', 'The "From" value cannot be greater than the "To" value.');
                $("#add-discount").prop("disabled", false).html('Add to List');
                return;
            }

            let alreadyExists = false;

            if (dataTableId === "client") {
                let rawToValue = $('#maximum_value').val().trim();
                let toValue = rawToValue === '' ? '75+' : rawToValue;

                $('#discount-tbody tr').each(function() {
                    var $row = $(this);
                    var rowPriceId = $row.data('price-id');
                    var rowAnalysisFrom = $row.find('.analysis-from').text().trim();
                    var rowAnalysisTo = $row.find('.analysis-to').text().trim();

                    if (
                        rowPriceId == analysisValue &&
                        rowAnalysisFrom === String(fromValue) &&
                        rowAnalysisTo === toValue
                    ) {
                        alreadyExists = true;
                        return false;
                    }
                });
            }

            if (alreadyExists) {
                mug_alert_lite('warning', 'Discount for this analysis with the same "From" and "To" range already exists.');
                $("#add-discount").prop("disabled", false).html('Add to List');
                return;
            }


            const discountData = {
                client_account_id: clientId,
                analysname: analysisName,
                analysvalue: analysisValue,
                table_id: dataTableId,
                itm_number: aNumber,
                frm_value: fromValue,
                to_value: parseInt($('#maximum_value').val()),
                pricee: price
            };

            $.ajax({
                url: '/add_to_disclist',
                method: 'POST',
                data: discountData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#frmDiscountSec')[0].reset();
                        mug_alert_lite('success', response.message || 'Added to list.');
                        loadAnalyseDiscountTable();
                        $("#add-discount").prop("disabled", false).html('Add to List');

                        $.ajax({
                            url: '/get_analyses',
                            method: 'POST',
                            data: {
                                client_account_id: clientId
                            },
                            dataType: 'json',
                            success: function(res) {
                                var $select = $('#gt-analysis');
                                $select.empty().append('<option value="">-- Choose Analysis --</option>');
                                $.each(res, function(index, item) {
                                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.number + '" data-price="' + item.price + '">' + item.name + '</option>');
                                });
                            },
                            error: function() {
                                alert('Failed to load analyses. Please try again.');
                            }
                        });

                        $('#gt-analysis').on('change', function() {
                            var selectedOption = $(this).find('option:selected');
                            var price = selectedOption.data('price');
                            $('#price').val(price);
                        });

                    } else {
                        mug_alert_lite('error', response.message || 'Something went wrong.');
                        $("#add-discount").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    mug_alert_lite('error', 'AJAX request failed. Please try again.');
                    $("#add-discount").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        });


        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();

            /* Re-number rows */
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

        /*Monthly Discount Tab
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

        /*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Maintenance Fee Tab*/
        var maintlength = $("#maintenance-tbody tr").length;

        const $maintenanceTbody = $('#maintenance-tbody');
        const $mcId = <?= $edit['client_account_id'] ?>;

        // $(document).ready(function() {
        //     /* console.log('DOM ready - loading table'); */
        //     loadAnalyseMaintenanceTable();
        // });

        function loadAnalyseMaintenanceTable() {
            $.ajax({
                url: "/analyses_maintenancefee_list",
                type: "POST",
                data: {
                    client_account_id: $mcId
                },
                dataType: "json",
                success: function(response) {
                    let rows = '';
                    if (response.data && response.data.length > 0) {
                        $.each(response.data, function(index, row) {

                            // rows += `
                            //     <tr data-subid="${row[4]}" data-subctid="${row[6]}" data-acpd="${row[5]}" data-from="fetch">
                            //         <td class="subsc-analysis-item">${row[0]}</td>
                            //         <td class="subsc-analysis-name">${row[1]}</td>
                            //         <td class="subsc-analysis-count">${row[2]}</td>
                            //         <td>
                            //             <button type="button" class="btn btn-info btn-xs mb-1 subsc-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            //             <button type="button" class="btn btn-danger btn-xs delete-subscription-btn mb-1"><i class="fas fa-trash-alt"></i> Delete</button>
                            //             <button type="button" class="btn btn-primary btn-xs subsc-save-btn mb-1 d-none"><i class="fa fa-save"></i> Save</button>
                            //             <button type="button" class="btn btn-secondary btn-xs mb-1 subsc-cancel-btn d-none">Cancel</button>
                            //         </td>
                            //     </tr>`;

                            // rows += `
                            //     <tr data-apriceid="${row[4]}" data-mainfeeid="${row[5]}">
                            //         <td class="mainfee-analysis-item">${row[0]}</td>
                            //         <td class="mainfee-analysis-name">${row[1]}</td>
                            //         <td class="mainfee-analysis-price">${row[2]}</td>
                            //         <td class="mainfee-analysis-mfee">${row[3]}</td>
                            //         <td>
                            //             <button type="button" class="btn btn-info btn-xs mb-1 mainfee-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                            //             <button type="button" class="btn btn-danger btn-xs mainfee-delete-btn mb-1"><i class="fas fa-trash-alt"></i> Delete</button>
                            //             <button type="button" class="btn btn-primary btn-xs mainfee-save-btn mb-1 d-none"><i class="fa fa-save"></i> Save</button>
                            //             <button type="button" class="btn btn-secondary btn-xs mb-1 mainfee-cancel-btn d-none">Cancel</button>
                            //         </td>
                            //     </tr>`;

                            rows += `
                                <tr data-apriceid="${row[4]}" data-mainfeeid="${row[5]}">
                                    <td class="mainfee-analysis-item">${row[0]}</td>
                                    <td class="mainfee-analysis-desc">${(row[6] === null || row[6] === '' || row[6] === '0') ? row[1] : row[6]}</td>
                                    <td class="mainfee-analysis-price">${row[2]}</td>
                                    <td class="mainfee-analysis-mfee">${row[3]}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs mb-1 mainfee-edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs mainfee-delete-btn mb-1"><i class="fas fa-trash-alt"></i> Delete</button>
                                        <button type="button" class="btn btn-primary btn-xs mainfee-save-btn mb-1 d-none"><i class="fa fa-save"></i> Save</button>
                                        <button type="button" class="btn btn-secondary btn-xs mb-1 mainfee-cancel-btn d-none">Cancel</button>
                                    </td>
                                </tr>`;



                        });
                    } else {
                        rows = '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                    }

                    $maintenanceTbody.html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }

            });

        }
        loadAnalyseMaintenanceTable();

        /* Maintenance Fee Add */
        $('#add-mFee').on('click', function(e) {
            $("#add-mFee").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            e.preventDefault();

            var clientId = <?php echo $edit['client_account_id'] ?>;
            var $selectedOption = $('#maint-analysis option:selected');
            var analysisName = $selectedOption.text();
            var analysisValue = $selectedOption.val();
            var dataTableId = $selectedOption.data('table-id');
            var anumber = $selectedOption.data('itemnumber');
            var dprice = $selectedOption.data('price');
            <?php /* var adesc = $selectedOption.data('analysesdesc'); */ ?>
            var adesc = $('#m_desc').val();
            var monthlyFee = $('#monthly_fee').val();

            if (!analysisValue || !monthlyFee) {
                mug_alert_lite('error', 'Please fill all fields.');
                $("#add-mFee").prop("disabled", false).html('Add to List');
                return;
            }

            if (dataTableId === 'client') {
                let alreadyInTable = false;
                $('#maintenance-tbody tr').each(function() {
                    if ($(this).data('apriceid') == analysisValue) {
                        alreadyInTable = true;
                        return false;
                    }
                });

                if (alreadyInTable) {
                    mug_alert_lite('warning', 'This analysis is already added to the list.');
                    $("#add-mFee").prop("disabled", false).html('Add to List');
                    return;
                }

            }

            const monthlyFeeData = {
                client_account_id: clientId,
                analysname: analysisName,
                analysvalue: analysisValue,
                table_id: dataTableId,
                a_number: anumber,
                def_price: dprice,
                a_desc: adesc,
                monthly_fee: monthlyFee
            };

            $.ajax({
                url: '/add_to_monthlyfee',
                method: 'POST',
                data: monthlyFeeData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#frmMaintFeeSec')[0].reset();
                        mug_alert_lite('success', response.message);
                        loadAnalyseMaintenanceTable();

                        $("#add-mFee").prop("disabled", false).html('Add to List');

                        $.ajax({
                            url: '/get_maintenance_analyses',
                            method: 'POST',
                            data: {
                                client_account_id: clientId
                            },
                            dataType: 'json',
                            success: function(response) {
                                var $select = $('#maint-analysis');
                                $select.empty().append('<option value="">-- Choose Analysis --</option>');
                                $.each(response, function(index, item) {
                                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.anumber + '" data-price="' + item.price + '" data-analysesdesc="' + item.description + '">' + item.name + '</option>');
                                });
                            },
                            error: function() {
                                alert('Failed to load analyses. Please try again.');
                            }
                        });

                        $('#maint-analysis').on('change', function() {
                            var selectedOption = $(this).find('option:selected');
                            var price = selectedOption.data('price');
                            $('#mdefault_price').val(price);
                            var inumber = selectedOption.data('itemnumber');
                            $('#mitem_number').val(inumber);
                        });

                    } else {
                        mug_alert_lite('error', response.message);
                        $("#add-mFee").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    }
                },
                error: function() {
                    mug_alert_lite('error', 'Request failed. Please try again.');
                    $("#add-mFee").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        });

        /* Maintenance Fee Edit */
        $(document).on('click', '.mainfee-edit-btn', function() {
            const $row = $(this).closest('tr');

            const analysismDesc = $row.find('.mainfee-analysis-desc').text().trim();
            const analysiscPrice = $row.find('.mainfee-analysis-price').text().trim();
            const analysismPrice = $row.find('.mainfee-analysis-mfee').text().trim();

            /*For comparison*/
            $row.data('original-mDesc', analysismDesc);
            $row.data('original-cPrice', analysiscPrice);
            $row.data('original-mPrice', analysismPrice);

            $row.find('.mainfee-analysis-desc').html(`<input type="text" id="analysis_mdesc" class="form-control form-control-sm" value="${analysismDesc}">`);

            $row.find('.mainfee-analysis-price').html(`<input type="text" id="analysis_cprice" class="form-control form-control-sm num" value="${analysiscPrice}">`);

            $row.find('.mainfee-analysis-mfee').html(`<input type="text" id="analysis_mprice" class="form-control form-control-sm num" value="${analysismPrice}">`);


            $row.find('.mainfee-edit-btn, .mainfee-delete-btn').addClass('d-none');
            $row.find('.mainfee-save-btn, .mainfee-cancel-btn').removeClass('d-none');
        });

        /*Maintenance Fee Save*/
        $(document).on('click', '.mainfee-save-btn', function() {
            $(".mainfee-save-btn").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            $(".mainfee-cancel-btn").prop("disabled", true);

            const $row = $(this).closest('tr');
            // const priceId = $row.data('price-id');
            // const discId = $(this).data('disc-id');
            const apriceId = $row.data('apriceid');
            const mainfeeId = $row.data('mainfeeid');


            const updatedmTblData = {
                aprice_id: apriceId,
                mainfee_id: mainfeeId,
                client_acc_id: $mcId,
                analysis_mdesc: $row.find('.mainfee-analysis-desc input').val(),
                analysis_cprice: $row.find('.mainfee-analysis-price input').val(),
                analysis_mprice: $row.find('.mainfee-analysis-mfee input').val(),
            };

            for (const key in updatedmTblData) {
                if (updatedmTblData[key] === '' || updatedmTblData[key] === null) {
                    mug_alert_lite('warning', 'Please fill all fields before saving.');
                    return;
                    $(".mainfee-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                }
            }

            const originalValues = {
                // analysis_name: $row.data('original-name'),
                // analysis_from: $row.data('original-from'),
                // analysis_to: $row.data('original-to'),
                // analysis_price: $row.data('original-price')
                analysis_mdesc: $row.data('original-mDesc'),
                analysis_cprice: $row.data('original-cPrice'),
                analysis_mprice: $row.data('original-mPrice')
            };

            let hasChanges = false;
            for (const key in originalValues) {
                if (updatedmTblData[key] !== originalValues[key]) {
                    hasChanges = true;
                    break;
                }
            }

            if (!hasChanges) {
                mug_alert_lite('info', 'No changes made.');
                return;
                $(".mainfee-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
            }

            $.ajax({
                url: "/update_analysis_maintenance_fee",
                type: "POST",
                data: updatedmTblData,
                dataType: "json",
                success: function(response) {

                    <?php /*if (response == "error") {
                        mug_alert_lite('error', 'Please fill all fields before saving.');
                        return;
                        $(".mainfee-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                    } */ ?>

                    if (response.status == "error") {
                        mug_alert_lite('error', 'Something went wrong!');
                        return;
                        $(".mainfee-save-btn").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    }

                    if (response.status == "success") {
                        mug_alert_lite('success', 'Updated Successfully.');

                        $row.find('.mainfee-analysis-desc').text(updatedmTblData.analysis_mdesc);
                        $row.find('.mainfee-analysis-price').text(updatedmTblData.analysis_cprice);
                        $row.find('.mainfee-analysis-mfee').text(updatedmTblData.analysis_mprice);
                        $row.find('.mainfee-edit-btn, .mainfee-delete-btn').removeClass('d-none');
                        $row.find('.mainfee-save-btn, .mainfee-cancel-btn').addClass('d-none');

                        $(".mainfee-save-btn").prop("disabled", false).html('<i class="fa fa-save"></i> Save');
                        $(".mainfee-cancel-btn").prop("disabled", false);
                    }


                },
                error: function() {
                    alert("Error saving data.");
                    $(".mainfee-save-btn").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                    $(".mainfee-cancel-btn").prop("disabled", false);
                }
            });
        });

        /*Maintenance Fee Cancel*/
        $(document).on('click', '.mainfee-cancel-btn', function() {
            const $row = $(this).closest('tr');
            const analysismDesc = $row.find('.mainfee-analysis-desc input').attr('value');
            const analysiscPrice = $row.find('.mainfee-analysis-price input').attr('value');
            const analysismPrice = $row.find('.mainfee-analysis-mfee input').attr('value');

            $row.find('.mainfee-analysis-desc').text(analysismDesc);
            $row.find('.mainfee-analysis-price').text(analysiscPrice);
            $row.find('.mainfee-analysis-mfee').text(analysismPrice);

            $row.find('.mainfee-edit-btn, .mainfee-delete-btn').removeClass('d-none');
            $row.find('.mainfee-save-btn, .mainfee-cancel-btn').addClass('d-none');
        });

        /*Maintenance Fee Delete*/
        $(document).on('click', '.mainfee-delete-btn', function() {
            const $row = $(this).closest('tr');
            const apriceId = $row.data('apriceid');
            const mainfeeId = $row.data('mainfeeid');


            const delmTblData = {
                aprice_id: apriceId,
                mainfee_id: mainfeeId,
                client_acc_id: $mcId,
            };


            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_analysis_maintenance_fee",
                        type: "POST",
                        data: delmTblData,
                        success: function(response) {
                            mug_alert_lite('success', 'Deleted Successfully.');
                            loadAnalyseMaintenanceTable();
                        },
                        error: function() {
                            alert("Error saving data.");
                        }
                    });
                }
            });

        });

        /*Maintenance Fee Tab
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/


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
                                 <input type="text" id="sub_count${y}" class="form-control num" value="${count}" name="sub_count[]" required="" min="0" max="9999999999">
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
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Subscription Tab

    $(document).ready(function() {
        var clientId = <?= $edit['client_account_id'] ?>;

        $.ajax({
            url: '/get_analyses_for_subscription',
            method: 'POST',
            data: {
                client_account_id: clientId
            },
            dataType: 'json',
            success: function(response) {
                var $select = $('#gt-subanalysis');
                $select.empty().append('<option value="">-- Choose Analysis --</option>');
                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.anumber + '" data-analysesprice="' + item.price + '" data-analysesdesc="' + item.description + '">' + item.name + '</option>');
                });
            },
            error: function() {
                alert('Failed to load analyses. Please try again.');
            }
        });

        $('#gt-subanalysis').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            /*var price = selectedOption.data('price');
            $('#sub_price').val(price);*/
            var anumber = selectedOption.data('itemnumber');
            $('#sub_item_number').val(anumber);
        });


    });
    //Subscription Tab
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Monthly Discount Tab
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
                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '"data-itemnumber="' + item.number + '" data-price="' + item.price + '">' + item.name + '</option>');
                });
            },
            error: function() {
                alert('Failed to load analyses. Please try again.');
            }
        });

        $('#gt-analysis').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var price = selectedOption.data('price');
            $('#price').val(price);
        });


    });
    //Monthly Discount Tab
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Maintenance Fee Tab
    $(document).ready(function() {
        var clientId = <?= $edit['client_account_id'] ?>;

        $.ajax({
            url: '/get_maintenance_analyses',
            method: 'POST',
            data: {
                client_account_id: clientId
            },
            dataType: 'json',
            success: function(response) {
                var $select = $('#maint-analysis');
                $select.empty().append('<option value="">-- Choose Analysis --</option>');
                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.value + '" data-table-id="' + item.table + '" data-itemnumber="' + item.anumber + '" data-price="' + item.price + '" data-analysesdesc="' + item.description + '">' + item.name + '</option>');
                });
            },
            error: function() {
                alert('Failed to load analyses. Please try again.');
            }
        });

        $('#maint-analysis').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var desc = selectedOption.data('analysesdesc');
            $('#m_desc').val(desc);
            var price = selectedOption.data('price');
            $('#mdefault_price').val(price);
            var inumber = selectedOption.data('itemnumber');
            $('#mitem_number').val(inumber);
        });
    });
    //Maintenance Fee Tab
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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