<?php

class logindbV2 extends Model {

    private $connection;

    public function __construct($con) {
        $this->connection = $con;
    }

    public function checkLogin($email, $password) {
        $return = "";

        $sql_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($sql_query);
        
        // Bind parameters and execute
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $user = $result->fetch_object();

            if (password_verify($password, $user->password)) {
                $_SESSION['user'] = $user;
                $return = 1; // User correct
            } else {
                $return = 2; // Password incorrect

            }
        } else {
            $return = 3; // No user found
        }

        // Close the statement
        $stmt->close();
        return $return;
    }

    //resetPassword

      public function resetPassword($password, $userID)
    {
        $last_reset = date("Y-m-d H:i:s");

        //$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Use a prepared statement for the update
        $sql_query = "UPDATE users SET last_reset = ?, password = ? WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql_query);
        
        // Bind parameters
        $stmt->bind_param("ssi", $last_reset, $password, $userID);
        
        // Execute the update
        $result = $stmt->execute();
        
        if ($result) {
            // Fetch the updated user
            $sql_query1 = "SELECT * FROM users WHERE user_id = ?";
            $stmt1 = $this->connection->prepare($sql_query1);
            $stmt1->bind_param("i", $userID);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            

            if ($result1->num_rows == 1) {
                $_SESSION['user'] = $result1->fetch_object();
                $return = 1; // User updated successfully
            } else {
                $return = 0; // User not found after update
            }
            $stmt1->close();
        } else {
            $return = 0; // Update failed
        }

        $stmt->close();
        return $return;
    }

}

?>