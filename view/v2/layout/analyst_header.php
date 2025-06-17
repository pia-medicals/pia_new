<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>PIA Medical<?php echo !empty($page_title) ? ' - ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/css/my-style.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="<?= ADMIN_LTE3 ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= ADMIN_LTE3 ?>/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    
    <?php /*<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>*/ ?>
    
    <script>
        function mug_alert(type, msg) {
            Swal.fire({
                icon: type,
                title: msg,
                showConfirmButton: true
            });
        }

        function mug_alert_all(type, title, msg) {
            Swal.fire({
                icon: type,
                title: title,
                text: msg,
                showConfirmButton: true
            });
        }

        function mug_alert_lite(type, msg) {
            Swal.fire({
                position: "top-end",
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: 3000
            });
        }
    </script>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <?php
    // switch ($_SESSION['user']->user_type_ids) {
    //     case 1:
    //         //$cntrlr = 'admin';
    //         $cntrlr = 'mydashboard';
    //         break;
    //     case 2:
    //         $cntrlr = 'manager';
    //         break;
    //     default:
    //         $cntrlr = 'dashboard';
    //         break;
    // }
    ?>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="javascript:void(0);" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <!-- <a href="<?= SITE_URL . '/' . $cntrlr ?>" class="nav-link">Home</a> -->
                    <a href="<?= SITE_URL . '/analyst_dashboard' ?>" class="nav-link">Home</a>
                </li>
                    <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= SITE_URL . '/analyst_profile' ?>" class="nav-link">Profile</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <?php /*<a class="nav-link" href="<?php echo SITE_URL . '/logout'; ?>">
                            LOGOUT 
                            <i class="fas fa-sign-out-alt"></i>
                        </a>*/ ?>
                    <a class="nav-link" href="<?php echo SITE_URL . '/analyst_logout'; ?>">
                        LOGOUT
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->