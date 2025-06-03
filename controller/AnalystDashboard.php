<?php

class AnalystDashboard extends Controller
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
        $this->view('v2/analyst/analyst_dashboard', $data);
    }
}
