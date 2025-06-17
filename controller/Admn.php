<?php

class Admn extends Controller {

    public $Logindb;
    public $Admindb;
    public $user;
    public $Report;
    public $dbmodel;

    function __construct() {
        $this->Logindb = $this->model('logindbV2');
        $this->Admindb = $this->model('admindb');
        $this->Report = $this->model('report');
        $this->dbmodel = $this->model('dashboardmodel'); //RC 

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $userdata = $_SESSION['user'];
            //$this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index() {
        if (isset($_POST['btnSave'])) {
            $data['user'] = $this->user;

            /* $this->admin_sidebar($data);

              $data['analyst_worksheet'] = $this->Admindb->collect_wsheet();

              $data['jobs_assigned'] = $this->Admindb->count_table_by_date_new("clario", $_POST['from'], $_POST['to']);
              $data['jobs_Under_review'] = $this->Admindb->count_table_jobUnderReviewNew("clario", $_POST['from'], $_POST['to']);
              $data['jobs_In_progress'] = $this->Admindb->count_table_jobs_In_progressNew("clario", $_POST['from'], $_POST['to']);
              $data['jobs_Completed'] = $this->Admindb->count_table_jobs_CompletedNew("clario", $_POST['from'], $_POST['to']);
              $data['jobs_cancelled'] = $this->Admindb->count_table_jobs_cancelledNew("clario", $_POST['from'], $_POST['to']);
              $data['jobs_on_hold'] = $this->Admindb->count_table_jobs_on_holdNew("clario", $_POST['from'], $_POST['to']);
              $data['jobs_count'] = $this->Admindb->count_table_by_date_clario_new("clario", $_POST['from'], $_POST['to']);
              $data['customer_count'] = $this->Admindb->count_tableNew("users", "WHERE user_type_ids = 5");
              $data['analyst_count'] = $this->Admindb->count_tableNew("users", "WHERE user_type_ids = 3");

              $analyst_hours = $this->Admindb->count_analyst_hours();
              $data['analyst_hours'] = round($analyst_hours);
              $data['jobs_Not_assigned'] = $this->dbmodel->getcountbydateNew("Clario", $_POST['from'], $_POST['to']);
              $data['jobs_last_study'] = $this->dbmodel->getlaststudy();
              $data['jobs_last_user'] = $this->dbmodel->getnewuser();
              $data['jobs_count_full'] = $this->Admindb->count_table("Clario");
              $data['jobs_assigned_full'] = $this->Admindb->count_table("worksheets");
              $data['jobs_Completed_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'Completed' ");
              $data['jobs_Under_review_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'Under review' ");
              $data['jobs_In_progress_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'In progress'");
              $data['jobs_Not_assigned_full'] = $this->dbmodel->getcount("Clario", "WHERE assignee = 0");

              $data['jobs_Completed_per'] = ($data['jobs_Completed_full'] / $data['jobs_assigned_full']) * 100;
              $data['jobs_Under_review_per'] = ($data['jobs_Under_review_full'] / $data['jobs_assigned_full']) * 100;
              $data['jobs_In_progress_per'] = ($data['jobs_In_progress_full'] / $data['jobs_assigned_full']) * 100;
              $data['analyst_amount_in_month'] = $this->Admindb->analyst_amount_per_month();

              $data['total_analyst_amount_per_month'] = $this->wsheet_month_rate();
              $data['Week_case_count'] = $this->Admindb->weekly_case_count();

              $data['checkdone'] 		= $this->Admindb->getsecondcheckcountdone($_POST['from'], $_POST['to']);
              $data['checknotdone'] 	= $this->Admindb->getsecondcheckcountnotdone($_POST['from'], $_POST['to']);

              //daily workcount
              $data['daily_work_count'] = $this->Admindb->daily_work_count();
              $data['daily_completed_work_count'] = $this->Admindb->daily_completed_work_count();
              $data['cases_by_analysesTypes'] = $this->Admindb->cases_by_analyses_types();
              $data['result_from'] = isset($_POST['from'])?$_POST['from']:'';
              $data['result_to'] = isset($_POST['to'])?$_POST['to']:''; */
//print_r($data);
            $this->view('dashboard/index', $data);
        } else {

            $data['result_from'] = '';
            $data['result_to'] = '';
            $from = date('Y-m-01 00:00:00');
            $to = date('Y-m-d 23:59:59');
            $data['user'] = $this->user;
            $this->admin_sidebar($data);
            //$data['analyst_worksheet'] = $this->Admindb->collect_wsheet();            

            $data['customer_count'] = $this->Admindb->count_usertableNew("users", "WHERE user_type_ids = 5");
            $data['analyst_count'] = $this->Admindb->count_usertableNew("users", "WHERE user_type_ids = 3");

            // $analyst_hours = $this->Admindb->count_analyst_hours();
            // $data['analyst_hours'] = round($analyst_hours);
            // $data['jobs_count_full'] = $this->Admindb->count_table("Clario");
            // $data['jobs_count'] = $this->Admindb->count_table_by_date_clario_new("Clario", $from, $to);
            // $data['jobs_assigned_full'] = $this->Admindb->count_table("worksheets");
            // $data['jobs_assigned'] = $this->Admindb->count_table_by_date_new("Clario", $from, $to);
            // $data['jobs_Completed_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'Completed' ");
            // $data['jobs_Completed'] = $this->Admindb->count_table_jobs_CompletedNew("Clario", $from, $to);
            // $data['jobs_Under_review_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'Under review' ");
            // $data['jobs_Under_review'] = $this->Admindb->count_table_jobUnderReviewNew("Clario", $from, $to);
            // $data['jobs_cancelled'] = $this->Admindb->count_table_jobs_cancelledNew("Clario", $from, $to);
            // $data['jobs_on_hold'] = $this->Admindb->count_table_jobs_on_holdNew("Clario", $from, $to);
            // $data['jobs_In_progress_full'] = $this->Admindb->count_table("worksheets", "WHERE status = 'In progress'");
            // $data['jobs_In_progress'] = $this->Admindb->count_table_jobs_In_progressNew("Clario", $from, $to);
            $data['jobs_last_user'] = $this->dbmodel->getnewuser();
            //$data['jobs_last_study'] = $this->dbmodel->getlaststudy();
            /*             * ****************************** RC ***************************************** */
            /* ----------------------- GET NOT ASSIGNED ------------------------------------
              @FUNCTION DATE              :  28-12-2018
              ------------------------------------------------------------------------------ */
            /*    $data['jobs_Not_assigned_full'] = $this->dbmodel->getcount("Clario", "WHERE assignee = 0");
              // $data['jobs_Not_assigned'] = $this->dbmodel->getcountbydate("Clario", $from, $to);
              $data['jobs_Not_assigned'] = $this->dbmodel->getcountbydateNew("Clario", $from, $to);

              $data['jobs_last_study'] = $this->dbmodel->getlaststudy();
              $data['jobs_last_user'] = $this->dbmodel->getnewuser();

              $data['checkdone'] 		= $this->Admindb->getsecondcheckcountdone($from, $to);
              $data['checknotdone'] 	= $this->Admindb->getsecondcheckcountnotdone($from, $to);

              $data['jobs_Completed_per'] = ($data['jobs_Completed_full'] / $data['jobs_assigned_full']) * 100;
              $data['jobs_Under_review_per'] = ($data['jobs_Under_review_full'] / $data['jobs_assigned_full']) * 100;
              $data['jobs_In_progress_per'] = ($data['jobs_In_progress_full'] / $data['jobs_assigned_full']) * 100;
              $data['analyst_amount_in_month'] = $this->Admindb->analyst_amount_per_month();

              $data['total_analyst_amount_per_month'] = $this->wsheet_month_rate();
              $data['Week_case_count'] = $this->Admindb->weekly_case_count();

              //daily workcount
              $data['daily_work_count'] = $this->Admindb->daily_work_count();
              $data['daily_completed_work_count'] = $this->Admindb->daily_completed_work_count();
              $data['cases_by_analysesTypes'] = $this->Admindb->cases_by_analyses_types();
              //print_r($data);
              /* ----------------------------------------------------------------------------
              @FUNCTION DATE              :  17-01-2019
              ------------------------------------------------------------------------------ */

            // Actual time vs. expected time
            /*   $data['actual_vs_expected'] = $this->dbmodel->getATvsEAT();

              //  Actual time vs. Expected time for Different Analyst
              $data['analyst_actual_vs_expected'] = $this->dbmodel->getWorstAnalystChart(); */

            $this->view('dashboard/admn', $data);
        }
        die('Access forbidden');
    }

