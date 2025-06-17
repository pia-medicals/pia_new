 <?php foreach ($site as $kk) {?>

  
  <table class="admin" id="carry_table">
    <h3>Carry Forward From Previous Month</td></h3>
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Customer</th>
      <th>Analysis</th>
      <th>Balance<br />(Carry Forward)</th>
    </tr>
  </thead>
  <tbody>

<?php

if (isset($carry_frwd['data']) && !empty($carry_frwd['data']))
  {
  $page = 1;
  $gtotal = 0;
  $extra_array = array();
  foreach($carry_frwd['data'][$kk] as $key => $value)
    {
      
?>
      <tr>
      	<td data-label="Analysis"><?php echo $custmernames[$kk]['name']; ?></td>
        <td data-label="Analysis"><?php echo $value['name'] ?></td>
        <td data-label="Balance"><?php echo $value['count'] ?></td>
      </tr>
<?php
    }
  }
  else { ?>
            <tr>
              <td colspan="2">No Records to Fetch</td>
            </tr>
        <?php } 

?>



        </tbody>
</table>

<br />
<br />

  <table class="admin" id="sub_table">

<h3>Subscription Package</h3>
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Customer</th>
      <th>Item</th>
      <th>Subscribed</th>
      <th>Previous Month Carry</th>
      <th>Total Balance</th>
      <th>Used</th>
      <th>Balance<br />(Carry Forward)</th>
      <th>Extra</th>
    </tr>
  </thead>
  <tbody> 
          <?php if(!empty($wsheet)){ ?>
         
          <?php foreach ($wsheet[$kk] as $key => $wsheets) { ?>
          <?php 
                 $previous_carrys =  $this->Admindb->carry_forward_with_cust_ans_id($kk,$pre_date,$wsheets['ans_id']);

                 $previous_carry = $previous_carrys['count']; ?>

          <?php $sub_found = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]);
             
                if($sub_found) {  ?>
              <tr>
              	<td><?php echo $custmernames[$kk]['name']; ?></td>
                <td>
                  <?php $analysis_name = $this->Admindb->analyses_name_by_id($wsheets['ans_id']); echo $analysis_name; //echo $wsheets['ans_id'];  ?>
                    
                </td>
                <td>
                  <?php $sub_count = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]); print_r($sub_count['count']);  ?>
                </td>
                <td><?php echo $previous_carry; ?></td>
                <td><?php $total_balance = $sub_count['count'] + $previous_carry; echo $total_balance; ?></td>
                <td><?php echo $wsheets['qty'] ?></td>
                <td>
                  <?php 
                        $balance_carry = $total_balance - $wsheets['qty'];
                         if($balance_carry>=0){

                                echo $balance_carry;
                            }
                            else
                            {
                                echo "0";
                            } 
                  ?>
                </td>
                <td>
                  <?php   $balance_carry = $total_balance - $wsheets['qty']; 
                      if ($balance_carry<0) {
                            echo $balance_carry*-1; 
                      }else{
                            echo "0";
                      }
                  ?>
                </td>
              </tr>
            <?php } ?>
          <?php } ?>
          <?php }else { ?>
            <tr>
              <td colspan="7">No Records to Fetch</td>
            </tr>
        <?php } ?>   
  </tbody>


<?php
if (isset($site))
  {
      
   /*   $new_time_id = $this->Admindb->get_time_id_by_date($start_date,$end_date,$kk);
      
      $time_ids = $new_time_id[0]['time_id'];


      $sub_amount =  $this->Admindb->get_subscription_by_time_id($kk,$time_ids);

      $sb_amount = $sub_amount['subscription_fees']; */
       $new_time_id[] = $this->Admindb->get_time_id_by_date($start_date,$end_date,$kk);
      
      $time_ids = $new_time_id[$kk]['time_id'];


      $sub_amount =  $this->Admindb->get_subscription_by_time_id($kk,$time_ids);

      $sb_amount = $sub_amount['subscription_fees'];



  ?><tfoot>
      <tr class="not_exicute">
        <td></td>
        <td></td>
        <!--<td></td>-->
        <!--<td></td>-->
        <!--<td></td>
        <td></td>-->
        <td id="sub_a" style="text-align: right;" align="right" colspan="4">Subscription Amount: <strong>
          <?php if(isset($sb_amount)){echo $sb_amount;}else{echo 'Amount not added';} ?></strong></td>
      </tr>
    </tfoot>
<?php } ?>

</table>


