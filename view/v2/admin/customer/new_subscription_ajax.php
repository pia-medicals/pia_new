<?php
$client_id = $edit['client_account_id'];
$fetch_sub_id = $this->Tatdb->get_subscription_id_by_client($client_id);
$client_sub_id = isset($fetch_sub_id) && $fetch_sub_id !== false ? $fetch_sub_id : '0';
?>
<form method="post" id="frmSubscriptionSec">

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="analysis">Analysis Name</label>
                <select id="gt-subanalysis" class="form-control first">
                    <option value="">-- Choose Analysis --</option>

                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="item_Number">Item Number</label>
                <input type="text" id="sub_item_number" class="form-control bg-white num" readonly>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="count">Count</label>
                <input type="text" id="new_sub_count" class="form-control num">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="code" style="visibility: hidden; display: block">add</label>
                <button id="add-subscription" class="btn btn-success">Add to List</button>
                <button type="button" id="delete-subscription" class="btn btn-danger pull-right" style="display: none;">Delete</button>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <form class="container-fluid" method="post" id="frmSubscription" name="frmSubscription">
        <div class="col-md-12">
            <h3 class="text-center">Analyses Subscription</h3>
        </div>
        <div class="col-md-12 bg-light">
            <div class="row justify-content-end pt-2">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="subamount">Subscription Amount</label>
                        <input type="text" id="new_sub_amount" class="form-control num znum">
                    </div>
                </div>

                <div class="col-auto">
                    <div class="form-group">
                        <label for="sub_save" style="visibility: hidden; display: block">Save</label>
                        <button id="save-added-subscription" class="btn btn-primary save-added-subscription">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin_table mb-5">
            <table class="admin">
                <thead>
                    <tr>
                        <th>Item Number</th>
                        <th>Analysis Name</th>
                        <th>Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="subscription-tbody">
                </tbody>
            </table>
        </div>

        <?php
        $dpl = 'none';
        if (!empty($discount_pricing_list['results'])) {
            $dpl = 'block';
        }
        ?>
        <div style="display: <?php echo $dpl; ?>; padding: 15px;" class="col-md-12 save-subscription-range">
            <button id="edit-subscription-range" class="btn btn-warning pull-right">Edit</button>
        </div>
        <div style="display: none; padding: 15px;" class="col-md-12 save-subscription-range">
            <button type="submit" id="subscription_submit" name="subscription_submit" class="btn btn-primary pull-right">Save</button>
        </div>
    </form>
</div>