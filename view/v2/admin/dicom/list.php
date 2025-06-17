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
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                <div class="col-12">
                    <div class="card data-tb-style">
                        <div class="card-header">
                            <h3 class="card-title">List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Received Date</th>      
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Default TAT</th>
                                        <th style="width:180px">Customer</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>                     
                                <tfoot>
                                    <tr>
                                        <th>Received Date</th>      
                                        <th>Accession</th>
                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Default TAT</th>
                                        <th style="width:180px">Customer</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= ADMIN_LTE3 ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        var dataTable = $('#dataTbl').DataTable({         
                "lengthMenu": [[50, 100, 500], [50, 100, 500]],
                "order": [[0, "desc"]],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/ajaxV2/get_open_worksheets_info",
                    type: "post"
                }
            });
            setInterval(function () {
                dataTable.ajax.reload();
            }, 120000);   
            
        $('#dataTbl').on('click', '.delete_link', function (e) {
            e.preventDefault(); 
            const deleteUrl = $(this).attr('href'); 
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this?",
                icon: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: "No, Cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl; 
                }
            });
        });    
            
    });
</script>