<style>
    .style-red{
        font-size:20px;
        color:red;
    }
    .style-green{
        font-size:20px;
        color:green;
    }
</style>
<div class="dashboard_body content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php $this->alert(); ?>

                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Study Time Report</h2>            
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper" > 
                            <div class="dataTables_length" >  
                               <form method="post" action="/ajax/downloaddata_completefilternewcsv_studyrp">
                                        <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
                                            <label class="col-md-5 col-sm-6 col-xs-12"> Show last </label> <select name="is_day" aria-controls="admin_table" id="is_day" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0">All </option> 
                                            <option value="1"> 1 day </option>
                                            <option value="3"> 3 days</option>
                                            <option value="30">30 days</option>
                                        </select>
                                        </div>

                                 <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
                                     <label class="col-md-5 col-sm-6 col-xs-12">Assignee</label>
                                        <select name="is_assignee" aria-controls="admin_table" id="is_assignee" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0">Select Assignee</option>
                                            <?php
                                            if (isset($asignee)):

                                                foreach ($asignee as $key => $value):
                                                    ?>
                                                    <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
                                                <?php endforeach;
                                            endif;
                                            ?>
                                        </select>
                                 </div>
                                
                                    <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
                                        <label class="col-md-5 col-sm-6 col-xs-12">Customer</label>
                                        <select name="is_customer" aria-controls="admin_table" id="is_customer" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0">Select Customer</option>
                                            <?php
                                            if (isset($customer)):
                                                foreach ($customer as $key => $value):
                                                    ?>
                                                    <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
    <?php endforeach;
