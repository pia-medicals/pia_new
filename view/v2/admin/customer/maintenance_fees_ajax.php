<?php
$maintenance_fee_list = $this->Admindb->get_maintenance_fees_for_customer($edit['client_account_id']);
if (!empty($maintenance_fee_list)) {
    foreach ($maintenance_fee_list as $key => $value) {
        ?>
        <tr>
            <td><?php echo ($key+1); ?></td>
            <td><?php echo ucwords($value['maintenance_fee_type']); ?></td>
            <td><?php echo $value['maintenance_fee_amount']; ?></td>
        </tr>
        <?php
    }
}
?>                                                    