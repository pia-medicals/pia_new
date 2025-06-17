<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Add Pricing Discount</h2>
    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
	<div class="form-group">
		<label for="analysis">Minimum Value</label>
		<input type="number" id="minimum_value" class="form-control" required name="minimum_value"   min="<?= $max_disc ?>" value="<?=$edit['minimum_value'] ?>">
		<input type="hidden" name="id" value="<?=$edit['id'] ?>">
	</div>
	<div class="form-group">
		<label for="code">Maximum Value</label>
		<input type="number" id="maximum_value" class="form-control" required name="maximum_value" min="<?= $max_disc+1; ?>" value="<?=$edit['maximum_value'] ?>">
	</div>
	<div class="form-group">
		<label for="price">Percentage</label>
		<input type="number" id="percentage" class="form-control" required name="percentage" value="<?=$edit['percentage'] ?>">
	</div>
	<div class="form-group ">
		<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Submit</button>
    </div>
    </form>

</div>