<br />
<br />


 
        
          <table class="admin">
              <h3>Additional Billing Items</h3>
          <thead>
            <tr>
             <th>Customer</th>
              <th>Item</th>
              <th>Rate</th>
              <th>Count</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
              <?php if(!empty($wsheet)){ ?>
            <?php $extra_count = 0; $extra_amount = 0;  ?>
            <?php foreach ($wsheet[$kk] as $key => $wsheets) { ?>
          <?php $sub_found = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]); 
                if(!$sub_found) {  ?>
                   <tr>
                   	<td><?php echo $custmernames[$kk]['name']; ?></td>
                  
                     <td>
                          <?php $analysis_name = $this->Admindb->analyses_name_by_id($wsheets['ans_id']); echo $analysis_name; //echo $wsheets['ans_id'];  ?>
                     </td>
                     <td>
                          <?php echo $wsheets['rate'];  ?>
                     </td>
                     <td>
                        <?php  $extra_count += $wsheets['qty']; echo $wsheets['qty'];  ?>
                     </td>
                      <td>
                        <?php $extra_amount += $wsheets['qty']*$wsheets['rate']; echo $wsheets['qty']*$wsheets['rate'];  ?>
                      </td>

                   </tr>
            <?php }else{ ?>

              <?php   $sub_count = $this->Admindb->count_subscription($kk,$wsheets['ans_id'],$time_id[$kk][0]);
                      $total_balance = $sub_count['count'] + $previous_carry;
                      $balance_carry = $total_balance - $wsheets['qty']; 
                      if ($balance_carry<0) { ?> <!-- show only - value counts -->
                        <tr>
                       
                          <td>
                            <?php $analysis_name = $this->Admindb->analyses_name_by_id($wsheets['ans_id']); echo $analysis_name; //echo $wsheets['ans_id'];  ?>
                          </td>
                          <td>
                              <?php echo $wsheets['rate'];  ?>
                          </td>
                          <td>
                              <?php  $extra_count += $balance_carry*-1; echo $balance_carry*-1;  ?>
                          </td>

                          <td>
                              <?php $extra_amount += ($balance_carry*-1)*$wsheets['rate']; echo ($balance_carry*-1)*$wsheets['rate'];  ?>
                          </td>

                        <tr>
                      <?php } ?>
            <?php } ?>
          <?php } ?>
          
            </tbody>
              <tfoot>
                 <tr>
                  
                  <td></td>
                  <td>Total</td>
                  <td><strong><?php echo $extra_count; ?></strong></td>
                  <td><strong><?php echo $extra_amount; ?></strong></td>
                </tr>
              </tfoot>
              <?php }else { ?>
              <tr>
                <td colspan="7">No Records to Fetch</td>
              </tr>
            <?php } ?>   
        </table>

      
<br />
   
<table class="admin bold" id="total_table">
   <?php if(!empty($wsheet)){ ?>
      <tr>
          <td id="t_bfr_disc">Total Before Discount: <strong><?php echo $extra_amount; ?></strong></td>

          <?php $discount_range = $this->Admindb->get_discount($extra_count,$kk,$time_id[$kk][0]);  ?>
          <td id="disc">Discount:
            <strong><?php $discount =$extra_amount*$discount_range['percentage']/100; echo $discount; ?></strong>
          </td>
          <td id="t_amt_aftr">Total amount after <span>(<?php echo $discount_range['percentage'] ?> </span>%) Discount: 
            <strong><?php $total_after_disc = $extra_amount - $discount; echo $total_after_disc; ?></strong>
          </td>
      </tr>
      <tr>
         <?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($kk,$time_id[$kk][0]);   ?>
          <td id="main_fee_amt">Maintenance fee amount: <span><?php echo $maintenance['maintenance_fee_amount']; ?></span></td>
          <td id="main_fee_type">Maintenance fee type: <span><?php echo $maintenance['maintenance_fee_type']; ?></span></td>
          <?php $grand_total =$total_after_disc+$maintenance['maintenance_fee_amount'] + $sb_amount; ?>
          <td id="gtotal" style="font-size: 18px; background: #cbd7d3;">Grand Total : <strong><?php echo $grand_total; ?></strong></td>
      </tr>
    <?php }else{ ?>
      <tr>
        <?php if(!empty($site)){ ?>
         
            <?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($kk,$time_id[$kk][0]);   ?>
            <td id="main_fee_amt">Maintenance fee amount: <span><?php echo $maintenance['maintenance_fee_amount']; ?></span></td>
            <td id="main_fee_type">Maintenance fee type: <span><?php echo $maintenance['maintenance_fee_type']; ?></span></td>
            <?php $grand_total = $maintenance['maintenance_fee_amount'] + $sb_amount; ?>
            <td id="gtotal" style="font-size: 18px; background: #cbd7d3;">Grand Total : <strong><?php echo $grand_total; ?></strong></td>
         <?php } ?> 
      </tr> 
    <?php } ?> 
</table>
  
  <?php } ?>

