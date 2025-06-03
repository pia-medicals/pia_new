<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Contact extends MY_Controller{
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
        $this->load->model('user/contactmodel');
    }
/******************************** RC ******************************************/
/*----------------------- CUSTOMER CONTACTS ------------------------------------
	@CREATE DATE                 :  21-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function index(){
        $menu['user']       =   $this->logindata();
        $data['list']       =   $this->contactmodel->getContactlist();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/user/contact/list',$data);
        $this->load->view('admin/tpl/footer-tpl');
    }
}
