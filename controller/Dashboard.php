<?php
class Dashboard extends Controller{
	public $user;
	public $Admindb;
	public $Logindb;
	public $Assigndb;
	function __construct(){
		$this->Logindb = $this->model('logindb');
		$this->Admindb = $this->model('admindb');
		$this->Assigndb 	= 	$this->model('adminassignmodel');
		if(isset($_SESSION['user']) && $_SESSION['user']->user_type_ids != 1){
			$this->user = $this->Admindb->user_obj( $_SESSION['user']->email );



		}else{ 
			//die('Access forbidden');
			$this->add_alert('danger','Access forbidden');
			$this->redirect('');
		}
	}
	public function index(){
		$data['user'] = $this->user;
		
		$this->admin_sidebar($data);

		if($this->user->group_id == 3){			
			$data['analyst_worksheet'] = $this->Admindb->collect_wsheet($this->user->id);
			$data['jobs_count'] =  $this->Admindb->count_table("Clario");
			$data['analyst_total_hours'] =  $this->Admindb->total_analyst_hours("worksheets","WHERE analyst =".$this->user->id );
			$data['jobs_complete'] =  $this->Admindb->count_table("worksheets","WHERE status = 'Completed' AND analyst =".$this->user->id );
			$data['jobs_open'] =  $this->Admindb->count_table("Clario","WHERE assignee =".$this->user->id );
			$data['jobs_under_review'] =  $this->Admindb->count_table("worksheets","WHERE status = 'Under review' AND analyst =".$this->user->id );
			$data['jobs_in_progress'] = $data['jobs_open'] - ($data['jobs_complete']+$data['jobs_under_review']);
			
			$data['checkdone'] 		= $this->Admindb->anysecondcheckcountdone();
			$data['checknotdone'] 	= $this->Admindb->anysecondcheckcountnotdone();
			//print_r($data);
			$this->view('user/dashboard',$data);

		}elseif($this->user->group_id == 2){

					$data['analyst_worksheet'] = $this->Admindb->collect_wsheet();

		$data['customer_count'] = $this->Admindb->count_table("users","WHERE user_type_ids = 5");	
		$data['analyst_count'] = $this->Admindb->count_table("users","WHERE user_type_ids = 3");


		$data['jobs_count'] =  $this->Admindb->count_table("Clario");
		$data['jobs_Completed'] =  $this->Admindb->count_table("worksheets", "WHERE status = 'Completed' " );
		$data['jobs_Under_review'] =  $this->Admindb->count_table("worksheets", "WHERE status = 'Under review' ");
		$data['jobs_In_progress'] =  $this->Admindb->count_table("worksheets","WHERE status = 'In progress'");

		$data['jobs_Assigned'] =  $this->Admindb->count_table("Clario","WHERE assignee =" .$this->user->id );

		$data['jobs_Completed_per'] = ($data['jobs_Completed']/$data['jobs_assigned'])*100;
		$data['jobs_Under_review_per'] = ($data['jobs_Under_review']/$data['jobs_assigned'])*100;
		$data['jobs_In_progress_per'] = ($data['jobs_In_progress']/$data['jobs_assigned'])*100;
		$this->view('dashboard/index',$data);

		}else{
			$this->view('user/dashboard',$data);
		}

		
		
	}

