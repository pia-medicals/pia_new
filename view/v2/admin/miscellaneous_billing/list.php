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
              <table id="admin_table" class="table table-bordered table-striped">





  <thead>
    <tr><!-- 
      <th>S.No.</th> -->
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
       <th>Client</th>
        <th>Date</th><!-- 
      <th>Count</th> -->
      <th>Action</th>
    </tr>
  </thead>




<tfoot>
                                    <tr><!-- 
      <th>S.No.</th> -->
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
       <th>Client</th>
        <th>Date</th><!-- 
      <th>Count</th> -->
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


<script type="text/javascript">
$(document).ready(function () {
        var dt = $('#admin_table').DataTable({
            "order": [[4, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/ajaxV2/get_miscellaneous_billing_info",
                type: "post"
            }
        });
        $('#admin_table').on('click', '.delete_link', function (e) {
            e.preventDefault(); 
            const deleteUrl = $(this).attr('href'); 
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete?",
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