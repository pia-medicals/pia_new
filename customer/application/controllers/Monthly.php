<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Monthly extends MY_Controller{
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
        $this->load->model('monthlymodel','mdb');
    }
/******************************** RC ******************************************/
/*----------------------- ADMIN REPORT PAGE ------------------------------------
	@CREATE DATE                 :  28-08-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function report(){
        $menu['user']       =   $this->logindata();
        $data['user']       =   $this->mdb->userdata();
        
        $data['list']       =   $this->mdb->getreport();
        $data['listNew']       =   $this->mdb->newfunction();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/monthly',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/monthlyscript'); 
    }
}
