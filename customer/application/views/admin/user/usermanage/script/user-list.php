<script src="<?php echo base_url('static/admin/js/plugins/dataTables/datatables.min.js');?>"></script>
<script src="<?php echo base_url('static/admin/js/plugins/dataTables/dataTables.bootstrap4.min.js');?>"></script>
<script src="<?php echo base_url('static/admin/js/plugins/sweetalert/sweetalert.min.js');?>"></script>
<script type="application/javascript">
/******************************** RC ******************************************/
/*----------------------- USER LIST DATA TABLE --------------------------------
        @CREATE DATE                 :  19-03-2019     
------------------------------------------------------------------------------*/
$(document).ready(function(){
    $('.dataTables-example').DataTable({
        pageLength: 10,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend    : 'copy'},
            {extend     : 'csv'},
            {extend     : 'excel', title: 'ExampleFile'},
            {extend     : 'pdf', title: 'ExampleFile'},
            {extend     : 'print',
                customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ]
    });
});
/******************************** RC ******************************************/
/*----------------------- USER BLOCK ------------------------------------------
        @CREATE DATE                 :  18-07-2019     
------------------------------------------------------------------------------*/
$('.change-status').on('click',function(){
    var arrayId     =   this.id.split('-');
    var status      =   $(this).attr('data-status');
    swal({
        title               :   "Are you sure?",
        text                :   "Are you sure you want to change the status!",
        type                :   "warning",
        showCancelButton    :   true,
        confirmButtonColor  :   "#DD6B55",
        confirmButtonText   :   "Yes, Change Status!",
        cancelButtonText    :   "No, Cancel !",
        closeOnConfirm      :   false,
        closeOnCancel       :   false },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url     :   '<?php echo base_url('user/manageuser/changestatus');?>',
                    type    :   'POST',
                    data    :   {userid    :  arrayId[1],status    :  status},
                    success:function(data){
                        var newstatus           =  status==1?5:1; 
                        $("#check-"+arrayId[1]).attr('data-status',newstatus);
                        var statuslabel         =   status==1?'<span class="label label-danger">Block</span>':'<span class="label label-primary">Active</span>';
                        $('#status-'+arrayId[1]).html(statuslabel);    
                        swal("Changed!", "Your currnt status is changed.", "success");
                    },
                    error:function(){}
                });
            } 
            else {
                swal("Cancelled","", "error");
            }
        });
});    
/******************************** RC ******************************************/
/*----------------------- PACKAGE DELETE ----------------------------------------
        @CREATE DATE                 :  18-07-2019    
------------------------------------------------------------------------------*/
$('.delete').on('click',function(){
    var arrayId     =   this.id.split('-');
    swal({
        title               :   "Are you sure?",
        text                :   "Are you sure you want to delete the user!",
        type                :   "warning",
        showCancelButton    :   true,
        confirmButtonColor  :   "#DD6B55",
        confirmButtonText   :   "Yes, Delete User!",
        cancelButtonText    :   "No, Cancel !",
        closeOnConfirm      :   false,
        closeOnCancel       :   false },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url     :   '<?php echo base_url('user/manageuser/delete');?>',
                    type    :   'POST',
                    data    :   {userid    :  arrayId[1]},
                    success:function(data){
                        $('#row-'+arrayId[1]).hide();
                        $('#delete-msg').show(); 
                        swal("Deleted!", "User delete successfully.", "success");
                    },
                    error:function(){}
                });
            } 
            else {
                swal("Cancelled","", "error");
            }
        });
});
</script>    