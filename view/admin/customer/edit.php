<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><span id="custname"><?= ucfirst($edit['user_name']) ?></span> <a data-toggle="modal" data-target="#modal-sm"> <i class="fa fa-pencil" style="font-size: 12px"></i></span></a>
            </div>
            <?php
            $pf = isset($edit['profile_picture'])?$edit['profile_picture']:"";
            $alert_data = $this->alert();
            //$meta = json_decode($edit['user_meta']);
            //$hospital = $this->Admindb->table_full('hospital');
            $rep = 1;
            //$this->debug($meta->hospital);
            if (isset($meta->analysis))
                $rep = count($meta->analysis);
            if (isset($meta) && $meta != "")
                
                ?>


            <div class="container-fluid">

                <br>
                <div style="float: right;">
                    <a href="<?= SITE_URL ?>/excel/get_excel_customer?cus=<?= $edit['user_id'] ?>"><button class="pull-right btn btn-primary btn-flat " style="margin-right: 10px;">Download Customer Data</button></a>
                </div>
                <form role="Form" method="post" action="" enctype="multipart/form-data" id="admin_form1" class="sep" data-form-validate="true"  novalidate="novalidate" >

                    <!-- <div class="form-group">
                        <label for="logo">Logo</label>
                        <img src="<?= SITE_URL . '/assets/uploads/user/' . $pf ?>" alt="" style="    margin-bottom: 20px; max-width: 70px"  class="img-responsive">
                        <input type="file" id="logo" class="form-control"  name="logo"    >
                    </div>

                    <div class="form-group">
                        <label for="customer_code">Site Code</label>
                        <input type="text" id="customer_code" class="form-control" required name="customer_code" value="<?= $this->issetEcho($meta, 'customer_code') ?>" data-rule-required="true" data-rule-maxlength="5" >
                        <input type="hidden" name="id" value="<?= $edit['user_id'] ?>">
                    </div> -->

                    <div class="form-group">
                        <label for="customer_code">Client Code</label>
                        <input type="number" id="client_code" class="form-control" required name="client_code"  minlength="1" maxlength="4" required="">
                        <input type="hidden" name="id" value="<?= $edit['user_id'] ?>">
                    </div>


                    <!-- <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="number" id="phone" class="form-control" name="phone" placeholder="" value="<?= $this->issetEcho($meta, 'phone') ?>" >
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" data-rule-required="true" ><?= $this->issetEcho($meta, 'address') ?></textarea>
                    </div> -->

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


<section class="content fl100">
  <div class="box fl100">





   <div class="nav-tabs-custom">
      <ul class="nav nav-tabs ">
         <li class="active"><a data-toggle="tab" href="#price">Price</a></li>
         <li><a data-toggle="tab" href="#discount">Monthly Discount</a></li>
         <li><a data-toggle="tab" href="#subscription">Subscription</a></li>
         <li><a data-toggle="tab" href="#maint_fees">Maintenance Fees</a></li>
         <li><a data-toggle="tab" href="#upload_docs">Agreements</a></li>
         <li><a data-toggle="tab" href="#upload_bills">Bills</a></li>
      </ul>
   </div>
   <div class="tab-content site_tab">
      <div id="price" class="tab-pane fade in active">
         <form class="container-fluid " id="admin_form2" method="post" data-form-validate="true"  novalidate="novalidate">
            <?php 
               $analysis = $this->Admindb->table_full('analyses');
               ?>
            <div class="row">
               <div class="col-md-3">
                  <div class="form-group">
                     <label for="analysis">Analysis</label>
                     <select id="analysis" class="form-control first">
                        <option selected disabled>Choose Analysis</option>
                        <?php 
                           foreach ($analysis as $key => $value) {
                            if(isset($edit['analysis']) && $edit['analysis'] == $value['analysis_id']) $sel = 'selected'; else $sel = '';
                            echo '<option value="'.$value['analysis_id'].'" '.$sel.' >'.$value['analysis_name'].'</option>';
                           }
                           ?>
                     </select>
                     <input type="hidden" name="customer" id="customer" value="<?=$_GET['edit'] ?>" >
                  </div>
               </div>
              <!-- <div class="col-md-2">
                  <div class="form-group">
                     <label for="rate">Customer Description</label>
                    <textarea id="custom_description" class="form-control" rows="5"></textarea>                                    
                  </div>
               </div>-->
               <div class="col-md-2">
                  <div class="form-group">
                     <label for="rate">Rate</label>
                     <input type="number" id="rate" class="form-control" >
                  </div>
               </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="code">Code</label>
                       <!-- <input type="number" id="code" class="form-control" data-rule-maxlength="4" >-->
                       <input type="text" id="code" class="form-control">
                    </div>
                </div>
                <div class="col-md-2" style="display: none;">
                    <div class="form-group">
                        <label for="min_time">Minimum Time</label>
                        <input type="hidden" id="min_time" class="form-control" data-rule-maxlength="5" >
                    </div>
                </div>
