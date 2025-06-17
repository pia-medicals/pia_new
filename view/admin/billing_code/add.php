

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Add Billing Code</h2>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
	<div class="form-group">
		<label for="analysis">Analysis</label>
		<input type="text" id="analysis" class="form-control" required name="analysis"   value="">
	</div>
	<div class="form-group">
		<label for="code">Code</label>
		<input type="text" id="code" class="form-control" required name="code" value="">
	</div>
	<div class="form-group">
		<label for="price">Price</label>
		<input type="text" id="price" class="form-control" required name="price" value="$">
	</div>

	<div class="form-group">
		<label for="price">Salesforce Code</label>
		<select name="salesforce_code" id="salesforce_code" class="form-control" required >
			<option selected disabled>Choose Salesforce Code</option>
				<?php 
				$sel = '';
					foreach ($sfc as $key => $value) {
						if(isset($meta->salesforce_code[$i]) && $meta->salesforce_code[$i] == $value['id']) $sel = 'selected';
						echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['code'].'</option>';
					}
				 ?>
		</select>
	</div>
		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Submit</button>
        </div>
    </form>





</div>