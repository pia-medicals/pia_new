

<div class="dashboard_body content-wrapper">
	<section class="content">
		<?php $this->alert(); ?>
          <div class="box box-primary">
	<div class="box-header with-border">
              <h2 class="box-title">Edit User</h2>
            </div>



    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
		<div class="form-group">
			<label for="fname">Name</label>
			<input type="text" id="name" class="form-control" required name="name" value="<?=$edit->user_name ?>">
			<input type="hidden" name="id" value="<?=$edit->user_id ?>">
		</div>
		<div class="form-group">
			<label for="emailaddr">Email Address</label>
			<input type="email" id="emailaddr" class="form-control" required name="email" placeholder="Example: john.doe@gmail.com" value="<?=$edit->email ?>">
        </div>


		<!-- <div class="form-group">
			<label for="group">Profile Picture</label>
			<div class="">
	            <input type="file"  name="profile_picture" id="profile_picture" >
	        </div>
	    </div> -->       
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" id="password"  class="form-control" name="password" placeholder="">
        </div>
		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Submit</button>
        </div>
    </form>



        </div>
    </section>

</div>