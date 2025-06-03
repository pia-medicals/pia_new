<?php
switch ($_SESSION['user']->user_type_ids) {
    case 1:
        // $cntrlr = 'admin';
        $cntrlr = 'mydashboard';
        break;
    case 2:
        $cntrlr = 'manager';
        break;
    default:
        $cntrlr = 'dashboard';
        break;
}
$select_array = array(
    1 => 'Super Admin',
    2 => 'Manager',
    3 => 'Analyst',
    4 => 'Patient',
    5 => 'Customer'
);
$date = date("Y-m", strtotime($edit['created_at']));
?> 
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/validate/cmxform.css">
<script src="<?= ADMIN_LTE3 ?>/validate/jquery.validate.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo!empty($page_title) ? $page_title : ''; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo!empty($page_title) ? $page_title : ''; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
<div class="card-body">
		<div class="form-group">
			<label for="count_an">Date</label>
            <input type="text" id="date" name="date" required class="form-control" placeholder="Date" value="<?=$date ?>" readonly >
            <input type="hidden" name="id" value="<?=$edit['miscellaneous_billing_id'] ?>">
          </div> 


    <div class="form-group">
      <label for="count_an">Customer</label>
<?php
$sites = $this->Admindb->table_full_co('users', 'WHERE user_type_ids = 5');

?>


<select name="customer" id="customer" required class="form-control" >
    <option value <?php

if (!isset($edit['client_account_ids'])) echo "selected"; ?>>Choose Customer</option>
        <?php

foreach($sites as $key => $value)
  {
  if (isset($edit['client_account_ids'])) $an = $edit['client_account_ids'];
    else $an = '';
  if ($an == $value['user_id']) $sel_st = 'selected';
    else $sel_st = '';
  echo '<option value="' . $value['user_id'] . '" ' . $sel_st . ' >' . $value['user_name'] . '</option>';
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
            <input type="number" id="rate_an" name="rate_an" required class="form-control" placeholder="Rate"  value="<?=$edit['analysis_client_price'] ?>">
          </div>  
          <div class="form-group">
          	<label for="discription">Description</label>
            <input type="text" id="description_an" name="description_an" required class="form-control" placeholder="Description"  value="<?=$edit['analysis_invoicing_description'] ?>">
          </div>

		</div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/admin/miscellaneous_billing' ?>" class="btn btn-secondary float-right">Cancel <i aria-hidden="true" class="fa fa-redo"></i></a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->         
                </div>         
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= ADMIN_LTE3 ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        $(".admin_form").validate();
    });
</script>

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
