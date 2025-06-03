<div class="dashboard_body content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php $this->alert(); ?>

                <div class="box">
                    <div class="box-header">
                        <h2 class="box-title">Customers </h2>
                        <a href="<?= SITE_URL ?>/admin/add_customer"><button class="pull-right btn btn-primary btn-flat ">Add</button></a>

                        <a href="<?= SITE_URL ?>/excel/generate_excel"><button class="pull-right btn btn-primary btn-flat " style="margin-right: 10px;" >Download Excel</button></a>


                    </div>

                    <div class="box-body">

                        <table id="customer_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Customer Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Customer Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
<script type="text/javascript">
    $(document).ready(function () {
        var dataTable = $('#customer_table').DataTable({
            "lengthMenu": [[100], [100]],
            "order": [[2, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/ajax/get_customer_info",
                type: "post"
            },
            /* "dom": 'Bfrtip',*/
            /* "buttons": [ 'copy', 'excel', 'pdf']*/
        });


        $('#customer_table').on('click', '.change_status', function () {
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
                            url: '/ajax/ajaxChangeCustomerStatus',
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
