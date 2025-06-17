<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
		<div class="box box-primary">
			<div class="box-header with-border">
			  <h2 class="box-title">Add Study</h2>
			</div>

			<form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">

				<div class="form-group">
					<label for="accession">Accession</label>
					<input type="text" id="accession" name="accession" required class="form-control" placeholder="Accession">
				</div> 		
				  
			    <div class="form-group">
					<label for="mrn">MRN</label>
					<input type="text" id="mrn" name="mrn" required class="form-control" placeholder="MRN">
			    </div>		 

				 <div class="form-group">
					<label for="patientname">Patient Name</label>
					<input type="text" id="patientname" name="patientname" required class="form-control" placeholder="Patient Name">
				 </div> 		  
				  
				<div class="form-group">
					<label for="examtime">Exam Time</label>
					<input type="text" id="examtime" name="examtime"  class="form-control" placeholder="Exam Time">
				</div>	
				
				<div class="form-group">
					<label for="description">Site</label>
					<input type="text" id="site" name="site"  required class="form-control" placeholder="Site">
				</div>			

				<div class="form-group">
					<label for="count_an">Customer</label>
					<?php
					//$sites = $this->Admindb->table_full('Users', 'WHERE group_id = 5');
					 /* $sites = $this->get_customers_with_time_id(); */

					?>

					<!--<select  id="customers" name="customer" class="form-control customers_choose" required >
						<option value disabled selected>Choose customer</option>
							  <?php
						/* foreach($sites as $key => $value){
							echo '<option value="' . $value[0]['id'] . '"  >' . $value[0]['name'] . '</option>';
						} */
						?>
					</select>-->
					<input type="text" id="customer" name="customer" required class="form-control" placeholder="Customer">

				</div> 
				
				<div class="form-group">
					<label for="description">Description</label>
					<input type="text" id="description" name="description" required class="form-control" placeholder="Description">
				</div> 				

				<div class="form-group ">
					<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Add</button>
				</div>
			</form>
        </div>
    </section>
</div>