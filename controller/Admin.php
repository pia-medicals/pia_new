<?php

class Admin extends Controller {

    public $Logindb;
    public $Admindb;
    public $user;
    public $Report;
    public $dbmodel;

    function __construct() {
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Report = $this->model('report');
        $this->dbmodel = $this->model('dashboardmodel'); //RC 

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $userdata = $_SESSION['user'];
            // $this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index() {
        if (isset($_POST['btnSave'])) {
            $data['user'] = $this->user;
            $data['jobs_last_user'] = $this->dbmodel->getnewuser();
            $this->view('dashboard/index', $data);
        } else {
            $this->view('dashboard/index', $data);
        }
    }

    public function user() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

        /* $key = "";
          $page_now = 1;

          if (isset($_GET)) {
          if (isset($_GET['page']))
          $page_now = $_GET['page'];
          else
          $page_now = 1;

          if (isset($_GET['key']))
          $key = $_GET['key'];
          else
          $key = "";
          } */

        //$data['user_list'] = $this->Admindb->users($key, $page_now);
        // $data['user_list']['results'] = $this->Admindb->table_full('users');

        /*  if (isset($_GET['delete']) && $_GET['delete'] != "") {
          $id = $_GET['delete'];
          $status = $this->Admindb->delete('users', $id, 'user_id');
          // $this->add_alert($status['type'], $status['msg']);
          $this->redirect('admin/user');
          } */

