<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Customerloginmodel extends CI_Model{
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
/*----------------------- LOGIN PROCESS  ---------------------------------------
        @CREATE DATE                 :  03-07-2019  
------------------------------------------------------------------------------*/
    public function loginprocess($data){
        $sql    =   $this->db->get_where('adm_admin_customer_login',$data);
        if(($sql->num_rows()>0)){
            $loginData      =   $sql->row_object();
/*------------ Admin Session Value Get ---------------------------------------*/            
            if($loginData->ACL_Status==1){
                $this->session->set_userdata('customerId',$loginData->ACL_ID_PK);
                $this->session->set_userdata('customerName',$loginData->ACL_Fisrt_Name.' '.$loginData->ACL_Last_Name); 
                $this->session->set_userdata('customerRole',$loginData->ACL_Customer_Type_FK);
                $this->session->set_userdata('masterCustomerID',$loginData->ACL_Master_FK);
                return 1;
            }
/*------------ Admin Block Redirect ------------------------------------------*/
            else{
                redirect('');
            }
        }
        else{
            redirect('login/loginview/er'); 
        }
    }
}
