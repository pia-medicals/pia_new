<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Profilemodel extends CI_Model{
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
/*----------------------- GET CUSTOMER TIME ID  --------------------------------
        @CREATE DATE                 :  22-07-2019  
------------------------------------------------------------------------------*/
    public function gettimeid($id){
        $this->db->select_max('time_id');
        $this->db->from('Timeline');
        $this->db->where('customer_id',$id);
        $sql    =   $this->db->get()->row_array();
        return $sql['time_id'];
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER PROFILE ID  -----------------------------
        @CREATE DATE                 :  22-07-2019  
------------------------------------------------------------------------------*/
    public function getprofileid(){
/*------------------------------------------------------------------------------
-------- Get Data From Customer Type [Contracts Manager]    
------------------------------------------------------------------------------*/
        if($this->customerRole!=1){
            $this->db->select('ACL_Add_User_By');
            $this->db->from('adm_admin_customer_login');
            $this->db->where('ACL_ID_PK', $this->customerId);
            $masterId    =   $this->db->get()->row_array();
            $this->db->select('ACL_Master_FK');
            $this->db->from('adm_admin_customer_login');
            $this->db->where('ACL_ID_PK', $masterId['ACL_Add_User_By']);
            $sql    =   $this->db->get()->row_array();
            return $sql['ACL_Master_FK'];
        }
/*------------------------------------------------------------------------------
-------- Get Data From Customer Type [Customer Admin]  
------------------------------------------------------------------------------*/
        else{
            $this->db->select('ACL_Master_FK');
            $this->db->from('adm_admin_customer_login');
            $this->db->where('ACL_ID_PK', $this->customerId);
            $sql    =   $this->db->get()->row_array();
            return $sql['ACL_Master_FK'];
        }    
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER ANALYSES RATE  --------------------------
        @CREATE DATE                 :  22-07-2019  
------------------------------------------------------------------------------*/
    public function getAnalyeseRate(){
        $customerid     =   $this->getprofileid();
        $timeId         =   $this->gettimeid($customerid);
        $this->db->select('*');
        $this->db->from('analyses_rates');
        $this->db->where('customer',$customerid);
        $this->db->where('time_id',$timeId);
        return $this->db->get()->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER SUBSCRIPITION  --------------------------
        @CREATE DATE                 :  23-07-2019  
------------------------------------------------------------------------------*/
    public function getSubscription(){
        $customerid     =   $this->getprofileid();
        $timeId         =   $this->gettimeid($customerid);
        $this->db->select('subscriptions.id,subscriptions.count,analyses.name');
        $this->db->from('subscriptions');
        $this->db->join('analyses','subscriptions.analysis=analyses.id');
        $this->db->where('subscriptions.customer',$customerid);
        $this->db->where('subscriptions.time_id',$timeId);
        return $this->db->get()->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER DISCOUNT PRICING  -----------------------
        @CREATE DATE                 :  23-07-2019  
------------------------------------------------------------------------------*/
    public function getDisicountPrice(){
        $customerid     =   $this->getprofileid();
        $timeId         =   $this->gettimeid($customerid);
        $this->db->select('id,minimum_value,maximum_value,percentage');
        $this->db->from('discount_range');
        $this->db->where('customer',$customerid);
        $this->db->where('time_id',$timeId);
        return $this->db->get()->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER MAINTENANCE FEES  -----------------------
        @CREATE DATE                 :  23-07-2019  
------------------------------------------------------------------------------*/
    public function getMaintenanceFee(){
        $customerid     =   $this->getprofileid();
        $timeId         =   $this->gettimeid($customerid);
        $this->db->select('id,maintenance_fee_type,maintenance_fee_amount');
        $this->db->from('maintenance');
        $this->db->where('customer',$customerid);
        $this->db->where('time_id',$timeId);
        return $this->db->get()->result_array();
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER MAINTENANCE FEES  -----------------------
        @CREATE DATE                 :  24-07-2019  
------------------------------------------------------------------------------*/
    public function getCustomerData(){
        $customerId     =   $this->getprofileid();
        $this->db->select('CA.ACL_ID_PK,CA.ACL_Fisrt_Name,CA.ACL_Last_Name,CA.ACL_Phone_Number,CA.ACL_Email,CA.ACL_Customer_Image_Thumb,CA.ACL_About_Customer,CA.ACL_Gender,CA.ACL_User_Add_On,CA.ACL_Status,PU.user_meta');
        $this->db->from('adm_admin_customer_login AS CA');
        $this->db->join('Users AS PU','CA.ACL_Master_FK=PU.id');
        $this->db->where('ACL_Master_FK',$customerId);
        return $this->db->get()->row_array();
    }
/******************************** RC ******************************************/
/*----------------------- GET CUSTOMER TOTAL STUDIES  -----------------------
        @CREATE DATE                 :  28-08-2019  
------------------------------------------------------------------------------*/
    public function getTotalStudies(){
        $customerId     =   $this->getprofileid();
        $this->db->select('Clario.id, Clario.created, Clario.accession, Clario.patient_name, Clario.mrn, Clario.tat, Clario.webhook_customer,  users.name, Clario.webhook_description,Clario.status,Clario.review_user_id')->from('Clario');
        $this->db->join('users', 'Clario.assignee = users.id', 'left');
        if (!empty($customerId)) {
            $this->db->where('Clario.customer', $customerId);
        }
        $this->db->order_by('Clario.created', 'DESC');
        $data   =   $this->db->get();
        return $rowcount    =   $data->num_rows();
    }
}
