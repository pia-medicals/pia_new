<script src="<?php echo base_url('static/admin/js/plugins/dataTables/datatables.min.js');?>"></script>
<script src="<?php echo base_url('static/admin/js/plugins/dataTables/dataTables.bootstrap4.min.js');?>"></script>
<script src="<?php echo base_url('static/admin/js/plugins/sweetalert/sweetalert.min.js');?>"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="text/javascript">
    /******************************** RC ******************************************/

	$(document).ready(function(){
    /*----------------------- STUDIES LIST DATA TABLE --------------------------------
        @CREATE DATE                 :  14-08-2019     
    ------------------------------------------------------------------------------*/
        load_studies();

         function load_studies(day = null, assignee = null) {
            var dataTable = $('#dicom-list').DataTable({        
              "lengthMenu": [[50], [50]],
                "order": [[ 0, "desc" ]],
                "processing":true,
                "serverSide":true,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend    : 'copy'},
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
                ],
                "ajax":{
                url:"<?php echo base_url('customer/studies/getAllworkSheetStudies')?>",
                    type:"POST",
                    data:{is_day:day, is_assignee:assignee}  
                },
                "columnDefs":[
                    {
                        "targets":[2],
                        "orderable":false,
                    },
                ],

            });

            //dataTable.columns.adjust().draw();

            setInterval(function () {
                console.log('draw');
                dataTable.ajax.reload(null, false);
            }, 120000);
        }
        /*-----------------------  SECOND LIST DATA TABLE --------------------------------
        @CREATE DATE                 :  14-08-2019     
        ------------------------------------------------------------------------------*/
        function load_studies_second(is_second) {
            var dataTable = $('#dicom-list').DataTable({        
              "lengthMenu": [[50], [50]],
                "order": [[ 0, "desc" ]],
                "processing":true,
                "serverSide":true,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend    : 'copy'},
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
                ],
                "ajax":{
                url:"<?php echo base_url('customer/studies/getAnalystAllworkSheetStudies')?>",
                    type:"POST",
                    data:{is_second:is_second}  
                },
                "columnDefs":[
                    {
                        "targets":[2],
                        "orderable":false,
                    },
                ],

            });
        }
        /*----------------------- FILTERS --------------------------------
        @CREATE DATE                 :  14-08-2019     
        ------------------------------------------------------------------------------*/
        $(document).on('change', '#day_select, #asignee_select', function(){
            var day         = $('#day_select').val();
            var assignee    = $('#asignee_select').val();
            $('#dicom-list').DataTable().destroy();          
            load_studies(day, assignee);
            
        })

        $(document).on('change', '#status_select', function(){
            var option = $( "#status_select" ).val();
            $('#dicom-list_filter > label > input').val(option).trigger("keyup"); 
        });

        $(document).on('click', '.btn-status', function(){
            var option = $(this).attr('data-value');
            $('#dicom-list_filter > label > input').val(option).trigger("keyup"); 
        });
        $(document).on('click', '.btn-days', function(){
            var days = $(this).attr('data-value');
            $('#asignee_select').val('');
            var assigneez    = $('#asignee_select').val();
            $('#dicom-list').DataTable().destroy();
            load_studies(days, assigneez);
        });

        $("#reset_filter").click(function(){
           $("#status_select").val("");
           $('#dicom-list_filter > label > input').val("").trigger("keyup");
           $("#asignee_select").val("");
           $("#asignee_select").trigger("change");
           $("#day_select").val("");
           $("#day_select").trigger("change");
        });

        $(document).on('change', '#secondcheck_select', function(){
            var second = $(this).val();
            $('#dicom-list').DataTable().destroy();
            if(second != '') {
                load_studies_second(second);
            } else {
               load_studies(); 
            }
        })
  
        $('[data-toggle="tooltip"]').tooltip();
});
</script>