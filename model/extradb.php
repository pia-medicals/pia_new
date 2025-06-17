<?php

class extradb extends Model
{

    public $mysqli;

    function __construct($con)
    {
        $this->mysqli = $con;
    }

    public function debug($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public function user_obj($email)
    {
        $sql_query = "SELECT * FROM users WHERE email='$email'";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows == 1) {
            while ($obj = $result->fetch_object()) {

                $return = $obj;
            }
        } else
            $return = false;
        return $return;
    }


    public function edit_tat_details($tat, $id)
    {
        $tat = $this->mysqli->real_escape_string($tat);
        $id = $this->mysqli->real_escape_string($id);
        //$sql_query = "DELETE FROM $table WHERE id=$id";
        $sql_query = "UPDATE studies  SET actual_tat = $tat WHERE studies_id=$id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Updated Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }


}
