<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> 
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

   

 <!-- <select name="site[]" id="site[]" class="dropdown-menu" multiple> 
      <option value <?php
  //if (!isset($_POST['site'])) echo "selected"; ?>>Choose Customer</option>
    option
      //  <?php

//foreach($sites as $key => $value)
 // {
 // if (isset($_POST['site'])) $an = $_POST['site'];
  //  else $an = '';
 // if ($an == $value['id']) $sel_st = 'selected';
  //  else $sel_st = '';
   // echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
  
 //}

///?>

 //<</select> -->
        
  

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
 <?php foreach ($site as $kk) {?>

  <h3>Carry Forward From Previous Month- <?php echo $custmernames[$kk]['name']; ?></td></h3>
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
  foreach($carry_frwd['data'][$kk] as $key => $value)
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
<?php
    }
    ?>

<br />
<br />




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

 <!-- <script type="text/javascript">
     $(document).ready(function() {       
    
      $('#site').multiselect({       
        nonSelectedText: 'Week'              
    });
      
});
</script>-->

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
