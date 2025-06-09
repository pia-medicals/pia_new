

<div class="dashboard_body content-wrapper">
	<section class="content">
		<?php $this->alert(); ?>
          <div class="box box-primary">
	<div class="box-header with-border">
              <h2 class="box-title">Edit Analysis</h2>
            </div>



    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">


		<div class="form-group">
			<label for="name">Analysis Name</label>
			<input type="text" id="name" class="form-control" value="<?=$edit['analysis_name'] ?>" required name="name" >
			<input type="hidden" name="id" value="<?=$edit['analysis_id'] ?>">
		</div>


		

		<div class="form-group">
			<label for="price">Price </label>
			<input type="number" id="price" class="form-control" required name="price" value="<?=$edit['analysis_price'] ?>" >
		</div>

         <div class="form-group">
                    <label for="minimum_time">Minimum Time</label>
                    <input type="number" id="minimum_time" class="form-control" required name="minimum_time" maxlength="5" value="<?=$edit['time_to_analyze'] ?>">
                </div>
        
		<div class="form-group">
			<label for="description">Description</label>
			<textarea id="description" class="form-control" required name="description"><?=$edit['analysis_invoicing_description'] ?></textarea>
		</div>




		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Update</button>
        </div>
    </form>




        </div>
    </section>

</div>