

<div class="dashboard_body content-wrapper">


<section class="content">
	<?php
	$this->alert();
	$select_array = array(
		1 =>'Super Admin',
		2 =>'Manager',
		3 =>'Analyst',
		4 =>'Patient',
		5 =>'Customer'
	);
?>
          <div class="box box-primary">
	<div class="box-header with-border">
              <h2 class="box-title">Edit Analyses Category</h2>
            </div>
    <form role="Form" method="post" action="<?=SITE_URL?>/admin/analyses_category" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group">
			<label for="category">Name</label>
			<input type="text" id="category" class="form-control" required name="category" value="<?=$edit['category_name'] ?>">
			<input type="hidden" name="id" value="<?=$edit['category_id'] ?>">
		</div>
	
	
		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Update</button>
        </div>
    </form>







		</div>
</section>
</div>