<?php
	function issetecho($var){
		if(isset($var)) echo $var; else echo "";
	}
	function issetecho2($var){
		if(isset($var)) echo $var; else echo "1";
	}
	function issetecho3($var){
		if(isset($var) && $var !="") echo $var; else echo "0";
	}
	FUNCTION h2m($hours) { 
		$t 		= 	explode(".", $hours); 
		$h 		= 	$t[0]; 
		if (isset($t[1])) { 
			$m 	= 	$t[1]; 
		} 
		else { 
			$m 	= 	"0"; 
		} 
		$mm 	= 	$h . " hour(s) and " .$m. " minute(s)";
		return $mm; 
	} 
	$customer 	= 	$this->Admindb->get_by_id('users',$edit['customer'])['name'];
?>
<div class="dashboard_body content-wrapper my_wsheet_edit <?php if($edit_wsheet['status'] == 'Completed') echo 'disable';?>">
	<section class="content">
		<?php $this->alert(); ?>
		<div class="box box-primary fl100">
			<div class="box-header with-border">
				<h2 class="box-title">Worksheet</h2>
			</div>
			<div class="col-md-12">
                <table class="table table-bordered">
                	<tbody>
                		<tr>
                			<td>Accession</td>
                			<td><?=$edit['accession'] ?></td>
                		</tr>
                		<tr>
                			<td>MRN</td>
                			<td><?=$edit['mrn'] ?></td>
                		</tr>
                		<tr>
                			<td>Patient Name</td>
                            <td><?=$edit['patient_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Site Procedure</td>
                            <td><?=$edit['site_procedure'] ?></td>
                        </tr>
                        <tr>
                            <td>Exam Time</td>
                            <td><?=$edit['exam_time'] ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?=$edit['status'] ?></td>
                        </tr>
                        <tr>
                            <td>Priority</td>
                            <td><?=$edit['priority'] ?></td>
                        </tr>
                        <tr>
                            <td>Site</td>
                            <td><?=$edit['site'] ?></td>
                        </tr>
                        <tr>
                            <td>Hospital</td>
                            <td><?=$edit['hospital'] ?></td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td><?php if(isset($edit['webhook_customer']) && !empty($edit['webhook_customer'])) echo $edit['webhook_customer'];?></td>
                		</tr>
		                <tr>
                            <td>Description</td>
                            <td><?php if(isset($edit['webhook_description']) && !empty($edit['webhook_description']))echo $edit['webhook_description'];?></td>
                        </tr>
                        <tr>
                        	<td>TAT</td>
                       		<td><?php if(isset($edit['tat']) && !empty($edit['tat'])) echo $edit['tat'];?></td>
                        </tr>
                        <tr>
                        	<td>Expected Time</td>
                        	<td id="calc_expected_time"><?php echo ($edit_wsheet['expected_time']>0) ? $edit_wsheet['expected_time'].' hour(s)' : '0 hour(s)'; ?></td>
                        </tr>
                        <?php if(!empty($secondcheck)){?>
                        <tr>
                        	<td>Second Check Analyst</td>
                        	<td><?php echo $secondcheck['name']?></td>
                        </tr>
                        <?php } ?>
                	</tbody>
                </table>
			</div>
			<div class="col-md-12">
				<div class="alert alert-info alert-dismissible" id="succes-analyst" style="display:none;"> 
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h4><i class="icon fa fa-info"></i> Alert!</h4>Second check updated successfully.
				</div>
                
                <div class="alert alert-error alert-dismissible" id="error-completed" style="display:none;"> 
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h4><i class="icon fa fa-info"></i> Alert!</h4>Please select the second check details. 
                </div> 
			</div>  
			<form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off" data-form-validate="true" novalidate="novalidate">
				<div class="form-group">
					<label for="analysis">Analyst</label>
					<?php	$analysts = $this->get_all_analysts();?>
					<select  id="analyst" name="analyst" class="form-control analyst_choose" required >
                        <option value disabled selected>Choose analyst</option>
                        <?php foreach($analysts as $key => $value){
                                if ($edit['assignee']== $value['id']) $sel_st = 'selected';
                                else $sel_st = '';	
                                echo '<option value="' . $value['id'] . '" ' . $sel_st . ' >' . $value['name'] . '</option>';
                        }?>
                    </select>		
                    <input type="hidden" name="clario_id" value="<?=$edit['id'] ?>">
					<input type="hidden" name="wsheet_id" value="<?php echo($edit_wsheet['id'])?>">    
					<input type="hidden" name="expected_time" id="expected_time" value="<?php ($edit_wsheet['expected_time']>0) ? $edit_wsheet['expected_time'] : 0; ?>">
				</div>
				<div class="form-group">
					<label for="other">Other</label>
					<input type="text" id="other" class="form-control"  name="other" placeholder="Enter other details" value="<?php issetecho($edit_wsheet['other'])?>">
				</div>
				<div class="form-group">
					<label for="other">Second Check</label>
					<select class="form-control" id="review-analyst" name="txtReviewAnalyst">
                        <option value="0">Select Analyst</option>
                        <?php foreach($analysts as $key => $value){?>
                        <option value="<?=$value['id']?>"<?php if($edit_wsheet['review_user_id']==$value['id']){?> selected="selected" <?php } ?>><?=$value['name']?></option>
                        <?php } ?>
                    </select>
				</div>
				<div class="form-group">
                    <label for="status">Status</label>
                    <?php $status = array('In progress','Under review','Completed','Cancelled','On hold');
                        if(isset($edit_wsheet['status'])) $act_status = $edit_wsheet['status']; else $act_status ='';?>
                    <select name="status" data-rule-required="true" id="status" class="form-control" required>
                    <?php 
                        foreach ($status as $key => $value) {
                            if($value == $act_status) $sel_st = 'selected'; else $sel_st = '';
                            echo '<option value="'.$value.'" '.$sel_st.' >'.$value.'</option>';
                        }
                    ?>
                    </select>
                </div>
				<?php 
					if(isset($edit_wsheet['analyses_ids'])){
						$selected = explode(",",$edit_wsheet['analyses_ids']);
					}
				?>
				<div class="form-group analyses_select">
                    <div class="form-group w100 analyses_per">
                    	<label for="price">Analysis Performed</label>
                    </div>
                    <div class="form-group col-xs-6 analyses_per" id="analyses_performed_new">
                    	<select class="form-control">
                    		<?php foreach ($billing_codes as $key => $value) {
                    			echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                    		}?>    
                    	</select>
                    </div>
                    <div class="form-group col-xs-6 analyses_per">
                    	<button type="button" class="btn btn-warning btn-flat " id="add-ans" name="submit">Add</button>
                    </div>
				</div> 
<?php 
$analyses_ids			=	explode(',',$edit_wsheet['analyses_ids']);
$analyses_performed		=	explode(',',$edit_wsheet['analyses_performed']);
$any_mint				=	explode(',',$edit_wsheet['any_mint']);
?>
                <div class="form-group">
                    <table class="table table-bordered" id="ans-table">
                        <tbody id="ans-table">
                        	<?php if($analyses_ids[0]!=''){ for($i=0;$i<count($analyses_ids);$i++){ ?>
                            <tr id="row-<?php echo $analyses_ids[$i];?>">
                                <td><?php echo $analyses_performed[$i];?>&emsp;<input type="hidden" value="<?php echo $analyses_ids[$i];?>" name="analyses_performed[]"/></td>
                        		<td>Analyses Time(Minute)&nbsp;<input type="number" name="ans_hr[]" placeholder="Analyses Time(Minute)" value="<?php echo $any_mint[$i];?>" /></td>
                        		<td><button type="button" class="btn btn-block btn-danger delete-row" id="delete-<?php echo $analyses_ids[$i];?>">Remove</button></td>
                        	</tr>	
                        	<?php  } } ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group" style="display: none;">
                	<h4>Addon Flows</h4>
                	<div class="multiple_inputs">
					<?php 
                    	if(isset($edit_wsheet['addon_flows']) && $edit_wsheet['addon_flows'] !='' && $edit_wsheet['addon_flows'] !='null' &&  $edit_wsheet['addon_flows'] != null){
                    	foreach (json_decode( $edit_wsheet['addon_flows']) as $key => $value) {
                    ?>
                		<div class="each each_<?=$key ?>"> 
                			<label for="addon_flows_<?=$key ?>">Addon flows for <?=$this->Admindb->get_by_id('analyses',$key)['name'] ?></label> 
                			<input type="number" min="0" data-rule-required="true" id="addon_flows_<?=$key ?>" class="form-control" required="" name="addon_flows_<?=$key ?>" value="<?=$value-1 ?>"> 
                		</div>
                		<?php } }?>
               		 </div>
                </div>
				<div class="form-group">
					<label for="custom_analysis_description">Custom Analysis Description</label>
					<input type="text" id="custom_analysis_description" class="form-control" placeholder="Enter Custom Analysis Description" name="custom_analysis_description" value="<?php issetecho		($edit_wsheet['custom_analysis_description'])?>">
				</div>
				<div class="form-group">
					<label for="other_notes">Other Notes</label>
					<input type="text" placeholder="Enter Other Notes" id="other_notes" class="form-control" name="other_notes" value="<?php issetecho($edit_wsheet['other_notes'])?>">
				</div>
				<?php if($edit_wsheet['status'] != 'Completed'){ ?>
				<div class="form-group ">
					<!--<a  class="btn btn-warning  btn-flat" onclick="getreview('<?php echo $edit['id']?>');";>Review</a>-->
					<button type="submit" class="btn btn-primary btn-flat savebtn" id="submitbtn" name="submit">Submit</button>
				</div>
				<?php } 
				 if(!isset($edit_wsheet['id'])){ ?>
				<div class="form-group remove_assignee">
					<input type="hidden" name="wsheet_id" data-rule-required="true" value="<?php issetecho($edit_wsheet['id'])?>">
                    <input type="submit" class="btn btn-danger " id="submitbtn" name="remove_assign" value="Remove Assignee"/>
                </div>
                <?php } ?>
                <!-- Edit - 25-2-2020 -->
                <?php if (isset($edit_wsheet['id'])): ?>
                    <div class="form-group remove_assignee_worksheet">
                    <input type="hidden" name="wsheet_id" data-rule-required="true" value="<?php issetecho($edit_wsheet['id'])?>">
                    <input type="submit" class="btn btn-danger " id="submitbtn" name="remove_assignee_worksheet" value="Remove Assignee"/>
                </div>
                <?php endif; ?>
            </form>
			<?php if($edit_wsheet['status'] == 'Completed'): ?>
			<form role="Form" method="post" action="" class="" accept-charset="UTF-8" autocomplete="off" data-form-validate="true" novalidate="novalidate">
				<div class="form-group re_assign">
					<button type="submit" class="btn btn-danger" id="submitbtn" name="re_assign">Re Open</button>&nbsp;
					<?php if($rv_status==1){?>
						<button type="submit" class="btn btn-warning" id="re_completed" name="re_completed">Review Completed</button>
					<?php } ?>
				</div>
			</form>
			<?php endif; ?>
		</div>
        </section>
	</div>
</div>
<!-- RC LOAD MODEL -->
<div class="modal modal-info fade" id="modal-info">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Review</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Analyst</label>
                    <select class="form-control" id="review-analyst">
                        <option value="0">Select Analyst</option>
                        <?php foreach($analysts as $key => $value){?>
                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                        <?php } ?>
                    </select>
                    <!--<label>Analyst Time(minutes)</label>
                    <input type="number" name="txtAnalystHours" id="txtAnalystHours" class="form-control"/>
                    <label>Comments</label>
                    <textarea cols="5" rows="5" class="form-control" name="txtComments" id="txtComments"></textarea>
                    <label>Analyst Rating</label>
                    <select id="example-fontawesome" name="analystRating" autocomplete="off">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    </select>-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline" id="review-save">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script src="//<?=ASSET ?>js/jquery.barrating.js"></script>
<script src="//<?=ASSET ?>js/examples.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("select#analyses_performed").change(function(){
	var min = 0;
	var valnew = 0;
	//var hrs = 0;
	$("select#analyses_performed").find("option:selected").each(function() {
		min += parseFloat($(this).attr("rel"));
		//                alert($(this).attr("rel")+' -- '+min);                
	});   
	//  min = parseFloat(min)/60;            
	// min = converttohours(min);           
	if(parseFloat(min)>0){
		valnew = min.toFixed(2);               
		$("#expected_time").val(valnew); 
			$("td#calc_expected_time").text(valnew + " hour(s)");   
		}
		else{
			$("td#calc_expected_time").text(0+ " hour(s)");    
			$("#expected_time").val(0);  
		}
	});
});
function converttohours(min) {
	var num = min;
	var hours = (num / 60);
	var rhours = Math.floor(hours);
	var minutes = (hours - rhours) * 60;
	var rminutes = Math.round(minutes);
	$("#expected_time").val(rhours+'.'+rminutes);
	return rhours + " hour(s) and " + rminutes + " minute(s)";
}
$('#add-ans').on('click',function(){
	var analyses_performed_id		=	$('#analyses_performed_new option:selected').val();
	var analyses_performed_text		=	$('#analyses_performed_new option:selected').text();
	if($('#row-'+analyses_performed_id).length){
		alert('alredy added');
	}
	else{
		var tablerow					=	'<tr id="row-'+analyses_performed_id+'"><td>'+analyses_performed_text+'&emsp;<input type="hidden" value="'+analyses_performed_id+'" name="analyses_performed[]"/></td><td>Analyses Time(Minute)&nbsp;<input type="number" name="ans_hr[]" value="0" placeholder="Analyses Time(Minute)" value=""/></td><td><button type="button" class="btn btn-block btn-danger delete-row" id="delete-'+analyses_performed_id+'">Remove</button></td></tr>';
		$('#ans-table').append(tablerow);
	}
	$('.delete-row').on('click',function(){
		var rowid	=	this.id.split('-');
		//alert(rowid[1]);
		$('#row-'+rowid[1]).remove();
	});
});	  
$('.delete-row').on('click',function(){
	var rowid	=	this.id.split('-');
	//alert(rowid[1]);
	$('#row-'+rowid[1]).remove();
});
function getreview(reviewid){
	$('#succes-analyst').hide();
	$('#modal-info').modal('show');
	$.ajax({
		url   : '/ajax/getreview',
		type  : 'POST',
		data  : {reviewid:reviewid},
		success:function(data){
		//alert(data);
			var jsonData    =   JSON.parse(data);
			//alert(jsonData[0].review_user_id);
			if(jsonData[0].review_user_id!=''){
				$('#txtAnalystHours').val(jsonData[0].second_analyst_hours);
				$('#review-analyst').val(jsonData[0].review_user_id);
				$('#txtComments').val(jsonData[0].second_comment);
				$('#example-fontawesome').val(jsonData[0].second_check_rate);
			}
		},
		error:function(){}
	});
	$('#review-save').on('click',function(){
		var analyst         = $('#review-analyst option:selected').val();
		var analystHours    = $('#txtAnalystHours').val();
		var comments        = $('#txtComments').val();
		var rate            = $('#example-fontawesome').val();
		//alert(analyst);
		if(analyst!=0){
			$.ajax({
				url   : '/ajax/userreview',
				type  : 'POST',
				data  :{
						analyst         : analyst,
						reviewid        : reviewid,
						analystHours    : analystHours,
						comments        : comments,
						rate            : rate
				},
				success:function(data){
					//alert(data);
					if(data==1){
						$('#review-analyst').val(0);
						$('#txtAnalystHours').val('');
						$('#txtComments').val('');
						$('#modal-info').modal('hide');
						$('#succes-analyst').show(); 
					}
					else{
						$('#review-analyst').val(0);
						$('#txtAnalystHours').val('');
						$('#txtComments').val('');
						$('#modal-info').modal('hide');
						$('#error-analyst').show();
					}       
				},
				error:function(){}
			});
		}
		else{
			alert('please select the analyst');
		}
	})
}
$('#status').on('change',function(){
	var stausVal		=	$(this).val();
	var secondCheck		=	$('#review-analyst option:selected').val();
	//alert(secondCheck);
	if((secondCheck==0)&&(stausVal=='Completed')){
		$('.savebtn').hide();
		$('#error-completed').show();
	}
	else{
		$('.savebtn').show();
		$('#error-completed').hide();
	}
});
$('#review-analyst').on('change',function(){
	var secondCheck		=	$(this).val();
	var stausVal		=	$('#status option:selected').val();
	//alert(secondCheck);
	if((secondCheck==0)&&(stausVal=='Completed')){
		$('.savebtn').hide();
		$('#error-completed').show();
	}
	else{
		$('.savebtn').show();
		$('#error-completed').hide();
	}
});
</script>