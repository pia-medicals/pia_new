JJ<div class="dashboard_body content-wrapper admin_dash">
    <?php //print_r($analyst_amount_per_month);die; ?>

    <?php //$this->debug($cases_by_analysesTypes); die;?>
    <?php //$fromdate	=	date('Y-m-d',strtotime($result_to));;echo " to "; echo $result_to; ?>
    <!-- Small boxes (Stat box) -->
   

    <section class="content">
        <div class="row widget_box analyst_dash">
            <!-- Date range -->
            <div class="col-lg-12 col-xs-12">
                <div class="col-md-8 col-lg-8 col-xs-8">
                    <?php if ($result_from && $result_to) { ?>
                        <h4>Showing results from <?php
                            echo $result_from;
                            echo " to ";
                            echo $result_to;
                            ?></h4>
                    <?php } ?>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-lg-12 col-xs-12">
            	<?php //print_r($total_analyst_amount_per_month);
?>
                <div class="col-md-4 col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner text-capitalize">
                           <h3 style="visibility:hidden;">DASHBOARD</h3>
                            <p>DASHBOARD</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <a href="<?=SITE_URL ?>/admin" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
              
              
            </div>
            
            
        </div>  
        <!-- /.row -->
        <!--        Line-->
        <div class="row dash-icons">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <hr class="box box-success mb-0" />
                </div>
            </div>
        </div>
        <!--        Line-->

       
        <!--        <div class="row dash-icons">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analyst Hours</span>
                                    <span class="info-box-number"><?php echo $analyst_hours; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                        /.col 
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Customers</span>
                                    <span class="info-box-number"><?= $customer_count; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-star-o"></i></span>
                                <div class="info-box-content text-capitalize">
                                    <span class="info-box-text">Total Analysts</span>
                                    <span class="info-box-number"><?= $analyst_count; ?></span>
                                </div>
                                /.info-box-content 
                            </div>
                            /.info-box 
                        </div>
                        /.col 
                    </div>
                </div>-->
    </section>

    <div class="clearfix"></div>

    <!-- weekly count chart -->
   
   
    
   
   
            <!-- /.box-body -->
        </div>       
    </div>
    <!-- / END Analyst Performance -->  
    
    
    
    
    
    
    
    
    <style>
        .search-button-dashboard {
            position: absolute;
            top: 24px;
            bottom: 0;
        }
    </style>
</div>