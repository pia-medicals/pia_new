<?php

class ReportV2 extends Controller {

    public $Admindb;
    public $user;
    public $Report;

    function __construct() {
        $this->Admindb = $this->model('admindb');
        $this->Report = $this->model('reportdb');

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $userdata = $_SESSION['user'];
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index() {
        $this->redirect('report/billing_summary_analyst');
    }

    public function billing_summary_analyst() {
        $data = [];
        $data['user'] = $this->user;
        $data['page_title'] = 'Billing Summary - Analyst Detailed';
        $this->admin_sidebar_v2($data);
        $analysts = $this->Report->get_analyst_names();
        $data['analysts'] = $analysts;
        $this->view('v2/admin/billing/billing_summary_analyst', $data);
    }

    public function billing_summary_customer() {
        $data = [];
        $data['user'] = $this->user;
        $data['page_title'] = 'Billing Summary - Customer';
        $this->admin_sidebar_v2($data);
        $customers = $this->Report->get_customer_names();
        $data['customers'] = $customers;
        $this->view('v2/admin/billing/billing_summary_customer', $data);
    }

    public function billing_summary_detailed() {
        $data = [];
        $data['user'] = $this->user;
        $data['page_title'] = 'Billing Summary - Detailed';
        $this->admin_sidebar_v2($data);
        $customers = $this->Report->get_customer_names();
        $data['customers'] = $customers;
        $this->view('v2/admin/billing/billing_summary_detailed', $data);
    }

    public function study_time_report() {
        $data = [];
        $data['user'] = $this->user;
        $data['page_title'] = 'Study Time Report';
        $data['customers'] = $this->Report->get_customer_names();
        $data['analysts'] = $this->Report->get_analyst_names();
        $data['statuses'] = $this->Report->get_status_lists();
        $this->admin_sidebar_v2($data);
        $this->view('v2/admin/report/study_time_report', $data);
    }
    
    
}
