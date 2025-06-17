




<div class="dashboard_body content-wrapper">
<section class="content">
		<?php $this->alert(); ?>
          <div class="box box-primary">
	<div class="box-header with-border">
              <h2 class="box-title">Add Customers</h2>
            </div>


    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
		<div class="form-group">
			<label for="fname">Name</label>
			<input type="text" id="name" class="form-control" placeholder="Enter Name" required name="name" >
		</div>
		<div class="form-group">
			<label for="emailaddr">Email Address</label>
			<input type="email" id="emailaddr" class="form-control" required name="email" placeholder="Example: john.doe@gmail.com">
        </div><!-- 
        <div class="form-group">
			<label for="group">User Type</label>
			<select id="group" class="form-control" required name="group_id">
				<option selected disabled>Choose Type</option>
				<?php 
				$sel = '';
					foreach ($select_array as $key => $value) {
						echo '<option value="'.$key.'"  >'.$value.'</option>';
					}
				 ?>
			</select>
		</div>  -->   
<!-- 


		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" id="password" required class="form-control" name="password" placeholder="Enter Password">
        </div> -->

<input type="hidden" id="password" required class="form-control" name="password" value="pwd4cust!23">

		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
        </div>
    </form>


        </div>
    </section>


</div>