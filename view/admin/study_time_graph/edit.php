

<div class="dashboard_body content-wrapper">
	<?php
$date = date("Y-m", strtotime($edit['date']));
?>

<section class="content">
    <?php $this->alert(); ?>
          <div class="box box-primary">
  <div class="box-header with-border">
              <h2 class="box-title">Edit Item</h2>
            </div>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">

		<div class="form-group">
			<label for="count_an">Date</label>
            <input type="text" id="date" name="date" required class="form-control" placeholder="Date" value="<?=$date ?>" readonly >
            <input type="hidden" name="id" value="<?=$edit['id'] ?>">
          </div> 


    <div class="form-group">
      <label for="count_an">Customer</label>
<?php
$sites = $this->Admindb->table_full('users', 'WHERE user_type_ids = 5');
?>


<select name="customer" id="customer" required class="form-control" >
    <option value <?php

if (!isset($edit['customer'])) echo "selected"; ?>>Choose Customer</option>
        <?php

foreach($sites as $key => $value)
  {
  if (isset($edit['customer'])) $an = $edit['customer'];
    else $an = '';
  if ($an == $value['id']) $sel_st = 'selected';
    else $sel_st = '';
  echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
  }

?>
</select>




          </div> 





		<div class="form-group">
            <input type="hidden" id="count_an" name="count_an" required class="form-control" placeholder="Count"  value="<?=$edit['count'] ?>">
          </div>          
          <div class="form-group">
          	<label for="name_an">Item</label>
            <input type="text" id="name_an" name="name_an" required class="form-control" placeholder="Name"  value="<?=$edit['name'] ?>">
          </div>       
          <div class="form-group">
          	<label for="discription">Price</label>
            <input type="number" id="rate_an" name="rate_an" required class="form-control" placeholder="Rate"  value="<?=$edit['price'] ?>">
          </div>  
          <div class="form-group">
          	<label for="discription">Description</label>
            <input type="text" id="description_an" name="description_an" required class="form-control" placeholder="Description"  value="<?=$edit['description'] ?>">
          </div>

		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Edit</button>
        </div>
    </form>

        </div>
    </section>
</div>




<style type="text/css">
    .ui-datepicker-calendar, button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all {
        display: none;
        }


input#date {
    cursor: pointer;
    background-color: white;
}













    </style>













  <script>









$(function() {
     $("#date").datepicker(
        {
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