<!--                <div class="col-md-offset-9 col-md-3">-->
   <div class="col-md-3">
                    <div class="form-group">
                        <label for="code" style="visibility: hidden; display: block">add</label>
                        <button type="button" id="add-row" class="btn btn-primary btn-flat">Add To List</button>
                        <button type="button" id="delete-analysis" class="btn btn-primary btn-flat pull-right">Delete</button>
                    </div>
               </div>
            </div>
        
         <script type="text/javascript">
             $(document).ready(function(){

                  // x = 0;
                  x = $("#analysis-tbody tr").length;
                  y = 0;
                  z = 0;
                  // analysis Rate
                  $("#add-row").click(function(){

                        x++;
                      
                        var rate = $("#rate").val();
                        var code = $("#code").val();
						var custom_description	=	$('#custom_description').val();
                                                var min_time            =       $('#min_time').val();
                        var analysis_id = $("#analysis").val();
                        if(!rate){
                            alert("Please Enter Rate");
                              return false;
                        }
//                        if(!min_time){
//                            alert("Please Enter Minimum Time");
//                              return false;
//                        }
                        var jaba=0;

                        jQuery('.analysis_ids').each(function(){

                          var text = $(this).val(); 

                           if(text == analysis_id ){
                                
                                alert("Already Exists");
                                jaba = 1;
                                return false;
                               
                           } 

                        });

                        if(jaba == 1){
                          return false;
                        }

                        var analysis_name = $("#analysis option:selected").html();
                        var markup =`
                           <tr>
                               <td  hidden><input class="analysis_ids" type="hidden" value="${analysis_id}" name="analysis_id[]"></td>
                               <td>${x}</td>
                               <td style="text-align: left;">${analysis_name}</td>
                               <td> 
                                 <input type="number" id="rate${x}" class="form-control" value="${rate}"  name="rate[]" data-rule-required="true" aria-required="true">
                               </td>
                               <td>
                                 <input value="${code}" type="text" id="code${x}" class="form-control"  name="code[]" aria-required="true" data-rule-required="true" data-rule-maxlength="4" >
                              </td>
                              <td style="display:none;"> 
                                 <input type="hidden" id="min_time${x}" class="form-control" value="${min_time}"  name="min_time[]" aria-required="true" data-rule-required="true" data-rule-maxlength="5">
                               </td>
                               <td>
                                 <input type='checkbox' name='record'>
                              </td>
                           </tr>`;

                        $('.make-editable').prop("disabled", false);

                         $(".analysis-save-section").css('display', 'block');
                         $("#edit-analysis-button").css('display', 'none');

                        $("table #analysis-tbody").append(markup);

                        $(".analysis-save-section").css('display', 'block');

                    });



                   $("#delete-analysis").click(function(){
                        $("table #analysis-tbody").find('input[name="record"]').each(function(){
                           if($(this).is(":checked")){
                                $(this).parents("tr").remove();
                            }
                        });
                     });

                   $("#edit-analysis-button").click(function(event){
                        event.preventDefault();
                         $('.make-editable').prop("disabled", false);
                         $(".analysis-save-section").css('display', 'block');
                         $("#edit-analysis-button").css('display', 'none');

                     
                   });



                   
                  // analysis Rate

                  //Subscription

       $("#add-sub-row").click(function(){

                        y++;
                      
                        var count = $("#count").val();
                        
                        var analysis_id = $("#subscription #analysis").val();

                        if(!count){
                              return false;
                        }

                        var jaba=0;
                        
                        jQuery('#subscri-tbody .sub_analysis_id').each(function(){

                          var text = $(this).val(); 

                           if(text == analysis_id ){
                                
                                alert("Already Exists");
                                jaba = 1;
                                return false;
                               
                           } 

                        });

                        if(jaba == 1){
                          return false;
                        }

                        var analysis_name = $("#subscription #analysis option:selected").html();
                        var markup =`
                           <tr>
                               <td hidden><input type="hidden" class="sub_analysis_id" value="${analysis_id}" name="sub_analysis_id[]"></td>
                               <td>${y}</td>
                               <td style="text-align: left;">${analysis_name}</td>
                               <td> 
                                 <input type="number" id="count${y}" class="form-control" value="${count}"  name="sub_count[]" data-rule-required="true">
                               </td>
                               <td>
                                 <input type='checkbox' name='record'>
                              </td>
                           </tr>`;

                        $("table #subscri-tbody").append(markup);

                         $("#save-subcount-button").css('display', 'block');
                        $("#edit-subcount-button").css('display', 'none');
                         $('.make-subs-editable').prop("disabled", false);

                      

                    });

       
                    
                    $("#delete-subscri").click(function(){
                        $("table #subscri-tbody").find('input[name="record"]').each(function(){
                           if($(this).is(":checked")){
                                $(this).parents("tr").remove();
                            }
                        });
                     });

                    $("#edit-subcount-button").click(function(event){

                        event.preventDefault();
                        $('.make-subs-editable').prop("disabled", false);
                        $("#save-subcount-button").css('display', 'block');
                        $("#edit-subcount-button").css('display', 'none');

                     
                   });

                  //Subscription

                  //Discount



                     $("#add-discount").click(function(event){

                        z++;
                        
                        event.preventDefault(); 
                        var minimum_value = $("#minimum_value").val();
                        var maximum_value = $("#maximum_value").val();
                        var percentage = $("#percentage").val();

                        

                        if(!minimum_value){
                                return false;
                        }
                        if(!maximum_value){
                                return false;
                        }
                        if(!percentage){
                                return false;
                        }

                        var markup =`<tr>
                                        <td>${z}</td>
                                        <td> 
                                           <input type="number" id="minimum_value${z}" class="form-control" value="${minimum_value}"  name="minimum_value[]" data-rule-required="true">
                                        </td>
                                        <td> 
                                           <input type="number" id="maximum_value${z}" class="form-control"  name="maximum_value[]" value="${maximum_value}" aria-required="true">
                                        </td>
                                        <td>
                                            <input type="number" id="percentage${z}" class="form-control" name="percentage[]" min="0.01" max="100" value="${percentage}" aria-required="true">
                                        </td>     
                                        <td>
                                           <input type='checkbox' name='record'>
                                        </td>
                                     </tr>
                                    `;

                            $("table #discount-tbody").append(markup);  

                            $('.make-disc-editable').prop("disabled", false);
                            $(".save-discount-range").css('display', 'block');
                            $("#edit-discount-range").css('display', 'none');


                            var new_maxivalue =  parseInt(maximum_value) + 1;

                            $("#minimum_value").val(new_maxivalue);

                             $("#maximum_value").val("");
                             $("#percentage").val("");

                            $("#edit-discount-range").css('display', 'none');


                      });

                     $("#delete-discount").click(function(){
                        $("table #discount-tbody").find('input[name="record"]').each(function(){
                           if($(this).is(":checked")){
                                $(this).parents("tr").remove();
                            }
                        });
                     });

                  $("#edit-discount-range").click(function(event){

                        event.preventDefault();
                        $('.make-disc-editable').prop("disabled", false);
                        $(".save-discount-range").css('display', 'block');
                        $("#edit-discount-range").css('display', 'none');

                     
                   });

                  
             });
         </script>
         <div class="row">
         <div class="col-md-6">
            <h2>Analysis Rates</h2>
         </div>
         <div class="admin_table">
            <table class="admin">
               <thead>
                  <tr>
                     <th>S.No.</th>
                     <th>Analysis</th>
                     <th>Rate</th>
                     <th>Code</th>
                     <th style="display:none;">Minimum Time</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody id="analysis-tbody">
                  <?php
                     if(isset($analyses_rate) && $analyses_rate != false){
                       if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
                       foreach ($analyses_rate as $key => $value) { 
                       $analysis = $this->Admindb->get_by_id('analyses',$value['analysis'])['name'];
                       $user = $this->Admindb->get_by_id('users',$value['customer'])['name'];
                     
                       ?>
                  
                  <tr>
                      <td hidden><input class="analysis_ids" type="hidden" value="<?=$value['analysis'] ?>" name="analysis_id[]"></td>
                      <td><?php echo ($key+1)+(($page-1) * 10); ?></td>
                      <td style="text-align: left;"><?=$analysis ?></td>
                     
                      <td> 
                          <input type="number" disabled id="rate_<?= $key ?>" class="make-editable form-control" value="<?=$value['rate'] ?>"  name="rate[]" data-rule-required="true" aria-required="true">
                      </td>
                      <td>
                       <!--   <input disabled value="<?=$value['code'] ?>" type="number" id="code_<?= $key ?>" class="make-editable form-control"  name="code[]" data-rule-required="true" data-rule-maxlength="4" aria-required="true" >-->
                       <input disabled value="<?=$value['code'] ?>" type="text" id="code_<?= $key ?>" class="make-editable form-control"  name="code[]" data-rule-required="true"  aria-required="true" >
                     </td>
                      <td style="display:none;"> 
                          <input type="hidden" disabled id="min_time<?= $key ?>" class="make-editable form-control" value="<?=$value['min_time'] ?>"  name="min_time[]" data-rule-required="true" aria-required="true" >
                      </td>
                      <td>
                        <input class="make-editable" disabled type='checkbox' name='record'>
                     </td>
                  </tr>


                  <?php  } } ?>
               </tbody>
            </table>
            </div>
             <?php if(isset($analyses_rate) && $analyses_rate != false){ ?>

                <div  style="padding: 15px;" class="col-md-12 analysis-save-section">
                     <button id="edit-analysis-button" class="btn btn-primary pull-right btn-flat">Edit</button>
               </div>
             
             <?php } ?> 
               <div  style="display: none; padding: 15px;" class="col-md-12 analysis-save-section">
                     <button type="submit" name="submit_add_rate" class="btn btn-primary pull-right btn-flat">Submit</button>
               </div>

            </form>
            <?php
               if(isset($analyses_rate)  && $analyses_rate != false){
                foreach ($analyses_rate as $key => $value_outer) { 
               $analysis = $this->Admindb->table_full('analyses');
                 ?>
            <div id="Modal<?=$value_outer['id']?>" class="modal fade" role="dialog">
               <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Analysis Rates</h4>
                     </div>
                     <div class="modal-body">
                        <form role="Form" method="post" action="" class="" accept-charset="UTF-8" autocomplete="off" data-form-validate="true"  novalidate="novalidate">
                           <div class="form-group">
                              <label for="analysis">Analysis</label>
                              <select name="analysis" id="analysis" class="form-control" required  data-rule-required="true" >
                                 <option selected disabled>Choose Analysis</option>
                                 <?php 
                                    foreach ($analysis as $key => $value) {
                                      if(isset($value_outer['analysis']) && $value_outer['analysis'] == $value['id']) $sel = 'selected'; else $sel = '';
                                      echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
                                    }
                                    ?>
                              </select>
                              <input type="hidden" name="id" value="<?=$value_outer['id'] ?>">
                              <input type="hidden" name="customer" value="<?=$_GET['edit'] ?>">
                           </div>
                           <div class="form-group">
                              <label for="rate">Rate</label>
                              <input type="number" id="rate" class="form-control" required name="rate"value="<?=$value_outer['rate'] ?>"  data-rule-required="true" >
                           </div>
                           <div class="form-group">
                              <label for="code">Code</label>
                              <input type="text" id="code" class="form-control" required name="code"value="<?=$value_outer['code'] ?>"  data-rule-required="true" >
                           </div>
                           <div class="form-group ">
                              <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="update_price">Update</button>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>
            <?php
               }}
                 ?>
         </div>
      </div>





