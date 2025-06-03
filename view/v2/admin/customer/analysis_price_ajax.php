<?php
$analyses_rate = $this->Admindb->analyses_rate_user($edit['client_account_id']);
if (!empty($analyses_rate) && $analyses_rate != false) {
    foreach ($analyses_rate as $key => $value) {
        ?>
        <tr>
            <td hidden=""><input class="analysis_ids" type="hidden" value="<?= $value['analysis_id'] ?>" id="analysis_id_<?= $key ?>" name="analysis_id[]"></td>
            <td><?php echo ($key + 1); ?></td>
            <td>
                <input type="text" id="analysis_name<?= $key ?>" class="make-editable form-control" disabled="" value="<?= $value['analysis_name'] ?>" name="analysis_name[]" maxlength="100" required="">
            </td>
            <td>
                <textarea id="analysis_desc<?= $key ?>" class="make-editable form-control" disabled="" name="analysis_desc[]" required="" maxlength="200"><?= $value['analysis_invoicing_description'] ?></textarea>
            </td>
            <td> 
                <input type="text" disabled="" id="rate<?= $key ?>" class="make-editable form-control num" value="<?= $value['analysis_client_price'] ?>" name="rate[]" required="" maxlength="10">
            </td>
            <td>                                                                    
                <input disabled="" value="<?= $value['analysis_code'] ?>" type="text" id="code<?= $key ?>" class="make-editable form-control num" name="code[]" required="" maxlength="4">
            </td>
            <td style="display:none;"> 
                <input type="hidden" disabled="" id="min_time<?= $key ?>" class="make-editable form-control num" value="<?= $value['analysis_time'] ?>" name="min_time[]" required="" maxlength="5">
            </td>
            <td>
                <input class="make-editable record-del" disabled="" type="checkbox" id="record_<?= $key ?>" name="record[]">
                <input type="hidden" class="analysis_client_price_id" id="analysis_client_price_id_<?= $key ?>" name="analysis_client_price_id[]" value="<?= $value['analysis_client_price_id'] ?>">
            </td>
        </tr>
        <?php
    }
}
?>                                                    