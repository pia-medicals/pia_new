<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Billsmodel extends CI_Model{
	private $customerId        =   "";/************** Login ID   **************/
    private $customerName      =   "";/************** Login Name **************/
    private $customerRole      =   "";/************** Login Role **************/
    private $masterCustomerID  =   "";/************** Pia User ID **************/

    /*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	------------------------------------------------------------------------------*/
	public function __construct() {
        parent::__construct();
        $this->customerId       =   $this->session->userdata('customerId');
        $this->customerName     =   $this->session->userdata('customerName');
        $this->customerRole     =   $this->session->userdata('customerRole');
        $this->masterCustomerID =   $this->session->userdata('masterCustomerID');
    }
    /*----------------------- GET ALL BILLS BY CUST  --------------------------------
        @CREATE DATE                 :  26-08-2019 
        $RETURN                      :  ARRAY 
    ------------------------------------------------------------------------------*/
    public function getAllBills() {
    	$data = array();
        if (!empty($this->masterCustomerID)) {
            $data   =   $this->db->select('ACB_ID_PK, ACB_Customer_ID_FK, ACB_Bills_Title, ACB_Bills_Desc, ACB_Bills_Invoice_No, ACB_Bills_Due, ACB_Bills_Month, ACB_Bills_Year, ACB_Bills_Total, ACB_Bills_Discount, ACB_Bills_Invoice_Amount, ACB_Bills_Path, ACB_Add_User_By, ACB_User_Add_On, ACB_Updated_User_By, ACB_User_Updated_On, ACB_Status')
                        ->from('adm_admin_customer_bills')
                        ->where('ACB_Customer_ID_FK', $this->masterCustomerID)
                        ->get()->result_array();
        }

        return $data;
    }

    
}