<div id="maint_fees" class="tab-pane fade">
          <div class="col-md-12"> <h2>Maintenance Fees</h2></div>   
         <form class="container-fluid " method="post" enctype="multipart/form-data" data-form-validate="true"  novalidate="novalidate">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="types">Type</label>
                     <select name="maintenance_fee_type" required id="maintenance_fee_type" class="form-control" data-rule-required="true" aria-required="true">
                        <option selected disabled>Choose Type</option>
                        <option value="monthly" <?php if(isset($meta->maintenance_fee_type) && $meta->maintenance_fee_type == 'monthly') echo "selected"; ?>>Monthly</option>
                        <option value="yearly" <?php if(isset($meta->maintenance_fee_type) && $meta->maintenance_fee_type == 'yearly') echo "selected"; ?>>Yearly</option>
                                           
                      </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="maint_fee">Amount</label>
                     <input type="number" id="maintenance_fee_amount" required class="form-control" name="maintenance_fee_amount" value="<?=$this->issetEcho($meta,'maintenance_fee_amount') ?>">
                     
                  </div>
               </div>
               
               <div class="col-md-2">
                  <div class="form-group">
                     <label for="code" style="visibility: hidden; display: block">add</label>
                     <button type="submit" name="submit_maint_fees" class="btn btn-primary btn-flat pull-right">Set Fee</button>
                  </div>
               </div>

              
            </div>
         </form>
              
              <div class="col-md-12"><h2>Maintenance Fees</h2></div>
           
         <div class="admin_table">
            <table class="admin">
               <thead>
                  <tr>
                     <th>S.No.</th>
                     <th>Maintenance type</th>
                     <th>Maintenance Amount</th>
                    <!--  <th>Action</th> -->
                  </tr>
               </thead>
               <tbody>
                  <?php  if(isset($maintenance) && $maintenance != false){ ?>
                      <tr>
                        <td>1</td>
                        <td><?php echo $maintenance['maintenance_fee_type']; ?></td>
                        <td><?php echo $maintenance['maintenance_fee_amount']; ?></td>
                      </tr>
                  <?php  } ?>
               </tbody>
            </table>
           
         </div>
            
      </div>
