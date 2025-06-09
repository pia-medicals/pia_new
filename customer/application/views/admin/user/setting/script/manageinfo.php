<script type="application/javascript">
/******************************** RC ******************************************/
/*----------------------- UPDATE BASIC INFO -----------------------------------
        @CREATE DATE                 :  19-07-2019     
------------------------------------------------------------------------------*/
$('#form-basic-info').on('submit',function(event){
    var firstName    	=   $('#txtFirstName').val(); 
	var lastName    	=   $('#txtLastName').val();
    var mobile      	=   $('#txtMobile').val(); 
    var email       	=   $('#txtEmail').val(); 
	var genter			=	$("input[name='txtGenter']:checked").val();
	var about			=	$('#txtAbout').val();
    $.ajax({
        url     :   '<?php echo base_url('user/setting');?>',
        type    :   'POST',
        data    :   {
            firstName    	:   firstName,
			lastName		:	lastName,
            mobile      	:   mobile,
            email       	:   email,
			genter			:	genter,
			about			:	about
        },
        success 			: function(data){
            if(data==1){
                $('#basic-info-error').hide();   
                $('#basic-info-success').show();   
            }
            else{
                $('#basic-info-success').hide();   
                $('#basic-info-error').show();     
            }
        },
        error:function(){}
    });
    event.preventDefault();
})
/******************************** RC ******************************************/
/*----------------------- OLD PASSWORD CHECKING -------------------------------
        @CREATE DATE                 :  19-07-2019    
------------------------------------------------------------------------------*/
$('#txtOldPassword').on('change',function(){
    var oldPassword     =   $(this).val(); 
    $.ajax({
        url     :   '<?php echo base_url('user/setting/passwordcheck');?>',
        type    :   'POST',
        data    :   {oldPassword:oldPassword},
        success:function(data){
            if(data==1){
                $('#add-reset').attr('disabled',false);
                $('#old-password-error').hide()
            }
            else{
                $('#add-reset').attr('disabled',true)
                $('#old-password-error').show();
            }
        },
        error:function(){}
    });
});  
/******************************** RC ******************************************/
/*----------------------- PASSWORD RESET --------------------------------------
        @CREATE DATE                 :  19-07-2019     
------------------------------------------------------------------------------*/
$('#form-rest-password').on('submit',function(event){
    var paswword    =   $('#txtPassword').val();
    $.ajax({
       url      :   '<?php echo base_url('user/setting/passwordreset'); ?>',
        type    :   'POST',
        data    :   {paswword:paswword},
        success:function(data){
            if(data==1){
                $('#old-password-error').hide();
                $('#password-success').show();  
                $('#txtPassword').val('');
                $('#txtOldPassword').val('');
                $('#password_two').val('');
                $('#add-reset').attr('disabled',false);
            }
            else{
                $('#password-error').show();   
            }
        },
        error:function(){}
    });
    event.preventDefault();
});
/******************************** RC ******************************************/
/*----------------------- UPLOAD PROFILE IMAGE --------------------------------
        @CREATE DATE                 :  20-07-2019     
------------------------------------------------------------------------------*/
$('#upload-image').on('submit',function(event){
	$.ajax({
		url			:	'<?php echo base_url('user/setting/uploadimage'); ?>',
		type		:	'POST',
		data		:	new FormData(this),
		contentType	: 	false,
		cache		: 	false,
		processData	:	false,
		success		:	function(data){
			var responce	=	JSON.parse(data);
			if(responce.error==1){
				var uploadPath	=  "<?php echo base_url('static/upload/user/thumb/')?>"+responce.thumb;	
				$('.profile-image').html('<img src="'+uploadPath+'" class="rounded-circle circle-border m-b-md" alt="profile" width="128" height="128">'); 
				$('#txtImage').val('');
				$('#upload-success').show();
			}
			else{
				$('#upload-error').show();
				$('#uploadimage-error').html(responce.error);
			}
		},
		error:function(){}
	});
	event.preventDefault();
});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    
    
    
    
    
</script>