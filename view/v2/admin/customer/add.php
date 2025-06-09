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

<link rel="stylesheet" href="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" integrity="sha512-tlP4yGOtHdxdeW9/VptIsVMLtgnObNNr07KlHzK4B5zVUuzJ+9KrF86B/a7PJnzxEggPAMzoV/eOipZd8wWpag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="<?= ADMIN_LTE3 ?>/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js" integrity="sha512-YwbKCcfMdqB6NYfdzp1NtNcopsG84SxP8Wxk0FgUyTvgtQe0tQRRnnFOwK3xfnZ2XYls+rCfBrD0L2EqmSD2sA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

        .card-collapsed {
            max-height: 75vh;
            /* default height */
        }

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


                        <form role="Form" method="post" id="myForm" name="myForm" class="admin_form" accept-charset="UTF-8" autocomplete="off">
                            <div class="card-body card-expand card-collapsed">
                                <?php //$this->alert(); 
                                ?>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Enter Client Name" required="" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" id="email" class="form-control" required="" name="email" placeholder="Example: john.doe@gmail.com" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Site Code</label>
                                            <input type="text" id="site_code" class="form-control" name="site_code" maxlength="5" placeholder="Enter Site Code">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client Number</label>
                                            <input type="text" id="client_code" class="form-control num" name="client_code" maxlength="4" placeholder="Enter Client Number">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client Site Name</label>
                                            <input type="text" id="client_site_name" class="form-control" name="client_site_name" maxlength="100" placeholder="Enter Client Site Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Headquarters ?</label>
                                            <select id="is_headquarters" name="is_headquarters" class="form-control" required="">
                                                <option value="">-- Select --</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Address Line 1</label>
                                            <input type="text" name="address_line1" id="address_line1" class="form-control" maxlength="100" placeholder="Enter Address Line 1">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" name="address_line2" id="address_line2" class="form-control" maxlength="50" placeholder="Enter Address Line 2">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" id="city" class="form-control" maxlength="50" placeholder="Enter City">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" name="state" id="state" class="form-control" maxlength="50" placeholder="Enter State">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" name="zipcode" id="zipcode" class="form-control" maxlength="10" placeholder="Enter Zip Code">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control num" maxlength="15" placeholder="Enter Phone Number">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Default Turn Around Time</label>
                                            <select id="contract_tat" name="contract_tat" class="form-control customers_choose" required="">
                                                <option value="">Choose Default TAT</option>
                                                <?php
                                                foreach ($tat_ddwn as $key => $value) {
                                                ?>
                                                    <?php /* <option value="<?php echo $value['tat']; ?>"><?php echo $value['tat']; ?></option> */ ?>

                                                    <?php /* <option value="<?php echo $value['tat'] . ' ' . $value['tat_unit']; ?>"><?php echo $value['tat'] . ' ' . $value['tat_unit']; ?></option> */ ?>
                                                    <option value="<?php echo $value['tat_id']; ?>"><?php echo $value['tat'] . ' ' . $value['tat_unit']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- <input type="hidden" id="password" class="form-control" name="password" value="pwd4cust!23"> -->

                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" id="password" required="" class="form-control" name="password" placeholder="Enter Password" maxlength="15">
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
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit" name="submit">Save <i aria-hidden="true" class="fa fa-save"></i></button>
                                    <a href="<?= SITE_URL . '/admin/customer' ?>" class="btn btn-secondary float-right">Back</a>
                                </div>
                        </form>

                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>

<script>
    /*$.validator.addMethod("minLengthCheck", function(value, element) {
        return value.length >= 4;
    }, "Password must be at least 4 characters long.");

    $.validator.addMethod("hasUppercase", function(value, element) {
        return /[A-Z]/.test(value);
    }, "Password must include at least one uppercase letter.");

    $.validator.addMethod("hasLowercase", function(value, element) {
        return /[a-z]/.test(value);
    }, "Password must include at least one lowercase letter.");

    $.validator.addMethod("hasNumber", function(value, element) {
        return /[0-9]/.test(value);
    }, "Password must include at least one number.");

    $.validator.addMethod("hasSpecialChar", function(value, element) {
        return /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value);
    }, "Password must include at least one special character."); */

    $("#myForm").validate({
        rules: {
            client_name: {
                required: true,
                maxlength: 100
            },
            email: {
                required: true,
                email: true,
                maxlength: 100
            },
            site_code: {
                maxlength: 5
            },
            client_code: {
                maxlength: 4,
                digits: true
            },
            client_site_name: {
                maxlength: 100
            },
            is_headquarters: {
                required: true
            },
            address_line1: {
                maxlength: 100
            },
            address_line2: {
                maxlength: 50
            },
            city: {
                maxlength: 50
            },
            state: {
                maxlength: 50
            },
            zipcode: {
                maxlength: 10
            },
            phone_number: {
                maxlength: 15
            },
            /*password: {
                required: true,
                minLengthCheck: true,
                hasUppercase: true,
                hasLowercase: true,
                hasNumber: true,
                hasSpecialChar: true,
                maxlength: 15
            }*/
        },
        /*messages: {
            password: {
                required: "Please enter a password.",
                maxlength: "Password cannot be longer than 15 characters."
            }
        },
        onkeyup: function (element, event) {
            if (element.id === "password") {
                $(element).valid();
            } else {
                // For other fields, you might want to keep the default behavior
                $(element).valid();
            }
        }, */
        submitHandler: function() {
            save_customer_details();
        }
    });

    function save_customer_details() {
        $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {
                client_name: $("#client_name").val(),
                email: $("#email").val(),
                site_code: $("#site_code").val(),
                client_code: $("#client_code").val(),
                password: $("#password").val(),
                client_site_name: $("#client_site_name").val(),
                is_headquarters: $("#is_headquarters").val(),
                address_line1: $("#address_line1").val(),
                address_line2: $("#address_line2").val(),
                city: $("#city").val(),
                state: $("#state").val(),
                zipcode: $("#zipcode").val(),
                phone_number: $("#phone_number").val(),
                contract_tat: $("#contract_tat").val()
            },
            url: "/ajaxV2/save_customer_details",
            dataType: "json",
            timeout: 60000,
            success: function(response) {
                if (response.success > 0) {
                    $("#myForm")[0].reset();
                    mug_alert_all('success', 'Success', response.msg);
                } else {
                    if (response.msg != '') {
                        mug_alert_all('error', 'Error', response.msg);
                    } else {
                        mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                    }
                }
                $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
            },
            error: function(jqXHR, textStatus) {
                $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        const passwordInput = $('#password');
        const form = $('#addUserFrm');
        const cardBody = $('.card-body');
        const passwordRules = $('#password-rules');

        function validatePasswordRules(password) {
            let rules = {
                /* length: password.length >= 4, */
                minlength: password.length >= 4,
                /* maxlength: password.length <= 15, */
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
        }

        passwordInput.on('focus', function() {
            passwordRules.fadeIn(400, function() {
                passwordRules.addClass('show');
            });
            cardBody.removeClass('card-collapsed');
        });

        passwordInput.on('keydown', function(e) {
            const val = $(this).val();

            // Allow control keys (backspace, arrows, delete, etc.)
            const controlKeys = [8, 37, 38, 39, 40, 46];
            if (val.length >= 15 && !controlKeys.includes(e.keyCode)) {
                $('#max-length-warning').fadeIn(200);
            } else {
                $('#max-length-warning').fadeOut(200);
            }
        });

        passwordInput.on('keyup', function() {
            //validatePasswordRules($(this).val());

            const password = $(this).val();
            validatePasswordRules(password);

            // Extra safety: hide warning if under limit
            if (password.length < 15) {
                $('#max-length-warning').fadeOut(200);
            }
        });

        $("#myForm").validate({
            rules: {
                client_name: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                site_code: {
                    maxlength: 5
                },
                client_code: {
                    maxlength: 4,
                    digits: true
                },
                client_site_name: {
                    maxlength: 100
                },
                is_headquarters: {
                    required: true
                },
                address_line1: {
                    maxlength: 100
                },
                address_line2: {
                    maxlength: 50
                },
                city: {
                    maxlength: 50
                },
                state: {
                    maxlength: 50
                },
                zipcode: {
                    maxlength: 10
                },
                phone_number: {
                    maxlength: 15
                },
                password: {
                    required: true,
                }
                /*password: {
                    required: true,
                    minLengthCheck: true,
                    hasUppercase: true,
                    hasLowercase: true,
                    hasNumber: true,
                    hasSpecialChar: true,
                    maxlength: 15
                }*/
            },
            /*messages: {
                password: {
                    required: "Please enter a password.",
                    maxlength: "Password cannot be longer than 15 characters."
                }
            },
            onkeyup: function (element, event) {
                if (element.id === "password") {
                    $(element).valid();
                } else {
                    // For other fields, you might want to keep the default behavior
                    $(element).valid();
                }
            }, */
            submitHandler: function() {
                const isValid = validatePasswordRules(passwordInput.val());
                if (!isValid) {
                    mug_alert_all('warning', 'Warning', 'Please ensure the password meets all the listed requirements.');
                    passwordInput.focus();
                    return false;
                }
                save_customer_details();
            }
        });

        function save_customer_details() {
            $("#submit").prop("disabled", true).html('Please wait... <i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: "POST",
                data: {
                    client_name: $("#client_name").val(),
                    email: $("#email").val(),
                    site_code: $("#site_code").val(),
                    client_code: $("#client_code").val(),
                    password: $("#password").val(),
                    client_site_name: $("#client_site_name").val(),
                    is_headquarters: $("#is_headquarters").val(),
                    address_line1: $("#address_line1").val(),
                    address_line2: $("#address_line2").val(),
                    city: $("#city").val(),
                    state: $("#state").val(),
                    zipcode: $("#zipcode").val(),
                    phone_number: $("#phone_number").val(),
                    contract_tat: $("#contract_tat").val()
                },
                url: "/ajaxV2/save_customer_details",
                dataType: "json",
                timeout: 60000,
                success: function(response) {
                    if (response.success > 0) {
                        $("#myForm")[0].reset();
                        mug_alert_all('success', 'Success', response.msg);
                    } else {
                        if (response.msg != '') {
                            mug_alert_all('error', 'Error', response.msg);
                        } else {
                            mug_alert_all('error', 'Error', 'Something went wrong. Please try again later!!');
                        }
                    }
                    $("#submit").prop("disabled", false).html('Save <i aria-hidden="true" class="fa fa-save"></i>');
                },
                error: function(jqXHR, textStatus) {
                    $("#submit").prop("disabled", false).html('Retry <i aria-hidden="true" class="fas fa-redo"></i>');
                }
            });
        }
    });
</script>