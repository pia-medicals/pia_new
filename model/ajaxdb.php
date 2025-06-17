<?php



class ajaxdb extends Model
{ 
	public $mysqli;
	function __construct($con) {
		$this->mysqli = $con;
	}






	public function user_obj($email){

		$sql_query = "SELECT * FROM users WHERE email='$email'";
		$result = $this->mysqli->query($sql_query);
    	if ($result->num_rows == 1) {
    		while ($obj = $result->fetch_object()) {
            
            		$return = $obj;
       		 }
    	}else $return = false;
    	
    	return $return;
    	
    
	}


}