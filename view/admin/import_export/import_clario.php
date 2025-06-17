<div class="dashboard_body content-wrapper">
  <?php
$this->alert();
//print_r($dicom_details_list);
?>
  <h2>Import</h2>

<form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">

       <div class="col-lg-6">
       	<div class="form-group">
			<label for="group">Upload file</label>
			<div class="">
	            <input type="file" required name="up" id="up" >
	        </div>
	    </div>  
	    <div class="form-group ">
			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Submit</button>
        </div>
    </form>
       </div>

       <div class="col-lg-6">
       		<div class="form-group">
			<label for="group">Download demo file</label>
			<div class="">
	           <a href="<?=SITE_URL ?>/assets/uploads/upload_test.xlsx" > Test File</a>
	        </div>
	    </div>  

       </div>
		     

		



</div>