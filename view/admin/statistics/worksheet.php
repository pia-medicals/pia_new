<div class="dashboard_body content-wrapper worksheet_status">
	<h2>Accounts</h2>
	<div class="col-md-12 form_time">
		<form action="" method="post" accept-charset="utf-8" class="">
			<div class="">
				<input type="text" id="start_date" required name="start_date" placeholder="Start Date" value="<?php if(isset($_POST['start_date'])) echo $_POST['start_date']; ?>">
				<input type="text" id="end_data" required name="end_date" value="<?php if(isset($_POST['start_date'])) echo $_POST['end_date']; ?>"  placeholder="End Date">
<?php 

$analyst = $this->Admindb->table_full('users','WHERE user_type_ids = 3');
$hospitals = $this->Admindb->clario_hospitals();
//$sites1 = $this->Admindb->clario_sites();
//$site2 = $this->Admindb->table_full('hospital');
$sites = $this->Admindb->table_full('users','WHERE user_type_ids = 5');


//print_r($sites);
/*$status = array(
      'In progress',
      'Under review',
      'Completed'
    );*/

 ?>

<select name="analyst" id="analyst" class="" >
 	<option value <?php if(!isset($_POST['analyst'])) echo "selected"; ?>>Choose analyst</option>
 	option
        <?php 
          foreach ($analyst as $key => $value) {
          	if(isset($_POST['analyst'])) $an = $_POST['analyst']; else $an = '';
            if($an == $value['id']) $sel_st = 'selected'; else $sel_st = '';

            echo '<option value="'.$value['id'].'" '.$sel_st.' >'.$value['name'].'</option>';
          }
         ?>
</select>

<select name="site" id="site" class="" >
 	<option value <?php if(!isset($_POST['site'])) echo "selected"; ?>>Choose site</option>
 	option
        <?php 
          foreach ($sites as $key => $value) {
          	if(isset($_POST['site'])) $an = $_POST['site']; else $an = '';
            if($an == $value['name']) $sel_st = 'selected'; else $sel_st = '';

            echo '<option value="'.$value['name'].'" '.$sel_st.' >'.$value['name'].'</option>';
          }
         ?>
</select>



				<button type="submit" class="btn btn-primary btn-flat">Generate Report</button>
			</div>
		</form>
	</div>


<div class="admin_table">  
  <table class="admin">
  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Analyst</th>
      <th>Date</th>
      <th>MRN</th>
      <th>Analysis Performed</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>

<?php

if(isset($wsheet) && !empty($wsheet)){
  if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
  $gtotal = 0;

 // $this->debug($wsheet);


 foreach ($wsheet as $key => $value) { 

      $addon_flows = json_decode($value['addon_flows']);
      $analyses_ids = explode(',',$value['analyses_ids']);
      $customer_id = $value['customer_id'];

      $rate = array();

      foreach ($analyses_ids as  $analyses_id) {
        $rate_data = $this->Admindb->get_rate_by_anid_cid($analyses_id,$customer_id);
        $rate[$analyses_id]['total'] = $rate_data['rate'] * $addon_flows->$analyses_id;
        $rate[$analyses_id]['count'] = $addon_flows->$analyses_id;
        $rate[$analyses_id]['rate'] = $rate_data['rate'];
      }

//$this->debug($rate);

  ?>
      <tr><!-- 
      <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td> -->
      <td data-label="Analyst"><?=$value['name'] ?></td>
      <td data-label="Date"><?=date("d/m/Y", strtotime($value['date'])) ?></td>
      <td data-label="MRN"><?php 

		$clario = $this->Admindb->get_by_id('Clario',$value['clario_id']);
      echo $clario['mrn'];


       ?></td>
      <td data-label="Analysis Performed"><?php 
      foreach (explode(',', $value['analyses_performed']) as $key => $value2) {
      	echo $value2.'<br>';
      }
       ?></td>
      <td data-label="Total"><?php 
      $total = 0;
      foreach ($rate as $key => $value3) {
      	echo $value3['rate'].' x '.$value3['count'].' = '.$value3['total'].'<br>';

        $total = $total + $value3['total'];
      }
      echo "<hr class='line'>";
      echo '<strong>'.$total.'</strong>';
      $gtotal = $gtotal + $total;

       ?></td>
    
    </tr>
<?php  } } ?>



        </tbody>
</table>
<?php if(isset($gtotal) && $gtotal > 0) { ?>
<div class="btn btn-default btn-lg flout-right gtotal">Grand Total: <?=$gtotal ?></div>
<?php } ?>
</div>










</div>
  <script>

  $( function() {
    $( "#end_data, #start_date" ).datepicker({
      dateFormat: 'yy-mm-dd'
    });
  } );

  </script>