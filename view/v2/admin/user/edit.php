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
/*
  $select_array = array(
  1 => 'Super Admin',
  2 => 'Manager',
  3 => 'Analyst',
  4 => 'Patient',
  5 => 'Customer'
  );
 */
?>
<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/validate/cmxform.css">
<script src="<?= ADMIN_LTE3 ?>/validate/jquery.validate.js"></script>
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

    <style>
        #password-rules {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
            margin-top: 10px;
        }

        #password-rules.show {
            display: block;
            opacity: 1;
        }

        .card-expand {
            transition: max-height 0.6s ease;
            overflow: hidden;
            /* max-height: 600px; */
            /* max-height: 66vh; */
            /* adjust if needed */
        }

        /* .card-collapsed {
            max-height: 500px; */
        /* default height */
        /* } */

        .bold {
            font-weight: bold;
        }

        #max-length-warning {
            transition: opacity 0.3s ease;
        }
    </style>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editUserFrm" name="editUserFrm" method="post" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                            <div class="card-body card-expand card-collapsed">
                                <?php //$this->alert(); 
                                ?>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="name" class="form-control" required="" name="name" value="<?= $edit['user_name'] ?>" maxlength="100">
                                    <input type="hidden" id="id" name="id" value="<?= $edit['user_id'] ?>">
                                </div>

                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" id="email" class="form-control" required="" name="email" placeholder="Example: john.doe@gmail.com" value="<?= $edit['email'] ?>" maxlength="100">
                                </div>

                                <div class="form-group">
                                    <label>Account Status</label>
                                    <div class="input-group">
                                        <input type="checkbox" id="active" name="active" <?php if ($edit['is_active'] == true) echo "checked"; ?> data-bootstrap-switch data-on-text="Active" data-off-text="Inactive" data-off-color="danger" data-on-color="secondary">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>User Type</label>
                                    <select id="group_id" class="form-control" required="" name="group_id">
                                        <?php
                                        /*
                                        foreach ($select_array as $key => $value) {
                                            $sel = (!empty($edit['user_type_ids']) && ($edit['user_type_ids'] == $value['user_type_id'])) ? 'selected' : '';
                                            echo '<option value="' . $value['user_type_id'] . '" ' . $sel . '>' . $value['user_type'] . '</option>';
                                        } */

                                        foreach ($select_array as $key => $value) {
                                            $sel = (!empty($edit['user_type_ids']) && ($edit['user_type_ids'] == $value['user_type_id'])) ? 'selected' : '';
                                            $display_text = ($value['user_type_id'] == 3 && $value['user_type'] == 'Analyst') ? 'Technologist' : $value['user_type'];
                                            echo '<option value="' . $value['user_type_id'] . '" ' . $sel . '>' . $display_text . '</option>';
                                        }

                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Organization</label>
                                    <select id="orgz_id" class="form-control" name="orgz_id">
                                        <option value="">Choose Organization</option>
                                    </select>
                                </div>

                                <!--   <div class="form-group">
                                      <label>Profile Picture</label>
                                      <div class="input-group">
                                          <div class="custom-file">
                                              <input type="file" class="custom-file-input" name="profile_picture" id="profile_picture">
                                              <label class="custom-file-label" for="profile_picture">Choose File</label>
                                          </div>
                                      </div>
                                  </div> -->

                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password" value="" maxlength="15">
                                        <div class="input-group-append">
                                            <button class="btn btn-light border border-start-0" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <ul id="password-rules" class="list-unstyled mt-2 mb-0">
                                        <p id="max-length-warning" style="display:none; color:#d9534f; font-weight:bold; margin-top:10px;">
                                            <i class="fas fa-exclamation-triangle"></i> Maximum 15 characters allowed
                                        </p>
                                        <?php /* <li id="rule-length" class="text-danger"><i class="fas fa-times"></i> <em>At least 4 characters</em></li> */ ?>
                                        <li id="rule-minlength" class="text-danger"><i class="fas fa-times"></i> <em>At least 4 characters</em></li>
                                        <?php /* <li id="rule-maxlength" class="text-danger"><i class="fas fa-times"></i> <em>Maximum 15 characters allowed</em></li> */ ?>
                                        <li id="rule-uppercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one uppercase letter (A–Z)</em></li>
                                        <li id="rule-lowercase" class="text-danger"><i class="fas fa-times"></i> <em>At least one lowercase letter (a–z)</em></li>
                                        <li id="rule-number" class="text-danger"><i class="fas fa-times"></i> <em>At least one number (0–9)</em></li>
                                        <li id="rule-special" class="text-danger"><i class="fas fa-times"></i> <em>At least one special character (!@#$...)</em></li>
                                    </ul>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                <h5 class="h5 text-bold text-secondary m-0 flex-grow-1 text-center"><?php echo !empty($page_title) ? $page_title : ''; ?></h5>
                                <a href="<?= SITE_URL . '/admin/user' ?>" class="btn btn-secondary float-right">Back <?php /*  <i aria-hidden="true" class="fa fa-redo"></i> */ ?></a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= ADMIN_LTE3 ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        $('#group_id').on('change', function() {
            var selectedText = $("#group_id option:selected").text().trim();

            if (selectedText === 'Client') {
                $('#orgz_id').closest('.form-group').show();
                $('#orgz_id').attr('required', true);
            } else {
                $('#orgz_id').closest('.form-group').hide();
                $('#orgz_id').removeAttr('required');
                $('#orgz_id').val('');
            }
        });

        $('#group_id').trigger('change');

        var selectedClientId = "<?= isset($edit['organization']) ? $edit['organization'] : '' ?>";

        $.ajax({
            url: '/get_organizations',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var $orgSelect = $('#orgz_id');
                $orgSelect.empty().append('<option value="">Choose Organization</option>');

                $.each(response, function(index, item) {
                    var isSelected = item.value === selectedClientId ? ' selected' : '';
                    $orgSelect.append('<option value="' + item.value + '"' + isSelected + '>' + item.text + '</option>');
                });
            },
            error: function() {
                alert('Failed to load organizations. Please try again.');
            }
        });

        $('#togglePassword').on('click', function() {

            const passwordField = $('#password');

            const passwordFieldType = passwordField.attr('type');

            const passwordToggleIcon = $(this).find('i');


            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                passwordToggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                passwordToggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        const passwordInput = $('#password');
        const form = $('#editUserFrm');
        const cardBody = $('.card-body');
        const passwordRules = $('#password-rules');

        function validatePasswordRules(password) {
            let rules = {
                minlength: password.length >= 4,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            $('#password-rules li').removeClass('text-success text-danger bold')
                .find('i').removeClass('fa-check fa-times');

            for (let rule in rules) {
                let $li = $('#rule-' + rule);
                if (rules[rule]) {
                    $li.addClass('text-success');
                    $li.find('i').addClass('fas fa-check');
                } else {
                    $li.addClass('text-danger bold');
                    $li.find('i').addClass('fas fa-times');
                }
            }

            return Object.values(rules).every(Boolean);
        }

        function resetPasswordRulesUI() {
            $('#password-rules li')
                .removeClass('text-success bold')
                .addClass('text-danger')
                .each(function() {
                    $(this).find('i').removeClass('fa-check').addClass('fa-times');
                });

            passwordRules.removeClass('show').hide();
            cardBody.addClass('card-collapsed');
            $('#max-length-warning').hide();
        }

        passwordInput.on('focus', function() {
            passwordRules.fadeIn(400, function() {
                passwordRules.addClass('show');
            });
            cardBody.removeClass('card-collapsed');
        });

        passwordInput.on('keydown', function(e) {
            const val = $(this).val();
            const controlKeys = [8, 37, 38, 39, 40, 46]; // Allow delete, arrows, backspace

            if (val.length >= 15 && !controlKeys.includes(e.keyCode)) {
                $('#max-length-warning').fadeIn(200);
            } else {
                $('#max-length-warning').fadeOut(200);
            }
        });

        passwordInput.on('keyup', function() {
            const password = $(this).val();
            validatePasswordRules(password);

            if (password.length < 15) {
                $('#max-length-warning').fadeOut(200);
            }
        });

        form.validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                group_id: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "password") {
                    error.appendTo(element.closest('.form-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                const isValid = validatePasswordRules(passwordInput.val());
                if (!isValid) {
                    mug_alert_all('warning', 'Warning', 'Please ensure the password meets all the listed requirements.');
                    passwordInput.focus();
                    return false;
                }
                save_user_details();
            }
        });

        <?php /*
        function save_user_details() {
            let is_active = $('#active').prop('checked') ? 1 : 0;

            $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                type: "POST",
                data: {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    group_id: $("#group_id").val(),
                    password: $("#password").val(),
                    is_active: is_active,
                    id: $("#id").val()
                },
                url: "/ajaxV2/edit_user_details",
                dataType: "json",
                timeout: 60000,
                success: function(response) {
                    if (response.success > 0) {
                        //form[0].reset();
                        resetPasswordRulesUI();
                        mug_alert_all('success', 'Success', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', response.msg || 'Something went wrong. Please try again later!!');
                    }
                    $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
                },
                error: function() {
                    $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        } */ ?>

        function save_user_details() {
            let is_active = $('#active').prop('checked') ? 1 : 0;

            $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

            let data = {
                name: $("#name").val(),
                email: $("#email").val(),
                group_id: $("#group_id").val(),
                password: $("#password").val(),
                is_active: is_active,
                id: $("#id").val()
            };

            if ($("#group_id").val() === "5") {
                data.orgz_id = $("#orgz_id").val();
            }

            $.ajax({
                type: "POST",
                // data: {
                //     name: $("#name").val(),
                //     email: $("#email").val(),
                //     group_id: $("#group_id").val(),
                //     password: $("#password").val(),
                //     is_active: is_active,
                //     id: $("#id").val()
                // },
                data: data,
                url: "/ajaxV2/edit_user_details",
                dataType: "json",
                timeout: 60000,
                success: function(response) {
                    if (response.success > 0) {
                        //form[0].reset();
                        $("#password").val('');
                        resetPasswordRulesUI();
                        mug_alert_all('success', 'Success', response.msg);

                        if ($("#group_id").val() === "5") {

                            var selectedClientId = $("#orgz_id").val();

                            $.ajax({
                                url: '/get_organizations',
                                method: 'POST',
                                dataType: 'json',
                                success: function(response) {
                                    var $orgSelect = $('#orgz_id');
                                    $orgSelect.empty().append('<option value="">Choose Organization</option>');

                                    $.each(response, function(index, item) {
                                        var isSelected = item.value === selectedClientId ? ' selected' : '';
                                        $orgSelect.append('<option value="' + item.value + '"' + isSelected + '>' + item.text + '</option>');
                                    });
                                },
                                error: function() {
                                    alert('Failed to load organizations. Please try again.');
                                }
                            });
                        }

                    } else {
                        mug_alert_all('error', 'Error', response.msg || 'Something went wrong. Please try again later!!');
                    }
                    $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
                },
                error: function() {
                    $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });
</script>