<?php
if (!empty($wsheet)) {
    $analyst_id_arr = array_keys($wsheet);
    foreach ($analyst_id_arr as $kk) {
        ?>
        <table class="tblDataList admin table table-bordered" >
            <thead>
                <tr>
                    <th>Analyst</th>
                    <th>Client Name</th>
                    <th>Client Code</th>
                    <th>Site Code</th>
                    <th>MRN</th>
                    <th>Exam Date</th>
                    <th>Exam Time</th>
                    <th>Analysis Performed</th>
                    <th>Item Number</th>
                    <th>Study Price</th>
                </tr>
            </thead>
            <tbody>       
                <?php if (!empty($wsheet[$kk])) { ?> 
                    <?php
                    foreach ($wsheet[$kk] as $key => $studies) {
                        ?>
                        <tr>
                            <td><?php echo!empty($studies['analyst_name']) ? $studies['analyst_name'] : ''; ?></td>
                            <td><?php echo!empty($studies['client_name']) ? $studies['client_name'] : ''; ?></td>
                            <td><?php echo!empty($studies['client_number']) ? $studies['client_number'] : ''; ?></td>
                            <td><?php echo!empty($studies['site_code']) ? $studies['site_code'] : ''; ?></td>
                            <td><?php echo!empty($studies['mrn']) ? $studies['mrn'] : ''; ?></td>
                            <td>
                                <?php
                                if (!empty($studies['created_at'])) {
                                    $created_date = new DateTime($studies['created_at']);
                                    echo $created_date->format('m-d-Y');
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($studies['created_at'])) {
                                    $created_date = new DateTime($studies['created_at']);
                                    echo $created_date->format('h:i:s A');
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $analysis_name = '';
                                if (!empty($studies['analysis_name'])) {
                                    $analysis_name = $studies['analysis_name'];
                                } elseif (!empty($studies['analysis_invoicing_description'])) {
                                    $analysis_name = $studies['analysis_invoicing_description'];
                                }
                                echo $analysis_name;
                                ?>
                            </td>
                            <td><?php echo!empty($studies['analysis_code']) ? $studies['analysis_code'] : ''; ?></td>
                            <td><?php echo!empty($studies['analysis_client_price']) ? $studies['analysis_client_price'] : ''; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10">No Records to Fetch</td>
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
                <th>Analyst</th>
                <th>Client Name</th>
                <th>Client Code</th>
                <th>Site Code</th>
                <th>MRN</th>
                <th>Exam Date</th>
                <th>Exam Time</th>
                <th>Analysis Performed</th>
                <th>Item Number</th>
                <th>Study Price</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <td colspan="10"><strong>No Details Found!</strong></td>
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