	public function dicom_details(){

		$data['user'] 		= $this->user;
		
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/dashboard/dicom_details');
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
				$this->redirect('dashboard/dicom_details');

			}
			$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/dashboard/dicom_details');

			
			if(isset($_GET['edit']) && $_GET['edit'] != ""){
				$id = $_GET['edit'];
				$data['edit'] = $this->Admindb->get_by_id('Clario',$id);
				$this->view('dashboard/dicom/edit',$data);

			}else{
				$this->view('dashboard/dicom/list',$data);
			}

		}
		else{$this->redirect('/dashboard');}
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



	// show Current Month Studies
	public function dicom_details_month() {

		$data['user'] = $this->user;
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			$data['analysts'] = $this->get_all_analysts();
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}

			//$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_full();
			$data['asignee'] = $this->Admindb->get_all_analyst();

			if(isset($_POST['submit'])){
				$status = $this->Admindb->worksheet_assign($this->user->id,$_GET['view'],$_POST['customer']);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/open_work_sheets');
			}

			if(isset($_GET['view']) && $_GET['view'] !=""){
				
				$data['edit'] = $this->Admindb->get_by_id('Clario',$_GET['view']);
				$this->view('dashboard/worksheet/all/view',$data);

			}

			if(isset($_POST['last_search'])){
				
				$form_data = $_POST;
				$day = $form_data['last_select']; 
				$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
				$data['day'] = $day;
				$this->view('dashboard/worksheet/all/list',$data);
				
			}
			else{

				$this->view('dashboard/worksheet/month/list',$data);
			}
			
		} else {
			$this->redirect('/dashboard');
		}
	}

	public function dicom_details_all(){

		$data['user'] = $this->user;
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			$data['analysts'] = $this->get_all_analysts();
			//echo "<pre>";
			//print_r($data['analysts']);exit;
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			//$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/dashboard/open_work_sheets');
			//$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario');
			//$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_full();

			$data['asignee'] = $this->Admindb->get_all_analyst();

			if(isset($_POST['submit'])){
				$status = $this->Admindb->worksheet_assign($this->user->id,$_GET['view'],$_POST['customer']);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/open_work_sheets');
			}

			

			if(isset($_GET['view']) && $_GET['view'] !=""){
				
				$data['edit'] = $this->Admindb->get_by_id('Clario',$_GET['view']);
				$this->view('dashboard/worksheet/all/view',$data);

			}
			if(isset($_POST['last_search'])){
				
				$form_data = $_POST;
				$day = $form_data['last_select']; 
				$data['dicom_details_list'] = $this->Admindb->wsheet_assign_list_analyst_day($day);
				$data['day'] = $day;
				$this->view('dashboard/worksheet/all/list',$data);
				
			}

			else{
				$this->view('dashboard/worksheet/all/list',$data);
			}

		}else{$this->redirect('/dashboard');}
	}

	public function open_work_sheets(){

		$data['user'] = $this->user;
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			//$data['dicom_details_list'] = $this->Admindb->dicom_details($page_now,SITE_URL.'/dashboard/open_work_sheets');
			$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario' ,' WHERE assignee = 0');

			if(isset($_POST['submit'])){

				//echo $_GET['view'];
				$assn = $this->Admindb->get_assigned_id($_GET['view']);
				//print_r($assn); die(); 
				if($assn == 0){
				
					$status = $this->Admindb->worksheet_assign($this->user->id,$_GET['view'],$_POST['customer'],$_POST['tat']);
					//$this->add_alert($status['type'],$status['msg']);
					//$this->redirect('dashboard/open_work_sheets');
					$this->redirect('dashboard/my_work_sheets?edit='.$_GET['view']);
			
				}else{
					if(isset($_POST['reasn']) && $_POST['reasn']==1){
						$status = $this->Admindb->worksheet_assign($this->user->id,$_GET['view'],$_POST['customer'],$_POST['tat']);
						$this->add_alert($status['type'],$status['msg']);
						//$this->redirect('dashboard/open_work_sheets');
						$this->redirect('dashboard/my_work_sheets?edit='.$_GET['view']);
					}else{
						$_SESSION['acustomer'] = $_POST['customer'];
						$_SESSION['atat'] = $_POST['tat'];
						$this->redirect('dashboard/open_work_sheets?view='.$_GET['view'].'&assign=1');
					}
					
				}      
				
			}

			if(isset($_GET['view']) && $_GET['view'] !=""){
				
				$data['edit'] = $this->Admindb->get_by_id('Clario',$_GET['view']);
				$this->view('dashboard/worksheet/open/view',$data);

			}else{
				$this->view('dashboard/worksheet/open/list',$data);
			}

		}else{$this->redirect('/dashboard');}
	}


	public function my_work_sheets(){


		$data['user'] = $this->user;
		$data['rv_status']	=	(!empty($_GET['rv']))?$_GET['rv']:0;
		//$data['all_billing_code'] = $this->Admindb->billing_code_full();
		$data['all_billing_code'] = $this->Admindb->table_full('analyses');
		
       echo $data['all_billing_code'];


		//$data['new_analysis' ] = $this->Admindb->get_user_id();
		//print_r($data['all_billing_code']);die;
		if($this->user->group_id == 3){
			$this->admin_sidebar($data);
			if(isset($_GET['page'])){
				$page_now = $_GET['page'];
			}
			else{
				$page_now = 1;
			}
			//$data['dicom_details_list'] = $this->Admindb->dicom_detail_by_id($page_now,SITE_URL.'/dashboard/my_work_sheets',$this->user->id);
			$data['dicom_details_list']['results'] = $this->Admindb->table_full('Clario' ,'WHERE assignee = '.$this->user->id);

			if(isset($_POST['remove_assign'])){
				$clario_id		=	isset($_POST['clario_id'])?$_POST['clario_id']:'';
				if(!empty($clario_id)){
					$this->Admindb->statusupdate($clario_id);
				}
				//$status = $this->Admindb->worksheet_assign(0,$_GET['edit'],0);
				$status = $this->Admindb->delete('worksheets',$_POST['wsheet_id']);
				$this->add_alert('danger','Assignee Removed.');
				$this->redirect('dashboard/my_work_sheets');
			}

			/*Edit - 25-2-2020 */
			if(isset($_POST['remove_assignee_worksheet'])){
				$clario_id		=	isset($_POST['clario_id'])?$_POST['clario_id']:'';
				if(!empty($clario_id)){
					$this->Admindb->statusupdate($clario_id);
				}
				//$status = $this->Admindb->worksheet_assign(0,$_GET['edit'],0);
				$this->Admindb->worksheets_details_delete($_POST['wsheet_id']);
				$status = $this->Admindb->delete('worksheets',$_POST['wsheet_id']);
				$this->add_alert('danger','Assignee Removed.');
				$this->redirect('dashboard/my_work_sheets');
			}
			/*Edit - 25-2-2020 */

			if(isset($_POST['re_assign'])){

				$edit_id = $_GET['edit'];
				$status = $this->Admindb->re_assign($edit_id);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/my_work_sheets?edit='.$edit_id);

			}
/******************************** RC ******************************************/
/*----------------------- REVIEW COMPLETED FUNCTION ---------------------------
	@FUNCTION DATE              :  03-01-2019 
------------------------------------------------------------------------------*/
			if(isset($_POST['re_completed'])){
				$user_id 	= 	$this->user->id;
				$edit_id 	= 	$_GET['edit'];
				echo $status 	= 	$this->Admindb->reviewcompleted($edit_id,$user_id);
				if($status['type']=='success'){
					$this->redirect('dashboard/dicom_details_all');
				}
				else{
					$this->add_alert($status['type'],$status['msg']);
					$this->redirect('dashboard/my_work_sheets?edit='.$edit_id);
				}
			}
/******************************** RC END **************************************/
			if(isset($_GET['edit']) && $_GET['edit'] !=""){
				$edit_id = $_GET['edit'];
				$data['edit'] = $this->Admindb->get_by_id('Clario',$_GET['edit']);
				$data['edit_wsheet'] = $this->Admindb->get_by_clario_id($_GET['edit']);
				$data['secondcheck'] = $this->Admindb->getreviewdata($_GET['edit']);
				//print_r($data['edit_wsheet']);
				//$this->debug($data['edit']['customer']);
				//echo ();die;
				//$user_id = $this->Admindb->get_user_id_by_accession($data['edit']['accession'],$data['edit']['mrn']);

				//$usermeta = $this->Admindb->get_user_id_by_cc_code($user_id['cc_code']);

				$customer = $data['edit']['customer'];	


				//$data['billing_codes']= $this->Admindb->get_analyses_by_user($customer);

				$data['billing_codes'] = $this->Admindb->subscription_analyses($customer)['results'];
				
				/*print_r($data['user']->id )
				 ;*/ 				

				//$this->debug($data['edit_wsheet']);$user_id['id']
				if(isset($_POST['submit'])){
					$form_data = $_POST;

					
					
					$form_data['date'] =  date("Y-m-d H:i:s");
					$wsheet = $form_data['wsheet_id'];

					$form_data['analyst_hours'] = $form_data['analyst_hours'][0];//$this->time_convert($form_data['analyst_hours'],'hr');
                                        
                                      //  $form_data['expected_time'] = $form_data['expected_time'];

					$form_data['image_specialist_hours'] = $form_data['image_specialist_hours'][0];//$this->time_convert($form_data['image_specialist_hours'],'hr');

					$form_data['medical_director_hours'] = $form_data['medical_director_hours'][0];//$this->time_convert($form_data['medical_director_hours'],'hr');

					$no_of_analysis  = $form_data['analyses_performed'];
					


					//print_r($no_of_analysis).'<br/>';	
					/*
					$billing = $this->Admindb->get_multiple_billing_code($form_data['analyses_performed']);*/
					$anals_and_code = array();
					foreach ($form_data['analyses_performed'] as $key => $each) {
						$anals_and_code[] = $this->Admindb->analysis_data_by_ids($each,$customer);
						$addon_flows[$each] = $form_data['addon_flows_'.$each]+1;
					}

					//$this->debug($anals_and_code); die();
					$form_data['customer_id'] = $customer;
					$form_data['addon_flows'] = json_encode($addon_flows);
					$size = count($anals_and_code);
					$analyses_performed = '';
					$pia_analysis_codes = '';
					$analyses_ids = '';
					$existing_rate = array();
					foreach ($anals_and_code as $key => $value) {
						$existing_rate[$value[0]['id']] = $value[0]['rate'];
						if($size-1 == $key){
							$analyses_performed.= $value[0]['name'];
							$pia_analysis_codes.= $value[0]['code'];
							$analyses_ids.= $value[0]['id'];

						}
						else {
							$analyses_performed.= $value[0]['name'].',';
							$pia_analysis_codes.= $value[0]['code'].',';
							$analyses_ids.= $value[0]['id'].',';
						}
					}
					unset($form_data['analyses_performed']);
					//$this->debug($form_data['analyses_performed']); die();
					
					//$this->debug($anals_and_code); die(); 


					$form_data['pia_analysis_codes'] = $pia_analysis_codes;
					$form_data['analyses_performed'] = $analyses_performed;
					$form_data['analyses_ids'] = $analyses_ids;
					$form_data['existing_rate'] = json_encode($existing_rate);
					$any_mint				=	implode(',',$form_data['ans_hr']);
					$form_data['ans_hr']	=	$any_mint;
					unset($form_data['submit']);
					unset($form_data['wsheet_id']);
					//echo $analyses_ids.' '.$any_mint.'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
					//print_r($form_data);
					//echo $wsheet;
					//print_r($form_data);exit;
					if(isset($wsheet) && $wsheet != ""){
						 //update
						$status = $this->Admindb->update_wsheet($form_data);
						//echo  $status['qry'];
				        $this->add_alert($status['type'],$status['msg']);
						//print_r($form_data);
						if($form_data['status'] == "Completed")
						{
							
//print_r($form_data);
						$this->Admindb->empty_wsheet_details($wsheet);


						
//print_r($form_data);
							foreach ($no_of_analysis as $key => $no_of_ans) {
							//echo 1;
//print_r($form_data);
									// $this->Admindb->update_wsheet_details($form_data,$no_of_ans,$existing_rate[$no_of_ans],$addon_flows[$no_of_ans],$wsheet);

								$this->Admindb->insert_wsheet_details($form_data,$wsheet,$no_of_ans,$existing_rate[$no_of_ans],$addon_flows[$no_of_ans],$customer);
//echo $dataval['sql'];

								}

								
						
							$this->Admindb->update_wsheet($form_data);

							$this->Admindb->update_wsheet_completed_time($form_data);    
						

					} 



					else {
							if($form_data['status'] != $data['edit_wsheet']['status']){
								if($form_data['status'] == "CancelledAcc"){
									//add billing entry here
									$bill_data = array(
										'count_an' => 1,
										'name_an' => 'Cancelled accidental send',
										'description_an'=> 'Cancelled accidental send',
										'rate_an' => 10,
										'customer' =>$customer
									);
									$this->Admindb->miscellaneous_billing_add_during_cancellation($bill_data);
								}
								if($form_data['status'] == "CancelledCust"){
									//add billing entry here
									$bill_data = array(
										'count_an' => 1,
										'name_an' => 'Cancelled - by customer during analysis',
										'description_an'=> 'Cancelled - by customer during analysis',
										'rate_an' => 25,
										'customer' =>$customer
									);
									$this->Admindb->miscellaneous_billing_add_during_cancellation($bill_data);
								}
							}
							$this->Admindb->empty_wsheet_details($wsheet);       
						}
					
						//$this->Admindb->update_tat($form_data);
					}  

					else{

						$status = $this->Admindb->insert_wsheet($form_data);
						$this->add_alert($status['type'],$status['msg']);    
 
						if($form_data['status'] == "Completed")
						{
							/*$this->debug("Completed");
							$this->debug("insert");
							die;*/
							//echo 2;
							//print_r($no_of_analysis);
								foreach ($no_of_analysis as $key => $no_of_ans) {
							//print_r($form_data);
								$this->Admindb->insert_wsheet_details($form_data,$status['worksheet_id'],$no_of_ans,$existing_rate[$no_of_ans],$addon_flows[$no_of_ans],$customer);
							}
							
							$this->Admindb->update_wsheet_completed_time($form_data);
						}
						if($form_data['status'] != $data['edit_wsheet']['status']){
							if($form_data['status'] == "CancelledAcc"){
								//add billing entry here
								$bill_data = array(
									'count_an' => 1,
									'name_an' => 'Cancelled accidental send',
									'description_an'=> 'Cancelled accidental send',
									'rate_an' => 10,
									'customer' =>$customer
								);
								$this->Admindb->miscellaneous_billing_add_during_cancellation($bill_data);
							}
							if($form_data['status'] == "CancelledCust"){
								//add billing entry here
								$bill_data = array(
									'count_an' => 1,
									'name_an' => 'Cancelled - by customer during analysis',
									'description_an'=> 'Cancelled - by customer during analysis',
									'rate_an' => 25,
									'customer' =>$customer
								);
								$this->Admindb->miscellaneous_billing_add_during_cancellation($bill_data);
							}
						}  
					
					}      
					//die;
					//$this->redirect('dashboard/my_work_sheets?edit='.$edit_id);
					$this->redirect('dashboard/dicom_details_all');

				}
				//print_r($data);
				$this->view('dashboard/worksheet/my/edit',$data);

			}else{
				$this->view('dashboard/worksheet/my/list',$data);
			}
			


		}else{$this->redirect('/dashboard');}    
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

			$this->redirect('dashboard/miscellaneous_billing');
		}
		$this->view('dashboard/miscellaneous_billing/add',$data);

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
		
		//$data['miscellaneous_billing'] = $this->Admindb->miscellaneous_billing($key,$page_now,SITE_URL.'/dashboard/miscellaneous_billing');
		$data['miscellaneous_billing']['results'] = $this->Admindb->table_full('miscellaneous_billing');
		if(isset($_GET['delete']) && $_GET['delete'] != ""){
			$id = $_GET['delete'];
			$status = $this->Admindb->delete('miscellaneous_billing',$id);
			$this->add_alert($status['type'],$status['msg']);
			$this->redirect('dashboard/miscellaneous_billing');

		}

		if(isset($_POST['submit'])){
			$form_data = $_POST;
			unset($form_data['submit']);

			if(!empty($this->empty_key_value($form_data))){
				$this->add_alert('danger','Validation Error!');
			}else{
				$status = $this->Admindb->miscellaneous_billing_update($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/miscellaneous_billing?edit='.$form_data['id']);				
			}
			//$this->redirect('admin/salesforce_code_add');
		}

		if(isset($_GET['edit']) && $_GET['edit'] != ""){
			$id = $_GET['edit'];
			$data['edit'] = $this->Admindb->get_by_id('miscellaneous_billing',$id);
			$this->view('dashboard/miscellaneous_billing/edit',$data);

		}else{
			$this->view('dashboard/miscellaneous_billing/list',$data);
		}
		
		
		

	}





	public function add_work_sheet()
	{	
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		
		if(isset($_POST['submit'])){
			$form_data = $_POST;

				$status = $this->Admindb->insert_study($form_data);
				$this->add_alert($status['type'],$status['msg']);
				$this->redirect('dashboard/open_work_sheets');


			
		}	
		
		
		
	$this->view('dashboard/worksheet/open/add');
	}

	public function assigned_customer() {
		$data['user'] = $this->user;
		$this->admin_sidebar($data);
		$analyst_id = $this->user->id;

		$this->view('dashboard/adminassign/list');
	}

}