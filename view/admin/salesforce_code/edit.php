

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Edit Salesforce Code</h2>

    <form role="Form" method="post" action="<?=SITE_URL ?>/admin/salesforce_code" class="admin_form" accept-charset="UTF-8" autocomplete="off">
		<div class="form-group">
			<label for="code">Salesforce Code</label>
			<input type="text" id="code" class="form-control" required name="code" value="<?=$edit['code'] ?>">
			<input type="hidden" name="id" value="<?=$edit['id'] ?>">
		</div>


		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" class="form-control" required name="description"  value=""><?=$edit['description'] ?></textarea>
		</div>

		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
        </div>
    </form>





</div>