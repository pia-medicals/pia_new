<?php
/**
 * Admin Controller assigns customers to an analyst.
 */
class Adminassign extends Controller {
	public $user;
	public $Admindb;
	public $Assigndb;
	/*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/ 
	public function __construct() {
		parent::__construct();
		//------------------------------------------------------------------------------
		#		Loading Models
		//------------------------------------------------------------------------------
		$this->Admindb 		= 	$this->model('admindb');
		$this->Assigndb 	= 	$this->model('adminassignmodel');
		//------------------------------------------------------------------------------
		#		Session Value Checking Login Details 
		//------------------------------------------------------------------------------
		if (isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1) {
            $this->user 	= 	$this->Admindb->user_obj($_SESSION['user']->email);
        } 
		else {
            $this->add_alert('danger', 'Access forbidden');
            $this->redirect('');
        }
	}
	/*----------------------- ASSIGNED LIST ---------------------------------
		@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
		@FUNCTION DATE               :  08-08-2019  
	------------------------------------------------------------------------------*/
	public function index() {

		$data['user'] 		= 	$this->user;
        $this->admin_sidebar($data);
        //$data['assigned_list'] = $this->Assigndb->getAllAssignedCustommers();  
        $this->view('admin/adminassign/list',$data);
        
		
	}
	/*----------------------- ASSIGN CUSTOMER ---------------------------------
		@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
		@FUNCTION DATE               :  08-08-2019  
	------------------------------------------------------------------------------*/
	public function assign() {
		$data['user'] 		= 	$this->user;
        $this->admin_sidebar($data);

        if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        	$form_data = $_POST;
        	unset($form_data['submit']);
        	if (!empty($this->empty_key_value($form_data))) {
                $this->add_alert('danger', 'Validation Error!');
            } else {
            	$insArr = array();
            	foreach ($form_data['customers'] as $key => $value) {
            		$insArr[] = array(
            			'analyst_id'	=>	$form_data['analyst'],
            			'customer_id'	=>	$value,
            			'add_user_by'   =>  $this->user->id,
            			'user_add_on'   =>	date("Y-m-d H:i:s"),
            			'updated_user'	=>  $this->user->id,
            			'user_upd_on'	=>	date("Y-m-d H:i:s"),
            			'status'		=> 	'1',
            		);
            	}
            	$status = $this->Assigndb->assignCustomersToAnalyst($insArr);
            	$this->add_alert($status['type'], $status['msg']);
            	$this->redirect('adminassign');
            }           
        }


       // $data['customers'] = $this->Admindb->table_full('users', ' WHERE user_type_ids = 5');
       // $data['analysts']  = $this->Admindb->table_full('users', ' WHERE user_type_ids = 3');
        $data['customers'] = $this->Admindb->table_full_name_asc('users', ' WHERE user_type_ids = 5 AND `active` = 1');
        $data['analysts']  = $this->Admindb->table_full_name_asc('users', ' WHERE user_type_ids = 3 AND `active` = 1');

        $this->view('admin/adminassign/assign',$data);
	}
	/*----------------------- UPDATE CUSTOMER ---------------------------------
		@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
		@FUNCTION DATE               :  12-08-2019  
	------------------------------------------------------------------------------*/
	public function edit() {
		$data['user'] 		= 	$this->user;
        $this->admin_sidebar($data);
        $assign_id 	= 	$_GET['edit'];
        if (isset($_POST['submitedit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    		$form_data = $_POST;
    		unset($form_data['submitedit']);
    		if (!empty($this->empty_key_value($form_data))) {
            	$this->add_alert('danger', 'Validation Error!');
        	} else {
        		$upd_arr = array(
        			'id'			=> $assign_id,
        			'analyst_id' 	=> $form_data['analyst'],
        			'updated_user'	=>  $this->user->id,
        			'user_upd_on'	=>	date("Y-m-d H:i:s"),
        		);

        		$upd_status = $this->Assigndb->updateAnalystToCustomers($upd_arr);
        		$this->add_alert($upd_status['type'], $upd_status['msg']);
        		$this->redirect('adminassign');
        	}
        }

//        $data['analysts']  = $this->Admindb->table_full('users', ' WHERE user_type_ids = 3');
       // $data['analysts']  = $this->Admindb->table_full_name_desc('users', ' WHERE user_type_ids = 3');
        $data['analysts']  = $this->Admindb->table_full_name_asc('users', ' WHERE user_type_ids = 3');

        $data['assigned_user_info'] = $this->Assigndb->getAssigneduserById($assign_id);
        $this->view('admin/adminassign/edit',$data);
	}


	/*public function ajaxGetAssignedCustomerInfo() {
		$data['data'] = $this->Assigndb->getAllAssignedCustommers();
		echo json_encode($data);
		exit;
	}*/


}