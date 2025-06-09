<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Bills extends MY_Controller{
	private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
    private $masterCustomerID  =   "";/************** Pia User ID **************/
    /*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    -------------------------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();

		$this->customerId       =   $this->session->userdata('customerId');
        $this->customerName     =   $this->session->userdata('customerName');
        $this->customerRole     =   $this->session->userdata('customerRole');
        $this->masterCustomerID =   $this->session->userdata('masterCustomerID');

        $this->load->model('customer/billsmodel', 'dbbills');
	}
	/*----------------------- CUSTOMER ALL BILLS DETAILS -----------------------------
	@CREATE DATE                 :  26-08-2019	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/
	public function index() {
		$menu['user']       =   $this->logindata();
		$data['dicom_docs']	=	$this->dbbills->getAllBills();
		$this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/bills/list',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/bills/scripts/list');
	}
}