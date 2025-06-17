<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExtraController extends Controller
{

    public $Logindb;
    public $Admindb;
    public $user;
    // public $Report;
    // public $dbmodel;
    public $Tatdb;

    function __construct()
    {
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Tatdb = $this->model('tatdb');
        // $this->Report = $this->model('report');
        // $this->dbmodel = $this->model('dashboardmodel'); //RC 

        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $userdata = $_SESSION['user'];
            // $this->check_force_pasword_reset($userdata);
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    public function index()
    {
        // $data = [];
        // $data['user'] = $this->user;
        // $data['page_title'] = 'Turnaround Time';
        // $this->admin_sidebar_v2($data);
        // $this->view('v2/admin/turnaround_time/list', $data);
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

     public function viewdata() {

        $data['user'] = $this->user;
        $this->admin_sidebar_v2($data);

       
        if (isset($_GET['sid'])) {
            $id = $_GET['sid'];
           $data['sid'] = $id;
            $this->admin_sidebar_v2($data);
            $this->view('v2/admin/dicom/view_data', $data);
        } 
    }
  
}