<!---------------------------- Agreements Upload ---------------------------->
<div id="upload_docs" class="tab-pane fade">
    <div class="col-md-12"> 
      <span><h2>Agreements</h2></span>
    </div>
      <div class="col-md-11"><button class="btn btn-primary" id="add_more_section" data-toggle="tooltip" data-placement="top" title="To add multiple agreements in single upload!" style="float: right;">Add Multiple Rows +</button></div>
    
    <form name="frmCustomerDoc" id="frmCustomerDoc" method="post" enctype="multipart/form-data" class="container-fluid" data-form-validate="true"  novalidate="novalidate">
      <div class="c_wrapper" id="c_wrapper">
         <div class="row repeated_row row_repeat_1">

            <div class="col-md-3">
              <div class="form-group">
                 <label for="document_title_1">Title</label>
                 <input type="text" id="document_title_1" required class="form-control" name="document_title_1" value="">
                 
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                 <label for="document_desc_1">Description</label>
                 <textarea id="document_desc_1" required class="form-control" name="document_desc_1" rows="1" style="padding:6px;"></textarea>
                 
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                 <label for="document_docs_1">Document(.doc,.pdf)</label>
                 <input type="file" id="document_docs_1" required class="form-control" name="document_docs_1" value="" accept=".pdf, .doc">
                 
              </div>
            </div>

         </div>
      </div>
      <div class="form-group ">
          <input type="hidden" name="doc_count" id="doc_count" value="1">
          <input type="hidden" name="customer" id="customer" value="<?=$_GET['edit'] ?>">
          <button type="submit" class="btn btn-primary btn-flat " id="submit_agreement" name="submit_agreement">Upload</button>
       </div>
    </form>
