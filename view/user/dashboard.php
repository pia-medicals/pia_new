
<div class="dashboard_body content-wrapper">
	<section class="content">
		      <div class="row widget_box analyst_dash">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $jobs_count;?></h3>

              <p>Total Jobs Open</p>
            </div>
            <div class="icon">
              <i class="fa fa-file"></i>
            </div>
            <a href="<?=SITE_URL ?>/dashboard/open_work_sheets" class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $jobs_open;?></h3>

              <p>Total Jobs Assigned</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $jobs_in_progress;?></h3>

              <p>Jobs In Progress</p>
            </div>
            <div class="icon">
              <i class="fa fa-forward"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $jobs_complete;?></h3>

              <p>Jobs Completed</p>
            </div>
            <div class="icon">
              <i class="fa fa-suitcase"></i>
            </div>
            
          </div>
        </div>
        <!-- ./col -->
      </div>
      <div class="row widget_box analyst_dash">
      	<div class="col-md-3 col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner text-capitalize">
                            <h3><?= $checkdone; ?></h3>
                            <p>Total Number Of Second Check Done</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-3 col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner text-capitalize">
                            <h3><?= $checknotdone; ?></h3>
                            <p>Total Number Of Second Check Not Done</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-minus-o"></i>
                        </div>
                    </div>
                </div>
      </div>
      <!-- /.row -->

    
		<?php $this->alert(); ?>
          <div class="box box-primary fl100">
          <div class="col-md-12 ">
	<div class="box-header with-border">
          <!--     <h2 class="box-title">Hi <?//=$user->name ?>, Welcome</h2> -->
            </div>

				<table class="table table-bordered">
					<tr>
						<th>Time Logged</th>
						<td>HH:MM</td>
					</tr>
					<tr>
						<th>Analyst Hours</th>
						<td><?= round($analyst_total_hours,2); ?></td>
					</tr>
				
				</table>
				<br>
	        </div>
	        </div>
    </section>
</div>