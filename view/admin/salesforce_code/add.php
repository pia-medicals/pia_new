

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Add Salesforce Code</h2>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
		<div class="form-group">
			<label for="code">Salesforce Code</label>
			<input type="text" id="code" class="form-control" required name="code" >
		</div>


		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" class="form-control" required name="description"></textarea>
		</div>

		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
        </div>
    </form>





</div>