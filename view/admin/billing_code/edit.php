

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Edit Billing Code</h2>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
	<div class="form-group">
		<label for="analysis">Analysis</label>
		<input type="text" id="analysis" class="form-control" required name="analysis"   value="<?=$edit['analysis'] ?>">
		<input type="hidden" name="id" value="<?=$edit['id'] ?>">
	</div>
	<div class="form-group">
		<label for="code">Code</label>
		<input type="text" id="code" class="form-control" required name="code" value="<?=$edit['code'] ?>">
	</div>
	<div class="form-group">
		<label for="price">Price</label>
		<input type="text" id="price" class="form-control" required name="price" value="<?=$edit['price'] ?>">
	</div>

	<div class="form-group">
		<label for="price">Salesforce Code</label>
		<select name="salesforce_code" id="salesforce_code" class="form-control" required >
			<option selected disabled>Choose Salesforce Code</option>
				<?php 
				print_r($edit['salesforce_code']);
					foreach ($sfc as $key => $value) {
						$sel = '';
						if($edit['salesforce_code'] == $value['id']) $sel = 'selected';
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