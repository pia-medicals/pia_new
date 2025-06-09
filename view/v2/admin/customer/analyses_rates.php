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
<style>
    .pull-right {
        float: right;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo !empty($page_title) ? $page_title : ''; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL . '/' . $cntrlr ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo !empty($page_title) ? $page_title : ''; ?></li>
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
                    <div class="card">

                        <div class="card-header">
                            <!-- <h3 class="card-title">Details</h3> -->
                            <?php /* <form role="Form" action="/tat/export_client_analyses_info_excel" method="post" id="anForm" name="anForm" class="sep w-100"> */ ?>
                            <form role="Form" action="/tat/export_analyses_rates" method="post" id="anForm" name="anForm" class="sep w-100">

                                <div class="row align-items-end">
                                    <!-- Analyses Select -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group mb-0">
                                            <label for="sel_analyses">Analyses</label>
                                            <select id="sel_analyses" name="sel_analyses" class="form-control" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="all" selected>All Analyses</option>
                                                <option value="active">Active Analyses</option>
                                                <option value="inactive">Inactive Analyses</option>
                                            </select>
                                            <input type="hidden" id="cid" name="cid" class="form-control" value="<?= $id; ?>">
                                        </div>
                                    </div>

                                    <!-- Category Select -->
                                    <?php  /* <div class="col-md-6 col-lg-4">
                                        <div class="form-group mb-0">
                                            <label for="sel_analyses">Category</label>
                                            <select id="sel_analyses" name="sel_analyses" class="form-control" required>
                                                <option value="">-- Select --</option>
                                                <option value="all" selected>All Categories</option>
                                                <option value="active">Active Categories</option>
                                                <option value="inactive">Inactive Categories</option>
                                            </select>
                                        </div>
                                    </div> */ ?>

                                    <?php
                                    $anlys_categ = $this->Tatdb->getAnalysesCategorySELOPT();
                                    ?>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group mb-0">
                                            <label for="sel_analyses">Categories</label>
                                            <select id="sel_cat" name="sel_cat" class="form-control" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="" selected>All Categories</option>
                                                <?php
                                                foreach ($anlys_categ as $key => $value) {
                                                    echo '<option value="' . $value['category_id'] . '">' . $value['category_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Download Excel Button -->
                                    <div class="col-auto ml-auto">
                                        <div class="form-group mb-0">
                                            <?php /* <label class="visually-hidden" for="export">Download Excel</label>
                                            <a href="<?= SITE_URL ?>/ajaxV2/client_excel" name="export" id="export" class="btn btn-success">
                                                <i aria-hidden="true" class="fas fa-file-excel"></i> Download Excel
                                            </a> */ ?>
                                            <button type="submit" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Excel</button>
                                            <a href="<?= SITE_URL ?>/admin/analyses_add?page_id=<?= $id; ?>" class="btn btn-primary float-right" style="margin-right: 2px;"><i aria-hidden="true" class="fas fa-plus"></i> Add Analysis</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <!-- <div class="card-header">
                            <h3 class="card-title">Details</h3>
                            <?php /* echo $id; */ ?>
                            <?php /*<a href="<?= SITE_URL ?>/excel/get_excel_customer?cus=<?= $edit['client_account_id'] ?>" name="export" id="export" class="btn btn-success float-right"><i aria-hidden="true" class="fas fa-file-excel"></i> Download Client Data</a>*/ ?>
                        </div> -->
                        <div class="card-body">
                            <?php /* <form role="Form" method="post" id="anForm" name="anForm" class="sep">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Analyses</label>
                                            <select id="sel_analyses" name="sel_analyses" class="form-control" required>
                                                <option value="">-- Select --</option>
                                                <option value="all" selected>All Analyses</option>
                                                <option value="active">Active Analyses</option>
                                                <option value="inactive">Inactive Analyses</option>
                                            </select>
                                            <input type="hidden" id="cid" name="cid" class="form-control" value="<?= $id; ?>">
                                        </div>
                                    </div>
                                </div>
                            </form> */ ?>

                            <div class="row">
                                <form class="container-fluid" method="post" id="frmAnalysesPrice" name="frmAnalysesPrice">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Analyses Price Details</h3>

                                    </div>
                                    <div class="admin_table mb-5">
                                        <table class="admin admtbl table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>SI No.</th>
                                                    <th class="w-25">Analysis</th>
                                                    <th class="w-25">Description</th>
                                                    <th class="w-25">Category</th>
                                                    <th>Price</th>
                                                    <th>Item</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="analysis-tbody"></tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="<?= SITE_URL . '/admin/customer' ?>" class="btn btn-secondary float-right">Back</a>
                        </div>



                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>



</div>

<script>
    $(".num").keypress(function(event) {
        // Numeric input restriction
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });

    $(document).ready(function() {
        const $analysisTbody = $('#analysis-tbody');
        const $selAnalyses = $('#sel_analyses');
        const $selCat = $('#sel_cat');
        const $clientId = $('#cid').val();

        function loadAnalysesTable() {
            const selection = $selAnalyses.val();
            const selcateg = $selCat.val();

            if (selection !== '') {
                $.ajax({
                    url: "/analyses_list",
                    type: "POST",
                    // data: { selection: selection, client_id: $clientId },
                    data: {
                        selection: selection,
                        selcat: selcateg,
                        user_id: $clientId
                    },
                    dataType: "json",
                    success: function(response) {
                        let rows = '';
                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function(index, row) {
                                // rows += `
                                // <tr data-id="${row[1]}">
                                //     <td>${row[0]}</td>
                                //     <td class="analysis-name">${row[2]}</td>
                                //     <td class="analysis-desc">${row[3]}</td>
                                //     <td class="analysis-price">${row[4]}</td>
                                //     <td class="analysis-number">${row[5]}</td>
                                //     <td>
                                //         <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                //         ${row[7] == '0' ? `<button type="button" class="btn btn-success btn-xs mb-1 activate-btn" data-act-id="${row['6']}"><i class="fas fa-plane"></i> Activate</button>` : ''}
                                //         ${row[7] == '1' ? `<button type="button" class="btn btn-warning btn-xs mb-1 inactivate-btn" data-inact-id="${row['6']}"><i class="fas fa-plane-slash"></i> Inactivate</button>` : ''}
                                //         <button type="button" class="btn btn-success btn-xs save-btn mb-1 d-none" data-price-id="${row['6']}">Save</button>
                                //         <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
                                //     </td>
                                // </tr>`;
                                rows += `<tr data-id="${row[1]}" data-cat-id="${row[9]}">
                                    <td>${row[0]}</td>
                                    <td class="analysis-name">${row[2]}</td>
                                    <td class="analysis-desc">${row[3]}</td>
                                    <td class="analysis-categ">${row[4]}</td>
                                    <td class="analysis-price">${row[5]}</td>
                                    <td class="analysis-number">${row[6]}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        ${row[8] == '0' ? `<button type="button" class="btn btn-success btn-xs mb-1 activate-btn" data-act-id="${row['7']}"><i class="fas fa-plane"></i> Activate</button>` : ''}
                                        ${row[8] == '1' ? `<button type="button" class="btn btn-warning btn-xs mb-1 inactivate-btn" data-inact-id="${row['7']}"><i class="fas fa-plane-slash"></i> Inactivate</button>` : ''}
                                        <button type="button" class="btn btn-success btn-xs save-btn mb-1 d-none" data-price-id="${row['7']}">Save</button>
                                        <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
                                    </td>
                                </tr>`;
                            });
                        } else {
                            rows = '<tr><td colspan="6" class="text-center">No data available</td></tr>';
                        }
                        $analysisTbody.html(rows);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }
        }

        $selAnalyses.change(loadAnalysesTable);
        $selCat.change(loadAnalysesTable);

        loadAnalysesTable();

        // Edit
        $(document).on('click', '.edit-btn', function() {
            const $row = $(this).closest('tr');

            const name = $row.find('.analysis-name').text().trim();
            const desc = $row.find('.analysis-desc').text().trim();
            const price = $row.find('.analysis-price').text().trim();
            // const anumber = $row.find('.analysis-number').text().trim();

            //For comparison
            $row.data('original-name', name);
            $row.data('original-desc', desc);
            $row.data('original-price', price);
            //$row.data('original-anumber', anumber);

            $row.find('.analysis-name').html(`<input type="text" class="form-control form-control-sm" value="${name}">`);
            $row.find('.analysis-desc').html(`<input type="text" class="form-control form-control-sm" value="${desc}">`);
            $row.find('.analysis-price').html(`<input type="text" step="0.01" class="form-control form-control-sm num" value="${price}">`);
            // $row.find('.analysis-number').html(`<input type="number" step="0.01" class="form-control form-control-sm" value="${anumber}">`);
            //$row.find('.analysis-number').html(`<input type="text" class="form-control form-control-sm num" value="${anumber}">`);

            $row.find('.edit-btn').addClass('d-none');
            $row.find('.activate-btn').addClass('d-none');
            $row.find('.inactivate-btn').addClass('d-none');
            $row.find('.save-btn, .cancel-btn').removeClass('d-none');
        });

        // Format input to 4-digit with leading zeros on blur
        // $(document).on('blur', '.analysis-number input', function() {
        //     let val = $(this).val().trim();
        //     if (/^\d+$/.test(val)) {
        //         val = val.padStart(4, '0'); // Ensures 4 digits with leading zeros
        //         $(this).val(val);
        //     }
        // });

        // Cancel
        $(document).on('click', '.cancel-btn', function() {
            const $row = $(this).closest('tr');
            const name = $row.find('.analysis-name input').attr('value');
            const desc = $row.find('.analysis-desc input').attr('value');
            const price = $row.find('.analysis-price input').attr('value');
            //const anumber = $row.find('.analysis-number input').attr('value');

            $row.find('.analysis-name').text(name);
            $row.find('.analysis-desc').text(desc);
            $row.find('.analysis-price').text(price);
            //$row.find('.analysis-number').text(anumber);

            $row.find('.edit-btn').removeClass('d-none');
            $row.find('.activate-btn').removeClass('d-none');
            $row.find('.inactivate-btn').removeClass('d-none');
            $row.find('.save-btn, .cancel-btn').addClass('d-none');
        });

        // Save (Pass analysis_id instead of analysis_number)
        $(document).on('click', '.save-btn', function() {
            const $row = $(this).closest('tr');
            const analysisId = $row.data('id');
            const dataPriceId = $(this).data('price-id');
            const a_number = $row.find('.analysis-number').text().trim();

            // console.log($row[0].outerHTML); // View full row HTML
            // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

            const updatedData = {
                analysis_id: analysisId,
                data_price_id: dataPriceId,
                user_id: $clientId,
                analysis_name: $row.find('.analysis-name input').val(),
                description: $row.find('.analysis-desc input').val(),
                price: $row.find('.analysis-price input').val(),
                //anumber: $row.find('.analysis-number input').val()
                anumber: a_number
            };

            for (const key in updatedData) {
                if (key === 'data_price_id') continue;
                console.log(`${key}: ${updatedData[key]}`);
                if (updatedData[key] === '' || updatedData[key] === null) {
                    mug_alert_lite('warning', 'Please fill all fields before saving.');
                    return;
                }
            }

            // if (!/^\d{4}$/.test(updatedData.anumber)) {
            //     mug_alert_lite('warning', 'Item must be a 4-digit whole number.');
            //     return;
            // }

            const originalValues = {
                analysis_name: $row.data('original-name'),
                description: $row.data('original-desc'),
                price: $row.data('original-price'),
                //anumber: $row.data('original-anumber')
            };

            let hasChanges = false;
            for (const key in originalValues) {
                if (updatedData[key] !== originalValues[key]) {
                    hasChanges = true;
                    break;
                }
            }

            if (!hasChanges) {
                mug_alert_lite('info', 'No changes made.');
                return;
            }

            $.ajax({
                url: "/update_analysis_price",
                type: "POST",
                data: updatedData,
                dataType: "json",
                success: function(response) {

                    if (response == 0) {
                        mug_alert_lite('error', 'Please fill all fields before saving.');
                        return;
                    }

                    if (response.status === 'success') {
                        /* $row.find('.analysis-name').text(updatedData.analysis_name);
                        $row.find('.analysis-desc').text(updatedData.description);
                        $row.find('.analysis-price').text(updatedData.price);
                        $row.find('.analysis-number').text(updatedData.anumber);*/

                        /*$row.find('.edit-btn').removeClass('d-none');
                        $row.find('.activate-btn').removeClass('d-none');
                        $row.find('.inactivate-btn').removeClass('d-none');
                        $row.find('.save-btn, .cancel-btn').addClass('d-none');*/

                        mug_alert_lite('success', 'Updated Successfully.');
                        loadAnalysesTable();
                    }

                    if (response.status === 'error') {
                        mug_alert_lite('error', 'Something went wrong.');
                        return;
                    }
                },
                error: function() {
                    alert("Error saving data.");
                }
            });
        });

        // Activate (Pass analysis_id instead of analysis_number)
        $(document).on('click', '.activate-btn', function() {
            const $row = $(this).closest('tr');
            const analysisId = $row.data('id');
            const dataPriceId = $(this).data('price-id');

            // console.log($row[0].outerHTML); // View full row HTML
            // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

            // const updatedData = {
            //     analysis_id: analysisId,
            //     data_price_id: dataPriceId,
            //     user_id: $clientId,
            //     analysis_name: $row.find('.analysis-name input').val(),
            //     description: $row.find('.analysis-desc input').val(),
            //     price: $row.find('.analysis-price input').val(),
            //     anumber: $row.find('.analysis-number input').val()
            // };

            const updatedData = {
                analysis_id: analysisId,
                data_price_id: $(this).data('act-id'), // use 'act-id' since that's what you set in HTML
                user_id: $clientId,
                analysis_name: $row.find('.analysis-name').text().trim(),
                description: $row.find('.analysis-desc').text().trim(),
                price: $row.find('.analysis-price').text().trim(),
                anumber: $row.find('.analysis-number').text().trim()
            };


            $.ajax({
                url: "/activate_analysis",
                type: "POST",
                data: updatedData,
                success: function(html) {
                    // $row.find('.analysis-name').text(updatedData.analysis_name);
                    // $row.find('.analysis-desc').text(updatedData.description);
                    // $row.find('.analysis-price').text(updatedData.price);
                    // $row.find('.analysis-number').text(updatedData.anumber);

                    //location.reload();

                    // $row.replaceWith(html);


                    if ($selAnalyses.val() === 'inactive') {
                        $row.remove();
                        $('.admtbl tbody tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    } else {

                        $row.find('.analysis-name').text(updatedData.analysis_name);
                        $row.find('.analysis-desc').text(updatedData.description);
                        $row.find('.analysis-price').text(updatedData.price);
                        $row.find('.analysis-number').text(updatedData.anumber);

                        // Replace the buttons
                        let btns = `
                    <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
                    <button type="button" class="btn btn-warning btn-xs mb-1 inactivate-btn" data-inact-id="${updatedData.data_price_id}">
                    <i class="fas fa-plane-slash"></i> Inactivate
                    </button>
                    <button type="button" class="btn btn-success btn-xs mb-1 save-btn d-none" data-price-id="${updatedData.data_price_id}">Save</button>
                    <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
                    `;
                        $row.find('td:last').html(btns);


                        // $row.find('.edit-btn').removeClass('d-none');
                        // $row.find('.save-btn, .cancel-btn').addClass('d-none');
                    }

                    mug_alert_lite('success', 'Activated Successfully.');
                },
                error: function() {
                    alert("Error activating data.");
                }
            });
        });

        // Inactivate (Pass analysis_id instead of analysis_number)
        $(document).on('click', '.inactivate-btn', function() {
            const $row = $(this).closest('tr');
            const analysisId = $row.data('id');
            const dataPriceId = $(this).data('price-id');

            // console.log($row[0].outerHTML); // View full row HTML
            // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

            // const updatedData = {
            //     analysis_id: analysisId,
            //     data_price_id: dataPriceId,
            //     user_id: $clientId,
            //     analysis_name: $row.find('.analysis-name input').val(),
            //     description: $row.find('.analysis-desc input').val(),
            //     price: $row.find('.analysis-price input').val(),
            //     anumber: $row.find('.analysis-number input').val()
            // };

            const updatedData = {
                analysis_id: analysisId,
                data_price_id: $(this).data('inact-id'), // use 'inact-id' since that's what you set in HTML
                user_id: $clientId,
                analysis_name: $row.find('.analysis-name').text().trim(),
                description: $row.find('.analysis-desc').text().trim(),
                price: $row.find('.analysis-price').text().trim(),
                anumber: $row.find('.analysis-number').text().trim()
            };


            $.ajax({
                url: "/inactivate_analysis",
                type: "POST",
                data: updatedData,
                success: function(html) {
                    // $row.find('.analysis-name').text(updatedData.analysis_name);
                    // $row.find('.analysis-desc').text(updatedData.description);
                    // $row.find('.analysis-price').text(updatedData.price);
                    // $row.find('.analysis-number').text(updatedData.anumber);

                    //location.reload();



                    // $row.replaceWith(html);
                    if ($selAnalyses.val() === 'active') {
                        $row.remove();
                        $('.admtbl tbody tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    } else {

                        $row.find('.analysis-name').text(updatedData.analysis_name);
                        $row.find('.analysis-desc').text(updatedData.description);
                        $row.find('.analysis-price').text(updatedData.price);
                        $row.find('.analysis-number').text(updatedData.anumber);

                        // Replace the buttons
                        let btns = `
                    <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
                    <button type="button" class="btn btn-success btn-xs mb-1 activate-btn" data-act-id="${updatedData.data_price_id}">
                    <i class="fas fa-plane"></i> Activate
                    </button>
                    <button type="button" class="btn btn-success btn-xs mb-1 save-btn d-none" data-price-id="${updatedData.data_price_id}">Save</button>
                    <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
                    `;
                        $row.find('td:last').html(btns);



                        // $row.find('.edit-btn').removeClass('d-none');
                        // $row.find('.save-btn, .cancel-btn').addClass('d-none');
                    }

                    mug_alert_lite('success', 'Inactivated Successfully.');
                },
                error: function() {
                    alert("Error inactivating data.");
                }
            });
        });

    });
</script>

<script>
    // $(".num").keypress(function(event) {
    //     // Numeric input restriction
    //     if (event.which < 48 || event.which > 57) {
    //         event.preventDefault();
    //     }
    // });



    // $(document).ready(function() {
    //     const $analysisTbody = $('#analysis-tbody');
    //     const $selAnalyses = $('#sel_analyses');
    //     const $clientId = $('#cid').val();

    //     function loadAnalysesTable() {
    //         const selection = $selAnalyses.val();

    //         if (selection !== '') {
    //             $.ajax({
    //                 url: "/analyses_list",
    //                 type: "POST",
    //                 // data: { selection: selection, client_id: $clientId },
    //                 data: {
    //                     selection: selection,
    //                     user_id: $clientId
    //                 },
    //                 dataType: "json",
    //                 success: function(response) {
    //                     let rows = '';
    //                     if (response.data && response.data.length > 0) {
    //                         $.each(response.data, function(index, row) {
    //                             rows += `
    //                             <tr data-id="${row[1]}" data-cat-id="${row[9]}">
    //                                 <td>${row[0]}</td>
    //                                 <td class="analysis-name">${row[2]}</td>
    //                                 <td class="analysis-desc">${row[3]}</td>
    //                                 <td class="analysis-categ">${row[4]}</td>
    //                                 <td class="analysis-price">${row[5]}</td>
    //                                 <td class="analysis-number">${row[6]}</td>
    //                                 <td>
    //                                     <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
    //                                     ${row[8] == '0' ? `<button type="button" class="btn btn-success btn-xs mb-1 activate-btn" data-act-id="${row['7']}"><i class="fas fa-plane"></i> Activate</button>` : ''}
    //                                     ${row[8] == '1' ? `<button type="button" class="btn btn-warning btn-xs mb-1 inactivate-btn" data-inact-id="${row['7']}"><i class="fas fa-plane-slash"></i> Inactivate</button>` : ''}
    //                                     <button type="button" class="btn btn-success btn-xs save-btn mb-1 d-none" data-price-id="${row['7']}">Save</button>
    //                                     <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
    //                                 </td>
    //                             </tr>`;
    //                         });
    //                     } else {
    //                         rows = '<tr><td colspan="6" class="text-center">No data available</td></tr>';
    //                     }
    //                     $analysisTbody.html(rows);
    //                 },
    //                 error: function(xhr, status, error) {
    //                     console.error("AJAX Error:", status, error);
    //                 }
    //             });
    //         }
    //     }

    //     $selAnalyses.change(loadAnalysesTable);

    //     loadAnalysesTable();

    //     <?php
            //     $analysis_category = $this->Admindb->getAnalysesCategoryDDWN();
            //     
            ?>

    //     const analysisCategories = <?php echo json_encode($analysis_category); ?>;

    //     // Edit
    //     $(document).on('click', '.edit-btn', function() {
    //         const $row = $(this).closest('tr');

    //         const name = $row.find('.analysis-name').text().trim();
    //         const desc = $row.find('.analysis-desc').text().trim();
    //         const categ = $row.find('.analysis-categ').text().trim();
    //         const price = $row.find('.analysis-price').text().trim();
    //         const anumber = $row.find('.analysis-number').text().trim();
    //         const currentCategoryId = $row.data('cat-id');

    //         //For comparison
    //         $row.data('original-name', name);
    //         $row.data('original-desc', desc);
    //         $row.data('original-categ', categ);
    //         $row.data('original-price', price);
    //         $row.data('original-anumber', anumber);



    //         $row.find('.analysis-name').html(`<input type="text" class="form-control form-control-sm" value="${name}">`);
    //         $row.find('.analysis-desc').html(`<input type="text" class="form-control form-control-sm" value="${desc}">`);

    //         // Generate the select dropdown HTML
    //         let categoryDropdownHtml = `<select name="category" class="form-control form-control-sm analysis-category-select" required>
    //                             <option value="">-- Choose Category --</option>`;
    //         analysisCategories.forEach(cat => {
    //             categoryDropdownHtml += `<option value="${cat.category_id}" ${cat.category_id == currentCategoryId ? 'selected' : ''}>${cat.category_name}</option>`;
    //         });
    //         categoryDropdownHtml += `</select>`;

    //         $row.find('.analysis-categ').html(categoryDropdownHtml);


    //         $row.find('.analysis-price').html(`<input type="text" step="0.01" class="form-control form-control-sm num" value="${price}">`);
    //         // $row.find('.analysis-number').html(`<input type="number" step="0.01" class="form-control form-control-sm" value="${anumber}">`);
    //         $row.find('.analysis-number').html(`<input type="text" class="form-control form-control-sm num" value="${anumber}">`);

    //         //Prevent auto scroll
    //         setTimeout(() => {
    //             $row[0].scrollIntoView({
    //                 block: 'center',
    //                 behavior: 'instant'
    //             });
    //         }, 0);


    //         $row.find('.edit-btn').addClass('d-none');
    //         $row.find('.activate-btn').addClass('d-none');
    //         $row.find('.inactivate-btn').addClass('d-none');
    //         $row.find('.save-btn, .cancel-btn').removeClass('d-none');
    //     });

    //     // Format input to 4-digit with leading zeros on blur
    //     $(document).on('blur', '.analysis-number input', function() {
    //         let val = $(this).val().trim();
    //         if (/^\d+$/.test(val)) {
    //             val = val.padStart(4, '0'); // Ensures 4 digits with leading zeros
    //             $(this).val(val);
    //         }
    //     });

    //     // Cancel
    //     // $(document).on('click', '.cancel-btn', function() {
    //     //     const $row = $(this).closest('tr');
    //     //     const name = $row.find('.analysis-name input').attr('value');
    //     //     const desc = $row.find('.analysis-desc input').attr('value');
    //     //     const categ = $row.find('.analysis-categ input').attr('value');
    //     //     const price = $row.find('.analysis-price input').attr('value');
    //     //     const anumber = $row.find('.analysis-number input').attr('value');

    //     //     $row.find('.analysis-name').text(name);
    //     //     $row.find('.analysis-desc').text(desc);
    //     //     $row.find('.analysis-categ').text(categ);
    //     //     $row.find('.analysis-price').text(price);
    //     //     $row.find('.analysis-number').text(anumber);

    //     //     $row.find('.edit-btn').removeClass('d-none');
    //     //     $row.find('.activate-btn').removeClass('d-none');
    //     //     $row.find('.inactivate-btn').removeClass('d-none');
    //     //     $row.find('.save-btn, .cancel-btn').addClass('d-none');
    //     // });
    //     $(document).on('click', '.cancel-btn', function() {
    //         const $row = $(this).closest('tr');
    //         const originalName = $row.data('original-name');
    //         const originalDesc = $row.data('original-desc');
    //         const originalPrice = $row.data('original-price');
    //         const originalAnumber = $row.data('original-anumber');
    //         // const originalCategoryName = $row.find('.analysis-category-select option:selected').text(); // Get the displayed category name
    //         // const originalCategoryId = $row.find('.analysis-category-select').val(); // Get the selected category ID
    //         const originalCategoryId = $row.data('cat-id');
    //         const originalCategoryName = analysisCategories.find(cat => cat.category_id == originalCategoryId)?.category_name || '';


    //         $row.find('.analysis-name').text(originalName);
    //         $row.find('.analysis-desc').text(originalDesc);
    //         //$row.find('.analysis-categ').text(originalCategoryName); // Restore the category name
    //         $row.find('.analysis-categ').text(originalCategoryName);
    //         $row.find('.analysis-price').text(originalPrice);
    //         $row.find('.analysis-number').text(originalAnumber);

    //         $row.find('.edit-btn').removeClass('d-none');
    //         $row.find('.activate-btn').removeClass('d-none');
    //         $row.find('.inactivate-btn').removeClass('d-none');
    //         $row.find('.save-btn, .cancel-btn').addClass('d-none');
    //     });

    //     // Save (Pass analysis_id instead of analysis_number)
    //     $(document).on('click', '.save-btn', function() {
    //         const $row = $(this).closest('tr');
    //         const analysisId = $row.data('id');
    //         const dataPriceId = $(this).data('price-id');

    //         // console.log($row[0].outerHTML); // View full row HTML
    //         // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

    //         const updatedData = {
    //             analysis_id: analysisId,
    //             data_price_id: dataPriceId,
    //             user_id: $clientId,
    //             analysis_name: $row.find('.analysis-name input').val(),
    //             description: $row.find('.analysis-desc input').val(),
    //             price: $row.find('.analysis-price input').val(),
    //             anumber: $row.find('.analysis-number input').val()
    //         };

    //         for (const key in updatedData) {
    //             if (key === 'data_price_id') continue;
    //             console.log(`${key}: ${updatedData[key]}`);
    //             if (updatedData[key] === '' || updatedData[key] === null) {
    //                 mug_alert_lite('warning', 'Please fill all fields before saving.');
    //                 return;
    //             }
    //         }

    //         if (!/^\d{4}$/.test(updatedData.anumber)) {
    //             mug_alert_lite('warning', 'Item must be a 4-digit whole number.');
    //             return;
    //         }

    //         const originalValues = {
    //             analysis_name: $row.data('original-name'),
    //             description: $row.data('original-desc'),
    //             price: $row.data('original-price'),
    //             anumber: $row.data('original-anumber')
    //         };

    //         let hasChanges = false;
    //         for (const key in originalValues) {
    //             if (updatedData[key] !== originalValues[key]) {
    //                 hasChanges = true;
    //                 break;
    //             }
    //         }

    //         if (!hasChanges) {
    //             mug_alert_lite('info', 'No changes made.');
    //             return;
    //         }

    //         $.ajax({
    //             url: "/update_analysis_price",
    //             type: "POST",
    //             data: updatedData,
    //             dataType: "json",
    //             success: function(response) {

    //                 if (response == 0) {
    //                     mug_alert_lite('error', 'Please fill all fields before saving.');
    //                     return;
    //                 }

    //                 if (response.status === 'success') {
    //                     /* $row.find('.analysis-name').text(updatedData.analysis_name);
    //                     $row.find('.analysis-desc').text(updatedData.description);
    //                     $row.find('.analysis-price').text(updatedData.price);
    //                     $row.find('.analysis-number').text(updatedData.anumber);*/

    //                     /*$row.find('.edit-btn').removeClass('d-none');
    //                     $row.find('.activate-btn').removeClass('d-none');
    //                     $row.find('.inactivate-btn').removeClass('d-none');
    //                     $row.find('.save-btn, .cancel-btn').addClass('d-none');*/

    //                     mug_alert_lite('success', 'Updated Successfully.');
    //                     loadAnalysesTable();
    //                 }

    //                 if (response.status === 'error') {
    //                     mug_alert_lite('error', 'Something went wrong.');
    //                     return;
    //                 }
    //             },
    //             error: function() {
    //                 alert("Error saving data.");
    //             }
    //         });
    //     });

    //     // Activate (Pass analysis_id instead of analysis_number)
    //     $(document).on('click', '.activate-btn', function() {
    //         const $row = $(this).closest('tr');
    //         const analysisId = $row.data('id');
    //         const dataPriceId = $(this).data('price-id');

    //         // console.log($row[0].outerHTML); // View full row HTML
    //         // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

    //         // const updatedData = {
    //         //     analysis_id: analysisId,
    //         //     data_price_id: dataPriceId,
    //         //     user_id: $clientId,
    //         //     analysis_name: $row.find('.analysis-name input').val(),
    //         //     description: $row.find('.analysis-desc input').val(),
    //         //     price: $row.find('.analysis-price input').val(),
    //         //     anumber: $row.find('.analysis-number input').val()
    //         // };

    //         const updatedData = {
    //             analysis_id: analysisId,
    //             data_price_id: $(this).data('act-id'), // use 'act-id' since that's what you set in HTML
    //             user_id: $clientId,
    //             analysis_name: $row.find('.analysis-name').text().trim(),
    //             description: $row.find('.analysis-desc').text().trim(),
    //             price: $row.find('.analysis-price').text().trim(),
    //             anumber: $row.find('.analysis-number').text().trim()
    //         };


    //         $.ajax({
    //             url: "/activate_analysis",
    //             type: "POST",
    //             data: updatedData,
    //             success: function(html) {
    //                 // $row.find('.analysis-name').text(updatedData.analysis_name);
    //                 // $row.find('.analysis-desc').text(updatedData.description);
    //                 // $row.find('.analysis-price').text(updatedData.price);
    //                 // $row.find('.analysis-number').text(updatedData.anumber);

    //                 //location.reload();

    //                 // $row.replaceWith(html);


    //                 if ($selAnalyses.val() === 'inactive') {
    //                     $row.remove();
    //                     $('.admtbl tbody tr').each(function(index) {
    //                         $(this).find('td:first').text(index + 1);
    //                     });
    //                 } else {

    //                     $row.find('.analysis-name').text(updatedData.analysis_name);
    //                     $row.find('.analysis-desc').text(updatedData.description);
    //                     $row.find('.analysis-price').text(updatedData.price);
    //                     $row.find('.analysis-number').text(updatedData.anumber);

    //                     // Replace the buttons
    //                     let btns = `
    //                 <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
    //                 <button type="button" class="btn btn-warning btn-xs mb-1 inactivate-btn" data-inact-id="${updatedData.data_price_id}">
    //                 <i class="fas fa-plane-slash"></i> Inactivate
    //                 </button>
    //                 <button type="button" class="btn btn-success btn-xs mb-1 save-btn d-none" data-price-id="${updatedData.data_price_id}">Save</button>
    //                 <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
    //                 `;
    //                     $row.find('td:last').html(btns);


    //                     // $row.find('.edit-btn').removeClass('d-none');
    //                     // $row.find('.save-btn, .cancel-btn').addClass('d-none');
    //                 }

    //                 mug_alert_lite('success', 'Activated Successfully.');
    //             },
    //             error: function() {
    //                 alert("Error activating data.");
    //             }
    //         });
    //     });

    //     // Inactivate (Pass analysis_id instead of analysis_number)
    //     $(document).on('click', '.inactivate-btn', function() {
    //         const $row = $(this).closest('tr');
    //         const analysisId = $row.data('id');
    //         const dataPriceId = $(this).data('price-id');

    //         // console.log($row[0].outerHTML); // View full row HTML
    //         // console.log('data-id:', $row.data('id')); // Check if it's undefined or invalid

    //         // const updatedData = {
    //         //     analysis_id: analysisId,
    //         //     data_price_id: dataPriceId,
    //         //     user_id: $clientId,
    //         //     analysis_name: $row.find('.analysis-name input').val(),
    //         //     description: $row.find('.analysis-desc input').val(),
    //         //     price: $row.find('.analysis-price input').val(),
    //         //     anumber: $row.find('.analysis-number input').val()
    //         // };

    //         const updatedData = {
    //             analysis_id: analysisId,
    //             data_price_id: $(this).data('inact-id'), // use 'inact-id' since that's what you set in HTML
    //             user_id: $clientId,
    //             analysis_name: $row.find('.analysis-name').text().trim(),
    //             description: $row.find('.analysis-desc').text().trim(),
    //             price: $row.find('.analysis-price').text().trim(),
    //             anumber: $row.find('.analysis-number').text().trim()
    //         };


    //         $.ajax({
    //             url: "/inactivate_analysis",
    //             type: "POST",
    //             data: updatedData,
    //             success: function(html) {
    //                 // $row.find('.analysis-name').text(updatedData.analysis_name);
    //                 // $row.find('.analysis-desc').text(updatedData.description);
    //                 // $row.find('.analysis-price').text(updatedData.price);
    //                 // $row.find('.analysis-number').text(updatedData.anumber);

    //                 //location.reload();



    //                 // $row.replaceWith(html);
    //                 if ($selAnalyses.val() === 'active') {
    //                     $row.remove();
    //                     $('.admtbl tbody tr').each(function(index) {
    //                         $(this).find('td:first').text(index + 1);
    //                     });
    //                 } else {

    //                     $row.find('.analysis-name').text(updatedData.analysis_name);
    //                     $row.find('.analysis-desc').text(updatedData.description);
    //                     $row.find('.analysis-price').text(updatedData.price);
    //                     $row.find('.analysis-number').text(updatedData.anumber);

    //                     // Replace the buttons
    //                     let btns = `
    //                 <button type="button" class="btn btn-info btn-xs mb-1 edit-btn"><i class="fas fa-edit"></i> Edit</button>
    //                 <button type="button" class="btn btn-success btn-xs mb-1 activate-btn" data-act-id="${updatedData.data_price_id}">
    //                 <i class="fas fa-plane"></i> Activate
    //                 </button>
    //                 <button type="button" class="btn btn-success btn-xs mb-1 save-btn d-none" data-price-id="${updatedData.data_price_id}">Save</button>
    //                 <button type="button" class="btn btn-secondary btn-xs mb-1 cancel-btn d-none">Cancel</button>
    //                 `;
    //                     $row.find('td:last').html(btns);



    //                     // $row.find('.edit-btn').removeClass('d-none');
    //                     // $row.find('.save-btn, .cancel-btn').addClass('d-none');
    //                 }

    //                 mug_alert_lite('success', 'Inactivated Successfully.');
    //             },
    //             error: function() {
    //                 alert("Error inactivating data.");
    //             }
    //         });
    //     });

    // });
</script>