<?php

/**
 * Mysql Connections
 */
class logindb extends Model
{
	
	
	private $connection; 
	public function __construct($con) {

		$this->connection = $con;
		//print_r($con);die();

	}





	public function checkLogin($email,$password){

		
		$return = "";
		$sql_query = "SELECT * FROM users WHERE email='$email'";
		$result = $this->connection->query($sql_query);
    	if ($result->num_rows == 1) {
    		while ($obj = $result->fetch_object()) {
             	if(password_verify($password, $obj->password)){
            		$_SESSION['user'] = $obj;
            		$return = 1;//user correct
            	}else {
            		$return = 2;//password icorrect
            	}
       		 }
    	}else{
    		$return = 3;//no user found
    	}
    	
    	return $return;



    // $return = "";

    // // Query the database using CodeIgniter's Query Builder
    // $query = $this->db->get_where('users', ['email' => $email]);

    // if ($query->num_rows() == 1) {
    //     $user = $query->row();

    //     // Verify the password
    //     if (password_verify($password, $user->password)) {
    //         // Store user data in session
    //         $this->session->set_userdata('user', $user);
    //         $_SESSION['user'] = $user;
    //         $return = 1; // User credentials are correct
    //     } else {
    //         $return = 2; // Password is incorrect
    //     }
    // } else {
    //     $return = 3; // No user found with that email
    // }

    // return $return;
    	
    
	}

    //resetPassword

    public function resetPassword($password,$userID){

        $last_reset = date("Y-m-d H:i:s");

        $sql_query = "UPDATE `users` SET 
            last_reset = '$last_reset' ,
            password = '$password'
         WHERE id = $userID";

        $result = $this->connection->query($sql_query);
        if($result){
            $sql_query1 = "SELECT * FROM users WHERE id='$userID'";
            $result1 = $this->connection->query($sql_query1);
            if ($result1->num_rows == 1) {
                while ($obj = $result1->fetch_object()) {
                    
                    $_SESSION['user'] = $obj;
                    $return = 1;//user correct
                    
                 }
            }
        }
        

        return $result;
    }

}


?>