<!-------------------------------- Agreements List ------------------------------->
    <div class="row">
      <div class="col-md-12"><h2>Agreements</h2></div>
      <div class="admin_table">
        <table class="admin">
           <thead>
              <tr>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Actions</th>
              </tr>
           </thead>
           <tbody>
              <?php  if(isset($agreements) && $agreements != false): ?>
                <?php foreach ($agreements as $key => $value) : ?>
                  <tr>
                    <td><?php echo $value['ACD_Docs_Title'] ?></td>
                    <td><?php echo $value['ACD_Docs_Desc']  ?></td>
                    <td id="status-<?php echo $value['ACD_ID_PK'];?>"><?php echo ($value['ACD_Status']==1)?'<span class="pcoded-badge label label-primary">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td>
                    <td><a href="<?php echo 'http://dicon.tecbirds.com/assets/uploads/customer/'.$value['ACD_Customer_ID_FK'].'/'.$value['ACD_Docs_Path'];?>" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a></td>
                  </tr>
                <?php endforeach; ?>
                <?php else : ?>
                  <tr><td colspan="4">No Agreements found!!</td></tr>
              <?php  endif; ?>
           </tbody>
        </table>
             
      </div>
    </div>
<!-------------------------------- Agreements List ------------------------------->
</div>
<!-------------------------------- Bills Upload ------------------------------->

