<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> 
<style type="text/css">
  
</style>
<?php //echo base_url()?>
<div class="dashboard_body content-wrapper worksheet_status">
<section class="content">
    <?php $this->alert(); ?>
          <div class="box box-primary fl100">
  <div class="box-header with-border">
              <h2 class="box-title">Billing Summary - Customer<?php print_r($site); ?></h2>
            </div>
    <div class="col-md-12 form_time">
        <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="admin_form inline-block">
            <div class="">
                <input type="text" id="start_date" required name="start_date" autocomplete="off" placeholder="Start Date" value="<?php

if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
<?php
$sites = $this->Admindb->table_full_co('users', 'WHERE user_type_ids = 5');
?>

 <input type="text" id="end_date" required name="end_date" autocomplete="off" placeholder="End Date" value="<?php

if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
<?php
$sites = $this->Admindb->table_full_co('users', 'WHERE user_type_ids = 5');
?>

       <select name="site[]" id="site" class="form-control"  multiple size="1" style="height: 1%; visibility: hidden;">
                                        
                                         

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

   


                <button type="submit" class="btn btn-primary btn-flat">Generate Report</button>


               

            </div>
        </form>
        <?php  if (isset($_POST['start_date'])) { ?>
        <div class="float-right buttons_action">
                       <button type="submit" onclick="printData('pdf_div');" class="btn btn-primary btn-flat">Print Report</button>
                    <form action="<?=SITE_URL ?>/pdf" method="post" accept-charset="utf-8" style="padding: 0; display: inline;">
                      <input type="hidden" name="pdf" value="">
                      <input type="hidden" name="date" value="<?php if (isset($_POST['start_date'])) echo date("F Y", strtotime($_POST['start_date'])); ?>">
                      <button type="submit" class="btn btn-primary btn-flat">Download Report</button>
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



                      <!--<button type="button" class="btn btn-primary btn-flat" onclick="excel_btn(this)">Download Report Excel</button>-->
						
                    </form>
                    <button type="button" class="btn btn-primary btn-flat" id="export">Download CSV</button>
                <!-- 
                       <button type="submit"   class="btn btn-primary btn-flat" data-toggle="modal" data-target="#add">Add</button> -->

                </div>

                <div id="add" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Add</h4>
                        </div>
                        <div class="modal-body">


                          <form action="" class="billing3_submit admin_form1" accept-charset="utf-8">

                            <div class="form-group">
                              <input type="number" id="count_an" required class="form-control" placeholder="Count">
                            </div>          
                            <div class="form-group">
                              <input type="text" id="name_an" name="name_an" required class="form-control" placeholder="Name">
                            </div>       
                            <div class="form-group">
                              <input type="number" id="rate_an" name="rate_an" required class="form-control" placeholder="Rate">
                            </div>  
                            <div class="form-group">
                              <input type="text" id="description_an" name="description_an" required class="form-control" placeholder="Description">
                            </div>
                            <div class="form-group">
                              <button type="submit"  class="btn btn-primary btn-flat"   > Add </button>
                            </div>

                          </form>

                        </div>
                        <div class="modal-footer">
                          <button type="button"  class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
    </div>
        <?php } ?>
    </div>





<div class="pdf_div" id="pdf_div">
<div class="admin_table">  

  


          <?php if(!empty($wsheet)){ ?>
            <?php foreach ($site as $kk) {?>
          <?php foreach ($wsheet[$kk] as $key => $wsheets) { ?>
          <?php $previous_carrys =  $this->Admindb->carry_forward_with_cust_ans_id($site,$pre_date,$wsheets['ans_id']); $previous_carry = $previous_carrys['count']; ?>
          <?php $sub_found = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id[$kk][0]);
            if($sub_found) {  ?>
          <?php $analysis_name = $this->Admindb->analyses_name_by_id($wsheets['ans_id']); ?>
          <?php $sub_count = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id[$kk][0]);  ?>
        
          <?php $total_balance = $sub_count['count'] + $previous_carry;?>
        
               
                  <?php 
                        $balance_carry = $total_balance - $wsheets['qty'];
                         if($balance_carry>=0){

                              
                            }
                            else
                            {
                               
                            } 
                  ?>
             
                  <?php   $balance_carry = $total_balance - $wsheets['qty']; 
                      if ($balance_carry<0) {
                          
                      }else{
                           
                      }
                  ?>

               
            <?php } ?>
          <?php } }?>
          <?php }else { ?>
         
        <?php } ?>   
 
</div>

