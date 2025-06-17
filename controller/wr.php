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
						

						} else {
						/*	if($form_data['status'] != $data['edit_wsheet']['status']){
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
							$this->Admindb->empty_wsheet_details($wsheet);       */
						}
					
						//$this->Admindb->update_tat($form_data);
					}else{

						$status = $this->Admindb->insert_wsheet($form_data);
						$this->add_alert($status['type'],$status['msg']);

			/*			if($form_data['status'] == "Completed")
						{
							/*$this->debug("Completed");
							$this->debug("insert");
							die;*/
							//echo 2;
							//print_r($no_of_analysis);
					/*			foreach ($no_of_analysis as $key => $no_of_ans) {
							//print_r($form_data);
								$this->Admindb->insert_wsheet_details($form_data,$status['worksheet_id'],$no_of_ans,$existing_rate[$no_of_ans],$addon_flows[$no_of_ans],$customer);
							}
							
							$this->Admindb->update_wsheet_completed_time($form_data);
						}
	/*					if($form_data['status'] != $data['edit_wsheet']['status']){
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
						}   */
					
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