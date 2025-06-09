<div class="dashboard_body content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <?php $this->alert(); ?>

        <div class="box">
          <div class="box-header">
            <h2 class="box-title">Study Time Graph</h2>

          </div>

          <div class="box-body">
            <div class="dataTables_wrapper" > 
              <div class="dataTables_length" >         

                <span> 
                  <label>
                  <select name="customer_select" aria-controls="admin_table" id="customer_select" class="form-control" style="width: 266px; height: 35px; margin-left:15px;">
                    <option value="">Select Customer</option>
                    <?php if(isset($customer)):
                      foreach ($customer as $key => $value): ?>
                        <option value="<?= $value['id'] ?>"   > <?= $value['name'] ?> </option> 
                      <?php endforeach; endif; ?>
                    </select> 
                    <input type="text" id="start_date" required name="start_date" autocomplete="off" placeholder="Choose Year & month"  style="width: 142px; height: 35px;">
                  </label>  
                  <input type="button" id="study_time_search" value="Search" class="btn btn-success" name="study_time_search"  style="margin-top: -6px;" />

                   </span> 
                  <br>          
                </div>
              </div>
              <!-- CUSTOMER WISE TIME SPENT PER MONTH -->
  <div class="col-md-6"> 
  
  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title text-capitalize">CUSTOMER WISE TIME SHEET</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="customer_wise_time_spent" style="height: 230px; width: 487px;" width="300" height="155"></canvas>
  <style type="text/css">
  .dashboard_body::before {
    background: #ecf0f5;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    content: '';
}
 </style>



              </div>
            </div>
            <!-- /.box-body -->
          </div>

  </div>

  <div class="col-md-6"> 
  
  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title text-capitalize">LAST 6 MONTHS TIME SHEET REPORT</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lastsixmonth" style="height: 230px; width: 487px;" width="300" height="155"></canvas>
<script type="text/javascript">
  <?php 
    $analystdata = array(
      'hours' => array(),
      'month' => array()
    );
     
    foreach ($analyst_total_hours as $key => $eachmonth) {
      $analystdata['hours'][] = $eachmonth['analyst_hourstotal'];
      $analystdata['month'][] = date('F', mktime(0, 0, 0, $eachmonth['monthnum'], 10));
    }

    $specialystdata = array(
      'hours' => array(),
      'month' => array()
    );
     

    foreach ($img_specialist_hours as $key => $eachmonth) {
      $specialystdata['hours'][] = $eachmonth['image_specialist_hourstotal'];
      $specialystdata['month'][] = date('F', mktime(0, 0, 0, $eachmonth['monthnum'], 10));
    }

    $medidirectordata = array(
      'hours' => array(),
      'month' => array()
    );
     

    foreach ($medi_director_hours as $key => $eachmonth) {
      $medidirectordata['hours'][] = $eachmonth['medical_director_hourstotal'];
      $medidirectordata['month'][] = date('F', mktime(0, 0, 0, $eachmonth['monthnum'], 10));
    }


  ?>
jQuery(document).ready(function(){
var labels = <?= json_encode($analystdata['month']) ?>;
var type = 'line';
var yaxis = 'VALUE';
var xaxis = 'MONTH';
var data = [{
                label: 'ANALYST HOURS',
                data: <?= json_encode($analystdata['hours']) ?>,
                backgroundColor: ['rgba(255, 99, 132, 0.2)'],
                borderColor: [ 'rgba(255,99,132,1)'],
                fill: false,            
                borderWidth: 1
            },
            {
              label: 'SPECIALYST HOURS',
                data: <?= json_encode($specialystdata['hours']) ?>,
                backgroundColor: ['#4caf50ad'],
                borderColor: [ '#4caf50'],
                fill: false,            
                borderWidth: 1
            },
            {
              label: ' DIRECTOR HOURS',
                data: <?= json_encode($medidirectordata['hours']) ?>,
                backgroundColor: ['#9c27b0b0'],
                borderColor: [ '#9c27b0'],
                fill: false,            
                borderWidth: 1
            }
            ];
chartthediv('lastsixmonth',labels,data,type,yaxis,xaxis);
});

