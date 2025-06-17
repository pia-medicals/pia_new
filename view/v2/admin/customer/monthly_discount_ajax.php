<?php
$discount_pricing_list = $this->Admindb->get_monthly_discounts_for_customer($edit['client_account_id']);
if (!empty($discount_pricing_list['results'])) {
    foreach ($discount_pricing_list['results'] as $key => $value) {
        ?>
        <tr>
            <td><?php echo ($key + 1); ?></td>
            <td> 
                <input disabled="" type="number" id="minimum_value<?= $key ?>" class="make-disc-editable form-control num min-max min_val" rel="<?= $key ?>" value="<?= $value['minimum_volume'] ?>" name="minimum_value[]" max="9999999999">
            </td>
            <td> 
                <input disabled="" type="number" id="maximum_value<?= $key ?>" value="<?= $value['maximum_volume'] ?>" class="make-disc-editable form-control num min-max max_val" rel="<?= $key ?>" name="maximum_value[]" max="9999999999">
            </td>
            <td>
                <input disabled="" type="number" id="percentage<?= $key ?>" class="make-disc-editable form-control perc" name="percentage[]" min="0.01" max="100" value="<?php echo $value['discount_price']; ?>">
            </td>     
            <td>
                <input disabled="" class="make-disc-editable discount_sel" type="checkbox" id="discount_sel_<?= $key ?>" name="discount_sel[]">
                <input type="hidden" class="discount_id" id="discount_id_<?= $key ?>" name="discount_id[]" value="<?= $value['discount_id'] ?>">
            </td>
        </tr>
        <?php
    }
}
?>                                                    