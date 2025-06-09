    function chartthediv(div = 'mychart', labels, jsondataset, type,yaxis,xaxis){
    var ctx = document.getElementById(div);
    var lastsixmonth = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: jsondataset
        },

        options: {           
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: xaxis
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: yaxis
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    }

    function chartthediv_pie(div = 'mychart', labels, jsondataset, type,yaxis,xaxis='Month'){
    var ctx = document.getElementById(div);
    var lastsixmonth = new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: jsondataset
        },

        options: {           
            scales: {
                xAxes: [{
                    display: false,
                    scaleLabel: {
                        display: false,
                        labelString: xaxis
                    }
                }],
                yAxes: [{
                    display: false,
                    scaleLabel: {
                        display: false,
                        labelString: yaxis
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    }


$.fn.dataTable.moment('DD/MM/YYYY');
$.fn.dataTable.moment( 'DD/MM/YYYY h:mm:ssa' );



$('#admin_table').DataTable({
        "lengthMenu": [[50, 100], [50, 100]],
        "order": [[ 0, "desc" ]]
    });

function save_billing(date,customer){

    var data = {
       start_date: date,
       site: customer
   }
jQuery.ajax(
   {
   url: "http://www.dicon.loc/admin/billing",
   type: "POST",
   data: data,
   success: function (result) {
    console.log('saved billing');
   }
});   
}












function active_side(){
    
    var url      = window.location.href;
    var res = url.split("/");
    res = res[res.length-1];
    res = res.split("?");
    res = res[0];
    res = res.split("#");
    res = res[0];



jQuery('ul.sidebar-menu li').each(function(i,obj){
var active = $(obj).data('active');
if (active != undefined){
var ar = active.split(',');
if(jQuery.inArray(res, ar) !== -1)
jQuery(obj).addClass('active');
}

});





}
active_side();







function excel_btn(e) {
    var result = [];
    var carry = [];
    var sub = [];
    var billing = [];
    $('table#carry_table tbody tr').each(function(i,obj){
        var dmmy = [];
        $(obj).find('td').each(function(j,td){
            dmmy[j] = $(td).text();
        });
        carry[i] = dmmy;
    });
    $('table#sub_table tbody tr').each(function(i,obj){
        var dmmy = [];
        $(obj).find('td').each(function(j,td){
            dmmy[j] = $(td).text();
        });
        sub[i] = dmmy;
    });
    $('table#billing_table tbody tr').each(function(i,obj){
        var dmmy = [];
        $(obj).find('td').each(function(j,td){
            dmmy[j] = $(td).text();
        });
        billing[i] = dmmy;
    });
    result['carry'] = carry;
    result['sub'] = sub;
    result['billing'] = billing;
    var t_bfr_disc = $('td#t_bfr_disc strong').text();
    var pers = $('td#t_amt_aftr span').text();
    var disc = $('td#disc strong').text();
    var t_amt_aftr = $('td#t_amt_aftr strong').text();
    var sub_amount = $('td#sub_a  strong').text();
    var main_fee_amt = $('td#main_fee_amt  span').text();
    var main_fee_type = $('td#main_fee_type  span').text();
    var gtotal = $('td#gtotal  strong').text();






    $('input[name="carry"]').val(JSON.stringify(carry));
    $('input[name="sub"]').val(JSON.stringify(sub));
    $('input[name="billing"]').val(JSON.stringify(billing));
    $('input[name="t_bef_disc"]').val(JSON.stringify(t_bfr_disc));
    $('input[name="pers"]').val(JSON.stringify(pers));
    $('input[name="disc"]').val(JSON.stringify(disc));
    $('input[name="t_amt_aftr"]').val(JSON.stringify(t_amt_aftr));
    $('input[name="sub_amount"]').val(JSON.stringify(sub_amount));
    $('input[name="main_fee_amt"]').val(JSON.stringify(main_fee_amt));
    $('input[name="main_fee_type"]').val(JSON.stringify(main_fee_type));
    $('input[name="gtotal"]').val(JSON.stringify(gtotal));


   $(e).parent().submit();
}

setTimeout(function(){ jQuery('.content-wrapper').css('min-height',jQuery('aside.main-sidebar').height()+'px');
 }, 500);

$(window).resize(function(){
    setTimeout(function(){ jQuery('.content-wrapper').css('min-height',jQuery('aside.main-sidebar').height()+'px');
 }, 100);
});








$('.billing3_submit').submit(function(e){


e.preventDefault();

var count_an = jQuery('#count_an').val();
var name_an = jQuery('#name_an').val();
var rate_an = jQuery('#rate_an').val();
var description_an = jQuery('#description_an').val();

if(description_an !="" && rate_an !="" && name_an !="" && count_an !=""){


var g_total = jQuery('div#pdf_div table tbody tr:last-child td:last-child ').text();
var total = Number(rate_an) * Number(count_an);
var tl = Number(total) + Number(g_total);
jQuery('div#pdf_div table tbody tr:last-child td:last-child ').html('<strong>'+tl+'<strong>');
var tr = '<tr> <td data-label="Count">'+count_an+'</td> <td data-label="Analysis">'+name_an+'</td> <td data-label="Rate">'+rate_an+'</td> <td data-label="Description">'+description_an+'</td> <td data-label="Total">'+total+'</td> </tr>';


//jQuery('div#pdf_div table tbody').append(tr);

$( tr ).insertBefore( "div#pdf_div table tbody tr:last-child" );

jQuery('#count_an').val('');
jQuery('#name_an').val('');
jQuery('#rate_an').val('');
jQuery('#description_an').val('');

$('.modal').modal('hide');
validator.resetForm();
}


});












	jQuery(document).ready(function($) {
		jQuery('.input100').focusout(function(){

			if($(this).val() != "")
				$(this).next('span').attr('data-placeholder','');
			else
			{
				if($(this).attr('name') == 'user')
				$(this).next('span').attr('data-placeholder','Username');
				else
				$(this).next('span').attr('data-placeholder','Password');
			}

		});

		   $('.button-left').click(function(){
		       $('.sidebar').toggleClass('fliph');
		       $('.dashboard_body content-wrapper').toggleClass('fliph');
		       $('.black_bg').toggleClass('fliph');
		   });
		   if ($(window).width() < 993) {
			    $('.sidebar').addClass('fliph');
		       $('.dashboard_body content-wrapper').addClass('fliph');
		       $('.black_bg').addClass('fliph');
			}

jQuery('a.delete_link').click(
        function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_modal .delete_url').attr('href',url); 
            $('#delete_modal').modal('show'); 
            //if (confirm("Are you sure to delete this entry ?")) window.location = jQuery(this).attr('href');
        }
    );

$('label.file-upload').click(function(){
	$(this).find('input').click();
});



$('#analyses_performed').multiselect();


$('.form-group.analyses_select input').change(function(){
    var id = jQuery(this).val();
    var title = jQuery(this).parent().attr('title');
    if(jQuery(this).is(":checked")){
        var html = ' <div class="each each_'+id+'"> <label for="addon_flows_'+id+'">Addon flows for '+title+'</label> <input type="number" data-rule-required="true" min="0" id="addon_flows_'+id+'" class="form-control" required name="addon_flows_'+id+'" value="0" "></div>';
        if(jQuery('.each_'+id).length == 0)
        jQuery('.multiple_inputs').append(html);
    }else{
        jQuery('.each_'+id).remove();
    }

});

var html = $('.pdf_div').html();
$('input[name="pdf"]').val(html);



//status filter
 $(document).on('change', '#status_select', function(){
  var option = $( "#status_select" ).val();
$('#all_worksheet_filter > label > input').val(option).trigger("keyup"); 
 });

 //reset filter
 $("#reset_filter").click(function(){
   $("#status_select option:selected").removeAttr("selected");   
   $('#all_worksheet_filter > label > input').val("").trigger("keyup");
   $("#status_select_study_time option:selected").removeAttr("selected");   
   $('#example2_filter > label > input').val("").trigger("keyup");
   $("#asignee_select option:selected").removeAttr("selected");
   $("#asignee_select").trigger("change");
   $("#day_select option:selected").removeAttr("selected");
   $("#day_select").trigger("change");
   $("#customer_select option:selected").removeAttr("selected");
   $("#customer_select").trigger("change");

   $("#asignee_second option:selected").removeAttr("selected");
   $("#asignee_second").trigger("change");
   $("#secondcheck_select option:selected").removeAttr("selected");
   $("#secondcheck_select").trigger("change");

});

	});



function activeTab(tab){
    $('.nav-tabs a[href="' + tab + '"]').tab('show');
};




function printData(divId)
{
   var content = document.getElementById(divId).innerHTML;
    var mywindow = window.open('', 'Print', 'height=600,width=800');

    mywindow.document.write('<html><head><title>Print</title>');
    mywindow.document.write('<style>table{border-collapse:collapse;border-spacing:0}td,th{padding:0}@media print{*{text-shadow:none!important;color:#000!important;background:transparent!important;box-shadow:none!important}thead{display:table-header-group}tr{page-break-inside:avoid}}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}:before,:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}table{max-width:100%;background-color:transparent}th{text-align:left}table.admin{border:1px solid #ccc;width:100%;margin:0;padding:0;border-collapse:collapse;border-spacing:0;background:#eeefe8;-webkit-box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15);-moz-box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15);box-shadow:7px 22px 40px -1px rgba(0,0,0,0.15)}table.admin thead{background:#025b6c;color:#fff}table.admin tr{border:1px solid #000;padding:5px}table.admin th,table.admin td{padding:10px;text-align:center}table.admin th{text-transform:uppercase;font-size:14px;letter-spacing:1px}@media screen and (max-width:600px){table.admin{border:0}table.admin thead{display:none}table.admin tr{margin-bottom:10px;display:block;border-bottom:2px solid #000}table.admin td{display:block;text-align:right;font-size:13px;border-bottom:1px solid #ccc}table.admin td:last-child{border-bottom:0}table.admin td:before{content:attr(data-label);float:left;text-transform:uppercase;font-weight:bold}}::-moz-selection{background:##777;color:#00a7f5}::selection{background:##777;color:#00a7f5}::-moz-selection{background:##777;color:#00a7f5}*,*:hover{outline:none!important;text-decoration:none!important}</style></head><body >');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close();
    mywindow.focus()
    mywindow.print();
    mywindow.close();
    return true;
}

