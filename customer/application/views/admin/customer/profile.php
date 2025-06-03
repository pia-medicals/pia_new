<?php
if(!empty($customer['ACL_Customer_Image_Thumb'])){
	$profileImage   =   base_url('static/upload/user/thumb/'.$customer['ACL_Customer_Image_Thumb']);
}
else{
	$profileImage   =   ($customer['ACL_Gender']=='male')?base_url('static/admin/default/img_avatar.png'):base_url('static/admin/default/img_avatar2.png'); 
}
//var_dump($discountPrice);
$date	=	explode(' ',$customer['ACL_User_Add_On']);
$json	=	json_decode($customer['user_meta'],true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
    	<h2>Customer Profile</h2>
    	<ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo base_url('dashboard')?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Profile</strong>
            </li>
    	</ol>
    </div>
    <div class="col-sm-8">
    	<div class="title-action">
            <a href="<?php echo base_url('dashboard')?>" class="btn btn-primary">Dashboard</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-md-6 widget lazur-bg">
			<div class="profile-image">
				<img src="<?php echo $profileImage; ?>" class="rounded-circle circle-border m-b-md" alt="profile">
			</div>
			<div class="profile-info">
				<div class="">
					<div>
						<h2 class="no-margins"><?php echo ucfirst($customer['ACL_Fisrt_Name'].' '.$customer['ACL_Last_Name']);?></h2>
						<h4>About Customer</h4>
						<small><?php echo $customer['ACL_About_Customer'];?></small>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 widget">
			<table class="table">
				<tbody>
					<tr>
						<td><strong><i class="fa fa-envelope"></i>&emsp;</strong> <?php echo $customer['ACL_Email']?></td>
						<td><strong><i class="fa fa-phone"></i>&emsp;</strong><?php echo $customer['ACL_Phone_Number']?></td>
					</tr>
					<tr>
						<td><strong><i class="fa fa-barcode"></i>&emsp;</strong> <?php echo $json['customer_code']?></td>
						<td><strong><i class="fa fa-calendar"></i>&emsp;</strong> <?php echo $date[0]?></td>
					</tr>
					<tr>
						<td colspan="2"><strong><i class="fa fa-home"></i>&emsp;</strong> <?php echo $json['address']?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
            <div class="col-lg-3">
                <div class="widget style1 red-bg">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="fa fa-trophy fa-5x"></i>
                            </div>
                            <div class="col-8 text-right">
                                <span> Maintenance Fees <strong>[<?php echo  ucfirst(!empty($maintenanceFee[0]['maintenance_fee_type'])?$maintenanceFee[0]['maintenance_fee_type']:'Nothing' )?>]</strong></span>
                                <h2 class="font-bold">$ <?php echo (!empty($maintenanceFee[0]['maintenance_fee_amount']))?$maintenanceFee[0]['maintenance_fee_amount']:0;?></h2>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-4">
                            <i class="fa fa-area-chart fa-5x"></i>
                        </div>
                        <div class="col-8 text-right">
                            <span> Total <strong>Analysis Added</strong> </span>
                            <h2 class="font-bold"><?php echo count($analyeseRate);?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-4">
                            <i class="fa fa-newspaper-o fa-5x"></i>
                        </div>
                        <div class="col-8 text-right">
                            <span> Total <strong>Subscribed</strong> </span>
                            <h2 class="font-bold"><?php echo count($subscription);?></h2>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-lg-3">
                <div class="widget style1 lazur-bg">
                    <div class="row">
                        <div class="col-4">
                            <i class="fa fa-envelope-o fa-5x"></i>
                        </div>
                        <div class="col-8 text-right">
                            <span> Total <strong>Studies</strong> </span>
                            <h2 class="font-bold"><?php echo (isset($studies) && !empty($studies)) ? $studies : 0;?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	
 	<div class="row">
		<div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                	<h5>Analysis Rates</h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover no-margins">
                        <thead>
                        	<tr>
                                <th>S.NO.</th>	
                                <th>ANALYSIS</th>
								<th>CODE</th>
                                <th>CUSTOMER DESCRIPTION</th>
                                <th>RATE</th>
								<!--<th>ACTION</th>-->
                            </tr>
                        </thead>
                        <tbody>
							<?php $i=1; foreach($analyeseRate as $value){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $value['analysis_description']?></td>
								<td><i class="fa fa-barcode"></i> <?php echo $value['code']?></td>
								<td><?php echo $value['custom_description']?></td>
								<td><i class="fa fa-dollar"></i> <?php echo number_format($value['rate'])?></td>
								<!--<td>
                                	<a class="btn btn-white btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa fa-gears"></i></a>
                                	<a class="btn btn-warning btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa-search"></i></a>
                                </td>-->
							</tr>
							<?php $i++;} ?>
						</tbody>
					</table>
				</div>
				</div>		
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                	<h5>Monthly Quantity Discount Pricing</h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover no-margins">
                        <thead>
                        	<tr>
                                <th>S.NO.</th>	
                                <th>FROM</th>
								<!--<th>PROGRESS</th>-->
								<th>TO</th>
                                <th>PERCENTAGE</th>
								<!--<th>ACTION</th>-->
                            </tr>
                        </thead>
                        <tbody>
							<?php $i=1; foreach($discountPrice as $value){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $value['minimum_value']?></td>
								<!--<td>
									<div class="progress">
										<div class="progress-bar progress-bar-striped progress-bar-warning" style="width: 50%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>	
								</td>-->
								<td><?php echo $value['maximum_value']?></td>
								<td><?php echo $value['percentage']?> <i class="fa fa-percent"></i></td>
								<!--<td>
                                	<a class="btn btn-white btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa fa-gears"></i></a>
                                	<a class="btn btn-primary btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa-paper-plane"></i></a>
                                </td>-->
							</tr>
							<?php $i++;} ?>
						</tbody>
					</table>
				</div>
				</div>		
		</div>	
		<div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                	<h5>Subscription Analysis</h5>
                	<div class="ibox-tools">
                		<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                	</div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover no-margins">
                        <thead>
                        	<tr>
                                <th>S.NO.</th>	
                                <th>ANALYSIS</th>
								<th>COUNT</th>
								<!--<th>ACTION</th>-->
                            </tr>
                        </thead>
                        <tbody>
							<?php $i=1; foreach($subscription as $value){ ?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo (strlen($value['name'])>74)?substr($value['name'],0,70).'....':$value['name'];?></td>
								<td><i class="fa fa-microchip"></i> <?php echo $value['count']?></td>
								<!--<td>
                                	<a class="btn btn-white btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa fa-gears"></i></a>
                                	<a class="btn btn-success btn-bitbucket btn-xs" href="javascript:void(0)"><i class="fa fa-plug"></i></a>
                                </td>-->
							</tr>
							<?php $i++;} ?>
						</tbody>
					</table>
				</div>
				</div>		
		</div>
	</div>
		
	
	
		
	
</div>