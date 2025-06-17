<?php
if (!empty($wsheet)) {
    // $user_id_arr = array_keys($wsheet);
    foreach ($wsheet as $details) {
        ?>
        <table class="tblDataList admin table table-bordered"> 
            <thead>
                <tr>
                    <th>Client Name</th> 
                    <th>Client Code</th>
                    <th>Patient Name</th>
                    <th>MRN</th>
                    <th>Ascension</th>
                    <th>Exam Date</th>
                    <th>Assignee</th> 
                    <th>Second Check</th>
                    <th>Analysis Performed</th>
<!--                    <th>PIA Analysis Code</th>-->
                    <th>Site Code</th>
                    <th>Item Number</th>
                    <th>Study Price</th>
                </tr>
            </thead>
            <tbody>       
                <?php if (!empty($details)) { ?> 
                    <?php
                    foreach ($details as $key => $citem) {
                        ?>
                        <tr>
                            <td><?php echo!empty($citem['client_name']) ? $citem['client_name'] : ''; ?></td>                            
                            <td><?php echo!empty($citem['client_number']) ? $citem['client_number'] : ''; ?></td>
                            <td><?php echo!empty($citem['patient_name']) ? $citem['patient_name'] : ''; ?></td>
                            <td><?php echo!empty($citem['mrn']) ? $citem['mrn'] : ''; ?></td>
                            <td><?php echo!empty($citem['accession']) ? $citem['accession'] : ''; ?></td>
                            <td><?php echo!empty($citem['created_at']) ? date('d-m-Y', strtotime($citem['created_at'])) : ''; ?></td>                            
                            <td><?php echo!empty($citem['assignee_name']) ? $citem['assignee_name'] : ''; ?></td>
                            <td><?php echo!empty($citem['second_analyst_name']) ? $citem['second_analyst_name']  : ''; ?></td>
                            <td><?php echo!empty($citem['analysis_name']) ? $citem['analysis_name'] : ''; ?></td>                           
                            <td><?php echo!empty($citem['site_code']) ? $citem['site_code'] : ''; ?></td>     
                            <td><?php echo!empty($citem['analysis_code']) ? $citem['analysis_code'] : ''; ?></td>                       
                            <td><?php echo!empty($citem['analysis_client_price']) ? $citem['analysis_client_price'] : ''; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="12"><strong>No Details Found!</strong></td>
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
                <th>Client Name</th> 
                <th>Client Code</th>
                <th>Patient Name</th>
                <th>MRN</th>
                <th>Ascension</th>
                <th>Exam Date</th>
                <th>Assignee</th> 
                <th>Second Check</th>
                <th>Analysis Performed</th>
<!--                <th>PIA Analysis Code</th>-->
                <th>Site Code</th>
                <th>Item Number</th>
                <th>Study Price</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <td colspan="12"><strong>No Details Found!</strong></td>
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