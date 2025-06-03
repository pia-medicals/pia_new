<?php

class customerlogindb extends Model
{
    private $connection;

    public function __construct($con)
    {
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

}
