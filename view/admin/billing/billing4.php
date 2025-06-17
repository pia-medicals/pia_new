<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');
?>

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
        <form id="frmBilling"  accept-charset="utf-8"  class="admin_form inline-block">
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

            <button type="submit" class="btn btn-primary btn-flat" id="btn_generate">Generate Report</button>
            <?php  /*if (isset($_POST['start_date'])) { if($viewMrn!=1){ ?>
			<button type="submit" class="btn btn-primary btn-flat" name="btnMRNView">MRN No View</button>
            <?php } if($viewMrn==1){?>
            <button type="submit" class="btn btn-primary btn-flat" name="btnMRNMask">MRN No Mask</button>	
            <?php } }*/ //echo $viewMrn;?>
          </div>
        </form>
        <?php  //if (isset($_POST['start_date'])) { ?>
          <div class="float-right buttons_action" id="s_btn1" style="display:none;">
          
            <button type="submit" onclick="printData('pdf_div');" class="btn btn-primary btn-flat">Print Report</button>
			<button type="button" class="btn btn-primary btn-flat" id="export">Download CSV</button>

         </div>
       <?php //} ?>
     </div>

     <?php


// $this->debug($pers);

     ?>
     

    <div class="pdf_div">
                <div class="">  
                </div>

                <div id="pdf_div" class="admin_table my-det-val">

                </div>
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
  $("#frmBilling").on("submit", function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var elem = '#btn_generate';
        $(elem).html("Generating.. <i class='fa fa-spin fa-spinner'></i>").attr("disabled", true);
        $('.my-det-val').html('');
        $("#s_btn1").hide();
      //  $(".worksheet_status").find("#btnMRNViewHide").hide();
        $.ajax({
            url: '/ajax/billing_summary_analyst_ajax', // Update with the actual path to your PHP script
            type: 'POST',
            data: formData,
            success: function (response) {
                // Handle the response from the server
                $(elem).html("Generate Report").attr("disabled", false);
                $('.my-det-val').html(response);
                $("#s_btn1").show();
              //  $(".worksheet_status").find("#btnMRNViewHide").show();
                exe_fn();
            },
            error: function (xhr, status, error) {
                $("#s_btn1").hide();
                $(elem).html("Generate Report").attr("disabled", false);
                console.error('AJAX Error: ' + status + error);
            }
        });
    });
    
  /*  $(".worksheet_status").on("click", "#btnMRNViewHide", function(){
        $(".worksheet_status").find(".mrn-bt").toggleClass("hide");
    });  */

</script>



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
        includeSelectAllOption: true,
         maxHeight: 230,
         buttonWidth: '150px'                
    });
});
</script>
