<?php

class tatdb extends Model
{

    public $mysqli;

    function __construct($con)
    {
        $this->mysqli = $con;
    }

    // public function turnaround_time_add($data = array())
    // {
    //     extract($data);
    //     $new_tat = $this->mysqli->real_escape_string($new_tat);
    //     $sql_query = "INSERT INTO tat_master (tat, is_active, created_by, created_at) 
    // 				  VALUES ('$new_tat', '$is_active', '$created_by', '$created_at')";
    //     $result = $this->mysqli->query($sql_query);
    //     $status = array();

    //     if ($result === TRUE) {
    //         $status['type'] = 'success';
    //         $status['msg'] = 'New TAT created successfully';
    //         return $status;
    //     } else {
    //         $status['type'] = 'error';
    //         $status['msg'] = "Error:" . $this->mysqli->error;
    //         return $status;
    //     }
    // }

    public function turnaround_time_add($data = array())
    {
        // header('Content-Type: application/json');
        // echo json_encode($data);
        // return;

        extract($data);
        $new_tat = $this->mysqli->real_escape_string($new_tat);
        $tat_unit = $this->mysqli->real_escape_string($tat_unit);
        $created_by = $this->mysqli->real_escape_string($created_by);

        ($new_tat == "1") ? $tat_unit = "Hour" : null;

        //$tat_combined = implode(' ', [$new_tat, $tat_unit]);

        // Check if the same TAT already exists for this user
        // $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND created_by = '$created_by'";
        // $check_query = "SELECT tat FROM tat_master WHERE tat = '$tat_combined' AND created_by = '$created_by'";
        $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND tat_unit = '$tat_unit' AND is_deleted != '1' AND created_by = '$created_by'";
        $check_result = $this->mysqli->query($check_query);

        $status = array();

        if ($check_result && $check_result->num_rows > 0) {
            // TAT already exists
            $status['type'] = 'warning';
            $status['msg'] = 'TAT already exists';
            return $status;
        }

        if ((strtolower(trim($tat_unit)) === 'hours' || strtolower(trim($tat_unit)) === 'hour') && is_numeric($new_tat)) {
            $tat_min = $new_tat * 60;
        } elseif (strtolower(trim($tat_unit)) === 'minutes' && is_numeric($new_tat)) {
            $tat_min = $new_tat;
        }

        // Proceed with insert if not duplicate
        $is_active = $this->mysqli->real_escape_string($is_active);
        $created_at = $this->mysqli->real_escape_string($created_at);

        $sql_query = "INSERT INTO tat_master (tat, tat_unit, tat_in_minutes, is_active, created_by, created_at) 
                  VALUES ('$new_tat', '$tat_unit', $tat_min, '$is_active', '$created_by', '$created_at')";
        $result = $this->mysqli->query($sql_query);

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'New TAT created successfully';
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error: " . $this->mysqli->error;
        }

