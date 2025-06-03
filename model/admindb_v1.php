<?php

class admindb_v1 extends Model {

    public $mysqli;

    function __construct($con) {
        $this->mysqli = $con;
    }

    public function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

   

    public function get_tat_details_by_id($id) {
      

          $data = array();
          $dataclient = array();
        $sql_query = "SELECT contract_tat FROM client_details WHERE client_account_id = $id";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        $client_id = $data['contract_tat'];



        
         return $client_id;

    }


    public function get_client_id($id) {
      

          $data = array();
          $dataclient = array();
        $sql_query = "SELECT client_ids FROM client_details WHERE client_account_id = $id";

        //print_r($sql_query); die;

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        $client_id = $data['client_ids'];



        
         

         $sql_query1 = "SELECT client_number FROM clients WHERE client_id = $client_id";

        //print_r($sql_query); die;

        $result1 = $this->mysqli->query($sql_query1);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $data1 = $row1;
            }
        }
        $client_number = $data1['client_number'];
    }

    public function edit_tat_details($data = array()) {
        extract($data);
         $id = $this->mysqli->real_escape_string($id);
         $tat = $this->mysqli->real_escape_string($tat);
        $sql_query = "UPDATE studies  SET 
            actual_tat  = '$tat'
         WHERE studies_id = $id";
    
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Tat updated successfully';
            return $status;
        } else {
            $status['type'] = 'danger';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        } 
    }

     

     
}
