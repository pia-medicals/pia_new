<?php

class Analystrate extends Controller {

    public $user;  /*     * ********  User Date Array ************************* */
    public $Logindb; /*     * ********  User Login Date ************************* */
    public $Admindb; /*     * ********  Admin DB Model  ************************* */
    public $Analyst; /*     * ********  Analyst DB Model  *********************** */

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- MAIN CONSTRUCT ---------------------------------------
      @PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      ------------------------------------------------------------------------------ */

    public function __construct() {
        parent::__construct();
        $this->Logindb = $this->model('logindb');
        $this->Admindb = $this->model('admindb');
        $this->Analyst = $this->model('analystratemodel');
//------------------------------------------------------------------------------
#		Session Value Checking Login Details 
//------------------------------------------------------------------------------		
        if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $this->user = $this->Admindb->user_obj($_SESSION['user']->email);
        } else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- ANALYST RATE REPORT ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  23-04-2019
      ------------------------------------------------------------------------------ */

    public function ratereport() {
        $data['user'] = $this->user;
        $this->admin_sidebar($data);
        $statDate = !empty($_POST['txtStart']) ? $_POST['txtStart'] : '';
        $endDate = !empty($_POST['txtEnd']) ? $_POST['txtEnd'] : '';
        if ((!empty($statDate)) && (!empty($endDate))) {
            $data['analyst'] = $this->Analyst->analystreportDate($statDate, $endDate);
        } else {
            $data['analyst'] = $this->Analyst->analystreport();
        }
        $this->view('analyst/ratereport', $data);
    }

    /*     * ****************************** RC ***************************************** */
    /* ----------------------- ANALYST RATE REPORT ---------------------------------
      @ACCESS MODIFIERS            :  PUBLIC FUNCTION
      @FUNCTION DATE               :  23-04-2019
      ------------------------------------------------------------------------------ */

    public function ratedetails() {
        echo 1;
        //echo $reviewid	=	$_POST['reviewid'];
        //echo $rate		=	$_POST['rate'];
        //echo $data		=	$this->Analyst->getratedetails($reviewid,$rate);	
        //echo json_encode($data);
    }

    public function dataupdate() {
        $update = $this->Analyst->getdatavalue();
        //print_r($update);
        foreach ($update as $value) {
            $this->Admindb->empty_wsheet_details($value['id']);
            $rateval = json_decode($value['existing_rate']);
            foreach ($rateval as $key => $jsonval) {
                //echo $key.'-'.$jsonval.'<br/>';
                $worksheet_id = $value['id'];
                $customer_id = $value['customer_id'];
                $date = $value['date'];
                $ans_id = $key;
                $ans_hr = 0;
                $qty = 1;
                $rate = $jsonval;
                echo $this->Analyst->insertdatavalue($worksheet_id, $customer_id, $date, $ans_id, $ans_hr, $qty, $rate);
                //die;
            }
        }
    }

}
