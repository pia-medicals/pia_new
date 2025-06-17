   <div class="dashboard_body content-wrapper">


   <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <?php $this->alert(); ?>
          <div class="box">
            <div class="box-header">
              <h2 class="box-title">All Reports</h2>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="admin_table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                      <tbody>
                      <?php
                        if(isset($all_reports) && !empty($all_reports)){
                        foreach ($all_reports as $key => $reports) { ?>
                            <tr>
                              <td><?php echo $key+1; ?></td>
                              <td data-label="Name">Report-<?php echo $key+1; ?></td>
                              <td><?php echo $reports['date']; ?></td>
                              <td data-label="Action" class="text-center">
                                <a href="<?=SITE_URL.'/admin/report?file_name='.$reports['file_name'] ?>" class="edit_link">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a> 
                              </td>
                            </tr>
                      <?php  } } ?>
                      </tbody>
              </table>
            </div>
          </div>
</div>
</div>
</section>


</div>


<script type="text/javascript">


</script>