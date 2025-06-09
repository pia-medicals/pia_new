<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Contactmodel extends CI_Model{
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
/*----------------------- GET USER IMAGES  -------------------------------------
        @CREATE DATE                 :  21-07-2019  
------------------------------------------------------------------------------*/
    public function getContactlist(){
        $this->db->select('ACL_ID_PK,ACL_Fisrt_Name,ACL_Last_Name,ACL_Phone_Number,ACL_Email,ACL_Customer_Image_Thumb,ACL_About_Customer,ACL_Gender,ACL_Customer_Type_FK,ACL_User_Add_On,ACL_Status,AD_Role_Name');
        $this->db->from('adm_admin_customer_login');
        $this->db->join('adm_admin_role','AR_ID_PK=ACL_Customer_Type_FK');
        $this->db->where('ACL_Add_User_By',$this->customerId);
        $sql    =   $this->db->get();
        return $sql->result_array();
    }
}
