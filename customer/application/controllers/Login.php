<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Login extends CI_Controller{
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
        (!empty($this->customerId))?redirect('dashboard'):'';
        $this->load->model('login/customerloginmodel');
        $this->load->model('user/usermanagemodel');
    }
/******************************** RC ******************************************/
/*----------------------- Customer Login Process   -----------------------------
        @CREATE DATE                 :  03-07-2019
        @ACCESS MODIFIERS            :  PUBLIC FUNCTION    
------------------------------------------------------------------------------*/
    public function index(){
        $data['ACL_Email']      =   $this->input->post('txtemail',TRUE);
        $data['ACL_Password']   =   md5($this->input->post('txtPassword',TRUE));
        if((!empty($data['ACL_Email']))&&(!empty($data['ACL_Password']))){
            $value      =   $this->customerloginmodel->loginprocess($data);
            if($value==1){
                redirect('dashboard');
            }
        }
        else{
            redirect('login/loginview/er');
        }
    }
/******************************** RC ******************************************/
/*----------------------- Customer Login Page View  ----------------------------
        @CREATE DATE                 :  03-07-2019
        @ACCESS MODIFIERS            :  PUBLIC FUNCTION    
------------------------------------------------------------------------------*/
    public function loginview(){
        $data['value']      =   $this->uri->segment(3);
        $this->load->view('admin/login/loginview',$data);
    }

}
