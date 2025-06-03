
<div class="dashboard_body content-wrapper worksheet_status">
<section class="content">
    <?php $this->alert(); ?>
          <div class="box box-primary fl100">
  <div class="box-header with-border">
              <h2 class="box-title">Billing Summary</h2>
            </div>
    <div class="col-md-12 form_time">
        <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="admin_form inline-block">
            <div class="">
                <input type="text" id="start_date" required name="start_date" autocomplete="off" placeholder="Start Date" value="<?php

if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">

  <input type="text" id="end_date" required name="end_date" autocomplete="off" placeholder="End Date" value="<?php

if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
<?php
$sites = $this->Admindb->table_full('users', 'WHERE user_type_ids = 5');
?>


<select name="site" id="site" required class="" >
    <option value <?php

if (!isset($_POST['site'])) echo "selected"; ?>>Choose Customer</option>
    option
        <?php

foreach($sites as $key => $value)
  {
  if (isset($_POST['site'])) $an = $_POST['site'];
    else $an = '';
  if ($an == $value['id']) $sel_st = 'selected';
    else $sel_st = '';
    echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
  
  }

?>
</select>



                <button type="submit" class="btn btn-primary btn-flat gen_reprt">Generate Report</button>


               

            </div>
        </form>
        <?php  if (isset($_POST['start_date'])) { ?>
        <div class="float-right buttons_action">
                       <button type="submit" onclick="printData('pdf_div');" class="btn btn-primary btn-flat">Print Report</button>
                    <form action="<?=SITE_URL ?>/pdf" method="post" accept-charset="utf-8" style="padding: 0; display: inline;">
                      <input type="hidden" name="pdf" value="">
                      <input type="hidden" name="date" value="<?php if (isset($_POST['start_date'])) echo date("F Y", strtotime($_POST['start_date'])); ?>">
                      <!--<button type="submit" class="btn btn-primary btn-flat">Download Report</button>-->
                    </form>


                    <form action="<?=SITE_URL ?>/excel/create_xl" method="post" accept-charset="utf-8" style="padding: 0; display: inline;">
                      
                      <input type="hidden" name="carry" value="">
                      <input type="hidden" name="sub" value="">
                      <input type="hidden" name="billing" value="">
                      <input type="hidden" name="t_bef_disc" value="">
                      <input type="hidden" name="pers" value="">
                      <input type="hidden" name="disc" value="">
                      <input type="hidden" name="t_amt_aftr" value="">
                      <input type="hidden" name="sub_amount" value="">
                      <input type="hidden" name="main_fee_amt" value="">
                      <input type="hidden" name="main_fee_type" value="">
                      <input type="hidden" name="gtotal" value="">



                      <button type="button" class="btn btn-primary btn-flat" onclick="excel_btn(this)">Download Report Excel</button>
                    </form>
                

                </div>
        <?php } ?>
    </div>





<div class="pdf_div" id="pdf_div">
<div class="admin_table">  

  <h3>Carry Forward From Previous Month</h3>
  <table class="admin" id="carry_table">
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
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
  foreach($carry_frwd['data'] as $key => $value)
    {
      
?>
      <tr>
        <td data-label="Analysis"><?php echo $value['name'] ?></td>
        <td data-label="Balance"><?php echo $value['count'] ?></td>
      </tr>
<?php
    }
  }

?>



        </tbody>
</table>
<br />
<br />

<h3>Subscription Package</h3>
  <table class="admin" id="sub_table">
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
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
         
          <?php foreach ($wsheet as $key => $wsheets) { ?>
          <?php 
                 $previous_carrys =  $this->Admindb->carry_forward_with_cust_ans_id($site,$pre_date,$wsheets['ans_id']);

                 $previous_carry = $previous_carrys['count']; ?>

          <?php $sub_found = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id);
             
                if($sub_found) {  ?>
              <tr>
                <td>
                  <?php $analysis_name = $this->Admindb->analyses_name_by_id($wsheets['ans_id']); echo $analysis_name; //echo $wsheets['ans_id'];  ?>
                    
                </td>
                <td>
                  <?php $sub_count = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id); print_r($sub_count['count']);  ?>
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
      
      $new_time_id = $this->Admindb->get_time_id_by_date($start_date,$site);
      
      $time_ids = $new_time_id[0]['time_id'];


      $sub_amount =  $this->Admindb->get_subscription_by_time_id($site,$time_ids);

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
</div>

