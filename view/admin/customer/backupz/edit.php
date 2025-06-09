<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><span id="custname"><?= ucfirst($edit['name']) ?></span> <a data-toggle="modal" data-target="#modal-sm"> <i class="fa fa-pencil" style="font-size: 12px"></i></span></a>
            </div>
            <?php
            $pf = $edit['profile_picture'];
            $alert_data = $this->alert();
            $meta = json_decode($edit['user_meta']);
            $hospital = $this->Admindb->table_full('hospital');
            $rep = 1;
            //$this->debug($meta->hospital);
            if (isset($meta->analysis))
                $rep = count($meta->analysis);
            if (isset($meta) && $meta != "")
                
                ?>


            <div class="container-fluid">

                <br>
                <div style="float: right;">
                    <a href="<?= SITE_URL ?>/excel/get_excel_customer?cus=<?= $edit['id'] ?>"><button class="pull-right btn btn-primary btn-flat " style="margin-right: 10px;">Download Customer Data</button></a>
                </div>
                <form role="Form" method="post" action="" enctype="multipart/form-data" id="admin_form1" class="sep" data-form-validate="true"  novalidate="novalidate" >

                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <img src="<?= SITE_URL . '/assets/uploads/user/' . $pf ?>" alt="" style="    margin-bottom: 20px; max-width: 70px"  class="img-responsive">
                        <input type="file" id="logo" class="form-control"  name="logo"    >
                    </div>

                    <div class="form-group">
                        <label for="customer_code">Customer Code</label>
                        <input type="text" id="customer_code" class="form-control" required name="customer_code" value="<?= $this->issetEcho($meta, 'customer_code') ?>" data-rule-required="true" data-rule-maxlength="5" >
                        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="number" id="phone" class="form-control" name="phone" placeholder="" value="<?= $this->issetEcho($meta, 'phone') ?>" >
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" data-rule-required="true" ><?= $this->issetEcho($meta, 'address') ?></textarea>
                    </div>

                    <div class="form-group">
                    <label for="group">Default Turn Around Time</label>
                      <select  id="tat" name="tat" class="form-control customers_choose" data-rule-required="true">
                          <option value="">Choose Default TAT</option>
                          <option value="2" <?= (isset($edit['tat']) && $edit['tat'] == 2) ? 'selected' : ''?>>2 Hours</option>
                          <option value="4" <?= (isset($edit['tat']) && $edit['tat'] == 4) ? 'selected' : ''?>>4 Hours</option>
                          <option value="6" <?= (isset($edit['tat']) && $edit['tat'] == 6) ? 'selected' : ''?>>6 Hours</option>
                          <option value="24" <?= (isset($edit['tat']) && $edit['tat'] == 24) ? 'selected' : ''?>>24 Hours</option>
                              
                      </select>
                </div>

                    <!-- 
                      <div class="form-group">
                       <label for="phone">Subscription Amount</label>
                       <input type="number" id="subscription_amount" class="form-control" name="subscription_amount" placeholder="" value="<?= $this->issetEcho($meta, 'subscription_amount') ?>" data-rule-required="true" >
                    </div> -->
                    <div class="form-group ">
                        <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Save</button>
                    </div>
                </form>
            </div>
    </section>






</div>

<div class="modal fade" id="modal-sm">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Update Customer Name</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<form name="frmCustName" id="frmCustName" method="post" data-form-validate="true"  novalidate="novalidate">
<div class="modal-body">

  <input type="hidden" name="id" value="<?= $edit['id'] ?>">
    <div class="form-group">
        <label for="name">Customer Name</label>
        <input type="text" id="name" class="form-control" name="name" placeholder="" value="<?= ucfirst($edit['name']) ?>" data-rule-required="true">
    </div>

</div>
<div class="modal-footer justify-content-between">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" id="btn_name_edit" class="btn btn-primary">Save</button>

</div>
</form>
</div>

</div>

</div>
<script type="text/javascript">

