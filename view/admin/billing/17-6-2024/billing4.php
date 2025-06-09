<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> 
<?php //print_r($wsheet); ?>
<div class="dashboard_body content-wrapper worksheet_status">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="box box-primary fl100">
      <div class="box-header with-border">
        <h2 class="box-title">Billing Summary - Analyst Detailed</h2>
      </div>
      <div class="col-md-12 form_time">
        <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="admin_form inline-block">
          <div class="">
            <label>Date</label>
            <input type="text" id="start_date" required name="start_date" autocomplete="off" placeholder="Start Date" value="<?php

           if (isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
            <input type="text" id="end_date" required name="end_date" autocomplete="off" placeholder="End Date" value="<?php

if (isset($_POST['end_date'])) echo $_POST['end_date']; ?>">
            <?php
            $sites = $this->Admindb->table_full('users', 'WHERE user_type_ids = 3');
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
            <?php  /*if (isset($_POST['start_date'])) { if($viewMrn!=1){ ?>
			<button type="submit" class="btn btn-primary btn-flat" name="btnMRNView">MRN No View</button>
            <?php } if($viewMrn==1){?>
            <button type="submit" class="btn btn-primary btn-flat" name="btnMRNMask">MRN No Mask</button>	
            <?php } }*/ //echo $viewMrn;?>
          </div>
        </form>
        <?php  if (isset($_POST['start_date'])) { ?>
          <div class="float-right buttons_action">
          
            <button type="submit" onclick="printData('pdf_div');" class="btn btn-primary btn-flat">Print Report</button>
			<button type="button" class="btn btn-primary btn-flat" id="export">Download CSV</button>

         </div>
       <?php } ?>
     </div>

     <?php


// $this->debug($pers);

     ?>
     

    <div class="pdf_div" id="pdf_div">
      <div class="">  
       </tbody>
       


</div>
<?php //print_r($wsheet) ?>
<div id="pdf_div" class="admin_table">
    <?php foreach ($site as $kk) {?>
  <table id="dataList" class="admin table table-bordered" >
    <thead>
      <tr>
        <td> <?php echo $custmernames[$kk]['name']; ?></td>
      </tr>
      <tr>
      	<!-- <th>Name</th> -->
        <th>MRN</th>
        <th>Exam Date</th>
        <th>Analyst</th>
        <!--<th>Site</th>-->
        <!-- <th>Comments</th> -->
        <th>Analysis Performed</th>
        <!-- <th>Analyst Hours</th>
        <th>PIA Analysis Code</th> -->
        <th>Study Price</th>
      </tr>
    </thead>
    <tbody>
        <?php if (isset($wsheet)) {  ?>
          <?php $customer_code = $this->Admindb->get_usermeta_by_id($kk); ?> 
          <?php if(!empty($wsheet[$kk])) { ?> 
             <?php foreach ($wsheet[$kk] as $key => $wsheets) { 
                 $work_detials = $this->Admindb->worksheet_detials($wsheets['id']);  ?>
                 <?php //foreach ($work_detials as $key => $work_det) { ?>
                  <tr>
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
                     <td><?php echo $analyst_name?></td>
                     <!--<td style="text-align:left"><?php //echo $wsheets['webhook_customer']?></td>-->
                     <!-- <td style="text-align:left"><?php //echo $wsheets['custom_analysis_description']?></td> -->
                    <td data-label="Rate" style="text-align:left">
                      <?php $descriptionVal = "";
							foreach ($work_detials as $key => $work_det) {
								$ans_name = $this->Admindb->get_analysis_analyst($work_det['ans_id']);
								if(!empty($ans_name['analysis_description'])){ 
									$descriptionVal .= $ans_name['analysis_description'].','.'<br/>';
								}
							} 
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
                    <td style="text-align:left">
                    <?php  $cost	=	'';

                    foreach ($work_detials as $key => $work_det) {
						  $cost=$work_det['rate']; } 

              if(!empty($cost)){echo '$ '.number_format($cost,2);}?>
                    </td>
                  </tr>
                  <?php }}
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
</div>

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
//$('.mrnView').on('click',function(){
//	var id	=	this.id.split('-');
//	$('#'+this.id).hide();
//	$('#hide-'+id[1]).show();
//});
//$('.mrnHide').on('click',function(){
//	var id	=	this.id.split('-');
//	$('#'+this.id).hide();
//	$('#view-'+id[1]).show();
//});
$('.viewValue').on('click',function(){
	//alert(1);
	var mrn	=	$('.mrnView').id;	
	alert(mrn);
});
  $(function() {
   $("#start_date").datepicker(
   {
    dateFormat: "yy-mm",
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    onClose: function(dateText, inst) {

//alert(1);
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
  	$('#pdf_div').csvExport();
});

     $(document).ready(function() {       
   
        $('#site').multiselect({       
        nonSelectedText: 'Choose Analyst',
        includeSelectAllOption: false,
         maxHeight: 230,
         buttonWidth: '150px'                
    });
});
</script>
