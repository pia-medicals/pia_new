<script type="application/javascript">
$('#txtEmail').on('change',function(){
    var email   =   $(this).val();
    $.ajax({
        url         :   '<?php echo base_url('user/manageuser/checkuser');?>',
        type        :   'POST',
        data        :   {email:email},
        success:function(data){
            if(data==1){ 
				$('#add-new-btn').attr('disabled',true);
				$('#user-name-ex').show();
			}  
            else{ 
				$('#add-new-btn').attr('disabled',false);
				$('#user-name-ex').hide();
			}
        },
        error:function(){}
    });
})
</script>   