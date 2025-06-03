<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Usermanagemodel extends CI_Model{
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
/*----------------------- GET ADMIN ROLE  --------------------------------------
        @CREATE DATE                 :  10-07-2019  
------------------------------------------------------------------------------*/
    public function getadminrole(){
        $this->db->select('AD_Role_Name,AR_ID_PK');
        $this->db->from('adm_admin_role');
        $this->db->where('AD_Role_Status',1);
        $this->db->where('AR_ID_PK!=',1);
        $sql    =   $this->db->get();
        return $sql->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- ADD NEW USER  ----------------------------------------
        @CREATE DATE                 :  11-07-2019  
------------------------------------------------------------------------------*/
    public function adduser($data){
        $data['ACL_Add_User_By']    =   $this->customerId;
        $data['ACL_User_Add_On']    =   date('Y-m-d');
        $data['ACL_Status']         =   1;
        $sql                        =   $this->db->insert('adm_admin_customer_login',$data);
        return $sql;
    }
/******************************** RC ******************************************/
/*----------------------- CHECK EMAIL ADDRESS  ---------------------------------
        @CREATE DATE                 :  11-07-2019  
------------------------------------------------------------------------------*/
    public function checkemail($data){
        $sql    =   $this->db->get_where('adm_admin_customer_login',$data);
        return $sql->num_rows();
    }
/******************************** RC ******************************************/
/*----------------------- USER DATA LIST  --------------------------------------
        @CREATE DATE                 :  17-07-2019  
------------------------------------------------------------------------------*/
    public function getuserlist(){
        $this->db->select('ACL_ID_PK,ACL_Fisrt_Name,ACL_Last_Name,ACL_Phone_Number,ACL_Email,ACL_Gender,ACL_Customer_Type_FK,ACL_Status,AD_Role_Name');
        $this->db->from('adm_admin_customer_login');
        $this->db->join('adm_admin_role','AR_ID_PK=ACL_Customer_Type_FK');
        $sql    =   $this->db->get();
        return $sql->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- CHANGE USER STATUS  ----------------------------------
        @CREATE DATE                 :  19-07-2019
        @ACCESS MODIFIERS            :  PUBLIC FUNCTION    
------------------------------------------------------------------------------*/
    public function changestatus($data,$condition){
        $data['ACL_User_Update_On']  =   date('Y-m-d');
        $data['ACL_Update_User_By']  =   $this->medAdminId;
        $sql                         =   $this->db->update('adm_admin_customer_login',$data,$condition);
        return $sql;
    }
/******************************** RC ******************************************/
/*----------------------- DELETE USER DETAILS ----------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function deletedetails($condition){
        $this->db->delete('adm_admin_customer_login',$condition);
        return TRUE;
    }
    
}
