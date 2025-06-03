<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends MY_Controller{
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
        $this->load->model('user/settingmodel');
        $this->load->model('comman/utilitymodel');
    }
/******************************** RC ******************************************/
/*----------------------- INFO UPDATION ----------------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function index(){
        $data['ACL_Fisrt_Name']     =   $this->input->post('firstName',TRUE);
        $data['ACL_Last_Name']      =   $this->input->post('lastName',TRUE);
        $data['ACL_Phone_Number']   =   $this->input->post('mobile',TRUE);
        $data['ACL_Email']          =   $this->input->post('email',TRUE);
        $data['ACL_Gender']         =   $this->input->post('genter',TRUE);
        $data['ACL_About_Customer'] =   $this->input->post('about',TRUE);
        echo $this->settingmodel->updateinfo($data);
    }
/******************************** RC ******************************************/
/*----------------------- MANAGE INFO ------------------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function manageinfo(){
        $menu['user']       =   $this->logindata();
        $data['status']     =   $this->uri->segment(4);
        $data['user']       =   $this->settingmodel->getinfo();
        $this->load->view('admin/tpl/head-tpl');
        $this->load->view('admin/tpl/menu-tpl',$menu);
        $this->load->view('admin/user/setting/manageinfo',$data);
        $this->load->view('admin/tpl/footer-tpl');
        $this->load->view('admin/user/setting/script/manageinfo');
    }
/******************************** RC ******************************************/
/*----------------------- OLD PASSWORD CHECK  ----------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function passwordcheck(){
        $data['ACL_Password']   =   md5($this->input->post('oldPassword',TRUE));
        echo $value             =   $this->settingmodel->passwordcheck($data);
    }
/******************************** RC ******************************************/
/*----------------------- OLD PASSWORD CHECK  ----------------------------------
	@CREATE DATE                 :  19-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function passwordreset(){
        $data['ACL_Password']   =   md5($this->input->post('paswword',TRUE));
        echo $value             =   $this->settingmodel->passwordreset($data);
    }
/******************************** RC ******************************************/
/*----------------------- OLD PASSWORD CHECK  ----------------------------------
	@CREATE DATE                 :  20-07-2019 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
------------------------------------------------------------------------------*/ 	
    public function uploadimage(){
        if(!empty($_FILES['txtImage']['name'])){
            $responce                       =   array();
            $file_name                      =   $_FILES['txtImage']['name'];
            $file_size                      =   $_FILES['txtImage']['size'];
            $file_tmp                       =   $_FILES['txtImage']['tmp_name'];
            $file_type                      =   $_FILES['txtImage']['type'];
            $file_datails                   =   pathinfo($file_name);   
            $extensions                     =   array("jpeg","jpg","png");
            if(in_array($file_datails['extension'],$extensions)=== false){
                $responce['error']  =  "Upload image file";
            }
            if($file_size > 1048576){
                $responce['error']  =  "Upload image less than 1 MB";
            }
            if(empty($responce['error'])){
                move_uploaded_file($file_tmp,$this->config->item('uplod_path').'/user/images/'.$file_name);
                $imageConfig['filename']        =   $_FILES['txtImage']['name']; 
                $imageConfig['source']          =   $this->config->item('uplod_path').'/user/images';   
                $imageConfig['thumb']           =   $this->config->item('uplod_path').'/user/thumb';
                $imageConfig['prefix']          =   '_thumb';
                $imageConfig['width']           =   128;
                $imageConfig['height']          =   128;
                $this->utilitymodel->resizeimage($imageConfig);
                $file_datails                       =   pathinfo($imageConfig['filename']);
                $data['ACL_Customer_Image']         =   $_FILES['txtImage']['name']; 
                $data['ACL_Customer_Image_Thumb']   =   $file_datails['filename'].$imageConfig['prefix'].'.'.$file_datails['extension']; 
                $value                              =   $this->settingmodel->updateinfo($data);
                if($value==1){
                    $responce['thumb']              =   $data['ACL_Customer_Image_Thumb'];
                    $responce['error']              =   1;
                }
                else{
                    $responce['error']  =  "Image not uploded";
                }
            }
        }
        echo json_encode($responce);
    }
    
}
