


<style type="text/css">
    .green-row {
        background-color: green !important;
        color: white; /* Optional: This makes the text white to contrast with the green */
    }
    /*    .table td, .table th {
            padding-left: 8px;
            padding-right: 8px;
            font-size: 16px;
        }
        table.dataTable > thead > tr > th:not(.sorting_disabled), table.dataTable > thead > tr > td:not(.sorting_disabled) {
            padding-right: 15px;
        }*/
    .table td, .table th {
        padding-left: 6px;
        padding-right: 6px;
        font-size: 14px;
    }
    .table th{
        padding-right: 20px !important;
        font-size: 14px !important;
    }
    table.dataTable > thead .sorting::before
    {
        right: 2px;
    }
    table.dataTable > thead .sorting::after{
        right: 10px;
    }

    #dataTbl .bg-info {
        background-color: #d9edf7 !important;
        color: #000 !important;
    }

    #dataTbl .bg-success {
        background-color: #dff0d8 !important;
        color: #000 !important;
    }

    #dataTbl .bg-warning {
        background-color: #fcf8e3 !important;
        color: #000 !important;
    }

    #dataTbl .bg-default {
        background-color: #eee !important;
        color: #000 !important;
    }

    #dataTbl .bg-danger {
        background-color: #f2dede !important;
        color: #000 !important;
    }
    .filter-form {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 10px;
}

.add-study-btn {
  white-space: nowrap;
}

