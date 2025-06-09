<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Documentsmodel extends CI_Model{
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
    /*----------------------- GET ALL DOCUMENTS BY CUST  --------------------------------
        @CREATE DATE                 :  24-08-2019 
        $RETURN                      :  ARRAY 
    ------------------------------------------------------------------------------*/
    public function getAllDocuments() {
    	$data = array();
        if (!empty($this->masterCustomerID)) {
            $data   =   $this->db->select('ACD_ID_PK, ACD_Customer_ID_FK, ACD_Docs_Path, ACD_Docs_Title, ACD_Docs_Desc, ACD_Add_User_By, ACD_User_Add_On, ACD_Updated_User_By, ACD_User_Updated_On, ACD_Status')
                        ->from('adm_admin_customer_docs')
                        ->where('ACD_Customer_ID_FK', $this->masterCustomerID)
                        ->get()->result_array();
        }

        return $data;
    }

    
}