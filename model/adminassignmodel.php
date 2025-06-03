<?php 
class adminassignmodel extends Model {
	//------------------------------------------------------------------------------
	#		MYSQL ARRAY
	//------------------------------------------------------------------------------
	public $mysqli;
	/*----------------------- MAIN CONSTRUCT ---------------------------------------
	@PASSING ATTRIBUTES ARE      :  CONSTRUCT FUNCTION 	
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
    ------------------------------------------------------------------------------*/ 
	public function __construct($con){
		parent::__construct();
		$this->mysqli = $con;
	}
	/*----------------------- MODEL DEBUG ARRAY ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  08-08-2019  
	------------------------------------------------------------------------------*/
	public function debug($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
	/*----------------------- ASSIGNED CUSTOMERS LIST ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  09-08-2019
	@RETURN                		 :  ARRAY  
	------------------------------------------------------------------------------*/
	public function getAllAssignedCustommers() {
		$data = array();
		$sql_query = "SELECT adm.ACA_ID_PK, adm.ACA_Analyst_ID_FK, adm.ACA_Customer_ID_FK,usr.name, adm.ACA_Add_User_By, adm.ACA_User_Add_On, adm.ACA_Updated_User_By, adm.ACA_User_Updated_On, adm.ACA_Status FROM adm_admin_customer_assign adm INNER JOIN users usr ON usr.id = adm.ACA_Customer_ID_FK WHERE usr.group_id = '5' ORDER BY ACA_ID_PK DESC";
		$result = $this->mysqli->query($sql_query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$analyst_data = $this->_getuserName($row['ACA_Analyst_ID_FK']);
				$added_user   = $this->_getuserName($row['ACA_Add_User_By']);
				$upd_user     = $this->_getuserName($row['ACA_Updated_User_By']);
				
                if ($row['ACA_Status'] == 1) {                	
                	$status_a = '<span class="spanstatus badge badge-primary">Active</span>';
                } else {               	
                	$status_a = '<span class="spanstatus badge badge-danger">Block</span>';
                }
                $block_icon = '<a href="javascript:void(0)" class="status_link change_status" data-id="' . $row['ACA_ID_PK'] . '" data-status="'.$row['ACA_Status'].'"><i class="fa fa-ban" aria-hidden="true"></i></a>';
                
                $data[] = array($row['name'], $analyst_data['name'], $added_user['name'], $row['ACA_User_Add_On'], $upd_user['name'], $row['ACA_User_Updated_On'], $status_a, '<a href="' . SITE_URL . '/adminassign/edit?edit=' . $row['ACA_ID_PK'] . '" class="edit_link"><i class="fa fa-pencil-square" aria-hidden="true"></i></a><a href="javascript:void(0)" class="delete_link assign-delete-btn" data-delete="' . $row['ACA_ID_PK'] . '"><i class="fa fa-trash" aria-hidden="true"></i></a> '.$block_icon.'');
            }
		}
		
		return $data;
		
	}
	/*-----------------------  CUSTOMERS DASHBOARD ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  09-08-2019
	@RETURN                		 :  ARRAY  
	------------------------------------------------------------------------------*/
	public function getAssignedCustommersDashboard($analyst_id) {
		$data = array();
		$sql_query = "SELECT adm.ACA_ID_PK, adm.ACA_Analyst_ID_FK, adm.ACA_Customer_ID_FK,usr.name, adm.ACA_Add_User_By, adm.ACA_User_Add_On, adm.ACA_Updated_User_By, adm.ACA_User_Updated_On, adm.ACA_Status FROM adm_admin_customer_assign adm INNER JOIN users usr ON usr.id = adm.ACA_Customer_ID_FK WHERE usr.group_id = '5' AND adm.ACA_Analyst_ID_FK = '$analyst_id' AND adm.ACA_Status = 1 ORDER BY ACA_ID_PK DESC";
		$result = $this->mysqli->query($sql_query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$added_user   = $this->_getuserName($row['ACA_Add_User_By']);
				$upd_user     = $this->_getuserName($row['ACA_Updated_User_By']);				             
                
                $data[] = array($row['name'], $added_user['name'], $row['ACA_User_Add_On'], $upd_user['name'], $row['ACA_User_Updated_On']);
            }
		}
		
