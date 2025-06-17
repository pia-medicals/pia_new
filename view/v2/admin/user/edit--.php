<?php
switch ($_SESSION['user']->user_type_ids) {
    case 1:
        // $cntrlr = 'admin';
        $cntrlr = 'mydashboard';
        break;
    case 2:
        $cntrlr = 'manager';
        break;
    default:
        $cntrlr = 'dashboard';
        break;
}
$select_array = array(
    1 => 'Super Admin',
    2 => 'Manager',
    3 => 'Analyst',
    4 => 'Patient',
    5 => 'Customer'
);
?> 
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/validate/cmxform.css">
<script src="<?= ADMIN_LTE3 ?>/validate/jquery.validate.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo!empty($page_title) ? $page_title : ''; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo!empty($page_title) ? $page_title : ''; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" class="admin_form" action="<?= SITE_URL ?>/admin/user" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php $this->alert(); ?>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="name" class="form-control" required="" name="name" value="<?= $edit['user_name'] ?>">
                                    <input type="hidden" name="id" value="<?= $edit['user_id'] ?>">
                                </div>

                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="emailaddr" class="form-control" required="" name="email" placeholder="Example: john.doe@gmail.com" value="<?= $edit['email'] ?>">
                                </div>

                                <div class="form-group">                                                                 
                                    <label>Account Status</label>
                                    <div class="input-group">
                                        <input type="checkbox" id="active" name="active" <?php if ($edit['is_active'] == true) echo "checked"; ?> data-bootstrap-switch data-on-text="Active" data-off-text="Inactive" data-off-color="danger" data-on-color="success">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>User Type</label>
                                    <select id="group" class="form-control" required name="group_id">
                                        <?php foreach ($select_array as $key => $value): ?>
                                            <option value="<?= $key ?>" <?= $edit['user_type_ids'] == $key ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($value) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>             

                              <!--   <div class="form-group">
                                    <label>Profile Picture</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="profile_picture" id="profile_picture">
                                            <label class="custom-file-label" for="profile_picture">Choose File</label>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password" value="">
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/admin/user' ?>" class="btn btn-secondary float-right">Cancel <i aria-hidden="true" class="fa fa-redo"></i></a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->         
                </div>         
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= ADMIN_LTE3 ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        $(".admin_form").validate();
    });
</script>