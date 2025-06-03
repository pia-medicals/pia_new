<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PIA Medical - Analyst Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../" class="h1"><b>PIA</b> Medical</a>
            </div>
            <div class="card-body">
                <!-- <p class="login-box-msg">Sign in to start your session</p> -->
                <p class="login-box-msg">
                    Analyst Login
                    <!-- <?= ADMIN_LTE3 ?> -->
                    <!-- <a href="<?php echo SITE_URL; ?>/assets/js/jquery.validate.min.js">test link</a> -->
                </p>
                <?php
                $this->alert();
                ?>
                <!-- <form enctype="multipart/form-data" method="post" accept-charset="utf-8" action=""> -->
                <form id="analystLoginForm" method="post" accept-charset="utf-8">
                    <div class="row mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Email ID">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" id="anlstLogBtn" name="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= ADMIN_LTE3 ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= ADMIN_LTE3 ?>/dist/js/adminlte.min.js"></script>
    <!-- jQuery Validate JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/analyst.validate.js"></script>
    <!-- <script type="text/javascript">
        
    </script> -->
</body>

</html>