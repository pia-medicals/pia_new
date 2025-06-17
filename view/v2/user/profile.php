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
                        <form method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off" enctype="multipart/form-data">
                            <div class="card-body">
                                <?php $this->alert(); ?>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="name" class="form-control" required="" name="name" value="<?= $edit->user_name ?>" >
                                    <input type="hidden" name="id" value="<?= $edit->user_id ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="emailaddr" class="form-control" required="" name="email" placeholder="Example: john.doe@gmail.com" value="<?= $edit->email ?>">
                                </div>

                                <!-- <div class="form-group">
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
                                    <input type="password" id="password" class="form-control" name="password" value="">
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btnSubmit" name="btnSubmit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="btn btn-secondary float-right">Back</a>
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
<script>
    $(function () {
        bsCustomFileInput.init();
        $(".admin_form").validate();
    });
</script>