		return $data;
		
	}
	/*----------------------- ASSIGNED CUSTOMER BY ID ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  10-08-2019
	@RETURN                		 :  ARRAY  
	------------------------------------------------------------------------------*/
	public function getAssigneduserById($assign_id){
		$data = array();
		$sql_query = "SELECT adm.ACA_ID_PK, adm.ACA_Analyst_ID_FK, adm.ACA_Customer_ID_FK,usr.name, adm.ACA_Add_User_By, adm.ACA_User_Add_On, adm.ACA_Updated_User_By, adm.ACA_User_Updated_On, adm.ACA_Status FROM adm_admin_customer_assign adm INNER JOIN users usr ON usr.id = adm.ACA_Customer_ID_FK WHERE usr.group_id = '5' AND adm.ACA_ID_PK = '$assign_id'";
		$result = $this->mysqli->query($sql_query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {

				$data[] = array(
					'customer_name' => 	$row['name'],
					'customer_id'	=>	$row['ACA_Customer_ID_FK'],
					'analyst_id'	=>	$row['ACA_Analyst_ID_FK'],
				);
			}
			$data = $data[0];
		}
		return $data;
	}
	/*----------------------- ASSIGN CUSTOMERS ---------------------------------
	@ACCESS MODIFIERS            :  PRIVATE FUNCTION 
	@FUNCTION DATE               :  09-08-2019 
	@RETURN                		 :  ARRAY 
	------------------------------------------------------------------------------*/
	private function _getuserName($user_id = null) {
		$data = array();
		if (!empty($user_id)) {
			$sql_query = "SELECT id, name FROM users WHERE id = $user_id";
			$result = $this->mysqli->query($sql_query);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
	                $data[] = $row;
	            }
			}
		}
		return $data[0];
	}
	/*----------------------- ASSIGN CUSTOMERS ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  09-08-2019 
	@RETURN                		 :  BOOLEAN 
	------------------------------------------------------------------------------*/
	public function assignCustomersToAnalyst($data = array()) {
		if (!empty($data)) {
			foreach($data as $key => $item){
				$analyst_id 	= 	$this->mysqli->real_escape_string($item['analyst_id']);
				$customer_id 	=	$this->mysqli->real_escape_string($item['customer_id']);
				$add_user_by 	=	$this->mysqli->real_escape_string($item['add_user_by']);
				$user_add_on 	=	$this->mysqli->real_escape_string($item['user_add_on']);
				$updated_user 	=	$this->mysqli->real_escape_string($item['updated_user']);
				$user_upd_on 	=	$this->mysqli->real_escape_string($item['user_upd_on']);
				$status 		=	$this->mysqli->real_escape_string($item['status']);

				$sql_query = "INSERT INTO `adm_admin_customer_assign`(`ACA_Analyst_ID_FK`, `ACA_Customer_ID_FK`, `ACA_Add_User_By`, `ACA_User_Add_On`, `ACA_Updated_User_By`, `ACA_User_Updated_On`, `ACA_Status`) VALUES ('$analyst_id','$customer_id','$add_user_by','$user_add_on','$updated_user','$user_upd_on','$status')";

				$result = $this->mysqli->query($sql_query);
				if (!$result) {
					break;
				}
						
			}
			$status = array();
	        if ($result === TRUE) {
	            $status['type'] = 'success';
	            $status['msg'] = 'Added successfully';
	            return $status;
	        } else {
	            $status['type'] = 'danger';
	            $status['msg'] = "Error:" . $this->mysqli->error;
	            return $status;
	        }
			
		}
		return false;
	}
	/*----------------------- UPDATE ASSIGN CUSTOMERS ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  10-08-2019
	@RETURN                		 :  BOOLEAN   
	------------------------------------------------------------------------------*/
	public function updateAnalystToCustomers($data = array()) {
		$id 	= 	$this->mysqli->real_escape_string($data['id']);
		$analyst_id 	= 	$this->mysqli->real_escape_string($data['analyst_id']);
		$updated_user 	= 	$this->mysqli->real_escape_string($data['updated_user']);
		$user_upd_on 	= 	$this->mysqli->real_escape_string($data['user_upd_on']);

		$sql_query = "UPDATE `adm_admin_customer_assign` SET `ACA_Analyst_ID_FK`='$analyst_id',`ACA_Updated_User_By`='$updated_user',`ACA_User_Updated_On`='$user_upd_on' WHERE `ACA_ID_PK` = '$id'";
		$result = $this->mysqli->query($sql_query);
		$status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
	}
	/*----------------------- DELETE ASSIGN CUSTOMERS ---------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  10-08-2019 
	@RETURN                		 :  BOOLEAN  
	------------------------------------------------------------------------------*/
	public function delete($table, $id) {
        $sql_query = "DELETE FROM $table WHERE ACA_ID_PK ='$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'danger';
            $status['msg'] = 'Deleted successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }
    /*----------------------- BLOCK/UNBLOCK  ------------------------------------
	@ACCESS MODIFIERS            :  PUBLIC FUNCTION 
	@FUNCTION DATE               :  10-08-2019 
	@RETURN                		 :  BOOLEAN  
	------------------------------------------------------------------------------*/
    public function statusUpdate($id, $status_new) {
    	$sql_query = "UPDATE `adm_admin_customer_assign` SET `ACA_Status`='$status_new' WHERE `ACA_ID_PK` = '$id'";
		$result = $this->mysqli->query($sql_query);
		$status = array();

		if ($result === TRUE && $status_new == '5') {
            $status['type'] = 'danger';
            $status['msg'] = 'Blocked successfully';
            return $status;
        }
         if ($result === TRUE && $status_new == '1') {
        	$status['type'] = 'success';
            $status['msg'] = 'Unblocked successfully';
            return $status;
        } 
        else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }
	
}