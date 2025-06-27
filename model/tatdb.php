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

    /*public function turnaround_time_add($data = array())
    {
        // header('Content-Type: application/json');
        // echo json_encode($data);
        // return;

        extract($data);
        $new_tat = $this->mysqli->real_escape_string($new_tat);
        $tat_unit = $this->mysqli->real_escape_string($tat_unit);
        $created_by = $this->mysqli->real_escape_string($created_by);

        ($new_tat == "1" && $tat_unit == "Hours") ? $tat_unit = "Hour" : null;
        ($new_tat == "1" && $tat_unit == "Minutes") ? $tat_unit = "Minute" : null;

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

        if ($new_tat != "1" && $tat_unit != "Minute" || "Minutes") {
            if ((strtolower(trim($tat_unit)) === 'hours' || strtolower(trim($tat_unit)) === 'hour') && is_numeric($new_tat)) {
                $tat_min = $new_tat * 60;
            } elseif (strtolower(trim($tat_unit)) === 'minutes' && is_numeric($new_tat)) {
                $tat_min = $new_tat;
            }
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
    } */

    public function turnaround_time_add($data = array())
    {
        extract($data);
        $new_tat = $this->mysqli->real_escape_string($new_tat);
        $tat_unit = $this->mysqli->real_escape_string($tat_unit);
        $created_by = $this->mysqli->real_escape_string($created_by);

        // Normalize singular/plural units
        if ($new_tat == "1" && $tat_unit == "Hours") {
            $tat_unit = "Hour";
        } elseif ($new_tat == "1" && $tat_unit == "Minutes") {
            $tat_unit = "Minute";
        }

        // Check for duplicates
        $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND tat_unit = '$tat_unit' AND is_deleted != '1' AND created_by = '$created_by'";
        $check_result = $this->mysqli->query($check_query);

        $status = array();

        if ($check_result && $check_result->num_rows > 0) {
            $status['type'] = 'warning';
            $status['msg'] = 'TAT already exists';
            return $status;
        }

        // Normalize unit once
        $tat_unit_normalized = strtolower(trim($tat_unit));

        // Calculate minutes if unit is hour(s) or minute(s)
        // if ($new_tat != "1" && $tat_unit_normalized !== "minute" && $tat_unit_normalized !== "minutes") {
        //     if (($tat_unit_normalized === 'hours' || $tat_unit_normalized === 'hour') && is_numeric($new_tat)) {
        //         $tat_min = $new_tat * 60;
        //     } elseif ($tat_unit_normalized === 'minutes' && is_numeric($new_tat)) {
        //         $tat_min = $new_tat;
        //     } else {
        //         $tat_min = 0;
        //     }
        // } else {
        //     // fallback if the unit is exactly 'minute' or 'minutes'
        //     $tat_min = ($tat_unit_normalized === 'minute' || $tat_unit_normalized === 'minutes') && is_numeric($new_tat) ? $new_tat : 0;
        // }

        if (($tat_unit_normalized === 'hour' || $tat_unit_normalized === 'hours') && is_numeric($new_tat)) {
            $tat_min = $new_tat * 60;
        } elseif (($tat_unit_normalized === 'minute' || $tat_unit_normalized === 'minutes') && is_numeric($new_tat)) {
            $tat_min = $new_tat;
        } else {
            $tat_min = 0;
        }

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

    // public function turnaround_time_update($data = array())
    // {
    //     extract($data);
    //     $new_tat = $this->mysqli->real_escape_string($new_tat);
    //     $tat_unit = $this->mysqli->real_escape_string($tat_unit);
    //     $created_by = $this->mysqli->real_escape_string($created_by);

    //     //($new_tat == "1") ? $tat_unit = "Hour" : null;

    //     //$tat_combined = implode(' ', [$new_tat, $tat_unit]);

    //     if ($new_tat == "1" && $tat_unit == "Hours") {
    //         $tat_unit = "Hour";
    //     } elseif ($new_tat == "1" && $tat_unit == "Minutes") {
    //         $tat_unit = "Minute";
    //     }

    //     // Check if the same TAT already exists for this user
    //     // $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND created_by = '$created_by'";
    //     // $check_query = "SELECT tat FROM tat_master WHERE tat = '$tat_combined' AND created_by = '$created_by'";
    //     $check_query = "SELECT tat FROM tat_master WHERE tat = '$new_tat' AND tat_unit = '$tat_unit' AND is_deleted != '1' AND created_by = '$created_by'";
    //     $check_result = $this->mysqli->query($check_query);

    //     $status = array();

    //     if ($check_result && $check_result->num_rows > 0) {
    //         // TAT already exists
    //         $status['type'] = 'warning';
    //         $status['msg'] = 'TAT already exists';
    //         return $status;
    //     }

    //     /*if ((strtolower(trim($tat_unit)) === 'hours' || strtolower(trim($tat_unit)) === 'hour') && is_numeric($new_tat)) {
    //         $tat_min = $new_tat * 60;
    //     } elseif (strtolower(trim($tat_unit)) === 'minutes' && is_numeric($new_tat)) {
    //         $tat_min = $new_tat;
    //     } */

    //     // Normalize unit once
    //     $tat_unit_normalized = strtolower(trim($tat_unit));

    //     // Calculate minutes if unit is hour(s) or minute(s)
    //     if ($new_tat != "1" && $tat_unit_normalized !== "minute" && $tat_unit_normalized !== "minutes") {
    //         if (($tat_unit_normalized === 'hours' || $tat_unit_normalized === 'hour') && is_numeric($new_tat)) {
    //             $tat_min = $new_tat * 60;
    //         } elseif ($tat_unit_normalized === 'minutes' && is_numeric($new_tat)) {
    //             $tat_min = $new_tat;
    //         } else {
    //             $tat_min = 0;
    //         }
    //     } else {
    //         // fallback if the unit is exactly 'minute' or 'minutes'
    //         $tat_min = ($tat_unit_normalized === 'minute' || $tat_unit_normalized === 'minutes') && is_numeric($new_tat) ? $new_tat : 0;
    //     }

    //     $id = $this->mysqli->real_escape_string($id);
    //     // $sql_query = "UPDATE tat_master SET tat='$new_tat',is_active = '$active' WHERE tat_id = '$id'";
    //     // $sql_query = "UPDATE tat_master SET tat='$tat_combined',is_active = '$active' WHERE tat_id = '$id'";
    //     $sql_query = "UPDATE tat_master SET tat='$new_tat', tat_unit = '$tat_unit', tat_in_minutes = '$tat_min', is_active = '$active' WHERE tat_id = '$id'";
    //     $result = $this->mysqli->query($sql_query);
    //     $status = array();
    //     if ($result === TRUE) {
    //         $status['type'] = 'success';
    //         $status['msg'] = 'TAT updated successfully';
    //         return $status;
    //     } else {
    //         $status['type'] = 'error';
    //         $status['msg'] = "Error:" . $this->mysqli->error;
    //         return $status;
    //     }
    // }

    public function turnaround_time_update($data = array())
    {
        extract($data);

        // Escape input data
        $id = $this->mysqli->real_escape_string($id);
        $new_tat = $this->mysqli->real_escape_string($new_tat);
        $tat_unit = $this->mysqli->real_escape_string($tat_unit);
        $created_by = $this->mysqli->real_escape_string($created_by);
        $active = $this->mysqli->real_escape_string($active);

        // Normalize singular/plural units
        if ($new_tat == "1" && $tat_unit == "Hours") {
            $tat_unit = "Hour";
        } elseif ($new_tat == "1" && $tat_unit == "Minutes") {
            $tat_unit = "Minute";
        }

        // Check for duplicates, excluding the current record
        $check_query = "SELECT tat FROM tat_master 
                    WHERE tat = '$new_tat' 
                      AND tat_unit = '$tat_unit' 
                      AND is_deleted != '1' 
                      AND created_by = '$created_by' 
                      AND tat_id != '$id'";
        $check_result = $this->mysqli->query($check_query);

        $status = array();

        if ($check_result && $check_result->num_rows > 0) {
            $status['type'] = 'warning';
            $status['msg'] = 'TAT already exists';
            return $status;
        }

        // Normalize and calculate minutes
        $tat_unit_normalized = strtolower(trim($tat_unit));

        // if ($new_tat != "1" && $tat_unit_normalized !== "minute" && $tat_unit_normalized !== "minutes") {
        //     if (($tat_unit_normalized === 'hours' || $tat_unit_normalized === 'hour') && is_numeric($new_tat)) {
        //         $tat_min = $new_tat * 60;
        //     } elseif ($tat_unit_normalized === 'minutes' && is_numeric($new_tat)) {
        //         $tat_min = $new_tat;
        //     } else {
        //         $tat_min = 0;
        //     }
        // } else {
        //     $tat_min = ($tat_unit_normalized === 'minute' || $tat_unit_normalized === 'minutes') && is_numeric($new_tat) ? $new_tat : 0;
        // }

        if (($tat_unit_normalized === 'hour' || $tat_unit_normalized === 'hours') && is_numeric($new_tat)) {
            $tat_min = $new_tat * 60;
        } elseif (($tat_unit_normalized === 'minute' || $tat_unit_normalized === 'minutes') && is_numeric($new_tat)) {
            $tat_min = $new_tat;
        } else {
            $tat_min = 0;
        }

        // Perform update
        $sql_query = "UPDATE tat_master 
                  SET tat = '$new_tat', 
                      tat_unit = '$tat_unit', 
                      tat_in_minutes = '$tat_min', 
                      is_active = '$active' 
                  WHERE tat_id = '$id'";
        $result = $this->mysqli->query($sql_query);

        if ($result === TRUE) {
            $status['type'] = 'success';
            $status['msg'] = 'TAT updated successfully';
        } else {
            $status['type'] = 'error';
            $status['msg'] = "Error: " . $this->mysqli->error;
        }

        return $status;
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

    // Used in new code
    public function add_subscription_id($client_acc_id, $sub_amount, $created_by)
    {

        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $sub_amount = $this->mysqli->real_escape_string($sub_amount);
        $created_by = $this->mysqli->real_escape_string($created_by);

        // STEP 1: Insert into subscription only once
        $insert_subscription_query = "INSERT INTO subscription (
            client_account_ids, subscription_price, created_by
        ) VALUES (
            '$client_acc_id', '$sub_amount', '$created_by'
        )";

        if (!$this->mysqli->query($insert_subscription_query)) {
            throw new Exception("Failed to insert into subscription: " . $this->mysqli->error);
        }

        $subscription_id = $this->mysqli->insert_id;

        return $subscription_id;
    }

    public function update_subscription_amount($client_acc_id, $subscription_id, $sub_amount, $created_by)
    {
        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $subscription_id = $this->mysqli->real_escape_string($subscription_id);
        $sub_amount = $this->mysqli->real_escape_string($sub_amount);
        $created_by = $this->mysqli->real_escape_string($created_by);

        $update_subscription_query = "
        UPDATE subscription 
        SET subscription_price = '$sub_amount' 
        WHERE subscription_id = '$subscription_id' 
          AND client_account_ids = '$client_acc_id'
    ";

        if ($this->mysqli->query($update_subscription_query)) {
            return true;
        } else {
            // throw new Exception("Failed to update the subscription: " . $this->mysqli->error);
            return false;
        }
    }


    // public function save_added_subscriptions($client_acc_id, $subscription_id, $created_by, $analysis_id, $table_id, $analysis_price, $analysis_desc, $itemNumber, $analysisName, $count)
    // {
    //     $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
    //     $subscription_id = $this->mysqli->real_escape_string($subscription_id);
    //     $created_by = $this->mysqli->real_escape_string($created_by);
    //     $analysis_id = $this->mysqli->real_escape_string($analysis_id);
    //     $table_id = $this->mysqli->real_escape_string($table_id);
    //     $analysis_price = $this->mysqli->real_escape_string($analysis_price);
    //     $analysis_desc = $this->mysqli->real_escape_string($analysis_desc);
    //     $itemNumber = $this->mysqli->real_escape_string($itemNumber);
    //     $analysisName = $this->mysqli->real_escape_string($analysisName);
    //     $count = $this->mysqli->real_escape_string($count);
    //     $active = '1';


    //     $this->mysqli->begin_transaction(); // START TRANSACTION

    //     try {


    //         if ($table_id === 'parent') {

    //             //Flaw fixing code
    //             $sql = "SELECT analysis_client_price_id FROM analyses_client_price_details WHERE client_account_ids = '$client_acc_id' AND analysis_id = '$analysis_id' AND is_active != '0' AND is_deleted != '1'";
    //             $result = $this->mysqli->query($sql);

    //             if ($result && $row = $result->fetch_assoc()) {
    //                 $acpd_id = $row['analysis_client_price_id'];
    //             }

    //             if ($acpd_id) {
    //                 // STEP 2: Update analyses_client_price_details
    //                 $update_acpd_query = "UPDATE analyses_client_price_details SET
    //                 analysis_name = '$analysisName',
    //                 analysis_invoicing_description = '$analysis_desc',
    //                 analysis_client_price = '$analysis_price',
    //                 analysis_code = '$itemNumber'
    //                 WHERE client_account_ids = '$client_acc_id'
    //                 AND analysis_client_price_id = '$acpd_id'";

    //                 if (!$this->mysqli->query($update_acpd_query)) {
    //                     throw new Exception("Failed to update analyses_client_price_details subscription in  parent->client: " . $this->mysqli->error);
    //                 }

    //                 // STEP 3: Insert into subscription_contents using same subscription_id
    //                 $insert_content_query = "INSERT INTO subscription_contents (
    //                 subscription_ids, analysis_client_price_ids,
    //                 subscription_volume, created_by
    //                 ) VALUES (
    //                 '$subscription_id', '$acpd_id', '$count', '$created_by'
    //                 )";

    //                 if (!$this->mysqli->query($insert_content_query)) {
    //                     throw new Exception("Failed to insert into subscription_contents in parent->client: " . $this->mysqli->error);
    //                 }
    //             } else if (!$acpd_id) {
    //                 // STEP 2: Insert into analyses_client_price_details
    //                 $insert_acpd_query = "INSERT INTO analyses_client_price_details (
    //                 client_account_ids, analysis_id, analysis_name,
    //                 analysis_invoicing_description, analysis_client_price, analysis_code, is_active
    //                 ) VALUES (
    //                 '$client_acc_id', '$analysis_id', '$analysisName',
    //                 '$analysis_desc', '$analysis_price', '$itemNumber', '$active'
    //                 )";

    //                 if (!$this->mysqli->query($insert_acpd_query)) {
    //                     throw new Exception("Failed to insert into analyses_client_price_details: " . $this->mysqli->error);
    //                 }

    //                 $acpd_id = $this->mysqli->insert_id;

    //                 // STEP 3: Insert into subscription_contents using same subscription_id
    //                 $insert_content_query = "INSERT INTO subscription_contents (
    //                 subscription_ids, analysis_client_price_ids,
    //                 subscription_volume, created_by
    //                 ) VALUES (
    //                 '$subscription_id', '$acpd_id', '$count', '$created_by'
    //                 )";

    //                 if (!$this->mysqli->query($insert_content_query)) {
    //                     throw new Exception("Failed to insert into subscription_contents: " . $this->mysqli->error);
    //                 }
    //             }
    //         } elseif ($table_id === 'client') {
    //             // STEP 2: Update analyses_client_price_details
    //             $update_acpd_query = "UPDATE analyses_client_price_details SET
    //             analysis_name = '$analysisName',
    //             analysis_invoicing_description = '$analysis_desc',
    //             analysis_client_price = '$analysis_price',
    //             analysis_code = '$itemNumber'
    //             WHERE client_account_ids = '$client_acc_id'
    //             AND analysis_client_price_id = '$analysis_id'";

    //             if (!$this->mysqli->query($update_acpd_query)) {
    //                 throw new Exception("Failed to update analyses_client_price_details: " . $this->mysqli->error);
    //             }

    //             // STEP 3: Insert into subscription_contents using same subscription_id
    //             $insert_content_query = "INSERT INTO subscription_contents (
    //             subscription_ids, analysis_client_price_ids,
    //             subscription_volume, created_by
    //             ) VALUES (
    //             '$subscription_id', '$analysis_id', '$count', '$created_by'
    //             )";

    //             if (!$this->mysqli->query($insert_content_query)) {
    //                 throw new Exception("Failed to insert into subscription_contents: " . $this->mysqli->error);
    //             }
    //         } else {
    //             throw new Exception("Invalid table_id specified.");
    //         }

    //         $this->mysqli->commit(); // COMMIT TRANSACTION
    //         return true;
    //     } catch (Exception $e) {
    //         // $this->mysqli->rollback(); // ROLLBACK on error
    //         // error_log("Transaction failed: " . $e->getMessage());
    //         // return false;
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function save_added_subscriptions($client_acc_id, $subscription_id, $created_by, $analysis_id, $table_id, $analysis_price, $analysis_desc, $itemNumber, $analysisName, $count)
    {
        header('Content-Type: application/json'); // Ensure JSON response if error is caught here

        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $subscription_id = $this->mysqli->real_escape_string($subscription_id);
        $created_by = $this->mysqli->real_escape_string($created_by);
        $analysis_id = $this->mysqli->real_escape_string($analysis_id);
        $table_id = $this->mysqli->real_escape_string($table_id);
        $analysis_price = $this->mysqli->real_escape_string($analysis_price);
        $analysis_desc = $this->mysqli->real_escape_string($analysis_desc);
        $itemNumber = $this->mysqli->real_escape_string($itemNumber);
        $analysisName = $this->mysqli->real_escape_string($analysisName);
        $count = $this->mysqli->real_escape_string($count);
        $active = '1';

        $this->mysqli->begin_transaction();

        try {
            if ($table_id === 'parent') {
                $sql = "SELECT analysis_client_price_id FROM analyses_client_price_details WHERE client_account_ids = '$client_acc_id' AND analysis_id = '$analysis_id' AND is_active != '0' AND is_deleted != '1'";
                $result = $this->mysqli->query($sql);
                $acpd_id = null;

                if ($result && $row = $result->fetch_assoc()) {
                    $acpd_id = $row['analysis_client_price_id'];
                }

                if ($acpd_id) {
                    $update_acpd_query = "UPDATE analyses_client_price_details SET
                    analysis_name = '$analysisName',
                    analysis_invoicing_description = '$analysis_desc',
                    analysis_client_price = '$analysis_price',
                    analysis_code = '$itemNumber'
                    WHERE client_account_ids = '$client_acc_id'
                    AND analysis_client_price_id = '$acpd_id'";

                    if (!$this->mysqli->query($update_acpd_query)) {
                        throw new Exception("Failed to update analyses_client_price_details in parent->client: " . $this->mysqli->error);
                    }

                    $insert_content_query = "INSERT INTO subscription_contents (
                    subscription_ids, analysis_client_price_ids,
                    subscription_volume, created_by
                    ) VALUES (
                    '$subscription_id', '$acpd_id', '$count', '$created_by'
                    )";

                    if (!$this->mysqli->query($insert_content_query)) {
                        throw new Exception("Failed to insert into subscription_contents in parent->client: " . $this->mysqli->error);
                    }
                } else {
                    $insert_acpd_query = "INSERT INTO analyses_client_price_details (
                    client_account_ids, analysis_id, analysis_name,
                    analysis_invoicing_description, analysis_client_price, analysis_code, is_active
                    ) VALUES (
                    '$client_acc_id', '$analysis_id', '$analysisName',
                    '$analysis_desc', '$analysis_price', '$itemNumber', '$active'
                    )";

                    if (!$this->mysqli->query($insert_acpd_query)) {
                        throw new Exception("Failed to insert into analyses_client_price_details: " . $this->mysqli->error);
                    }

                    $acpd_id = $this->mysqli->insert_id;

                    $insert_content_query = "INSERT INTO subscription_contents (
                    subscription_ids, analysis_client_price_ids,
                    subscription_volume, created_by
                    ) VALUES (
                    '$subscription_id', '$acpd_id', '$count', '$created_by'
                    )";

                    if (!$this->mysqli->query($insert_content_query)) {
                        throw new Exception("Failed to insert into subscription_contents: " . $this->mysqli->error);
                    }
                }
            } elseif ($table_id === 'client') {
                $update_acpd_query = "UPDATE analyses_client_price_details SET
                analysis_name = '$analysisName',
                analysis_invoicing_description = '$analysis_desc',
                analysis_client_price = '$analysis_price',
                analysis_code = '$itemNumber'
                WHERE client_account_ids = '$client_acc_id'
                AND analysis_client_price_id = '$analysis_id'";

                if (!$this->mysqli->query($update_acpd_query)) {
                    throw new Exception("Failed to update analyses_client_price_details: " . $this->mysqli->error);
                }

                $insert_content_query = "INSERT INTO subscription_contents (
                subscription_ids, analysis_client_price_ids,
                subscription_volume, created_by
                ) VALUES (
                '$subscription_id', '$analysis_id', '$count', '$created_by'
                )";

                if (!$this->mysqli->query($insert_content_query)) {
                    throw new Exception("Failed to insert into subscription_contents: " . $this->mysqli->error);
                }
            } else {
                throw new Exception("Invalid table_id specified.");
            }

            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            $this->mysqli->rollback(); // Ensure rollback on error
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }


    public function get_subscription_id_by_client($client_id)
    {

        // header('Content-Type: application/json');
        // echo json_encode($client_id);
        // return;

        $client_id = $this->mysqli->real_escape_string($client_id);

        $query = "SELECT subscription_id FROM subscription WHERE client_account_ids = '$client_id' AND is_deleted != '1'";
        $result = $this->mysqli->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            return $row['subscription_id'];
        }


        return false;
    }

    public function is_analysis_subscribed($subscription_id, $analysis_id)
    {

        $subscription_id = $this->mysqli->real_escape_string($subscription_id);
        $analysis_id = $this->mysqli->real_escape_string($analysis_id);

        $query = "SELECT subscription_content_id FROM subscription_contents 
              WHERE subscription_ids = '$subscription_id' AND analysis_client_price_ids = '$analysis_id' AND is_deleted != '1'";

        $result = $this->mysqli->query($query);

        return ($result && $result->num_rows > 0);
    }

    public function update_susbscribed_analysis($acpd_id, $client_acc_id, $subanalysis_name)
    {
        $acpd_id = $this->mysqli->real_escape_string($acpd_id);
        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $subanalysis_name = $this->mysqli->real_escape_string($subanalysis_name);

        $sql = "UPDATE analyses_client_price_details SET
                analysis_name = '$subanalysis_name'
            WHERE client_account_ids = '$client_acc_id'
              AND analysis_client_price_id = '$acpd_id'";

        if (!$this->mysqli->query($sql)) {
            throw new Exception("Failed to update analyses_client_price_details: " . $this->mysqli->error);
        }
    }

    public function update_sub_count($subct_id, $sub_id, $acpd_id, $subanalysis_count)
    {
        $subct_id = $this->mysqli->real_escape_string($subct_id);
        $sub_id = $this->mysqli->real_escape_string($sub_id);
        $acpd_id = $this->mysqli->real_escape_string($acpd_id);
        $subanalysis_count = $this->mysqli->real_escape_string($subanalysis_count);

        $sql = "UPDATE subscription_contents SET subscription_volume = '$subanalysis_count'
                WHERE subscription_content_id = '$subct_id' AND subscription_ids = '$sub_id' AND analysis_client_price_ids = '$acpd_id'";
        if (!$this->mysqli->query($sql)) {
            throw new Exception("Failed to update subscription_contents: " . $this->mysqli->error);
        }
    }

    public function delete_analysis_subscription($subct_id, $sub_id, $acpd_id)
    {
        $subct_id = $this->mysqli->real_escape_string($subct_id);
        $sub_id = $this->mysqli->real_escape_string($sub_id);
        $acpd_id = $this->mysqli->real_escape_string($acpd_id);

        $sql = "UPDATE subscription_contents SET is_deleted = '1'
                WHERE subscription_content_id = '$subct_id' AND subscription_ids = '$sub_id' AND analysis_client_price_ids = '$acpd_id'";
        if (!$this->mysqli->query($sql)) {
            throw new Exception("Failed to delete subscription: " . $this->mysqli->error);
        }
    }

    public function get_acpdid_by_client($client_account_id, $analysis_value)
    {
        $client_acc_id = $this->mysqli->real_escape_string($client_account_id);
        $analysis_id = $this->mysqli->real_escape_string($analysis_value);

        $sql = "SELECT analysis_client_price_id FROM analyses_client_price_details WHERE client_account_ids = '$client_acc_id' AND analysis_id = '$analysis_id' AND is_active != '0' AND is_deleted != '1'";
        $result = $this->mysqli->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            return $row['analysis_client_price_id'];
        }

        return false;
    }

    public function add_new_monthly_fees($client_acc_id, $analysisName, $analysis_id, $table_id, $itemNumber, $analysis_price, $analysis_desc, $monthly_fee, $created_by)
    {
        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $analysisName = $this->mysqli->real_escape_string($analysisName);
        $analysis_id = $this->mysqli->real_escape_string($analysis_id);
        $table_id = $this->mysqli->real_escape_string($table_id);
        $itemNumber = $this->mysqli->real_escape_string($itemNumber);
        $analysis_price = $this->mysqli->real_escape_string($analysis_price);
        $analysis_desc = $this->mysqli->real_escape_string($analysis_desc);
        $monthly_fee_type = 'Monthly';
        $monthly_fee = $this->mysqli->real_escape_string($monthly_fee);
        $created_by = $this->mysqli->real_escape_string($created_by);

        if ($table_id == "parent") {
            $sql1 = "SELECT analysis_client_price_id FROM analyses_client_price_details WHERE client_account_ids = '$client_acc_id' AND analysis_id = '$analysis_id' AND is_deleted != '1' LIMIT 1";
            $result = $this->mysqli->query($sql1);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $analysis_client_price_id = $row['analysis_client_price_id'];

                $sql2 = "INSERT INTO maintenance_fees (client_account_ids, analysis_client_price_ids, maintenance_fee_type, maintenance_fee_amount, created_by)
                     VALUES ('$client_acc_id', '$analysis_client_price_id', '$monthly_fee_type', '$monthly_fee', '$created_by')";

                if ($this->mysqli->query($sql2)) {
                    return true;
                } else {
                    return "Maintenance Fee Insert Error: " . $this->mysqli->error;
                }
            } else {
                $is_active = '1';
                $sql2 = "INSERT INTO analyses_client_price_details (client_account_ids, analysis_id, analysis_name, analysis_invoicing_description, analysis_client_price, analysis_code, is_active)
                     VALUES ('$client_acc_id', '$analysis_id', '$analysisName', '$analysis_desc', '$analysis_price', '$itemNumber', '$is_active')";

                if ($this->mysqli->query($sql2)) {
                    $new_id = $this->mysqli->insert_id;
                    $sql3 = "INSERT INTO maintenance_fees (client_account_ids, analysis_client_price_ids, maintenance_fee_type, maintenance_fee_amount, created_by)
                         VALUES ('$client_acc_id', '$new_id', '$monthly_fee_type', '$monthly_fee', '$created_by')";

                    if ($this->mysqli->query($sql3)) {
                        return true;
                    } else {
                        return "Maintenance Fee Insert Error (after new price): " . $this->mysqli->error;
                    }
                } else {
                    return "Price Details Insert Error: " . $this->mysqli->error;
                }
            }
        } else if ($table_id == "client") {
            $sql2 = "INSERT INTO maintenance_fees (client_account_ids, analysis_client_price_ids, maintenance_fee_type, maintenance_fee_amount, created_by)
                 VALUES ('$client_acc_id', '$analysis_id', '$monthly_fee_type', '$monthly_fee', '$created_by')";

            if ($this->mysqli->query($sql2)) {
                return true;
            } else {
                return "Maintenance Fee Insert Error (client): " . $this->mysqli->error;
            }
        } else {
            return "Invalid table_id value.";
        }
    }

    public function update_analysis_maintenance_fees($aprice_id, $mainfee_id, $client_acc_id, $amdesc, $acprice, $amprice)
    {
        $aprice_id = $this->mysqli->real_escape_string($aprice_id);
        $mainfee_id = $this->mysqli->real_escape_string($mainfee_id);
        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $amdesc = $this->mysqli->real_escape_string($amdesc);
        $acprice = $this->mysqli->real_escape_string($acprice);
        $amprice = $this->mysqli->real_escape_string($amprice);

        $sql1 = "UPDATE analyses_client_price_details SET analysis_invoicing_description = '$amdesc', analysis_client_price = '$acprice'
                WHERE analysis_client_price_id = '$aprice_id' AND client_account_ids = '$client_acc_id'";
        if ($this->mysqli->query($sql1)) {
            $sql2 = "UPDATE maintenance_fees SET maintenance_fee_amount = '$amprice'
                WHERE maintenance_fees_id = '$mainfee_id' AND client_account_ids = '$client_acc_id' AND analysis_client_price_ids = '$aprice_id'";
            if ($this->mysqli->query($sql2)) {
                return true;
            } else {
                return "Error in Maintenance Fee Update: " . $this->mysqli->error;
            }
        } else {
            return "Error in Analysis Price Update: " . $this->mysqli->error;
        }
    }

    public function delete_maintenancefee($aprice_id, $mainfee_id, $client_acc_id)
    {
        $aprice_id = $this->mysqli->real_escape_string($aprice_id);
        $mainfee_id = $this->mysqli->real_escape_string($mainfee_id);
        $client_acc_id = $this->mysqli->real_escape_string($client_acc_id);
        $sql = "UPDATE maintenance_fees SET is_deleted = '1'
                WHERE maintenance_fees_id = '$mainfee_id' AND client_account_ids = '$client_acc_id' AND analysis_client_price_ids = '$aprice_id'";
        if ($this->mysqli->query($sql)) {
            return true;
        } else {
            return "Error while Deleting Maintenance Fee: " . $this->mysqli->error;
        }
    }

    public function get_active_clients()
    {
	// Ensure the MySQL connection is set to UTF-8 encoding
$this->mysqli->set_charset("utf8mb4");
	
        $sql = "SELECT client_id, client_name FROM clients WHERE is_active = '1' AND is_deleted = '0'";
        $result = $this->mysqli->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
		
		//print_r($data); die;

        return $data;
    }
}
