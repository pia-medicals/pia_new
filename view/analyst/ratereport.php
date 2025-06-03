<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
.checked {  color: orange;}
</style>
<div class="dashboard_body content-wrapper">
    <section class="content">
    	<?php //print_r($analyst); ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    	<h2 class="box-title">Performance Reports</h2>
                    </div>
                    <div class="col-md-12 form_time">
                        <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="admin_form inline-block">
           					<input type="text" name="daterange" value="" />
                            <input type="hidden" name="txtStart" id="txtStart" value=""/>
                            <input type="hidden" name="txtEnd" id="txtEnd" value=""/>
            				<button type="submit" class="btn btn-primary btn-flat">Filtter</button>
						 </form>
          		</div>
                	<!-- /.box-header -->
                    <div class="box-body">
                        <table id="admin_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Analyst</th>
                                    <th>Total Study</th>
                                    <th>Progress</th>
                                    <th>Review</th>
                                    <th>Completed</th>
                                    <th>Hold</th>
                                    <th>Cancel</th>
                                    <th>
                                        <b>1<span class="fa fa-star checked"></span></b>
                                    </th>
                                    <th>
                                        <b>2<span class="fa fa-star checked"></span></b>
                                    </th>
                                    <th>
                                        <b>3<span class="fa fa-star checked"></span></b>
                                    </th>
                                    <th>
                                        <b>4<span class="fa fa-star checked"></span></b>
                                    </th>
                                    <th>
                                        <b>5<span class="fa fa-star checked"></span></b>
                                    </th>
                                    <th>Avg Rate<span class="fa fa-star checked"></span></th>
                                </tr>
                            </thead>
                        	<tbody>
                        		<?php foreach($analyst as $value){ if($value['name']!=''){?>
                                	<tr>	
                                        <td><?=$value['name']?></td>
                                        <td><?php echo (!empty($value['totalStudy']))?$value['totalStudy']:'-';?></td>
                                        <td><?php echo (!empty($value['progress']))?$value['progress']:'-';?></td>
                                        <td><?php echo (!empty($value['review']))?$value['review']:'-';?></td>
                                        <td><?php echo (!empty($value['completed']))?$value['completed']:'-';?></td>
                                        <td><?php echo (!empty($value['cancel']))?$value['cancel']:'-';?></td>
                                        <td><?php echo (!empty($value['hold']))?$value['hold']:'-';?></td>
                                        <td><a href="javascript:void(0)" class="rate-data" id="<?php echo '1-'.$value['analyst'];?>"><?php echo (!empty($value['oneStar']))?'<span class="badge bg-red">'.$value['oneStar'].'<span class="fa fa-star checked"></span></span>':'-';?></a></td>
                                        <td><a href="javascript:void(0)" class="rate-data" id="<?php echo '2-'.$value['analyst'];?>"><?php echo (!empty($value['twoStar']))?'<span class="badge bg-red">'.$value['twoStar'].'<span class="fa fa-star checked"></span></span>':'-';?></a></td>
                                        <td><a href="javascript:void(0)" class="rate-data" id="<?php echo '3-'.$value['analyst'];?>"><?php echo (!empty($value['threeStar']))?'<span class="badge bg-light-blue">'.$value['threeStar'].'<span class="fa fa-star checked"></span></span>':'-';?></a></td>
                                        <td><a href="javascript:void(0)" class="rate-data" id="<?php echo '4-'.$value['analyst'];?>"><?php echo (!empty($value['fourStar']))?'<span class="badge bg-yellow">'.$value['fourStar'].'<span class="fa fa-star checked"></span></span>':'-';?></a></td>
                                        <td><a href="javascript:void(0)" class="rate-data" id="<?php echo '5-'.$value['analyst'];?>"><?php echo (!empty($value['fiveStar']))?'<span class="badge bg-green">'.$value['fiveStar'].'<span class="fa fa-star checked"></span></span>':'-';?></a></td>
                                        <td>
										<?php 
											$rate		= (!empty($value['avgeRate']))?round($value['avgeRate'],2):'';
											if($rate!=''){
												$ratePre	= $rate*20;
												if($ratePre<40){
													echo '<span class="badge bg-red">'.round($ratePre,2).'</span><div class="progress progress-xs"><div class="progress-bar progress-bar-danger" style="width: '.round($ratePre,2).'%"></div></div>';
												}
												if(($ratePre<60)&&($ratePre>=40)){
													echo '<span class="badge bg-light-blue">'.round($ratePre,2).'</span><div class="progress progress-xs progress-striped active"><div class="progress-bar progress-bar-primary" style="width: '.round($ratePre,2).'%"></div></div>';
												}
												if(($ratePre<80)&&($ratePre>=60)){
													echo '<span class="badge bg-yellow">'.round($ratePre,2).'</span><div class="progress progress-xs"><div class="progress-bar progress-bar-yellow" style="width: '.round($ratePre,2).'%"></div></div>';
												}
												if(($ratePre<100)&&($ratePre>=80)){
													echo '<span class="badge bg-green">'.round($ratePre,2).'</span><div class="progress progress-xs progress-striped active"><div class="progress-bar progress-bar-success" style="width: '.round($ratePre,2).'%"></div></div>';
												}
											}
											else{
												echo '-';
											}
										?>
                                        </td>
                                    </tr>
                                <?php }} ?>	
                        	</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- RC LOAD MODEL -->
<div class="modal modal-info fade" id="modal-info">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            	<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title">Review Details</h4>
            </div>
            <div class="modal-body">
            	<table class="table table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Second Check User</th>
                            <th>Accession</th>
                            <th>MRN No</th>
                            <th>Second Check Study</th>
                            <th>Second Check Date</th>
                            <th>Second Check Time</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody id="rate-comment">
                    </tbody>
              	</table>
			</div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    	<!-- /.modal-content -->
    </div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
	  $('#txtStart').val(start.format('YYYY-MM-DD'));
	  $('#txtEnd').val(end.format('YYYY-MM-DD'));
    //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
/******************************** RC ******************************************/
/*----------------------- ANALYST RATE DETAILS JSON ---------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  09-05-2019 
------------------------------------------------------------------------------*/ 
$('.rate-data').on('click',function(){
	var rateData	=	this.id;
	var dataVal		=	rateData.split('-');
	$.ajax({
        url         :   '/ajax/ratedetails',
        type        :   'POST',
        data        :   {
                reviewid    :   dataVal[1],
                rate        :   dataVal[0]
        },
        success     :   function(data){
			//alert(data);
            var rowJson     =   "";
            var i           =   1;
            $('#modal-info').modal('show');
	        $('#rate-comment').html('');
            $.each(JSON.parse(data), function(key,value){
                rowJson     =   rowJson+'<tr><td>'+i+'</td><td>'+value.name+'</td><td>'+value.accession+'</td><td>'+value.mrn+'</td><td>'+value.site+'</td><td>'+value.second_check_date+'</td><td>'+value.second_analyst_hours+'</td><td>'+value.second_comment+'</td></tr>';
                i++;
            });
            $('#rate-comment').html(rowJson);
        },
        error       :   function(){}
    })
});
</script>























