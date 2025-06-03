<?php
if (!empty($wsheet)) {
    // $user_id_arr = array_keys($wsheet);
    foreach ($wsheet as $details) {
        $customer = $details[0]['user_name'];
        ?>
        <table class="tblDataList admin table table-bordered" >
            <thead>
                <tr>
                    <th colspan="6"><?php echo $customer; ?></th> 
                </tr>
                <tr>
                    <th>Qty</th> 
                    <th>Item</th>
                    <th>Billing Code</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>       
                <?php if (!empty($details)) { ?> 
                    <?php
                    $c_total = 0;
                    foreach ($details as $key => $citem) {
                        $amount = !empty($citem['amount']) ? $citem['amount'] : 0;
                        $c_total += $amount;
                        ?>
                        <tr>
                            <td><?php echo!empty($citem['qty']) ? $citem['qty'] : ''; ?></td>                            
                            <td><?php echo!empty($citem['analysis_name']) ? $citem['analysis_name'] : ''; ?></td>
                            <td><?php echo!empty($citem['analysis_code']) ? $citem['analysis_code'] : ''; ?></td>
                            <td><?php echo!empty($citem['analysis_client_price']) ? $citem['analysis_client_price'] : ''; ?></td>
                            <td><?php echo!empty($citem['analysis_invoicing_description']) ? $citem['analysis_invoicing_description'] : ''; ?></td>
                            <td><?php echo $amount; ?></td>
                        </tr>
                        <?php if (sizeof($details) == ($key + 1)) { ?>
                            <tr>
                                <td colspan="4"></td>
                                <td><strong>Total:</strong></td>
                                <td><strong><?php echo $c_total; ?></strong></td>
                            </tr>    
                        <?php
                        }
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6"><strong>No Details Found!</strong></td>
                    </tr>
                <?php } ?>    
            </tbody>
        </table> <br><br>
        <?php
    }
} else {
    ?>
    <table class="tblDataList admin table table-bordered" >
        <thead>
            <tr>
                <th>Qty</th> 
                <th>Item</th>
                <th>Billing Code</th>
                <th>Price</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <td colspan="6"><strong>No Details Found!</strong></td>
            </tr>
        </tbody>
    </table>
    <script>
        hide_btns();
    </script>
    <?php
}
?> 
<br>
<br>