<div id="upload_bills" class="tab-pane fade">
    <div class="col-md-12"> 
      <span><h2>Bills</h2></span><span class="row pull-right"><button class="btn btn-primary" id="add_more_bills" data-toggle="tooltip" data-placement="top" title="To add multiple bills in single upload!">Add Multiple Rows +</button></span>
    </div>
    <form name="frmCustomerBills" id="frmCustomerBills" method="post" enctype="multipart/form-data" class="container-fluid" data-form-validate="true"  novalidate="novalidate">
      <div class="b_wrapper" id="b_wrapper">
          <div class="row repeated_brow brow_repeat_1">
              <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_title_1">Title</label>
                       <input type="text" id="bill_title_1" required class="form-control" name="bill_title_1" value="">  
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_desc_1">Description</label>
                       <textarea id="bill_desc_1" required class="form-control" name="bill_desc_1" rows="1" style="padding:6px;"></textarea>
                       
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_desc_1">Invoice No</label>
                       <input type="text" id="bill_invoice_1" required class="form-control" name="bill_invoice_1" rows="1" style="padding:0;">
                       
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_desc_1">Due date</label>
                       <input type="text" id="bill_due_1" required class="form-control" name="bill_due_1" rows="1">
                       
                    </div>
                  </div>

                  
              </div>
              
              <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_date_1">Month & Year</label>
                       <input type="text" id="bill_date_1" required class="form-control" name="bill_date_1" rows="1" style="padding:0;">
                       
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_total_1">Total</label>
                       <input type="text" id="bill_total_1" required class="form-control" name="bill_total_1" rows="1" style="padding:0;">
                       
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_discount_1">Discount</label>
                       <input type="text" id="bill_discount_1" required class="form-control" name="bill_discount_1" rows="1" style="padding:0;">
                       
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                       <label for="bill_invoice_amt_1">Invoice Amount</label>
                       <input type="text" id="bill_invoice_amt_1" required class="form-control" name="bill_invoice_amt_1" rows="1" style="padding:0;">
                       
                    </div>
                  </div>

              </div>
              <!-- <div class="row"> -->
                <div class="row col-md-3">
                  <div class="form-group">
                     <label for="bill_docs_1">Document(.doc,.pdf)</label>
                     <input type="file" id="bill_docs_1" required class="form-control" name="bill_docs_1" value="" accept=".pdf, .doc">
                     
                  </div>
                </div>
                <div class="col-md-8"></div>
              <!-- </div> -->
          
          </div>
          <!-- <hr> -->
          
      </div>
      <div class="form-group ">
        <input type="hidden" name="bill_count" id="bill_count" value="1">
        <input type="hidden" name="customer" id="customer" value="<?=$_GET['edit'] ?>">
        <button type="submit" class="btn btn-primary btn-flat " id="submit_bill" name="submit_bill">Upload Bills</button>
     </div>
    </form>

    <div class="row">
      <div class="col-md-12"><h2>Bills</h2></div>
      <div class="admin_table">
        <table class="admin">
           <thead>
              <tr>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Invoice No</th>
                  <th>Month & Year</th>
                  <th>Due Date</th>
                  <th>Total</th>
                  <th>Discount</th>
                  <th>Invoice Amount</th>
                  <th>Status</th>
                  <th>Actions</th>
              </tr>
           </thead>
           <tbody>
              <?php  if(isset($bills) && $bills != false): ?>
                <?php foreach ($bills as $key => $value) : ?>
                  <tr>
                    <td><?php echo $value['ACB_Bills_Title'] ?></td>
                    <td><?php echo $value['ACB_Bills_Desc']  ?></td>
                    <td><?php echo $value['ACB_Bills_Invoice_No'] ?></td>
                    <td><?php echo date('F', mktime(0, 0, 0, $value['ACB_Bills_Month'], 10));  ?>, <?php echo $value['ACB_Bills_Year'] ?></td>
                    <td><?php echo date('d-m-Y',strtotime($value['ACB_Bills_Due'] ))?></td>
                    <td><?php echo $value['ACB_Bills_Total']  ?></td>
                    <td><?php echo $value['ACB_Bills_Discount'] ?></td>
                    <td><?php echo $value['ACB_Bills_Invoice_Amount']  ?></td>
                    <td id="status-<?php echo $value['ACB_ID_PK'];?>"><?php echo ($value['ACB_Status']==1)?'<span class="pcoded-badge label label-primary">Active</span>':'<span class="pcoded-badge label label-danger">Block</span>';?></td>
                    <td><a href="<?php echo 'http://dicon.tecbirds.com/assets/uploads/customer/bills/'.$value['ACB_Customer_ID_FK'].'/'.$value['ACB_Bills_Path'];?>" class="btn btn-info btn-circle btn-outline" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a></td>
                  </tr>
                <?php endforeach; ?>
                <?php else : ?>
                  <tr><td colspan="10">No bills found!!</td></tr>
              <?php  endif; ?>
           </tbody>
        </table>
             
      </div>
    </div>
</div>
