<div class="admin_table">  
          <h3>Additional Billing Items</h3>
          <table class="admin" id="billing_table">
          <thead>
            <tr>
            
              <th>Item</th>
              <th>Rate</th>
              <th>Count</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
              <?php if(!empty($wsheet)){ ?>
            <?php $extra_count = 0; $extra_amount = 0;  ?>
            <?php foreach ($wsheet as $key => $wsheets) { ?>
          <?php $sub_found = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id); 
                if(!$sub_found) {  ?>
                   <tr>
                  
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

              <?php   $sub_count = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id);
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

          <?php $discount_range = $this->Admindb->get_discount($extra_count,$site,$time_id);  ?>
          <td id="disc">Discount:
            <strong><?php $discount =$extra_amount*$discount_range['percentage']/100; echo $discount; ?></strong>
          </td>
          <td id="t_amt_aftr">Total amount after <span>(<?php echo $discount_range['percentage'] ?> </span>%) Discount: 
            <strong><?php $total_after_disc = $extra_amount - $discount; echo $total_after_disc; ?></strong>
          </td>
      </tr>
      <tr>
         <?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($site,$time_id);   ?>
          <td id="main_fee_amt">Maintenance fee amount: <span><?php echo $maintenance['maintenance_fee_amount']; ?></span></td>
          <td id="main_fee_type">Maintenance fee type: <span><?php echo $maintenance['maintenance_fee_type']; ?></span></td>
          <?php $grand_total =$total_after_disc+$maintenance['maintenance_fee_amount'] + $sb_amount; ?>
          <td id="gtotal" style="font-size: 18px; background: #cbd7d3;">Grand Total : <strong><?php echo $grand_total; ?></strong></td>
      </tr>
    <?php }else{ ?>
      <tr>
        <?php if(!empty($site)){ ?>
         
            <?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($site,$time_ids);   ?>
            <td id="main_fee_amt">Maintenance fee amount: <span><?php echo $maintenance['maintenance_fee_amount']; ?></span></td>
            <td id="main_fee_type">Maintenance fee type: <span><?php echo $maintenance['maintenance_fee_type']; ?></span></td>
            <?php $grand_total = $maintenance['maintenance_fee_amount'] + $sb_amount; ?>
            <td id="gtotal" style="font-size: 18px; background: #cbd7d3;">Grand Total : <strong><?php echo $grand_total; ?></strong></td>
         <?php } ?> 
      </tr> 
    <?php } ?> 
</table>
  




</div>
</div>
</section>
</div>














<style type="text/css">
    .ui-datepicker-calendar, button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all {
        display: none;
        }

        label#start_date-error, label#site-error {
    display: none !important;
}
table.admin.bold tr {
    font-size: 16px;
}
.inline-block{
  display: inline-block;
}
@media print {

}
















    </style>
  <script>
$(function() {
     $("#start_date").datepicker(
        {
            dateFormat: "yy-mm",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            onClose: function(dateText, inst) {

			//alert(2);
                //function isDonePressed(){
                    //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

                //if (isDonePressed()){
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
                    
                     $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
                //}
            },
            beforeShow : function(input, inst) {

                inst.dpDiv.addClass('month_year_datepicker')

                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = datestr.substring(0, 2);
                    $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                    $(this).datepicker('setDate', new Date(year, month-1, 1));
                    $(".ui-datepicker-calendar").hide();
                }
            }
        })
});
  </script>

   <script>
$(function() {
     $("#end_date").datepicker(
        {
            dateFormat: "yy-mm",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            onClose: function(dateText, inst) {

      //alert(2);
                //function isDonePressed(){
                    //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

                //if (isDonePressed()){
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
                    
                     $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
                //}
            },
            beforeShow : function(input, inst) {

                inst.dpDiv.addClass('month_year_datepicker')

                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = datestr.substring(0, 2);
                    $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                    $(this).datepicker('setDate', new Date(year, month-1, 1));
                    $(".ui-datepicker-calendar").hide();
                }
            }
        })
});
  </script>
