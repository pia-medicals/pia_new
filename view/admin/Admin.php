<?php
/**
 * 
 */
class Admin extends Controller
{
	public $Logindb;
	public $Admindb;
	public $user;
	function __construct()
	{
		//$this->connection = parent::loader()->database();
		$this->Logindb = $this->model('logindb');
		$this->Admindb = $this->model('admindb');


		if(isset($_SESSION['user']) && $_SESSION['user']->user_type_ids == 1){
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
		$data['customer_count'] = $this->Admindb->count_table("Users","WHERE user_type_ids = 5");	
		$data['analyst_count'] = $this->Admindb->count_table("Users","WHERE user_type_ids = 3");

		$data['jobs_count'] =  $this->Admindb->count_table("Clario");
		$data['jobs_assigned'] =  $this->Admindb->count_table('Clario' ,'WHERE assignee != 0 ');
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
	public function user(){
		
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$key = "";
		$page_now = 1;
		
		if(isset($_GET)){
			if(isset($_GET['page']))
			$page_now = $_GET['page'];
			else $page_now = 1;

			if(isset($_GET['key']))
			$key = $_GET['key'];
			else $key = "";


		}

		//$data['user_list'] = $this->Admindb->users($key, $page_now);
		$data['user_list']['results'] = $this->Admindb->table_full('users');

		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('users',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/user');

		}



		if(isset($_POST['submit'])){
			$form_data = $_POST;
			$form_data['updated'] =  date("Y-m-d H:i:s");
			$data['edit'] = $this->Admindb->user_by_id($form_data['id']);
			if($form_data['password'] =="") 
				$form_data['password'] = $data['edit']['password'];
			else 
				$form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
			if(isset($form_data['active'])) {
				$form_data['active'] = 1;
			}
			else { $form_data['active'] = 0; }
			$form_data['profile_picture'] = '';
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

				$this->redirect('admin/user?edit='.$form_data['id']);
			}else{

				$status = $this->Admindb->user_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/user?edit='.$form_data['id']);				
			}

			
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->user_by_id($id);
			$this->view('admin/user/edit',$data);

		}else{
			$this->view('admin/user/list',$data);
		}



		
	}
	public function add_user(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$form_data['created'] = $form_data['updated'] =  date("Y-m-d H:i:s");
				$form_data['active'] = 1;
				$form_data['id'] = '';
				$form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
				//print_r($this->Admindb);
				$status = $this->Admindb->add_user($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/add_user');
		}
		$this->view('admin/user/add',$data);
	}
	public function add_customer(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$form_data['created'] = $form_data['updated'] =  date("Y-m-d H:i:s");
				$form_data['active'] = 1;
				$form_data['group_id'] = 5;
				$form_data['id'] = '';
				$form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
				//print_r($this->Admindb);
				$status = $this->Admindb->add_user($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/add_customer');
		}
		$this->view('admin/customer/add',$data);
	}

	public function dicom_details(){
		$data['user'] = $this->user;
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
			$this->redirect('admin/dicom_details');

		}
		//$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/admin/dicom_details');
		$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario',' WHERE assignee = 0');

		
		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
			$this->view('admin/dicom/edit',$data);

		}else{
			$this->view('admin/dicom/list',$data);
		}
	}
	public function dicom_details_all(){
		$data['user'] = $this->user;
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
			$this->redirect('admin/dicom_details');

		}
		//$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/admin/dicom_details');
		$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario');

		
		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
			$this->view('admin/dicom/edit',$data);

		}else{
			$this->view('admin/dicom/all',$data);
		}
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
		$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_full();

		
		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
			$data['edit_wsheet'] = $this->Admindb->whseet_by_cid($id);
			//print_r($data['edit_wsheet']);
			$this->view('admin/dicom/edit_assigne',$data);

		}else{
			$this->view('admin/dicom/list_assign',$data);
		}
	}


	public function salesforce_code()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_GET['page'])){
			$page_now = $_GET['page'];
		}
		else{
			$page_now = 1;
		}
		$data['salesforce_code_list'] = $this->Admindb->salesforce_code($page_now,SITE_URL.'/admin/salesforce_code');
		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('Salesforce',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/salesforce_code');

		}

		

		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->salesforce_code_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/salesforce_code?edit='.$form_data['id']);				
			}

			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->salesforce_code_by_id($id);
			$this->view('admin/salesforce_code/edit',$data);

		}else{
			$this->view('admin/salesforce_code/list',$data);
		}
		
		

	}

	public function salesforce_code_add()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			$salesforce = $this->Admindb->salesforce_code_by_code($form_data['code']);


			if(is_array($salesforce) ){
				$this->add_alert('danger','Salesforce code exist!');
				$this->redirect('admin/salesforce_code_add');
			}
			if(!empty($this->empty_key_value($form_data)) && is_array($salesforce) ){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->salesforce_code_add($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/salesforce_code_add');
		}
		$this->view('admin/salesforce_code/add',$data);

	}


	public function Customer(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$key = "";
		$page_now = 1;
		
		if(isset($_GET)){
			if(isset($_GET['page']))
			$page_now = $_GET['page'];
			else $page_now = 1;

			if(isset($_GET['key']))
			$key = $_GET['key'];
			else $key = "";


		}

		//$data['user_list'] = $this->Admindb->users($key, $page_now);

		$data['user_list']['results'] = $this->Admindb->table_full('users', ' WHERE user_type_ids = 5');
		//$data['user_list'] = $this->Admindb->customer($key, $page_now);

		//$this->time_convert(1.25);

		if(isset($_POST['submit'])){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->user_by_id($_GET['edit']);
			
			$form_data = $_POST;
			
			if(isset($_FILES['logo']) && !empty($_FILES['logo'])){
				$logo = $this->imageUpload( $_FILES['logo'], "/assets/uploads/user/");				
			}
			if(!$logo) $logo = $data['edit']['profile_picture'];

			$this->Admindb->user_pic_update(array('id' => $id, 'profile_picture' => $logo));
			//$this->debug($logo); die();
			
			
			$details = json_decode($data['edit']['user_meta']);
			$details->customer_code = $form_data['customer_code'];
			$details->phone = $form_data['phone'];
			$details->address = trim(preg_replace('/\s\s+/', ' ', $form_data['address']));
			//$details->hospital = $form_data['hospital'];
			//$details->subscription_amount = $form_data['subscription_amount'];

//$this->debug($form_data);die;


			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$error = $this->add_alert('danger','Validation Error!');
				print_r($error);
			}else{
				$details = json_encode($details);

				$status = $this->Admindb->user_update_meta($id,$details);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$form_data['id']);				
			}			
		}

		if(isset($_POST['dis_submit'])){
			$id = $_GET['edit'];
			$form_data = $_POST;
			unset($form_data['dis_submit']);
			//$this->debug($form_data); die();
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$status = $this->Admindb->discount_pricing_add($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$form_data['customer'].'#discount');				
			}			
		}	
			
		if(isset($_POST['submit_maint_fees'])){
			$id = $_GET['edit'];
			$form_data = $_POST;
			
			$data['edit'] = $this->Admindb->user_by_id($_GET['edit']);
			$details = json_decode($data['edit']['user_meta']);
			$details->maintenance_fee_type = $form_data['maintenance_fee_type'];
			$details->maintenance_fee_amount = $form_data['maintenance_fee_amount'];
			unset($form_data['submit_maint_fees']);

			//$this->debug($this->empty_key_value($form_data));die;

			if(!empty($this->empty_key_value($form_data))){

				$this->add_alert('danger','Validation Error!');
			}else{
				$details = json_encode($details);
				$status = $this->Admindb->user_update_meta($id,$details);
				$this->add_alert($status['type'],'Maintenance fee added sucessfully');
				$this->redirect('admin/customer?edit='.$id.'#maint_fees');				
			}			
		}


		if(isset($_POST['submit_add_rate'])){
			$id = $_GET['edit'];
			$form_data = $_POST;
			unset($form_data['submit_add_rate']);
			//$this->debug($form_data); die();
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->analyses_rate_add($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$form_data['customer'].'#price');				
			}

			
		}

		if(isset($_POST['submit_subscription'])){
			$id = $_GET['edit'];
			$form_data = $_POST;
			unset($form_data['submit_subscription']);
			//$this->debug($form_data); die();
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->subscription_add($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$form_data['customer'].'#subscription');				
			}

			
		}





		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];

			if(isset($_GET['delete_price']) && $_GET['delete_price'] != ""){
				$id_del = $_GET['delete_price'];
				$status = $this->Admindb->delete('analyses_rates',$id_del);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$id.'#price');

			}

			if(isset($_GET['delete_subscription']) && $_GET['delete_subscription'] != ""){
				$id_del = $_GET['delete_subscription'];
				$status = $this->Admindb->delete('subscriptions',$id_del);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/customer?edit='.$id.'#subscription');

			}

			if(isset($_GET['delete_disc']) && $_GET['delete_disc'] != ""){
			$id_del  = $_GET['delete_disc'];
			$status = $this->Admindb->delete('
				discount_range',$id_del);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/customer?edit='.$id.'#discount');

			}

			if(isset($_POST['update_subscription'])){
				$form_data = $_POST;
				unset($form_data['update_subscription']);

				if(!empty($this->empty_key_value($form_data))){
					$this->add_alert('danger','Validation Error!');
				}else{

					$status = $this->Admindb->subscription_update($form_data);
					$this->add_alert($status['type'],$status['msg']);
					$this->redirect('admin/customer?edit='.$id.'#subscription');			
				}
			}

			if(isset($_POST['update_price'])){
				$form_data = $_POST;
				unset($form_data['update_price']);

				if(!empty($this->empty_key_value($form_data))){
					$this->add_alert('danger','Validation Error!');
				}else{

					$status = $this->Admindb->analyses_rate_update($form_data);
					$this->add_alert($status['type'],$status['msg']);
					$this->redirect('admin/customer?edit='.$id.'#price');			
				}
			}


		if(isset($_POST['update_disc'])){
				$form_data = $_POST;
				unset($form_data['update_disc']);

				if(!empty($this->empty_key_value($form_data))){
					$this->add_alert('danger','Validation Error!');
				}else{

					$status = $this->Admindb->discount_range_update($form_data);
					$this->add_alert($status['type'],$status['msg']);
					$this->redirect('admin/customer?edit='.$id.'#discount');			
				}
			}




			$data['edit'] = $this->Admindb->user_by_id($id);
			//$sfc = $this->Admindb->salesforce_code_full();
			
			//$data['sfc'] = $this->Admindb->salesforce_code_full();

			$data['analyses_rate'] = $this->Admindb->analyses_rate_user($id);
			$data['subscription'] = $this->Admindb->subscriptions_user($id);

			$data['discount_pricing_list'] = $this->Admindb->get_discount_range_by_customer($id);

			$data['max_disc'] = $this->Admindb->get_max_discount_by_customer($id);
			
			$this->view('admin/customer/edit',$data);

		}else{
			$this->view('admin/customer/list',$data);
		}



		
	}	



	public function billing_code()
	{	
		$data['user'] = $this->user;
		$data['sfc'] = $this->Admindb->salesforce_code_full();
		$this->admin_sidebar($data);
		if(isset($_GET['page'])){
			$page_now = $_GET['page'];
		}
		else{
			$page_now = 1;
		}

		$data['billing_code_list'] = $this->Admindb->billing_code($page_now,SITE_URL.'/admin/billing_code');
		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('
				Billing_codes',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/billing_code');

		}


		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			//$this->debug($form_data); die();

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->billing_code_update($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->billing_code_by_id($id);
			$this->view('admin/billing_code/edit',$data);
		}else{
			$this->view('admin/billing_code/list',$data);
		}


		

	}

		public function discount_pricing(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$data['discount_pricing_list'] = $this->Admindb->get_discount_range();	



		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$status = $this->Admindb->discount_range_update($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}
		
		}	

		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('
				discount_range',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/discount_pricing');

		}
		

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->discount_range_by_id($id);
			$this->view('admin/discount/edit',$data);
		}else{
			$this->view('admin/discount/list',$data);
		}
	}

	public function billing_code_add()
	{	
		$data['user'] = $this->user;
		$data['sfc'] = $this->Admindb->salesforce_code_full();
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->billing_code_add($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/billing_code_add');
		}
		$this->view('admin/billing_code/add',$data);

	}

	public function discount_pricing_add()
	{
		$data['user'] = $this->user;
		$data['max_disc'] = $this->Admindb->get_last_discount_range();
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;

			unset($form_data['submit']);
			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				if($data['max_disc'] < $form_data['minimum_value'] && $data['max_disc']+1 < $form_data['maximum_value']){
					$status = $this->Admindb->discount_pricing_add($form_data);
					$this->add_alert($status['type'],$status['msg']);	
				} else {
					$status = $this->Admindb->discount_pricing_add($form_data);
					$this->add_alert('danger','Enter Appropriate values');
				}							
			}
			$this->redirect('admin/discount_pricing_add');
		}
		$this->view('admin/discount/add',$data);
	}

	public function import()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$colm = array('Accession', 'MRN', 'Patient Name', 'Site Procedure', 'Last Modified', 'Exam Time', 'Status', 'Priority', 'Site', 'Hospital');

		if(isset($_FILES) && !empty( $_FILES)){

			$file_name = $this->fileUpload($_FILES['up'],XL_PATH);
			$data_xl = $this->read_xl($file_name);


			

			foreach ($data_xl[0] as $key => $value) {
				if (in_array($value, $colm)){
					$needed_keys[] = $key; 
				}
			}


			foreach ($data_xl as $key => $value) {
				if($key == 0) continue;
				if($value[0] == '' || $value[0] == null) continue;
				$inner_data = array();
				foreach ($value as $key_inner => $value_inner) {
					if (in_array($key_inner, $needed_keys)){
						$inner_data[] =  $value_inner;
					}
				}
				$insert_data[] = $inner_data;
			}

			$update_count = 0;
			$rept_count = 0;
			$updated_array = array();
			$insert_count = 0;

			$msg = '<strong>Import successfully.</strong><br>';

			


 //$this->debug($insert_data); die();

			foreach ($insert_data as $key => $value) {
				$update_id = $this->Admindb->clario_exist($value[0],$value[1]);
				$hospital = $this->Admindb->hospital_name_exist($value[8]);


				


				if(!$hospital){
					$a = $this->Admindb->hospital_add($value[8]);

				} 




				if($update_id){
					$value_change = $this->Admindb->clario_change_exist($update_id,$value);
					if($value_change){
						$updated = '';
						$key_values = array_keys($value_change);
						foreach ($key_values as $index => $value_inner) {
							if($index != count($key_values)-1)
								$updated.=ucfirst( str_replace('_', ' ', $value_inner)).', '; 
							else
								$updated.=ucfirst( str_replace('_', ' ', $value_inner)).' '; 
						}
						$clario_row = $this->Admindb->get_by_id('Clario',$update_id);
						$updated.='Updated in (Accession: '.$clario_row['accession'].') <br>';
						$updated_array[] = $updated;
						$status = $this->Admindb->clario_import_update($update_id,$value);
						$update_count++;
					}else{
						$status['type'] = 'success';
						$rept_count++;
					}

				}else{
					$status = $this->Admindb->clario_import($value);
					$insert_count++;
				}

			}
			$msg.= $insert_count.' Rows Insert<br>';
			$msg.= $update_count.' Rows Update<br>';
			if(!empty($updated_array))
				foreach ($updated_array as  $value) {
					$msg.= $value;
				}
			$msg.= $rept_count.' Rows Skipped<br>';
				

			$this->add_alert($status['type'],$msg);	

		}



		$this->view('admin/import_export/import_clario',$data);
	}




	public function billing_hours(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$data['analyst_worksheet'] = $this->Admindb->collect_wsheet();
		$this->view('dashboard/accounts/index',$data);
	}

	public function accounts(){
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$form_data = $_POST;
		if(!empty($form_data))
		$data['wsheet'] = $this->Admindb->select_wsheet_date($form_data);

		$this->view('admin/statistics/worksheet',$data);
	}

	public function billing()
	{
		
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		//$this->carry_save();
		$form_data = $_POST;
		if(!empty($form_data)){
			$data['site'] = $ids = $form_data['site'];

			$data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids);
			if($data['carry_frwd']['data']){
				$ans_key = array_column($data['carry_frwd']['data'], 'analysis');
				$ans = array_column($data['carry_frwd']['data'], 'count');
				$data['carry_frwd']['ans_ids'] = $ans_key;
				$data['carry_frwd']['ans_count'] = array_combine ( $ans_key , $ans );
			}
			//$this->debug($data);
			$data['wsheet'] = $this->get_calc_worksheet($ids,$form_data['start_date']);
			//$this->debug($data['wsheet']);
			
		} 
		$this->view('admin/billing/come',$data);



	}
	public function billing2()
	{
		
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		//$this->carry_save();
		$form_data = $_POST;
		if(!empty($form_data)){
			$data['site'] = $ids = $form_data['site'];

			$data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids);
			if($data['carry_frwd']['data']){
				$ans_key = array_column($data['carry_frwd']['data'], 'analysis');
				$ans = array_column($data['carry_frwd']['data'], 'count');
				$data['carry_frwd']['ans_ids'] = $ans_key;
				$data['carry_frwd']['ans_count'] = array_combine ( $ans_key , $ans );
			}
			//$this->debug($data);
			$data['wsheet'] = $this->get_calc_worksheet($ids,$form_data['start_date']);
			//$this->debug($data['wsheet']);
			
		} 
		$this->view('admin/billing/billing2',$data);



	}

	public function billing3()
	{
		
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		//$this->carry_save();

		$form_data = $_POST;
		if(!empty($form_data)){
			$data['site'] = $ids = $form_data['site'];

			$data['carry_frwd']['data'] = $this->Admindb->carry_forward($ids);
			if($data['carry_frwd']['data']){
				$ans_key = array_column($data['carry_frwd']['data'], 'analysis');
				$ans = array_column($data['carry_frwd']['data'], 'count');
				$data['carry_frwd']['ans_ids'] = $ans_key;
				$data['carry_frwd']['ans_count'] = array_combine ( $ans_key , $ans );
			}
			//$this->debug($data);
			$data['wsheet'] = $this->get_calc_worksheet($ids,$form_data['start_date']);
			$data['extra_bill'] = $this->Admindb->miscellaneous_billing_by_date($ids,$form_data['start_date']);

			//$this->debug($data['wsheet']);
			
		} 
		$this->view('admin/billing/billing3',$data);



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

				$this->redirect('admin/profile');
			}else{

				$status = $this->Admindb->user_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/profile');				
			}

			
		}
			$this->view('user/profile',$data);

	}





		public function analyses()
	{	
			$data['user'] = $this->user;
		$this->admin_sidebar($data);

		$key = "";
		$page_now = 1;
		
		if(isset($_GET)){
			if(isset($_GET['page']))
			$page_now = $_GET['page'];
			else $page_now = 1;

			if(isset($_GET['key']))
			$key = $_GET['key'];
			else $key = "";


		}

		//analyses?key=a&page=3
		
		//$data['salesforce_code_list'] = $this->Admindb->analyses($key,$page_now,SITE_URL.'/admin/analyses');
		$data['salesforce_code_list']['results'] = $this->Admindb->table_full('analyses');
		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('analyses',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/analyses');

		}

		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$status = $this->Admindb->analyses_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/analyses?edit='.$form_data['id']);				
			}
			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->analyses_by_id($id);
			$this->view('admin/analyses/edit',$data);

		}else{
			$this->view('admin/analyses/list',$data);
		}
		
		
		

	}
	public function analyses_add()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data)) && is_array($salesforce) ){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->analyses_add($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/analyses_add');
		}
		$this->view('admin/analyses/add',$data);

	}






	public function analyses_rate()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_GET['page'])){
			$page_now = $_GET['page'];
		}
		else{
			$page_now = 1;
		}
		$data['salesforce_code_list'] = $this->Admindb->analyses_rate($page_now,SITE_URL.'/admin/analyses_rate');


		
		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('analyses_rate',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/analyses_rate');

		}

		

		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->analyses_rate_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/analyses_rate?edit='.$form_data['id']);				
			}

			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->analyses_rate_by_id($id);
			$this->view('admin/analyses_rate/edit',$data);

		}else{
			$this->view('admin/analyses_rate/list',$data);
		}
		
		

	}

	public function analyses_rate_add()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data)) && is_array($salesforce) ){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->analyses_rate_add($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/analyses_rate_add');
		}
		$this->view('admin/analyses_rate/add',$data);

	}


	public function ajax_analysis_polpulate()
	{
		$id = $_GET["id"];
		$data = $this->Admindb->analyses_by_id($id);
		echo json_encode($data);
		die();
		//echo $id;
	}

	public function test()
	{
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$this->carry_save();
	}

	



	public function add_miscellaneous_billing()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data)) && is_array($salesforce) ){
				$this->add_alert('danger','Validation Error!');
			}else{

				$status = $this->Admindb->miscellaneous_billing_add($form_data);
				$this->add_alert($status['type'],$status['msg']);				
			}

			$this->redirect('admin/miscellaneous_billing');
		}
		$this->view('admin/miscellaneous_billing/add',$data);

	}

	public function all_reports()
	{	

		
		$XML = (array)simplexml_load_file(ROOT_PATH."/assets/uploads/xml/demo.xml");

		$XML = json_decode(json_encode($XML), true);

		$data['patient'] = array(

					'Name'=> $XML['DICOMInfo']['Patient']['Name'],
					'DisplayName'=> $XML['DICOMInfo']['Patient']['DisplayName'],
					'ID'=> $XML['DICOMInfo']['Patient']['ID'],
					'age'=> $XML['DICOMInfo']['Patient']['age'],
					'Sex'=> $XML['DICOMInfo']['Patient']['Sex'],
					'DOB'=> $XML['DICOMInfo']['Patient']['Birth'],
					'accession'=> $XML['DICOMInfo']['GeneralStudy']['AccessionNo'],
					'StudyDate'=> $XML['DICOMInfo']['GeneralStudy']['StudyDate'],

				); 

		
		
		
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$this->view('admin/reports/report',$data);

	}

	public function miscellaneous_billing()
	{	


		$data['user'] = $this->user;
		$this->admin_sidebar($data);

		$key = "";
		$page_now = 1;
		
		if(isset($_GET)){
			if(isset($_GET['page']))
			$page_now = $_GET['page'];
			else $page_now = 1;

			if(isset($_GET['key']))
			$key = $_GET['key'];
			else $key = "";


		}

		//analyses?key=a&page=3
		
		//$data['miscellaneous_billing'] = $this->Admindb->miscellaneous_billing($key,$page_now,SITE_URL.'/admin/miscellaneous_billing');
		$data['miscellaneous_billing']['results'] = $this->Admindb->table_full('miscellaneous_billing');



		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('miscellaneous_billing',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('admin/miscellaneous_billing');

		}

		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$status = $this->Admindb->miscellaneous_billing_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('admin/miscellaneous_billing?edit='.$form_data['id']);				
			}
			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('miscellaneous_billing',$id);
			$this->view('admin/miscellaneous_billing/edit',$data);

		}else{
			$this->view('admin/miscellaneous_billing/list',$data);
		}
		
		
		

	}















}