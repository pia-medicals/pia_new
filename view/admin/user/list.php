<div class="dashboard_body content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php $this->alert(); ?>

                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Users </h2>
                        <a href="<?= SITE_URL ?>/admin/add_user"><button class="pull-right btn btn-primary btn-flat ">Add User</button></a>
                    </div>
                    <div class="box-header">
                        
                        <form method="post" action="/ajax/users_csv">
                         <input type="submit" name="export" class="pull-right btn btn-success btn-flat " value="Export To CSV"  />
                         </form>
                     </div>
                    <!-- /.box-header -->
                    <div class="box-body">
          <!--             <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr><th>S.No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
          
                        <?php
                        //if(isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
                        //foreach ($user_list['results'] as $key => $value) { 
                        ?>
                <tr><td data-label="S.No."><?php // echo ($key+1)+(($page-1) * 10);  ?></td>
                  <td data-label="Name"><? //=$value['name']  ?></td>
                  <td data-label="Email"><? //=$value['email']  ?></td>
                  <td data-label="Date"><? //=date("d/m/Y", strtotime($value['created']))  ?></td>
                  <td data-label="Action" class="text-center">
                    <a href="<? //=SITE_URL.'/admin/user?edit='.$value['id']  ?>" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                    <a href="<? //=SITE_URL.'/admin/user?delete='.$value['id']  ?>" class="delete_link"><i class="fa fa-trash" aria-hidden="true"></i></a>
          
                  </td>
          
                </tr>
<?php //  }  ?>
          
          
          
            </tbody>
          </table>
            <h1>Server Side Users table</h1>
                        -->


                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
    <script>

        $(document).ready(function () {
            /*$('#example1').DataTable(); */
            var dataTable = jQuery('#example2').DataTable({
                "order": [[2, "desc"]],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "/ajax/get_user_info",
                    type: "post"
                }
            });

            $('#example2').on('click', '.change_status', function () {
                var status = $(this).attr('data-status');
                var id = $(this).attr('data-id');
                var $t = $(this);
                swal({
                    title: "Are you sure?",
                    text: "Are you sure you want to change the status!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-info",
                    confirmButtonText: "Yes, Change Status!",
                    cancelButtonText: "No, Cancel !",
                    closeOnConfirm: false
                },
                        function () {
                          $.ajax({
                                url: '/ajax/ajaxChangeUserStatus',
                                type: 'POST',
                                data: {id: id, status: status},
                                success: function (data) {
                               
                                    if (data.type = 'success') {
                                        var newstatus = (status == 1) ? 0 : 1;
                                    } else {
                                        var newstatus = status;
                                    }
                                    
                                    $t.attr('data-status', newstatus);
                                    if (newstatus == 1) {
                                        $t.parent().prev().find('span.spanstatus').removeClass('badge-danger');
                                        $t.parent().prev().find('span.spanstatus').text('Active');
                                        $t.parent().prev().find('span.spanstatus').addClass('badge-primary');
                                    } else {
                                        $t.parent().prev().find('span.spanstatus').removeClass('badge-primary');
                                        $t.parent().prev().find('span.spanstatus').text('Inactive');
                                        $t.parent().prev().find('span.spanstatus').addClass('badge-danger');
                                    }

                                    swal("Changed!", "Status changed successfully.", "success");
                                },
                                error: function () {}
                            }, 'json'); 
                        });
            });


        });

    </script>

</div>