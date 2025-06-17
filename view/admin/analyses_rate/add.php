

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Add Analysis Rate</h2>
    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">



<?php 

$analysis = $this->Admindb->table_full('analyses');
$customer = $this->Admindb->table_full('users','WHERE user_type_ids = 5');


 ?>
	<div class="form-group">
		<label for="analysis">Analysis</label>
		<select name="analysis" id="analysis" class="form-control" required >
			<option selected disabled>Choose Analysis</option>
				<?php 
				$sel = '';
					foreach ($analysis as $key => $value) {
						//if(isset($meta->salesforce_code[$i]) && $meta->salesforce_code[$i] == $value['id']) $sel = 'selected';
						echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
					}
				 ?>
		</select>
	</div>




	<div class="form-group">
		<label for="customer">Customer</label>
		<select name="customer" id="customer" class="form-control" required >
			<option selected disabled>Choose Customer</option>
				<?php 
				$sel = '';
					foreach ($customer as $key => $value) {
						//if(isset($meta->salesforce_code[$i]) && $meta->salesforce_code[$i] == $value['id']) $sel = 'selected';
						echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
					}
				 ?>
		</select>
	</div>









		<div class="form-group">
			<label for="rate">Rate</label>
			<input type="number" id="rate" class="form-control" required name="rate" >
		</div>


		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
        </div>
    </form>





</div>