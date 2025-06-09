<?php if ( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Configmodel extends CI_Model{
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
        @CREATE DATE                 :  03-07-2019  
------------------------------------------------------------------------------*/
    public function userimaege(){
        $this->db->select('ACL_Customer_Image_Thumb,ACL_Gender');
        $this->db->from('adm_admin_customer_login');
        $this->db->where('ACL_ID_PK',$this->customerId);
        $sql    =   $this->db->get();
        $image  =   $sql->row_array();
        if(!empty($image['ACL_Customer_Image_Thumb'])){
            $profileImage   =   base_url('static/upload/user/thumb/'.$image['ACL_Customer_Image_Thumb']);
        }
        else{
           $profileImage   =   ($image['ACL_Gender']=='male')?base_url('static/admin/default/img_avatar.png'):base_url('static/admin/default/img_avatar2.png'); 
        }
        return $profileImage;
    }
}
