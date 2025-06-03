<div class="dashboard_body content-wrapper dashboard_body_set">
  <?php
function issetecho($var){
if(isset($var)) echo $var; else echo "";
}
function issetecho3($var){
if(isset($var) && $var !="") echo $var; else echo "0";
}

?>
<section class="content pull-left">
    <?php $this->alert(); ?>
          <div class="box box-primary pull-left">
  <div class="box-header with-border">
              <h2 class="box-title">Worksheet Details</h2>
            </div>

<div class="col-md-12">
  <table class="structure1">
    <tbody>

      <tr>
        <td>Accession</td>
        <td><?=$edit['accession'] ?></td>
      </tr>
      <tr>
        <td>MRN</td>
        <td><?=$edit['mrn'] ?></td>
      </tr>
      <tr>
        <td>Patient Name</td>
        <td><?=$edit['patient_name'] ?></td>
      </tr>
      <tr>
        <td>Site Procedure</td>
        <td><?=$edit['site_procedure'] ?></td>
      </tr>
      <tr>
        <td>Exam Time</td>
        <td><?=$edit['exam_time'] ?></td>
      </tr>
      <tr>
        <td>Status</td>
        <td><?=$edit['status'] ?></td>
      </tr>
      <tr>
        <td>Priority</td>
        <td><?=$edit['priority'] ?></td>
      </tr>
      <tr>
        <td>Site</td>
        <td><?=$edit['site'] ?></td>
      </tr>
      <tr>
        <td>Hospital</td>
        <td><?=$edit['hospital'] ?></td>
      </tr>


    </tbody>
  </table>





  <div class="form-group">
    <label for="other">Other</label>
    <input type="text" id="other" class="form-control" readonly required name="other" value="<?php issetecho($edit_wsheet['other'])?>">
  </div>




  <div class="form-group">
    <label for="other">Status</label>
    <input type="text" id="other" class="form-control" readonly required name="other" value="<?php issetecho($edit_wsheet['status'])?>">
  </div>









  <div class="form-group">
    <label for="custom_analysis_description">Analysis Performed</label>
    <input type="text" id="custom_analysis_description" class="form-control" required name="custom_analysis_description" readonly value="<?php issetecho($edit_wsheet['analyses_performed'])?>">
  </div>




  <div class="form-group">
    <label for="custom_analysis_description">Custom Analysis Description</label>
    <input type="text" id="custom_analysis_description" class="form-control" required name="custom_analysis_description" readonly value="<?php issetecho($edit_wsheet['custom_analysis_description'])?>">
  </div>




  <div class="form-group">
    <label for="other_notes">Other Notes</label>
    <input type="text" id="other_notes" class="form-control" required name="other_notes" readonly value="<?php issetecho($edit_wsheet['other_notes'])?>">
  </div>

<!-- <div class="form-group col-md-3 col-sm-6 col-xs-12">
    <label for="addon_flows">Addon Flows</label>
    <input type="number"  id="addon_flows" class="form-control" readonly required name="addon_flows" value="<?php// issetecho($edit_wsheet['addon_flows'])?>">
  </div> -->

<div class="row">


<?php

$analyst_hours = $this->time_convert($edit_wsheet['analyst_hours'],'min',true);
$image_specialist_hours = $this->time_convert($edit_wsheet['image_specialist_hours'],'min',true);
$medical_director_hours = $this->time_convert($edit_wsheet['medical_director_hours'],'min',true);

?>

  <div class="form-group col-md-4 col-sm-6 col-xs-12">
    <label for="analyst_hours">Analyst Hours</label><br>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Hours</label>
      <input type="number" data-rule-required="true" readonly placeholder="Hours" id="analyst_hours" class="form-control" required name="analyst_hours[]" value="<?php issetecho3($analyst_hours[0])?>">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Minute</label>
      <input type="number" data-rule-required="true" readonly placeholder="Minute" id="analyst_hours2" class="form-control" required name="analyst_hours[]" value="<?php issetecho3($analyst_hours[1])?>">
    </div>
  </div>




  <div class="form-group  col-md-4 col-sm-6 col-xs-12">
    <label for="image_specialist_hours">Image Specialist Hours</label><br>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Hours</label>
      <input type="number" data-rule-required="true" placeholder="Enter Image Specialist Hours" id="image_specialist_hours" class="form-control" readonly required name="image_specialist_hours[]" value="<?php issetecho3($image_specialist_hours[0])?>">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Minute</label>
      <input type="number" data-rule-required="true" placeholder="Enter Image Specialist Hours" id="image_specialist_hours2" class="form-control" readonly required name="image_specialist_hours[]" value="<?php issetecho3($image_specialist_hours[1])?>">
    </div>
  </div>




  <div class="form-group  col-md-4 col-sm-6 col-xs-12">
    <label for="medical_director_hours">Medical Director Hours</label><br>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Hours</label>
      <input type="number" data-rule-required="true" placeholder="Enter Medical Director Hours " id="medical_director_hours" class="form-control" readonly required name="medical_director_hours[]" value="<?php issetecho3($medical_director_hours[0])?>">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <label>Minute</label>
      <input type="number" data-rule-required="true" placeholder="Enter Medical Director Hours " id="medical_director_hours2" class="form-control" readonly required name="medical_director_hours[]" value="<?php issetecho3($medical_director_hours[1])?>">
    </div>
  </div>  



</div>
</div>


        </div>
    </section>
</div>