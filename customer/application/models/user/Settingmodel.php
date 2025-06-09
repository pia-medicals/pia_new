<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Settingmodel extends CI_Model{
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
/*----------------------- GET CUSTOMER INFO ------------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function getinfo(){
        $this->db->select('ACL_Fisrt_Name,ACL_Last_Name,ACL_Phone_Number,ACL_Email,ACL_About_Customer,ACL_Gender,ACL_Customer_Image_Thumb');
        $this->db->from('adm_admin_customer_login');
        $this->db->where('ACL_ID_PK',$this->customerId);
        $sql    =   $this->db->get();
        return $sql->row_array();
    }
/******************************** RC ******************************************/
/*----------------------- OLD PASSWORD CHECK  ----------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function passwordcheck($data){
        $condition['ACL_ID_PK']      =   $this->customerId; 
        $this->db->select('ACL_ID_PK');
        $this->db->from('adm_admin_customer_login');
        $this->db->where($condition);
        $this->db->where($data);
        $sql    =   $this->db->get();
        return $sql->num_rows();
    }
/******************************** RC ******************************************/
/*----------------------- PASSWORD RESET  --------------------------------------
	@CREATE DATE                 :  19-07-2019 
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function passwordreset($data){
        $condition['ACL_ID_PK']      =   $this->customerId;
        $sql                         =   $this->db->update('adm_admin_customer_login',$data,$condition);
        return $sql;
    }
/******************************** RC ******************************************/
/*----------------------- INFO UPDATION ----------------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function updateinfo($data){
        $condition['ACL_ID_PK']      =   $this->customerId;
        $sql                         =   $this->db->update('adm_admin_customer_login',$data,$condition);
        return $sql;
    }
}