        return $status;
    }


    public function turnaround_time_by_id($id)
    {
        $data = [];
        $sql_query = "SELECT * FROM tat_master
 WHERE tat_id = $id";

        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data = $row;
            }
        }
        return $data;
    }

    public function turnaround_time_update($data = array())
    {
        extract($data);
        $new_tat = $this->mysqli->real_escape_string($new_tat);
        $tat_unit = $this->mysqli->real_escape_string($tat_unit);
        $created_by = $this->mysqli->real_escape_string($created_by);

        ($new_tat == "1") ? $tat_unit = "Hour" : null;

        //$tat_combined = implode(' ', [$new_tat, $tat_unit]);

        // Check if the same TAT already exists for this user
        // $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND created_by = '$created_by'";
        // $check_query = "SELECT tat FROM tat_master WHERE tat = '$tat_combined' AND created_by = '$created_by'";
        $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND tat_unit = '$tat_unit' AND is_deleted != '1' AND created_by = '$created_by'";
        $check_result = $this->mysqli->query($check_query);

        $status = array();

        if ($check_result && $check_result->num_rows > 0) {
            // TAT already exists
            $status['type'] = 'warning';
            $status['msg'] = 'TAT already exists';
            return $status;
        }

        if ((strtolower(trim($tat_unit)) === 'hours' || strtolower(trim($tat_unit)) === 'hour') && is_numeric($new_tat)) {
            $tat_min = $new_tat * 60;
        } elseif (strtolower(trim($tat_unit)) === 'minutes' && is_numeric($new_tat)) {
            $tat_min = $new_tat;
        }

        $id = $this->mysqli->real_escape_string($id);
        // $sql_query = "UPDATE tat_master SET tat='$new_tat',is_active = '$active' WHERE tat_id = '$id'";
        // $sql_query = "UPDATE tat_master SET tat='$tat_combined',is_active = '$active' WHERE tat_id = '$id'";
        $sql_query = "UPDATE tat_master SET tat='$new_tat', tat_unit = '$tat_unit', tat_in_minutes = '$tat_min', is_active = '$active' WHERE tat_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'TAT updated successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function analysesTatStatusUpdate($id, $status_new)
    {
        $sql_query = "UPDATE tat_master SET is_active='$status_new' WHERE tat_id = '$id'";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE && $status_new == '0') {
            $status['type'] = 'success';
            $status['msg'] = 'Inactivated Successfully';
            return $status;
        }
        if ($result === TRUE && $status_new == '1') {
            $status['type'] = 'success';
            $status['msg'] = 'Activated Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    public function delete($table, $id, $primary_id)
    {
        $id = $this->mysqli->real_escape_string($id);
        $sql_query = "UPDATE $table  SET is_deleted = '1' WHERE $primary_id=$id";
        $result = $this->mysqli->query($sql_query);
        $status = array();
        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'Deleted Successfully';
            return $status;
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error:" . $this->mysqli->error;
            return $status;
        }
    }

    // public function fetchTurnAroundTimes($search)
    // {
    //     $sql = "SELECT tat_id, tat FROM tat_master WHERE is_active = 1 AND is_deleted = 0";
    //     $params = [];

    //     if (!empty($search)) {
    //         $sql .= " AND tat LIKE ?";
    //         $params[] = "%$search%";
    //     }

    //     $stmt = $this->mysqli->prepare($sql);

    //     if (!empty($search)) {
    //         $stmt->bind_param("s", $params[0]);
    //     }

    //     $stmt->execute();
    //     $stmt->bind_result($tat_id, $tat);

    //     $items = [];
    //     while ($stmt->fetch()) {
    //         $items[] = [
    //             "id" => $tat, // Set tat value as option value
    //             "text" => $tat // Display tat as option text
    //         ];
    //     }

    //     $stmt->close();

    //     return [
    //         "items" => $items
    //     ];
    // }

    // public function fetchTurnAroundTimes()
    // {
    //     $sql_query = "SELECT tat_id, tat FROM tat_master WHERE is_active = 1 AND is_deleted = 0";
    //     $result = $this->mysqli->query($sql_query);

    //     $items = [];
    //     if ($result) {
    //         while ($row = $result->fetch_assoc()) {
    //             $items[] = [
    //                 "id" => $row['tat_id'], // Using tat_id as value
    //                 "text" => $row['tat']    // Display tat as option text
    //             ];
    //         }
    //     } else {
    //         error_log("SQL Error: " . $this->mysqli->error);
    //     }

    //     return ["items" => $items];
    // }

    public function fetchTurnAroundTimes()
    {
        $sql_query = "SELECT tat_id, tat FROM tat_master WHERE is_active = 1 AND is_deleted = 0";
        $result = $this->mysqli->query($sql_query);

        if (!$result) {
            // error_log("SQL Error: " . $this->mysqli->error);
            // return ["items" => []]; // Return empty items on error
            $errorMessage = "SQL Error: " . $this->mysqli->error;
            error_log($errorMessage);
            return ["error" => $errorMessage];
        }

        // Debugging: Check the number of rows returned
        error_log("Number of rows: " . $result->num_rows);

        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = [
                    "id" => $row['tat_id'],
                    "text" => $row['tat']
                ];
            }
        }

        return ["items" => $items];
    }

    public function getAnalysesCategorySELOPT()
    {
        $data = array();
        // $sql_query = "SELECT category_id, category_name FROM analyses_category WHERE is_active = '1' AND is_deleted != '1' ORDER BY category_name ASC";
        $sql_query = "SELECT category_id, category_name FROM analyses_category WHERE is_active = '1' AND is_deleted != '1' ORDER BY category_name ASC";
        $result = $this->mysqli->query($sql_query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            $data = false;
        }
        return $data;
    }
}