<div id="subscription" class="tab-pane fade">
<form class="container-fluid " id="admin_form2" method="post" data-form-validate="true"  novalidate="novalidate">
            <?php 

            if(isset($subscription) && !empty($subscription))
            $arry_to_remove = array_column($subscription, 'analysis');
            
            $analysis =  $this->Admindb->get_my_analyses($_GET['edit'])['results'];

          
               ?>
            <div class="row">
               <div class="col-md-5">
                  <div class="form-group">
                     <label for="analysis">Analysis</label>
                     <select  id="analysis" class="form-control">
                        <option selected disabled>Choose Analysis</option>
                        <?php 
                           foreach ($analysis as $key => $value) {
                            //  if(in_array($value['id'], $arry_to_remove)) continue;
                              //if(isset($edit['analysis']) && $edit['analysis'] == $value['id']) $sel = 'selected'; else $sel = '';
                              echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
                           }
                           ?>
                     </select>
                     <input type="hidden" name="customer" id="customer" value="<?=$_GET['edit'] ?>" >
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="count">Count</label>
                     <input type="number" id="count" class="form-control">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label for="code" style="visibility: hidden; display: block">add</label>
                   
                     <button type="button" id="add-sub-row" class="btn btn-primary btn-flat">Add To List</button>
                     <button type="button" id="delete-subscri" class="btn btn-primary btn-flat pull-right">Delete</button>
                  </div>
               </div>
            </div>
         <h2>Subscription Analysis</h2>
         <div class="row">
         <div class="admin_table">
            <table class="admin">
               <thead>
                  <tr>
                     <th>S.No.</th>
                     <th>Analysis</th>
                     <th>Count</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody id="subscri-tbody">
                  <?php
                     $total = 0;
                     if(isset($subscription) && $subscription != false){
                       if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
                      foreach ($subscription as $key => $value) { 
                     
                     //print_r($this->Admindb->get_by_id('users',$value['customer']));
                     $analysis = $this->Admindb->get_by_id('analyses',$value['analysis'])['name'];
                     $user = $this->Admindb->get_by_id('users',$value['customer'])['name'];
                     $rate = $this->Admindb->get_rate_by_anid_cid($value['analysis'],$value['customer'])['rate'];
                     $total = $total + ($rate * $value['count']);
                       ?>
                  <tr>
                     <td hidden>
                        <input type="hidden" class="sub_analysis_id" value="<?= $value['analysis']; ?>" name="sub_analysis_id[]">
                     </td>
                     <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td>
                     <td style="text-align: left;" data-label="Analysis"><?=$analysis ?></td>
                     <td> 
                        <input disabled type="number" id="count<?=$key ?>" class="form-control make-subs-editable"  value="<?= $value['count'] ?>"  name="sub_count[]" data-rule-required="true">
                     </td>
                     <td>
                         <input class="make-subs-editable" disabled type='checkbox' name='record'>
                     </td>
                  </tr>
                  <?php  } } ?>
               </tbody>
            </table>
       

                     <input type="hidden" id="total" readonly class="form-control"  name="total" value="<?=$total ?>"  >
         <br>
         <div class="row">
         <div class="col-md-5 float-left total_save_div">
            <?php  
            if($alert_data['msg'] == 'Subscription analysis created successfully'){

               ?>
               <div class="alert alert-danger">The amount shown below is the total amount calculated by the rate and count for the respective analysis. Please make necessary changes and save the new anmount.</div>
               <label for="count">Subscription Amount</label>
               <div class="input-group">
                  <input type="hidden" name="cid" id="cid" value="<?=$edit['user_id'] ?>">
                   <input type="number" id="total"  class="form-control"  name="total" value="<?=$total ?>"  > <span class="input-group-btn"> 
                  <button class="btn btn-primary btn-flat save_total_ajax" type="button">Save</button> </span> 
               </div>




               <?php
            }else{



               if(isset($meta->subscription_amount)) $total = $meta->subscription_amount;
               else $total = '';
               ?><!-- 
               <div class="alert alert-success">The amount </div> -->
               <label for="count">Subscription Amount</label>


               <div class="edit_box" style="position: relative;">
            <div class="input-group">
               <input type="hidden" name="cid" id="cid" value="<?=$edit['user_id'] ?>">
                <input type="number" id="total"  class="form-control"  name="total" value="<?php echo $subscription_fees['subscription_fees']; ?>"  > <span class="input-group-btn"> 
               <button class="btn btn-primary btn-flat save_total_ajax" type="button">Save</button> </span> 
            </div>

               <div class="input-group overlay" style="    position: absolute; top: 0;">
                   <input type="number" id="total"  class="form-control" readonly   name="total" value="<?php echo $subscription_fees['subscription_fees']; ?>"  > <span class="input-group-btn"> 
                  <button class="btn btn-primary btn-flat " onclick="edit_amount();" type="button">Edit</button> </span> 
               </div>

            </div>
               <?php
            }


            ?>
            
         </div>

         <div  style="padding: 15px;" class="col-md-7 ">
                     <?php if(isset($subscription) && $subscription != false){ ?>

                           <button id="edit-subcount-button" class="btn btn-primary pull-right btn-flat">Edit</button>
                           <button style="display: none;" id="save-subcount-button" type="submit" name="submit_subscription" class="btn btn-primary btn-flat pull-right">Submit</button>
                     
                     <?php }else{ ?>
                        
                        <button type="submit" name="submit_subscription" class="btn btn-primary btn-flat pull-right">Submit</button>

                     <?php } ?>
        
         </div>

         </div> 


           </form>
                     </div>














        
            <?php
               if(isset($subscription)  && $subscription != false){
                foreach ($subscription as $key => $value_outer) { 
               $analysis = $this->Admindb->table_full('analyses');
                 ?>
            <div id="Modal_subscription<?=$value_outer['id']?>" class="modal fade" role="dialog">
               <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Analysis</h4>
                     </div>
                     <div class="modal-body">
                        <form role="Form" method="post" action="" class="" accept-charset="UTF-8" autocomplete="off" data-form-validate="true"  novalidate="novalidate">
                           <div class="form-group">
                              <label for="analysis">Analysis</label>
                              <select name="analysis" id="analysis" class="form-control" required  data-rule-required="true" >
                                 <option selected disabled>Choose Analysis</option>
                                 <?php 
                                    foreach ($analysis as $key => $value) {
                                      if(isset($value_outer['analysis']) && $value_outer['analysis'] == $value['id']) $sel = 'selected'; else $sel = '';
                                      echo '<option value="'.$value['id'].'" '.$sel.' >'.$value['name'].'</option>';
                                    }
                                    ?>
                              </select>
                              <input type="hidden" name="id" value="<?=$value_outer['id'] ?>">
                              <input type="hidden" name="customer" value="<?=$_GET['edit'] ?>">
                           </div>
                           <div class="form-group">
                              <label for="count">Count</label>
                              <input type="number" id="count" class="form-control" required name="count" value="<?=$value_outer['count'] ?>"  data-rule-required="true" >
                           </div>
                           <div class="form-group ">
                              <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="update_subscription">Update</button>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>
            <?php
               }}
                 ?>
         </div>
      </div>
    
    <div id="discount" class="tab-pane fade">
      
        <div class="col-md-12">
         <h2>Monthly Quantity Discount Pricing</h2>
         </div>

            <form  class="container-fluid " method="post" enctype="multipart/form-data" data-form-validate="true"  novalidate="novalidate">
            <div class="row">
              <div class="col-md-3">
                  <div class="form-group">
                     <label for="minimum_value">From</label>
                     <input type="number" id="minimum_value" min="<?= $max_disc['max_value']+1 ?>" class="form-control"     >
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label for="max">To</label>
                     <input type="number" id="maximum_value" class="form-control"   min="<?= $max_disc['max_value']+2 ?>"   >
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label for="percentage">Percentage</label>
                        <input type="number" id="percentage" class="form-control"  min="0.01" max="100" >
                        <input type="hidden"  name="customer" id="customer" value="<?=$_GET['edit'] ?>" >
                  </div>
                  </div>
             

               <div class="col-md-3">
                  <div class="form-group">
                     <label for="code" style="visibility: hidden; display: block">add</label>
                     <button id="add-discount" class="btn btn-primary btn-flat">Add to List</button>
                     <button type="button" id="delete-discount" class="btn btn-primary btn-flat pull-right">Delete</button>
                   
                  

                  </div>
               </div>
            </div>
        <div class="admin_table">
            <table class="admin">
               <thead>
                  <tr>
                     <th>S.No.</th>
                     <th>From</th>
                     <th>To</th>
                     <th>Percentage</th>
                     <th>Action</th>
                  </tr>
               </thead>

               <tbody id="discount-tbody">
                  <?php
                     if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
                     if(isset($discount_pricing_list['results'])){
                     foreach ($discount_pricing_list['results'] as $key => $value) { ?>
                      <tr>
                          <td data-label="S.No."><?php echo ($key+1)+(($page-1) * 10); ?></td>
                          <td> 
                             <input disabled type="number" id="minimum_value<?= $key ?>" class="make-disc-editable form-control" value="<?=$value['minimum_value'] ?>" name="minimum_value[]" data-rule-required="true">
                          </td>
                          <td> 
                             <input disabled type="number" id="maximum_value<?= $key ?>" value="<?=$value['maximum_value'] ?>" class="make-disc-editable form-control" name="maximum_value[]"  aria-required="true">
                          </td>
                          <td>
                              <input disabled type="number" id="percentage<?= $key ?>" class="make-disc-editable form-control" name="percentage[]" min="0.01" max="100" value="<?php echo $value['percentage'];?>" aria-required="true">
                          </td>     
                          <td>
                             <input disabled class="make-disc-editable" type="checkbox" name="record">
                          </td>
                       </tr>
                  <?php  }} ?>

                 
                 
               </tbody>
            </table>
                <div  style="padding: 15px 0px;" class="col-md-12 ">
                    <?php if(isset($discount_pricing_list['results'])){ ?>

                      <button type="submit" style="display: none;" name="dis_submit" class="pull-right save-discount-range btn btn-primary btn-flat">Save</button>
                    
                      <button id="edit-discount-range" class="pull-right btn btn-primary btn-flat">Edit</button>

                    <?php }else{ ?>

                      <button type="submit"  style="display: none;" name="dis_submit" class="pull-right save-discount-range btn btn-primary btn-flat">Save</button>

                    <?php } ?>
                </div>  
            </div>      
          </form>
         </div>
    </div>  
   </div>
   
</div>
</div>

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

  <input type="hidden" name="id" value="<?= $edit['user_id'] ?>">
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
