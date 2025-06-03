<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Profile extends MY_Controller{
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
        $this->load->model('customer/profilemodel');
    }
/******************************** RC ******************************************/
/*----------------------- CUSTOMER PROFILE DETAILS -----------------------------
	@CREATE DATE                 :  24-07-2019	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function index(){
        $menu['user']           =   $this->logindata();
        $data['customer']       =   $this->profilemodel->getCustomerData();
        $data['analyeseRate']   =   $this->profilemodel->getAnalyeseRate();
        $data['subscription']   =   $this->profilemodel->getSubscription();
        $data['discountPrice']  =   $this->profilemodel->getDisicountPrice();
        $data['maintenanceFee'] =   $this->profilemodel->getMaintenanceFee();
        $data['studies']        =   $this->profilemodel->getTotalStudies();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/customer/profile',$data);
        $this->load->view('admin/tpl/footer-tpl');
    }
}
