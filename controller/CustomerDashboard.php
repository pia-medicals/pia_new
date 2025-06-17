<?php

class CustomerDashboard extends Controller
{
    public $dbmodel;
    public $user;
    function __construct()
    {
        $this->dbmodel = $this->model('dashboardmodel');
    }

    public function index()
    {
        $data = [];
        $data['user'] = $this->user;
        // $data['page_title'] = 'Home';
        // $this->admin_sidebar_v2($data);
        $this->view('v2/customer/customer_dashboard', $data);
    }

    public function customerallstudies(){
        $data = [];
        $data['user'] = $this->user;
        //echo "Hi, I'm customerallstudies from CustomerDashboard";
        $this->view('v2/customer/customer_all_details', $data);
    }

    public function customer_stat_report() {
        $data = [];
        $data['user'] = $this->user;
        $this->view('v2/customer/customer_stat_report', $data);
        }

}
