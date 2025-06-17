<?php

class Analyst extends Controller {

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

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 3) {
            $userdata = $_SESSION['user'];
            // $this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index() {
        $this->redirect('analyst_dashboard');
    }

    public function analyst_dicom_details_all() {
        $data['user'] = $this->user;
        $this->view('v2/layout/side_menu/analyst_new_menu', $data);
        $data['asignee'] = $this->Admindb->get_all_analyst();
        $data['analysis_statuses'] = $this->Admindb->get_all_analysis_statuses();
        $this->view('v2/analyst/dicom/all', $data);
    }
}
