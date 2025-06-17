<div class="dashboard_body content-wrapper">
    <section class="content">
        <?php $this->alert(); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
              <h2 class="box-title"><?php echo $assigned_user_info['customer_name']; ?></h2>
            </div>

            <?php
            /*echo "<pre>";
            print_r($assigned_user_info);*/ ?>
            <form role="Form" method="post" action="" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                <!-- <div class="form-group">
                    <label for="multi-select-customers1">Select Customers</label>
                    <select id="multi-select-customers1" multiple="multiple" class="form-control" required="" name="customers[]">
                                    <?php foreach( $customers as $tmp=> $customer): ?>
                                    <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                </div> -->
                <div class="form-group">
                    <label for="select-analyst">Select Analyst</label>
                    <select class="form-control" id="select-analyst" required="" name="analyst">
                        <option value=""> Select </option>
                        <?php foreach( $analysts as $tmp=> $analyst): ?>
                        <option value="<?php echo $analyst['id'] ?>" <?php echo ($assigned_user_info['analyst_id'] == $analyst['id']) ? 'selected' : ''; ?>><?php echo $analyst['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group ">
                    <button type="submit" class="btn btn-primary btn-flat " id="submitbtn" name="submitedit">Update</button>
                    <a href="<?php echo SITE_URL.'/adminassign'; ?>" class="btn btn-default">Back</a>
                </div>
            </form>


        </div>
    </section>
</div>
<script type="text/javascript">
    /*$(document).ready(function() {
        $('#multi-select-customers').multiselect();
    });*/
</script>