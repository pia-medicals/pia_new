$(document).ready(function () {
    var currentUrl = window.location.pathname; // Get the current page URL     
    currentUrl = currentUrl.replace(/^\//, '');
    $('.sidebar li.nav-item').each(function () {
        if ($(this).attr('data-active')) {
            var activeKey = $(this).attr('data-active'); // Get the data-active value    
            if (activeKey.includes(currentUrl)) { // Check if the URL includes the activeKey                 
                $(this).find('.nav-link').addClass('active'); // Add the active class to the link
                $(this).addClass('menu-open'); // Optionally, keep the parent menu open
            }
        }
    });
});


function printTableData(divId)
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


$("html").on("keypress", ".num", function (event) {
    if (event.which < 48 || event.which > 57) {
        event.preventDefault();
    }
});

$("html").on("keyup blur", ".num", function (e) {
    var val = $(this).val();
    if (val.match(/[^0-9]/g)) {
        $(this).val(val.replace(/[^0-9]/g, ''));
    }
});

$("html").on("keypress", ".perc", function (event) {
      if ((event.which < 48 || event.which > 57) && event.which!=46) {
        event.preventDefault();
    }
});


$("html").on("input", ".perc", function () {
     var val = $(this).val();
    if (val.match(/[^0-9.]/g)) {
        $(this).val(val.replace(/[^0-9.]/g, ''));
    }
});