</script>


              </div>
            </div>
            <!-- /.box-body -->
          </div>
  </div>



  <div class="col-md-12"> 
  
  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title text-capitalize">CUSTOMER WISE TIME SHEET</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
         
           
            <div class="box-body"> <!-- /.box-body -->
                
                 <label> <span> <input type="text" id="study_time_date" required name="study_time_date" autocomplete="off" placeholder="Choose Year & month"  style="width: 150px; height: 35px;"></span></label>

                 <table id="study_time_graph" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width:180px">Customer</th>
                    <th>Analyst Time</th>     
                    <th>Image Specialist Time</th>                 
                    <th>Medical Director Time</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                   <th>Customer</th>
                   <th>Analyst Time</th>     
                   <th>Image Specialist Time</th>                 
                   <th>Medical Director Time</th>

                 </tr>
               </tfoot> 
             </table>

          </div>

  </div>
</div>     
           </div>
           <!-- /.box-body -->
         </div>
       </div>
     </div>
   </section>
   <style type="text/css">
   .ui-datepicker-calendar, button.ui-datepicker-current.ui-state-default.ui-priority-secondary.ui-corner-all {
    display: none;
  }

  label#start_date-error, label#site-error {
    display: none !important;
  }
  table.admin.bold tr {
    font-size: 16px;
  }
  .inline-block{
    display: inline-block;
  }
  @media print {

  }


</style>
<script>

  $(document).ready(function(){

chartsend([0,0,0]);
    
   $("#start_date, #study_time_date").datepicker(
   {
    dateFormat: "yy-mm",
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    onClose: function(dateText, inst) {


      function isDonePressed(){
        return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
      }

      if (isDonePressed()){
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');

                     $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
                   }
                 },
                 beforeShow : function(input, inst) {

                  inst.dpDiv.addClass('month_year_datepicker')

                  if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = datestr.substring(0, 2);
                    $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                    $(this).datepicker('setDate', new Date(year, month-1, 1));
                    $(".ui-datepicker-calendar").hide();
                  }
                }
              });

  //  var dataTable=jQuery('#study_time_graph').DataTable({
  //   "lengthMenu": [[100], [100]],
  //   "order": [[ 0, "desc" ]],
  //   "processing":true,
  //   "serverSide":true,
  //   "ajax":{
  //     url:"/ajax/study_time_graph_data",
  //     type:"POST",
  //   }
  // });

 load_data();

function load_data(is_date)
 {
  var dataTable = $('#study_time_graph').DataTable({
   "lengthMenu": [[100], [100]],
    "ordering": false,
   "processing":true,
   "serverSide":true,
   "ajax":{
    url:"/ajax/study_time_graph_data",
    type:"POST",
    data:{is_date:is_date}
   }
  });

  $('#study_time_graph_wrapper > div:nth-child(1)').hide();

 }

 $(document).on('change', '#study_time_date', function(){
  var date = $(this).val();
  $('#study_time_graph').DataTable().destroy();
  if(date != '')
  {
   load_data(date);
  }
  else
  {
   load_data();
  }

 });

$('#study_time_graph_wrapper > div:nth-child(1)').hide();


$("#study_time_search").click(function(){

var customer = $("#customer_select").val();
var date = $("#start_date").val();
var arr = date.split('-');
	//alert(customer);
$.ajax({  url: "/ajax/study_time_graph", 
          type:"POST",
          dataType: "json",
          data:{customer:customer, month:arr[1], year:arr[0]},
          success: function(result){  
			 // alert(result);
			 //console.log(result);
			  chartsend(result);
       }
    });
  });

});


  function chartsend(result){
	   //alert(result);
	  
   var ctx = document.getElementById("customer_wise_time_spent").getContext('2d');
	  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Analyst Time", "Expected Analyst Time"],
        datasets: [{

            label: ["Analyst Time", "Expected Analyst Time"],
            data: result,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)'
                
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)'
               
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
        }
});


  }

</script>

</div>



