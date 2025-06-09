

<div class="dashboard_body">
	<?php
$this->alert();
?>
	<h2>add user</h2>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
		<div class="form-group">
			<label for="fname">Name</label>
			<input type="text" id="name" class="form-control" required name="name" >
		</div>
		<div class="form-group">
			<label for="emailaddr">Email Address</label>
			<input type="email" id="emailaddr" class="form-control" required name="email" placeholder="Example: john.doe@gmail.com">
        </div>
        <div class="form-group">
			<label for="group">User Type</label>
			<select id="group" class="form-control" required name="group_id">
				<option selected disabled>Choose Type</option>
				<option value="1">Super Admin</option>
				<option value="2">Manager</option>
				<option value="3">Analyst</option>
				<option value="4">Patient</option>
				<option value="5" style="display: none;">Customer</option>
			</select>
		</div>        
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" id="password" required class="form-control" name="password" placeholder="">
        </div>
		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add User</button>
        </div>
    </form>





</div>