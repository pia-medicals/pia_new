<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Manageuser extends MY_Controller{
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
        $this->load->model('user/usermanagemodel');
    }
/******************************** RC ******************************************/
/*----------------------- USER INSERT PROCESS ----------------------------------
	@CREATE DATE                 :  10-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function index(){
        $data['ACL_Fisrt_Name']         =   $this->input->post('txtName',TRUE);
        $data['ACL_Email']              =   $this->input->post('txtEmail',TRUE);
        $data['ACL_Password']           =   md5($this->input->post('txtPassword',TRUE));
        $data['ACL_Gender']             =   $this->input->post('txtGenter',TRUE);
        $data['ACL_Customer_Type_FK']   =   $this->input->post('txtAdminType',TRUE);
        if((!empty($data['ACL_Fisrt_Name']))&&(!empty($data['ACL_Email']))&&(!empty($data['ACL_Password']))&&(!empty($data['ACL_Gender']))&&(!empty($data['ACL_Customer_Type_FK']))){
            $value  =   $this->usermanagemodel->adduser($data);
            ($value==1)?redirect('user/manageuser/addnew/s'):redirect('user/manageuser/addnew/r');
        }
        else{
            //var_dump($data);
            redirect('user/manageuser/addnew/e');
        } 
    }
/******************************** RC ******************************************/
/*----------------------- ADD NEW USER -----------------------------------------
	@CREATE DATE                 :  04-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function addnew(){
        $menu['user']       =   $this->logindata();
        $data['status']     =   $this->uri->segment(4);
        $data['adminrole']  =   $this->usermanagemodel->getadminrole();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/user/usermanage/addnew',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/user/usermanage/script/addnew');
    }
/******************************** RC ******************************************/
/*----------------------- USER LIST --------------------------------------------
	@CREATE DATE                 :  11-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function userlist(){
        $menu['user']       =   $this->logindata();
        $data['list']       =   $this->usermanagemodel->getuserlist();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/user/usermanage/list',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/user/usermanage/script/user-list');
    }
/******************************** RC ******************************************/
/*----------------------- CHECK E-MAIL ADDRESS ---------------------------------
	@CREATE DATE                 :  11-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function checkuser(){
        $data['ACL_Email']  =   $this->input->post('email',TRUE);
        echo $value         =   $this->usermanagemodel->checkemail($data);
    }
/******************************** RC ******************************************/
/*----------------------- ADMIN USER STATUS ------------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function changestatus(){
        $data['ACL_Status']          =   ($this->input->post('status',TRUE)==1)?5:1;
        $condition['ACL_ID_PK']      =   $this->input->post('userid',TRUE);
        $value                       =   $this->usermanagemodel->changestatus($data,$condition);
    }
/******************************** RC ******************************************/
/*----------------------- DELETE USER DETAILS ----------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function delete(){
        $condition['ACL_ID_PK']      =   $this->input->post('userid',TRUE);
        $this->usermodel->usermanagemodel($condition);
    }
    
    
}