/* Responsive tweaks for smaller screens */
@media screen and (max-width: 768px) {
  .filter-form {
    flex-direction: column;
    align-items: flex-start;
  }

  .add-study-btn,
  #reset_filter,
  #export {
    width: 100%;
    text-align: center;
  }

  .filter-form select {
    width: 100% !important;
  }
  .bottom-title-all {
        background: #f9f9f9;
        padding: 14px;
        text-align: center;
        font-size: 30px;
        font-weight: 600;
        color: #444;
    }
}

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Studies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active">All Studies</li>
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
                            <form method="post" action="/ajaxV3/export_all_studies">
                                <select class="form-control float-left" id="days" name="days" style="width: 10%; margin-right: 10px;">
                                    <option value="">-- Show Last --</option>
                                    <option value="1">1 day</option>
                                    <option value="3">3 days</option>
                                    <option value="30">30 days</option>
                                    <option value="">All</option>
                                </select>

                                <select class="form-control float-left" id="assignee" name="assignee" style="width: 15%; margin-right: 10px;">
                                    <option value="">-- Assignee --</option>
                                    <?php
                                    foreach ($asignee as $key => $row) {
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['user_name']; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <select class="form-control float-left" style="width: 12%; margin-right: 10px;" id="second_check" name="second_check">
                                    <option value="">-- Second Check --</option> 
                                    <option value="1">Yes</option>
                                    <option value="2" >No</option>
                                </select>

                                <select class="form-control float-left" style="width: 15%; margin-right: 10px;display:none;" id="second_assignee" name="second_assignee">
                                    <option value="">-- Reviewer --</option>
                                    <?php
                                    foreach ($asignee as $key => $row_second_assignee) {
                                        ?>
                                        <option value="<?php echo $row_second_assignee['user_id']; ?>"> <?php echo $row_second_assignee['user_name']; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>


                                <select class="form-control float-left" style="width: 12%; margin-right: 10px;" id="status" name="status">
                                    <option value="">-- Status --</option>
                                    <?php
                                    foreach ($analysis_statuses as $key => $row_status) {
                                        ?>
                                        <option value="<?php echo $row_status['status_id'] ?>"> <?php echo $row_status['status'] ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <button type="button" id="reset_filter" class="btn btn-danger" name="reset_filter">Reset Filter</button>
                                <a href="<?= SITE_URL ?>/analyst/add_new_study" class="btn btn-primary add-study-btn">
                                    Add New Study
                                </a>
                                <button type="submit" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Excel</button>

                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="dataTbl" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Received Date</th>

                                        <th>Accession</th>

                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Description</th>

                                        <th>Institution</th>
                                        <th>Client</th>
                                        

                                        <th>TAT(Min)</th>
                                        <th>Time Remaining(Hrs)</th>
                                        <th>Assignee</th>
                                        <th>Second Check</th>
                                        <th>Status</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>                     
                                <tfoot>
                                    <tr>
                                        <th>Received Date</th>

                                        <th>Accession</th>

                                        <th>Patient Name</th>
                                        <th>MRN</th>
                                        <th>Description</th>

                                        <th>Institution</th>
                                        <th>Client</th>
                                        <th>TAT(Min)</th>

                                        <th>Time Remaining(Hrs)</th>
                                        <th>Assignee</th>
                                        <th>Second Check</th>
                                        <th>Status</th>

                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="bottom-title-all" style="background: #f9f9f9 !important; padding: 14px !important; text-align: center !important; font-size: 30px !important; font-weight: 600 !important; color: #444 !important;">
                                 All Studies
                            </div>  
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
    
   <!-- TAT Edit Modal -->
<div id="tatEditModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <form id="tatEditForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit TAT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editStudyId" name="study_id">
        <div class="mb-3">
          <label for="tatValue" class="form-label">TAT</label>
          <input type="number" id="tatValue" name="tat_value" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="tatUnit" class="form-label">Unit</label>
          <select id="tatUnit" class="form-select">
            <option value="minutes">Minutes</option>
            <option value="hours">Hours</option>
            <option value="days">Days</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Client Modal -->
<div id="editClientModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <form id="editClientForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="modalStudyId" name="study_id">
        <div class="mb-3">
          <label for="modalClientSelect" class="form-label">Select Client</label>
          <select id="modalClientSelect" name="client_account_id" class="form-select" required>
            <!-- Options populated from PHP -->
            <?php foreach ($clients as $client): ?>
              <option value="<?= $client['client_account_id'] ?>"><?= $client['client_name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
        </div>
      </div>
    </form>
  </div>
</div>


</div>

<script>
    
$(document).ready(function () {
    const dt = $('#dataTbl').DataTable({
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        order: [[0, "desc"]],
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: "/ajaxV3/get_studies_info_analyst",
            type: "post",
            data: function (d) {
                d.selectedDays = $('#days').val();
                d.assignee = $('#assignee').val();
                d.second_check = $('#second_check').val();
                d.secondAssignee = $('#second_assignee').val();
                d.status = $('#status').val();
            }
        }
    });

    $('#days, #assignee, #second_assignee, #status, #second_check').on('change', function () {
        const second = $('#second_check').val();
        $('#second_assignee').toggle(second === '1');
        dt.ajax.reload();
    });

    $('#reset_filter').on('click', function () {
        $('#second_check, #second_assignee, #days, #assignee, #status').val('');
        dt.ajax.reload();
    });

    // Edit TAT
    $(document).on('click', '.editable-tat', function () {
        const studyId = $(this).data('study-id');
        const tatVal = $(this).data('tat');
        const tatUnit = $(this).data('unit') || 'minutes';

        $('#editStudyId').val(studyId);
        $('#tatValue').val(tatVal);
        $('#tatUnit').val(tatUnit);
        $('#tatEditModal').modal('show');
    });

    $('#tatEditForm').on('submit', function (e) {
        e.preventDefault();
        const studyId = $('#editStudyId').val();
        let tatVal = parseFloat($('#tatValue').val());
        const tatUnit = $('#tatUnit').val();

        if (tatUnit === 'hours') tatVal *= 60;
        else if (tatUnit === 'days') tatVal *= 1440;

        tatVal = Math.round(tatVal);

        $.ajax({
            url: '/analyst/update_tat',
            type: 'POST',
            data: { study_id: studyId, tat_value: tatVal },
            success: function () {
                $('#tatEditModal').modal('hide');

                const rowIdx = dt.rows().indexes().filter(function (idx) {
                    return $(dt.row(idx).node()).find('.editable-tat').data('study-id') == studyId;
                })[0];

                if (rowIdx !== undefined) {
                    const updatedSpan = `<span class="editable-tat" data-study-id="${studyId}" data-tat="${tatVal}" data-unit="minutes" style="cursor:pointer;text-decoration:underline;color:#007bff;">${tatVal} minutes</span>`;
                    const rowData = dt.row(rowIdx).data();
                    rowData[7] = updatedSpan;
                    dt.row(rowIdx).data(rowData).draw(false);
                }

                Swal.fire('Updated!', 'TAT updated successfully.', 'success');
            },
            error: function () {
                Swal.fire('Error!', 'Failed to update TAT.', 'error');
            }
        });
    });

    // Edit Client
    $(document).on('click', '.change-client-span', function () {
        const studyId = $(this).data('study-id');
        const clientAccountId = $(this).data('account-id');

        $('#modalStudyId').val(studyId);
        $('#modalClientSelect').val(clientAccountId);
        $('#editClientModal').modal('show');
    });

    // Handle client update form
   $('#editClientForm').on('submit', function (e) {
    e.preventDefault();
    const studyId = $('#modalStudyId').val();
    const clientId = $('#modalClientSelect').val();

    $.ajax({
        url: '/analyst/update_client_assignment',
        type: 'POST',
        data: {
            study_id: studyId,
            client_account_id: clientId
        },
        success: function (response) {
            try {
                if (typeof response === 'string') {
                    response = JSON.parse(response);
                }

                if (response.status === 'success') {
                    $('#editClientModal').modal('hide');
                    Swal.fire('Client Updated!', response.message || 'Client successfully updated.', 'success');
                    dt.ajax.reload();
                } else {
                    Swal.fire('Error', response.message || 'Failed to update client.', 'error');
                }
            } catch (e) {
                Swal.fire('Invalid JSON Response', `<pre>${e.message}</pre>`, 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error("Server returned:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                html: `<pre style="text-align:left;">${xhr.responseText.substring(0, 300)}</pre>`
            });
        }
    });
});

});

</script>