    public function user() {

        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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

        //$data['user_list'] = $this->Admindb->users($key, $page_now);
        $data['user_list']['results'] = $this->Admindb->table_full('users');

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('users', $id, 'user_id');
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/user');
        }



        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            $form_data['updated'] = date("Y-m-d H:i:s");
            $data['edit'] = $this->Admindb->user_by_id($form_data['id']);
            if ($form_data['password'] == "")
                $form_data['password'] = $data['edit']['password'];
            else
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
            if (isset($form_data['active'])) {
                $form_data['active'] = 1;
            } else {
                $form_data['active'] = 0;
            }
            $form_data['profile_picture'] = '';
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

                $this->redirect('admin/user?edit=' . $form_data['id']);
            } else {

                $status = $this->Admindb->user_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/user?edit=' . $form_data['id']);
            }
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->user_by_id($id);
            $this->view('admin/user/edit', $data);
        } else {
            $this->view('admin/user/list', $data);
        }
    }

    public function add_user() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);
            $email_check = $this->Admindb->check_email($form_data['email']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } elseif (!empty($email_check)) {
                $this->add_alert('danger', 'Email Already Exists!');
            } else {
                $form_data['created'] = $form_data['updated'] = date("Y-m-d H:i:s");
                $form_data['active'] = 1;
                $form_data['id'] = '';
                $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                //print_r($this->Admindb);
                $status = $this->Admindb->add_user($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/add_user');
        }
        $this->view('admin/user/add', $data);
    }

    public function add_customer() {

        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
                $form_data['password'] = md5($form_data['password']); //password_hash($form_data['password'], PASSWORD_DEFAULT);

                $status = $this->Admindb->add_user($form_data);

                $result = $this->Admindb->add_usertime_line($status['customer_id']);

                $this->add_alert($result['type'], $result['msg']);
            }

            $this->redirect('admin/add_customer');
        }
        $this->view('admin/customer/add', $data);
    }

    public function dicom_details() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
            $this->view('admin/dicom/all', $data);
        } else {
            $this->view('admin/dicom/all', $data);
        }
    }

    public function dicom_details_assigned() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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




        $data['user_list']['results'] = $this->Admindb->table_full('users', ' WHERE user_type_ids = 5');

        if (isset($_POST['submit'])) {

            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->user_by_id($_GET['edit']);

            $form_data = $_POST;

            if (isset($_FILES['logo']) && !empty($_FILES['logo'])) {
                $logo = $this->imageUpload($_FILES['logo'], "/assets/uploads/user/");
            }
            if (!$logo)
                $logo = $data['edit']['profile_picture'];

            $this->Admindb->user_pic_update(array('id' => $id, 'profile_picture' => $logo));

            $tat = $form_data['tat'];
            if (!empty($tat)) {
                $this->Admindb->user_tat_update(array('id' => $id, 'tat' => $tat));
            }

            $stringAddress1 = str_replace('\'', '', $form_data['address']);
            $fromAddress = str_replace("\"", '', $stringAddress1);
            $details = json_decode($data['edit']['user_meta']);
            $details->customer_code = $form_data['customer_code'];
            $details->phone = $form_data['phone'];
            $details->address = trim(preg_replace('/\'\s\s+/', ' ', $fromAddress));

            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $error = $this->add_alert('danger', 'Validation Error!');
                print_r($error);
            } else {
                $details = json_encode($details);

                $status = $this->Admindb->user_update_meta($id, $details);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/customer?edit=' . $form_data['id']);
            }
        }

        if (isset($_POST['dis_submit'])) {
            $id = $_GET['edit'];
            $form_data = $_POST;
            unset($form_data['dis_submit']);

            extract($form_data);

            if (!empty($this->empty_key_value($form_data))) {

                $this->add_alert('danger', 'Please Add Discount Data!');
            } else {





                $customer_id = $form_data['customer'];
                // get old timeline id
                $time_line = $this->Admindb->get_user_timeline($customer_id);

                $time_id = $time_line[0]['time_id'];

                // get all analysis details from that id

                $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                $all_subscrptionFees = $this->Admindb->fetch_all_subscrptionfees($time_id);

                // creating new time id

                $this->Admindb->update_user_timeline($time_id);

                //getting newly created id

                $stat = $this->Admindb->add_usertime_line($customer_id);

                $time_id = $stat['time_id'];

                // insert all details with new time id


                foreach ($all_analysis as $key => $all_analys) {

                    $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                            $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                }


                foreach ($all_subscrptions as $key => $all_subscri) {



                    $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                            $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                }





                foreach ($all_maintenance as $key => $all_mainten) {



                    $status = $this->Admindb->maintenance_insert_with_new_time_id(
                            $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                }

                foreach ($all_subscrptionFees as $key => $all_subfess) {

                    $status = $this->Admindb->subscrptionFees_insert_with_new_time_id(
                            $all_subfess['customer'], $all_subfess['subscription_fees'], $time_id);
                }





                $user = $this->Admindb->discounted_user($customer);

                if ($user) {
                    //DELETING OLD SUBS
                    $status = $this->Admindb->delete_user_discounts($customer);
                }


                foreach ($minimum_value as $key => $minimum_values) {

                    $status = $this->Admindb->discount_pricing_add($customer, $minimum_values, $maximum_value[$key], $percentage[$key]);
                }




                /* } */


                $this->add_alert($status['type'], $status['msg']);
            }
        }

        if (isset($_POST['submit_maint_fees'])) {
            $id = $_GET['edit'];
            $form_data = $_POST;

            unset($form_data['submit_maint_fees']);

            if (!empty($this->empty_key_value($form_data))) {

                $this->add_alert('danger', 'Validation Error!');
            } else {




                $customer_id = $id;
                // get old timeline id
                $time_line = $this->Admindb->get_user_timeline($customer_id);

                $time_id = $time_line[0]['time_id'];

                // get all analysis details from that id

                $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                $all_subscrptionFees = $this->Admindb->fetch_all_subscrptionfees($time_id);

                // creating new time id

                $this->Admindb->update_user_timeline($time_id);

                //getting newly created id

                $stat = $this->Admindb->add_usertime_line($customer_id);

                $time_id = $stat['time_id'];

                // insert all details with new time id


                foreach ($all_analysis as $key => $all_analys) {

                    $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                            $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                }

                foreach ($all_subscrptions as $key => $all_subscri) {



                    $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                            $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                }

                foreach ($all_discount_range as $key => $all_discount) {



                    $status = $this->Admindb->discount_range_insert_with_new_time_id(
                            $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                }

                foreach ($all_subscrptionFees as $key => $all_subfess) {

                    $status = $this->Admindb->subscrptionFees_insert_with_new_time_id(
                            $all_subfess['customer'], $all_subfess['subscription_fees'], $time_id);
                }



                $status = $this->Admindb->maintenance_fees_add($form_data, $id);

                $this->add_alert($status['type'], 'Maintenance fee added sucessfully');
            }
        }



        if (isset($_POST['submit_add_rate'])) {

            $id = $_GET['edit'];

            $form_data = $_POST;

            extract($form_data);

            unset($form_data['submit_add_rate']);

            if (!empty($this->empty_key_value($form_data))) {

                $this->add_alert('danger', 'Validation Error!');
            } else {





                $time_line = $this->Admindb->get_user_timeline($customer);

                $time_id = $time_line[0]['time_id'];

                // get all analysis details from that id

                $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                $all_subscrptionFees = $this->Admindb->fetch_all_subscrptionfees($time_id);

                $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                // creating new time id

                $this->Admindb->update_user_timeline($time_id);

                //getting newly created id

                $stat = $this->Admindb->add_usertime_line($customer);

                $time_id = $stat['time_id'];

                // insert all details with new time id

                foreach ($all_subscrptions as $key => $all_subscri) {



                    $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                            $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                }

                foreach ($all_discount_range as $key => $all_discount) {



                    $status = $this->Admindb->discount_range_insert_with_new_time_id(
                            $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                }

                foreach ($all_subscrptionFees as $key => $all_subfess) {

                    $status = $this->Admindb->subscrptionFees_insert_with_new_time_id(
                            $all_subfess['customer'], $all_subfess['subscription_fees'], $time_id);
                }


                foreach ($all_maintenance as $key => $all_mainten) {



                    $status = $this->Admindb->maintenance_insert_with_new_time_id(
                            $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                }




                $user = $this->Admindb->analyses_rate_user($customer);

                if ($user) { //DELETING OLD RATES
                    $status = $this->Admindb->delete_analyses_rate($customer);
                }
                //INSERT NEW RATES
                foreach ($analysis_id as $key => $analysis_ids) {
                    $status = $this->Admindb->analyses_rate_add($analysis_ids, $customer, $rate[$key], $code[$key], $custom_description[$key], $min_time[$key]);
                }
                //} // FOR EDITING

                $this->add_alert($status['type'], $status['msg']);
            }
        }


        if (isset($_POST['submit_subscription'])) {

            $id = $_GET['edit'];
            $form_data = $_POST;
            $form_datas = $form_data;
            $total = $form_data['total'];
            unset($form_datas['submit_subscription']);
            unset($form_datas['customer']);
            unset($form_datas['cid']);
            unset($form_datas['total']);

            $sub_analysis_arr = $form_datas;
            unset($sub_analysis_arr['sub_count']);
            $sub_analysis_arr = $sub_analysis_arr['sub_analysis_id'];

            $sub_count_arr = $form_datas;
            unset($sub_count_arr['sub_analysis_id']);
            $sub_count_arr = $sub_count_arr['sub_count'];

            //print_r($form_data); die("form data");	

            $time_line = $this->Admindb->get_user_timeline($id);
            $time_id = $time_line[0]['time_id'];
            $total_subscription_amount = 0;

            //find total subscription
            foreach ($sub_analysis_arr as $key => $value) {
                $curr_rate = $this->Admindb->get_latest_analysis_rates($id, $time_id, $sub_analysis_arr[$key]);

                $total_subscription_amount = $total_subscription_amount + ($curr_rate * $sub_count_arr[$key]);
            }


            if (empty($form_data['sub_count'])) {

                $this->add_alert('danger', 'Validation Error!');
            } else {
                $customer_id = $form_data['customer'];
                // get old timeline id
                $time_line = $this->Admindb->get_user_timeline($customer_id);

                $time_id = $time_line[0]['time_id'];

                // get all analysis details from that id

                $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                $all_subscrptionFees = $this->Admindb->fetch_all_subscrptionfees($time_id);

                $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                // creating new time id

                $this->Admindb->update_user_timeline($time_id);

                //getting newly created id

                $stat = $this->Admindb->add_usertime_line($customer_id);

                $time_id = $stat['time_id'];

                // insert all details with new time id


                foreach ($all_analysis as $key => $all_analys) {


                    $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                            $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                }



                foreach ($all_discount_range as $key => $all_discount) {



                    $status = $this->Admindb->discount_range_insert_with_new_time_id(
                            $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                }

                foreach ($all_maintenance as $key => $all_mainten) {



                    $status = $this->Admindb->maintenance_insert_with_new_time_id(
                            $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                }

                foreach ($all_subscrptionFees as $key => $all_subfess) {

                    $status = $this->Admindb->subscrptionFees_insert_with_new_time_id(
                            $all_subfess['customer'], $all_subfess['subscription_fees'], $time_id);
                }

                $user = $this->Admindb->subscibed_user($customer);

                if ($user) { //DELETING OLD SUBS
                    $status = $this->Admindb->delete_user_subscription($customer);
                }

                //INSERT NEW RATES
                foreach ($form_data['sub_analysis_id'] as $key => $sub_analysis_ids) {

                    $status = $this->Admindb->subscription_add($sub_analysis_ids, $form_data['customer'], $form_data['sub_count'][$key]);
                }

                if (empty($total)) {
                    $time_line = $this->Admindb->get_user_timeline($id);
                    $time_id = $time_line[0]['time_id'];
                    $this->Admindb->insert_subscription_fees($id, $time_id, $total_subscription_amount);
                }
                /* } */

                $this->add_alert($status['type'], $status['msg']);
            }
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];

            if (isset($_GET['delete_price']) && $_GET['delete_price'] != "") {
                $id_del = $_GET['delete_price'];
                $status = $this->Admindb->delete('analyses_rates', $id_del);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/customer?edit=' . $id . '#price');
            }

            if (isset($_GET['delete_subscription']) && $_GET['delete_subscription'] != "") {
                $id_del = $_GET['delete_subscription'];
                $status = $this->Admindb->delete('subscriptions', $id_del);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/customer?edit=' . $id . '#subscription');
            }

            if (isset($_GET['delete_disc']) && $_GET['delete_disc'] != "") {
                $id_del = $_GET['delete_disc'];
                $status = $this->Admindb->delete('
				discount_range', $id_del);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/customer?edit=' . $id . '#discount');
            }

            if (isset($_POST['update_subscription'])) {
                $form_data = $_POST;
                unset($form_data['update_subscription']);

                if (!empty($this->empty_key_value($form_data))) {
                    $this->add_alert('danger', 'Validation Error!');
                } else {


                    $customer_id = $form_data['customer'];
                    // get old timeline id
                    $time_line = $this->Admindb->get_user_timeline($customer_id);

                    $time_id = $time_line[0]['time_id'];

                    // get all analysis details from that id

                    $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                    $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                    $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                    $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                    // creating new time id

                    $this->Admindb->update_user_timeline($time_id);

                    //getting newly created id

                    $stat = $this->Admindb->add_usertime_line($customer_id);

                    $time_id = $stat['time_id'];

                    // insert all details with new time id


                    foreach ($all_analysis as $key => $all_analys) {

                        $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                                $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                    }

                    foreach ($all_subscrptions as $key => $all_subscri) {



                        $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                                $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                    }

                    foreach ($all_discount_range as $key => $all_discount) {



                        $status = $this->Admindb->discount_range_insert_with_new_time_id(
                                $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                    }

                    foreach ($all_maintenance as $key => $all_mainten) {



                        $status = $this->Admindb->maintenance_insert_with_new_time_id(
                                $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                    }



                    $status = $this->Admindb->subscription_update($form_data, $time_id);

                    $this->add_alert($status['type'], $status['msg']);
                }
            }

            if (isset($_POST['update_price'])) {

                $form_data = $_POST;
                unset($form_data['update_price']);

                if (!empty($this->empty_key_value($form_data))) {
                    $this->add_alert('danger', 'Validation Error!');
                } else {

                    $customer_id = $form_data['customer'];
                    // get old timeline id
                    $time_line = $this->Admindb->get_user_timeline($customer_id);

                    $time_id = $time_line[0]['time_id'];

                    // get all analysis details from that id

                    $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                    $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                    $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                    $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                    // creating new time id

                    $this->Admindb->update_user_timeline($time_id);

                    //getting newly created id

                    $stat = $this->Admindb->add_usertime_line($customer_id);

                    $time_id = $stat['time_id'];

                    // insert all details with new time id


                    foreach ($all_analysis as $key => $all_analys) {

                        $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                                $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                    }

                    foreach ($all_subscrptions as $key => $all_subscri) {



                        $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                                $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                    }

                    foreach ($all_discount_range as $key => $all_discount) {



                        $status = $this->Admindb->discount_range_insert_with_new_time_id(
                                $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                    }

                    foreach ($all_maintenance as $key => $all_mainten) {



                        $status = $this->Admindb->maintenance_insert_with_new_time_id(
                                $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                    }




                    $status = $this->Admindb->analyses_rate_update($form_data, $time_id);

                    $this->add_alert($status['type'], $status['msg']);
                }
            }


            if (isset($_POST['update_disc'])) {
                $form_data = $_POST;
                unset($form_data['update_disc']);

                if (!empty($this->empty_key_value($form_data))) {
                    $this->add_alert('danger', 'Validation Error!');
                } else {


                    $customer_id = $form_data['customer'];
                    // get old timeline id
                    $time_line = $this->Admindb->get_user_timeline($customer_id);

                    $time_id = $time_line[0]['time_id'];

                    // get all analysis details from that id

                    $all_analysis = $this->Admindb->fetch_all_analysis($time_id);

                    $all_subscrptions = $this->Admindb->fetch_all_subscrptions($time_id);

                    $all_discount_range = $this->Admindb->fetch_all_discount_range($time_id);

                    $all_maintenance = $this->Admindb->fetch_all_maintenance($time_id);

                    // creating new time id

                    $this->Admindb->update_user_timeline($time_id);

                    //getting newly created id

                    $stat = $this->Admindb->add_usertime_line($customer_id);

                    $time_id = $stat['time_id'];

                    // insert all details with new time id


                    foreach ($all_analysis as $key => $all_analys) {

                        $status = $this->Admindb->analyses_rate_insert_with_new_time_id(
                                $all_analys['analysis'], $all_analys['customer'], $all_analys['rate'], $all_analys['code'], $all_analys['analysis_description'], $all_analys['custom_description'], $time_id, $all_analys['min_time']);
                    }

                    foreach ($all_subscrptions as $key => $all_subscri) {



                        $status = $this->Admindb->subscription_rate_insert_with_new_time_id(
                                $all_subscri['month'], $all_subscri['analysis'], $all_subscri['customer'], $all_subscri['count'], $time_id);
                    }

                    foreach ($all_discount_range as $key => $all_discount) {



                        $status = $this->Admindb->discount_range_insert_with_new_time_id(
                                $all_discount['customer'], $all_discount['minimum_value'], $all_discount['maximum_value'], $all_discount['percentage'], $time_id);
                    }

                    foreach ($all_maintenance as $key => $all_mainten) {



                        $status = $this->Admindb->maintenance_insert_with_new_time_id(
                                $all_mainten['customer'], $all_mainten['maintenance_fee_type'], $all_mainten['maintenance_fee_amount'], $time_id);
                    }






                    $status = $this->Admindb->discount_range_update($form_data, $time_id);

                    $this->add_alert($status['type'], $status['msg']);
                }
            }




            $data['edit'] = $this->Admindb->user_by_id($id);
            //$sfc = $this->Admindb->salesforce_code_full();
            //$data['sfc'] = $this->Admindb->salesforce_code_full();

            $data['analyses_rate'] = $this->Admindb->analyses_rate_user($id);

            $data['subscription'] = $this->Admindb->subscriptions_user($id);

            $data['discount_pricing_list'] = $this->Admindb->get_discount_range_by_customer($id);

            $data['max_disc'] = $this->Admindb->get_max_discount_by_customer($id);

            $data['subscription_fees'] = $this->Admindb->get_subscription_by_customer($id);

            $data['maintenance'] = $this->Admindb->get_maintenance_by_customer($id);
            //$data['agreements']  = $this->Admindb->getDocumentsByCustomer($id);
            $data['agreements'] = $this->Admindb->getDocumentsByCustomer($id);
            $data['bills'] = $this->Admindb->getBillsByCustomer($id);
            ;

            $this->view('admin/customer/edit', $data);
        } else {
            $this->view('admin/customer/list', $data);
        }
    }

    public function billing_code() {
        $data['user'] = $this->user;
        $data['sfc'] = $this->Admindb->salesforce_code_full();
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;

            unset($form_data['submit']);
            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                if ($data['max_disc'] < $form_data['minimum_value'] && $data['max_disc'] + 1 < $form_data['maximum_value']) {
                    $status = $this->Admindb->discount_pricing_add($form_data);
                    $this->add_alert($status['type'], $status['msg']);
                } else {
                    $status = $this->Admindb->discount_pricing_add($form_data);
                    $this->add_alert('danger', 'Enter Appropriate values');
                }
            }
            $this->redirect('admin/discount_pricing_add');
        }
        $this->view('admin/discount/add', $data);
    }

    public function import() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
        $data['analyst_worksheet'] = $this->Admindb->collect_wsheet();
        $this->view('dashboard/accounts/index', $data);
    }

    public function accounts() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        $form_data = $_POST;
        if (!empty($form_data))
            $data['wsheet'] = $this->Admindb->select_wsheet_date($form_data);

        $this->view('admin/statistics/worksheet', $data);
    }

    public function billing() {

        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        //$this->carry_save();
        $form_data = $_POST;
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
        $this->view('admin/billing/come', $data);
    }

    public function billing_summary_customer() {


        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        //$this->carry_save();
        $form_data = $_POST;
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



        $this->view('admin/billing/billing2', $data);
    }

    public function billing_summary_detailed() {

        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        //$this->carry_save();

        $form_data = $_POST;
        if (!empty($form_data)) {
            if (isset($_POST['btnMRNView'])) {
                $data['viewMrn'] = 1;
            } else {
                $data['viewMrn'] = 0;
            }
            $data['site'] = $ids = $form_data['site'];
            $data['wsheet'] = $this->Admindb->billing_summary_detailed($ids, $form_data['start_date']);
        }

        $this->view('admin/billing/billing3', $data);
    }

    public function billing_summary_analyst() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        //
        $form_data = $_POST;
        if (!empty($form_data)) {
            $data['site'] = $ids = $form_data['site'];
            if (!empty($form_data['site'])) {
                $data['wsheet'] = $this->Admindb->billing_summary_analyst($ids, $form_data['start_date']);
            } else {
                $data['wsheet'] = $this->Admindb->billing_summary_analyst_all($form_data['start_date']);
            }
        } else {
            $data['site'] = $ids = '';
            $data['wsheet'] = $this->Admindb->billing_summary_analyst_all();
        }
        $this->view('admin/billing/billing4', $data);
    }

    public function profile() {
        $data['user'] = $data['edit'] = $this->user;
        $this->admin_sidebar($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;

            $form_data['group_id'] = $data['edit']->group_id;
            $form_data['active'] = $data['edit']->active;
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
        $this->admin_sidebar($data);

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


        $data['salesforce_code_list']['results'] = $this->Admindb->table_full('analyses');
        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('analyses', $id, 'analysis_id');
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/analyses');
        }

        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
                $status = $this->Admindb->analyses_update($form_data);
                $this->add_alert($status['type'], $status['msg']);
                $this->redirect('admin/analyses?edit=' . $form_data['id']);
            }
            ;
        }

        if (isset($_GET['edit']) && $_GET['edit'] != "") {
            $id = $_GET['edit'];
            $data['edit'] = $this->Admindb->analyses_by_id($id);
            $this->view('admin/analyses/edit', $data);
        } else {
            $this->view('admin/analyses/list', $data);
        }
    }

    public function analyses_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data)) && is_array($salesforce)) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->analyses_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/analyses_add');
        }
        $this->view('admin/analyses/add', $data);
    }

    public function analyses_rate() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
        if (isset($_GET['page'])) {
            $page_now = $_GET['page'];
        } else {
            $page_now = 1;
        }
        $data['analyses_category_list'] = $this->Admindb->analyses_category($page_now, SITE_URL . '/admin/analyses_category');

        if (isset($_GET['delete']) && $_GET['delete'] != "") {
            $id = $_GET['delete'];
            $status = $this->Admindb->delete('analyses_category', $id, 'category_id');
            $this->add_alert($status['type'], $status['msg']);
            $this->redirect('admin/analyses_category');
        }



        if (isset($_POST['submit'])) {
            $form_data = $_POST;
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
            $this->view('admin/analyses_category/edit', $data);
        } else {
            $this->view('admin/analyses_category/list', $data);
        }
    }

    public function analyses_category_add() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        if (isset($_POST['submit'])) {
            $form_data = $_POST;
            unset($form_data['submit']);

            if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {

                $status = $this->Admindb->analyses_category_add($form_data);
                $this->add_alert($status['type'], $status['msg']);
            }

            $this->redirect('admin/analyses_category_add');
        }
        $this->view('admin/analyses_category/add', $data);
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
        $this->admin_sidebar($data);
        $this->carry_save();
    }

    public function add_miscellaneous_billing() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->view('admin/miscellaneous_billing/add', $data);
    }

    public function miscellaneous_billing() {


        $data['user'] = $this->user;
        $this->admin_sidebar($data);

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
            $data['edit'] = $this->Admindb->get_by_id('miscellaneous_billing', $id);
            $this->view('admin/miscellaneous_billing/edit', $data);
        } else {
            $this->view('admin/miscellaneous_billing/list', $data);
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
        $this->admin_sidebar($data);
        $this->view('admin/reports/report', $data);
    }

    public function all_reports() {

        $data['all_reports'] = $this->Admindb->table_full('Reports');
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
        $this->admin_sidebar($data);
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
        $data['customer'] = $this->Admindb->get_all_customer();
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        $this->view('admin/study_time_report/study_time_report', $data);
    }

    public function study_time_graph() {

        $data['customer'] = $this->Admindb->get_all_customer();
        $data['user'] = $this->user;
        $data['analyst_total_hours'] = $this->Admindb->sum_hours('analyst_hours');
        $data['img_specialist_hours'] = $this->Admindb->sum_hours('image_specialist_hours');
        $data['medi_director_hours'] = $this->Admindb->sum_hours('medical_director_hours');
        $this->admin_sidebar($data);
        $this->view('admin/study_time_graph/study_time_graph', $data);
    }

    public function study_price_report() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
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
}
