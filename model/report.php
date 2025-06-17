<?php 


class report extends Model
{
	public $mysqli;

	function __construct($con) {
		$this->mysqli = $con;
	}

	public function upload_file_register($data){
		
		
		$sql_query = "INSERT INTO `Reports` (`file_name`) VALUES ('$data')";
		$result = $this->mysqli->query($sql_query);
		$status = array();
		if ($result === TRUE) {
			$status['type'] = 'success';
			$status['msg'] = 'Report Successfully Uploaded';
		    return  $status;
		} else {
			$status['type'] = 'danger';
			$status['msg'] = "Error:".$this->mysqli->error;
		    return  $status;
		}

  }

}

?>
