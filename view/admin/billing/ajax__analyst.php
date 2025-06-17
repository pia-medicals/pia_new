<?php //print_r($wsheet) ?>
<div id="pdf_div" class="admin_table">
    <?php foreach ($site as $kk) {?>
  <table id="dataList" class="admin table table-bordered" >
    <thead>
      <tr>
       
      </tr>
      <tr>
      	<!-- <th>Name</th> -->
         <th>Analyst</th>
          <th>Client Name</th>
        <th>Client Code</th>
         <th>Site Code</th>
        <th>MRN</th>
        <th>Exam Date</th>
      <!--  <th>Analyst</th>  -->
        <!--<th>Site</th>-->
        <!-- <th>Comments</th> -->
        <th>Analysis Performed</th>
         <th>Part Number</th>
        <!-- <th>Analyst Hours</th>
        <th>PIA Analysis Code</th> -->
        <th>Study Price</th>
      </tr>
    </thead>
    <tbody>
        <?php if (isset($wsheet)) {  ?>
         


          <?php if(!empty($wsheet[$kk])) { ?> 
             <?php foreach ($wsheet[$kk] as $key => $wsheets) { 
                 $work_detials = $this->Admindb->worksheet_detials($wsheets['id']);  

                  foreach ($work_detials as $key => $work_det) {?>

                 <?php //foreach ($work_detials as $key => $work_det) { ?>
                  <tr>

                     <?php
                     $cusid=$wsheets['customer'];
                      $customer_code = $this->Admindb->get_usermeta_by_id($cusid);

            $clint_code = $this->Admindb->get_client_by_id($cusid); 

        
            ?> 
                    
                    <td> <?php echo $custmernames[$kk]['name']; ?></td>
                     <td> <?php echo $clint_code[0]['name']; ?></td></td>
                       <td> <?php echo $clint_code[0]['client_code']; ?></td></td>
                         <td> <?php echo $customer_code['customer_code']; ?></td></td>
                  	<!-- <td style="text-align:left"><?php //echo $wsheets['patient_name'];//print_r($work_detials);?></td> -->
                    <td data-label="Count" style="text-align:left">
                      <button type="button" class="btn bg-purple btn-flat margin mrnHide" id="<?php echo 'mrn-'.$key;?>"><?php echo $wsheets['mrn'];?></button>
                    	<?php /*if($viewMrn==1){?>
                         <button type="button" class="btn bg-purple btn-flat margin mrnHide" id="<?php //echo 'hide-'.$key;?>"><?php //echo $wsheets['mrn'];?></button>
                        <?php } else{?>
                       		<button type="button" class="btn bg-maroon btn-flat margin mrnView" id="<?php //echo 'view-'.$key;?>"><?php //echo substr($wsheets['mrn'], 0, 2) . str_repeat('X', strlen($wsheets['mrn']) - 3) . substr($wsheets['mrn'], -2);?></button>
                       <?php }*/ ?> 
                    </td>
                    <td data-label="Item" style="text-align:left"><?php 
                          $date = new DateTime($wsheets['created']);

                          echo $date->format('m-d-Y h:i:s A');
                       ?>
                     </td>
                     <?php 
                     if (isset($_POST['site']) && !empty($_POST['site'])) {
                        $analyst_name = $this->Admindb->get_analysis_name($_POST['site']);
                     } else {
                      $analyst_name = $this->Admindb->get_analysis_name($wsheets['assignee']);
                     }
                     

                     ?>
                  <!--   <td><?php echo $analyst_name?></td>-->
                     <!--<td style="text-align:left"><?php //echo $wsheets['webhook_customer']?></td>-->
                     <!-- <td style="text-align:left"><?php //echo $wsheets['custom_analysis_description']?></td> -->
                    <td data-label="Rate" style="text-align:left">
                      <?php $descriptionVal = "";
						//	foreach ($work_detials as $key => $work_det) {
								$ans_name = $this->Admindb->get_analysis_analyst($work_det['ans_id']);
								if(!empty($ans_name['analysis_description'])){ 
									$descriptionVal .= $ans_name['analysis_description'].','.'<br/>';
								}
						//	} 
							$descriptionVal		=	substr($descriptionVal,0,-6);
							echo  (!empty($descriptionVal))?$descriptionVal:'NO ANALYSIS PERFORMED';
					  ?>
                      </td>
                      <!-- <td style="text-align:left"><?php //echo $wsheets['analyst_hours']?></td>
                    <td data-label="Description" style="text-align:left">
						<?php $ans_nameVal 	=	"";
							/*foreach ($work_detials as $key => $work_det) {
                        		$ans_name 	= $this->Admindb->get_analysis_details($work_det['ans_id'],$_POST['site']);
								if(!empty($ans_name['code'])){ 
                        			$ans_nameVal	.=	$customer_code['customer_code'].'-'.$ans_name['code'].',<br/>';
								}
							}
							$ans_nameVal		=	substr($ans_nameVal,0,-6);
							echo  (!empty($ans_nameVal))?$ans_nameVal:'N/A';*/
						?>
                    </td> -->

                     <td>
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
                    <?php  $cost	=	'';

                  //  foreach ($work_detials as $key => $work_det) {
						  $cost=$work_det['rate'];

              // } 

              if(!empty($cost)){echo '$ '.number_format($cost,2);}?>
                    </td>
                  </tr>
                  <?php }}}
                 else { ?>
            <tr>
              <td colspan="8">No Records to Fetch</td>
            </tr>
        <?php } ?> 
            <?php //} ?>          
        
      <?php } ?> 

    
    </tbody>

  </table> <br><br>
    <?php } ?> 
  <br>
  <br>