        /*  if (isset($_POST['submit'])) {
          $form_data = $_POST;
          $form_data['updated'] = date("Y-m-d H:i:s");
          $data['edit'] = $this->Admindb->user_by_id($form_data['id']);
          if ($form_data['password'] == "")
          $form_data['password'] = $data['edit']['password'];
          else
          $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
          if (isset($form_data['active'])) {
          $form_data['is_active'] = 1;
          } else {
          $form_data['is_active'] = 0;
          }

          unset($form_data['submit']);

          // Filter out keys that should not trigger validation errors
          $ignored_keys = ['is_active'];

          // Filter the $form_data for validation, ignoring certain keys
          $filtered_form_data = array_diff_key($form_data, array_flip($ignored_keys));

          // Validate only the filtered data
          if (!empty($this->empty_key_value($filtered_form_data))) {
          $error = $this->empty_key_value($filtered_form_data);
          $error_label = '';

          // Create a comma-separated error label string
          foreach ($error as $key => $value) {
          $error_label .= ucfirst($this->underscore_remove($value)) . ',';
          }

          // Add a validation error alert
          $this->add_alert('danger', 'Validation Error in ' . rtrim($error_label, ','));

          $this->redirect('admin/user?edit=' . $form_data['id']);
          } else {

          $status = $this->Admindb->user_update($form_data);
          $this->add_alert($status['type'], $status['msg']);
          $this->redirect('admin/user?edit=' . $form_data['id']);
          }
          } */

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['select_array'] = $this->Admindb->getUserTypes();
            $data['edit'] = $this->Admindb->user_by_id($id);
            $data['page_title'] = 'Edit User';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/user/edit', $data);
        } else {
            $data['page_title'] = 'User Setup';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/user/list', $data);
        }
    }

    public function add_user() {
        $data['user'] = $this->user;
        $data['page_title'] = 'Add User';
        $this->admin_sidebar_v2($data);
  
        $data['select_array'] = $this->Admindb->getUserTypes();
        $this->view('v2/admin/user/add', $data);
    }

    public function add_customer() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);
            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                $form_data['created'] = $form_data['updated'] = date("Y-m-d H:i:s");
                $form_data['active'] = 1;
                $form_data['group_id'] = 5;
                $form_data['id'] = '';
                $form_data['user'] = $this->user;
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                $form_data['created_by'] = $_SESSION['user']->user_id;

                $status = $this->Admindb->add_user($form_data);

                //$result = $this->Admindb->add_usertime_line($status['customer_id']);

                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/add_customer');
        }
        $data['tat_ddwn'] = $this->Admindb->getTATddwn();
        $data['page_title'] = 'Add Client';
        $this->admin_sidebar_v2($data);
        $this->view('v2/admin/customer/add', $data);
    }

    public function dicom_details() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Clario', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/dicom_details');
        }

        $data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario', ' WHERE assignee = 0');

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $this->view('admin/dicom/edit', $data);
        } else {
            $this->view('admin/dicom/list', $data);
        }
    }

    public function dicom_details_month() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Clario', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/dicom_details');
        }
        $data['asignee'] = $this->Admindb->get_all_analyst();

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $this->view('admin/dicom/edit', $data);
        }

        if (isset($_POST['last_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
            $data['day'] = $day;
            $this->view('admin/dicom/all', $data);
        }

        if (isset($_POST['filter_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $asignee = $form_data['asignee_select'];
            //$status = $form_data['status_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_assignee($day, $asignee);
            $data['day'] = $day;
            $data['sel_assignee'] = $asignee;
            //$data['status'] = $status;                
            $this->view('admin/dicom/month', $data);
        } else {
            $this->view('admin/dicom/month', $data);
        }
    }

    public function dicom_details_all() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();        
        $this->view('v2/admin/dicom/all', $data);       
    }

    public function missing_tat() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Clario', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/dicom_details');
        }
        $data['asignee'] = $this->Admindb->get_all_analyst();
        //$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_full();


        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $this->view('admin/dicom/edit', $data);
        }
        if (isset($_POST['last_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
            $data['day'] = $day;
            $this->view('admin/dicom/missing_tat', $data);
        }
        if (isset($_POST['filter_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $asignee = $form_data['asignee_select'];
            //$status = $form_data['status_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_assignee($day, $asignee);
            $data['day'] = $day;
            $data['sel_assignee'] = $asignee;
            //$data['status'] = $status;                
            $this->view('admin/dicom/missing_tat', $data);
        } else {
            $this->view('admin/dicom/missing_tat', $data);
        }
    }

    public function dicom_details_all_new() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Clario', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/dicom_details');
        }
        $data['asignee'] = $this->Admindb->get_all_analyst();
        //$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_full();


        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $this->view('admin/dicom/edit', $data);
        }
        if (isset($_POST['last_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
            $data['day'] = $day;
            $this->view('admin/dicom/all_new', $data);
        }
        if (isset($_POST['filter_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $asignee = $form_data['asignee_select'];
            //$status = $form_data['status_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_assignee($day, $asignee);
            $data['day'] = $day;
            $data['sel_assignee'] = $asignee;
            //$data['status'] = $status;                
            $this->view('admin/dicom/all_new', $data);
        } else {
            $this->view('admin/dicom/all_new', $data);
        }
    }

    public function stat_all() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Clario', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/dicom_details');
        }
        $data['asignee'] = $this->Admindb->get_all_analyst();
        //$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_full();


        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $this->view('admin/dicom/edit', $data);
        }
        if (isset($_POST['last_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
            $data['day'] = $day;
            $this->view('admin/dicom/stat', $data);
        }
        if (isset($_POST['filter_search'])) {

            $form_data = $_POST;
            $day = $form_data['last_select'];
            $asignee = $form_data['asignee_select'];
            //$status = $form_data['status_select'];
            $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_assignee($day, $asignee);
            $data['day'] = $day;
            $data['sel_assignee'] = $asignee;
            //$data['status'] = $status;                
            $this->view('admin/dicom/stat', $data);
        } else {
            $this->view('admin/dicom/stat', $data);
        }
    }

    public function dicom_details_assigned() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        // $data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_full();


        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->get_by_id('Clario', $id);
            $data['edit_wsheet'] = $this->Admindb->whseet_by_cid($id);
            //print_r($data['edit_wsheet']);
            $this->view('admin/dicom/edit_assigne', $data);
        } else {
            $this->view('admin/dicom/list_assign', $data);
        }
    }

    public function salesforce_code() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        $data['salesforce_code_list'] = $this->Admindb->salesforce_code($page_now, SITE_URL . '/admin/salesforce_code');
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('Salesforce', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/salesforce_code');
        }



        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->salesforce_code_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/salesforce_code?edit=' . $form_data['id']);
            }
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->salesforce_code_by_id($id);
            $this->view('admin/salesforce_code/edit', $data);
        } else {
            $this->view('admin/salesforce_code/list', $data);
        }
    }

    public function salesforce_code_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            $salesforce = $this->Admindb->salesforce_code_by_code($form_data['code']);

            if (is_array($salesforce)) {
                $this->add_alert('danger', 'Salesforce code exist!');
                $this->redirect('admin/salesforce_code_add');
            }
            if (!empty($this->empty_key_value($form_data)) && is_array($salesforce)) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->salesforce_code_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/salesforce_code_add');
        }
        $this->view('admin/salesforce_code/add', $data);
    }

    public function Customer() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $key = "";
        $page_now = 1;   
        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->client_details_by_id($id);
            $client_account_id = !empty($data['edit']['client_account_id']) ? $data['edit']['client_account_id'] : '';
            $data['max_disc'] = $this->Admindb->get_max_discount_for_customer($client_account_id);
            $data['page_title'] = 'Edit Client';
            $data['tat_ddwn'] = $this->Admindb->getTATddwn();
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/customer/edit', $data);
        } else {
            $data['page_title'] = 'Client Setup';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/customer/list', $data);
        }
    }

    public function billing_code() {
        $data['user'] = $this->user;
        $data['sfc'] = $this->Admindb->salesforce_code_full();
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }

        $data['billing_code_list'] = $this->Admindb->billing_code($page_now, SITE_URL . '/admin/billing_code');
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('
				Billing_codes', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/billing_code');
        }


        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            //$this->debug($form_data); die();

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->billing_code_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            //$this->redirect('admin/salesforce_code_add');
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->billing_code_by_id($id);
            $this->view('admin/billing_code/edit', $data);
        } else {
            $this->view('admin/billing_code/list', $data);
        }
    }

    public function discount_pricing() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $data['discount_pricing_list'] = $this->Admindb->get_discount_range();

        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                $status = $this->Admindb->discount_range_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }
        }

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('
				discount_range', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/discount_pricing');
        }


        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->discount_range_by_id($id);
            $this->view('admin/discount/edit', $data);
        } else {
            $this->view('admin/discount/list', $data);
        }
    }

    public function billing_code_add() {
        $data['user'] = $this->user;
        $data['sfc'] = $this->Admindb->salesforce_code_full();
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->billing_code_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/billing_code_add');
        }
        $this->view('admin/billing_code/add', $data);
    }

    public function discount_pricing_add() {
        $data['user'] = $this->user;
        $data['max_disc'] = $this->Admindb->get_last_discount_range();
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;

            unset($form_data['submit']);
            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                if ($data['max_disc'] + 1 < $form_data['maximum_value']) {
                    $status = $this->Admindb->discount_pricing_add($form_data);
                    $this->add_alert($status['type'], $status['msg']);
                } else {
                    $status = $this->Admindb->discount_pricing_add($form_data);
                    $this->add_alert('danger', 'Enter Appropriate value');
                }
            }
            $this->redirect('admin/discount_pricing_add');
        }
        $this->view('admin/discount/add', $data);
    }

    public function import() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $colm = array('Accession', 'MRN', 'Patient Name', 'Site Procedure', 'Last Modified', 'Exam Time', 'Status', 'Priority', 'Site', 'Hospital');

        if (isset($_FILES) && !empty($_FILES)) {

            $file_name = $this->fileUpload($_FILES['up'], XL_PATH);
            $data_xl = $this->read_xl($file_name);

            foreach ($data_xl[0] as $key => $value) {
                if (in_array($value, $colm)) {
                    $needed_keys[] = $key;
                }
            }


            foreach ($data_xl as $key => $value) {
                if ($key == 0)
                    continue;
                if ($value[0] == '' || $value[0] == null)
                    continue;
                $inner_data = array();
                foreach ($value as $key_inner => $value_inner) {
                    if (in_array($key_inner, $needed_keys)) {
                        $inner_data[] = $value_inner;
                    }
                }
                $insert_data[] = $inner_data;
            }

            $update_count = 0;
            $rept_count = 0;
            $updated_array = array();
            $insert_count = 0;

            $msg = '<strong>Import successfully.</strong><br>';

            //$this->debug($insert_data); die();

            foreach ($insert_data as $key => $value) {
                $update_id = $this->Admindb->clario_exist($value[0], $value[1]);
                $hospital = $this->Admindb->hospital_name_exist($value[8]);

                if (!$hospital) {
                    $a = $this->Admindb->hospital_add($value[8]);
                }




                if ($update_id) {
                    $value_change = $this->Admindb->clario_change_exist($update_id, $value);
                    if ($value_change) {
                        $updated = '';
                        $key_values = array_keys($value_change);
                        foreach ($key_values as $index => $value_inner) {
                            if ($index != count($key_values) - 1)
                                $updated .= ucfirst(str_replace('_', ' ', $value_inner)) . ', ';
                            else
                                $updated .= ucfirst(str_replace('_', ' ', $value_inner)) . ' ';
                        }
                        $clario_row = $this->Admindb->get_by_id('Clario', $update_id);
                        $updated .= 'Updated in (Accession: ' . $clario_row['accession'] . ') <br>';
                        $updated_array[] = $updated;
                        $status = $this->Admindb->clario_import_update($update_id, $value);
                        $update_count++;
                    } else {
                        $status['type'] = 'success';
                        $rept_count++;
                    }
                } else {
                    $status = $this->Admindb->clario_import($value);
                    $insert_count++;
                }
            }
            $msg .= $insert_count . ' Rows Insert<br>';
            $msg .= $update_count . ' Rows Update<br>';
            if (!empty($updated_array))
                foreach ($updated_array as $value) {
                    $msg .= $value;
                }
            $msg .= $rept_count . ' Rows Skipped<br>';

            $this->add_alert($status['type'], $msg);
        }



        $this->view('admin/import_export/import_clario', $data);
    }

    public function billing_hours() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $data['analyst_worksheet'] = $this->Admindb->collect_wsheet();
        $this->view('dashboard/accounts/index', $data);
    }

    public function accounts() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $form_data = $_POST;
        if (!empty($form_data))
            $data['wsheet'] = $this->Admindb->select_wsheet_date($form_data);

        $this->view('admin/statistics/worksheet', $data);
    }

    public function billing() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        //$this->carry_save();
        $form_data = $_POST;
        if (!empty($form_data)) {
            $data['site'] = $ids = $form_data['site'];

            $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));
            $effectiveDateend = strtotime("-1 months", strtotime($form_data['end_date']));

            $date = date("Y-m", $effectiveDate);
            $dateend = date("Y-m", $effectiveDateend);

            $data['pre_date'] = $date;

            $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date, $dateend);
            // $dat = $this->Admindb->carry_forward($ids, $date, $dateend);
            // print_r($dat);



            if ($data['carry_frwd']['data']) {
                foreach ($ids as $kk) {
                    $ans_key = array_column($data['carry_frwd']['data'][$kk], 'analysis');
                    $ans = array_column($data['carry_frwd']['data'][$kk], 'count');
                    $data['carry_frwd']['ans_ids'][$kk] = $ans_key;
                    $data['carry_frwd']['ans_count'][$kk] = array_combine($ans_key, $ans);
                }
            }
            print_r($data['carry_frwd']['ans_count']);

            $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date'], $form_data['end_date']);

            /*    if (empty($wsheet['time_id'])) {

              $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $ids);
              $data['new_time_id'] = $new_time_id[0]['time_id'];
              } */

            $data['wsheet'] = $wsheet['worksheets_details'];
            $data['time_id'] = $wsheet['time_id'];
            $data['start_date'] = $form_data['start_date'];
            $data['end_date'] = $form_data['end_date'];
            $data['site'] = $form_data['site'];
            $custmernames = $this->Admindb->get_custmernames($ids);
            $data['custmernames'] = $custmernames;
        }
        $this->view('admin/billing/come', $data);
    }

    public function billing_summary_customer() {


        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        //$this->carry_save();
        $form_data = $_POST;
        if (!empty($form_data)) {


            $data['site'] = $ids = $form_data['site'];

            $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));

            $effectiveDateend = strtotime("-1 months", strtotime($form_data['end_date']));

            $date = date("Y-m", $effectiveDate);
            $dateend = date("Y-m", $effectiveDateend);

            $data['pre_date'] = $date;

            /* $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date, $dateend);



              /*  if ($data['carry_frwd']['data']) {
              $ans_key = array_column($data['carry_frwd']['data'], 'analysis');
              $ans = array_column($data['carry_frwd']['data'], 'count');
              $data['carry_frwd']['ans_ids'] = $ans_key;
              $data['carry_frwd']['ans_count'] = array_combine($ans_key, $ans);
              } */

            $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date'], $form_data['end_date']);
            print_r($wsheet);

            // print_r($custmernames);

            if (empty($wsheet['time_id'])) {

                $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $form_data['end_date'], $ids);
                //$data['new_time_id'] = $new_time_id[0]['time_id'];
                $data['new_time_id'] = $new_time_id;
            }

            // $data['wsheet'] = $wsheet['136']['worksheets_details'];
            $data['wsheet'] = $wsheet['worksheets_details'];
            $data['time_id'] = $wsheet['time_id'];
            $data['start_date'] = $form_data['start_date'];
            $data['site'] = $form_data['site'];

            $custmernames = $this->Admindb->get_custmernames($ids);
            $data['custmernames'] = $custmernames;
        }

        $this->view('admin/billing/billing2', $data);
    }

    /*   public function billing_summary_customer() {


      $data['user'] = $this->user;
      $this->admin_sidebar_v2($data);
      //$this->carry_save();
      $form_data = $_POST;
      if (!empty($form_data)) {
      $data['site'] = $ids = $form_data['site'];

      // print_r($ids);

      $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));

      $effectiveDateend = strtotime("-1 months", strtotime($form_data['end_date']));

      $date = date("Y-m", $effectiveDate);
      $dateend = date("Y-m", $effectiveDateend);


      $data['pre_date'] = $date;


      $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date, $dateend);



      if ($data['carry_frwd']['data']) {
      $ans_key = array_column($data['carry_frwd']['data'], 'analysis');
      $ans = array_column($data['carry_frwd']['data'], 'count');
      $data['carry_frwd']['ans_ids'] = $ans_key;
      $data['carry_frwd']['ans_count'] = array_combine($ans_key, $ans);
      }

      $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date'],$form_data['end_date']);




      if (empty($wsheet['time_id'])) {

      $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $ids);
      $data['new_time_id'] = $new_time_id[0]['time_id'];
      }

      $data['wsheet'] = $wsheet['worksheets_details'];
      $data['time_id'] = $wsheet['time_id'];
      $data['start_date'] = $form_data['start_date'];
      $data['site'] = $form_data['site'];
      }



      $this->view('admin/billing/billing2', $data);
      } */

    public function billing_summary_detailed() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        //$this->carry_save();
        $ids = [];
        $form_data = $_POST;

        if (!empty($form_data)) {
            if (isset($_POST['btnMRNView'])) {
                $data['viewMrn'] = 1;
            } else {
                $data['viewMrn'] = 0;
            }
            $data['site'] = $ids = $form_data['site'];
            $date = $form_data['start_date'];
            $edate = $form_data['end_date'];
            //  print_r($ids);
            // echo $date;
            // echo $edate;
            //$data['wsheet'] = $this->Admindb->billing_summary_detailed_new($ids, $date, $edate);
            $data['wsheet'] = $this->Admindb->billing_summary_detailed_basic($ids, $date, $edate);

            // echo $ids;
        }
        $custmernames = $this->Admindb->get_custmernames($ids);
        $data['custmernames'] = $custmernames;
        $this->view('admin/billing/billing3', $data);
    }

    public function get_billing_detail_ajax() {
        $data['user'] = $this->user;
        $ids = [];
        $form_data = $_POST;
        if (!empty($form_data)) {
            if (isset($_POST['btnMRNView'])) {
                $data['viewMrn'] = 1;
            } else {
                $data['viewMrn'] = 0;
            }
            $data['site'] = $ids = $form_data['site'];
            $date = $form_data['start_date'];
            $edate = $form_data['end_date'];
            $data['wsheet'] = $this->Admindb->billing_summary_detailed_basic($ids, $date, $edate);
        }
        $custmernames = $this->Admindb->get_custmernames($ids);
        $data['custmernames'] = $custmernames;
        $this->view('admin/billing/billing-ajax', $data);
        die;
    }

    public function billing_summary_analyst() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

        $form_data = $_POST;
        if (!empty($form_data)) {
            $data['site'] = $ids = $form_data['site'];
            if (!empty($form_data['site'])) {
                $data['wsheet'] = $this->Admindb->billing_summary_analyst_new($ids, $form_data['start_date'], $form_data['end_date']);
            } else {
                $data['wsheet'] = $this->Admindb->billing_summary_analyst_all($form_data['start_date'], $form_data['end_date']);
            }
        } else {
            $data['site'] = $ids = '';
            $data['wsheet'] = $this->Admindb->billing_summary_analyst_all();
        }
        $custmernames = $this->Admindb->get_custmernames($ids);
        $data['custmernames'] = $custmernames;
        $this->view('admin/billing/billing4', $data);
    }

    public function profile() {
        $data['user'] = $data['edit'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;

            $form_data['group_id'] = $data['edit']->user_type_ids;
            $form_data['is_active'] = $data['edit']->active;
            $form_data['updated'] = date("Y-m-d H:i:s");
            if ($form_data['password'] == "")
                $form_data['password'] = $data['edit']->password;
            else
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);

            $form_data['profile_picture'] = $data['edit']->profile_picture;
            $upload = $this->imageUpload($_FILES['profile_picture'], '/assets/uploads/user/');
            if ($upload != false) {
                $form_data['profile_picture'] = $_SESSION['user']->profile_picture = $upload;
            }

            unset($form_data['submit']);
            if (!empty($this->empty_key_value($form_data))) {
                $error = $this->empty_key_value($form_data);
                $error_label = '';
                foreach ($error as $key => $value) {
                    $error_label = $error_label . ucfirst($this->underscore_remove($value)) . ',';
                }

                $this->add_alert('danger', 'Validation Error in ' . $error_label);

                $this->redirect('admin/profile');
            } else {

                $status = $this->Admindb->user_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/profile');
            }
        }
        $this->view('user/profile', $data);
    }

    public function analyses() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

        /*  $key = "";
          $page_now = 1;

          if (isset($_GET)) {
          if (isset($_GET['page']))
          $page_now = $_GET['page'];
          else
          $page_now = 1;

          if (isset($_GET['key']))
          $key = $_GET['key'];
          else
          $key = "";
          } */

        // $data['salesforce_code_list']['results'] = $this->Admindb->table_full('analyses');
        /* if (isset($_GET['delete']) && $_GET['delete'] != "") {
          $id = $_GET['delete'];
          $status = $this->Admindb->delete('analyses', $id, 'analyses_id');
          $this->add_alert($status['type'], $status['msg']);
          $this->redirect('admin/analyses');
          } */

        /*  if (isset($_POST['submit'])) {
          $form_data = $_POST;
          if (isset($form_data['active'])) {
          $form_data['is_active'] = 1;
          } else {
          $form_data['is_active'] = 0;
          }
          unset($form_data['submit']);

          if (!empty($this->empty_key_value($form_data))) {
          $this->add_alert('danger', 'Validation Error!');
          } else {
          $status = $this->Admindb->analyses_update($form_data);
          $this->add_alert($status['type'], $status['msg']);
          $this->redirect('admin/analyses?edit=' . $form_data['id']);
          }
          } */

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->analyses_by_id($id);
            $data['page_title'] = 'Edit Analyses';
            $data['tat_ddwn'] = $this->Admindb->getTATddwn();
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/analyses/edit', $data);
        } else {
            $data['page_title'] = 'Global Analyses + Price List Setup';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/analyses/list', $data);
        }
    }

    public function analyses_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        /*
          if (isset($_POST['submit'])) {
          $form_data = $_POST;
          unset($form_data['submit']);
          if (!empty($this->empty_key_value($form_data))) {
          $this->add_alert('danger', 'Validation Error!');
          } else {
          $status = $this->Admindb->analyses_add($form_data);
          $this->add_alert($status['type'], $status['msg']);
          }
          $this->redirect('admin/analyses_add');
          }
         */

         //Newly added
         if(isset($_GET['page_id']) && $_GET['page_id'] != ""){
            $data['page_id'] = $_GET['page_id'];
         }

        $data['page_title'] = 'Analysis';
        $data['tat_ddwn'] = $this->Admindb->getTATddwn();
        $this->view('v2/admin/analyses/add', $data);
    }

    public function analyses_rate() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        $data['salesforce_code_list'] = $this->Admindb->analyses_rate($page_now, SITE_URL . '/admin/analyses_rate');

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('analyses_rate', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/analyses_rate');
        }



        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->analyses_rate_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/analyses_rate?edit=' . $form_data['id']);
            }
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->analyses_rate_by_id($id);
            $this->view('admin/analyses_rate/edit', $data);
        } else {
            $this->view('admin/analyses_rate/list', $data);
        }
    }

    public function analyses_rate_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data)) && is_array($salesforce)) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->analyses_rate_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/analyses_rate_add');
        }
        $this->view('admin/analyses_rate/add', $data);
    }

    /*
     * Analyses Category 
     * Function added on 22-1-2019
     */

    public function analyses_category() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        // $data['analyses_category_list'] = $this->Admindb->analyses_category($page_now, SITE_URL . '/admin/analyses_category');

        /* if (isset($_GET['delete']) && $_GET['delete'] != "") {
          $id = $_GET['delete'];
          $status = $this->Admindb->delete('analyses_category', $id, 'category_id');
          $this->add_alert($status['type'], $status['msg']);
          $this->redirect('admin/analyses_category');
          }
         */
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            if (isset($form_data['active'])) {
                $form_data['is_active'] = 1;
            } else {
                $form_data['is_active'] = 0;
            }
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->analyses_category_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/analyses_category?edit=' . $form_data['id']);
            }

            //$this->redirect('admin/salesforce_code_add');
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->analyses_category_by_id($id);
            $data['page_title'] = 'Edit Analyses Category';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/analyses_category/edit', $data);
        } else {
            $data['page_title'] = 'Analyses Categories';
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/analyses_category/list', $data);
        }
    }

    public function analyses_category_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        /* if (isset($_POST['submit'])) {
          $form_data = $_POST;
          unset($form_data['submit']);
          if (!empty($this->empty_key_value($form_data))) {
          $this->add_alert('danger', 'Validation Error!');
          } else {
          $status = $this->Admindb->analyses_category_add($form_data);
          $this->add_alert($status['type'], $status['msg']);
          }
          $this->redirect('admin/analyses_category_add');
          } */
        $data['page_title'] = 'Analyses Categories';
        $this->view('v2/admin/analyses_category/add', $data);
    }

    public function ajax_analysis_polpulate() {
        $id = $_GET["id"];
        $data = $this->Admindb->analyses_by_id($id);
        echo json_encode($data);
        die();
        //echo $id;
    }

    public function test() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->carry_save();
    }

    public function add_miscellaneous_billing() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data)) && is_array($salesforce)) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->miscellaneous_billing_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/miscellaneous_billing');
        }
        $this->view('v2/admin/miscellaneous_billing/add', $data);
    }

    public function miscellaneous_billing() {


        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

        $key = "";
        $page_now = 1;

        if (isset($_GET)) {
            if (isset($_GET['page']))
                $page_now = $_GET['page'];
            else
                $page_now = 1;

            if (isset($_GET['key']))
                $key = $_GET['key'];
            else
                $key = "";
        }


        $data['miscellaneous_billing']['results'] = $this->Admindb->table_full_miscellaneous_billing('miscellaneous_billing');

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('miscellaneous_billing', $id);
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/miscellaneous_billing');
        }

        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                $status = $this->Admindb->miscellaneous_billing_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/miscellaneous_billing?edit=' . $form_data['id']);
            }
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->miscellaneous_billing_by_id($id);
            $this->view('v2/admin/miscellaneous_billing/edit', $data);
        } else {
            $this->view('v2/admin/miscellaneous_billing/list', $data);
        }
    }

    public function report() {


        $XML = (array) simplexml_load_file(ROOT_PATH . "/assets/uploads/xml/" . $_GET['file_name']);

        $XML = json_decode(json_encode($XML), true);

        $data['patient'] = array(
            'Name' => $XML['DICOMInfo']['Patient']['Name'],
            'DisplayName' => $XML['DICOMInfo']['Patient']['DisplayName'],
            'ID' => $XML['DICOMInfo']['Patient']['ID'],
            'age' => $XML['DICOMInfo']['Patient']['age'],
            'Sex' => $XML['DICOMInfo']['Patient']['Sex'],
            'DOB' => $XML['DICOMInfo']['Patient']['Birth'],
            'accession' => $XML['DICOMInfo']['GeneralStudy']['AccessionNo'],
            'StudyDate' => $XML['DICOMInfo']['GeneralStudy']['StudyDate'],
        );

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->view('admin/reports/report', $data);
    }

    public function all_reports() {

        $data['all_reports'] = $this->Admindb->table_full('Reports');
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->view('admin/reports/all_report', $data);
    }

    public function upload_report() {

        if (isset($_POST["submit"])) {

            $fileName = $_FILES["uploadxml"]["name"]; // The file name
            $fileTmpLoc = $_FILES["uploadxml"]["tmp_name"]; // File in the PHP tmp folder
            $fileType = $_FILES["uploadxml"]["type"]; // The type of file it is
            $fileSize = $_FILES["uploadxml"]["size"]; // File size in bytes
            $fileErrorMsg = $_FILES["uploadxml"]["error"]; // 0 for false... and 1 for true



            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowed = array('xml');

            if (!in_array($fileExt, $allowed)) {

                $status['type'] = 'danger';
                $status['msg'] = "Error : Unsupported File Type";
                $this->add_alert($status['type'], $status['msg']);
            } else {

                if (!$fileTmpLoc) { // if file not chosen
                    $status['type'] = 'danger';
                    $status['msg'] = "Error : file not chosen";
                    $this->add_alert($status['type'], $status['msg']);
                }

                $date = new DateTime();
                $fileName = $date->getTimestamp() . mt_rand(1000, 9999);

                $new_file_name = $fileName . '.' . $fileExt;

                $uploadDirectory = ROOT_PATH . '/assets/uploads/xml/' . $fileName . '.' . $fileExt;

                if (move_uploaded_file($fileTmpLoc, "$uploadDirectory")) {

                    $status = $this->Report->upload_file_register($new_file_name);

                    $this->add_alert($status['type'], $status['msg']);
                } else {

                    $status['type'] = 'danger';
                    $status['msg'] = "Error : File Upload Error";
                    $this->add_alert($status['type'], $status['msg']);
                }
            }
        }

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->view('admin/reports/upload', $data);
    }

    public function carry_calculation() {

        // run this function on 1st day of every month

        $data = array();
        $date = date("Y-m");

        //$date = "2019-01";



        $effectiveDate = strtotime("-1 months", strtotime($date));

        $datePrev = date("Y-m", $effectiveDate);

        $datePrevs = strtotime("-1 months", strtotime($datePrev));

        $before_prev_date = date("Y-m", $datePrevs);

        $allworksheets = $this->Admindb->get_all_worksheets(); //Users Fetch

        foreach ($allworksheets as $key => $allworksht) {

            $wsheet = $this->get_calc_worksheet($allworksht['customer'], $datePrev);

            $new_time_id = $this->Admindb->get_time_id_by_date($datePrev, $allworksht['customer']);

            $time_id = $new_time_id[0]['time_id'];

            if (isset($wsheet) && !empty($wsheet['worksheets_details'])) {



                foreach ($wsheet['worksheets_details'] as $key => $worksheets_det) {


                    $sub_count = $this->Admindb->only_count_subscription($allworksht['customer'], $worksheets_det['ans_id'], $time_id);

                    $pre_carry = $this->Admindb->count_previous_carry($allworksht['customer'], $worksheets_det['ans_id'], $before_prev_date);
                    if ($sub_count) {

                        $carry = $sub_count['count'] + $pre_carry['count'] - $worksheets_det['qty'];
                        if ($carry <= 0) {

                            $count[] = 0;
                        } else {

                            $count[] = $carry;
                        }
                        $analysis[] = $sub_count['analysis'];
                        $customer[] = $allworksht['customer'];
                    }
                }
            }
        }


        $this->debug("analysis list");
        $this->debug($analysis);
        $this->debug("customer list");
        $this->debug($customer);
        $this->debug("analysis count");
        $this->debug($count);

        foreach ($analysis as $key => $ans_id) {

            $this->Admindb->carry_add($ans_id, $customer[$key], $count[$key], $datePrev);
        }

        $all_carry = $this->Admindb->carry_select($datePrev, $time_id);

        $this->debug($all_carry);

        $this->debug("from carry forward");

        $this->Admindb->trunct_table();

        foreach ($all_carry as $key => $all_cry) {

            $this->Admindb->carry_add_backup($all_cry['analysis'], $all_cry['customer'], $all_cry['count'], $datePrev);
        }

        //selecting unused items from subscriptions

        $all_carry_backup = $this->Admindb->carry_select_from($datePrev, $time_id);

        $this->debug("un-used items list");
        $this->debug($all_carry_backup);

        foreach ($all_carry_backup as $key => $all_carrys) {


            $prev_carry = $this->Admindb->count_previous_carry($all_carrys['customer'], $all_carrys['analysis'], $before_prev_date);

            $this->debug($prev_carry['count']);

            $this->Admindb->carry_add_2($all_carrys['analysis'], $all_carrys['customer'], $all_carrys['count'], $datePrev, $prev_carry['count']);
        }








        $this->debug("success");
    }

    public function save_invoice() {





        $invoice = array(
            'customer_id' => $_POST['customer_id'],
            'date' => $_POST['start_date'],
            'total_before_dicount' => $_POST['total_before_dicount'],
            'discount_percnt' => $_POST['discount_percnt'],
            'discount' => $_POST['discount'],
            'total_after_dicount' => $_POST['total_after_dicount'],
            'subs_amount' => $_POST['subs_amount'],
            'maint_fees' => $_POST['maint_fees'],
            'maint_fees_type' => $_POST['maint_fees_type'],
            'grand_total' => $_POST['grand_total'],
            //invoice_details
            'ans_id' => json_decode($_POST['ans_id']),
            'ans_name' => json_decode($_POST['ans_name']),
            'total_subscribed' => json_decode($_POST['total_subscribed']),
            'used' => json_decode($_POST['used']),
            'balance_carry' => json_decode($_POST['balance_carry']),
            'extra_used' => json_decode($_POST['extra_used']),
            'rate' => json_decode($_POST['rate']),
            'total' => json_decode($_POST['total']),
            //additional
            'ad_ans_id' => json_decode($_POST['ad_ans_id']),
            'ad_ans_name' => json_decode($_POST['ad_ans_name']),
            'ad_ans_rate' => json_decode($_POST['ad_ans_rate']),
            'ad_ans_qty' => json_decode($_POST['ad_ans_qty']),
            'ad_ans_total' => json_decode($_POST['ad_ans_total'])
        );

        $this->Admindb->save_invoice($invoice);
    }

    public function study_time_report() {
        // $data['customer'] = $this->Admindb->get_all_customer();
        $data['customer'] = $this->Admindb->get_all_customer_new();
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->view('admin/study_time_report/study_time_report', $data);
    }

    public function study_time_graph() {

        // $data['customer'] = $this->Admindb->get_all_customer();
        $data['customer'] = $this->Admindb->get_all_customer_new();
        $data['user'] = $this->user;
        $data['analyst_total_hours'] = $this->Admindb->sum_hours('analyst_hours');
        $data['img_specialist_hours'] = $this->Admindb->sum_hours('image_specialist_hours');
        $data['medi_director_hours'] = $this->Admindb->sum_hours('medical_director_hours');
        $this->admin_sidebar_v2($data);
        $this->view('admin/study_time_graph/study_time_graph', $data);
    }

    public function study_price_report() {
        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $form_data = $_POST;

        $data['sitelist'] = $this->Admindb->table_full('users', 'WHERE user_type_ids = 5');

        if (!empty($form_data)) {
            $data['site'] = $ids = $form_data['site'];
            $effectiveDate = strtotime("-1 months", strtotime($form_data['start_date']));
            $date = date("Y-m", $effectiveDate);
            $data['pre_date'] = $date;
            $data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids, $date);
            if ($data['carry_frwd']['data']) {
                $ans_key = array_column($data['carry_frwd']['data'], 'analysis');
                $ans = array_column($data['carry_frwd']['data'], 'count');
                $data['carry_frwd']['ans_ids'] = $ans_key;
                $data['carry_frwd']['ans_count'] = array_combine($ans_key, $ans);
            }
            $wsheet = $this->get_calc_worksheet($ids, $form_data['start_date']);
            if (empty($wsheet['time_id'])) {
                $new_time_id = $this->Admindb->get_time_id_by_date($form_data['start_date'], $ids);
                $data['new_time_id'] = $new_time_id[0]['time_id'];
            }
            $data['wsheet'] = $wsheet['worksheets_details'];
            $data['time_id'] = $wsheet['time_id'];
            $data['start_date'] = $form_data['start_date'];
            $data['site'] = $form_data['site'];
        }
        $this->view('admin/study_price_report/study_price_report_view', $data);
    }



    public function stat_report() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);
        $this->view('v2/admin/dicom/stat_report', $data);
        }

    
    public function edit_tat() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

       
        if (isset($_GET['sid'])) {
            $id = $_GET['sid'];
           $data['sid'] = $id;
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/dicom/edit_tat', $data);
        } 
    }
}
