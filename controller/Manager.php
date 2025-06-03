<?php
class Manager extends Controller{
	public $Logindb;
	public $Admindb;
	public $user;
	function __construct()
	{
		//$this->connection = parent::loader()->database();
		$this->Logindb = $this->model('logindb');
		$this->Admindb = $this->model('admindb');


		if(isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 2){
			$this->user = $this->Admindb->user_obj( $_SESSION['user']->email );
		}else{ 
			//die('Access forbidden');
			$this->add_alert('danger','Access forbidden');
			$this->redirect('');
		}
	}
	public function index(){
		/*$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$this->view('user/admin',$data);*/
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$data['analyst_worksheet'] = $this->Admindb->collect_wsheet();

		$data['customer_count'] = $this->Admindb->count_table("users","WHERE user_type_ids = 5");	
		$data['analyst_count'] = $this->Admindb->count_table("users","WHERE user_type_ids = 3");


		$data['jobs_count'] =  $this->Admindb->count_table("Clario");
		$data['jobs_assigned'] =  $this->Admindb->count_table("worksheets");
		$data['jobs_Completed'] =  $this->Admindb->count_table("worksheets", "WHERE status = 'Completed' " );
		$data['jobs_Under_review'] =  $this->Admindb->count_table("worksheets", "WHERE status = 'Under review' ");
		$data['jobs_In_progress'] =  $this->Admindb->count_table("worksheets","WHERE status = 'In progress'");

		$data['jobs_Completed_per'] = ($data['jobs_Completed']/$data['jobs_assigned'])*100;
		$data['jobs_Under_review_per'] = ($data['jobs_Under_review']/$data['jobs_assigned'])*100;
		$data['jobs_In_progress_per'] = ($data['jobs_In_progress']/$data['jobs_assigned'])*100;
		$data['analyst_amount_in_month'] = $this->Admindb->analyst_amount_per_month();	
		//$data['wsheet_static'] = $this->Admindb->wsheet_static();	
		$data['total_analyst_amount_per_month'] = $this->wsheet_month_rate();	
		//$this->debug($data['total_analyst_amount_per_month']);
		//$data['total_analyst_amount_per_month'] = $this->analyst_total_amount($data['analyst_amount_in_month']);//error
		$data['analyst_total_hours'] = $this->Admindb->sum_hours('analyst_hours');
		$data['img_specialist_hours'] = $this->Admindb->sum_hours('image_specialist_hours');
		$data['medi_director_hours'] = $this->Admindb->sum_hours('medical_director_hours');
		$this->view('dashboard/index',$data);

		//print_r($data['analyst_amount_per_month']); die;

	}


		public function dicom_details(){

		$data['user'] = $this->user;
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			//$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/manager/dicom_details');
			$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario' ,' WHERE assignee = 0');
			$this->view('dashboard/dicom_details_list',$data);

		}elseif($this->user->group_id == 2){

			$this->admin_sidebar($data);
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			if(isset($_GET['delete']) && $_GET['delete'] != ""){
				$id = $_GET['delete'];
				$status = $this->Admindb->delete('Clario',$id);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('manager/dicom_details');

			}
			$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario' ,' WHERE assignee = 0');

			
			if(isset($_GET['edit']) && $_GET['edit'] != ""){
				$id = $_GET['edit'];
				$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
				$this->view('dashboard/dicom/edit',$data);

			}else{
				$this->view('dashboard/dicom/list',$data);
			}

		}
		else{$this->redirect('/manager');}
	}



	public function dicom_details_assigned(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_GET['page'])){
			$page_now = $_GET['page'];
		}
		else{
			$page_now = 1;
		}
		$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list();

		
		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
			$data['edit_wsheet'] = $this->Admindb->whseet_by_cid($id);
			//print_r($data['edit_wsheet']);
			$this->view('dashboard/dicom/edit_assigne',$data);

		}else{
			$this->view('dashboard/dicom/list_assign',$data);
		}
	}










	public function profile(){
		$data['user'] = $data['edit'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;


			$form_data['group_id'] = $data['edit']->group_id;
			$form_data['active'] = $data['edit']->active;
			$form_data['updated'] =  date("Y-m-d H:i:s");
			if($form_data['password'] =="") 
				$form_data['password'] = $data['edit']->password;
			else 
				$form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);

			$form_data['profile_picture'] = $data['edit']->profile_picture;
			$upload = $this->imageUpload($_FILES['profile_picture'],'/assets/uploads/user/');
			if($upload != false){
				$form_data['profile_picture'] = $_SESSION['user']->profile_picture = $upload;
			}

			unset($form_data['submit']);
			if(!empty($this->empty_key_value($form_data))){
				$error = $this->empty_key_value($form_data);
				$error_label = '';
				foreach ($error as $key => $value) {
					$error_label = $error_label . ucfirst($this->underscore_remove($value)).',';
				}

				$this->add_alert('danger','Validation Error in '.$error_label);

				$this->redirect('dashboard/profile');
			}else{

				$status = $this->Admindb->user_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/profile');				
			}

			
		}
			$this->view('user/profile',$data);

	}













	


}

