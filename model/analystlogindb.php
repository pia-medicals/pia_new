<?php

class analystlogindb extends Model
{
    private $connection;

    public function __construct($con)
    {
        $this->connection = $con;
    }

    public function checkLogin($email, $password)
    {
        $return = "";

        $sql_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($sql_query);
        // Bind parameters and execute
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $user = $result->fetch_object();
            if($user->is_active == "1") {

                if (password_verify($password, $user->password)) {
                    $_SESSION['user'] = $user;
                    $return = 1; // User correct
                } else {
                    $return = 2; // Password incorrect

                }
            }
            else if ($user->is_active == "0" || $user->is_active == "2") {
                $return = 4; // User inactive or blocked
            }
        } else {
            $return = 3; // No user found
        }

        // Close the statement
        $stmt->close();
        return $return;
    }
}