function edit_amount() {
   $('.input-group.overlay').remove();
};

   jQuery(document).ready(function($) {

        activeTab(location.hash);
   

   setTimeout(function(){ 

          var msg = $('.alert.alert-success').text();
          if( msg == "Success! Subscription analysis created successfully" ){
          $('input#subscription_amount').val($('input#total').val()).parent().append('<div class="alert alert-success">Please click submit button to save Subscription Amount.</div>');
        }

    }, 300);
  
   $("form[data-form-validate='true']").each(function() {
          $(this).validate({
              errorPlacement: function(error, element) {
                  // to append radio group validation erro after radio group            
                  if (element.is(":radio")) {
                      error.appendTo(element.parents('.form-group'));
                  } else {
                      error.insertAfter(element);
                  }
              }
          });
      });
   
    $("select#analysis").change(function(){
        var analys 		= $("#analysis option:selected").val();
		var analysname 	= $("#analysis option:selected").text();
        var data = { id: analys }       
        $.ajax({
        url : "<?=SITE_URL?>/ajax/ajax_analysis_polpulate",
        type: "GET",
         data: data,
        dataType: "JSON",
        success: function(data)
        {        
            //console.log(data);  
           //var a = JSON.parse(data);
           $("input#rate").val(data.price);
           $("input#code").val(data.part_number);
		   //$("input#custom_description").val(data.name);
                    $("input#min_time").val(data.minimum_time);
        }
    });

    });
   
    $(".save_total_ajax").click(function(){
        var cid = $(".total_save_div #cid").val();
        var amount = $(".total_save_div #total").val();
        var data = { id: cid, amount: amount, }       
        $.ajax({
        url : "<?=SITE_URL?>/ajax/save_subscription_amount",
        type: "POST",
         data: data,
        dataType: "JSON",
        success: function(data)
        {        
          
           
            if(data = 1){
               $('.total_save_div .alert-danger').remove();
               $('.total_save_div').prepend('<div class="alert alert-success saved_amt">Subscription Amount saved.</div>');
               setTimeout(function(){ $('.total_save_div .saved_amt').remove(); }, 3000);
            }
        }
    });

    });
   
   
  /* =============Documents / Agreements Upload Add more=================*/
  var p = $('#doc_count').val() || 1;
  var maxField = 10;
  var wrapper = $('#c_wrapper');
  var fieldHTML = $('.row_repeat_1').html();
  $('#add_more_section').on('click', function() {
      if (p < maxField) {
          p++;
          $(wrapper).append('<div class="row reset_row repeated_row row_repeat_' + p + ' removerepeated ">' + fieldHTML.replace(/_1/g,'_'+p) + '<div class="col-md-1"><a href="javascript:void(0);" class="remove_button btn btn-danger" style="margin-top:22px;">&times;</a></div></div>');
          $('#doc_count').val(p);
      }
  });   
  $('#c_wrapper').on('click', '.remove_button', function(e){
      e.preventDefault();
      $( "div.repeated_row:last-child" ).remove();
      p--;
      $('#doc_count').val(p);
  });

  $("#frmCustomerDoc").submit(function (event) {
    event.preventDefault();
    if(! $(this).valid()) return false;
    var dataobject = $('#frmCustomerDoc').get(0);
    $.ajax({
        type: "POST",
        data: new FormData(dataobject),
        url: '/ajax/ajaxUploadAgreements',
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
           
            var result = jQuery.parseJSON(data);
            if (result.status > 0) {
              alert("Agreements Uploaded Successfully");
              var url = window.location.href+'#upload_docs';
              window.location.href = url; 
              location.reload();
              //$("#frmCustomerDoc")[0].reset();
              //$(".reset_row").remove();
              //p = 1;
              //$('#doc_count').val(1);
                 
            } else {
                alert(result.msg);  
            }
            
        }
    });
    return false;
  })
  /* =============Documents / Agreements Upload Add more=================*/ 

  /* =============================Bills Upload Add more====================*/

  
  $('#bill_due_1').datepicker({
      startDate: '-0d',
      format: 'mm/dd/yyyy',
  });
  $("#bill_date_1").datepicker( {
      format: "mm-yyyy",
      viewMode: "months", 
      minViewMode: "months"
  });

  var b   =    $('#bill_count').val() || 1;
  var bwrapper    =    $('#b_wrapper');
  var bfieldHTML  =    $('.brow_repeat_1').html();
  $('#add_more_bills').on('click', function() {
    if (b < maxField) {
      b++;
      $(bwrapper).append('<div class="row reset_brow repeated_brow brow_repeat_' + b + ' removerepeated "><hr/>' + bfieldHTML.replace(/_1/g,'_'+b) + '<div class="col-md-1"><a href="javascript:void(0);" class="bremove_button btn btn-danger" style="margin-top:22px;">&times;</a></div></div>');
        $('#bill_count').val(b);
        $('#bill_due_'+b).datepicker({
          startDate: '-0d',
          format: 'mm/dd/yyyy',
      });
      $("#bill_date_"+b).datepicker( {
          format: "mm-yyyy",
          viewMode: "months", 
          minViewMode: "months"
      });
    }
  });
  $('#b_wrapper').on('click', '.bremove_button', function(e){
      e.preventDefault();
      $( "div.repeated_brow:last-child" ).remove();
      b--;
      $('#bill_count').val(b);
  });
  //frmCustomerBills
  $("#frmCustomerBills").submit(function (event) {
    event.preventDefault();
    if(! $(this).valid()) return false;
    var dataobjectbill = $('#frmCustomerBills').get(0);
    $.ajax({
        type: "POST",
        data: new FormData(dataobjectbill),
        url: '/ajax/ajaxUploadBills',
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
           
            var result = jQuery.parseJSON(data);
            if (result.status > 0) {
              alert("Bills Uploaded Successfully");
              var url = window.location.href+'#upload_bills';
              window.location.href = url; 
              location.reload();

            } else {
                alert(result.msg);  
            }
            
        }
    });
    return false;
  })
  /* =============================Bills Upload Add more====================*/ 

  //frmCustName
  $("#frmCustName").submit(function (event) {
    event.preventDefault();
    if(! $(this).valid()) return false;
    var dataobjectbill = $('#frmCustName').get(0);
    $.ajax({
        type: "POST",
        data: new FormData(dataobjectbill),
        url: '/ajax/ajaxCustomerName',
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
           
            var result = jQuery.parseJSON(data);
            if (result.status > 0) {
              $('#custname').html($('#name').val());
              alert("Customer Name Updated Successfully");
              $('#modal-sm').modal('hide');
            } else {
                alert(result.msg);  
            }
            
        }
    });
    return false;
  })
});

</script>

<script>  
$(document).ready (function () {  
    $("select.first").change (function () {  
        var selectedanalysis = $(this).children("option:selected").text(); 
       $("#custom_description").val(selectedanalysis);
    });  
});  
</script>
