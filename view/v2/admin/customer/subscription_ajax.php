<?php
$analysis_client = $this->Admindb->analyses_rate_user($edit['client_account_id']);
$subscriptions = $this->Admindb->get_analyses_subscriptions($edit['client_account_id']);
$subscription_total = !empty($subscriptions[0]['subscription_price']) ? $subscriptions[0]['subscription_price'] : '';
?>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="analysis1">Analysis</label>
            <select id="analysis1" name="analysis1" class="form-control" required="">
                <option value="">-- Choose Analysis --</option>
                <?php
                foreach ($analysis_client as $key => $value) {                    
                    echo '<option value="' . $value['analysis_client_price_id'] . '">' . $value['analysis_name'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="count">Count</label>
            <input type="number" id="count1" name="count1" class="form-control" required="" min="1" max="9999999999">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label style="visibility: hidden; display: block">add</label>
            <button type="button" id="add-sub-row" class="btn btn-success">Add To List</button>
            <button type="button" id="delete-subscription" class="btn btn-danger pull-right" style="display: none;">Delete</button>
        </div>
    </div>
</div>
<div class="row">
    <form id="frmSubscription" name="frmSubscription" method="post" class="container-fluid">    
        <div class="col-md-12">
            <h3 class="text-center">Analyses Subscription</h3>
        </div>
        <div class="admin_table mb-5">
            <table class="admin">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Analysis</th>
                        <th>Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="subscription-tbody">
                    <?php
                    if (!empty($subscriptions)) {
                        foreach ($subscriptions as $key => $value) {
                            ?>
                            <tr>             
                                <td><?php echo ($key + 1); ?></td>
                                <td style="text-align: left;"><?= $value['analysis_name'] ?></td>
                                <td> 
                                    <input disabled="" type="number" id="sub_count<?= $key ?>" class="form-control make-subs-editable"  value="<?= $value['subscription_volume'] ?>" name="sub_count[]" required="" min="0" max="9999999999">
                                </td>
                                <td>
                                    <input class="make-subs-editable subscription_sel" disabled="" type="checkbox" id="sub_sel<?= $key ?>" name="sub_sel[]">                         
                                    <input type="hidden" class="subscription_content_id" id="subscription_content_id<?= $key ?>" value="<?= $value['subscription_content_id']; ?>" name="subscription_content_id[]">
                                    <input type="hidden" class="subscription_ids" id="subscription_ids<?= $key ?>" value="<?= $value['subscription_ids']; ?>" name="subscription_ids[]">
                                    <input type="hidden" class="sub_analysis_id" id="sub_analysis_id<?= $key ?>" value="<?= $value['analysis_client_price_ids']; ?>" name="sub_analysis_id[]">
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>        
                </tbody>
                <tfoot id="subscription-tfoot" <?php echo !empty($subscriptions) ? '' : 'style="display: none;"'; ?>>
                    <tr>
                        <th colspan="3" style="text-align: right;font-size: 16px;">Subscription Amount</th>
                        <td colspan="1" style="width: 400px;"><input type="number" id="subscription_total" class="form-control make-subs-editable" name="subscription_total" value="<?= $subscription_total ?>" required="" min="0" max="9999999999" disabled=""></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php
        $dps = 'none';
        if (!empty($subscriptions)) {
            $dps = 'block';
        }
        ?>
        <div style="display: <?php echo $dps; ?>; padding: 15px;" class="col-md-12 save-subscription">
            <button id="edit-subscription" class="btn btn-warning pull-right">Edit</button>
        </div> 
        <div style="display: none; padding: 15px;" class="col-md-12 save-subscription">
            <button type="submit" id="submit_subscription" name="submit_subscription" class="btn btn-primary pull-right">Save</button>
        </div>
    </form>
</div>
