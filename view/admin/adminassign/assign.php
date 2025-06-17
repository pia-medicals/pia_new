<link rel="stylesheet" type="text/css" href="https://rawgit.com/select2/select2/master/dist/css/select2.min.css">
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: #000 !important;
    }
</style>
<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
              <h2 class="box-title">Assign Customers</h2>
            </div>


            <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                <div class="form-group">
                    <label for="multi-select-customers1">Select Customers</label>
                    <select id="multi-select-customers1" multiple="multiple" class="form-control" required="" name="customers[]">
                                    <?php foreach( $customers as $tmp=> $customer): ?>
                                    <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                </div>
                <div class="form-group">
                    <label for="select-analyst">Select Analyst</label>
                    <select class="form-control" id="select-analyst" required="" name="analyst">
                        <option value=""> Select </option>
                        <?php foreach( $analysts as $tmp=> $analyst): ?>
                        <option value="<?php echo $analyst['id'] ?>"><?php echo $analyst['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group ">
                    <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submit">Assign</button>
                    <a href="<?php echo SITE_URL.'/adminassign'; ?>" class="btn btn-default">Back</a>
                </div>
            </form>


        </div>
    </section>
</div>
<script type="text/javascript" src="https://rawgit.com/select2/select2/master/dist/js/select2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#multi-select-customers1').select2({
            placeholder: 'Select Customers'
        });
    });
    /*$(document).ready(function() {
        $('#multi-select-customers').multiselect();
    });*/
</script>