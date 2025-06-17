 <h3>Additional Billing Items</h3>
           <?php foreach ($site as $kk) {?>
          <table class="admin" >
         
          <thead>
           
           <tr>
            <td>Qty</td>
            <td>Client</td>
            <td>Client Code</td>
            <td>Item</td>
            <td>Billing Code</td>
            <td>Price</td>
            <td>Description</td>
            <td>Amount</td>
           
          </tr>
          </thead>
          <tbody>
            <?php if(!empty($wsheet)){ ?>
            <?php $extra_count = 0; $extra_amount = 0;  ?>
            
            <?php foreach ($wsheet[$kk] as $key => $wsheets) { ?>
          <?php $sub_found = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]); 
           $clint_code = $this->Admindb->get_client_by_id($kk);
            if(!$sub_found) {  ?>
              <!-- Unsubscribed Items -->
             <tr>
                <td>
                  <?php  $extra_count += $wsheets['qty']; echo $wsheets['qty'];  ?>
               </td>
               <td> <?php echo $custmernames[$kk]['name']; ?></td></td>
               <td> <?php echo $clint_code[0]['client_code']; ?></td></td>
               <td>
                      <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($kk,$wsheets['ans_id'],$time_id[$kk][0]); 
                          $analyses_rate_detailsArray = explode('-',$analyses_rate_details['analysis_description']);
						  echo $analyses_rate_detailsArray[0];
                      ?>
                     </td>
                     <td>
                     	<?= $analyses_rate_details['code']?>
                     </td>
                     <td>
                          <?php echo $wsheets['rate'];  ?>
                     </td>
                     <td>
                          <?php  $analyses_rate_detailsCustomArray	=	explode('-',$analyses_rate_details['custom_description']);  
						  		echo (!empty($analyses_rate_detailsCustomArray[1]))?$analyses_rate_detailsCustomArray[1]:$analyses_rate_detailsArray[1];
						  ?>
                     </td>
                     <td class="sum-class<?php echo $kk; ?>">
                        <?php $extra_amount += $wsheets['qty']*$wsheets['rate']; echo $wsheets['qty']*$wsheets['rate'];  ?>
                    </td>
              
                    <?php }else{ ?>
                               <!-- subscribed Items -->
              <?php   $sub_count = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]);
                      $total_balance = $sub_count['count'] + $previous_carry;
                      $balance_carry = $total_balance - $wsheets['qty']; 
                      if ($balance_carry<0) { ?> <!-- show only - value counts -->
                        <tr>
                          <!-- extracount -->
                          <td> 
                              <?php  $extra_count += $balance_carry*-1; echo $balance_carry*-1;  ?>
                          </td>
                           <!-- description -->
                          <td>
                            <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($kk,$wsheets['ans_id'],$time_id[$kk][0]); 
                          $analyses_rate_detailsArray = explode('-',$analyses_rate_details['analysis_description']);
						  echo $analyses_rate_detailsArray[0];
                             ?>
                           
                          </td>
                          <td>&nbsp;</td>
                          <td> <!-- rate -->
                              <?php echo $wsheets['rate'];  ?>
                          </td>
                          <td> <!-- description -->
                              <?php  $analyses_rate_detailsCustomArray	=	explode('-',$analyses_rate_details['custom_description']);  
						  		echo (!empty($analyses_rate_detailsCustomArray[1]))?$analyses_rate_detailsCustomArray[1]:$analyses_rate_detailsArray[1];
						  ?>
                          </td>
                          <!-- total extra amount -->

                          <td class="sum-class<?php echo $kk; ?>"> 
                              <?php $extra_amount += ($balance_carry*-1)*$wsheets['rate']; echo ($balance_carry*-1)*$wsheets['rate'];  ?>
                          </td>

                        <tr>
                      <?php } ?>
            <?php } ?>
            <?php } ?>
          
            
              <?php }else { ?>
              <tr>
                <td colspan="7">No Records to Fetch</td>
              </tr>
            <?php } ?> 
              </tbody>
              <?php if(!empty($wsheet)){ ?>

<?php $discount_range = $this->Admindb->get_discount_details($extra_count,$kk,$time_id[$kk][0]); ?>
<?php $customer_code = $this->Admindb->get_usermeta_by_id($kk); ?>  
<?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($kk,$time_id[$kk][0]);   ?>
<?php   
                  $sub_amount =  $this->Admindb->get_subscription_by_time_id($kk,$time_id[$kk][0]);
                  $sb_amount = $sub_amount['subscription_fees']; 

          ?>
   <?php 
                
                $misc =  $this->Admindb->miscellaneous_billing_by_date($kk,$_POST['start_date'] );  

                
          ?>

          <tfoot>

          <?php if($misc){ ?>
                <?php foreach ($misc as $key => $miscz) {  ?>
                    <tr>
                      <td>1</td>
                      <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?></td>
                      <td>&nbsp;</td>
                      <td><?php echo $miscz['price']; ?></td> 
                      <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?>-<?php echo $miscz['description']; ?></td>
                      <td class="sum-class<?php echo $kk; ?>"><?php echo $miscz['price']; ?></td>
                    </tr>
                <?php } ?>
                <?php } ?>
                <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?>- Volume Discount </td>
                  <td></td>
                  <td>-<?php $discount =$extra_amount*$discount_range['percentage']/100; echo $discount; ?></td>
                  <td><?php echo $customer_code['customer_code']  ?>- Volume Discount ( <?php  echo $discount_range['minimum_value']; ?> - <?php  echo $discount_range['maximum_value']; ?>  Studies <?php  echo $discount_range['percentage']; ?>% Discount )</td>
                  <td class="sum-class<?php echo $kk; ?>">-<?php $discount =$extra_amount*$discount_range['percentage']/100; if($discount){ echo $discount; }else{ echo 0; }  ?></td>
                </tr>
                <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?> - Monthly Subscription Fee </td>
                  <td>&nbsp;</td>
                  <td><?php echo $sb_amount ?></td>
                  <td><?php echo $customer_code['customer_code'] ?> - Monthly Subscription Fee </td>
                  <td class="sum-class<?php echo $kk; ?>"><?php if($sb_amount){ echo $sb_amount; }else{ echo 0; } ?></td>
                </tr>
                <!-- Subscription Fees -->

                <!-- Maintenance -->
                <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?> - Maintenace <?php echo $maintenance['maintenance_fee_type']; ?> </td>
                  <td></td>
                  <td><?php echo $maintenance['maintenance_fee_amount']; ?></td>
                  <td>Maintenace <?php echo $maintenance['maintenance_fee_type']; ?> </td>
                 <td class="sum-class<?php echo $kk; ?>"><?php if($maintenance) { echo $maintenance['maintenance_fee_amount']; }else{ echo 0; } ?></td>
                </tr>
                <!-- Maintenance -->

                <!-- Total -->

                <tr>
                  <td style="text-align: right; " colspan="5"><strong>Total</strong></td>
                  <td id="grand-total<?php echo $kk; ?>"></td>
                </tr>
                <!-- Miscs Billing -->
             

                <script type="text/javascript">


                   $(document).ready(function(){

                        var total = 0;
                       jQuery('.sum-class<?php echo $kk; ?>').each(function(){

                              var text = parseInt($(this).html());

                              total += text;

                            }); 

                       jQuery('#grand-total<?php echo $kk; ?>').html(total);
                     

                    });

                </script>



                <!-- Total -->
               
                 
                <?php }else{ ?>
   
        
  
   <?php } ?> 


     </table>

  <?php } ?> 

