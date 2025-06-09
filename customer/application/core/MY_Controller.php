<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller{
    private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
    private $masterCustomerID  =   "";/************** Pia User ID **************/
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
        $this->masterCustomerID =   $this->session->userdata('masterCustomerID');
        (empty($this->customerId))?redirect('login/loginview'):'';
    }
/******************************** RC ******************************************/
/*----------------------- MENU PASSING DATA ------------------------------------
	@CREATE DATE                 :  03-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function logindata(){
        $profileImg =   $this->configmodel->userimaege();
        $login      =   array(
                            'loginId'       =>  $this->customerId,
                            'loginName'     =>  $this->customerName,
                            'loginType'     =>  $this->customerRole,
                            'userImage'     =>  $profileImg
                        );
        return $login;
    }
}
