<div class="dashboard_body content-wrapper worksheet_status">
	<section class="content">
    	<div class="box box-primary fl100">
        	<div class="box-header with-border">
              <h2 class="box-title">Study Price Report</h2>
            </div>
        </div>
        <div class="col-md-12 form_time">
             <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="admin_form inline-block">
             	<div>
                	<input type="text" id="start_date" required name="start_date" autocomplete="off" placeholder="Start Date" value="<?php
if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
					<select name="site" id="site" required>
                    	<option value <?php if (!isset($_POST['site'])) echo "selected"; ?>>Choose Customer</option>
                        <?php foreach($sitelist as $value){?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-flat">Study Report</button>
                </div>
             </form>
        </div>
        <?php print_r($wsheet) ?>
        <div class="col-md-12 form_time">
        	<div class="admin_table">  
          	<?php if(!empty($wsheet)){ 
		  			foreach ($wsheet as $key => $wsheets) { 
		   				$previous_carrys 	=  $this->Admindb->carry_forward_with_cust_ans_id($site,$pre_date,$wsheets['ans_id']); $previous_carry = $previous_carrys['count']; 
           				$sub_found 			= 	$this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id);
            			if($sub_found) {  
           					$analysis_name 	= 	$this->Admindb->analyses_name_by_id($wsheets['ans_id']); 
           					$sub_count 		= 	$this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id);  
           					$total_balance 	= 	$sub_count['count'] + $previous_carry;
                        	$balance_carry 	= 	$total_balance - $wsheets['qty'];
                         	if($balance_carry>=0){}
							else{} 
                     		$balance_carry 	= 	$total_balance - $wsheets['qty']; 
                      		if ($balance_carry<0) {}else{}
            			} 
           			} 
           			}else { } ?>  
			</div>
            <div class="admin_table">  
          		<h3>Study Price Report Details</h3>
          		<table class="admin" id="billing_table">
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
                    	<?php if(!empty($wsheet)){ 
                    			 $extra_count 		= 0; 
								 $extra_amount 		= 0; 
                     			 foreach ($wsheet as $key => $wsheets) { 
                     			 	$sub_found 		= $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id); 
                    				if(!$sub_found) {  ?>
                    <!-- Unsubscribed Items -->
                    	<tr>
                    		<td>
								<?php  $extra_count += $wsheets['qty']; echo $wsheets['qty'];  ?>
                            </td>
                            <td>
                                <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($site,$wsheets['ans_id'],$time_id); 
                                $analyses_rate_detailsArray = explode('-',$analyses_rate_details['analysis_description']);
                                echo $analyses_rate_detailsArray[0];
                                ?>
                            </td>
                            <td><?= $analyses_rate_details['code']?></td>
                            <td><?php echo $wsheets['rate'];  ?></td>
                            <td>
                                <?php  $analyses_rate_detailsCustomArray	=	explode('-',$analyses_rate_details['custom_description']);  
                                echo (!empty($analyses_rate_detailsCustomArray[1]))?$analyses_rate_detailsCustomArray[1]:$analyses_rate_detailsArray[1];
                                ?>
                            </td>
                            <td class="sum-class">
                            <?php $extra_amount += $wsheets['qty']*$wsheets['rate']; echo $wsheets['qty']*$wsheets['rate'];  ?>
                            </td>
                        </tr>
						<?php }else{ ?>
                        <!-- subscribed Items -->
                        <?php   $sub_count = $this->Admindb->count_subscription($site,$wsheets['ans_id'],$time_id);
                        $total_balance = $sub_count['count'] + $previous_carry;
                        $balance_carry = $total_balance - $wsheets['qty']; 
                        if ($balance_carry<0) { ?> <!-- show only - value counts -->
                        <tr>
                            <!-- extracount -->
                            <td><?php  $extra_count += $balance_carry*-1; echo $balance_carry*-1;  ?></td>
                            <!-- description -->
                            <td>
                            <?php $analyses_rate_details = $this->Admindb->analyses_rate_details($site,$wsheets['ans_id'],$time_id); 
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
                        </tr>
                        <?php }}} ?>
                        <?php }else { ?>
                        <tr>
                            <td colspan="7">No Records to Fetch</td>
                        </tr>
                    <?php } ?> 
                	</tbody>
					<?php if(!empty($wsheet)){ 
						$discount_range = $this->Admindb->get_discount_details($extra_count,$site,$time_id); 
						$customer_code = $this->Admindb->get_usermeta_by_id($site);   
						$maintenance = $this->Admindb->get_maintenance_by_customer_and_time_Id($site,$time_id);   
						$sub_amount =  $this->Admindb->get_subscription_by_time_id($site,$time_id);
						$sb_amount = $sub_amount['subscription_fees']; 
						$misc =  $this->Admindb->miscellaneous_billing_by_date( $site,$_POST['start_date'] );  
                    ?>
			        <tfoot>
                	<!-- Miscs Billing -->
               		<?php if($misc){ 
                	 foreach ($misc as $key => $miscz) {  ?>
                        <tr>
                          <td>1</td>
                          <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?></td>
                          <td>&nbsp;</td>
                          <td><?php echo $miscz['price']; ?></td> 
                          <td><?php echo $customer_code['customer_code'] ?> - <?php echo $miscz['name']; ?>-<?php echo $miscz['description']; ?></td>
                          <td class="sum-class"><?php echo $miscz['price']; ?></td>
                        </tr>
					<?php } } ?>
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
                          <td id="grand-total"></td>
                        </tr>
                <script type="text/javascript">
                   $(document).ready(function(){
                        var total = 0;
                       	jQuery('.sum-class').each(function(){
                              var text = parseInt($(this).html());
                              total += text;
                            }); 
                       jQuery('#grand-total').html(total);
                    });
                </script>
          			</tfoot>    
			    <?php }else{ ?>
			    <?php } ?> 
	      </table>
		</div>
    </div>
	</section>
</div>

<style type="text/css">
.ui-datepicker-calendar, button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all {display: none;}
label#start_date-error, label#site-error {display: none !important;}
table.admin.bold tr {font-size: 16px;}
.inline-block{display: inline-block;}
</style>
<script>
$(function() {
	$("#start_date").datepicker({
		dateFormat: "yy-mm",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		onClose: function(dateText, inst) {
			function isDonePressed(){
				return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
			}
			if (isDonePressed()){
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
				$('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
			}
		},
		beforeShow : function(input, inst) {
			inst.dpDiv.addClass('month_year_datepicker')
			if ((datestr = $(this).val()).length > 0) {
				year 	= 	datestr.substring(datestr.length-4, datestr.length);
				month 	= 	datestr.substring(0, 2);
				$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
				$(this).datepicker('setDate', new Date(year, month-1, 1));
				$(".ui-datepicker-calendar").hide();
			}
		}
	})
});
  </script>
