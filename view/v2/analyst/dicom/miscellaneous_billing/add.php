<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Miscellaneous Billing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst_dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing">Miscellaneous Billing</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <?php $this->alert(); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Add Billing Entry</h3>
                        </div>
                        <form method="post" action="">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name <span style="color:red">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="analysis_invoicing_description">Description</label>
                                    <input type="text" name="analysis_invoicing_description" id="analysis_invoicing_description" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="analysis_client_price">Price</label>
                                    <input type="number" name="analysis_client_price" id="analysis_client_price" class="form-control" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="client_account_ids">Client</label>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle form-control text-left" type="button" id="clientDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select Client
                                        </button>
                                        <input type="hidden" name="client_account_ids" id="client_account_ids" required>
                                        <div class="dropdown-menu" aria-labelledby="clientDropdown">
                                            <?php if (empty($clients)): ?>
                                                <a class="dropdown-item" href="#" disabled>No clients available</a>
                                            <?php else: ?>
                                                <?php foreach ($clients as $client): ?>
                                                    <a class="dropdown-item client-option" href="#" data-value="<?= htmlspecialchars($client['user_id']) ?>">
                                                        <?= htmlspecialchars($client['user_name']) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="count">Count</label>
                                    <input type="number" name="count" id="count" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Add</button>
                                <a href="<?= SITE_URL ?>/analyst/analyst_dicom_details_miscellaneous_billing" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom CSS to ensure dropdown behavior and match the button color -->
    <style>
        .content-wrapper {
            padding-bottom: 100px; /* Ensure space for the dropdown */
        }
        .dropdown-menu {
            top: 100% !important; /* Force the menu to appear below */
            bottom: auto !important;
            max-height: 200px; /* Limit height for scrollable dropdown */
            overflow-y: auto; /* Enable scrolling if many clients */
        }
        .dropdown-toggle::after {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        .dropdown-toggle {
            position: relative;
            text-align: left !important;
            background-color:rgb(255, 255, 255) !important; 
            border-color:rgb(4, 12, 19) !important;
            color: black !important;
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