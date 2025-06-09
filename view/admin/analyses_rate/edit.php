

<div class="dashboard_body content-wrapper">
	<?php
$this->alert();
?>
	<h2>Edit Analysis Rate</h2>

    <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">



<?php 

$analysis = $this->Admindb->table_full('analyses');
$customer = $this->Admindb->table_full('users','WHERE user_type_ids = 5');


 ?>
	<div class="form-group">
		<label for="analysis">Analysis</label>
		<select name="analysis" id="analysis" class="form-control" required >
			<option selected disabled>Choose Analysis</option>
				<?php 

				
					foreach ($analysis as $key => $value) {
						if(isset($edit['analysis']) && $edit['analysis'] == $value['id']) $sel = 'selected'; else $sel = '';
						echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
					}
				 ?>
		</select>

			<input type="hidden" name="id" value="<?=$edit['id'] ?>">
	</div>




	<div class="form-group">
		<label for="customer">Customer</label>
		<select name="customer" id="customer" class="form-control" required >
			<option selected disabled>Choose Customer</option>
				<?php 
					foreach ($customer as $key => $value) {
						if(isset($edit['customer']) && $edit['customer'] == $value['id']) $sel = 'selected';  else $sel = '';
						echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
					}
				 ?>
		</select>
	</div>



		<div class="form-group">
			<label for="rate">Rate</label>
			<input type="number" id="rate" class="form-control" required name="rate"value="<?=$edit['rate'] ?>" >
		</div>



		<div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
        </div>
    </form>





</div>