<!-- Content Wrapper. Contains page content -->
<?php
        switch ($_SESSION['user']->user_type_ids) {
            case 1:
                $cntrlr = 'admin';
                break;
            case 2:
                $cntrlr = 'manager';
                break;
            default:
                $cntrlr = 'dashboard';
                break;
        }
?>  
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Welcome to PIA Medical!</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                   
                    
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->