<div class="admin_table">  
          <h3>Additional Billing Items</h3>
           <?php foreach ($site as $kk) {?>
          <table class="admin" id="billing_table">
          <thead>
           <tr>
            <td>Qty</td>
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
          <?php $sub_found = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id[$kk][0]); 
                if(!$sub_found) {  ?>
                    <!-- Unsubscribed Items -->
                   <tr>
                      <td>
                        <?php  $extra_count += $wsheets['qty']; echo $wsheets['qty'];  ?>
                     </td>
                  
                     <td>
                      <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($site,$wsheets['ans_id'],$time_id[$kk][0]); 
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

                   </tr>
            <?php }else{ ?>
                               <!-- subscribed Items -->
              <?php   $sub_count = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id[$kk][0]);
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
                            <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($site,$wsheets['ans_id'],$time_id[$kk][0]); 
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

                          <td class="sum-class"> 
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

          <?php $discount_range = $this->Admindb->get_discount_details($extra_count,$site,$time_id[$kk][0]); ?>
          <?php $customer_code = $this->Admindb->get_usermeta_by_id($site); ?>  
          <?php $maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($site,$time_id[$kk][0]);   ?>
          <?php   
                  $sub_amount =  $this->Admindb->get_subscription_by_time_id($site,$time_id[$kk][0]);
                  $sb_amount = $sub_amount['subscription_fees']; 

          ?>
          <?php 
                
                $misc =  $this->Admindb->miscellaneous_billing_by_date( $site,$_POST['start_date'] );  

                
          ?>
         

          <tfoot>

                <!-- Miscs Billing -->
               <?php if($misc){ ?>
                <?php foreach ($misc as $key => $miscz) {  ?>
                    <tr>
                      <td>1</td>
                      <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?></td>
                      <td>&nbsp;</td>
                      <td><?php echo $miscz['price']; ?></td> 
                      <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?>-<?php echo $miscz['description']; ?></td>
                      <td class="sum-class"><?php echo $miscz['price']; ?></td>
                    </tr>
                <?php } ?>
                <?php } ?>
                <!-- Miscs Billing -->
                <!-- Discount -->
                <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?>- Volume Discount </td>
                  <td></td>
                  <td>-<?php $discount =$extra_amount*$discount_range['percentage']/100; echo $discount; ?></td>
                  <td><?php echo $customer_code['customer_code']  ?>- Volume Discount ( <?php  echo $discount_range['minimum_value']; ?> - <?php  echo $discount_range['maximum_value']; ?>  Studies <?php  echo $discount_range['percentage']; ?>% Discount )</td>
                  <td class="sum-class">-<?php $discount =$extra_amount*$discount_range['percentage']/100; if($discount){ echo $discount; }else{ echo 0; }  ?></td>
                </tr>
                <!-- Discount -->
                 <!-- Subscription Fees -->
                 <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?> - Monthly Subscription Fee </td>
                  <td>&nbsp;</td>
                  <td><?php echo $sb_amount ?></td>
                  <td><?php echo $customer_code['customer_code'] ?> - Monthly Subscription Fee </td>
                  <td class="sum-class"><?php if($sb_amount){ echo $sb_amount; }else{ echo 0; } ?></td>
                </tr>
                <!-- Subscription Fees -->

                <!-- Maintenance -->
                <tr>
                  <td>1</td>
                  <td><?php echo $customer_code['customer_code'] ?> - Maintenace <?php echo $maintenance['maintenance_fee_type']; ?> </td>
                  <td></td>
                  <td><?php echo $maintenance['maintenance_fee_amount']; ?></td>
                  <td>Maintenace <?php echo $maintenance['maintenance_fee_type']; ?> </td>
                 <td class="sum-class"><?php if($maintenance) { echo $maintenance['maintenance_fee_amount']; }else{ echo 0; } ?></td>
                </tr>
                <!-- Maintenance -->

                <!-- Total -->

                <tr>
                  <td style="text-align: right; " colspan="5"><strong>Total</strong></td>
                  <td id="grand-total<?php echo $kk; ?>"></td>
                </tr>

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
               
                 
          </tfoot>    
    
    <?php }else{ ?>
   
        
  
    <?php } ?> 


      </table>

   <?php } ?> 




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


                //function isDonePressed(){
                    //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

               // if (isDonePressed()){
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

$(function() {
     $("#end_date").datepicker(
        {
            dateFormat: "yy-mm",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            onClose: function(dateText, inst) {


                //function isDonePressed(){
                    //return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                //}

               // if (isDonePressed()){
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
<script src="//<?=ASSET ?>js/csvExport.min.js"></script>  
<script>
$( "#export" ).click(function() {
  	$('#billing_table').csvExport();
});
</script>

  <script type="text/javascript">
     $(document).ready(function() {       
   
        $('#site').multiselect({       
        nonSelectedText: 'Choose Customer',
        includeSelectAllOption: true,
         maxHeight: 230,
         buttonWidth: '150px'                
    });
});
</script>
