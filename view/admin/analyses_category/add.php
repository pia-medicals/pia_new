

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
              <h2 class="box-title">Add Analyses Category</h2>
            </div>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
		<div class="form-group">
			<label for="category">Analyses Category Name</label>
			<input type="text" id="category" class="form-control" required name="category" placeholder="Enter Analyses Category Name">
		</div>

		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add Analyses Category</button>
        </div>
    </form>





		</div>
</section>
</div>