endif;
?>
                                        </select><br>
                                        

                                    </div>
                                        
                                   <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
                                        <label class="col-md-5 col-sm-6 col-xs-12">
                                            Status </label>
                                       <select name="is_status" aria-controls="admin_table" id="is_status" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0"> None</option>
                                            <option value="Completed" > Completed</option>
                                            <option value="Under review" > Under review</option>
                                            <option value="In progress" > In progress</option>
                                            <option value="Cancelled" > Cancelled</option>
                                            <option value="On hold" > On hold</option>				  
                                        </select>
                                   </div>

      <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
          <label class="col-md-5 col-sm-6 col-xs-12">     Time Management </label><select name="is_time_mgmt" aria-controls="admin_table" id="is_time_mgmt" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0"> None</option>
                                            <!--<option value="AtVsEat" > AT > EAT</option>
                                            <option value="EatVsAt" > EAT > AT</option>-->
                                            <option value="TimeNotAdded">Time Not Added</option>

                                        </select>

      </div>

                                   <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">
                                        <label class="col-md-5 col-sm-6 col-xs-12">
                                            Time Differance Sorting </label>
                                       <select name="is_sorting" aria-controls="admin_table" id="is_sorting" class="form-control col-md-7 col-sm-6 col-xs-12" style="width:55%">
                                            <option value="0"> None</option>
                                            <option value="1" >Ascending order</option>
                                            <option value="2" >Descending order</option>                
                                        </select><br><br><br>
                                         <!-- <input type="button" id="report_filter" value="Filter" class="btn btn-success col-md-offset-5" name="report_filter" /> -->
                                        <input type="submit" name="export" class="btn btn-success" value="Export To CSV" />

                                        <input type="button" id="report_reset_filter" value="Reset Filter" class="btn btn-danger" name="report_reset_filter" /> 
                                   </div>

                           <!-- <div class="col-md-4 col-sm-6 col-xs-12" style="margin: 5px 0px 0px 0px;">    
                                      

                            </div>   --> 
  </div>                                
                            </div>
                        </div>
                        <br>

                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h6 class="" style="text-align: right;margin-right: 37px;">AT:Analyst Time,&nbsp; EAT:Expected Analyst Time,&nbsp; MDT:Medical Director Time,&nbsp; IST Image Specialist Time</h6>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">

                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                        <!--                    <th>MRN</th>-->
                                            <th>Customer</th>
                        <!--                    <th>Accession</th>-->
                                            <th>Patient Name</th>
                                            <th>AT [Minutes]</th>
                                            <th>EAT [Minutes]</th>                    
                                           <!-- <th>IST [Minutes]</th>
                                            <th>MDT [Minutes]</th>-->
                                            
											<th>Time Difference</th>
                                             <th>Assignee</th>
                                           
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                        <!--                    <th></th>-->
                                            <th></th>
                        <!--                    <th></th>-->
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                           <!-- <th></th>-->
                                            <th></th>
                                            <th></th> 
                                            <th></th> 
                                        </tr>
                                    </tfoot> 
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>

    <script>

        $(document).ready(function() {

            $("#start_date, #end_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd'
            });
            
             $(document).on('change', '#is_day', function(){
                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                      $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

 });
    $(document).on('change', '#is_assignee', function(){
                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                     $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

 });

     $(document).on('change', '#is_status', function(){
                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                     $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

 });


      $(document).on('change', '#is_time_mgmt', function(){
    var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                     $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

 });


      $(document).on('change', '#is_customer', function(){
                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                     $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

 });

       $(document).on('change', '#is_sorting', function(){
                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();
                 var is_sorting = $('#is_sorting').val();
 
     if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '' && is_sorting != '')
                {
                     $('#example2').DataTable().destroy();
                    load_data(day, asignee, customer, status, is_time_mgmt, is_sorting);
                }

                else
                {
                    load_data();
                }

 });


            load_data();

            function load_data(is_day, is_assignee, is_customer, is_status, is_time_mgmt, is_sorting)
            {
                var dataTable = jQuery('#example2').DataTable({
                    "footerCallback": function(row, data, start, end, display) {
						//console.log(JSON.stringify(data));
                        // alert(JSON.stringify(data));
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };

                        //get only numeric datas
                        //             var numOnly = function ( i ) {
                        //            	//return typeof i === 'number' ?
                        //            	//i : i.replace(/[^\d.-]/g, '');
                        //                return typeof i === 'string' ?
                        //            	i.replace(/(<([^>]+)>)/ig,'')*1 :
                        //            	typeof i === 'number' ?
                        //            	i : 0;               
                        //            };

                        //get only numeric datas
                        var numOnly = function(i) {
                            return typeof i === 'string' ?
                                    i.replace(/[^\d.]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };

                        // Total over this page
                        pageTotal = api
                                .column(3, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    // alert(numOnly(a) + ' -- ' + numOnly(b));
                                    return (numOnly(a) + numOnly(b)).toFixed(2);
                                }, 0);

                        pageTotal1 = api
                                .column(4, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    return (numOnly(a) + numOnly(b)).toFixed(2);
                                }, 0);

                                

                        //pageTotal2 = api
//                                .column(5, {page: 'current'})
//                                .data()
//                                .reduce(function(a, b) {
//                                    return intVal(a) + intVal(b);
//                                }, 0);

                        //pageTotal3 = api
//                                .column(6, {page: 'current'})
//                                .data()
//                                .reduce(function(a, b) {
//                                    return intVal(a) + intVal(b);
//                                }, 0);

                        // Update footer
                        /*$( api.column( 5 ).footer() ).html(
                         pageTotal+'HRS ' +' ('+ total +' total)'
                         );*/
                        $(api.column(3).footer()).html(
                                //(pageTotal / 60).toFixed(2) + ' HRS '
								(pageTotal)+ ' MIN '
                                );
                        $(api.column(4).footer()).html(
                                //(pageTotal1 / 60).toFixed(2) + ' HRS '
							(pageTotal1) + ' MIN '
                                );
                       
                        /*$(api.column(5).footer()).html(
                                //(pageTotal2 / 60).toFixed(2) + ' HRS '
                                );
                        $(api.column(6).footer()).html(
                                //(pageTotal3 / 60).toFixed(2) + ' HRS '
                                );*/

                    },
                    "lengthMenu": [[100], [100]],
                    "order": [[0, "desc"]],
                   // "order": [[0, "asc"]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "/ajax/get_study_time_report",
                        data: {is_day: is_day, is_assignee: is_assignee, is_customer: is_customer, is_status: is_status, is_time_mgmt: is_time_mgmt,is_sorting: is_sorting},
                        type: "post"
                    }
                });
            }


            $("#report_filter").click(function() {

                //alert("clicked");

                var customer = $('#is_customer').val();
                var asignee = $('#is_assignee').val();
                var day = $('#is_day').val();
                var status = $('#is_status').val();
                var is_time_mgmt = $('#is_time_mgmt').val();

                $('#example2').DataTable().destroy();

                if (day != '' && customer != '' && asignee != '' && status != '' && is_time_mgmt != '')
                {
                    load_data(day, asignee, customer, status, is_time_mgmt);
                }

                else
                {
                    load_data();
                }

            });


            $("#report_reset_filter").click(function() {
                $("#is_status option:selected").removeAttr("selected");
                $("#is_assignee option:selected").removeAttr("selected");
                $("#is_day option:selected").removeAttr("selected");
                $("#is_customer option:selected").removeAttr("selected");
                $("#is_time_mgmt option:selected").removeAttr("selected");

                $('#example2').DataTable().destroy();

                //      var dataTable=jQuery('#example2').DataTable({
                //        "footerCallback": function ( row, data, start, end, display ) {
                //           //  alert(JSON.stringify(data));
                //          var api = this.api(), data;
                //
                //            // Remove the formatting to get integer data for summation
                //            var intVal = function ( i ) {
                //              return typeof i === 'string' ?
                //              i.replace(/[\$,]/g, '')*1 :
                //              typeof i === 'number' ?
                //              i : 0;
                //            };
                //
                //            // Total over all pages
                //            total = api
                //            .column( 5 )
                //            .data()
                //            .reduce( function (a, b) {
                //              return intVal(a) + intVal(b);
                //            }, 0 );
                //
                //            // Total over this page
                //            pageTotal = api
                //            .column( 5, { page: 'current'} )
                //            .data()
                //            .reduce( function (a, b) {
                //              return intVal(a) + intVal(b);
                //            }, 0 );
                //
                //            // Update footer
                //            $( api.column( 5 ).footer() ).html(
                //              '$'+pageTotal +' ( $'+ total +' total)'
                //              );
                //          },
                //          "lengthMenu": [[100], [100]],
                //          "order": [[ 0, "desc" ]],
                //          "processing": true,
                //          "serverSide":true,
                //          "ajax":{
                //            url:"/ajax/get_study_time_report",         
                //            type:"post"
                //          }
                //        }); 

                var dataTable = jQuery('#example2').DataTable({
                    "footerCallback": function(row, data, start, end, display) {

                          //alert(JSON.stringify(data));
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };


                        //get only numeric datas
                        var numOnly = function(i) {
                            return typeof i === 'string' ?
                                    i.replace(/[^\d.]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                        };

                        // Total over this page
                        pageTotal = api
                                .column(3, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    // alert(numOnly(a) + ' -- ' + numOnly(b));
                                    return (numOnly(a) + numOnly(b)).toFixed(2);
                                }, 0);

                        pageTotal1 = api
                                .column(4, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    return (numOnly(a) + numOnly(b)).toFixed(2);
                                }, 0);
                                 


                        /*pageTotal2 = api
                                .column(5, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                        pageTotal3 = api
                                .column(6, {page: 'current'})
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
*/
                        $(api.column(3).footer()).html(
                                //(pageTotal / 60).toFixed(2) + ' HRS '
							(pageTotal)+ ' MIN '
							
                                );
                        $(api.column(4).footer()).html(
                                //(pageTotal1 / 60).toFixed(2) + ' HRS '
							(pageTotal1)+ ' MIN '
                                );
                        
                        /*$(api.column(5).footer()).html(
                                (pageTotal2 / 60).toFixed(2) + ' HRS '
                                );
                        $(api.column(6).footer()).html(
                                (pageTotal3 / 60).toFixed(2) + ' HRS '
                                );*/

                    },
                    "lengthMenu": [[100], [100]],
                    "order": [[0, "desc"]],
                    //  "order": [[0, "asc"]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "/ajax/get_study_time_report",
                        type: "post"
                    }
                });


            });

        });

    </script>
</div>



