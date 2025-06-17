<?php
//ini_set('memory_limit', '-1');
//ini_set('max_execution_time', '0');
//print_r($wsheet);
?>
<?php foreach ($site as $ji => $kk) { ?>
    <table id="dataList<?php echo $ji; ?>" class="admin table table-bordered" >
        <thead>
            <tr>
                
            </tr> 
            <tr>
                <th>Client</th>
                <th>Client Code</th>
                <th>Name</th>
                <th>MRN</th>
                 <th>Accession</th>
                <th>Exam Date</th>
                <!--<th>Site</th>-->
                <th>Assignee</th>
                <th>Second Check</th>
                <th>Comments</th>
                <th>Analysis Performed</th>
              <!-- <th>Analyst Hours</th>-->
                <th>PIA Analysis Code</th>
                 <th>Part Number</th>
                <th>Study Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($wsheet)) {
                ?>
                <?php $customer_code = $this->Admindb->get_usermeta_by_id($kk);
                  $clint_code = $this->Admindb->get_client_by_id($kk);

                 ?>  
                <?php if (!empty($wsheet[$kk])) { ?>
                    <?php
                    foreach ($wsheet[$kk] as $key => $wsheets) {
                        $work_detials = $this->Admindb->worksheet_detials($wsheets['id']);
                      //  print_r($work_detials)."</br>";
                        foreach ($work_detials as $key => $work_det) {
                        ?>
                        <tr>
                            <td style="text-align:left">
                               

                                <?php echo $custmernames[$kk]['name']; ?></td>
                            <td style="text-align:left"><?php echo $clint_code[0]['client_code']; ?></td>
                            <td style="text-align:left"><?php echo $wsheets['patient_name']; //print_r($work_detials);   ?></td>
                            <td data-label="Count" style="text-align:left">
                                <?php //if ($viewMrn == 1) { ?>
                                    <button type="button" class="btn bg-purple btn-flat margin mrnHide mrn-bt hide" id="<?php echo 'hide-' . $key; ?>"><?php echo $wsheets['mrn']; ?></button>
                                <?php //} else { ?>
                                    <button type="button" class="btn bg-maroon btn-flat margin mrnView mrn-bt" id="<?php echo 'view-' . $key; ?>"><?php echo substr($wsheets['mrn'], 0, 2) . str_repeat('X', strlen($wsheets['mrn']) - 3) . substr($wsheets['mrn'], -2); ?></button>
                                <?php //} ?> 
                            </td>
                            <td style="text-align:left"><?php echo $wsheets['accession']; //print_r($work_detials);   ?></td>
                            <td data-label="Item" style="text-align:left"><?php
                                $date = new DateTime($wsheets['date']);

                                echo $date->format('m-d-Y');
                                ?>
                            </td>

                            <td><?php 
                            $scn = $wsheets['review_user_id'];
                        $review_name = $this->Admindb->get_name_by_id($scn);
                            echo $review_name; ?></td>


                            <td><?php 
                            $asn = $wsheets['assignee'];
                        $assignee_name = $this->Admindb->get_name_by_id($asn);
                            echo $assignee_name; ?></td>
                             <!--<td style="text-align:left"><?php //echo $wsheets['webhook_customer']     ?></td>-->
                            <td style="text-align:left"><?php echo $wsheets['custom_analysis_description'] ?></td>


                            <td data-label="Rate" style="text-align:left">
                                <?php
                            //   echo sizeof($work_detials);
                               // print_r($work_detials);
                              //  echo $work_detials[0]['ans_id'];
                                $descriptionVal = "";
                                if (!empty($work_detials)) {
                                   // foreach ($work_detials[0] as $key => $work_det) {
                                        //   $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                                       $ans_name = $this->Admindb->get_analysis_details_description($work_det['ans_id'], $kk);
                                    //  $ans_name = $this->Admindb->get_analysis_details_description($work_detials[0]['ans_id'], $kk);
                                        if (!empty($ans_name['analysis_description'])) {
                                            $descriptionVal .= $ans_name['analysis_description'] . ',<br/>';
                                        }
                                    //}
                                    //$descriptionVal = substr($descriptionVal, 0, -6);
                                    $descriptionVal = rtrim($descriptionVal, ",<br/>");
                                }
                             echo (!empty($descriptionVal)) ? $descriptionVal : 'NO ANALYSIS PERFORMED';
                                ?>
                            </td>





                            <!--<td style="text-align:left"><?php //echo $wsheets['analyst_hours'] ?></td>-->
                            <td data-label="Description" style="text-align:left">
                                <?php
                                $ans_nameVal = "";
                                if (!empty($work_detials)) {
                                   // foreach ($work_detials as $key => $work_det) {
                                        // $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                                        $ans_name = $this->Admindb->get_analysis_code($work_det['ans_id'], $kk);
                                        if (!empty($ans_name['code'])) {
                                            $ans_nameVal .= $customer_code['customer_code'] . '-' . $ans_name['code'] . ',<br/>';
                                        }
                                   // }
                                    //  $ans_nameVal = substr($ans_nameVal, 0, -6);
                                    $ans_nameVal = rtrim($ans_nameVal, ",<br/>");
                                }
                                echo (!empty($ans_nameVal)) ? $ans_nameVal : 'N/A';
                                ?>
                            </td>


                            <td data-label="Description" style="text-align:left">
                                <?php
                               $partname = "";
                                if (!empty($work_detials)) {
                                   // foreach ($work_detials as $key => $work_det) {
                                        // $ans_name = $this->Admindb->get_analysis_details($work_det['ans_id'], $kk);
                                        $part_number = $this->Admindb->get_analysis_part($work_det['ans_id']);
                                        if (!empty($part_number['part_number'])) {
                                            $partname .= $part_number['part_number'];
                                        }
                                   // }
                                    
                                }
                                echo $partname;
                                ?>
                            </td>



                            <td style="text-align:left">
                                <?php
                                $cost = '';
                                if (!empty($work_detials)) {
                                   // foreach ($work_detials as $key => $work_det) {
                                        $cost = $work_det['rate'];
                                  //  }
                                }
                                if (!empty($cost)) {
                                    echo '$ ' . number_format($cost, 2);
                                }
                                ?>
                            </td>
                        </tr>

                        <?php
                    }
                } 
            }

            else {
                    ?>
                    <tr>
                        <td colspan="8">No Records to Fetch</td>
                    </tr>
                <?php } ?>                                        

            <?php } ?> 
        </tbody>
    </table><br><br>
<?php } ?> 
<br>
<br>  

<script>
var html = '';
html += '  <button type="button" class="btn btn-primary btn-flat mrn-mask" name="btnMRNViewHide" id="btnMRNViewHide" style="margin-left: 3px;">View/Hide MRN</button>';
<?php
/*if (isset($_POST['start_date'])) {
    if ($viewMrn != 1) {
        ?>
            html += '  <button type="submit" class="btn btn-primary btn-flat mrn-mask" name="btnMRNView" id="btnMRNView" style="margin-left: 3px;">MRN No View</button>';
    <?php } if ($viewMrn == 1) { ?>
            html += '  <button type="submit" class="btn btn-primary btn-flat mrn-mask" name="btnMRNMask" id="btnMRNMask" style="margin-left: 3px;">MRN No Mask</button>';
        <?php
    }
}*/
?>
    $(".dashboard_body").find(".mrn-mask").remove();
    $(html).insertAfter("#export_mail");
</script>