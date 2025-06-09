

<div class="dashboard_body content-wrapper">
  <section class="content">
    <?php $this->alert(); ?>
    <div class="box box-primary">
        <div class="box-header with-border">
                    <h2 class="box-title">Add Item</h2>
        </div>
        <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">

      		<div class="form-group">
      			<label for="count_an">Upload XML File</label>
                 
                  <input class="form-control" placeholder="Upload XML" type="file" accept="text/xml" name="uploadxml" id="uploadxml">
          </div> 
          <div class="form-group ">
      			<button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Upload</button>
          </div>
        </form>
  </div>
    </section>
</div>

