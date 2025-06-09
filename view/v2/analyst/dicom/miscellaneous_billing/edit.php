<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Miscellaneous Billing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing">Miscellaneous Billing</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?php $this->alert(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <form method="post" action="" style="background: #fff; padding: 20px; border-radius: 5px;">
                        <input type="hidden" name="miscellaneous_billing_id" value="<?= htmlspecialchars($edit_billing['miscellaneous_billing_id'] ?? '') ?>">

                        <div class="form-group">
                            <label for="name">Name <span style="color:red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($edit_billing['name'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="analysis_invoicing_description">Description</label>
                            <input type="text" name="analysis_invoicing_description" id="analysis_invoicing_description" class="form-control" value="<?= htmlspecialchars($edit_billing['analysis_invoicing_description'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="analysis_client_price">Price</label>
                            <input type="number" name="analysis_client_price" id="analysis_client_price" class="form-control" step="0.01" value="<?= htmlspecialchars($edit_billing['analysis_client_price'] ?? '') ?>">
                        </div>

                        <div class="form-group select-wrapper">
                            <label for="client_account_ids">Client</label>
                            <select name="client_account_ids" id="client_account_ids" class="form-control" required>
                                <option value="">Select Client</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= htmlspecialchars($client['user_id']) ?>" <?= ($edit_billing['client_account_ids'] == $client['user_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($client['user_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="count">Count</label>
                            <input type="number" name="count" id="count" class="form-control" value="<?= htmlspecialchars($edit_billing['count'] ?? '') ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>



    <!-- Custom CSS to ensure dropdown behavior -->
    <style>
        .form-control {
            box-shadow: none;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        select.form-control {
        position: relative;
        z-index: 1;
        }

    /* Force dropdown to open downward (some themes override this with dropup) */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        display: block;
    }

    /* Fix for Bootstrap if it's being interpreted as dropup */
    .dropdown-menu {
        top: 100% !important;
        bottom: auto !important;
    }

    </style>


    <!-- JavaScript to handle dropdown selection -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownButton = document.getElementById('clientDropdown');
        const hiddenInput = document.getElementById('client_account_ids');
        const clientOptions = document.querySelectorAll('.client-option');

        clientOptions.forEach(option => {
            option.addEventListener('click', function (e) {
                e.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                const selectedText = this.textContent.trim();

                hiddenInput.value = selectedValue;
                dropdownButton.textContent = selectedText;

                // Remove 'is-invalid' class if present (for Bootstrap validation)
                hiddenInput.classList.remove('is-invalid');
            });
        });

        // Form validation to ensure a client is selected
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            if (!hiddenInput.value) {
                e.preventDefault();
                hiddenInput.classList.add('is-invalid');
                alert('Please select a client.');
            }
        });
    });
    </script>
</div>