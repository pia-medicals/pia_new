<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends MY_Controller{
    private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
/******************************** RC ******************************************/
/*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/
    public function __construct() {
        parent::__construct();
        $this->customerId    =   $this->session->userdata('customerId');
        $this->customerName  =   $this->session->userdata('customerName');
        $this->customerRole  =   $this->session->userdata('customerRole');
    }
/******************************** RC ******************************************/
/*----------------------- ADMIN DASHBOARD PAGE ---------------------------------
	@CREATE DATE                 :  03-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function index(){
        $menu['user']       =   $this->logindata();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        //$this->load->view('admin/dashboard');
        $this->load->view('admin/tpl/footer-tpl');
    }
/******************************** RC ******************************************/
/*----------------------- ADMIN LOGOUT PROCESS ---------------------------------
	@CREATE DATE                 :  03-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function logout(){
        $this->session->unset_userdata('customerId');
        $this->session->unset_userdata('customerName');
        $this->session->unset_userdata('customerRole');
        redirect('login/loginview');
    }
}
