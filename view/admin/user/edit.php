

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
              <h2 class="box-title">Edit User</h2>
            </div>
    <form role="Form" method="post" action="<?=SITE_URL?>/admin/user" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group">
			<label for="fname">Name</label>
			<input type="text" id="name" class="form-control" required name="name" value="<?=$edit['user_name'] ?>">
			<input type="hidden" name="id" value="<?=$edit['user_id'] ?>">
		</div>
		<div class="form-group">
			<label for="emailaddr">Email Address</label>
			<input type="email" id="emailaddr" class="form-control" required name="email" placeholder="Example: john.doe@gmail.com" value="<?=$edit['email'] ?>">
        </div>

        <div class="form-group">
            <input type="checkbox" name="active" <?php if($edit['is_active'] == true) echo "checked"; ?> id="active"  autocomplete="off" />
            <div class="btn-group">
                <label for="active" class="checkbox_left btn btn-default">
                    <span class="fa fa-check" aria-hidden="true"></span>
                    <span> </span>
                </label>
                <label for="active" class="btn btn-default active">
                    Account Status
                </label>
            </div>
        </div>

        <div class="form-group">
			<label for="group">User Type</label>
			<select id="group" class="form-control" required name="group_id">

				<?php 
				$sel = '';
					foreach ($select_array as $key => $value) {
						if($edit['user_type_ids'] == $key) $sel = 'selected'; else $sel = '';
						echo '<option value="'.$key.'" '.$sel.' >'.$value.'</option>';
					}
				 ?>

			</select>
		</div> 
		<!-- <div class="form-group">
			<label for="group">Profile Picture</label>
			<div class="">
	            <input type="file" required name="profile_picture" id="profile_picture" >
	        </div>
	    </div>  -->      
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" id="password"  class="form-control" name="password" placeholder="Enter new password">
        </div>
		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Submit</button>
        </div>
    </form>







		</div>
</section>
</div>