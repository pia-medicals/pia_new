<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); 

error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('memory_limit', '-1');
//ini_set('session.gc_maxlifetime', 3600);
    ?>
    <?php if(isset($_GET['assign']) && $_GET['assign'] == 1){ ?>
<div class="alert alert-warning alert-dismissible text-center">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                This worksheet is already assigned. Do you want to continue?


<form role="Form" method="post" action="" class="" accept-charset="UTF-8" autocomplete="off">
       <input type="hidden" name="tat" value="<?php echo $_SESSION['atat']; ?>">
       <input type="hidden" name="customer"  value="<?php echo $_SESSION['acustomer']; ?>">
       <input type="hidden" name="reasn" value="1">
            <button type="submit" class="btn btn-success btn-flat " id="submitbtn" name="submit">Yes</button>
      <a href="<?=SITE_URL ?>/dashboard/open_work_sheets" class="btn btn-danger btn-flat ">No</a>

      </form>









              </div>
    <?php } ?>
          <div class="box box-primary fl100">
  <div class="box-header with-border">
              <h2 class="box-title">Worksheet</h2>
            </div>
<div class="col-md-12">
<div class="box">
<div class="box-body">
  <table class="table table-bordered">
    <tbody>

      <tr>
        <td>Accession</td>
        <td><?=$edit['accession'] ?></td>
      </tr>
      <tr>
        <td>MRN</td>
        <td><?=$edit['mrn'] ?></td>
      </tr>
      <tr>
        <td>Patient Name</td>
        <td><?=$edit['patient_name'] ?></td>
      </tr>
      <tr>
        <td>Site Procedure</td>
        <td><?=$edit['site_procedure'] ?></td>
      </tr>
      <tr>
        <td>Exam Time</td>
        <td><?=$edit['exam_time'] ?></td>
      </tr>
      <tr>
        <td>Status</td>
        <td><?=$edit['status'] ?></td>
      </tr>
      <tr>
        <td>Priority</td>
        <td><?=$edit['priority'] ?></td>
      </tr>
      <tr>
        <td>Site</td>
        <td><?=$edit['site'] ?></td>
      </tr>
      <tr>
        <td>Hospital</td>
        <td><?=$edit['hospital'] ?></td>
      </tr>
      <tr>
        <td>Customer</td>
        <td><?php
if(isset($edit['webhook_customer']) && !empty($edit['webhook_customer']))
  echo $edit['webhook_customer'];

         ?></td>
      </tr>

      <tr>
        <td>Description</td>
        <td><?php
if(isset($edit['webhook_description']) && !empty($edit['webhook_description']))
  echo $edit['webhook_description'];

         ?></td>
      </tr>

    </tbody>
  </table>
</div>
</div>
      <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
<?php
        //$sites = $this->Admindb->table_full('users', 'WHERE user_type_ids = 5');
      //$sites = $this->get_customers_with_time_id();
	  $sites = $this->get_all_customers();
?>
        <div class="form-group customers_group row">
      <label for="group">Customer</label>
      <select  id="customers" name="customer" class="form-control customers_choose select2" required >
          <option value disabled selected>Choose customer</option>
              <?php
          foreach($sites as $key => $value)
            {
				
			  if (isset($edit['site'])) $an = $edit['site'];
				else $an = '';
			  if ($an == $value['id']) $sel_st = 'selected';
				else $sel_st = '';	
				
                echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
            }
        ?>
      </select>

      <br>

       <!-- <label for="group">Turn Around Time</label>
      <select  id="tat" name="tat" class="form-control customers_choose">
          <option value="0" selected>Choose TAT</option>
          <option value="2">2 Hours</option>
          <option value="4">4 Hours</option>
          <option value="6">6 Hours</option>
          <option value="24">24 Hours</option>
              
      </select> -->
      <input type="hidden" name="tat" id="tat" value="0">
    </div>

        <div class="form-group  text-center">
            <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Assign to me</button>
        </div>
      </form>


</div>

        </div>
    </section>

</div>

<style type="text/css">
  a.btn.btn-primary.btn-flat {
    border-radius: 3px;
    background: linear-gradient(90deg, #18a0ee,#388cff);
    /* background: linear-gradient(90deg, #3c69bc , #20bdff , #328be6); */
    /* background: linear-gradient(90deg, #0b68aa , #20bdff , #074483); */
    box-shadow: 1px 2px 4px rgba(0, 0, 0, .3);
}

.select2-container--default .select2-selection--single{
  height: 35px;
  border: 1px solid #d2d6de !important;
}
</style>